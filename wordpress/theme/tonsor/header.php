<?php
/**
 * Общий <head> + открывающая разметка (GTM, промо-полоса, шапка).
 * Заголовок, description, Open Graph/Twitter-карточка, канонический URL и локаль —
 * из SEO-плагина (поля страницы «Hlavní stránka»), печатаются внутри wp_head().
 */

defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-W85XQ596');</script>
  <!-- End Google Tag Manager -->

  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="format-detection" content="telephone=no">

  <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(tns_asset('img/common.tns/favicon.svg')); ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(tns_asset('img/common.tns/favicon.png')); ?>">
  <link rel="apple-touch-icon" href="<?php echo esc_url(tns_asset('img/common.tns/apple-touch-icon.png')); ?>">

  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W85XQ596"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <div class="wrapper">
    <?php
    get_template_part('template-parts/promo');
    get_template_part('template-parts/site-header');
    ?>

    <main class="main">
