"use strict";

// Recenze (юнит U7): карусель отзывов. Десктоп — 3 карточки в ряд (breakpoint 576px =
// граница $mobile-xxlg из СТАЙЛГАЙДа), мобилка — «подглядывание» следующей карточки.
// Стрелки — свой стиль `.reviews__arrow--prev/--next` (не `nav-arrow` из components.b,
// см. _reviews.scss), лежат вне `.swiper` (сиблинги на уровне `.reviews__inner`), поэтому
// передаются в Swiper как внешние селекторы.
// Правка 06.09 (п. 14): мобильные параметры пересчитаны по макету. В `104:636` карточка
// `109:1141` — 269px при зазоре 10px до следующей (`109:1154` начинается на x=294 при
// контейнере 15…305), из-за прежних 1.15/16 карточка выходила 250px и текст отзыва
// `109:1148` ломался на 5 строк вместо макетных 4. Swiper считает ширину слайда как
// (ширина_контейнера − spaceBetween × (slidesPerView − 1)) / slidesPerView, отсюда при
// контейнере 290 и зазоре 10: slidesPerView = 300 / 279 ≈ 1.075 → слайд 269px.
(() => {
  const el = document.querySelector(".reviews__slider");
  if (!el || !window.Swiper) return;

  new window.Swiper(el, {
    loop: true,
    spaceBetween: 10,
    slidesPerView: 1.075,
    navigation: {
      nextEl: ".reviews__arrow--next",
      prevEl: ".reviews__arrow--prev",
    },
    breakpoints: {
      576: {
        slidesPerView: 3,
        spaceBetween: 24,
      },
    },
  });
})();
