<?php
/**
 * Общие хелперы темы TONSOR.
 */

defined('ABSPATH') || exit;

if (!function_exists('tns_asset')) {
    /**
     * URL файла в assets/ темы (картинки/шрифты/спрайт сборки).
     */
    function tns_asset(string $path): string
    {
        return get_theme_file_uri('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('tns_sprite')) {
    /**
     * URL символа SVG-спрайта: tns_sprite('arrow-left') → .../sprite.svg#arrow-left-sym
     */
    function tns_sprite(string $id): string
    {
        return tns_asset('img/sprite.svg') . '#' . $id . '-sym';
    }
}
