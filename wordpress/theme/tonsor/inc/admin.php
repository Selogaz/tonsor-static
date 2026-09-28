<?php
/**
 * Админ-UX: статическая страница-носитель полей главной, скрытие редактора на ней,
 * колонки и сортировка списков CPT, подключение установленных типов записей к
 * плагинам дублирования и ручного порядка (inc/post-types.php, inc/fields.php).
 */

defined('ABSPATH') || exit;

/** Типы записей, у которых порядок карточек на странице задаётся вручную (menu_order). */
function tns_orderable_post_types(): array
{
    return ['tns_hero_slide', 'tns_service', 'tns_work', 'tns_review', 'tns_barber', 'tns_faq'];
}

/**
 * ACF-поля главной живут на обычной WP-странице, отмеченной как статический фронт
 * сайта (location "Тип страницы == Главная" требует именно этого). Вывод страницы
 * всё равно отдаёт front-page.php — по шаблонной иерархии WP он приоритетнее page.php
 * независимо от настройки "Настройки чтения", так что переключение ничего не ломает.
 * Идемпотентно: создаёт страницу и включает show_on_front только один раз.
 */
add_action('init', function (): void {
    $front_page_id = (int) get_option('page_on_front');
    if ($front_page_id && get_post($front_page_id)) {
        return;
    }

    $front_page_id = wp_insert_post([
        'post_title' => 'Hlavní stránka',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_content' => '',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
    ], true);

    if (is_wp_error($front_page_id) || !$front_page_id) {
        return;
    }

    update_option('show_on_front', 'page');
    update_option('page_on_front', $front_page_id);
}, 20);

/**
 * На странице главной нет блочного редактора — весь контент только через вкладки
 * ACF. get_current_screen() на admin_init ещё не готов (set_current_screen() в
 * wp-admin/admin.php вызывается позже) — определяем экран через $pagenow и $_GET,
 * до того как wp-admin/post.php решит, грузить блочный редактор или нет.
 */
add_action('admin_init', function (): void {
    global $pagenow;

    if ('post.php' !== $pagenow || !isset($_GET['post'])) {
        return;
    }

    $post_id = (int) $_GET['post'];
    $front_page_id = (int) get_option('page_on_front');

    if ($post_id && $front_page_id && $post_id === $front_page_id && 'page' === get_post_type($post_id)) {
        remove_post_type_support('page', 'editor');
    }
});

/** Список колонок с миниатюрой (фичер-изображение или картинка из ACF-поля). */
function tns_post_types_with_thumbnail_column(): array
{
    return ['tns_hero_slide', 'tns_work', 'tns_barber', 'tns_review'];
}

/**
 * Колонка «Миниатюра» — фичер-изображение, где оно поддерживается (Работы, Барберы),
 * иначе первое доступное ACF-изображение записи (Слайды, Отзывы).
 */
add_action('admin_init', function (): void {
    foreach (tns_post_types_with_thumbnail_column() as $post_type) {
        add_filter("manage_{$post_type}_posts_columns", 'tns_add_thumbnail_column');
        add_action("manage_{$post_type}_posts_custom_column", 'tns_render_thumbnail_column', 10, 2);
    }

    add_filter('manage_tns_service_posts_columns', 'tns_add_service_columns');
    add_action('manage_tns_service_posts_custom_column', 'tns_render_service_column', 10, 2);

    add_filter('manage_tns_work_posts_columns', 'tns_add_work_columns');
    add_action('manage_tns_work_posts_custom_column', 'tns_render_work_column', 10, 2);

    add_filter('manage_tns_review_posts_columns', 'tns_add_review_columns');
    add_action('manage_tns_review_posts_custom_column', 'tns_render_review_column', 10, 2);
});

function tns_add_thumbnail_column(array $columns): array
{
    return array_merge(['tns_thumbnail' => 'Миниатюра'], $columns);
}

function tns_render_thumbnail_column(string $column, int $post_id): void
{
    if ('tns_thumbnail' !== $column) {
        return;
    }

    if (has_post_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, [48, 48], ['style' => 'width:48px;height:48px;object-fit:cover;']);
        return;
    }

    $post_type = get_post_type($post_id);
    $image_id = 'tns_hero_slide' === $post_type
        ? tns_field('image_desktop', $post_id)
        : tns_field('photo', $post_id);

    if ($image_id) {
        echo wp_get_attachment_image((int) $image_id, [48, 48], false, ['style' => 'width:48px;height:48px;object-fit:cover;']);
    }
}

function tns_add_service_columns(array $columns): array
{
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ('title' === $key) {
            $new['tns_price'] = 'Цена';
            $new['tns_price_from'] = '«od»';
        }
    }

    return $new;
}

function tns_render_service_column(string $column, int $post_id): void
{
    if ('tns_price' === $column) {
        $price = tns_field('price', $post_id);
        echo $price !== '' && $price !== null ? esc_html($price) . ' Kč' : '—';
    }

    if ('tns_price_from' === $column) {
        echo tns_field('price_from', $post_id) ? 'да' : '—';
    }
}

function tns_add_work_columns(array $columns): array
{
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ('title' === $key) {
            $new['tns_barber'] = 'Барбер';
        }
    }

    return $new;
}

function tns_render_work_column(string $column, int $post_id): void
{
    if ('tns_barber' !== $column) {
        return;
    }

    $barber = tns_field('barber', $post_id);
    echo $barber instanceof WP_Post ? esc_html(get_the_title($barber)) : '—';
}

function tns_add_review_columns(array $columns): array
{
    $new = [];
    foreach ($columns as $key => $label) {
        $new[$key] = $label;
        if ('title' === $key) {
            $new['tns_stars'] = 'Рейтинг';
        }
    }

    return $new;
}

function tns_render_review_column(string $column, int $post_id): void
{
    if ('tns_stars' !== $column) {
        return;
    }

    $stars = (int) tns_field('stars', $post_id);
    echo $stars > 0 ? esc_html(str_repeat('★', $stars) . str_repeat('☆', max(0, 5 - $stars))) : '—';
}

/**
 * Списки CPT сортируются по ручному порядку (menu_order), а не по дате — так задаёт
 * вывод на сайте (карточки идут в порядке, который выставляет клиент).
 */
add_action('pre_get_posts', function (WP_Query $query): void {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || 'edit' !== $screen->base || !in_array($screen->post_type, tns_orderable_post_types(), true)) {
        return;
    }

    if (!isset($_GET['orderby'])) {
        $query->set('orderby', 'menu_order title');
        $query->set('order', 'ASC');
    }
});

/**
 * Yoast Duplicate Post по умолчанию дублирует только "post"/"page" — включаем наши
 * типы записей фильтром, не трогая настройки плагина в БД.
 */
add_filter('duplicate_post_enabled_post_types', function (array $post_types): array {
    return array_values(array_unique(array_merge($post_types, tns_orderable_post_types())));
});

/**
 * Simple Custom Post Order хранит список сортируемых типов в своей опции — включаем
 * наши типы один раз, не трогая остальные настройки плагина (движок, роли и т.д.),
 * если админ их менял вручную.
 */
add_action('init', function (): void {
    $options = get_option('scporder_options', []);
    if (!is_array($options)) {
        $options = [];
    }

    $objects = isset($options['objects']) && is_array($options['objects']) ? $options['objects'] : [];
    $missing = array_diff(tns_orderable_post_types(), $objects);

    if (!$missing) {
        return;
    }

    $options['objects'] = array_values(array_unique(array_merge($objects, $missing)));
    update_option('scporder_options', $options);
}, 20);
