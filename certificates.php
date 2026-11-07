<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = require_login();

$stmt = db()->prepare(
    'SELECT c.*, d.title AS design_title, d.image_path
     FROM certificates c
     INNER JOIN designs d ON d.id = c.design_id
     WHERE c.user_id = ?
     ORDER BY c.created_at DESC'
);
$stmt->execute([(int) $user['id']]);
$certificates = $stmt->fetchAll();

$pageTitle = 'Мои сертификаты — Дари';
$activeNav = 'certificates';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1>Мои сертификаты</h1>
        <p>Здесь хранятся все оформленные подарочные сертификаты с кодами и выбранными критериями.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container">
        <?php if (!$certificates): ?>
            <div class="empty-state">
                <p>Пока нет оформленных сертификатов.</p>
                <a class="btn btn-primary" href="order.php">Оформить первый</a>
            </div>
        <?php else: ?>
            <div class="cert-list">
                <?php foreach ($certificates as $cert): ?>
                    <article class="cert-item">
                        <img src="<?= e($cert['image_path']) ?>" alt="<?= e($cert['design_title']) ?>">
                        <div>
                            <h3><?= e($cert['design_title']) ?></h3>
                            <div class="cert-meta muted">
                                <div><strong>Код:</strong> <?= e($cert['certificate_code']) ?></div>
                                <div><strong>Сумма:</strong> <?= e(format_money((float) $cert['amount'])) ?></div>
                                <div><strong>Получатель:</strong> <?= e($cert['recipient_name']) ?></div>
                                <div><strong>Повод:</strong> <?= e(occasion_label($cert['occasion'])) ?></div>
                                <div><strong>Акцент:</strong> <?= e(accent_label($cert['accent_color'])) ?></div>
                                <div><strong>Доставка:</strong> <?= $cert['delivery_method'] === 'print' ? 'Для печати' : 'На email' ?></div>
                                <div><strong>Дата:</strong> <?= e(date('d.m.Y H:i', strtotime((string) $cert['created_at']))) ?></div>
                                <?php if (!empty($cert['message'])): ?>
                                    <div><strong>Сообщение:</strong> <?= e($cert['message']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
