"use strict";

// Hero-слайдер (юнит U2): 3 слайда, автопрокрутка, пауза при наведении. Стрелок/точек в
// макете нет ни на одной ширине (подтверждено брифом) — правка 09.09 (п.3 реестра): точки
// заменены на стрелки листания по бокам экрана (паттерн ТЗ — `.swiper-button-prev/next` на
// уровне со `.swiper`, см. hero.html/_hero.scss). `disableOnInteraction: false` сохраняет
// автопрокрутку после клика по стрелке (сбрасывает таймер, не выключает).
(() => {
  const el = document.querySelector(".hero__slider");
  if (!el || !window.Swiper) return;

  // Гвард (краевой случай малого числа слайдов из админки): при 1 слайде зацикливание не
  // нужно и Swiper сам предупредил бы о его невозможности в консоль — отключаем явно.
  // watchOverflow скрывает стрелки целиком (.swiper-button-lock, см. _swiper-core.scss),
  // когда прокручивать нечего — при 1 слайде это тот же самый случай, отдельного кода для
  // «спрятать стрелки» не нужно.
  const slideCount = el.querySelectorAll(".hero__slide").length;

  new window.Swiper(el, {
    loop: slideCount > 1,
    watchOverflow: true,
    speed: 600,
    autoplay: {
      delay: 6000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    navigation: {
      nextEl: ".hero__arrow--next",
      prevEl: ".hero__arrow--prev",
    },
  });
})();
