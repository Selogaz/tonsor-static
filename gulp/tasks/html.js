import gulp from "gulp";
import del from "del";
import browserSync from "browser-sync";

import fileinclude from "gulp-file-include";
import replace from "gulp-replace";
import versionNumber from "gulp-version-number";

// webp у фото — явной разметкой в партиалах (<picture><source type="image/webp">…),
// не этим шагом сборки: gulp-webp-html-nosvg трогает только голый <img> вне <picture>,
// а по конвенции проекта все контентные картинки уже обёрнуты в <picture> — плагин
// молчаливо не сделал бы ничего. См. gulp/tasks/images.js → convertImages (U16).
export default function htmlBuild() {
  del("./dist/*.html");
  return (
    gulp
      .src(app.path.src.html)
      .pipe(fileinclude())
      .pipe(replace(/@img\//g, "./img/"))
      .pipe(app.plugins.if(app.isProd, replace("style.css", "style.min.css")))
      .pipe(
        versionNumber({
          value: "%DT%", // добавляем дату и время в мс
          append: {
            key: "_v",
            cover: 0,
            // "preload" — отдельный тип детекции у gulp-version-number (`<link rel="preload">`
            // не подпадает под "css", т.к. тот матчит только `rel="stylesheet"`), нужен явно,
            // иначе `<link rel="preload" href="./css/style.min.css">` остаётся без `?_v=…`,
            // расходится с версионированным `<link rel="stylesheet">` на ту же версию файла
            // и браузер ругается «preload not used» (см. QA1).
            to: ["css", "js", "preload"],
          },
          output: {
            file: "./gulp/version.json",
          },
        })
      )
      .pipe(gulp.dest(app.path.build.html))
      .pipe(browserSync.reload({ stream: true }))
  );
}
