import gulp from "gulp";
import del from "del";
import browserSync from "browser-sync";
import sourcemaps from "gulp-sourcemaps";

import uglify from "gulp-uglify";
import webpack from "webpack-stream";

/**
 * @source https://stackoverflow.com/questions/40096470/get-webpack-not-to-bundle-files
 * Чтобы добавить новый выходной файл — пропиши его сюда
 * - [ ] Вынести в отдельный конфиг jsEntryPoints
 */
const jsEntryPoints = {
  index: "/src/js/index.js",
  // product: "/src/js/product.js",
  // new: "/src/js/new.js"
}

export default function jsBuild() {
  del("./dist/js/**/*.js");
  return gulp
    .src(app.path.src.js, { sourcemaps: app.isDev })
    .pipe(
      webpack({
        mode: app.isDev ? "development" : "production",
        entry: jsEntryPoints,
        output: {
          filename: "[name].min.js",
          sourceMapFilename: './[name].js.map'
        },
        module: {
          rules: [
            // package.json имеет "type": "module" — из-за этого webpack по умолчанию
            // разбирает все .js как строгий ESM. Вендорный inputmask.js собран в CommonJS
            // (module.exports = ...) и в строгом ESM-режиме не отдаёт вообще ни одного
            // экспорта («module has no exports»). Возвращаем этому файлу обычный разбор,
            // чтобы `import * as` в _inputmask-init.js видел его CommonJS-экспорт.
            {
              test: /src[\\/]js[\\/]libs[\\/]inputmask\.js$/,
              type: "javascript/auto",
            },
          ],
        },
      })
    )
    .pipe(sourcemaps.init())
    .pipe(uglify())
    .pipe(sourcemaps.write("../maps"))
    .pipe(gulp.dest(app.path.build.js))
    .pipe(browserSync.reload({ stream: true }));
}
