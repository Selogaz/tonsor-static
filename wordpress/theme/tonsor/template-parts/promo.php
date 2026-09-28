<?php
/**
 * Промо-полоса над шапкой. Перенесено 1:1 со статикой (src/tpls/header.html);
 * текст — поле главной (ACF). Пустое поле — полоса не выводится вообще.
 */

defined('ABSPATH') || exit;

$tns_promo_text = (string) tns_field('promo_text', tns_front_id());
if ('' === trim($tns_promo_text)) {
    return;
}
?>
<div class="promo">
  <div class="container">
    <p class="promo__text"><?php echo esc_html($tns_promo_text); ?></p>
  </div>
</div>
