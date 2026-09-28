#!/usr/bin/env bash
# Идемпотентный сид текущего контента лендинга в БД WordPress (содержимое — seed/content.php).
# Запуск: wordpress/bin/seed.sh (нужно, чтобы docker compose up -d и bootstrap.sh уже отработали).
# Полная пересидка (удалить всё засеянное и залить заново): wordpress/bin/seed.sh --reset
# (или переменная окружения TNS_SEED_RESET=1).

set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$DIR"

RESET="${TNS_SEED_RESET:-0}"
for arg in "$@"; do
  if [ "$arg" = "--reset" ]; then
    RESET=1
  fi
done

echo "==> Сид контента (TNS_SEED_RESET=$RESET)..."
docker compose --profile tools run --rm -T -e TNS_SEED_RESET="$RESET" wpcli eval-file /seed/seed.php
