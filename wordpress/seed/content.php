<?php
/**
 * Содержимое лендинга для идемпотентного сида (seed.php): переписано из текущей
 * статической вёрстки (src/tpls/**, src/page-homepage.html). При изменении текста
 * или цен на сайте — сначала правится здесь, затем прогоняется сид, чтобы админка
 * и статика не расходились.
 *
 * Пути картинок — относительно src/img (в контейнере сида смонтирован как /seed-img).
 * Акцент в заголовках — в звёздочках *слово*, перенос строки — \n (см. inc/fields.php).
 */

defined('ABSPATH') || exit;

$fresha_general = 'https://www.fresha.com/cs/a/tonsor-premiovy-barbershop-plzen-plzensky-kraj-plzen-3-jizni-predmesti-1056-halkova-10-l8myvecl?pId=3091855&preview=a97ba182-3039-45c6-ba48-f0862e5da559';
$fresha_gift = 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/packages?share=true&pId=3091855';

return [

    'front_page' => [
        'text' => [
            'fresha_general' => $fresha_general,
            'fresha_gift' => $fresha_gift,
            'promo_text' => 'AKCE MĚSÍCE – PRVNÍ NÁVŠTĚVA SE SLEVOU 50%',
            'header_cta_text' => 'Rezervovat',
            'services_title' => '*Služby* a ceny',
            'gift_title' => '*Dárkový* poukaz TONSOR',
            'gift_lead' => 'Darujte důležitému muži sebevědomí a upravený vzhled.',
            'gift_price' => 'Od 500 Kč',
            'gift_checks' => "Zaslání e-mailem\nNa jakoukoli službu TONSOR",
            'gift_cta_text' => 'Koupit dárkový poukaz',
            'care_title' => '*Pečujeme* o vaše sebevědomí',
            'portfolio_title' => '*Portfolio* barberů',
            'reviews_title' => '*Recenze*',
            'reviews_rating' => '4,9 / 5,0 na',
            'reviews_rating_sub' => 'Google Maps',
            'booking_eyebrow' => '— ONLINE REZERVACE',
            'booking_title' => "*Rezervujte* si termín \nna 3 kliknutí ještě dnes",
            'inspire_title' => 'Najděte inspiraci pro svůj střih na sociálních sítích TONSOR',
            'inspire_sub' => 'Uděláme vám střih podle fotografie',
            'team_title' => 'Tým *TONSOR*',
            'faq_title' => '*Odpovídáme* na vaše otázky',
            'faq_lead' => 'Vše, co potřebujete vědět před návštěvou barbershopu TONSOR',
            'tagline' => 'Prémiový barbershop v Plzni',
            'hours' => "Po – Pá: 08:00 – 20:00\nSo – Ne: 08:00 – 20:00",
            'address' => "Hálkova 1056, 301 00\nPlzeň 3",
            'maps_url' => 'https://maps.app.goo.gl/r4fRSUpbbaMPW99v7',
            'phone' => '+420 777 042 214',
            'email' => 'info@tonsorbarber.cz',
            'privacy_url' => 'https://docs.google.com/document/d/1twr9tCG6OJEzn4_RoF_v_HPqqNFZFfuLQMQaAoN8tWw/edit?usp=sharing',
            'vop_url' => '#',
            'social_tiktok' => 'https://www.tiktok.com/@tonsor.barbershop.cz',
            'social_facebook' => 'https://www.facebook.com/profile.php?id=61591946332992',
            'social_instagram' => 'https://www.instagram.com/tonsor.barbershop/',
            'schema_street' => 'Hálkova 1056/10',
            'schema_zip' => '301 00',
            'schema_city' => 'Plzeň',
            'schema_opens' => '08:00',
            'schema_closes' => '20:00',
        ],
        'links' => [
            'booking_cta_desktop' => ['title' => 'Rezervovat', 'url' => $fresha_general, 'target' => '_blank'],
            'booking_cta_mobile' => ['title' => 'Koupit dárkový poukaz', 'url' => '#poukaz', 'target' => ''],
        ],
        'groups' => [
            'care_pillar_1' => [
                'title' => 'Spolehlivost',
                'items' => "Jednoduchá online rezervace na 3 kliknutí s potvrzením termínu\n"
                    . "Připomenutí návštěvy 24 hodin předem\n"
                    . "Bez zbytečného čekání před návštěvou i během ní\n"
                    . 'Přesně vytvoříme střih i vousy podle vašeho popisu nebo fotografie',
            ],
            'care_pillar_2' => [
                'title' => 'Profesionalita',
                'items' => "Konzultace před střihem nebo úpravou vousů. Najdeme styl, který vám bude sedět\n"
                    . "Péče o vlasy a vousy je součástí každé služby. Používáme hypoalergenní kosmetiku ZNAČKA\n"
                    . 'Signature drink a příjemná hudba. Z běžné návštěvy děláme příjemný relax',
            ],
            'care_pillar_3' => [
                'title' => 'Dlouhodobá péče',
                'items' => "Vaše preference ohledně střihu i nápoje si zapamatujeme už od první návštěvy\n"
                    . "Pomůžeme vám usnadnit úpravu delších vlasů správnou technikou střihu. Poradíme s péčí i výběrem vhodných produktů\n"
                    . 'Udržujeme stejnou kvalitu střihu i služeb při každé návštěvě',
            ],
        ],
        'seo' => [
            'title' => 'TONSOR — prémiový barbershop v Plzni',
            'description' => 'TONSOR — prémiový barbershop v Plzni. Rezervujte si termín na 3 kliknutí.',
            'image' => ['src' => 'common.tns/og.jpg', 'alt' => 'TONSOR — prémiový barbershop v Plzni'],
        ],
        'images' => [
            'gift_image_desktop' => ['src' => 'gift.tns/certificate-desktop@2x.jpg', 'alt' => 'Dárkový poukaz TONSOR'],
            'gift_image_mobile' => ['src' => 'gift.tns/certificate-mobile@2x.jpg', 'alt' => 'Dárkový poukaz TONSOR'],
            'care_image_1' => ['src' => 'care.tns/interior-1@2x.jpg', 'alt' => 'Interiér barbershopu TONSOR'],
            'care_image_2' => ['src' => 'care.tns/interior-2@2x.jpg', 'alt' => 'Interiér barbershopu TONSOR'],
            'care_image_mobile' => ['src' => 'care.tns/interior-mobile@2x.jpg', 'alt' => 'Interiér barbershopu TONSOR'],
            'booking_image_desktop' => ['src' => 'booking.tns/booking-banner-desktop@2x.jpg', 'alt' => 'Interiér barbershopu TONSOR'],
            'booking_image_mobile' => ['src' => 'booking.tns/booking-banner-mobile@2x.jpg', 'alt' => 'Interiér barbershopu TONSOR'],
        ],
    ],

    'hero_slides' => [
        [
            'seed_key' => 'hero:1',
            'title' => 'Slajd 1',
            'menu_order' => 10,
            'heading' => '*Vybereme* výrazný střih a naučíme vás, jak si ho upravovat',
            'eyebrow' => '– Prémiový barbershop v Plzni',
            'lead' => 'Vyberte si termín a rezervujte se na 3 kliknutí',
            'cta' => ['title' => 'Rezervovat střih', 'url' => $fresha_general, 'target' => '_blank'],
            'image_desktop' => ['src' => 'hero.tns/hero-slide-1@2x.jpg', 'alt' => ''],
            'image_mobile' => ['src' => 'hero.tns/hero-slide-1-mobile@2x.jpg', 'alt' => ''],
        ],
        [
            'seed_key' => 'hero:2',
            'title' => 'Slajd 2',
            'menu_order' => 20,
            'heading' => 'Ostříháme vás podle popisu nebo fotografie. Přesně tak, jak chcete. Uděláme *perfektní fade*',
            'eyebrow' => '– Prémiový barbershop v Plzni',
            'lead' => 'Vyberte si termín a rezervujte se na 3 kliknutí',
            'cta' => ['title' => 'Rezervovat střih', 'url' => $fresha_general, 'target' => '_blank'],
            'image_desktop' => ['src' => 'hero.tns/hero-slide-2@2x.jpg', 'alt' => ''],
            'image_mobile' => ['src' => 'hero.tns/hero-slide-2-mobile@2x.jpg', 'alt' => ''],
        ],
        [
            'seed_key' => 'hero:3',
            'title' => 'Slajd 3',
            'menu_order' => 30,
            'heading' => 'Upravíme vaše vousy tak, aby působily čistě, upraveně a mužně',
            'eyebrow' => '– Prémiový barbershop v Plzni',
            'lead' => 'Vyberte si termín a rezervujte se na 3 kliknutí',
            'cta' => ['title' => 'Rezervovat střih', 'url' => $fresha_general, 'target' => '_blank'],
            'image_desktop' => ['src' => 'hero.tns/hero-slide-3@2x.jpg', 'alt' => ''],
            'image_mobile' => ['src' => 'hero.tns/hero-slide-3-mobile@2x.jpg', 'alt' => ''],
        ],
    ],

    'portfolio_terms' => [
        ['slug' => 'kratke-vlasy', 'name' => 'Krátké vlasy', 'order' => 1],
        ['slug' => 'dlouhe-vlasy', 'name' => 'Dlouhé vlasy', 'order' => 2],
        ['slug' => 'vousy', 'name' => 'Vousy', 'order' => 3],
        ['slug' => 'detske-strihy', 'name' => 'Dětské střihy', 'order' => 4],
        ['slug' => 'strih-vousy', 'name' => 'Střih + vousy', 'order' => 5],
    ],

    'services' => [
        [
            'seed_key' => 'service:strih-kratkych-vlasu',
            'title' => 'Střih krátkých vlasů',
            'menu_order' => 10,
            'price' => 500,
            'price_from' => true,
            'fresha_url' => 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/services?lid=3196688&oiid=sv%3A29210843&share=true&pId=3091855',
            'portfolio_term_slug' => 'kratke-vlasy',
        ],
        [
            'seed_key' => 'service:strih-dlouhych-vlasu',
            'title' => 'Střih dlouhých vlasů',
            'menu_order' => 20,
            'price' => 600,
            'price_from' => true,
            'fresha_url' => 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/services?lid=3196688&oiid=sv%3A29210845&share=true&pId=3091855',
            'portfolio_term_slug' => 'dlouhe-vlasy',
        ],
        [
            'seed_key' => 'service:strih-uprava-vousu',
            'title' => 'Střih + úprava vousů',
            'menu_order' => 30,
            'price' => 750,
            'price_from' => true,
            'fresha_url' => 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/services?lid=3196688&oiid=sv%3A29211029&share=true&pId=3091855',
            'portfolio_term_slug' => 'strih-vousy',
        ],
        [
            'seed_key' => 'service:uprava-vousu',
            'title' => 'Úprava vousů',
            'menu_order' => 40,
            'price' => 300,
            'price_from' => true,
            'fresha_url' => 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/services?lid=3196688&oiid=sv%3A29210914&share=true&pId=3091855',
            'portfolio_term_slug' => 'vousy',
        ],
        [
            'seed_key' => 'service:detsky-strih-do-10-let',
            'title' => 'Dětský střih do 10 let',
            'menu_order' => 50,
            'price' => 400,
            'price_from' => true,
            'fresha_url' => 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/services?lid=3196688&oiid=sv%3A29210875&share=true&pId=3091855',
            'portfolio_term_slug' => 'detske-strihy',
        ],
        [
            'seed_key' => 'service:tonovani-vousu',
            'title' => 'Tónování vousů',
            'menu_order' => 60,
            'price' => 300,
            'price_from' => true,
            'fresha_url' => 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/services?lid=3196688&oiid=sv%3A29210926&share=true&pId=3091855',
            'portfolio_term_slug' => null,
        ],
        [
            'seed_key' => 'service:strih-tata-a-syn',
            'title' => 'Střih táta a syn',
            'menu_order' => 70,
            'price' => 850,
            'price_from' => true,
            'fresha_url' => 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/services?lid=3196688&oiid=sv%3A29211050&share=true&pId=3091855',
            'portfolio_term_slug' => null,
        ],
        [
            'seed_key' => 'service:strih-tata-a-syn-uprava-vousu',
            'title' => 'Střih táta a syn + úprava vousů',
            'menu_order' => 80,
            'price' => 850,
            'price_from' => true,
            'fresha_url' => 'https://www.fresha.com/book-now/stam-barber-s-r-o-c9avb40d/services?lid=3196688&oiid=sv%3A29211060&share=true&pId=3091855',
            'portfolio_term_slug' => null,
        ],
    ],

    'barbers' => [
        [
            'seed_key' => 'barber:zachar',
            'title' => 'Zachar',
            'menu_order' => 10,
            'caption' => '',
            'image' => ['src' => 'team.tns/barber-zachar@2x.jpg', 'alt' => 'Barber Zachar'],
        ],
        [
            'seed_key' => 'barber:ivan',
            'title' => 'Ivan',
            'menu_order' => 20,
            'caption' => '',
            'image' => ['src' => 'team.tns/barber-ivan@2x.jpg', 'alt' => 'Barber Ivan'],
        ],
        [
            'seed_key' => 'barber:viktorie',
            'title' => 'Viktorie',
            'menu_order' => 30,
            'caption' => '',
            'image' => ['src' => 'team.tns/barber-viktorie@2x.jpg', 'alt' => 'Barber Viktorie'],
        ],
        [
            'seed_key' => 'barber:anastasie',
            'title' => 'Anastasie',
            'menu_order' => 40,
            'caption' => '',
            'image' => ['src' => 'team.tns/barber-anastasie@2x.jpg', 'alt' => 'Barber Anastasie'],
        ],
    ],

    // Логика и количество карточек портфолио повторяют статику: 4 уникальных фото, каждое
    // дважды (по числу повторов на фильтр в src/tpls/sections/portfolio.html), каждая запись
    // сразу во всех 5 категориях — так portfolio.js по контракту «слайд на каждый термин
    // записи» даёт те же 8×5=40 карточек, что сейчас в статике.
    'portfolio_works' => [
        [
            'seed_key' => 'work:anastasie-1',
            'title' => 'Anastasie — práce č. 1',
            'menu_order' => 10,
            'barber_seed_key' => 'barber:anastasie',
            'image' => ['src' => 'portfolio.tns/work-1@2x.jpg', 'alt' => 'Pánský krátký sestřih s fade'],
        ],
        [
            'seed_key' => 'work:anastasie-2',
            'title' => 'Anastasie — práce č. 2',
            'menu_order' => 20,
            'barber_seed_key' => 'barber:anastasie',
            'image' => ['src' => 'portfolio.tns/work-1@2x.jpg', 'alt' => 'Pánský krátký sestřih s fade'],
        ],
        [
            'seed_key' => 'work:zachar-1',
            'title' => 'Zachar — práce č. 1',
            'menu_order' => 30,
            'barber_seed_key' => 'barber:zachar',
            'image' => ['src' => 'portfolio.tns/work-2@2x.jpg', 'alt' => 'Pánský sestřih, pohled zezadu'],
        ],
        [
            'seed_key' => 'work:zachar-2',
            'title' => 'Zachar — práce č. 2',
            'menu_order' => 40,
            'barber_seed_key' => 'barber:zachar',
            'image' => ['src' => 'portfolio.tns/work-2@2x.jpg', 'alt' => 'Pánský sestřih, pohled zezadu'],
        ],
        [
            'seed_key' => 'work:ivan-1',
            'title' => 'Ivan — práce č. 1',
            'menu_order' => 50,
            'barber_seed_key' => 'barber:ivan',
            'image' => ['src' => 'portfolio.tns/work-3@2x.jpg', 'alt' => 'Melírovaný sestřih s texturou'],
        ],
        [
            'seed_key' => 'work:ivan-2',
            'title' => 'Ivan — práce č. 2',
            'menu_order' => 60,
            'barber_seed_key' => 'barber:ivan',
            'image' => ['src' => 'portfolio.tns/work-3@2x.jpg', 'alt' => 'Melírovaný sestřih s texturou'],
        ],
        [
            'seed_key' => 'work:viktorie-1',
            'title' => 'Viktorie — práce č. 1',
            'menu_order' => 70,
            'barber_seed_key' => 'barber:viktorie',
            'image' => ['src' => 'portfolio.tns/work-4@2x.jpg', 'alt' => 'Kudrnatý sestřih s fade'],
        ],
        [
            'seed_key' => 'work:viktorie-2',
            'title' => 'Viktorie — práce č. 2',
            'menu_order' => 80,
            'barber_seed_key' => 'barber:viktorie',
            'image' => ['src' => 'portfolio.tns/work-4@2x.jpg', 'alt' => 'Kudrnatý sestřih s fade'],
        ],
    ],

    'reviews' => [
        [
            'seed_key' => 'review:pavel-h',
            'title' => 'Pavel H.',
            'menu_order' => 10,
            'stars' => 5,
            'service' => 'Střih / barber služby',
            'text' => 'Kluci dělají fakt skvělé střihy. Pokud chcete fade, TONSOR rozhodně doporučuji. Parkování zdarma a služby přizpůsobené přáním zákazníka. 😊',
        ],
        [
            'seed_key' => 'review:maxim-d',
            'title' => 'Maxim D.',
            'menu_order' => 20,
            'stars' => 5,
            'service' => 'Střih / barber služby',
            'text' => 'Super profesionální služby na velké úrovni. Krásné prostředí, výborný kolektiv.',
        ],
        [
            'seed_key' => 'review:denis-k',
            'title' => 'Denis K.',
            'menu_order' => 30,
            'stars' => 5,
            'service' => 'Střih / barber služby',
            'text' => 'Skvělý barbershop! Profesionální přístup, příjemná atmosféra a opravdu precizní práce. Barber si dal záležet na každém detailu a výsledek přesně odpovídal mé představě.',
        ],
        [
            'seed_key' => 'review:milan-j',
            'title' => 'Milan J.',
            'menu_order' => 40,
            'stars' => 5,
            'service' => 'Střih / barber služby',
            'text' => 'Velká spokojenost! Jednoduchá a rychlá rezervace. Super odvedená práce. Doporučuji!!',
        ],
        [
            'seed_key' => 'review:vitalij',
            'title' => 'Vitalij',
            'menu_order' => 50,
            'stars' => 5,
            'service' => 'Barber služby',
            'text' => 'Zkušený tým pracovníků 👍',
        ],
        [
            'seed_key' => 'review:jiri-a',
            'title' => 'Jiří A.',
            'menu_order' => 60,
            'stars' => 5,
            'service' => 'Střih / barber služby',
            'text' => 'Bezkonkurenčně nejlepší Barber v Plzni.',
        ],
        [
            'seed_key' => 'review:jakub-d',
            'title' => 'Jakub D.',
            'menu_order' => 70,
            'stars' => 5,
            'service' => 'Střih / barber služby',
            'text' => 'Vše naprosto super. Nejen samotný střih, ale všechen servis kolem toho. Prostředí barbershopu navíc velmi příjemné.',
        ],
        [
            'seed_key' => 'review:ivan-g',
            'title' => 'Ivan G.',
            'menu_order' => 80,
            'stars' => 5,
            'service' => 'Střih',
            'text' => 'Dostal jsem perfektní střih, super barbershop, všem radím!',
        ],
        [
            'seed_key' => 'review:lukas-k',
            'title' => 'Lukáš K.',
            'menu_order' => 90,
            'stars' => 5,
            'service' => 'Střih',
            'text' => 'Super přístup a střih doporučuji 10/10.',
        ],
        [
            'seed_key' => 'review:roman',
            'title' => 'Roman',
            'menu_order' => 100,
            'stars' => 5,
            'service' => 'Barber služby',
            'text' => 'TOP jako vždy... doporučuji... 👍',
        ],
    ],

    'faq' => [
        [
            'seed_key' => 'faq:1',
            'title' => 'Kolik stojí služby?',
            'menu_order' => 10,
            'answer' => 'Ceny začínají od 300 Kč za úpravu nebo tónování vousů. Kompletní ceník všech služeb najdete na našem webu.',
        ],
        [
            'seed_key' => 'faq:2',
            'title' => 'Mohu se před návštěvou poradit ohledně střihu?',
            'menu_order' => 20,
            'answer' => 'Ano. Můžeme se spojit přes WhatsApp. Pošlete nám referenční fotku a svou fotografii a poradíme vám, zda je daný střih vhodný a jak by vám mohl sedět.',
        ],
        [
            'seed_key' => 'faq:3',
            'title' => 'Máte volný termín dnes nebo zítra?',
            'menu_order' => 30,
            'answer' => 'Snažíme se nechávat několik termínů pro rezervace na poslední chvíli. Aktuální dostupnost najdete v online rezervačním systému.',
        ],
        [
            'seed_key' => 'faq:4',
            'title' => 'Jak dlouho služba trvá?',
            'menu_order' => 40,
            'answer' => 'Standardní pánský střih trvá přibližně 45–60 minut. Kombinace střih + vousy trvá přibližně 75–90 minut.',
        ],
        [
            'seed_key' => 'faq:5',
            'title' => 'Musím se objednat předem?',
            'menu_order' => 50,
            'answer' => 'Doporučujeme rezervovat termín 2–3 dny před plánovanou návštěvou. Před svátky a vytíženými termíny raději alespoň týden dopředu.',
        ],
    ],

];
