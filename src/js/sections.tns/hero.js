"use strict";

// Hero-слайдер (юнит U2): 3 слайда, автопрокрутка, пауза при наведении. Стрелок/точек в
// макете нет ни на одной ширине (подтверждено брифом) — точки-пагинация добавлены решением
// юнита, кликабельны, не ломают композицию (см. _hero.scss → «.hero__pagination»).
(() => {
  const el = document.querySelector(".hero__slider");
  if (!el || !window.Swiper) return;

  new window.Swiper(el, {
    loop: true,
    speed: 600,
    autoplay: {
      delay: 6000,
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },
    pagination: {
      el: ".hero__pagination",
      clickable: true,
      bulletElement: "button",
      bulletClass: "hero__dot",
      bulletActiveClass: "is-active",
    },
  });
})();
