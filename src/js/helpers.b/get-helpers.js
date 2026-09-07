/**
 * offsetPage(elem) - определение расстояния между элементом и верхней границей страницы
 */
function offsetPage(elem) {
    var rect = elem.getBoundingClientRect(),
    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
}

// Возвращает рандомное целое число включительно max
export function getRandomInt(min, max) {
  return (
    Math.floor(Math.random() * (Math.floor(max) + 1 - Math.ceil(min))) +
    Math.ceil(min)
  );
}

/**
 * getCoords(elem) - получает координаты элемента, относительно страницы
 */
function getCoords(elem) {
    var box = elem.getBoundingClientRect();

    return {
      top: box.top + pageYOffset,
      left: box.left + pageXOffset
    };
}

/**
 * getCookie(name) - возвращает cookie с указанным именем
 * name - имя куки
 * Если ничего не будет найдено, вернет undefined
 */
function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}


// Получаем все соседние элементы
export function getSiblings(elem) {
  const siblings = [];
  let sibling = elem;

  while (sibling.previousSibling) {
    sibling = sibling.previousSibling;
    sibling.nodeType == 1 && siblings.push(sibling);
  }

  sibling = elem;
  while (sibling.nextSibling) {
    sibling = sibling.nextSibling;
    sibling.nodeType == 1 && siblings.push(sibling);
  }

  return siblings;
}

/*
  Проверяет был ли клик за пределами выбранного блока
 */
export function getClickedNotBeyondElement(e, selector) {
    // let isElementClicked = false;
    let clickedElement;
    const path = e.path || (e.composedPath && e.composedPath());
    const isSelect = path.map((item, index, pathElems) => {
      if (pathElems.length - 4 < index) return;
      if (item.classList.contains(selector)) {
        // isElementClicked = true;
        clickedElement = item;
      }
    })
    if (clickedElement !== undefined) return clickedElement;
    return false
    // return isElementClicked;
}

export function getGradientFromImageSrc(imageSrc) {
    return new Promise((resolve, reject) => {
      var img = new Image();
      img.crossOrigin = "Anonymous";
      img.onload = function() {
        var canvas = document.createElement("canvas");
        canvas.width = this.width;
        canvas.height = this.height;

        var ctx = canvas.getContext("2d");
        ctx.drawImage(this, 0, 0);

        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        var pixels = imageData.data;

        var pixelArray = [];

        for (var i = 0; i < pixels.length; i += 4) {
          var r = pixels[i];
          var g = pixels[i + 1];
          var b = pixels[i + 2];
          var a = pixels[i + 3];
          pixelArray.push({r: r, g: g, b: b, a: a});
        }

        var colorCount = {};
        var maxCount = 0;
        var dominantColor = "";

        for (var i = 0; i < pixelArray.length; i++) {
          var rgb = pixelArray[i].r + "," + pixelArray[i].g + "," + pixelArray[i].b;

          if (rgb in colorCount) {
            colorCount[rgb]++;
          } else {
            colorCount[rgb] = 1;
          }
          if (colorCount[rgb] > maxCount) {
            maxCount = colorCount[rgb];
            dominantColor = pixelArray[i];
          }
        }

        var dominantRGB = "rgba(" + dominantColor.r + ", " + dominantColor.g + ", " + dominantColor.b + ", 0.4)";
        var gradient = `linear-gradient(to bottom, transparent 0%, ${dominantRGB} 100%)`;

        resolve(gradient);
      };
      img.onerror = reject;
      img.src = imageSrc;
    });
  }
  export function getDarkenedGradientFromImageSrc(imageSrc) {
    return new Promise((resolve, reject) => {
      var img = new Image();
      img.crossOrigin = "Anonymous";
      img.onload = function() {
        var canvas = document.createElement("canvas");
        canvas.width = this.width;
        canvas.height = this.height;

        var ctx = canvas.getContext("2d");
        ctx.drawImage(this, 0, 0);

        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        var pixels = imageData.data;

        var pixelArray = [];

        for (var i = 0; i < pixels.length; i += 4) {
          var r = pixels[i];
          var g = pixels[i + 1];
          var b = pixels[i + 2];
          var a = pixels[i + 3];
          pixelArray.push({r: r, g: g, b: b, a: a});
        }

        var colorCount = {};
        var maxCount = 0;
        var dominantColor = "";

        for (var i = 0; i < pixelArray.length; i++) {
          var rgb = pixelArray[i].r + "," + pixelArray[i].g + "," + pixelArray[i].b;

          if (rgb in colorCount) {
            colorCount[rgb]++;
          } else {
            colorCount[rgb] = 1;
          }
          if (colorCount[rgb] > maxCount) {
            maxCount = colorCount[rgb];
            dominantColor = pixelArray[i];
          }
        }

        // Создание RGB значения для темного цвета
        var darkR = Math.round(dominantColor.r * 0.7);
        var darkG = Math.round(dominantColor.g * 0.7);
        var darkB = Math.round(dominantColor.b * 0.7);
        var darkRGB = "rgba(" + darkR + ", " + darkG + ", " + darkB + ", 0.4)";

        var gradient = `linear-gradient(to bottom, transparent 0%, ${darkRGB} 100%)`;

        resolve(gradient);
      };
      img.onerror = reject;
      img.src = imageSrc;
    });
  }


export function getMaxDaysFromString(str) {
  // Регулярное выражение для извлечения чисел из строки
  const numberRegex = /\d+/g;

  // Извлекаем все числа из строки
  const matches = str.match(numberRegex);

  if (matches) {
    // Преобразуем найденные числа в массив чисел
    const numbers = matches.map(match => parseInt(match, 10));

    // Если есть более одного числа, возвращаем максимальное значение
    if (numbers.length > 1) {
      return [Math.min(...numbers), Math.max(...numbers)];
    }

    // Если есть только одно число, возвращаем его
    return numbers[0];
  }

  // Если числа не найдены, возвращаем null или другое значение по умолчанию
  return null;
}

