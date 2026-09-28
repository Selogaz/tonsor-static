<?php
/**
 * Подключение ассетов, собранных `npm run wp:build` в assets/css/style.css и
 * assets/js/index.min.js. Версия — filemtime, чтобы браузер подхватывал пересборку без ручной
 * правки версии.
 */

defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function (): void {
    $tns_style_rel  = 'assets/css/style.css';
    $tns_script_rel = 'assets/js/index.min.js';

    $tns_style_path  = get_theme_file_path($tns_style_rel);
    $tns_script_path = get_theme_file_path($tns_script_rel);

    wp_enqueue_style(
        'tonsor',
        get_theme_file_uri($tns_style_rel),
        [],
        file_exists($tns_style_path) ? (string) filemtime($tns_style_path) : null
    );

    wp_enqueue_script(
        'tonsor',
        get_theme_file_uri($tns_script_rel),
        [],
        file_exists($tns_script_path) ? (string) filemtime($tns_script_path) : null,
        [
            'strategy'  => 'defer',
            'in_footer' => true,
        ]
    );
});
