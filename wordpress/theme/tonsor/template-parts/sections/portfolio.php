<?php
/**
 * Portfolio barberů: фильтры + карусель (portfolio.js). Перенесено 1:1 со статикой
 * (src/tpls/sections/portfolio.html); заголовок — поле главной (ACF), табы/select/слайды —
 * термины tns_portfolio_cat и работы tns_work.
 *
 * Термины — только с ≥1 опубликованной работой (get_terms hide_empty=true; WP считает
 * term->count именно по опубликованным записям, см. _update_post_term_count() в ядре),
 * порядок — по term meta `order` (ACF-поле термина, inc/fields.php). Контракт portfolio.js:
 * у каждого слайда ОДИН data-category → работа, привязанная к нескольким терминам,
 * выводится слайдом на каждый термин; первый таб = стартовый фильтр.
 */

defined('ABSPATH') || exit;

$tns_portfolio_title = (string) tns_field('portfolio_title', tns_front_id());

/**
 * Раскладка карточек по фильтрам 1:1 со статикой (src/tpls/sections/portfolio.html), пока
 * портфолио заполнено 4 дублированными фото — см. tns_portfolio_reorder() в inc/helpers.php.
 */
$tns_portfolio_order_map = [
    'kratke-vlasy' => [0, 1, 2, 3, 0, 1, 2, 3],
    'dlouhe-vlasy' => [1, 3, 0, 2, 1, 3, 0, 2],
    'vousy' => [2, 0, 3, 1, 2, 0, 3, 1],
    'detske-strihy' => [3, 2, 1, 0, 3, 2, 1, 0],
    'strih-vousy' => [0, 3, 1, 2, 0, 3, 1, 2],
];

$tns_portfolio_terms = get_terms([
    'taxonomy' => 'tns_portfolio_cat',
    'hide_empty' => true,
    'meta_key' => 'order',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
]);

if (is_wp_error($tns_portfolio_terms)) {
    $tns_portfolio_terms = [];
}

// get_terms() с hide_empty не переиндексирует ключи после фильтрации (напр. при скрытом
// первом по порядку термине ключи начинаются не с 0) — без этого сравнение "0 === $tns_index"
// ниже ошибочно не находило бы первый видимый термин.
$tns_portfolio_terms = array_values($tns_portfolio_terms);
?>

<section class="portfolio" id="portfolio">
  <div class="container">
    <div class="portfolio__grid">
      <h2 class="portfolio__title section-title" data-aos="fade-up"><?php echo tns_accent($tns_portfolio_title); ?></h2>

      <?php if ($tns_portfolio_terms) : ?>
      <div class="portfolio__tabs" role="tablist">
        <?php foreach ($tns_portfolio_terms as $tns_index => $tns_term) : ?>
        <button type="button" class="portfolio__tab<?php echo 0 === $tns_index ? ' portfolio__tab--active' : ''; ?>" data-filter="<?php echo esc_attr($tns_term->slug); ?>" role="tab" aria-selected="<?php echo 0 === $tns_index ? 'true' : 'false'; ?>"><?php echo esc_html($tns_term->name); ?></button>
        <?php endforeach; ?>
      </div>

      <div class="portfolio__select-wrap">
        <select class="portfolio__select" aria-label="Filtr portfolia">
          <?php foreach ($tns_portfolio_terms as $tns_term) : ?>
          <option value="<?php echo esc_attr($tns_term->slug); ?>"><?php echo esc_html($tns_term->name); ?></option>
          <?php endforeach; ?>
        </select>
        <svg class="portfolio__select-chevron" width="14" height="8"><use href="<?php echo esc_url(tns_sprite('chevron-down')); ?>"></use></svg>
      </div>

      <div class="portfolio__slider swiper">
        <div class="swiper-wrapper">
          <?php foreach ($tns_portfolio_terms as $tns_term) :
              $tns_works = get_posts([
                  'post_type' => 'tns_work',
                  'post_status' => 'publish',
                  'posts_per_page' => -1,
                  'orderby' => 'menu_order',
                  'order' => 'ASC',
                  'tax_query' => [[
                      'taxonomy' => 'tns_portfolio_cat',
                      'field' => 'term_id',
                      'terms' => $tns_term->term_id,
                  ]],
              ]);
              $tns_works = tns_portfolio_reorder($tns_works, $tns_portfolio_order_map[$tns_term->slug] ?? null);

              foreach ($tns_works as $tns_work) :
                  $tns_work_id = $tns_work->ID;
                  $tns_thumb_id = (int) get_post_thumbnail_id($tns_work_id);
                  $tns_barber = tns_field('barber', $tns_work_id);
                  $tns_caption = $tns_barber instanceof WP_Post ? get_the_title($tns_barber) : '';
          ?>
          <article class="portfolio__slide swiper-slide" data-category="<?php echo esc_attr($tns_term->slug); ?>">
            <div class="portfolio__media">
              <?php echo tns_picture($tns_thumb_id, 0, ['block' => 'portfolio', 'loading' => 'lazy']); ?>
            </div>
            <?php if ($tns_caption) : ?>
            <p class="portfolio__caption"><?php echo esc_html($tns_caption); ?></p>
            <?php endif; ?>
          </article>
          <?php
              endforeach;
          endforeach;
          ?>
        </div>
      </div>

      <div class="portfolio__nav">
        <button type="button" class="portfolio__arrow portfolio__arrow--prev nav-arrow swiper-button-prev" aria-label="Předchozí práce">
          <svg class="portfolio__arrow-icon" width="15" height="10"><use href="<?php echo esc_url(tns_sprite('arrow-left')); ?>"></use></svg>
        </button>
        <button type="button" class="portfolio__arrow portfolio__arrow--next nav-arrow swiper-button-next" aria-label="Další práce">
          <svg class="portfolio__arrow-icon" width="15" height="10"><use href="<?php echo esc_url(tns_sprite('arrow-right')); ?>"></use></svg>
        </button>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
