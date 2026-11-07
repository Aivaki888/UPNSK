# Дари — подарочные сертификаты

Учебный сайт на **HTML, CSS, JavaScript, PHP и MySQL**: регистрация и авторизация, профиль, светлая/тёмная тема и покупка подарочного сертификата с выбором дизайна и критериев.

## Страницы

1. `index.php` — главная  
2. `catalog.php` — каталог дизайнов  
3. `order.php` — оформление / покупка сертификата  
4. `about.php` — о проекте  
5. `login.php` — вход  
6. `register.php` — регистрация  
7. `profile.php` — профиль  
8. `certificates.php` — мои сертификаты  

## Возможности

- Регистрация, вход, выход, редактирование профиля и смена пароля  
- Светлая и тёмная тема (переключатель в шапке, сохранение в `localStorage` и cookie)  
- Покупка сертификата: картинка, сумма, повод, получатель, акцент, доставка, сообщение  
- История сертификатов с уникальным кодом в MySQL  

## Требования

- PHP 8.1+ с расширением `pdo_mysql`  
- MySQL 8 / MariaDB 10+  

## Быстрый старт

```bash
# 1. Создайте БД и пользователя (пример для MariaDB/MySQL)
sudo mysql -e "CREATE DATABASE IF NOT EXISTS giftshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'giftuser'@'localhost' IDENTIFIED BY 'giftpass';
GRANT ALL PRIVILEGES ON giftshop.* TO 'giftuser'@'localhost';
FLUSH PRIVILEGES;"

# 2. Импортируйте схему и демо-данные
php scripts/init_db.php

# 3. Запустите встроенный сервер PHP
php -S 127.0.0.1:8765 -t .
```

Откройте в браузере: `http://127.0.0.1:8765`

### Демо-аккаунт

- Email: `demo@dari.local`  
- Пароль: `demo123`

## Переменные окружения (опционально)

| Переменная | По умолчанию |
|---|---|
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_NAME` | `giftshop` |
| `DB_USER` | `giftuser` |
| `DB_PASS` | `giftpass` |

## Структура

```
config/database.php   — PDO-подключение
includes/             — bootstrap, header, footer, helpers
assets/css|js|img     — стили, скрипты, SVG-дизайны
sql/schema.sql        — схема MySQL
scripts/init_db.php   — инициализация БД
```
