<?php
/**
 * Идемпотентный сид текущего контента лендинга (содержимое — content.php) в базу
 * WordPress: записи типов записей, термины таксономии портфолио, медиа, поля главной
 * страницы. Повторный запуск находит уже засеянное по служебной мете и обновляет
 * поля актуальными значениями из content.php, не создавая дублей.
 *
 * Полная пересидка (удалить всё засеянное и залить заново) — переменная окружения
 * TNS_SEED_RESET=1.
 *
 * Запуск: wp eval-file /seed/seed.php (обёртка — bin/seed.sh).
 */

defined('ABSPATH') || exit;

if (!function_exists('acf_add_local_field_group') || !function_exists('update_field')) {
    WP_CLI::error('ACF не активен — сид полей невозможен.');
}

/** @var array<string,mixed> $content */
$content = require __DIR__ . '/content.php';

/**
 * Счётчики сводки — статическая переменная функции, а не глобальная: `wp eval-file`
 * исполняет содержимое файла внутри собственной функции-обёртки, поэтому обычный
 * `global $stats;` из вложенных функций тут указывает не на ту область видимости.
 * Возврат по ссылке даёт вызывающему коду мутировать один и тот же массив.
 */
function &tns_seed_stats(): array
{
    static $stats = [
        'posts_created' => 0,
        'posts_updated' => 0,
        'media_created' => 0,
        'media_skipped' => 0,
        'terms_created' => 0,
        'terms_updated' => 0,
    ];

    return $stats;
}

$seeded_post_types = ['tns_hero_slide', 'tns_service', 'tns_work', 'tns_review', 'tns_barber', 'tns_faq'];

if (getenv('TNS_SEED_RESET') === '1') {
    WP_CLI::log('==> TNS_SEED_RESET=1 — удаляю ранее засеянные записи и вложения...');

    foreach ($seeded_post_types as $post_type) {
        $ids = get_posts([
            'post_type' => $post_type,
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_key' => '_tns_seed_key',
        ]);
        foreach ($ids as $id) {
            wp_delete_post((int) $id, true);
        }
    }

    $attachment_ids = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => '_tns_seed_src',
    ]);
    foreach ($attachment_ids as $id) {
        wp_delete_attachment((int) $id, true);
    }
}

/**
 * Ищет запись, ранее засеянную под этим служебным ключом (в рамках указанного типа).
 */
function tns_seed_find_post(string $seed_key, string $post_type): ?int
{
    $found = get_posts([
        'post_type' => $post_type,
        'post_status' => 'any',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_key' => '_tns_seed_key',
        'meta_value' => $seed_key,
    ]);

    return $found ? (int) $found[0] : null;
}

/**
 * Заводит или обновляет запись CPT по служебному ключу и переписывает её ACF-поля
 * значениями из content.php. $postarr — как для wp_insert_post() (без ID/post_type).
 *
 * @return array{0:int,1:string} [ID записи, "created"|"updated"|"error"]
 */
function tns_seed_upsert_post(string $seed_key, string $post_type, array $postarr, array $acf_fields = []): array
{
    $stats = &tns_seed_stats();

    $existing_id = tns_seed_find_post($seed_key, $post_type);
    $postarr['post_type'] = $post_type;

    if ($existing_id) {
        $postarr['ID'] = $existing_id;
        $post_id = wp_update_post($postarr, true);
        $status = 'updated';
    } else {
        $postarr['post_status'] = $postarr['post_status'] ?? 'publish';
        $post_id = wp_insert_post($postarr, true);
        $status = 'created';
    }

    if (is_wp_error($post_id) || !$post_id) {
        $message = is_wp_error($post_id) ? $post_id->get_error_message() : 'неизвестная ошибка';
        WP_CLI::warning("Не удалось сохранить «$seed_key»: $message");

        return [0, 'error'];
    }

    $post_id = (int) $post_id;
    update_post_meta($post_id, '_tns_seed_key', $seed_key);

    foreach ($acf_fields as $field => $value) {
        update_field($field, $value, $post_id);
    }

    $stats['posts_' . $status]++;

    return [$post_id, $status];
}

