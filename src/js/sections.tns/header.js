"use strict";

// Мобильное меню: открытие/закрытие и блокировка скролла — общий компонент
// `components.b/header/burger.js` (см. `js/index.js`, подключён раньше этого файла).
// Бургер сам анимируется в крестик (`.is-active`, компонент `components.b/lego/burger`)
// и, будучи открытым, закрепляется в углу экрана (`_header.scss`) — им же меню и закрывается,
// отдельная кнопка-крестик не нужна. Здесь — то, что макетом не покрыто (раскрытое состояние
// дровера не нарисовано): закрытие по клику на пункт меню и по Esc, синхронизация aria-expanded.
import { closeBurger } from "../components.b/header/burger.js";

const header = document.querySelector(".header");
const burger = document.getElementById("burger");
const menu = document.querySelector(".menu");
const menuLinks = document.querySelectorAll(".menu__link");

function closeMenuAndBurger() {
  closeBurger();
  if (burger) burger.setAttribute("aria-expanded", "false");
}

if (burger && header) {
  burger.addEventListener("click", () => {
    const opened = header.classList.contains("header--burger-opened");
    burger.setAttribute("aria-expanded", opened ? "true" : "false");
  });
}

menuLinks.forEach((link) => {
  link.addEventListener("click", closeMenuAndBurger);
});

document.addEventListener("keydown", (event) => {
  if (event.key !== "Escape") return;
  if (!menu || !menu.classList.contains("menu--visible")) return;

  closeMenuAndBurger();
});
