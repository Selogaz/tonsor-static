<?php
/**
 * Dárkový poukaz. Перенесено 1:1 со статикой (src/tpls/sections/gift.html);
 * данные — поля главной (ACF).
 */

defined('ABSPATH') || exit;

$tns_front_id = tns_front_id();
$tns_title = (string) tns_field('gift_title', $tns_front_id);
$tns_lead = (string) tns_field('gift_lead', $tns_front_id);
$tns_price = (string) tns_field('gift_price', $tns_front_id);
$tns_checks = tns_split_lines((string) tns_field('gift_checks', $tns_front_id));
$tns_cta_text = (string) tns_field('gift_cta_text', $tns_front_id);
$tns_fresha_gift = (string) tns_field('fresha_gift', $tns_front_id);
$tns_image_desktop = (int) tns_field('gift_image_desktop', $tns_front_id);
$tns_image_mobile = (int) tns_field('gift_image_mobile', $tns_front_id);
?>

<section class="gift" id="poukaz">
  <div class="gift__media">
    <?php echo tns_picture($tns_image_desktop, $tns_image_mobile, ['block' => 'gift', 'loading' => 'lazy']); ?>
  </div>

  <div class="container gift__container">
    <div class="gift__content" data-aos="fade-up">
      <h2 class="gift__title section-title"><?php echo tns_accent($tns_title); ?></h2>

      <?php if ($tns_lead || $tns_price) : ?>
      <p class="gift__lead"><?php echo nl2br(esc_html($tns_lead), false); ?><?php if ($tns_price) : ?> <strong class="gift__lead-accent"><?php echo esc_html($tns_price); ?></strong><?php endif; ?></p>
      <?php endif; ?>

      <?php if ($tns_checks) : ?>
      <ul class="gift__checks">
        <?php foreach ($tns_checks as $tns_check) : ?>
        <li class="gift__check">
          <svg class="gift__check-icon" width="20" height="20"><use href="<?php echo esc_url(tns_sprite('check')); ?>"></use></svg>
          <span class="gift__check-text"><?php echo esc_html($tns_check); ?></span>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>

      <?php if ($tns_fresha_gift) : ?>
      <a class="gift__cta button button--solid" href="<?php echo esc_url($tns_fresha_gift); ?>" target="_blank" rel="noopener"><?php echo esc_html($tns_cta_text ?: 'Koupit dárkový poukaz'); ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>
