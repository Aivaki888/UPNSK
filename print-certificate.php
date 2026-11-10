<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = require_login();
$code = strtoupper(trim((string) ($_GET['code'] ?? '')));

$stmt = db()->prepare(
    'SELECT c.*, d.title AS design_title, d.image_path
     FROM certificates c
     INNER JOIN designs d ON d.id = c.design_id
     WHERE c.user_id = ? AND c.certificate_code = ?'
);
$stmt->execute([(int) $user['id'], $code]);
$cert = $stmt->fetch();

if (!$cert) {
    flash('error', 'Сертификат для печати не найден.');
    redirect('certificates.php');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Печать — <?= e($cert['certificate_code']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: Comfortaa, sans-serif; margin: 0; background: #e7eaef; color: #16191f; }
        .sheet { max-width: 720px; margin: 2rem auto; background: #f4f6f9; border-radius: 24px; padding: 2rem; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .eyebrow { color: #0068d9; font-size: .85rem; font-weight: 600; margin-bottom: .4rem; }
        .media { width: 100%; aspect-ratio: 16/10; border-radius: 16px; background: #d6dbe4; display: grid; place-items: center; overflow: hidden; margin-bottom: 1.25rem; }
        .media img { width: 100%; height: 100%; object-fit: cover; }
        h1 { margin: 0 0 .5rem; }
        .meta { display: grid; gap: .45rem; color: #667084; }
        .actions { display: flex; gap: .75rem; margin-top: 1.5rem; flex-wrap: wrap; }
        .btn { border: 0; border-radius: 999px; padding: .75rem 1.2rem; background: #0068d9; color: #fff; cursor: pointer; text-decoration: none; font: inherit; }
        .btn.secondary { background: #d6dbe4; color: #16191f; }
        @media print {
            body { background: #fff; }
            .actions { display: none !important; }
            .sheet { box-shadow: none; margin: 0; max-width: none; }
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="media">
            <?php if (media_exists($cert['image_path'])): ?>
                <img src="<?= e($cert['image_path']) ?>" alt="<?= e($cert['design_title']) ?>">
            <?php else: ?>
                <span>Место для картинки</span>
            <?php endif; ?>
        </div>
        <div class="eyebrow">PlayGift · игровой сертификат</div>
        <h1><?= e($cert['design_title']) ?></h1>
        <div class="meta">
            <div><strong>Код:</strong> <?= e($cert['certificate_code']) ?></div>
            <div><strong>Сумма:</strong> <?= e(format_money((float) $cert['amount'])) ?></div>
            <div><strong>Получатель:</strong> <?= e($cert['recipient_name']) ?></div>
            <div><strong>Повод:</strong> <?= e(occasion_label($cert['occasion'])) ?></div>
            <div><strong>Стиль:</strong> <?= e(accent_label($cert['accent_color'])) ?></div>
            <?php if (!empty($cert['message'])): ?>
                <div><strong>Сообщение:</strong> <?= e($cert['message']) ?></div>
            <?php endif; ?>
        </div>
        <div class="actions">
            <button class="btn" type="button" onclick="window.print()">Печать</button>
            <a class="btn secondary" href="certificates.php">Назад</a>
        </div>
    </div>
</body>
</html>
