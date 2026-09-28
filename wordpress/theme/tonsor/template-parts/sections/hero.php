<?php
/**
 * Hero-слайдер: N слайдов (hero.js). Перенесено 1:1 со статикой (src/tpls/sections/hero.html);
 * контент — CPT tns_hero_slide (ACF), порядок — menu_order. Первый опубликованный слайд —
 * <h1> (единственный на странице) и loading=eager, остальные — <p class="hero__title"> и lazy.
 * Пустая ссылка кнопки слайда → фолбэк на общую ссылку Fresha (fresha_general, поле главной) —
 * заметка юнита W4. Гварды на малое число слайдов (loop/стрелки Swiper) — W7, здесь не трогаем.
 */

defined('ABSPATH') || exit;

$tns_front_id = tns_front_id();
$tns_fresha_general = (string) tns_field('fresha_general', $tns_front_id);

$tns_hero_slides = get_posts([
    'post_type' => 'tns_hero_slide',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

if (!$tns_hero_slides) {
    return;
}
?>
<!-- Hero-слайдер (`65:47`, юнит U2). Слайд 1 в корне: `65:48`+`65:49` (фото/скрим),
`65:71` (Blok textu: H1 `65:72`, лид `65:73`), `65:77` (eyebrow), `65:74` (CTA).
Слайд 2 — фрейм `100:47`, слайд 3 — `100:318`. Мобилка: `104:636` (слайд 1: eyebrow
`109:925`, H1 `109:923`, лид `109:924`, кнопка `109:1039`, фото `109:929` — БЛОК ПОД
ТЕКСТОМ, не фон), слайды 2/3 — `109:1298`/`109:1562`. Общий eyebrow/lead/CTA на всех
слайдах, меняются H1 и фон-фото. Только первый слайд несёт <h1> (единственный h1
страницы), 2 и 3 — <p class="hero__title"> с тем же визуалом.

Автопрокрутка без контролов в макете не нарисована. Правка 09.09 (п.3 реестра): точки
заменены на стрелки листания. Паттерн переиспользован у Portfolio barberů (`.nav-arrow` —
общий хром b-компонента `components.b/controls/_nav-arrows.scss`, перекрашенный под тёмный
фон через `--fg`, тот же приём, что `.portfolio__arrow`) + иконка спрайта `arrow-left-sym`/
`arrow-right-sym` (та же, что у Portfolio/Recenze/Tým). В отличие от тех трёх каруселей, где
стрелки сгруппированы рядом друг с другом, здесь — по независимым краям экрана, вертикально
по центру (`position: absolute` внутри `.hero`, законное применение для боковых стрелок
слайдера). Стрелки — сиблинги `.hero__slider.swiper` (паттерн ТЗ, правило 13). На мобилке
(`≤ $mobile-xxlg`) скрыты — секция там не full-bleed фон (фото уходит в поток под текст),
центрировать боковые стрелки не по чему без наезда на контент; свайп пальцем остаётся
(Swiper даёт его по умолчанию). -->
<section class="hero">
  <div class="hero__slider swiper">
    <div class="hero__wrapper swiper-wrapper">

      <?php foreach ($tns_hero_slides as $tns_index => $tns_slide) :
          $tns_post_id = $tns_slide->ID;
          $tns_is_first = 0 === $tns_index;

          $tns_heading = (string) tns_field('heading', $tns_post_id);
          $tns_eyebrow = (string) tns_field('eyebrow', $tns_post_id);
          $tns_lead = (string) tns_field('lead', $tns_post_id);
          $tns_cta = (array) tns_field('cta', $tns_post_id);
          $tns_desktop_id = (int) tns_field('image_desktop', $tns_post_id);
          $tns_mobile_id = (int) tns_field('image_mobile', $tns_post_id);

          $tns_cta_url = !empty($tns_cta['url']) ? $tns_cta['url'] : $tns_fresha_general;
          $tns_cta_text = !empty($tns_cta['title']) ? $tns_cta['title'] : 'Rezervovat střih';
          $tns_cta_target = !empty($tns_cta['target']) ? $tns_cta['target'] : '_blank';
      ?>
      <div class="hero__slide swiper-slide">
        <div class="hero__content container" data-aos="fade-up">
          <div class="hero__text">
            <?php if ($tns_eyebrow) : ?>
            <p class="hero__eyebrow"><?php echo esc_html($tns_eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($tns_is_first) : ?>
            <h1 class="hero__title"><?php echo tns_accent($tns_heading, 'hero__title-accent'); ?></h1>
            <?php else : ?>
            <p class="hero__title"><?php echo tns_accent($tns_heading, 'hero__title-accent'); ?></p>
            <?php endif; ?>
            <?php if ($tns_lead) : ?>
            <p class="hero__lead"><?php echo esc_html($tns_lead); ?></p>
            <?php endif; ?>
          </div>
          <?php if ($tns_cta_url) : ?>
          <a class="hero__cta button button--solid" href="<?php echo esc_url($tns_cta_url); ?>" target="<?php echo esc_attr($tns_cta_target); ?>" rel="noopener"><?php echo esc_html($tns_cta_text); ?></a>
          <?php endif; ?>
        </div>
        <div class="hero__media">
          <?php echo tns_picture($tns_desktop_id, $tns_mobile_id, [
              'block' => 'hero',
              'loading' => $tns_is_first ? 'eager' : 'lazy',
          ]); ?>
          <div class="hero__scrim"></div>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>

  <button type="button" class="hero__arrow hero__arrow--prev nav-arrow swiper-button-prev" aria-label="Předchozí snímek">
    <svg class="hero__arrow-icon" width="15" height="10" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('arrow-left')); ?>"></use></svg>
  </button>
  <button type="button" class="hero__arrow hero__arrow--next nav-arrow swiper-button-next" aria-label="Další snímek">
    <svg class="hero__arrow-icon" width="15" height="10" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('arrow-right')); ?>"></use></svg>
  </button>
</section>
