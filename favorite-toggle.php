<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? null)) {
    flash('error', 'Не удалось обновить избранное.');
    redirect($_SERVER['HTTP_REFERER'] ?? 'catalog.php');
}

$designId = (int) ($_POST['design_id'] ?? 0);
$back = trim((string) ($_POST['redirect'] ?? 'catalog.php'));
if ($back === '' || str_contains($back, '://')) {
    $back = 'catalog.php';
}

$stmt = db()->prepare('SELECT id FROM designs WHERE id = ? AND is_active = 1');
$stmt->execute([$designId]);
if (!$stmt->fetch()) {
    flash('error', 'Дизайн не найден.');
    redirect($back);
}

ensure_extra_tables();

if (is_favorite((int) $user['id'], $designId)) {
    $del = db()->prepare('DELETE FROM favorites WHERE user_id = ? AND design_id = ?');
    $del->execute([(int) $user['id'], $designId]);
    flash('success', 'Убрано из избранного.');
} else {
    $ins = db()->prepare('INSERT IGNORE INTO favorites (user_id, design_id) VALUES (?, ?)');
    $ins->execute([(int) $user['id'], $designId]);
    flash('success', 'Добавлено в избранное.');
}

redirect($back);
