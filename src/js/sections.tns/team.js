"use strict";

// Tým TONSOR (юнит U10): карусель барберов. Десктоп — 3 карточки в ряд (breakpoint 576px =
// граница $mobile-xxlg из СТАЙЛГАЙДа), мобилка — «подглядывание» следующей карточки
// (slidesPerView "auto" + фиксированная ширина слайда в `_team.scss`, паттерн юнита Portfolio,
// U6 — точнее, чем произвольная десятичная доля slidesPerView). Стрелки — свой стиль
// `.team__arrow--prev/--next` (не `nav-arrow` из components.b, см. _team.scss), лежат сиблингом
// `.team__slider` (паттерн ТЗ). Без loop: 4 карточки (правка клиента 10.09, п.6) — Swiper и без
// зацикливания докручивает до последней и сам гасит стрелку в конце (проверено на 1920 и 320),
// включать loop незачем и рискованно — для качественного бесшовного цикла Swiper обычно нужно
// больше слайдов, чем помещается в ряд за раз.
(() => {
  const el = document.querySelector(".team__slider");
  if (!el || !window.Swiper) return;

  new window.Swiper(el, {
    slidesPerView: "auto",
    spaceBetween: 15,
    navigation: {
      nextEl: ".team__arrow--next",
      prevEl: ".team__arrow--prev",
    },
    breakpoints: {
      576: {
        slidesPerView: 3,
        spaceBetween: 24,
      },
    },
  });
})();