/**
 * Заливает медиафайл из /seed-img (read-only том src/img) один раз — по относительному
 * пути. Повторные запуски находят существующее вложение и только обновляют alt-текст.
 */
function tns_seed_media(string $rel_path, string $alt): int
{
    $stats = &tns_seed_stats();

    $existing = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'any',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_key' => '_tns_seed_src',
        'meta_value' => $rel_path,
    ]);

    if ($existing) {
        $attachment_id = (int) $existing[0];
        update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);
        $stats['media_skipped']++;

        return $attachment_id;
    }

    $source_path = '/seed-img/' . ltrim($rel_path, '/');

    if (!is_readable($source_path)) {
        WP_CLI::warning("Файл не найден: $source_path");

        return 0;
    }

    $filename = wp_unique_filename(wp_upload_dir()['path'], basename($rel_path));
    $filedata = file_get_contents($source_path);
    $upload = wp_upload_bits($filename, null, $filedata);

    if (!empty($upload['error'])) {
        WP_CLI::warning("Не удалось сохранить $rel_path: {$upload['error']}");

        return 0;
    }

    $filetype = wp_check_filetype($upload['file']);
    $attachment_id = wp_insert_attachment([
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_file_name(pathinfo($rel_path, PATHINFO_FILENAME)),
        'post_status' => 'inherit',
        'post_content' => '',
    ], $upload['file']);

    if (is_wp_error($attachment_id) || !$attachment_id) {
        WP_CLI::warning("Не удалось создать вложение для $rel_path");

        return 0;
    }

    $attachment_id = (int) $attachment_id;

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $metadata = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    wp_update_attachment_metadata($attachment_id, $metadata);

    update_post_meta($attachment_id, '_tns_seed_src', $rel_path);
    update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);

    $stats['media_created']++;

    return $attachment_id;
}

/** Находит или заводит термин таксономии портфолио по слагу, пишет ACF-поле "order". */
function tns_seed_term(string $slug, string $name, int $order): int
{
    $stats = &tns_seed_stats();

    $term = get_term_by('slug', $slug, 'tns_portfolio_cat');

    if ($term) {
        if ($term->name !== $name) {
            wp_update_term($term->term_id, 'tns_portfolio_cat', ['name' => $name]);
        }
        $term_id = (int) $term->term_id;
        $stats['terms_updated']++;
    } else {
        $inserted = wp_insert_term($name, 'tns_portfolio_cat', ['slug' => $slug]);
        if (is_wp_error($inserted)) {
            WP_CLI::warning("Не удалось создать термин $slug: " . $inserted->get_error_message());

            return 0;
        }
        $term_id = (int) $inserted['term_id'];
        $stats['terms_created']++;
    }

    update_field('order', $order, 'tns_portfolio_cat_' . $term_id);

    return $term_id;
}

// ---- Главная страница (создаётся идемпотентно инициализацией темы — inc/admin.php) ----
$front_page_id = (int) get_option('page_on_front');
if (!$front_page_id || 'page' !== get_post_type($front_page_id)) {
    WP_CLI::error('Страница «Hlavní stránka» ещё не создана — активируй тему tonsor и повтори.');
}

foreach ($content['front_page']['text'] as $field => $value) {
    update_field($field, $value, $front_page_id);
}
foreach ($content['front_page']['links'] as $field => $value) {
    update_field($field, $value, $front_page_id);
}
foreach ($content['front_page']['groups'] as $field => $value) {
    update_field($field, $value, $front_page_id);
}
foreach ($content['front_page']['images'] as $field => $image) {
    $media_id = tns_seed_media($image['src'], $image['alt']);
    if ($media_id) {
        update_field($field, $media_id, $front_page_id);
    }
}
WP_CLI::log('==> Поля главной страницы записаны.');

