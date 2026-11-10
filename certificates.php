<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = require_login();

$q = trim((string) ($_GET['q'] ?? ''));
$status = (string) ($_GET['status'] ?? '');

$params = [(int) $user['id']];
$sql = 'SELECT c.*, d.title AS design_title, d.image_path
        FROM certificates c
        INNER JOIN designs d ON d.id = c.design_id
        WHERE c.user_id = ?';

if ($status !== '' && in_array($status, ['paid', 'sent', 'used', 'new'], true)) {
    $sql .= ' AND c.status = ?';
    $params[] = $status;
}

if ($q !== '') {
    $sql .= ' AND (c.certificate_code LIKE ? OR c.recipient_name LIKE ? OR d.title LIKE ?)';
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql .= ' ORDER BY c.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$certificates = $stmt->fetchAll();

$pageTitle = 'Мои сертификаты — PlayGift';
$activeNav = 'certificates';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>Мои сертификаты</h1>
        <p>Копируйте код, печатайте сертификат и отмечайте использование.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container stack-sm">
        <form class="toolbar panel stagger-form" method="get" action="certificates.php">
            <div class="form-row two">
                <div class="form-row">
                    <label for="q">Поиск</label>
                    <input id="q" name="q" type="search" value="<?= e($q) ?>" placeholder="Код, получатель или дизайн">
                </div>
                <div class="form-row">
                    <label for="status">Статус</label>
                    <select id="status" name="status">
                        <option value="">Все</option>
                        <option value="paid" <?= $status === 'paid' ? 'selected' : '' ?>>Оплачен</option>
                        <option value="sent" <?= $status === 'sent' ? 'selected' : '' ?>>Отправлен</option>
                        <option value="used" <?= $status === 'used' ? 'selected' : '' ?>>Использован</option>
                    </select>
                </div>
            </div>
            <div class="cta-row">
                <button class="btn btn-primary" type="submit">Найти</button>
                <a class="btn btn-secondary" href="certificates.php">Сбросить</a>
                <a class="btn btn-ghost" href="verify.php">Проверить код</a>
            </div>
        </form>

        <?php if (!$certificates): ?>
            <div class="empty-state">
                <p>Сертификатов пока нет или ничего не найдено.</p>
                <a class="btn btn-primary" href="order.php">Оформить</a>
            </div>
        <?php else: ?>
            <div class="cert-list">
                <?php foreach ($certificates as $cert): ?>
                    <article class="cert-item">
                        <?php render_media($cert['image_path'], $cert['design_title'], 'cert-image', 'Место для картинки'); ?>
                        <div>
                            <h3><?= e($cert['design_title']) ?></h3>
                            <div class="cert-meta muted">
                                <div><strong>Код:</strong> <span data-copy-text><?= e($cert['certificate_code']) ?></span></div>
                                <div><strong>Сумма:</strong> <?= e(format_money((float) $cert['amount'])) ?></div>
                                <div><strong>Статус:</strong> <?= e(status_label($cert['status'])) ?></div>
                                <div><strong>Получатель:</strong> <?= e($cert['recipient_name']) ?></div>
                                <div><strong>Повод:</strong> <?= e(occasion_label($cert['occasion'])) ?></div>
                                <div><strong>Стиль:</strong> <?= e(accent_label($cert['accent_color'])) ?></div>
                                <div><strong>Доставка:</strong> <?= $cert['delivery_method'] === 'print' ? 'Для печати' : 'На email' ?></div>
                                <div><strong>Дата:</strong> <?= e(date('d.m.Y H:i', strtotime((string) $cert['created_at']))) ?></div>
                                <?php if (!empty($cert['message'])): ?>
                                    <div><strong>Сообщение:</strong> <?= e($cert['message']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="cta-row" style="margin-top: 0.9rem;">
                                <button class="btn btn-secondary" type="button" data-copy="<?= e($cert['certificate_code']) ?>">Копировать код</button>
                                <a class="btn btn-ghost" href="print-certificate.php?code=<?= urlencode($cert['certificate_code']) ?>">Печать</a>
                                <form method="post" action="certificate-action.php" class="inline-form">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="certificate_id" value="<?= (int) $cert['id'] ?>">
                                    <?php if ($cert['status'] !== 'used'): ?>
                                        <input type="hidden" name="action" value="mark_used">
                                        <button class="btn btn-primary" type="submit">Отметить использованным</button>
                                    <?php else: ?>
                                        <input type="hidden" name="action" value="mark_paid">
                                        <button class="btn btn-ghost" type="submit">Вернуть статус</button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
