<?php
/**
 * Базовая настройка темы: поддержка возможностей WP, отключение фронтенд-мусора ядра,
 * который вёрстка не использует (эмодзи-полифилл, блочные стили, oEmbed, RSD/generator, XML-RPC).
 */

defined('ABSPATH') || exit;

add_action('after_setup_theme', function (): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
    ]);
});

// Контентные фото (hero, Rezervace) физически шире порога WP «big_image_size_threshold»
// (2560px, напр. hero — 3840×1510 @2x): без этого фильтра WP тихо подменяет "full" размер
// на пересжатый и уменьшенный «-scaled» файл при загрузке — вывод секций через tns_picture()
// (inc/helpers.php) в этом случае перестаёт быть побайтово тем же файлом, что использует
// статика, и паритет-сверка (wp-parity.py) на узких экранах даёт ложный дифф из-за подмены
// файла в срезе srcset-кандидатов. Не масштабируем оригинал вообще — картинки этого проекта
// уже заранее подготовлены под экспорт x2, лишний уровень пересжатия не нужен.
add_filter('big_image_size_threshold', '__return_false');

// WebP для сгенерированных подразмеров (админ-миниатюры, `wp_get_attachment_image_src()` с
// именованным размером вроде 'thumbnail' — сейчас это аватар отзыва) — если сама среда это
// умеет. НЕ через стандартный фильтр `image_editor_output_format`: ядро включает по нему и
// ОРИГИНАЛ ("full") тоже — если новый mime для исходного файла отличается от текущего, WP
// молча подменяет сам загруженный файл на сконвертированную версию ещё ДО генерации
// подразмеров (wp_create_image_subsizes() в wp-admin/includes/image.php, ветка `$convert`),
// то есть `tns_picture()` (inc/helpers.php) на любой новой заливке перестал бы отдавать
// байт-в-байт тот же файл, что загрузил клиент, — риск для паритета сопоставимый с тем, что
// уже закрыт фильтром big_image_size_threshold выше. Поэтому конвертируем сами и только
// готовые подразмеры — уже ПОСЛЕ того, как WP сгенерировал их в исходном формате; запись
// 'file' (оригинал) в метаданных вложения не трогаем вообще.
add_filter('wp_generate_attachment_metadata', function (array $metadata, int $attachment_id): array {
    if (empty($metadata['sizes']) || !is_array($metadata['sizes'])) {
        return $metadata;
    }

    if (!wp_image_editor_supports(['mime_type' => 'image/webp'])) {
        return $metadata;
    }

    $upload_dir = trailingslashit(dirname(get_attached_file($attachment_id)));

    foreach ($metadata['sizes'] as &$size) {
        if (empty($size['file']) || !in_array($size['mime-type'] ?? '', ['image/jpeg', 'image/png'], true)) {
            continue;
        }

        $source_path = $upload_dir . $size['file'];
        $editor = wp_get_image_editor($source_path);
        if (is_wp_error($editor)) {
            continue;
        }

        $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $source_path);
        $saved = $editor->save($webp_path, 'image/webp');
        if (is_wp_error($saved)) {
            continue;
        }

        if ($source_path !== $webp_path) {
            @unlink($source_path);
        }

        $size['file'] = $saved['file'];
        $size['mime-type'] = $saved['mime-type'];
        if (isset($saved['filesize'])) {
            $size['filesize'] = $saved['filesize'];
        }
    }
    unset($size);

    return $metadata;
}, 20, 2);

// Комментариев на сайте нет нигде: ни у CPT (не в 'supports'), ни у обычных страниц
// (единственная — носитель полей главной, комментарии на ней и так закрыты при создании,
// inc/admin.php). Условие closed — на случай, если у какой-то записи статус вручную поменяют
// в БД — форма/ответы всё равно нигде не всплывут.
remove_post_type_support('page', 'comments');
remove_post_type_support('page', 'trackbacks');
add_filter('comments_open', '__return_false');
add_filter('pings_open', '__return_false');

// Заголовок и описание документа отдаёт SEO-плагин (поля страницы «Hlavní stránka»);
// без плагина WP использует стандартный title-tag с заголовком записи/страницы.

// Контент сайта — чешский независимо от языка интерфейса админки: OG-локаль в разметке
// всегда cs_CZ, даже когда общий языковой параметр WP (интерфейс/переводы плагинов) — ru_RU.
add_filter('wpseo_locale', function (): string {
    return 'cs_CZ';
});

// Emoji-скрипты/стили ядра — вёрстка не использует эмодзи-полифилл.
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

// Стили блочного редактора и глобальные стили темы — своя SCSS-сборка их не использует.
// С WP 6.9 `global-styles` у классических тем печатается не тут, а на wp_footer (ticket
// core-64099, «hoist late-printed styles») — снимаем на обоих хуках.
add_action('wp_enqueue_scripts', function (): void {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('wp-global-styles-placeholder');
}, 100);

add_action('wp_footer', function (): void {
    wp_dequeue_style('global-styles');
    wp_dequeue_style('wp-global-styles-placeholder');
}, 2);

// oEmbed discovery — не используется.
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');

// RSD/wlwmanifest/generator — служебные метки ядра, не нужны на публичном сайте.
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');

// XML-RPC не используется — отключаем поверхность атаки.
add_filter('xmlrpc_enabled', '__return_false');
