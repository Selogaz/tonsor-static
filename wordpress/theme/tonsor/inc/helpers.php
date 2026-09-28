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

if (!function_exists('tns_front_id')) {
    /** ID страницы, отмеченной статическим фронтом сайта — носитель полей главной (inc/admin.php). */
    function tns_front_id(): int
    {
        return (int) get_option('page_on_front');
    }
}

if (!function_exists('tns_split_lines')) {
    /**
     * Многострочное текстовое поле (textarea) → список непустых строк без крайних
     * пробелов. Нужен там, где ACF Free не даёт Repeater (чек-пункты сертификата,
     * пункты столпов «Pečujeme»), а повторяющийся список собирается из строк одного
     * текстового поля.
     *
     * @return string[]
     */
    function tns_split_lines(string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];
        $lines = array_map('trim', $lines);

        return array_values(array_filter($lines, static function (string $line): bool {
            return '' !== $line;
        }));
    }
}

if (!function_exists('tns_accent')) {
    /**
     * Текст поля → безопасный HTML по конвенции полей главной: акцент в звёздочках
     * *слово* → <span class="{класс}">слово</span>, перенос строки \n → <br>.
     * Экранирование идёт ДО разбора разметки — вход поля не может внести
     * произвольный HTML/скрипт, только эти два конструкта.
     */
    function tns_accent(string $text, string $accent_class = 'section-title__accent'): string
    {
        $text = trim($text);
        if ('' === $text) {
            return '';
        }

        $html = nl2br(esc_html($text), false);
        $html = preg_replace(
            '/\*(.+?)\*/su',
            '<span class="' . esc_attr($accent_class) . '">$1</span>',
            $html
        );

        return wp_kses($html, [
            'span' => ['class' => []],
            'br' => [],
        ]);
    }
}

if (!function_exists('tns_picture')) {
    /**
     * <picture> по паттерну правила 7 (media/pic/img): десктопное фото — одноразмерный
     * <img> (src на исходный файл в медиатеке, width/height/alt — из неё же), мобильное —
     * необязательный <source media="(max-width: 575px)"> той же формы. Без srcset/sizes:
     * WP по умолчанию подставил бы туда сгенерированные подразмеры (wp_get_attachment_image()
     * с 'full' всё равно считает и добавляет srcset), а на узких экранах браузер выбирает
     * из них МЕНЬШУЮ по разрешению JPEG-регенерацию вместо оригинального файла — в статике
     * у этих картинок отродясь единственный @2x-файл на все ширины, так и оставляем: точное
     * совпадение со статикой важнее автоматической адаптивности. Оригинал ("full") этим не
     * подменяется на webp — конвертация в webp (inc/setup.php) намеренно трогает только уже
     * сгенерированные WP подразмеры (админ-миниатюры, `wp_get_attachment_image_src` с
     * размером вроде 'thumbnail'), не сам загруженный файл.
     *
     * Мобильное фото не задано → нет отдельного <source>, <img> с десктопным фото резолвится
     * и на мобилке (тот же эффект, что «фолбэк на десктопную версию»). Пустой/удалённый
     * $desktop_id → пустая строка: без PHP-notice и без поломки окружающей вёрстки (обёртку
     * `.{block}__media` в разметке секции это не трогает).
     *
     * $args:
     *   block     — БЭМ-блок для классов {block}__media-pic/{block}__media-img (обязательно)
     *   modifier  — суффикс {block}__media-pic--{modifier} (напр. Pečujeme — два фото рядом)
     *   loading   — по умолчанию lazy
     *   decoding  — по умолчанию async
     */
    function tns_picture(int $desktop_id, int $mobile_id, array $args): string
    {
        if (!$desktop_id) {
            return '';
        }

        $desktop_src = wp_get_attachment_image_src($desktop_id, 'full');
        if (!$desktop_src) {
            return '';
        }

        [$tns_url, $tns_width, $tns_height] = $desktop_src;

        $block = $args['block'] ?? '';

        $pic_classes = [$block . '__media-pic'];
        if (!empty($args['modifier'])) {
            $pic_classes[] = $block . '__media-pic--' . $args['modifier'];
        }

        $alt = trim((string) get_post_meta($desktop_id, '_wp_attachment_image_alt', true));

        $img = sprintf(
            '<img class="%s" src="%s" alt="%s" width="%d" height="%d" loading="%s" decoding="%s">',
            esc_attr($block . '__media-img'),
            esc_url($tns_url),
            esc_attr($alt),
            (int) $tns_width,
            (int) $tns_height,
            esc_attr($args['loading'] ?? 'lazy'),
            esc_attr($args['decoding'] ?? 'async')
        );

        $mobile_source = '';
        if ($mobile_id) {
            $mobile_url = wp_get_attachment_image_url($mobile_id, 'full');
            if ($mobile_url) {
                $mobile_source = '<source media="(max-width: 575px)" srcset="' . esc_url($mobile_url) . '">';
            }
        }

        return '<picture class="' . esc_attr(implode(' ', $pic_classes)) . '">' . $mobile_source . $img . '</picture>';
    }
}

