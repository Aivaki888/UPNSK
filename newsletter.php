<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    flash('error', 'Сессия устарела. Попробуйте ещё раз.');
    redirect($_POST['redirect'] ?? 'index.php');
}

$email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
$redirect = (string) ($_POST['redirect'] ?? 'index.php');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('error', 'Укажите корректный email для подписки.');
    redirect($redirect);
}

ensure_extra_tables();
$stmt = db()->prepare('INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)');
$stmt->execute([$email]);
flash('success', 'Вы в списке PlayGift — пришлём учебные новости о кошельках и идеях подарков.');
redirect($redirect);
