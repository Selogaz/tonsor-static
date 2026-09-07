"use strict";

// Связка `Ukázky práce` → портфолио (юнит U13): клик по ссылке услуги с атрибутом
// `data-portfolio-filter` включает нужный фильтр в секции Portfolio (публичный API юнита U6,
// `window.tonsorPortfolio.setFilter`, синхронизирует таб и select) и плавно скроллит к
// `#portfolio`. Шапка — `position: static` (см. `_header.scss`), в потоке документа, поэтому
// компенсация высоты при скролле не нужна. Обработчик делегирован на `document`, чтобы не
// зависеть от порядка подключения и разметки карточек услуг. Ссылки без слага (не все 11 услуг
// замаплены на фильтр) и переход без JS/API — обычный якорный переход по `href="#portfolio"`
// работает как фолбэк.
document.addEventListener("click", (event) => {
  const link = event.target.closest("a[data-portfolio-filter]");
  if (!link) return;

  const slug = link.dataset.portfolioFilter;
  const portfolio = document.getElementById("portfolio");
  if (!slug || !portfolio || !window.tonsorPortfolio) return;

  event.preventDefault();
  window.tonsorPortfolio.setFilter(slug);
  portfolio.scrollIntoView({ behavior: "smooth", block: "start" });
});
