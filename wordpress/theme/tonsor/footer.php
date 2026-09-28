<?php
/**
 * Закрывающая разметка: патичка, Schema.org (BarberShop/LocalBusiness — из полей главной,
 * inc/schema.php; FAQPage пока захардкожена 1:1 со статики — переедет на записи вопросов
 * вместе со списком FAQ), wp_footer() (подключает assets/js/index.min.js из inc/assets.php).
 */

defined('ABSPATH') || exit;
?>
    </main>

    <?php get_template_part('template-parts/site-footer'); ?>
  </div>

  <script type="application/ld+json">
  <?php echo tns_schema_barbershop(); ?>
  </script>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "Kolik stojí služby?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Ceny začínají od 300 Kč za úpravu nebo tónování vousů. Kompletní ceník všech služeb najdete na našem webu."
        }
      },
      {
        "@type": "Question",
        "name": "Mohu se před návštěvou poradit ohledně střihu?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Ano. Můžeme se spojit přes WhatsApp. Pošlete nám referenční fotku a svou fotografii a poradíme vám, zda je daný střih vhodný a jak by vám mohl sedět."
        }
      },
      {
        "@type": "Question",
        "name": "Máte volný termín dnes nebo zítra?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Snažíme se nechávat několik termínů pro rezervace na poslední chvíli. Aktuální dostupnost najdete v online rezervačním systému."
        }
      },
      {
        "@type": "Question",
        "name": "Jak dlouho služba trvá?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Standardní pánský střih trvá přibližně 45–60 minut. Kombinace střih + vousy trvá přibližně 75–90 minut."
        }
      },
      {
        "@type": "Question",
        "name": "Musím se objednat předem?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Doporučujeme rezervovat termín 2–3 dny před plánovanou návštěvou. Před svátky a vytíženými termíny raději alespoň týden dopředu."
        }
      }
    ]
  }
  </script>

<?php wp_footer(); ?>
</body>
</html>
