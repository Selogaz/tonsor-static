<?php
/**
 * Rezervace-баннер + блок соцсетей. Перенесено 1:1 со статикой (src/tpls/sections/booking.html);
 * данные — поля главной (ACF).
 */

defined('ABSPATH') || exit;

$tns_front_id = tns_front_id();
$tns_eyebrow = (string) tns_field('booking_eyebrow', $tns_front_id);
$tns_title = (string) tns_field('booking_title', $tns_front_id);
$tns_cta_desktop = (array) tns_field('booking_cta_desktop', $tns_front_id);
$tns_cta_mobile = (array) tns_field('booking_cta_mobile', $tns_front_id);
$tns_fresha_general = (string) tns_field('fresha_general', $tns_front_id);
$tns_image_desktop = (int) tns_field('booking_image_desktop', $tns_front_id);
$tns_image_mobile = (int) tns_field('booking_image_mobile', $tns_front_id);
$tns_inspire_title = (string) tns_field('inspire_title', $tns_front_id);
$tns_inspire_sub = (string) tns_field('inspire_sub', $tns_front_id);
$tns_social_tiktok = (string) tns_field('social_tiktok', $tns_front_id);
$tns_social_facebook = (string) tns_field('social_facebook', $tns_front_id);
$tns_social_instagram = (string) tns_field('social_instagram', $tns_front_id);

$tns_cta_desktop_url = !empty($tns_cta_desktop['url']) ? $tns_cta_desktop['url'] : $tns_fresha_general;
$tns_cta_desktop_text = !empty($tns_cta_desktop['title']) ? $tns_cta_desktop['title'] : 'Rezervovat';
$tns_cta_desktop_target = !empty($tns_cta_desktop['target']) ? $tns_cta_desktop['target'] : '_blank';

$tns_cta_mobile_url = !empty($tns_cta_mobile['url']) ? $tns_cta_mobile['url'] : '#poukaz';
$tns_cta_mobile_text = !empty($tns_cta_mobile['title']) ? $tns_cta_mobile['title'] : 'Koupit dárkový poukaz';
$tns_cta_mobile_target = (string) ($tns_cta_mobile['target'] ?? '');
?>
<!-- Rezervace-баннер + блок соцсетей (`76:465`, юнит U8). Один фрейм макета на десктопе и
один визуальный блок на мобилке (общий фон `109:1176`) — баннер (`.booking`) и соцсети
(`.inspire`) идут подряд без разделителя в одном партиале, фон общий (`--bg-card`).
Баннер: фото `76:581`/`109:1179` (скрим — CSS, `--gradient-booking-scrim`) + eyebrow
`76:468`/`109:1182` + H2 `76:469`/`109:1183` + кнопка `76:470`/`109:1185`. Соцсети:
иконки `76:472`/`109:1188` (плоские глифы без круга, см. `_inspire.scss`) + заголовок
`76:479`/`109:1195` + подпись `76:480`/`109:1196`.

Решение заказчика 06.09 (правка, п. 19): текст кнопки баннера РАЗНЫЙ по ширинам — на
десктопе «Rezervovat» (`76:471`, ссылка Fresha), на мобилке «Koupit dárkový poukaz»
(`109:1185`, как в мобильном инстансе макета). Раньше это считалось багом макета
(мобильный инстанс кнопки был назван «Btn / Rezervovat střih (solid)», но с
неотредактированным текстовым оверрайдом «Koupit dárkový poukaz», скопированным с
Dárkový poukaz) — по замечанию заказчика 06.09 верстаем как в макете (подтверждение у
клиента — 07.09): два явных CTA, переключаемых `display` по `$mobile-xxlg` (без JS). -->
<section class="booking" id="rezervace">
  <div class="container">
    <div class="booking__media">
      <?php echo tns_picture($tns_image_desktop, $tns_image_mobile, ['block' => 'booking', 'loading' => 'lazy']); ?>
      <div class="booking__scrim"></div>

      <div class="booking__content" data-aos="fade-up">
        <?php if ($tns_eyebrow) : ?>
        <p class="booking__eyebrow"><?php echo esc_html($tns_eyebrow); ?></p>
        <?php endif; ?>
        <h2 class="booking__title"><?php echo tns_accent($tns_title); ?></h2>
        <?php if ($tns_cta_desktop_url) : ?>
        <a class="booking__cta booking__cta--desktop button button--solid" href="<?php echo esc_url($tns_cta_desktop_url); ?>" target="<?php echo esc_attr($tns_cta_desktop_target); ?>" rel="noopener"><?php echo esc_html($tns_cta_desktop_text); ?></a>
        <?php endif; ?>
        <?php if ($tns_cta_mobile_url) : ?>
        <a class="booking__cta booking__cta--mobile button button--solid" href="<?php echo esc_url($tns_cta_mobile_url); ?>"<?php echo $tns_cta_mobile_target ? ' target="' . esc_attr($tns_cta_mobile_target) . '" rel="noopener"' : ''; ?>><?php echo esc_html($tns_cta_mobile_text); ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="inspire">
    <div class="container" data-aos="fade-up">
      <div class="inspire__socials">
        <?php if ($tns_social_tiktok) : ?>
        <a class="inspire__social" href="<?php echo esc_url($tns_social_tiktok); ?>" target="_blank" rel="noopener" aria-label="TikTok TONSOR">
          <svg width="32" height="32"><use href="<?php echo esc_url(tns_sprite('tiktok-plain')); ?>"></use></svg>
        </a>
        <?php endif; ?>
        <?php if ($tns_social_facebook) : ?>
        <a class="inspire__social" href="<?php echo esc_url($tns_social_facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook TONSOR">
          <svg width="32" height="32"><use href="<?php echo esc_url(tns_sprite('facebook-plain')); ?>"></use></svg>
        </a>
        <?php endif; ?>
        <?php if ($tns_social_instagram) : ?>
        <a class="inspire__social" href="<?php echo esc_url($tns_social_instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram TONSOR">
          <svg width="32" height="32"><use href="<?php echo esc_url(tns_sprite('instagram-plain')); ?>"></use></svg>
        </a>
        <?php endif; ?>
      </div>

      <?php if ($tns_inspire_title) : ?>
      <p class="inspire__title"><?php echo esc_html($tns_inspire_title); ?></p>
      <?php endif; ?>
      <?php if ($tns_inspire_sub) : ?>
      <p class="inspire__sub"><?php echo esc_html($tns_inspire_sub); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>
