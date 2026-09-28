<?php
/**
 * Шапка: лого, меню, соцсети, CTA, бургер. Перенесено 1:1 со статикой (src/tpls/header.html);
 * якоря меню фиксированы (не редактируются), CTA и соцсети — поля главной (ACF). Пустая
 * ссылка соцсети → иконка не выводится; пустая общая ссылка Fresha → кнопки нет вообще
 * (без битого href="").
 */

defined('ABSPATH') || exit;

$tns_front_id = tns_front_id();
$tns_fresha_general = (string) tns_field('fresha_general', $tns_front_id);
$tns_cta_text = (string) tns_field('header_cta_text', $tns_front_id);
$tns_social_tiktok = (string) tns_field('social_tiktok', $tns_front_id);
$tns_social_facebook = (string) tns_field('social_facebook', $tns_front_id);
$tns_social_instagram = (string) tns_field('social_instagram', $tns_front_id);
?>
<header class="header">
  <div class="header__container container">
    <a class="logo header__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="TONSOR — na hlavní stránku">
      <picture>
        <source srcset="<?php echo esc_url(tns_asset('img/common.tns/logo.webp')); ?>" type="image/webp">
        <img class="logo__img" src="<?php echo esc_url(tns_asset('img/common.tns/logo.png')); ?>" alt="TONSOR" width="224" height="38">
      </picture>
    </a>

    <div class="menu" id="menu">
      <nav class="menu__nav" aria-label="Hlavní menu">
        <ul class="menu__list">
          <li class="menu__item"><a class="menu__link" href="#sluzby">Služby</a></li>
          <li class="menu__item"><a class="menu__link" href="#portfolio">Portfolio barberů</a></li>
          <li class="menu__item"><a class="menu__link" href="#poukaz">Dárkový poukaz</a></li>
          <li class="menu__item"><a class="menu__link" href="#recenze">Recenze</a></li>
        </ul>
      </nav>

      <div class="menu__socials">
        <?php if ($tns_social_tiktok) : ?>
        <a class="menu__social" href="<?php echo esc_url($tns_social_tiktok); ?>" target="_blank" rel="noopener" aria-label="TikTok TONSOR">
          <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('tiktok')); ?>"></use></svg>
        </a>
        <?php endif; ?>
        <?php if ($tns_social_facebook) : ?>
        <a class="menu__social" href="<?php echo esc_url($tns_social_facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook TONSOR">
          <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('facebook')); ?>"></use></svg>
        </a>
        <?php endif; ?>
        <?php if ($tns_social_instagram) : ?>
        <a class="menu__social" href="<?php echo esc_url($tns_social_instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram TONSOR">
          <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('instagram')); ?>"></use></svg>
        </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="menu-overlay"></div>

    <div class="header__actions">
      <?php if ($tns_fresha_general) : ?>
      <a class="header__cta button button--line" href="<?php echo esc_url($tns_fresha_general); ?>" target="_blank" rel="noopener"><?php echo esc_html($tns_cta_text ?: 'Rezervovat'); ?></a>
      <?php endif; ?>

      <button class="header__burger burger" id="burger" type="button" aria-label="Menu" aria-expanded="false">
        <span class="burger__line"></span>
        <span class="burger__line"></span>
        <span class="burger__line"></span>
      </button>
    </div>
  </div>
</header>
