<?php
/**
 * Закрывающая разметка: патичка, Schema.org (BarberShop/LocalBusiness — из полей главной,
 * FAQPage — из записей tns_faq, оба inc/schema.php), wp_footer() (подключает
 * assets/js/index.min.js из inc/assets.php).
 */

defined('ABSPATH') || exit;

$tns_schema_faq = tns_schema_faq();
?>
    </main>

    <?php get_template_part('template-parts/site-footer'); ?>
  </div>

  <script type="application/ld+json">
  <?php echo tns_schema_barbershop(); ?>
  </script>
  <?php if ($tns_schema_faq) : ?>
  <script type="application/ld+json">
  <?php echo $tns_schema_faq; ?>
  </script>
  <?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
