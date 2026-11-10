#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

if ! command -v php >/dev/null 2>&1; then
  echo "PHP не найден. Установи PHP 8+ и расширение pdo_mysql."
  exit 1
fi

if ! php -m 2>/dev/null | grep -qi pdo_mysql; then
  echo "Нет расширения pdo_mysql. Установи php-mysql / php-pdo_mysql."
  exit 1
fi

echo "Инициализация базы..."
php scripts/init_db.php || {
  echo
  echo "Не удалось подключиться к MySQL."
  echo "Сначала создай БД (пример):"
  echo "  mysql -u root -e \"CREATE DATABASE giftshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  echo "  CREATE USER 'giftuser'@'localhost' IDENTIFIED BY 'giftpass';"
  echo "  GRANT ALL ON giftshop.* TO 'giftuser'@'localhost'; FLUSH PRIVILEGES;\""
  exit 1
}

PORT="${PORT:-8765}"
echo "Сайт: http://127.0.0.1:${PORT}"
echo "Демо: demo@playgift.local / demo123"
exec php -S "127.0.0.1:${PORT}" -t .
