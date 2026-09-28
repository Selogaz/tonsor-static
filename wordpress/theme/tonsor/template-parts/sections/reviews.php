<?php
/**
 * Recenze: карусель отзывов (reviews.js). Перенесено 1:1 со статикой
 * (src/tpls/sections/reviews.html); заголовок и рейтинг — поля главной (ACF),
 * карточки — CPT tns_review, порядок menu_order. Чешские кавычки „…“ вокруг текста —
 * оформление шаблона (в статике впечатаны в разметку у каждого из 10 отзывов), поле
 * `text` хранит сам текст без них — проще редактировать в админке.
 */

defined('ABSPATH') || exit;

$tns_front_id = tns_front_id();
$tns_reviews_title = (string) tns_field('reviews_title', $tns_front_id);
$tns_reviews_rating = (string) tns_field('reviews_rating', $tns_front_id);
$tns_reviews_rating_sub = (string) tns_field('reviews_rating_sub', $tns_front_id);

$tns_reviews = get_posts([
    'post_type' => 'tns_review',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);
?>

<!-- Recenze (`76:422`, юнит U7). Шапка: H2 `76:423` (целиком золотое слово — вся надпись
через `.section-title__accent`, не только первое слово, т.к. заголовок односложный) +
индикатор рейтинга Google `76:424` (символ спрайта `star-sym` + «4,9 / 5,0 na Google Maps»,
на мобилке вторая строка мельче/полужирная/полупрозрачная — `109:1139`). Карточки — Swiper
(`76:428`/`76:440`/`76:452`, десктоп 3 в ряд по 477px с зазором 24px; мобилка `109:1153`/
`109:1154` 269×181 с «подглядыванием» следующей, x=294 при контейнере 320): 5 звёзд
(`star-sym`), текст отзыва, аватар — круглая заливка с инициалом, либо (W6) фото автора
(поле `photo`, необязательное) прижат к низу карточки флексом (не завязан на длину текста,
все карточки — фиксированной высоты). Стрелки `76:557`/`76:558` (мобилка `109:1167`) — свой
стиль `.reviews__arrow` (решение юнита: не `nav-arrow` из components.b, чтобы не трогать
общий файл), лежат сиблингами `.reviews__slider.swiper` (паттерн ТЗ), на десктопе абсолютно
поверх шапки справа, на мобилке — в потоке под каруселью по центру. -->
<section class="reviews" id="recenze">
  <div class="container reviews__inner">

    <div class="reviews__head" data-aos="fade-up">
      <h2 class="section-title reviews__title"><?php echo tns_accent($tns_reviews_title); ?></h2>
      <div class="reviews__rating">
        <svg class="reviews__rating-icon" width="32" height="32" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('star')); ?>"></use></svg>
        <?php if ($tns_reviews_rating || $tns_reviews_rating_sub) : ?>
        <div class="reviews__rating-text">
          <?php if ($tns_reviews_rating) : ?>
          <p class="reviews__rating-value"><?php echo esc_html($tns_reviews_rating); ?></p>
          <?php endif; ?>
          <?php if ($tns_reviews_rating_sub) : ?>
          <p class="reviews__rating-sub"><?php echo esc_html($tns_reviews_rating_sub); ?></p>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($tns_reviews) : ?>
    <div class="reviews__slider swiper">
      <div class="reviews__wrapper swiper-wrapper">

        <?php foreach ($tns_reviews as $tns_review) :
            $tns_post_id = $tns_review->ID;
            $tns_name = get_the_title($tns_post_id);
            $tns_text = (string) tns_field('text', $tns_post_id);
            $tns_stars = max(0, min(5, (int) tns_field('stars', $tns_post_id)));
            $tns_service = (string) tns_field('service', $tns_post_id);
            $tns_photo_id = (int) tns_field('photo', $tns_post_id);
            $tns_photo_src = $tns_photo_id ? wp_get_attachment_image_src($tns_photo_id, 'thumbnail') : false;
            $tns_initial = $tns_name !== '' ? mb_strtoupper(mb_substr($tns_name, 0, 1)) : '';
        ?>
        <div class="reviews__slide swiper-slide">
          <article class="reviews__card">
            <div class="reviews__stars" aria-hidden="true">
              <?php for ($tns_i = 0; $tns_i < $tns_stars; $tns_i++) : ?>
              <svg class="reviews__star" width="16" height="16"><use href="<?php echo esc_url(tns_sprite('star')); ?>"></use></svg>
              <?php endfor; ?>
            </div>
            <?php if ($tns_text) : ?>
            <p class="reviews__text">„<?php echo esc_html($tns_text); ?>“</p>
            <?php endif; ?>
            <div class="reviews__author">
              <?php if ($tns_photo_src) : ?>
              <span class="reviews__avatar reviews__avatar--photo">
                <img class="reviews__avatar-img" src="<?php echo esc_url($tns_photo_src[0]); ?>" alt="" width="48" height="48" loading="lazy" decoding="async">
              </span>
              <?php else : ?>
              <span class="reviews__avatar"><?php echo esc_html($tns_initial); ?></span>
              <?php endif; ?>
              <div class="reviews__author-info">
                <p class="reviews__name"><?php echo esc_html($tns_name); ?></p>
                <?php if ($tns_service) : ?>
                <p class="reviews__service"><?php echo esc_html($tns_service); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </article>
        </div>
        <?php endforeach; ?>

      </div>
    </div>

    <button type="button" class="reviews__arrow reviews__arrow--prev swiper-button-prev" aria-label="Předchozí recenze">
      <svg class="reviews__arrow-icon" width="15" height="10" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('arrow-left')); ?>"></use></svg>
    </button>
    <button type="button" class="reviews__arrow reviews__arrow--next swiper-button-next" aria-label="Další recenze">
      <svg class="reviews__arrow-icon" width="15" height="10" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('arrow-right')); ?>"></use></svg>
    </button>
    <?php endif; ?>

  </div>
</section>
