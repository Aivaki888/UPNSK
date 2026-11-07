<?php

declare(strict_types=1);

/**
 * Инициализация таблиц MySQL и демо-пользователя.
 * Запуск: php scripts/init_db.php
 */

$host = getenv('DB_HOST') ?: '127.0.0.1';
$name = getenv('DB_NAME') ?: 'giftshop';
$user = getenv('DB_USER') ?: 'giftuser';
$pass = getenv('DB_PASS') !== false ? (string) getenv('DB_PASS') : 'giftpass';
$port = getenv('DB_PORT') ?: '3306';

$sqlFile = __DIR__ . '/../sql/schema.sql';
$rootSql = file_get_contents($sqlFile);

if ($rootSql === false) {
    fwrite(STDERR, "Не удалось прочитать sql/schema.sql\n");
    exit(1);
}

// Убираем CREATE DATABASE / USE — база уже должна существовать.
$statements = preg_replace('/^\s*CREATE DATABASE[\s\S]*?;/mi', '', $rootSql) ?? $rootSql;
$statements = preg_replace('/^\s*USE\s+\w+\s*;/mi', '', $statements) ?? $statements;

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name),
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    foreach (array_filter(array_map('trim', explode(';', $statements))) as $statement) {
        if ($statement === '') {
            continue;
        }
        $pdo->exec($statement);
    }

    $demoEmail = 'demo@dari.local';
    $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $check->execute([$demoEmail]);

    if (!$check->fetch()) {
        $insert = $pdo->prepare(
            'INSERT INTO users (name, email, password_hash, phone, city, theme_pref)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $insert->execute([
            'Демо Пользователь',
            $demoEmail,
            password_hash('demo123', PASSWORD_DEFAULT),
            '+7 900 000-00-00',
            'Москва',
            'system',
        ]);
        echo "Создан демо-пользователь: {$demoEmail} / demo123\n";
    } else {
        echo "Демо-пользователь уже существует: {$demoEmail} / demo123\n";
    }

    echo "База {$name} готова.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Ошибка инициализации: ' . $e->getMessage() . "\n");
    exit(1);
}
