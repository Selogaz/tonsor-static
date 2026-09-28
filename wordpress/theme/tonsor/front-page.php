<?php
/**
 * Главная страница — одностраничный лендинг. Собирает секции в порядке src/page-homepage.html.
 * front-page.php применяется WP к главной независимо от show_on_front (план §5) — отдельная
 * статическая страница «на главную» не заводится.
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
