<?php

declare(strict_types=1);

/**
 * Подключение к MySQL.
 * Локально используются значения по умолчанию;
 * на сервере можно задать DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_SOCKET.
 */
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

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
