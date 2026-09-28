<?php
/**
 * Типы записей и таксономия для контента лендинга. Ни один тип не публичный —
 * у карточек нет отдельных страниц, архивов или ленты, весь вывод собирают
 * шаблоны главной. Поля — inc/fields.php, колонки и сортировка списков — inc/admin.php.
 */

defined('ABSPATH') || exit;

/**
 * Регистрирует один тип записи в общей для всех сущностей лендинга конфигурации:
 * без блочного редактора, не публичный, список сортируется вручную (menu_order).
 */
function tns_register_post_type(string $slug, string $singular, string $plural, string $icon, int $menu_position, array $supports): void
{
    $plural_lower = mb_strtolower($plural);

    register_post_type($slug, [
        'labels' => [
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'name_admin_bar' => $singular,
            'add_new' => 'Добавить',
            'add_new_item' => 'Добавить: ' . $singular,
            'edit_item' => 'Редактировать: ' . $singular,
            'new_item' => 'Новая запись: ' . $singular,
            'view_item' => 'Просмотреть запись',
            'view_items' => 'Просмотреть: ' . $plural_lower,
            'all_items' => $plural,
            'search_items' => 'Искать: ' . $plural_lower,
            'not_found' => 'Ничего не найдено',
            'not_found_in_trash' => 'В корзине пусто',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => false,
        'show_in_rest' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'has_archive' => false,
        'rewrite' => false,
        'query_var' => false,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => $menu_position,
        'menu_icon' => $icon,
        'supports' => $supports,
    ]);
}

add_action('init', function (): void {
    tns_register_post_type(
        'tns_hero_slide',
        'Слайд',
        'Слайды главного экрана',
        'dashicons-images-alt2',
        21,
        ['title', 'page-attributes']
    );

    tns_register_post_type(
        'tns_service',
        'Услуга',
        'Услуги',
        'dashicons-tag',
        22,
        ['title', 'page-attributes']
    );

    tns_register_post_type(
        'tns_work',
        'Работа',
        'Портфолио работ',
        'dashicons-format-gallery',
        23,
        ['title', 'thumbnail', 'page-attributes']
    );

    tns_register_post_type(
        'tns_review',
        'Отзыв',
        'Отзывы',
        'dashicons-star-filled',
        24,
        ['title', 'page-attributes']
    );

    tns_register_post_type(
        'tns_barber',
        'Барбер',
        'Барберы',
        'dashicons-groups',
        25,
        ['title', 'thumbnail', 'page-attributes']
    );

    tns_register_post_type(
        'tns_faq',
        'Вопрос',
        'Частые вопросы',
        'dashicons-editor-help',
        26,
        ['title', 'page-attributes']
    );

    register_taxonomy('tns_portfolio_cat', ['tns_work'], [
        'labels' => [
            'name' => 'Категории портфолио',
            'singular_name' => 'Категория портфолио',
            'menu_name' => 'Категории',
            'all_items' => 'Все категории',
            'edit_item' => 'Редактировать категорию',
            'view_item' => 'Просмотреть категорию',
            'update_item' => 'Обновить категорию',
            'add_new_item' => 'Добавить категорию',
            'new_item_name' => 'Название новой категории',
            'search_items' => 'Искать категории',
            'not_found' => 'Ничего не найдено',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'show_admin_column' => true,
        'hierarchical' => true,
        'query_var' => false,
        'rewrite' => false,
    ]);
});
