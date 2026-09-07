/**
 * @bayan
 * 
 * Первый элемент внутри .bayan будет шапкой, второй будет открывающейся частью
 * 
 * 
 */
const bayans = [...document.querySelectorAll(".bayan")];
const bayanOpenedClass = "bayan--opened";
// const bayanHeight = 2000;

function openBayan(bayanObject) {
  const {top, bottom, bayan} = bayanObject;
  top.bayan.parentElement.classList.add(bayanOpenedClass);
  bottom.bayan.querySelectorAll("a").forEach((anchor) => {
    anchor.setAttribute("tabindex", "0");
  });

  const bayanOpen = new CustomEvent("bayan-open", {
    detail: {
    },
  });
  bayan.dispatchEvent(bayanOpen);
}

function closeBayan(bayanObject) {
  const {top, bottom, bayan} = bayanObject;

  top.bayan.parentElement.classList.remove(bayanOpenedClass);
  bottom.bayan.querySelectorAll("a").forEach((anchor) => {
    anchor.setAttribute("tabindex", "-1");
  });
  setTimeout(() => {
    // bayanObject.bottom.bayan.style.display = "none";
  }, 401);

  const bayanClose = new CustomEvent("bayan-close", {
    detail: {
    },
  });
  bayan.dispatchEvent(bayanClose);
}

function toggleBayan(bayanObject) {
  if (!bayanObject) return;

  if (
    bayanObject.top.bayan.parentElement.classList.value.includes(
      bayanOpenedClass
    )
  ) {
    closeBayan(bayanObject);
  } else {
    openBayan(bayanObject);
  }
}

function createBayans(bayans) {
  bayans.forEach((bayan, index) => {
    let [bayanTopContent, bayanBottomContent] = Array.from(bayan.children);

    let bayanObject = {
      top: {
        content: bayanTopContent,
      },
      bottom: {
        content: bayanBottomContent,
      },
      bayan,
    };

    bayan.openBayan = () => {
      openBayan(bayanObject);
    }
    bayan.closeBayan = () => {
      closeBayan(bayanObject);
    }

    function createBayanStructure(bayanObject) {
      let bayanTop = document.createElement("div");
      bayanTop.classList.add("bayan__top");
      bayanTop.appendChild(bayanTopContent);
      bayanObject.bayan.appendChild(bayanTop);
      bayanObject.top.bayan = bayanTop;

      let bayanBottom = document.createElement("div");
      bayanBottom.classList.add("bayan__bottom");
      bayanBottom.appendChild(bayanBottomContent);
      bayanObject.bayan.appendChild(bayanBottom);
      bayanObject.bottom.bayan = bayanBottom;

      let bayanToggler = document.createElement('button')
      bayanToggler.type = 'button';
      bayanToggler.classList.add('bayan__toggler');
      bayanTopContent.appendChild(bayanToggler)


      bayanObject.top.bayan.addEventListener("click", (event) => {
        toggleBayan(bayanObject);
      });
    }

    createBayanStructure(bayanObject);
  });
}

if (bayans.length > 0) {
  createBayans(bayans);
}

function initBayan(className) {
  let bayans = document.querySelectorAll(className);
  if (bayans.length > 0) {
    createBayans(bayans);
  }
}

