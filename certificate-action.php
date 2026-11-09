<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? null)) {
    flash('error', 'Действие не выполнено.');
    redirect('certificates.php');
}

$id = (int) ($_POST['certificate_id'] ?? 0);
$action = (string) ($_POST['action'] ?? '');

$stmt = db()->prepare('SELECT id, status FROM certificates WHERE id = ? AND user_id = ?');
$stmt->execute([$id, (int) $user['id']]);
$cert = $stmt->fetch();

if (!$cert) {
    flash('error', 'Сертификат не найден.');
    redirect('certificates.php');
}

if ($action === 'mark_used' && $cert['status'] !== 'used') {
    $upd = db()->prepare("UPDATE certificates SET status = 'used' WHERE id = ? AND user_id = ?");
    $upd->execute([$id, (int) $user['id']]);
    flash('success', 'Сертификат отмечен как использованный.');
} elseif ($action === 'mark_paid' && $cert['status'] === 'used') {
    $upd = db()->prepare("UPDATE certificates SET status = 'paid' WHERE id = ? AND user_id = ?");
    $upd->execute([$id, (int) $user['id']]);
    flash('success', 'Статус сертификата возвращён к «Оплачен».');
} else {
    flash('error', 'Нельзя выполнить это действие.');
}

redirect('certificates.php');
