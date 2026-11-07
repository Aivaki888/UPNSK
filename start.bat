@echo off
chcp 65001 >nul
cd /d "%~dp0"

where php >nul 2>&1
if errorlevel 1 (
  echo PHP не найден.
  echo Установи XAMPP/OpenServer или PHP с сайта windows.php.net
  echo и добавь php.exe в PATH.
  pause
  exit /b 1
)

php -m 2>nul | findstr /I "pdo_mysql" >nul
if errorlevel 1 (
  echo Нет расширения pdo_mysql. Включи extension=pdo_mysql в php.ini
  pause
  exit /b 1
)

echo Инициализация базы...
php scripts\init_db.php
if errorlevel 1 (
  echo.
  echo Не удалось подключиться к MySQL.
  echo Запусти MySQL в XAMPP/OpenServer и создай БД giftshop,
  echo пользователя giftuser с паролем giftpass.
  pause
  exit /b 1
)

set PORT=8765
echo Сайт: http://127.0.0.1:%PORT%
echo Демо: demo@dari.local / demo123
php -S 127.0.0.1:%PORT% -t .
pause
