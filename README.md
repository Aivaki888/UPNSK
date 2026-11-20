# PlayGift — пополнение игровых кошельков

Сайт на HTML, CSS, JavaScript, PHP и MySQL: регистрация, профиль, светлая/тёмная тема и покупка сертификатов на пополнение кошельков PlayStation, Xbox и Steam.

## Страницы

1. `index.php` — главная  
2. `catalog.php` — каталог  
3. `order.php` — оформление сертификата  
4. `spin.php` — колесо удачи  
5. `quiz.php` — подбор кошелька  
6. `wall.php` — стена сертификатов  
7. `leaderboard.php` — рейтинг  
8. `compare.php` — сравнение платформ  
9. `guide.php` — гид по подаркам  
10. `about.php` — о проекте  
11. `login.php` / `register.php` — вход и регистрация  
12. `profile.php` — профиль  
13. `certificates.php` — мои сертификаты  
14. `verify.php` — проверка кода  
15. `contact.php` — контакты  

`Ctrl/⌘ + K` — быстрый переход по страницам.

## Требования

- PHP 8.1+ с `pdo_mysql`  
- MySQL 8 / MariaDB 10+  

## Переменные окружения

| Переменная | По умолчанию |
|---|---|
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_NAME` | `giftshop` |
| `DB_USER` | `giftuser` |
| `DB_PASS` | `giftpass` |

## Картинки

Папка: `assets/img/`. Можно заменить файлы, сохранив имена.

| Файл | Назначение |
|---|---|
| `assets/img/logo.png` | логотип |
| `assets/img/theme-icon.png` | иконка темы |
| `assets/img/hero.jpg` | баннер главной |
| `assets/img/auth.jpg` | вход / регистрация |
| `assets/img/about.jpg` | страница «О нас» |
| `assets/img/designs/01.jpg` … `06.jpg` | дизайны |

## Структура

```
config/database.php
includes/
assets/css|js|img
sql/schema.sql
scripts/init_db.php
start.bat / start.sh
```
