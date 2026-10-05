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
TONSOR_USER="${TONSOR_USER:-tonsor}"
TONSOR_USER_PASSWORD="${TONSOR_USER_PASSWORD:-tonsor12345}"
TONSOR_USER_EMAIL="${TONSOR_USER_EMAIL:-tonsor@tonsorbarber.local}"
# Версии плагинов закреплены на тех, на которых собран и проверен сайт (локалка и стейджинг).
# При обновлении плагинов поднимать номера здесь (или переопределять через .env).
ACF_VERSION="${ACF_VERSION:-6.8.10}"
SCPO_VERSION="${SCPO_VERSION:-2.8.8}"
DUPLICATE_POST_VERSION="${DUPLICATE_POST_VERSION:-4.7}"
YOAST_SEO_VERSION="${YOAST_SEO_VERSION:-28.5}"
# 1 — разрешить понижение версии плагина (по умолчанию не понижаем, см. блок «Плагины»).
ALLOW_PLUGIN_DOWNGRADE="${ALLOW_PLUGIN_DOWNGRADE:-0}"

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

echo "==> Плагины..."
# Идемпотентно: нужная версия уже стоит — только активируем; стоит более старая — доводим до закреплённой.
# Более НОВАЯ версия, чем закреплённая, не понижается молча: плагин мог уже провести миграции БД,
# и откат кода под них небезопасен. Печатаем предупреждение и идём дальше с тем, что стоит;
# осознанный откат — ALLOW_PLUGIN_DOWNGRADE=1 (лучше на свежей БД).
PLUGIN_PINS="
advanced-custom-fields:$ACF_VERSION
duplicate-post:$DUPLICATE_POST_VERSION
simple-custom-post-order:$SCPO_VERSION
wordpress-seo:$YOAST_SEO_VERSION
"
for pin in $PLUGIN_PINS; do
  slug="${pin%%:*}"
  want="${pin#*:}"
  have=""
  if wp plugin is-installed "$slug" >/dev/null 2>&1; then
    have="$(wp plugin get "$slug" --field=version)"
  fi
  if [ "$have" = "$want" ]; then
    echo "$slug $want — уже нужной версии."
  elif [ -z "$have" ]; then
    wp plugin install "$slug" --version="$want"
  elif [ "$(printf '%s\n%s\n' "$have" "$want" | sort -V | head -n1)" = "$want" ] && [ "$ALLOW_PLUGIN_DOWNGRADE" != "1" ]; then
    echo "ВНИМАНИЕ: $slug $have новее закреплённой $want — не понижаю (возможны миграции БД). Поднимите номер в bootstrap.sh/.env или задайте ALLOW_PLUGIN_DOWNGRADE=1." >&2
  else
    wp plugin install "$slug" --version="$want" --force
  fi
  wp plugin activate "$slug"
  wp language plugin install "$slug" ru_RU >/dev/null 2>&1 || true
done

echo "==> Базовые настройки SEO-плагина (контентные поля — в seed.php)..."
wp option patch update wpseo_titles company_or_person company
wp option patch update wpseo_titles disable-attachment true

echo "==> Тема tonsor..."
wp theme activate tonsor

echo "==> Настройки плагинов: дубликат карточки создаётся черновиком, порядок работ..."
wp option update duplicate_post_copystatus 0

echo "==> Учётка клиента ($TONSOR_USER, роль «SEO Manager» от Yoast = Editor + wpseo_manage_options)..."
# Штатная роль Yoast создаётся при активации плагина: права Editor + глобальные настройки Yoast SEO
# (без manage_options — плагины, темы, пользователи и общие настройки WP по-прежнему недоступны).
if ! wp role exists wpseo_manager >/dev/null 2>&1; then
  echo "Роль wpseo_manager не найдена — Yoast SEO не активирован?" >&2
  exit 1
fi
if wp user get "$TONSOR_USER" >/dev/null 2>&1; then
  wp user update "$TONSOR_USER" --role=wpseo_manager
else
  wp user create "$TONSOR_USER" "$TONSOR_USER_EMAIL" --role=wpseo_manager --user_pass="$TONSOR_USER_PASSWORD"
fi

echo "==> Комментарии отключены целиком (сайт их не использует нигде)..."
wp option update default_comment_status closed
wp option update default_ping_status closed

echo "==> Готово."
wp option get siteurl
wp theme list
