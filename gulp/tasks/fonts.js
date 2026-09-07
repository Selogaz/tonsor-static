import gulp from "gulp";
import del from "del";
import browserSync from "browser-sync";
import newer from "gulp-newer";

// Копирует локальные шрифты (woff2/woff) в dist/fonts/.
// CSS ссылается на них как ../fonts/ (см. replace в gulp/tasks/css.js).
export default function fontsBuild() {
  del("./dist/fonts/**/*");
  return gulp
    .src(app.path.src.fonts)
    .pipe(newer(app.path.build.fonts))
    .pipe(gulp.dest(app.path.build.fonts))
    .pipe(browserSync.reload({ stream: true }));
}
