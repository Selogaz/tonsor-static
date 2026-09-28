<?php
/**
 * Главная страница — одностраничный лендинг. Собирает секции в порядке src/page-homepage.html.
 * Шаблон применяется WordPress к главной по иерархии шаблонов независимо от настройки
 * "Настройки чтения"; страница, отмеченная статическим фронтом сайта, служит только
 * носителем полей ACF (inc/admin.php) и не влияет на вывод — он всегда собирается отсюда.
 */

defined('ABSPATH') || exit;

get_header();

foreach (
    [
        'hero',
        'services',
        'gift',
        'care',
        'portfolio',
        'reviews',
        'booking',
        'team',
        'faq',
    ] as $tns_section
) {
    get_template_part('template-parts/sections/' . $tns_section);
}

get_footer();
