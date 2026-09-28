<?php
/**
 * Služby a ceny: карточки услуг. Перенесено 1:1 со статикой (src/tpls/sections/services.html);
 * заголовок — поле главной (ACF); карточки — CPT tns_service, порядок menu_order.
 * «Ukázky práce» несёт data-portfolio-filter только если у услуги выбран термин портфолио
 * и в нём есть хотя бы одна опубликованная работа (term->count — WP считает его именно по
 * опубликованным записям, см. _update_post_term_count() в ядре).
 */

defined('ABSPATH') || exit;

$tns_services_title = (string) tns_field('services_title', tns_front_id());

$tns_services = get_posts([
    'post_type' => 'tns_service',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);
?>

<section class="services" id="sluzby">
  <div class="container">
    <div class="services__head" data-aos="fade-up">
      <h2 class="section-title"><?php echo tns_accent($tns_services_title); ?></h2>
    </div>

    <?php if ($tns_services) : ?>
    <div class="services__grid">
      <?php foreach ($tns_services as $tns_service) :
          $tns_post_id = $tns_service->ID;
          $tns_title = get_the_title($tns_post_id);
          $tns_fresha_url = (string) tns_field('fresha_url', $tns_post_id);
          $tns_term_id = (int) tns_field('portfolio_term', $tns_post_id);
          $tns_filter_slug = '';

          if ($tns_term_id) {
              $tns_term = get_term($tns_term_id, 'tns_portfolio_cat');
              if ($tns_term instanceof WP_Term && $tns_term->count > 0) {
                  $tns_filter_slug = $tns_term->slug;
              }
          }
      ?>
      <article class="service-card" data-aos="fade-up">
        <h3 class="service-card__title"><?php echo esc_html($tns_title); ?></h3>
        <div class="service-card__links">
          <?php if ($tns_fresha_url) : ?>
          <a class="service-card__cta" href="<?php echo esc_url($tns_fresha_url); ?>" target="_blank" rel="noopener">Rezervovat</a>
          <?php endif; ?>
          <a class="service-card__more" href="#portfolio"<?php echo $tns_filter_slug ? ' data-portfolio-filter="' . esc_attr($tns_filter_slug) . '"' : ''; ?>>Ukázky práce</a>
        </div>
        <p class="service-card__price"><?php echo tns_price($tns_post_id); ?></p>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
