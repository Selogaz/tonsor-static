<?php
/**
 * Патичка: лого, соцсети, часы, адрес, контакты, юр. ссылки. Перенесено 1:1 со статикой
 * (src/tpls/footer.html); данные — поля главной (ACF).
 */

defined('ABSPATH') || exit;
?>
<footer class="footer">
  <div class="container footer__container">
    <div class="footer__row">
      <div class="footer__brand">
        <a class="logo footer__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="TONSOR — na hlavní stránku">
          <picture>
            <source srcset="<?php echo esc_url(tns_asset('img/common.tns/logo.webp')); ?>" type="image/webp">
            <img class="logo__img" src="<?php echo esc_url(tns_asset('img/common.tns/logo.png')); ?>" alt="TONSOR" width="224" height="38" loading="lazy" decoding="async">
          </picture>
        </a>

        <p class="footer__tagline">Prémiový barbershop v Plzni</p>

        <div class="footer__socials">
          <a class="footer__social" href="https://www.tiktok.com/@tonsor.barbershop.cz" target="_blank" rel="noopener" aria-label="TikTok TONSOR">
            <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('tiktok-plain')); ?>"></use></svg>
          </a>
          <a class="footer__social" href="https://www.facebook.com/profile.php?id=61591946332992" target="_blank" rel="noopener" aria-label="Facebook TONSOR">
            <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('facebook-plain')); ?>"></use></svg>
          </a>
          <a class="footer__social" href="https://www.instagram.com/tonsor.barbershop/" target="_blank" rel="noopener" aria-label="Instagram TONSOR">
            <svg width="24" height="24"><use href="<?php echo esc_url(tns_sprite('instagram-plain')); ?>"></use></svg>
          </a>
        </div>
      </div>

      <div class="footer__col">
        <h3 class="footer__col-title">OTEVÍRACÍ DOBA</h3>
        <p class="footer__col-text">Po – Pá: 08:00 – 20:00<br>So – Ne: 08:00 – 20:00</p>
      </div>

      <div class="footer__col">
        <h3 class="footer__col-title">KDE NÁS NAJDETE</h3>
        <p class="footer__col-text">Hálkova 1056, 301 00<br class="footer__col-text-break"> Plzeň 3</p>
        <a class="footer__col-link" href="https://maps.app.goo.gl/r4fRSUpbbaMPW99v7" target="_blank" rel="noopener">Google maps</a>
      </div>

      <div class="footer__col">
        <h3 class="footer__col-title">KONTAKTY</h3>
        <a class="footer__phone" href="tel:+420777042214">+420 777 042 214</a>
        <a class="footer__email" href="mailto:info@tonsorbarber.cz">info@tonsorbarber.cz</a>
      </div>
    </div>

    <div class="footer__hairline"></div>

    <div class="footer__legal">
      <a class="footer__legal-link" href="https://docs.google.com/document/d/1twr9tCG6OJEzn4_RoF_v_HPqqNFZFfuLQMQaAoN8tWw/edit?usp=sharing" target="_blank" rel="noopener">Zásady ochrany osobních údajů</a>
      <a class="footer__legal-link" href="#">VOP</a>
    </div>
  </div>
</footer>
