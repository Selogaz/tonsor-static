<?php
/**
 * Патичка: лого, соцсети, часы, адрес, контакты, юр. ссылки. Перенесено 1:1 со статикой
 * (src/tpls/footer.html); данные — поля главной (ACF).
 */

defined('ABSPATH') || exit;

$tns_front_id = tns_front_id();
$tns_tagline = (string) tns_field('tagline', $tns_front_id);
$tns_hours = (string) tns_field('hours', $tns_front_id);
$tns_address = (string) tns_field('address', $tns_front_id);
$tns_maps_url = (string) tns_field('maps_url', $tns_front_id);
$tns_phone = (string) tns_field('phone', $tns_front_id);
$tns_email = (string) tns_field('email', $tns_front_id);
$tns_privacy_url = (string) tns_field('privacy_url', $tns_front_id);
$tns_vop_url = (string) tns_field('vop_url', $tns_front_id);
$tns_social_tiktok = (string) tns_field('social_tiktok', $tns_front_id);
$tns_social_facebook = (string) tns_field('social_facebook', $tns_front_id);
$tns_social_instagram = (string) tns_field('social_instagram', $tns_front_id);

/*
 * Адрес — textarea, каждая строка отдельно. В статике 2-я строка на мобилке (≤$mobile-xxlg)
 * скрыта классом footer__col-text-break (_footer.scss) — перенос прячется медиазапросом,
 * а не дублированием разметки. Собираем вручную (не общий nl2br), чтобы это поведение не
 * потерялось: 2-я строка получает этот класс + пробел перед текстом (пробел — часть самого
 * текстового узла, не CSS, поэтому нужен явно), 3-я и далее (адрес сверх исходных двух строк) —
 * обычный <br>.
 */
$tns_address_lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $tns_address))));
$tns_address_html = '';
foreach ($tns_address_lines as $tns_i => $tns_line) {
    if (0 === $tns_i) {
        $tns_address_html .= esc_html($tns_line);
    } elseif (1 === $tns_i) {
        $tns_address_html .= '<br class="footer__col-text-break"> ' . esc_html($tns_line);
    } else {
        $tns_address_html .= '<br>' . esc_html($tns_line);
    }
}
?>
<footer class="footer">
  <div class="container footer__container">
    <div class="footer__row">
      <div class="footer__brand">
        <a class="logo footer__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="TONSOR — na hlavní stránku">
          <picture>
            <source srcset="<?php echo esc_url(tns_asset('img/common.tns/logo.webp')); ?>" type="image/webp">
            <img class="logo__img" src="<?php echo esc_url(tns_asset('img/common.tns/logo.png')); ?>" alt="TONSOR" width="224" height="38" loading="lazy" decoding="async">
          </picture>
        </a>

        <?php if ($tns_tagline) : ?>
        <p class="footer__tagline"><?php echo esc_html($tns_tagline); ?></p>
        <?php endif; ?>

        <div class="footer__socials">
          <?php if ($tns_social_tiktok) : ?>
          <a class="footer__social" href="<?php echo esc_url($tns_social_tiktok); ?>" target="_blank" rel="noopener" aria-label="TikTok TONSOR">
            <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('tiktok-plain')); ?>"></use></svg>
          </a>
          <?php endif; ?>
          <?php if ($tns_social_facebook) : ?>
          <a class="footer__social" href="<?php echo esc_url($tns_social_facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook TONSOR">
            <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('facebook-plain')); ?>"></use></svg>
          </a>
          <?php endif; ?>
          <?php if ($tns_social_instagram) : ?>
          <a class="footer__social" href="<?php echo esc_url($tns_social_instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram TONSOR">
            <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('instagram-plain')); ?>"></use></svg>
          </a>
          <?php endif; ?>
        </div>
      </div>

      <div class="footer__col">
        <h3 class="footer__col-title">OTEVÍRACÍ DOBA</h3>
        <?php if ($tns_hours) : ?>
        <p class="footer__col-text"><?php echo nl2br(esc_html($tns_hours), false); ?></p>
        <?php endif; ?>
      </div>

      <div class="footer__col">
        <h3 class="footer__col-title">KDE NÁS NAJDETE</h3>
        <?php if ($tns_address_html) : ?>
        <p class="footer__col-text"><?php echo $tns_address_html; ?></p>
        <?php endif; ?>
        <?php if ($tns_maps_url) : ?>
        <a class="footer__col-link" href="<?php echo esc_url($tns_maps_url); ?>" target="_blank" rel="noopener">Google maps</a>
        <?php endif; ?>
      </div>

      <div class="footer__col">
        <h3 class="footer__col-title">KONTAKTY</h3>
        <?php if ($tns_phone) : ?>
        <a class="footer__phone" href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $tns_phone)); ?>"><?php echo esc_html($tns_phone); ?></a>
        <?php endif; ?>
        <?php if ($tns_email) : ?>
        <a class="footer__email" href="mailto:<?php echo esc_attr($tns_email); ?>"><?php echo esc_html($tns_email); ?></a>
        <?php endif; ?>
      </div>
    </div>

    <div class="footer__hairline"></div>

    <div class="footer__legal">
      <?php if ($tns_privacy_url) : ?>
      <a class="footer__legal-link" href="<?php echo esc_url($tns_privacy_url); ?>" target="_blank" rel="noopener">Zásady ochrany osobních údajů</a>
      <?php endif; ?>
      <?php if ($tns_vop_url) : ?>
      <a class="footer__legal-link" href="<?php echo esc_url($tns_vop_url); ?>">VOP</a>
      <?php endif; ?>
    </div>
  </div>
</footer>
