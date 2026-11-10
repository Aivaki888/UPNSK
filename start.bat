@echo off
chcp 65001 >nul
cd /d "%~dp0"
setlocal EnableDelayedExpansion

set "PHP_EXE="

where php >nul 2>&1
if not errorlevel 1 (
  for /f "delims=" %%I in ('where php') do (
    set "PHP_EXE=%%I"
    goto :php_found
  )
)

for %%P in (
  "C:\xampp\php\php.exe"
  "C:\XAMPP\php\php.exe"
  "D:\xampp\php\php.exe"
  "C:\wamp64\bin\php\php8.3.0\php.exe"
  "C:\wamp64\bin\php\php8.2.0\php.exe"
  "C:\wamp64\bin\php\php8.1.0\php.exe"
  "C:\OpenServer\modules\php\PHP_8.3\php.exe"
  "C:\OpenServer\modules\php\PHP_8.2\php.exe"
  "C:\OpenServer\modules\php\PHP_8.1\php.exe"
  "C:\OpenServer\modules\php\PHP_8.0\php.exe"
  "C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe"
  "C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe"
) do (
  if exist %%~P (
    set "PHP_EXE=%%~P"
    goto :php_found
  )
)

for /d %%D in ("C:\wamp64\bin\php\php*") do (
  if exist "%%~D\php.exe" (
    set "PHP_EXE=%%~D\php.exe"
    goto :php_found
  )
)

for /d %%D in ("C:\laragon\bin\php\php*") do (
  if exist "%%~D\php.exe" (
    set "PHP_EXE=%%~D\php.exe"
    goto :php_found
  )
)

for /d %%D in ("C:\OpenServer\modules\php\PHP*") do (
  if exist "%%~D\php.exe" (
    set "PHP_EXE=%%~D\php.exe"
    goto :php_found
  )
)

echo.
echo PHP не найден на этом компьютере.
echo.
echo Что сделать:
echo   1. Установи XAMPP: https://www.apachefriends.org/
echo   2. Запусти XAMPP Control Panel
echo   3. Нажми Start у Apache и MySQL
echo   4. Снова запусти этот файл start.bat
echo.
echo Если XAMPP уже установлен в другой папке, запусти так:
echo   "C:\путь\к\php.exe" -S 127.0.0.1:8765 -t .
echo.
pause
exit /b 1

:php_found
echo Найден PHP: %PHP_EXE%
"%PHP_EXE%" -v
echo.

"%PHP_EXE%" -m 2>nul | findstr /I "pdo_mysql" >nul
if errorlevel 1 (
  echo Внимание: расширение pdo_mysql не видно.
  echo В XAMPP обычно оно уже есть. Проверь, что MySQL запущен.
  echo.
)

echo Инициализация базы данных...
"%PHP_EXE%" scripts\init_db.php
if errorlevel 1 (
  echo.
  echo Не удалось подключиться к MySQL.
  echo.
  echo 1. Открой XAMPP Control Panel и нажми Start у MySQL
  echo 2. Открой http://localhost/phpmyadmin
  echo 3. Во вкладке SQL выполни:
  echo.
  echo CREATE DATABASE IF NOT EXISTS giftshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  echo CREATE USER IF NOT EXISTS 'giftuser'@'localhost' IDENTIFIED BY 'giftpass';
  echo GRANT ALL PRIVILEGES ON giftshop.* TO 'giftuser'@'localhost';
  echo FLUSH PRIVILEGES;
  echo.
  echo 4. Снова запусти start.bat
  echo.
  pause
  exit /b 1
)

set PORT=8765
echo.
echo ========================================
echo   Сайт запущен!
echo   Открой: http://127.0.0.1:%PORT%
echo   Демо: demo@playgift.local / demo123
echo   Остановка: Ctrl+C
echo ========================================
echo.
"%PHP_EXE%" -S 127.0.0.1:%PORT% -t .
pause
