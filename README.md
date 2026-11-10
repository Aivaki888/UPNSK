# PlayGift — пополнение игровых кошельков

Учебный сайт на **HTML, CSS, JavaScript, PHP и MySQL**: регистрация и авторизация, профиль, светлая/тёмная тема и покупка сертификатов **только на пополнение кошельков** PlayStation, Xbox и Steam.

## Страницы

1. `index.php` — главная  
2. `catalog.php` — каталог платформ  
3. `order.php` — оформление / покупка сертификата  
4. `about.php` — о проекте  
5. `login.php` — вход  
6. `register.php` — регистрация  
7. `profile.php` — профиль  
8. `certificates.php` — мои сертификаты  
9. `verify.php` — проверка кода  
10. `contact.php` — контакты  

## Требования

- PHP 8.1+ с расширением `pdo_mysql`  
- MySQL 8 / MariaDB 10+  

## Быстрый старт

### VS Code / Cursor

1. Открой папку проекта в VS Code.  
2. Убедись, что установлены PHP и MySQL, БД создана (см. ниже).  
3. `Terminal` → `Run Task…` → **PlayGift: Init DB and start website**  
   или нажми `Ctrl+Shift+B` (задача по умолчанию).  
4. Открой http://127.0.0.1:8765 в браузере  
   или `Run and Debug` → **PlayGift: Open in browser** (нужен Chrome).

### Windows (XAMPP / OpenServer)

1. Установи [XAMPP](https://www.apachefriends.org/) или OpenServer.  
2. Запусти **Apache** и **MySQL**.  
3. Склонируй проект в папку сайта, например `C:\xampp\htdocs\UPNSK`.  
4. Открой phpMyAdmin → создай БД `giftshop`.  
5. В MySQL выполни:

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

### Linux / macOS

```bash
chmod +x start.sh
./start.sh
```

### Демо-аккаунт

- Email: `demo@playgift.local`  
- Пароль: `demo123`

## Переменные окружения (опционально)

| Переменная | По умолчанию |
|---|---|
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_NAME` | `giftshop` |
| `DB_USER` | `giftuser` |
| `DB_PASS` | `giftpass` |

## Картинки и логотип

Все изображения вставляются вручную в `assets/img/`. Пока файлов нет, на сайте видны пустые области.

| Файл | Куда попадает |
|---|---|
| `assets/img/logo.png` | логотип в шапке и подвале |
| `assets/img/hero.jpg` | баннер на главной |
| `assets/img/auth.jpg` | вход / регистрация |
| `assets/img/about.jpg` | страница «О нас» |
| `assets/img/designs/01.jpg` … `06.jpg` | дизайны PS / Xbox / PC |

## Структура

```
config/database.php   — PDO-подключение
includes/             — bootstrap, header, footer, helpers
assets/css|js|img     — стили, скрипты, места под картинки
sql/schema.sql        — схема MySQL
scripts/init_db.php   — инициализация БД
start.bat / start.sh  — запуск сайта
```
