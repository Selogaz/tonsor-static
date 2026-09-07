/*
 * Удаляет у всех элементов items класс itemClass
 * items - класс элементов или массив с переменными с селекторами, у которых нужно удалить класс.
 * itemsClass - класс, который нужно удалить. Указывается без точки
 *
 */
export function removeAllClasses(items, itemClass) {
  if (typeof items == "string") {
    items = document.querySelectorAll(items);
  }

  for (let i = 0; i < items.length; i++) {
    if (typeof itemClass === "object") {
      items[i].classList.remove(...itemClass);
    } else {
      items[i].classList.remove(itemClass);
    }
  }
}


// Вспомогательные модули блокировки прокрутки и резкого сдвига
// export let bodyLockStatus = true;
// export function bodyLockToggle(delay = 100) {
//   if (document.documentElement.classList.contains("_lock")) {
//     bodyUnlock(delay);
//   } else {
//     bodyLock(delay);
//   }
// }

// Разблокировать скролл
// export function bodyUnlock(delay = 100) {
//   let body = document.querySelector("body");

//   if (bodyLockStatus) {
//     let lockPadding = document.querySelectorAll("[data-lp]");

//     setTimeout(() => {
//       for (let index = 0; index < lockPadding.length; index++) {
//         const el = lockPadding[index];

//         el.style.paddingRight = "0px";
//       }

//       body.style.paddingRight = "0px";
//       document.documentElement.classList.remove("_lock");
//     }, delay);

//     bodyLockStatus = false;

//     setTimeout(function () {
//       bodyLockStatus = true;
//     }, delay);
//   }
// }

// Заблокировать скролл
// export function bodyLock(delay = 100) {
//   let body = document.querySelector("body");

//   if (bodyLockStatus) {
//     let lock_padding = document.querySelectorAll("[data-lp]");

//     for (let index = 0; index < lock_padding.length; index++) {
//       const el = lock_padding[index];

//       el.style.paddingRight =
//         window.innerWidth -
//         document.querySelector(".wrapper").offsetWidth +
//         "px";
//     }

//     body.style.paddingRight =
//       window.innerWidth - document.querySelector(".wrapper").offsetWidth + "px";
//     document.documentElement.classList.add("_lock");

//     bodyLockStatus = false;

//     setTimeout(function () {
//       bodyLockStatus = true;
//     }, delay);
//   }
// }

/**
 * Фиксирует скрол у body
 *  */

function lockBody() {
  const DEFAULT_SCROLLBAR_WIDTH = getScrollbarWidth();
  document.body.classList.add("_lock");
  document.body.style.paddingRight = `${DEFAULT_SCROLLBAR_WIDTH}px`;
}
function unlockBody() {
  const DEFAULT_SCROLLBAR_WIDTH = getScrollbarWidth();
  document.body.classList.remove("_lock");
  document.body.style.paddingRight = '';
}
function getScrollbarWidth() {
  return window.innerWidth - document.body.offsetWidth;
}
export function bodyLock(con) {
  let scrollFix = window.innerWidth - document.body.clientWidth;
  if (con === true) {
    lockBody();
  } else if (con === false) {
    unlockBody();
  } else if (con === undefined) {
    if (!document.body.classList.contains("_lock")) {
      lockBody();
    } else {
      unlockBody();
    }
  } else {
    console.error("Неопределенный аргумент у функции bodyLock()");
  }
}


/**
 * Загружает скрипт в DOM
 */
function loadScript(windowWidth, scriptPath) {
	if (window.innerWidth <= windowWidth) {
		const script = document.createElement('script')
		script.setAttribute('src', scriptPath)
		body.prepend(script)
	}
}

/**
 * @param string pass string to clipboard
 */
// eslint-disable-next-line no-unused-vars
export function copyToClipboard(text) {
  if (!text) return;

  var clipboardStorage = document.createElement("input");
  document.querySelector("body").appendChild(clipboardStorage);
  clipboardStorage.setAttribute("value", text);
  clipboardStorage.select();
  document.execCommand("copy");
  clipboardStorage.remove();
}

/**
 * Удаляет у всех элементов items класс itemClass
 *  */
function removeAll(items, itemClass) {
  if (!items && !itemClass) return;

  if (typeof items == "string") {
    items = document.querySelectorAll(items);
  }
  for (let i = 0; i < items.length; i++) {
    const item = items[i];
    item.classList.remove(itemClass);
  }
}

/**
 * Scroll page to top
 * - [ ] tested
 */
const scrollTopButtons = document.querySelectorAll(".js_scroll-top");
const scrollTop = (event) => {
  event.preventDefault();

  const id = scrollTop.getAttribute("href").substring(1);
  const section = document.querySelector(id);

  if (section) {
    document.scrollIntoView(section, {
      behavior: "smooth",
      block: "start",
      inline: "center",
    });
  }
};
scrollTopButtons.forEach((button) =>
  button.addEventListener("click", (event) => {
    scrollTop(event);
  })
);

export function preloadImages() {
  let sources = [
    window.location.origin + '/img/common/paper.png',
  ];

  for (let i = 0; i < sources.length; i++) {
    sources[i] += "?" + Math.random();
  }
}