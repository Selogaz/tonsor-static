#!/usr/bin/env bash
# Идемпотентная настройка локального окружения WordPress в Docker.
# Запуск: wordpress/bin/bootstrap.sh (нужно, чтобы docker compose up -d уже отработал).

set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$DIR"

if [ -f .env ]; then
  set -a
  # shellcheck disable=SC1091
  source .env
  set +a
fi

SITE_URL="${SITE_URL:-http://localhost:8080}"
WP_TITLE="${WP_TITLE:-TONSOR}"
WP_ADMIN_USER="${WP_ADMIN_USER:-admin}"
WP_ADMIN_PASSWORD="${WP_ADMIN_PASSWORD:-admin12345}"
WP_ADMIN_EMAIL="${WP_ADMIN_EMAIL:-admin@tonsorbarber.local}"

wp() {
  docker compose --profile tools run --rm -T wpcli "$@"
}

echo "==> Жду БД..."
tries=0
until wp db query "SELECT 1" >/dev/null 2>&1; do
  tries=$((tries + 1))
  if [ "$tries" -ge 60 ]; then
    echo "БД не поднялась за отведённое время" >&2
    exit 1
  fi
  sleep 2
done
echo "БД готова."

echo "==> Ядро WordPress..."
if wp core is-installed >/dev/null 2>&1; then
  echo "Ядро уже установлено — пропускаю core install."
else
  wp core install \
    --url="$SITE_URL" \
    --title="$WP_TITLE" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email
fi

echo "==> Локализация ядра (интерфейс админки — русский, решение пользователя 28.09)..."
wp language core install ru_RU --activate

echo "==> Часовой пояс, формат даты, постоянные ссылки..."
wp option update timezone_string "Europe/Prague"
wp option update date_format "j.n.Y"
wp option update time_format "H:i"
wp rewrite structure "/%postname%/" --hard

echo "==> blog_public=0 на локалке (не индексировать)..."
wp option update blog_public 0

echo "==> Чистка примерных данных (по слагам — не трогает контент, засеянный позже)..."
HELLO_ID="$(wp post list --post_type=post --name=hello-world --format=ids)"
if [ -n "$HELLO_ID" ]; then
  wp post delete "$HELLO_ID" --force
fi
SAMPLE_PAGE_ID="$(wp post list --post_type=page --name=sample-page --format=ids)"
if [ -n "$SAMPLE_PAGE_ID" ]; then
  wp post delete "$SAMPLE_PAGE_ID" --force
fi
wp plugin deactivate hello >/dev/null 2>&1 || true
wp plugin delete hello >/dev/null 2>&1 || true
wp plugin deactivate akismet >/dev/null 2>&1 || true
wp plugin delete akismet >/dev/null 2>&1 || true

echo "==> Плагины (план §4; Yoast SEO — НЕ ставим, это W9)..."
PLUGINS="advanced-custom-fields duplicate-post simple-custom-post-order"
for slug in $PLUGINS; do
  if wp plugin is-installed "$slug" >/dev/null 2>&1; then
    wp plugin activate "$slug"
  else
    wp plugin install "$slug" --activate
  fi
  wp language plugin install "$slug" ru_RU >/dev/null 2>&1 || true
done

echo "==> Тема tonsor..."
wp theme activate tonsor

echo "==> Готово."
wp option get siteurl
wp theme list