if (!function_exists('tns_portfolio_order_is_default')) {
    /**
     * true, пока НИ ОДНА из переданных работ не поменяла menu_order с момента последнего
     * сида (сид пишет исходное значение в мету `_tns_seed_menu_order` — см. seed/seed.php).
     * `Simple Custom Post Order` при перетаскивании в списке `tns_work` пишет menu_order
     * ГЛОБАЛЬНО на запись — своего порядка "на категорию" у ACF Free нет, поэтому запись
     * с одним и тем же menu_order одинаково влияет на все 5 категорий портфолио сразу.
     * Как только клиент один раз перетащил любую из этих работ (или добавил новую, без
     * метки сида вовсе), весь захардкоженный порядок дублей (tns_portfolio_reorder)
     * отключается разом для всех фильтров — дальше действует обычный menu_order.
     *
     * @param WP_Post[] $works
     */
    function tns_portfolio_order_is_default(array $works): bool
    {
        foreach ($works as $work) {
            $baseline = get_post_meta($work->ID, '_tns_seed_menu_order', true);
            if ('' === $baseline || (int) $baseline !== (int) $work->menu_order) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('tns_portfolio_reorder')) {
    /**
     * Порядок карточек `tns_work` внутри одного фильтра портфолио. Пока в категориях лежат
     * дубли одних и тех же 4 фото (по 2 карточки на фото), $sequence — фиксированная
     * последовательность индексов "групп одинаковых фото" (0 = первая по menu_order группа,
     * 1 = вторая, …), нужная только для точного повторения прежней раскладки; сам порядок
     * фото внутри группы (какая из двух карточек-дублей идёт первой) по-прежнему решает
     * menu_order записи.
     *
     * Карта — только СТАРТОВАЯ раскладка сида, не постоянное правило: применяется, пока
     * `tns_portfolio_order_is_default()` подтверждает, что ни одна из работ не была вручную
     * переставлена клиентом (см. её докблок про глобальный menu_order). После первой ручной
     * перестановки любой работы функция возвращает список как пришёл (по menu_order) —
     * перетаскивание в админке сразу становится заметно на фронте во всех категориях.
     *
     * $sequence = null (фильтр не описан ниже) → карточки не трогаем, отдаём как пришли
     * (по menu_order). Группа с $sequence, для которой не хватает записей (карточку
     * удалили/раскрепили) или которых стало больше 4 (добавили новое уникальное фото) —
     * лишнее дописывается в конец в исходном порядке, без ошибок и потери карточек: как
     * только дубли заменят настоящими разными фото, приоритет просто перестаёт что-либо
     * значить сам по себе.
     *
     * @param WP_Post[] $works
     * @param int[]|null $sequence
     * @return WP_Post[]
     */
    function tns_portfolio_reorder(array $works, ?array $sequence): array
    {
        if (null === $sequence || !tns_portfolio_order_is_default($works)) {
            return $works;
        }

        $groups = [];
        $group_keys = [];
        foreach ($works as $work) {
            $key = (int) get_post_thumbnail_id($work->ID);
            if (!isset($groups[$key])) {
                $groups[$key] = [];
                $group_keys[] = $key;
            }
            $groups[$key][] = $work;
        }

        $ordered = [];
        foreach ($sequence as $slot) {
            $key = $group_keys[$slot] ?? null;
            if (null !== $key && !empty($groups[$key])) {
                $ordered[] = array_shift($groups[$key]);
            }
        }

        foreach ($group_keys as $key) {
            foreach ($groups[$key] as $work) {
                $ordered[] = $work;
            }
        }

        return $ordered;
    }
}

if (!function_exists('tns_price')) {
    /**
     * Разметка цены услуги (`.service-card__price` внутри — сам `<p>` остаётся в шаблоне):
     * `<span class="service-card__price-from">od </span>1 100 Kč`. Span выводится всегда,
     * даже без приставки «od» (пустой, как в статике у услуг без «od») — на него завязан CSS.
     * Разделитель тысяч — неразрывный пробел.
     */
    function tns_price(int $post_id): string
    {
        $price = (int) tns_field('price', $post_id);
        $has_from = (bool) tns_field('price_from', $post_id);

        $formatted = number_format($price, 0, ',', "\xc2\xa0");

        return '<span class="service-card__price-from">' . ($has_from ? 'od ' : '') . '</span>' . esc_html($formatted) . ' Kč';
    }
}
