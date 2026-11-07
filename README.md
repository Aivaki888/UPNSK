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

## Требования

- PHP 8.1+ с расширением `pdo_mysql`  
- MySQL 8 / MariaDB 10+  

## Быстрый старт

### VS Code / Cursor

1. Открой папку проекта в VS Code.  
2. Убедись, что установлены PHP и MySQL, БД создана (см. ниже).  
3. `Terminal` → `Run Task…` → **Dari: Init DB and start website**  
   или нажми `Ctrl+Shift+B` (задача по умолчанию).  
4. Открой http://127.0.0.1:8765 в браузере  
   или `Run and Debug` → **Dari: Open in browser** (нужен Chrome).

### Windows (XAMPP / OpenServer)

1. Установи [XAMPP](https://www.apachefriends.org/) или OpenServer.  
2. Запусти **Apache** и **MySQL**.  
3. Склонируй проект в папку сайта, например `C:\xampp\htdocs\UPNSK`.  
4. Открой phpMyAdmin → создай БД `giftshop`.  
5. В MySQL выполни (через phpMyAdmin → SQL или консоль):

```sql
CREATE DATABASE IF NOT EXISTS giftshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'giftuser'@'localhost' IDENTIFIED BY 'giftpass';
GRANT ALL PRIVILEGES ON giftshop.* TO 'giftuser'@'localhost';
FLUSH PRIVILEGES;
```

6. В папке проекта дважды кликни `start.bat`  
   или в терминале:

```bat
php scripts\init_db.php
php -S 127.0.0.1:8765 -t .
```

7. Открой в браузере: http://127.0.0.1:8765

Если PHP не находится, укажи полный путь, например:

```bat
C:\xampp\php\php.exe scripts\init_db.php
C:\xampp\php\php.exe -S 127.0.0.1:8765 -t .
```

### Linux / macOS

```bash
chmod +x start.sh
./start.sh
```

Или вручную:

```bash
php scripts/init_db.php
php -S 127.0.0.1:8765 -t .
```

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
start.bat / start.sh  — запуск сайта
```
