import path from "path";

const rootFolder = path.basename(path.resolve()); // Корневая папка проекта
// Флаг --wp переключает выход сборки на ассеты темы WordPress (см. wp:build/wp:watch
// в package.json); без флага — прежнее поведение, сборка в ./dist.
const isWp = process.argv.includes("--wp");
const buildFolder = isWp ? "./wordpress/theme/tonsor/assets" : "./dist"; // Папка с файлами на продакшн
const srcFolder = "./src"; // Папка с исходниками

export default {
  build: {
    js: `${buildFolder}/js/`,
    css: `${buildFolder}/css/`,
    html: `${buildFolder}/`,
    images: `${buildFolder}/img/`,
    convertImages: `./convert-images/`,
    resources: `${buildFolder}/resources/`,
    fonts: `${buildFolder}/fonts/`,
  },

  src: {
    js: `${srcFolder}/js/index.js`,
    scss: `${srcFolder}/scss/style.scss`,
    html: `${srcFolder}/*.html`,
    images: `${srcFolder}/img/**/*.{jpg,jpeg,png,gif,webp}`,
    svg: `${srcFolder}/img/**/*.svg`,
    svgIcons: `${srcFolder}/icons-to-sprite/**/*.svg`,
    convertImages: `./convert-images/**/*.*`,
    resources: `${srcFolder}/resources/**/*.*`,
    fonts: `${srcFolder}/fonts/**/*.{woff,woff2}`,
  },

  watch: {
    js: `${srcFolder}/js/**/*.js`,
    scss: `${srcFolder}/scss/**/*.{scss,sass,css}`,
    html: `${srcFolder}/**/*.html`,
    images: `${srcFolder}/img/**/*.{jpg,jpeg,png,gif,webp}`,
    convertImages: `convert-images/**/*.*`,
    svg: `${srcFolder}/img/**/*.svg`,
    resources: `${srcFolder}/resources/**/*.*`,
    fonts: `${srcFolder}/fonts/**/*.{woff,woff2}`,
  },

  clean: buildFolder,
  buildFolder,
  srcFolder,
  rootFolder,
  isWp,
};
