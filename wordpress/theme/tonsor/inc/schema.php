<?php
/**
 * JSON-LD Schema.org. BarberShop/LocalBusiness собирается из полей контактов
 * главной страницы (footer.php печатает результат tns_schema_barbershop()).
 * FAQPage пока остаётся захардкоженной в footer.php — переедет сюда вместе со
 * списком записей вопросов-ответов.
 */

defined('ABSPATH') || exit;

if (!function_exists('tns_schema_barbershop')) {
    function tns_schema_barbershop(): string
    {
        $tns_front_id = tns_front_id();

        $tns_socials = array_values(array_filter([
            (string) tns_field('social_tiktok', $tns_front_id),
            (string) tns_field('social_facebook', $tns_front_id),
            (string) tns_field('social_instagram', $tns_front_id),
        ]));

        $tns_data = [
            '@context' => 'https://schema.org',
            '@type' => ['BarberShop', 'LocalBusiness'],
            'name' => 'TONSOR',
            'image' => tns_asset('img/common.tns/og.jpg'),
            'url' => home_url('/'),
            'telephone' => (string) tns_field('phone', $tns_front_id),
            'email' => (string) tns_field('email', $tns_front_id),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => (string) tns_field('schema_street', $tns_front_id),
                'postalCode' => (string) tns_field('schema_zip', $tns_front_id),
                'addressLocality' => (string) tns_field('schema_city', $tns_front_id),
                'addressCountry' => 'CZ',
            ],
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'opens' => (string) tns_field('schema_opens', $tns_front_id),
                'closes' => (string) tns_field('schema_closes', $tns_front_id),
            ],
        ];

        if ($tns_socials) {
            $tns_data['sameAs'] = $tns_socials;
        }

        $tns_maps_url = (string) tns_field('maps_url', $tns_front_id);
        if ($tns_maps_url) {
            $tns_data['hasMap'] = $tns_maps_url;
        }

        return (string) wp_json_encode($tns_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
