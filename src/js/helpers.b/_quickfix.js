// Quickfix helpers

window.screenWidth = {
  tablet: 991,
};

// Lazy load images
document.addEventListener("DOMContentLoaded", () => {
  const lazyImages = document.querySelectorAll("img[loading='lazy']");
  if ("loading" in HTMLImageElement.prototype) {
    // Native lazy loading supported
  } else {
    // Fallback — could load a lazyload polyfill here
  }
});