// ---- SEO (Yoast): заголовок/описание/OG-картинка главной, данные организации и соцпрофили ----
if (function_exists('YoastSEO')) {
    $tns_seo = $content['front_page']['seo'];

    update_post_meta($front_page_id, '_yoast_wpseo_title', $tns_seo['title']);
    update_post_meta($front_page_id, '_yoast_wpseo_metadesc', $tns_seo['description']);

    $tns_seo_image_id = tns_seed_media($tns_seo['image']['src'], $tns_seo['image']['alt']);
    if ($tns_seo_image_id) {
        // Ключ на ID картинки (`_yoast_wpseo_opengraph-image-id`) в текущей версии Yoast —
        // только внутренний кэш индексируемого объекта, сама плитка на фронте строится по
        // URL-ключу ниже; хранить его через wp_postmeta напрямую не нужно.
        $tns_seo_image_url = (string) wp_get_attachment_url($tns_seo_image_id);
        update_post_meta($front_page_id, '_yoast_wpseo_opengraph-image', $tns_seo_image_url);

        // Тот же кадр — запасная OG-картинка для случаев без собственной (у сайта сейчас
        // только одна публичная страница, но настройка сайтовая и переживает добавление новых).
        $tns_wpseo_social = get_option('wpseo_social', []);
        $tns_wpseo_social['og_default_image_id'] = $tns_seo_image_id;
        $tns_wpseo_social['og_default_image'] = $tns_seo_image_url;
        update_option('wpseo_social', $tns_wpseo_social);
    }

    // Соцпрофили и «организация» для графа Yoast (Organization уживается с нашей
    // BarberShop/FAQPage JSON-LD — разные узлы, конфликта типов нет).
    $tns_wpseo_social = get_option('wpseo_social', []);
    $tns_wpseo_social['facebook_site'] = (string) ($content['front_page']['text']['social_facebook'] ?? '');
    $tns_wpseo_social['instagram_url'] = (string) ($content['front_page']['text']['social_instagram'] ?? '');
    $tns_wpseo_social['other_social_urls'] = array_values(array_filter([
        (string) ($content['front_page']['text']['social_tiktok'] ?? ''),
    ]));
    update_option('wpseo_social', $tns_wpseo_social);

    $tns_wpseo_titles = get_option('wpseo_titles', []);
    $tns_wpseo_titles['company_or_person'] = 'company';
    $tns_wpseo_titles['company_name'] = get_bloginfo('name');
    update_option('wpseo_titles', $tns_wpseo_titles);

    WP_CLI::log('==> SEO главной страницы (Yoast) записано.');
} else {
    WP_CLI::warning('Yoast SEO не активен — пропускаю сид SEO-полей главной страницы.');
}

// ---- Слайды главного экрана ----
foreach ($content['hero_slides'] as $slide) {
    $acf = [
        'heading' => $slide['heading'],
        'eyebrow' => $slide['eyebrow'],
        'lead' => $slide['lead'],
        'cta' => $slide['cta'],
    ];

    $desktop_id = tns_seed_media($slide['image_desktop']['src'], $slide['image_desktop']['alt']);
    if ($desktop_id) {
        $acf['image_desktop'] = $desktop_id;
    }
    if (!empty($slide['image_mobile']['src'])) {
        $mobile_id = tns_seed_media($slide['image_mobile']['src'], $slide['image_mobile']['alt']);
        if ($mobile_id) {
            $acf['image_mobile'] = $mobile_id;
        }
    }

    tns_seed_upsert_post($slide['seed_key'], 'tns_hero_slide', [
        'post_title' => $slide['title'],
        'menu_order' => $slide['menu_order'],
    ], $acf);
}
WP_CLI::log('==> Слайды главного экрана готовы.');

// ---- Категории портфолио (термины — раньше услуг и работ, которые на них ссылаются) ----
$term_id_by_slug = [];
foreach ($content['portfolio_terms'] as $term) {
    $term_id_by_slug[$term['slug']] = tns_seed_term($term['slug'], $term['name'], $term['order']);
}
WP_CLI::log('==> Категории портфолио готовы.');

