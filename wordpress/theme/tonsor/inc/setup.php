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

// Временный заголовок документа — 1:1 со статикой, до подключения SEO-плагина.
add_filter('document_title_parts', function (array $tns_parts): array {
    if (is_front_page()) {
        return ['title' => 'TONSOR — prémiový barbershop v Plzni'];
    }
    return $tns_parts;
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
