import gulp from "gulp";
import del from "del";
import browserSync from "browser-sync";
import newer from "gulp-newer";

export default function resourcesBuild() {
  del(`${app.path.build.resources}**/*`);
  return gulp
    .src(app.path.src.resources)
    .pipe(gulp.dest(app.path.build.resources))
    .pipe(browserSync.reload({ stream: true }));
}
