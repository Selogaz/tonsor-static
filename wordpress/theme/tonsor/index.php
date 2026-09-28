<?php
/**
 * Резервный шаблон: WordPress требует index.php в каждой теме. Главную страницу
 * отдаёт front-page.php — этот файл нужен только как страховка на прочие адреса.
 */

defined('ABSPATH') || exit;

get_header();
?>
<main class="tonsor-stub">
  <p>TONSOR — тема в разработке.</p>
</main>
<?php
get_footer();
