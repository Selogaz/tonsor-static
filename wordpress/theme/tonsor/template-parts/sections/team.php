<?php
/**
 * Tým TONSOR: карусель барберов (team.js). Перенесено 1:1 со статикой
 * (src/tpls/sections/team.html); заголовок — поле главной (ACF), карточки барберов
 * остаются статикой до CPT tns_barber (W6).
 */

defined('ABSPATH') || exit;

$tns_team_title = (string) tns_field('team_title', tns_front_id());
?>

<!-- Tým TONSOR (`76:481`, юнит U10). H2 `76:482`: «Tým » белым + «TONSOR» золотом — единственная
секция макета, где акцент — само название бренда, не тематическое слово секции. Карточки
барберов (`76:483/485/487`, 477×540 десктоп / `109:1199`/`109:1212`, 182×258 мобилка): фото —
РАСТР, цветные (в макете плейсхолдер был чёрно-белым, но реальные фото барберов клиент передал
в цвете — так и оставляем, без ч/б-фильтра), подпись «Barber Имя» шрифтом Trajan Pro 3 (капитель —
свойство самого начертания, см. STYLEGUIDE §2). 4 реальных барбера (правка клиента 10.09, п.6) —
Zachar/Ivan/Viktorie/Anastasie, порядок фиксирован клиентом. Кадр — под точное соотношение
карточки (477×540 → 954×1080px @2x), поэтому `object-fit: cover` в `_team.scss` де-факто не
обрезает, а только подстраховывает. С 4 карточками (было 5) `loop` в `team.js` не включаем:
при `slidesPerView:3` на десктопе и «подглядывании» на мобилке Swiper и без loop корректно
докручивает до последней карточки и сам гасит стрелку в конце — проверено кликами по обеим
стрелкам на 1920 и 320, пустых мест и ошибок в консоли нет. Стрелки (`109:1214`/`109:1215` десктоп, `109:1221` мобилка, 39/47×48px) — свой стиль
`.team__arrow` (решение юнита — не общий `nav-arrow`), но токены
`--bg-nav-btn`/`--radius-nav-btn`/`--c-divider` переиспользованы из Recenze (юнит U7):
совпадают тютелька в тютельку (сверено design-context). Стрелки лежат сиблингом `.swiper`
(паттерн ТЗ), центрированы под каруселью на обеих ширинах (в макете центр группы стрелок
совпадает с центром фрейма/контейнера, не с одной карточкой). -->
<section class="team" id="tym">
  <div class="container">
    <h2 class="team__title section-title" data-aos="fade-up"><?php echo tns_accent($tns_team_title); ?></h2>

    <div class="team__slider swiper">
      <div class="swiper-wrapper">

        <article class="team__slide swiper-slide">
          <div class="team__media">
            <picture class="team__media-pic">
              <source srcset="<?php echo esc_url(tns_asset('img/team.tns/barber-zachar@2x.webp')); ?>" type="image/webp">
              <img class="team__media-img" src="<?php echo esc_url(tns_asset('img/team.tns/barber-zachar@2x.jpg')); ?>" alt="Barber Zachar" width="477" height="540" loading="lazy" decoding="async">
            </picture>
          </div>
          <p class="team__caption">Barber Zachar</p>
        </article>

        <article class="team__slide swiper-slide">
          <div class="team__media">
            <picture class="team__media-pic">
              <source srcset="<?php echo esc_url(tns_asset('img/team.tns/barber-ivan@2x.webp')); ?>" type="image/webp">
              <img class="team__media-img" src="<?php echo esc_url(tns_asset('img/team.tns/barber-ivan@2x.jpg')); ?>" alt="Barber Ivan" width="477" height="540" loading="lazy" decoding="async">
            </picture>
          </div>
          <p class="team__caption">Barber Ivan</p>
        </article>

        <article class="team__slide swiper-slide">
          <div class="team__media">
            <picture class="team__media-pic">
              <source srcset="<?php echo esc_url(tns_asset('img/team.tns/barber-viktorie@2x.webp')); ?>" type="image/webp">
              <img class="team__media-img" src="<?php echo esc_url(tns_asset('img/team.tns/barber-viktorie@2x.jpg')); ?>" alt="Barber Viktorie" width="477" height="540" loading="lazy" decoding="async">
            </picture>
          </div>
          <p class="team__caption">Barber Viktorie</p>
        </article>

        <article class="team__slide swiper-slide">
          <div class="team__media">
            <picture class="team__media-pic">
              <source srcset="<?php echo esc_url(tns_asset('img/team.tns/barber-anastasie@2x.webp')); ?>" type="image/webp">
              <img class="team__media-img" src="<?php echo esc_url(tns_asset('img/team.tns/barber-anastasie@2x.jpg')); ?>" alt="Barber Anastasie" width="477" height="540" loading="lazy" decoding="async">
            </picture>
          </div>
          <p class="team__caption">Barber Anastasie</p>
        </article>

      </div>
    </div>

    <div class="team__nav">
      <button type="button" class="team__arrow team__arrow--prev swiper-button-prev" aria-label="Předchozí barber">
        <svg class="team__arrow-icon" width="15" height="10" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('arrow-left')); ?>"></use></svg>
      </button>
      <button type="button" class="team__arrow team__arrow--next swiper-button-next" aria-label="Další barber">
        <svg class="team__arrow-icon" width="15" height="10" aria-hidden="true"><use href="<?php echo esc_url(tns_sprite('arrow-right')); ?>"></use></svg>
      </button>
    </div>
  </div>
</section>
