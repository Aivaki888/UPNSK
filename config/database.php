<?php

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $name = getenv('DB_NAME') ?: 'giftshop';
    $user = getenv('DB_USER') ?: 'giftuser';
    $pass = getenv('DB_PASS') !== false ? (string) getenv('DB_PASS') : 'giftpass';
    $socket = getenv('DB_SOCKET') ?: '';
    $port = getenv('DB_PORT') ?: '3306';

    if ($socket !== '') {
        $dsn = sprintf('mysql:unix_socket=%s;dbname=%s;charset=utf8mb4', $socket, $name);
    } else {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);
    }

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>База данных недоступна</title>';
        echo '<style>body{font-family:Arial,sans-serif;max-width:40rem;margin:3rem auto;padding:0 1rem;line-height:1.5}';
        echo 'code{background:#f3f3f3;padding:.1rem .35rem}</style></head><body>';
        echo '<h1>Не удалось подключиться к MySQL</h1>';
        echo '<p>Сайт работает только при запущенной базе данных.</p>';
        echo '<ol>';
        echo '<li>Запусти MySQL / MariaDB (XAMPP, OpenServer или служба MySQL).</li>';
        echo '<li>Создай БД <code>giftshop</code>, пользователя <code>giftuser</code>, пароль <code>giftpass</code>.</li>';
        echo '<li>Выполни: <code>php scripts/init_db.php</code></li>';
        echo '<li>Или запусти <code>start.bat</code> / <code>start.sh</code></li>';
        echo '</ol>';
        echo '<p><small>' . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</small></p>';
        echo '</body></html>';
        exit;
    }

    return $pdo;
}
