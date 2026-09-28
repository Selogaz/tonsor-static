<?php
/**
 * Tým TONSOR: карусель барберов (team.js). Перенесено 1:1 со статикой
 * (src/tpls/sections/team.html); заголовок — поле главной (ACF), карточки — CPT tns_barber,
 * порядок menu_order. Подпись — поле `caption`, пусто → фолбэк «Barber {имя}» (решение
 * юнита W4 — сид оставляет caption пустым у всех барберов специально ради этого фолбэка).
 */

defined('ABSPATH') || exit;

$tns_team_title = (string) tns_field('team_title', tns_front_id());

$tns_barbers = get_posts([
    'post_type' => 'tns_barber',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);
?>

<!-- Tým TONSOR (`76:481`, юнит U10). H2 `76:482`: «Tým » белым + «TONSOR» золотом — единственная
секция макета, где акцент — само название бренда, не тематическое слово секции. Карточки
барберов (`76:483/485/487`, 477×540 десктоп / `109:1199`/`109:1212`, 182×258 мобилка): фото —
featured image записи, подпись «Barber Имя» шрифтом Trajan Pro 3 (капитель — свойство самого
начертания, см. STYLEGUIDE §2). Стрелки (`109:1214`/`109:1215` десктоп, `109:1221` мобилка,
39/47×48px) — свой стиль `.team__arrow` (решение юнита — не общий `nav-arrow`), но токены
`--bg-nav-btn`/`--radius-nav-btn`/`--c-divider` переиспользованы из Recenze (юнит U7). Стрелки
лежат сиблингом `.swiper` (паттерн ТЗ), центрированы под каруселью на обеих ширинах. -->
<section class="team" id="tym">
  <div class="container">
    <h2 class="team__title section-title" data-aos="fade-up"><?php echo tns_accent($tns_team_title); ?></h2>

    <?php if ($tns_barbers) : ?>
    <div class="team__slider swiper">
      <div class="swiper-wrapper">

        <?php foreach ($tns_barbers as $tns_barber) :
            $tns_post_id = $tns_barber->ID;
            $tns_name = get_the_title($tns_post_id);
            $tns_caption = (string) tns_field('caption', $tns_post_id);
            if ('' === $tns_caption) {
                $tns_caption = 'Barber ' . $tns_name;
            }
            $tns_thumb_id = (int) get_post_thumbnail_id($tns_post_id);
        ?>
        <article class="team__slide swiper-slide">
          <div class="team__media">
            <?php echo tns_picture($tns_thumb_id, 0, ['block' => 'team', 'loading' => 'lazy']); ?>
          </div>
          <p class="team__caption"><?php echo esc_html($tns_caption); ?></p>
        </article>
        <?php endforeach; ?>

      </div>
    </div>

    <div class="team__nav">
      <button type="button" class="team__arrow team__arrow--prev swiper-button-prev" aria-label="Předchozí barber">
        <svg class="team__arrow-icon" width="15" height="10" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('arrow-left')); ?>"></use></svg>
      </button>
      <button type="button" class="team__arrow team__arrow--next swiper-button-next" aria-label="Další barber">
        <svg class="team__arrow-icon" width="15" height="10" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('arrow-right')); ?>"></use></svg>
      </button>
    </div>
    <?php endif; ?>
  </div>
</section>
