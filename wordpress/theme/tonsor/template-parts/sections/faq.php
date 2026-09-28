<?php
/**
 * Časté dotazy: аккордеон bayan, первый пункт раскрыт. Перенесено 1:1 со статикой
 * (src/tpls/sections/faq.html); заголовок и лид — поля главной (ACF), карточки — CPT
 * tns_faq, порядок menu_order. JSON-LD FAQPage из тех же записей — inc/schema.php,
 * печатается в footer.php.
 */

defined('ABSPATH') || exit;

$tns_front_id = tns_front_id();
$tns_faq_title = (string) tns_field('faq_title', $tns_front_id);
$tns_faq_lead = (string) tns_field('faq_lead', $tns_front_id);

$tns_faq_items = get_posts([
    'post_type' => 'tns_faq',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);
?>
<!-- Časté dotazy (`76:494`, юнит U11). H2 «Odpovídáme na vaše otázky» (акцент — целиком
слово «Odpovídáme») + лид «Vše, co potřebujete vědět před návštěvou barbershopu TONSOR»
(`76:495`/`76:496`, `109:1229`/`109:1230` — идентичный текст на обеих ширинах).

Аккордеоны — библиотека bayan (`components.b/groupers/_bayan.js`): `.faq__item` несёт класс
`bayan`, первая карточка по порядку (menu_order) — ещё и `bayan--opened` (первый вопрос
раскрыт по умолчанию на обеих ширинах — bayan это уже покрывает самим классом в разметке,
свой JS не нужен).

Десктоп — CSS-грид 2 колонки, обычный `grid-template-columns: 1fr 1fr` без переворота
потока — карточки идут ПАРАМИ СТРОК, стандартный row-major поток с DOM-порядком сам даёт
нужное «слева 1,3,5 / справа 2,4».

Шеврон — символ спрайта `chevron-down-sym` (в спрайте уже отзеркален под положение «вниз» =
свёрнуто), поворот 180° в раскрытом состоянии на CSS.

Мобилка (`109:1229…109:1260`) — 1 колонка, тот же DOM-порядок. -->
<section class="faq" id="faq">
  <div class="container">
    <div class="faq__head" data-aos="fade-up">
      <h2 class="section-title faq__title"><?php echo tns_accent($tns_faq_title); ?></h2>
      <?php if ($tns_faq_lead) : ?>
      <p class="faq__lead"><?php echo esc_html($tns_faq_lead); ?></p>
      <?php endif; ?>
    </div>

    <?php if ($tns_faq_items) : ?>
    <div class="faq__list">

      <?php foreach ($tns_faq_items as $tns_index => $tns_faq_item) :
          $tns_post_id = $tns_faq_item->ID;
          $tns_question = get_the_title($tns_post_id);
          $tns_answer = (string) tns_field('answer', $tns_post_id);
          $tns_is_first = 0 === $tns_index;
      ?>
      <div class="faq__item bayan<?php echo $tns_is_first ? ' bayan--opened' : ''; ?>" data-aos="fade-up">
        <div class="faq__item-question">
          <p class="faq__item-question-text"><?php echo esc_html($tns_question); ?></p>
          <svg class="faq__item-chevron" width="22" height="13" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('chevron-down')); ?>"></use></svg>
        </div>
        <div class="faq__item-answer">
          <p class="faq__item-answer-inner"><?php echo esc_html($tns_answer); ?></p>
        </div>
      </div>
      <?php endforeach; ?>

    </div>
    <?php endif; ?>
  </div>
</section>
