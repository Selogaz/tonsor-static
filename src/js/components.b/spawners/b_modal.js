"use strict";
import {bodyLock} from "../../helpers.b/action-helpers.js"

// Закрытие модального окна при клике по заднему фону
function closeWhenClickingOnBg(itemArray, itemParent, classShow = "_show") {
  document.addEventListener("click", (e) => {
    let itemElems = document.querySelectorAll(itemArray);

    for (let i = 0; i < itemElems.length; i++) {
      const item = itemElems[i];

      const target = e.target,
        itsItem = target == item || item.contains(target),
        itemIsShow = item.classList.contains(classShow);

      if (itemParent) {
        const itsItemParent =
            target == itemParent || itemParent.contains(target),
          itemParentIsShow = itemParent.classList.contains(classShow);

        if (!itsItem && itsItemParent && itemParentIsShow) {
          itemParent.classList.remove(classShow);

          if (document.body.classList.contains("_lock")) {
            bodyLock(false);
          }

          if (window.location.hash === "#" + itemParent.getAttribute("id")) {
            resetHash();
          }
        }
      } else {
        if (!itsItem && itemIsShow) {
          item.classList.remove(classShow);
          if (document.body.classList.contains("_lock")) {
            bodyLock(false);
          }

          if (window.location.hash === "#" + itemParent.getAttribute("id")) {
            resetHash();
          }
        }
      }
    }
  });
}

class b_modal {
  constructor() {
    this.initPopups();
    this.initButtons();

    document.addEventListener("keydown", (e) => {
      const id = this.getLastOpenedId();
      if (e.key === "Escape" && id) {
        this.closePop(id);
        // modal.classList.remove("_show");
        // bodyLock(false);
        // resetHash();
      }
    });

    this.openb_modalHash();
  }

  instances = [];
  getPopupsStorage() {
    return document.querySelector(".b_modal__storage");
  }

  initPopups() {
    const popups = document.querySelectorAll(".b_modal");
    const popupsStorage = document.createElement("div");

    popupsStorage.classList.add("b_modal__storage");
    document.body.append(popupsStorage);

    popups.forEach((b_modal) => this.makePopupWrapper(b_modal));

    window.addEventListener("click", (e) => {
      if (e.target.classList.contains("b_modal__aligner")) {
        this.closePop(e.target.querySelector(".b_modal").id);
      }
    });
  }

  makePopup(popup, id = new Date().getTime()) {
    popup.id = `p-${id}`;
    this.makePopupWrapper(popup);
  }
  makeOpenerButton(button, id) {
    button.dataset.b_modalOpen = id;
    button.addEventListener("click", () => {
      this.handleOpen(button);
    });
  }

  makePopupWrapper(b_modal) {
    let { id } = b_modal;

    const overlay = document.createElement("div");
    overlay.classList.add("b_modal__overlay");
    overlay.classList.add(`b_modal__overlay--${id}`);
    overlay.dataset.b_modalName = id;

    const aligner = document.createElement("div");
    aligner.classList.add("b_modal__aligner");

    const closer = document.createElement("button");
    closer.classList.add("b_modal__closer");
    closer.innerText = "Закрыть";
    closer.dataset.b_modalClose = id;
    const handleClose = () => {
      this.handleClose(closer);
    };
    closer.addEventListener("click", handleClose);
    // TODO: Сделай накидывание нужных ариа атрибутов

    document.querySelector(".b_modal__storage").append(overlay);
    aligner.append(b_modal);
    overlay.append(aligner);

    if (this.detectCloserType(b_modal) == 'inner') {
      b_modal.append(closer);
    } else {
      overlay.append(closer);
    }

    this.instances.push(overlay);
  }

  detectCloserType(modal) {
    if (!modal.dataset.closerType) return false;

    return modal.dataset.closerType;
  }

  initButtons() {
    const openButtons = document.querySelectorAll("[data-b_modal-open]");
    openButtons.forEach((button) => {
      button.addEventListener("click", () => {
        this.handleOpen(button);
      });
    });

    const closeButtons = document.querySelectorAll("[data-b_modal-close]");
    closeButtons.forEach((button) => {
      button.addEventListener("click", () => {
        this.handleClose(button);
      });
    });
  }

  handleOpen(button) {
    const id = button.dataset.b_modalOpen;
    const b_modal = document.querySelector(`#${id}`);
    if (b_modal) {
      this.openPop(id);
    } else {
      this.makeInfoPop(`Попапа "#${id}" нет. Проверь айди`);
    }
  }
  openPop(id) {
    const pop = this.getPop(id);
    this.getPopupsStorage().append(pop);
    setTimeout(() => {
      pop.classList.add("_show");
    });
    window.location.hash = id;
    const event = new Event("b_modal-open");
    pop.dispatchEvent(event);
    pop.querySelector(".b_modal").dispatchEvent(event);

    bodyLock(true);


    setTimeout(() => {
      const popupBody = pop.querySelector('.b_modal');
      if (popupBody.getBoundingClientRect().height > window.innerHeight) {
        pop.classList.add('b_modal--scrollable')
      }
    }, 600)

  }

  handleClose(button) {
    const id = button.dataset.b_modalClose;
    const b_modal = document.querySelector(`#${id}`);
    if (b_modal) {
      this.closePop(id);
    } else {
      this.makeInfoPop(`Попапа "#${id}" нет. Проверь айди`);
    }
  }

  closePop(id) {
    const pop = this.getPop(id);
    if (pop.classList.contains("_show")) {
      pop.classList.remove("_show");
    }
    this.resetHash();
    const event = new Event("b_modal-close");
    pop.dispatchEvent(event);
    pop.querySelector(".b_modal").dispatchEvent(event);

    if (document.querySelector('.header').classList.contains('header--opened')) return;
    bodyLock(false);
  }

  makeInfoPop(text, removeAfter = 6000) {
    const info = document.createElement("div");
    const id = "pop-" + new Date().getTime();
    info.classList.add("b_modal");
    info.classList.add("b_modal--invalid");
    info.innerHTML = text;
    info.id = id;

    document.body.append(info);

    this.makePopupWrapper(info);
    this.openPop(id);

    setTimeout(() => {
      this.getPop(id).remove();
    }, removeAfter);
  }

  getPop(id) {
    return document.querySelector(`[data-b_modal-name="${id}"]`);
  }
  getTotalPopups() {
    return document.querySelectorAll(`[data-b_modal-name]`);
  }

  getLastOpenedId() {
    // let storage = this.getPopupsStorage();
    // storage = Array.from(storage.children);
    // return storage[storage.length - 1].dataset.b_modalName;
    const opened = [...document.querySelectorAll("._show[data-b_modal-name]")];
    return opened.length == 0
      ? false
      : opened[opened.length - 1].dataset.b_modalName;
  }

  openb_modalHash() {
    const IGNORE_MODAL_AUTOOPEN = [
      'modal-success',
      'modal-error',
      'tariff',
      'modal-review-detail',
    ];
    if (window.location.hash) {
      const hash = window.location.hash.substring(1);

      if (IGNORE_MODAL_AUTOOPEN.includes(hash)) return;

      const b_modal = document.querySelector(`.b_modal#${hash}`);
      if (b_modal) {
        this.openPop(hash);
      }
    }
  }
  resetHash() {
    const windowTop = window.pageYOffset;
    window.location.hash = "";
    window.scrollTo(0, windowTop);
  }
}

const b_b_modal = new b_modal();
window.b_modal = b_b_modal;
