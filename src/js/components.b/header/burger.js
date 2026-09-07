import {bodyLock} from '../../helpers.b/action-helpers.js'
import {getClickedNotBeyondElement} from '../../helpers.b/get-helpers.js'

const burger = document.getElementById("burger");
const header = document.querySelector(".header");
const menu = document.querySelector('.menu');
const HEADER_OPENED_CLASS = "header--burger-opened";
const BURGER_OPENED = "is-active";
const MENU_OPENED_CLASS = "menu--visible";
const OPENED_BURGER_LOCKS_SCROLL = false;

window.addEventListener('click', (e) => {
  if (!menu.classList.contains(MENU_OPENED_CLASS)) return;
  if (e.target.classList.contains('burger')) return;
  if (document.querySelector('.b_modal__overlay._show')) return;

  if (getClickedNotBeyondElement(e, 'menu') != false) return;

  closeBurger();
  
})

export function openMenu() {
  if (!menu) return;
  menu.classList.add(MENU_OPENED_CLASS);
}

export function closeMenu() {
  if (!menu) return;
  menu.classList.remove(MENU_OPENED_CLASS);
}

export function openBurger() {
  header.classList.add(HEADER_OPENED_CLASS);
  burger.classList.add(BURGER_OPENED)

  openMenu();

  if (OPENED_BURGER_LOCKS_SCROLL) {
    bodyLock(true);
  } else {
    bodyLock(true);
  }
  const stickyHeader = document.querySelector('.page-heading--sticky-run');

  if (!stickyHeader) return;

  stickyHeader.style.transform = '';
}
window.openBurger = openBurger

export function closeBurger() {
  header.classList.remove(HEADER_OPENED_CLASS);
  burger.classList.remove(BURGER_OPENED)
  closeMenu();
  bodyLock(false);
}
window.closeBurger = closeBurger

// burger.b_close = closeBurger;
// burger.b_open = openBurger;

if (header && burger) {
  burger.addEventListener("click", () => {
    if (header.classList.contains(HEADER_OPENED_CLASS)) {
      closeBurger();
    } else {
      openBurger();
    }
  });
}
