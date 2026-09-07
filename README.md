# TONSOR

Статическая вёрстка одностраничного лендинга барбершопа TONSOR (Пльзень, Чехия).
Только вёрстка: чистые HTML/CSS/JS, без интеграции с CMS.

## Стек

Gulp 4 + dart-sass + Webpack 5 + BrowserSync, шаблонизация `@@include` (gulp-file-include).

## Команды

- `npm start` — dev-сервер (BrowserSync, `localhost:3000`, live-reload).
- `npm run prod` — продакшн-сборка в `dist/`.
- Линт: `npx stylelint "src/scss/**/*.scss"` · `npx eslint "src/js/**/*.js"` · `npx htmlhint "src/**/*.html"`.
