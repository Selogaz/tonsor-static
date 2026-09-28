<?php
/**
 * Группы полей ACF (регистрация в PHP, не acf-json): модель данных живёт в коде,
 * одинаково поднимается локально и на проде, клиент не может случайно сломать
 * структуру группы в интерфейсе. Тема не должна падать при выключенном ACF —
 * вся регистрация идёт через хук acf/include_fields, который без ACF не срабатывает.
 */

defined('ABSPATH') || exit;

add_action('acf/include_fields', function (): void {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // ---- фабрики часто повторяющихся типов полей верхнего уровня ----
    $tns_text = function (string $name, string $label, array $extra = []): array {
        return array_merge([
            'key' => 'field_tns_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'text',
        ], $extra);
    };
    $tns_textarea = function (string $name, string $label, array $extra = []): array {
        return array_merge([
            'key' => 'field_tns_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'textarea',
            'rows' => 3,
            'new_lines' => '',
        ], $extra);
    };
    $tns_url = function (string $name, string $label, array $extra = []): array {
        return array_merge([
            'key' => 'field_tns_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'url',
        ], $extra);
    };
    $tns_number = function (string $name, string $label, array $extra = []): array {
        return array_merge([
            'key' => 'field_tns_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'number',
        ], $extra);
    };
    $tns_true_false = function (string $name, string $label, array $extra = []): array {
        return array_merge([
            'key' => 'field_tns_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'true_false',
            'ui' => 1,
        ], $extra);
    };
    $tns_image = function (string $name, string $label, array $extra = []): array {
        return array_merge([
            'key' => 'field_tns_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'image',
            'return_format' => 'id',
            'preview_size' => 'medium',
        ], $extra);
    };
    $tns_link = function (string $name, string $label, array $extra = []): array {
        return array_merge([
            'key' => 'field_tns_' . $name,
            'label' => $label,
            'name' => $name,
            'type' => 'link',
            'return_format' => 'array',
        ], $extra);
    };
    $tns_tab = function (string $name, string $label): array {
        return [
            'key' => 'field_tns_tab_' . $name,
            'label' => $label,
            'type' => 'tab',
        ];
    };

    // ---- Слайды главного экрана (tns_hero_slide) ----
    acf_add_local_field_group([
        'key' => 'group_tns_hero_slide',
        'title' => 'Слайд',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'tns_hero_slide']]],
        'fields' => [
            $tns_textarea('heading', 'Заголовок слайда', [
                'instructions' => 'Акцент — в звёздочках: *слово*. Первый по порядку опубликованный слайд выводится как заголовок H1, остальные — тем же по виду текстом.',
                'required' => 1,
                'rows' => 2,
                'maxlength' => 160,
            ]),
            $tns_text('eyebrow', 'Надглавие', [
                'instructions' => 'Короткая строка над заголовком, например «– Prémiový barbershop v Plzni».',
                'maxlength' => 80,
            ]),
            $tns_text('lead', 'Подзаголовок', [
                'instructions' => 'Строка под заголовком.',
                'maxlength' => 120,
            ]),
            $tns_link('cta', 'Кнопка слайда', [
                'instructions' => 'Необязательно. Если не заполнено — используется общая ссылка на бронирование (вкладка «Общее / Fresha» настроек главной страницы).',
            ]),
            $tns_image('image_desktop', 'Фото, версия для широкого экрана', [
                'instructions' => 'Обязательно. Рекомендуемый размер 1920×755 px.',
                'required' => 1,
            ]),
            $tns_image('image_mobile', 'Фото, версия для телефона', [
                'instructions' => 'Необязательно — если не загружено, используется фото для широкого экрана. Рекомендуемый размер 320×293 px.',
            ]),
        ],
    ]);

    // ---- Услуги (tns_service) ----
    acf_add_local_field_group([
        'key' => 'group_tns_service',
        'title' => 'Услуга',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'tns_service']]],
        'fields' => [
            $tns_number('price', 'Цена, Kč', [
                'instructions' => 'Целое число без разделителей и обозначения валюты, например 500.',
                'required' => 1,
                'min' => 0,
                'step' => 1,
            ]),
            $tns_true_false('price_from', 'Приставка «od» перед ценой', [
                'instructions' => 'Включите, если реальная стоимость услуги — «от» указанной суммы.',
                'default_value' => 0,
            ]),
            $tns_url('fresha_url', 'Ссылка на запись (Fresha)', [
                'instructions' => 'Ссылка на бронирование именно этой услуги. Пусто — кнопка «Rezervovat» ведёт на общую ссылку бронирования (вкладка «Общее / Fresha» настроек главной страницы).',
            ]),
            [
                'key' => 'field_tns_portfolio_term',
                'label' => 'Фильтр портфолио',
                'name' => 'portfolio_term',
                'type' => 'taxonomy',
                'instructions' => 'Категория портфолио, которая откроется по кнопке «Ukázky práce» у этой услуги. Пусто — кнопки «Ukázky práce» не будет.',
                'taxonomy' => 'tns_portfolio_cat',
                'field_type' => 'select',
                'allow_null' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'multiple' => 0,
                'return_format' => 'id',
            ],
        ],
    ]);

    // ---- Портфолио работ (tns_work) ----
    acf_add_local_field_group([
        'key' => 'group_tns_work',
        'title' => 'Работа портфолио',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'tns_work']]],
        'fields' => [
            [
                'key' => 'field_tns_work_barber',
                'label' => 'Барбер',
                'name' => 'barber',
                'type' => 'post_object',
                'instructions' => 'Необязательно. Барбер, который сделал эту работу, — его имя выводится подписью под фото.',
                'post_type' => ['tns_barber'],
                'allow_null' => 1,
                'multiple' => 0,
                'ui' => 1,
                'return_format' => 'object',
            ],
        ],
    ]);

    // ---- Категория портфолио (термин таксономии tns_portfolio_cat) ----
    acf_add_local_field_group([
        'key' => 'group_tns_portfolio_cat_term',
        'title' => 'Категория портфолио',
        'location' => [[['param' => 'taxonomy', 'operator' => '==', 'value' => 'tns_portfolio_cat']]],
        'fields' => [
            $tns_number('order', 'Порядок', [
                'instructions' => 'Меньшее число — левее в табах на широком экране и выше в списке на телефоне.',
                'default_value' => 0,
                'step' => 1,
            ]),
        ],
    ]);

    // ---- Барберы (tns_barber) ----
    acf_add_local_field_group([
        'key' => 'group_tns_barber',
        'title' => 'Барбер',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'tns_barber']]],
        'fields' => [
            $tns_text('caption', 'Подпись под фото', [
                'instructions' => 'Например «Barber Zachar». Если оставить пустым, подпись собирается автоматически как «Barber {имя барбера}».',
                'maxlength' => 60,
            ]),
        ],
    ]);

    // ---- Отзывы (tns_review) ----
    acf_add_local_field_group([
        'key' => 'group_tns_review',
        'title' => 'Отзыв',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'tns_review']]],
        'fields' => [
            $tns_textarea('text', 'Текст отзыва', [
                'instructions' => 'Без кавычек — на сайте они добавляются вокруг текста автоматически.',
                'required' => 1,
                'rows' => 4,
                'maxlength' => 600,
            ]),
            $tns_number('stars', 'Рейтинг (звёзды)', [
                'instructions' => 'От 1 до 5.',
                'required' => 1,
                'default_value' => 5,
                'min' => 1,
                'max' => 5,
                'step' => 1,
            ]),
            $tns_text('service', 'Услуга', [
                'instructions' => 'Например «Střih / úprava vousů» — что именно заказывал клиент.',
                'maxlength' => 80,
            ]),
            $tns_image('photo', 'Фото автора', [
                'instructions' => 'Необязательно. Без фото аватар отзыва показывает первую букву имени автора на цветной подложке.',
            ]),
        ],
    ]);

    // ---- Частые вопросы (tns_faq) ----
    acf_add_local_field_group([
        'key' => 'group_tns_faq',
        'title' => 'Вопрос',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'tns_faq']]],
        'fields' => [
            $tns_textarea('answer', 'Ответ', [
                'required' => 1,
                'rows' => 4,
                'maxlength' => 500,
            ]),
        ],
    ]);

    // ---- Главная страница (статическая страница, отмеченная фронтом сайта) ----
    acf_add_local_field_group([
        'key' => 'group_tns_front_page',
        'title' => 'Главная страница',
        'location' => [[['param' => 'page_type', 'operator' => '==', 'value' => 'front_page']]],
        'fields' => [
            $tns_tab('general', 'Общее / Fresha'),
            $tns_url('fresha_general', 'Общая ссылка на бронирование (Fresha)', [
                'instructions' => 'Используется в шапке, в hero (если у слайда нет своей кнопки) и в баннере бронирования.',
                'required' => 1,
            ]),
            $tns_url('fresha_gift', 'Ссылка на пакеты подарочных сертификатов (Fresha)', [
                'required' => 1,
            ]),

            $tns_tab('promo', 'Промо-полоса'),
            $tns_text('promo_text', 'Текст промо-полосы', [
                'instructions' => 'Пусто — промо-полоса над шапкой не показывается.',
                'maxlength' => 160,
            ]),

            $tns_tab('header', 'Шапка'),
            $tns_text('header_cta_text', 'Текст кнопки «Rezervovat» в шапке', [
                'required' => 1,
                'maxlength' => 40,
            ]),

            $tns_tab('services', 'Услуги (Služby)'),
            $tns_text('services_title', 'Заголовок секции', [
                'instructions' => 'Акцент — в звёздочках, например «*Služby* a ceny».',
                'required' => 1,
                'maxlength' => 100,
            ]),

            $tns_tab('gift', 'Подарочный сертификат (Dárkový poukaz)'),
            $tns_text('gift_title', 'Заголовок секции', [
                'instructions' => 'Акцент — в звёздочках.',
                'required' => 1,
                'maxlength' => 100,
            ]),
            $tns_textarea('gift_lead', 'Вводный текст', [
                'maxlength' => 200,
            ]),
            $tns_text('gift_price', 'Строка цены', [
                'instructions' => 'Например «Od 500 Kč».',
                'maxlength' => 40,
            ]),
            $tns_textarea('gift_checks', 'Пункты списка (галочки)', [
                'instructions' => 'Каждый пункт — с новой строки.',
                'rows' => 3,
            ]),
            $tns_text('gift_cta_text', 'Текст кнопки', [
                'instructions' => 'Сама ссылка кнопки — на вкладке «Общее / Fresha» настроек главной страницы.',
                'maxlength' => 60,
            ]),
            $tns_image('gift_image_desktop', 'Фото, версия для широкого экрана', [
                'instructions' => 'Обязательно. Рекомендуемый размер 968×680 px.',
                'required' => 1,
            ]),
            $tns_image('gift_image_mobile', 'Фото, версия для телефона', [
                'instructions' => 'Необязательно — если не загружено, используется фото для широкого экрана.',
            ]),

            $tns_tab('care', 'О нас (Pečujeme)'),
            $tns_text('care_title', 'Заголовок секции', [
                'instructions' => 'Акцент — в звёздочках.',
                'required' => 1,
                'maxlength' => 100,
            ]),
            $tns_image('care_image_1', 'Фото интерьера 1', [
                'instructions' => 'Обязательно. Рекомендуемый размер 721×362 px.',
                'required' => 1,
            ]),
            $tns_image('care_image_2', 'Фото интерьера 2', [
                'instructions' => 'Обязательно. Рекомендуемый размер 721×362 px.',
                'required' => 1,
            ]),
            $tns_image('care_image_mobile', 'Фото интерьера, версия для телефона', [
                'instructions' => 'Необязательно — если не загружено, используется «Фото интерьера 1».',
            ]),
            [
                'key' => 'field_tns_care_pillar_1',
                'label' => 'Столп 1',
                'name' => 'care_pillar_1',
                'type' => 'group',
                'sub_fields' => [
                    [
                        'key' => 'field_tns_care_pillar_1_title',
                        'label' => 'Заголовок',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                        'maxlength' => 60,
                    ],
                    [
                        'key' => 'field_tns_care_pillar_1_items',
                        'label' => 'Пункты списка',
                        'name' => 'items',
                        'type' => 'textarea',
                        'instructions' => 'Каждый пункт — с новой строки.',
                        'rows' => 4,
                    ],
                ],
            ],
            [
                'key' => 'field_tns_care_pillar_2',
                'label' => 'Столп 2',
                'name' => 'care_pillar_2',
                'type' => 'group',
                'sub_fields' => [
                    [
                        'key' => 'field_tns_care_pillar_2_title',
                        'label' => 'Заголовок',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                        'maxlength' => 60,
                    ],
                    [
                        'key' => 'field_tns_care_pillar_2_items',
                        'label' => 'Пункты списка',
                        'name' => 'items',
                        'type' => 'textarea',
                        'instructions' => 'Каждый пункт — с новой строки.',
                        'rows' => 4,
                    ],
                ],
            ],
            [
                'key' => 'field_tns_care_pillar_3',
                'label' => 'Столп 3 (акцентный)',
                'name' => 'care_pillar_3',
                'type' => 'group',
                'sub_fields' => [
                    [
                        'key' => 'field_tns_care_pillar_3_title',
                        'label' => 'Заголовок',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                        'maxlength' => 60,
                    ],
                    [
                        'key' => 'field_tns_care_pillar_3_items',
                        'label' => 'Пункты списка',
                        'name' => 'items',
                        'type' => 'textarea',
                        'instructions' => 'Каждый пункт — с новой строки.',
                        'rows' => 4,
                    ],
                ],
            ],

            $tns_tab('portfolio', 'Портфолио'),
            $tns_text('portfolio_title', 'Заголовок секции', [
                'instructions' => 'Акцент — в звёздочках.',
                'required' => 1,
                'maxlength' => 100,
            ]),

            $tns_tab('reviews', 'Отзывы (Recenze)'),
            $tns_text('reviews_title', 'Заголовок секции', [
                'instructions' => 'Акцент — в звёздочках (в этой секции слово обычно акцентное целиком).',
                'required' => 1,
                'maxlength' => 60,
            ]),
            $tns_text('reviews_rating', 'Строка рейтинга', [
                'instructions' => 'Например «4,9 / 5,0 na».',
                'maxlength' => 40,
            ]),
            $tns_text('reviews_rating_sub', 'Подпись под рейтингом', [
                'instructions' => 'Например «Google Maps».',
                'maxlength' => 40,
            ]),

            $tns_tab('booking', 'Бронирование (Rezervace)'),
            $tns_text('booking_eyebrow', 'Надглавие', [
                'maxlength' => 60,
            ]),
            $tns_textarea('booking_title', 'Заголовок баннера', [
                'instructions' => 'Акцент — в звёздочках, перенос строки — с новой строки.',
                'rows' => 2,
                'maxlength' => 140,
            ]),
            $tns_link('booking_cta_desktop', 'Кнопка, версия для широкого экрана', [
                'instructions' => 'Показывается на широких экранах. Пусто — текст «Rezervovat» и общая ссылка бронирования (вкладка «Общее / Fresha»).',
            ]),
            $tns_link('booking_cta_mobile', 'Кнопка, версия для телефона', [
                'instructions' => 'Показывается на узких экранах — обычно ведёт на подарочный сертификат. Пусто — текст «Koupit dárkový poukaz» и переход к сертификату на этой же странице.',
            ]),
            $tns_image('booking_image_desktop', 'Фото, версия для широкого экрана', [
                'instructions' => 'Обязательно. Рекомендуемый размер 1480×340 px.',
                'required' => 1,
            ]),
            $tns_image('booking_image_mobile', 'Фото, версия для телефона', [
                'instructions' => 'Необязательно — если не загружено, используется фото для широкого экрана.',
            ]),
            $tns_text('inspire_title', 'Заголовок блока соцсетей', [
                'maxlength' => 140,
            ]),
            $tns_text('inspire_sub', 'Подпись блока соцсетей', [
                'maxlength' => 100,
            ]),

            $tns_tab('team', 'Команда (Tým)'),
            $tns_text('team_title', 'Заголовок секции', [
                'instructions' => 'Акцент — в звёздочках, обычно название бренда.',
                'required' => 1,
                'maxlength' => 60,
            ]),

            $tns_tab('faq', 'Частые вопросы'),
            $tns_text('faq_title', 'Заголовок секции', [
                'instructions' => 'Акцент — в звёздочках.',
                'required' => 1,
                'maxlength' => 100,
            ]),
            $tns_textarea('faq_lead', 'Вводный текст', [
                'maxlength' => 200,
            ]),

            $tns_tab('footer', 'Контакты и футер'),
            $tns_text('tagline', 'Слоган под лого', [
                'maxlength' => 80,
            ]),
            $tns_textarea('hours', 'Часы работы', [
                'instructions' => 'Каждая строка — отдельная строка в футере.',
                'rows' => 2,
            ]),
            $tns_textarea('address', 'Адрес', [
                'instructions' => 'Каждая строка — отдельная строка в футере.',
                'rows' => 2,
            ]),
            $tns_url('maps_url', 'Ссылка на Google Maps'),
            $tns_text('phone', 'Телефон', [
                'instructions' => 'В формате +420 777 042 214.',
                'maxlength' => 30,
            ]),
            $tns_text('email', 'E-mail', [
                'maxlength' => 80,
            ]),
            $tns_url('privacy_url', 'Ссылка на «Zásady ochrany osobních údajů»'),
            $tns_url('vop_url', 'Ссылка на «VOP»'),
            $tns_url('social_tiktok', 'Ссылка на TikTok'),
            $tns_url('social_facebook', 'Ссылка на Facebook'),
            $tns_url('social_instagram', 'Ссылка на Instagram'),
            $tns_text('schema_street', 'Улица и номер дома (для карты и Schema.org)', [
                'instructions' => 'Например «Hálkova 1056/10».',
                'maxlength' => 100,
            ]),
            $tns_text('schema_zip', 'Индекс (для Schema.org)', [
                'maxlength' => 20,
            ]),
            $tns_text('schema_city', 'Город (для Schema.org)', [
                'maxlength' => 60,
            ]),
            $tns_text('schema_opens', 'Время открытия (для Schema.org)', [
                'instructions' => 'Формат ЧЧ:ММ, например 08:00.',
                'maxlength' => 5,
            ]),
            $tns_text('schema_closes', 'Время закрытия (для Schema.org)', [
                'instructions' => 'Формат ЧЧ:ММ, например 20:00.',
                'maxlength' => 5,
            ]),
        ],
    ]);
});
