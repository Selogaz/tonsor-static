// Импорт основного модула
import gulp from "gulp";

import browserSync from "browser-sync";
import del from "del";
import htmlBuild from "./gulp/tasks/html.js";
import cssBuild from "./gulp/tasks/css.js";
import jsBuild from "./gulp/tasks/js.js";
import { imagesBuild, convertImages, imagesCopy } from "./gulp/tasks/images.js";
import resourcesBuild from "./gulp/tasks/resources.js";
import fontsBuild from "./gulp/tasks/fonts.js";

// Импорт общих путей
import path from "./gulp/config/path.js";
// Импорт общих плагинов
import plugins from "./gulp/config/plugins.js";

global.app = {
  isProd: process.argv.includes("--production"),
  isDev: !process.argv.includes("--production"),
  path,
  gulp,
  plugins,
};

function webServer() {
  browserSync.init({
    // proxy: "", // Для работы с OpenServer (php) указываем папку, с которой работаем в OpenServer.
    server: {
      baseDir: "./dist",
      index: "index.html",
    },
    notify: false,
    port: 3000,
    online: false,
  });
}

// Файлы, за изменением которых gulp будет следить
function watchFiles() {
  /* eslint-disable no-undef */
  gulp.watch(app.path.watch.html, htmlBuild);
  gulp.watch(app.path.watch.scss, cssBuild);
  gulp.watch(app.path.watch.js, jsBuild);
  gulp.watch(app.path.watch.images, imagesBuild);
  // gulp.watch(app.path.watch.images, convertImages);
  gulp.watch(app.path.watch.svg, imagesBuild);
  gulp.watch(app.path.watch.resources, resourcesBuild);
  gulp.watch(app.path.watch.fonts, fontsBuild);
  /* eslint-enable no-undef */
}

// Удалить папку с build
async function cleanDist() {
  // eslint-disable-next-line no-undef
  await del(app.path.buildFolder);
}

// Серия выполнения команд
const tasks = gulp.series(
  htmlBuild,
  cssBuild,
  jsBuild,
  convertImages,
  imagesBuild,
  resourcesBuild,
  fontsBuild,
  imagesCopy
);

// Серия выполнения команд для разработки
export const dev = gulp.series(
  cleanDist,
  tasks,
  gulp.parallel(watchFiles, webServer)
);

// Серия выполнения команд на продакшн с предварительной компиляцией изображений
export const prod = gulp.series(cleanDist, tasks);

// Серия выполнения команд на продакшн с копированием скомпилированных изображений
export const prodCopyImages = gulp.series(
  cleanDist,
  gulp.series(
    htmlBuild,
    cssBuild,
    jsBuild,
    convertImages,
    imagesBuild,
    imagesCopy,
    resourcesBuild,
    fontsBuild
  )
);

export { convertImages };

gulp.task("default", dev);
