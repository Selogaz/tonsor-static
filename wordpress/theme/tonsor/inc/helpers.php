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

if (!function_exists('tns_field')) {
    /**
     * Значение поля ACF с деградацией на обычный post-мета, если ACF выключен —
     * тема не должна падать без плагина. $post_id принимает то же, что и get_field()
     * (ID записи, объект записи, "term_123", "option" и т.д.).
     *
     * @return mixed
     */
    function tns_field(string $name, $post_id = null)
    {
        if (function_exists('get_field')) {
            return get_field($name, $post_id);
        }

        $post_id = $post_id ?: get_the_ID();

        return get_post_meta((int) $post_id, $name, true);
    }
}
