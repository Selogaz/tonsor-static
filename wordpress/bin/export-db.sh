#!/usr/bin/env bash
# Дамп БД WordPress для переноса на другой хост (стейджинг/прод) с заменой URL.
# Запуск: wordpress/bin/export-db.sh [целевой_URL] [файл_дампа]
#   целевой_URL  — по умолчанию https://staging.tonsorbarber.cz (без слеша на конце)
#   файл_дампа   — по умолчанию /tmp/tonsor-db-<хост>.sql
# Локальная БД НЕ меняется: `wp search-replace --export` пишет преобразованный SQL в файл,
# а не в базу; сериализованные данные (ACF, опции Yoast) пересчитываются корректно.
# Заменяется URL того экземпляра, из которого идёт экспорт (SITE_URL из .env, по умолчанию
# http://localhost:8080). Нужно, чтобы docker compose up -d и bootstrap.sh уже отработали.

set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$DIR"

if [ -f .env ]; then
  set -a
  # shellcheck disable=SC1091
  source .env
  set +a
fi

SOURCE_URL="${SITE_URL:-http://localhost:8080}"
TARGET_URL="${1:-https://staging.tonsorbarber.cz}"
TARGET_URL="${TARGET_URL%/}"
TARGET_HOST="${TARGET_URL#*://}"
OUT="${2:-/tmp/tonsor-db-${TARGET_HOST//[^A-Za-z0-9.-]/_}.sql}"

wp() {
  docker compose --profile tools run --rm -T wpcli "$@"
}

if [ "$SOURCE_URL" = "$TARGET_URL" ]; then
  echo "Исходный и целевой URL совпадают ($SOURCE_URL) — нечего заменять" >&2
  exit 1
fi

echo "==> Проверка источника..."
wp core is-installed
CURRENT_URL="$(wp option get siteurl)"
if [ "$CURRENT_URL" != "$SOURCE_URL" ]; then
  echo "siteurl в БД ($CURRENT_URL) не равен SITE_URL ($SOURCE_URL) — экспорт остановлен" >&2
  exit 1
fi
# Стейджинг и прод до приёмки закрыты от индексации (решение п.12 плана): в дампе blog_public=0.
if [ "$(wp option get blog_public)" != "0" ]; then
  echo "blog_public != 0 — экспорт остановлен (запусти bootstrap.sh)" >&2
  exit 1
fi

echo "==> Дамп с заменой $SOURCE_URL -> $TARGET_URL (БД не меняется)..."
TMP="$(mktemp)"
trap 'rm -f "$TMP"' EXIT
# --export без файла пишет SQL в stdout; статус и ошибки WP-CLI идут в stderr.
wp search-replace "$SOURCE_URL" "$TARGET_URL" --all-tables --export > "$TMP"

# URL внутри JSON с экранированными слешами (блоки, опции Yoast) WP-CLI не трогает, а ручная
# замена ломает длины в сериализованных строках — значит, их быть не должно.
ESCAPED_SOURCE="${SOURCE_URL//\//\\/}"
if grep -qF "$ESCAPED_SOURCE" "$TMP"; then
  echo "В дампе остались URL с экранированными слешами ($ESCAPED_SOURCE) — нужна доработка" >&2
  exit 1
fi
if grep -qF "$SOURCE_URL" "$TMP"; then
  echo "В дампе остался исходный URL $SOURCE_URL" >&2
  exit 1
fi

# Совместимость с MySQL/MariaDB хостинга: коллации MariaDB 11.4+ (uca1400) и MySQL 8 (0900)
# старые серверы не знают — приводим к utf8mb4_unicode_ci (как и utf8mb4_unicode_520_ci от WP).
# Служебные таблицы Yoast создаются в utf8mb3 — имя utf8mb3 понимают не все версии MySQL 5.x,
# поэтому пишем синоним utf8 (то же самое кодирование).
sed -E -i \
  -e 's/utf8mb4_uca1400_[a-z0-9_]+/utf8mb4_unicode_ci/g' \
  -e 's/utf8mb4_0900_[a-z0-9_]+/utf8mb4_unicode_ci/g' \
  -e 's/utf8mb4_unicode_520_ci/utf8mb4_unicode_ci/g' \
  -e 's/utf8mb3_general_ci/utf8_general_ci/g' \
  -e 's/CHARSET=utf8mb3/CHARSET=utf8/g' \
  "$TMP"
if grep -qE 'uca1400|utf8mb4_0900|utf8mb3|_520_' "$TMP"; then
  echo "В дампе остались несовместимые коллации" >&2
  exit 1
fi

# Без явной кодировки соединения клиент импорта (mysql CLI, phpMyAdmin) может прочитать
# дамп как latin1 — эмодзи в отзывах тогда не импортируются (ERROR 1366).
{ echo '/*!40101 SET NAMES utf8mb4 */;'; echo; cat "$TMP"; } > "$TMP.new"
mv "$TMP.new" "$TMP"

mv "$TMP" "$OUT"
trap - EXIT
echo "==> Готово: $OUT ($(wc -c < "$OUT") байт)"
