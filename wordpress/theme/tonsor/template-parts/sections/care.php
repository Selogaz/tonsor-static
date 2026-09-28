<?php
/**
 * Интерьер + Pečujeme o vaše sebevědomí. Перенесено 1:1 со статикой (src/tpls/sections/care.html);
 * данные — поля главной (ACF).
 */

defined('ABSPATH') || exit;

$tns_front_id = tns_front_id();
$tns_title = (string) tns_field('care_title', $tns_front_id);
$tns_image_1 = (int) tns_field('care_image_1', $tns_front_id);
$tns_image_2 = (int) tns_field('care_image_2', $tns_front_id);
$tns_image_mobile = (int) tns_field('care_image_mobile', $tns_front_id);

$tns_pillars = [
    (array) tns_field('care_pillar_1', $tns_front_id),
    (array) tns_field('care_pillar_2', $tns_front_id),
    (array) tns_field('care_pillar_3', $tns_front_id),
];
?>

<section class="care" id="o-nas">
  <div class="container">
    <div class="care__media">
      <?php echo tns_picture($tns_image_1, $tns_image_mobile, ['block' => 'care', 'modifier' => '1', 'loading' => 'lazy']); ?>
      <?php echo tns_picture($tns_image_2, 0, ['block' => 'care', 'modifier' => '2', 'loading' => 'lazy']); ?>
    </div>

    <h2 class="care__title section-title" data-aos="fade-up"><?php echo tns_accent($tns_title); ?></h2>

    <div class="care__pillars">
      <?php foreach ($tns_pillars as $tns_index => $tns_pillar) :
          $tns_pillar_title = (string) ($tns_pillar['title'] ?? '');
          $tns_pillar_items = tns_split_lines((string) ($tns_pillar['items'] ?? ''));
          $tns_is_accent = 2 === $tns_index; // 3-й столп — акцентный по вёрстке
      ?>
      <div class="care__pillar<?php echo $tns_is_accent ? ' care__pillar--accent' : ''; ?>" data-aos="fade-up">
        <?php if ($tns_pillar_title) : ?>
        <h3 class="care__pillar-title"><?php echo esc_html($tns_pillar_title); ?></h3>
        <?php endif; ?>
        <?php if ($tns_pillar_items) : ?>
        <ul class="care__pillar-list">
          <?php foreach ($tns_pillar_items as $tns_item) : ?>
          <li class="care__pillar-item"><?php echo esc_html($tns_item); ?></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
