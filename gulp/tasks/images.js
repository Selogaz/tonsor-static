import gulp from "gulp";
import del from "del";
import browserSync from "browser-sync";
import newer from "gulp-newer";
import fs from "fs";
import path from "path";
import { execFileSync } from "child_process";

export function imagesBuild() {
  return (
    gulp
      .src(app.path.src.images)
      .pipe(newer(app.path.build.images))
      .pipe(gulp.dest(app.path.build.images))
      // .pipe(gulp.dest(app.path.build.images))

      .pipe(gulp.src(app.path.src.svg))
      .pipe(gulp.dest(app.path.build.images))
      .pipe(browserSync.reload({ stream: true }))
  );
}

export function imagesCopy() {
  return gulp
    .src(app.path.src.convertImages)
    .pipe(newer(app.path.build.images))
    .pipe(gulp.dest(app.path.build.images));
}

// webp-версии контентных фото: генерируются рядом с jpg/png прямо в src/img/**,
// дальше их подхватывает imagesBuild — webp уже входит в app.path.src.images.
// Унаследованный конвейер (imagesCopy из внешней ./convert-images/ + gulp-webp-html-nosvg)
// не работал: convertImages ничего не кодировал в webp, а gulp-webp-html-nosvg
// игнорирует <img>, уже обёрнутый в <picture> (по конвенции проекта обёрнуты все).
const WEBP_QUALITY = 80;
const WEBP_EXCLUDE = new Set(["og.jpg", "favicon.png", "apple-touch-icon.png"]);
const WEBP_SOURCE_EXT = new Set([".jpg", ".jpeg", ".png"]);

function walkFiles(dir, acc = []) {
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    const full = path.join(dir, entry.name);
    if (entry.isDirectory()) walkFiles(full, acc);
    else acc.push(full);
  }
  return acc;
}

export function convertImages(cb) {
  const root = path.resolve(app.path.srcFolder, "img");
  const files = fs.existsSync(root) ? walkFiles(root) : [];

  files.forEach((file) => {
    const ext = path.extname(file).toLowerCase();
    if (!WEBP_SOURCE_EXT.has(ext) || WEBP_EXCLUDE.has(path.basename(file))) return;

    const webpFile = `${file.slice(0, -ext.length)}.webp`;
    const isFresh =
      fs.existsSync(webpFile) &&
      fs.statSync(webpFile).mtimeMs >= fs.statSync(file).mtimeMs;
    if (isFresh) return;

    execFileSync("cwebp", ["-quiet", "-q", String(WEBP_QUALITY), file, "-o", webpFile]);
  });

  cb();
}