// ---- Услуги ----
foreach ($content['services'] as $service) {
    $term_id = $service['portfolio_term_slug'] ? ($term_id_by_slug[$service['portfolio_term_slug']] ?? 0) : 0;

    tns_seed_upsert_post($service['seed_key'], 'tns_service', [
        'post_title' => $service['title'],
        'menu_order' => $service['menu_order'],
    ], [
        'price' => $service['price'],
        'price_from' => $service['price_from'],
        'fresha_url' => $service['fresha_url'],
        'portfolio_term' => $term_id ?: '',
    ]);
}
WP_CLI::log('==> Услуги готовы.');

// ---- Барберы (раньше работ портфолио, которые на них ссылаются) ----
$barber_id_by_key = [];
foreach ($content['barbers'] as $barber) {
    $image_id = tns_seed_media($barber['image']['src'], $barber['image']['alt']);

    [$post_id] = tns_seed_upsert_post($barber['seed_key'], 'tns_barber', [
        'post_title' => $barber['title'],
        'menu_order' => $barber['menu_order'],
    ], [
        'caption' => $barber['caption'],
    ]);

    if ($post_id && $image_id) {
        set_post_thumbnail($post_id, $image_id);
    }

    $barber_id_by_key[$barber['seed_key']] = $post_id;
}
WP_CLI::log('==> Барберы готовы.');

// ---- Работы портфолио (каждая работа — во всех 5 категориях) ----
$all_term_ids = array_values($term_id_by_slug);
foreach ($content['portfolio_works'] as $work) {
    $image_id = tns_seed_media($work['image']['src'], $work['image']['alt']);
    $barber_id = $barber_id_by_key[$work['barber_seed_key']] ?? 0;

    [$post_id] = tns_seed_upsert_post($work['seed_key'], 'tns_work', [
        'post_title' => $work['title'],
        'menu_order' => $work['menu_order'],
    ], [
        'barber' => $barber_id ?: '',
    ]);

    if ($post_id) {
        if ($image_id) {
            set_post_thumbnail($post_id, $image_id);
        }
        wp_set_object_terms($post_id, $all_term_ids, 'tns_portfolio_cat');
        // Базовый menu_order на момент сида — tns_portfolio_reorder() сверяет с ним
        // текущий menu_order записи, чтобы понять, перетаскивал ли клиент карточку
        // руками (inc/helpers.php → tns_portfolio_order_is_default()).
        update_post_meta($post_id, '_tns_seed_menu_order', $work['menu_order']);
    }
}
WP_CLI::log('==> Работы портфолио готовы.');

// ---- Отзывы ----
foreach ($content['reviews'] as $review) {
    tns_seed_upsert_post($review['seed_key'], 'tns_review', [
        'post_title' => $review['title'],
        'menu_order' => $review['menu_order'],
    ], [
        'text' => $review['text'],
        'stars' => $review['stars'],
        'service' => $review['service'],
    ]);
}
WP_CLI::log('==> Отзывы готовы.');

// ---- Частые вопросы ----
foreach ($content['faq'] as $item) {
    tns_seed_upsert_post($item['seed_key'], 'tns_faq', [
        'post_title' => $item['title'],
        'menu_order' => $item['menu_order'],
    ], [
        'answer' => $item['answer'],
    ]);
}
WP_CLI::log('==> Частые вопросы готовы.');

$final_stats = tns_seed_stats();
WP_CLI::success(sprintf(
    'Сид завершён. Записи: создано %d, обновлено %d. Медиа: создано %d, пропущено (уже были) %d. Термины: создано %d, обновлено %d.',
    $final_stats['posts_created'],
    $final_stats['posts_updated'],
    $final_stats['media_created'],
    $final_stats['media_skipped'],
    $final_stats['terms_created'],
    $final_stats['terms_updated']
));
