"use strict";

// Portfolio barberů (юнит U6): 5 фильтров (десктоп — табы, мобилка — `<select>`, одна
// логика на оба), карусель работ (Swiper, паттерн ТЗ — `.swiper-button-prev/next` на
// уровне со `.swiper`, не внутри). Фильтрация — переключением класса на слайдах +
// `swiper.update()` (решение юнита: пересборка инстанса Swiper на каждый клик была бы
// сложнее и не даёт заметного выигрыша при 10 слайдах).
//
// Публичный API для юнита U13 (связка `Ukázky práce` → портфолио):
//   window.tonsorPortfolio.setFilter(slug) — включить фильтр извне (таб/select синхронно).
//   window.tonsorPortfolio.getFilter() — текущий активный slug.
(() => {
  const section = document.querySelector(".portfolio");
  if (!section || !window.Swiper) return;

  const sliderEl = section.querySelector(".portfolio__slider");
  const tabs = Array.from(section.querySelectorAll(".portfolio__tab"));
  const select = section.querySelector(".portfolio__select");
  const slides = Array.from(section.querySelectorAll(".portfolio__slide"));

  const swiper = new window.Swiper(sliderEl, {
    slidesPerView: "auto",
    spaceBetween: 12,
    watchOverflow: true,
    navigation: {
      nextEl: section.querySelector(".portfolio__arrow--next"),
      prevEl: section.querySelector(".portfolio__arrow--prev"),
    },
    breakpoints: {
      992: {
        slidesPerView: 4,
        spaceBetween: 24,
      },
    },
  });

  let currentFilter = null;

  function applyFilter(slug) {
    if (!slug || slug === currentFilter) return;
    currentFilter = slug;

    slides.forEach((slide) => {
      slide.classList.toggle("portfolio__slide--hidden", slide.dataset.category !== slug);
    });

    tabs.forEach((tab) => {
      const isActive = tab.dataset.filter === slug;
      tab.classList.toggle("portfolio__tab--active", isActive);
      tab.setAttribute("aria-selected", String(isActive));
    });

    if (select && select.value !== slug) select.value = slug;

    swiper.update();
    swiper.slideTo(0, 0);
  }

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => applyFilter(tab.dataset.filter));
  });

  if (select) {
    select.addEventListener("change", () => applyFilter(select.value));
  }

  const initialFilter = (tabs[0] && tabs[0].dataset.filter) || (slides[0] && slides[0].dataset.category);
  applyFilter(initialFilter);

  window.tonsorPortfolio = {
    setFilter: applyFilter,
    getFilter: () => currentFilter,
  };
})();
