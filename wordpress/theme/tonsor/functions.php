<?php
/**
 * TONSOR — точка входа темы.
 *
 * Вся функциональность живёт в inc/*.php; здесь только подключение.
 */

defined('ABSPATH') || exit;

foreach (glob(__DIR__ . '/inc/*.php') as $tns_inc_file) {
    require_once $tns_inc_file;
}
