import AOS from "aos";

AOS.init({
  duration: 600,
  easing: "ease-out-quad",
  once: true,
  offset: 80,
});

window.AOS = AOS;
