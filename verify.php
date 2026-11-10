<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$result = null;
$error = null;
$code = strtoupper(trim((string) ($_GET['code'] ?? $_POST['code'] ?? '')));

if ($code !== '') {
    $stmt = db()->prepare(
        'SELECT c.*, d.title AS design_title, d.image_path, d.category, u.name AS owner_name
         FROM certificates c
         INNER JOIN designs d ON d.id = c.design_id
         INNER JOIN users u ON u.id = c.user_id
         WHERE c.certificate_code = ?'
    );
    $stmt->execute([$code]);
    $result = $stmt->fetch() ?: null;
    if (!$result) {
        $error = 'Сертификат с таким кодом не найден.';
    }
}

$shareUrl = $code !== '' ? (isset($_SERVER['HTTP_HOST']) ? ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/verify.php?code=' . rawurlencode($code)) : 'verify.php?code=' . rawurlencode($code)) : '';

$pageTitle = 'Проверка сертификата — PlayGift';
$activeNav = 'verify';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>Проверка кода</h1>
        <p>Введите код сертификата на пополнение кошелька — увидите сумму, статус, QR и получателя.</p>
    </div>
</section>

<section class="container narrow-wrap">
    <div class="panel">
        <form class="form-grid stagger-form" method="get" action="verify.php">
            <div class="form-row">
                <label for="code">Код сертификата</label>
                <input id="code" name="code" type="text" required value="<?= e($code) ?>" placeholder="Например, A1B2C3D4E5F6" autocomplete="off" data-verify-input>
            </div>
            <button class="btn btn-primary" type="submit">Проверить</button>
        </form>
    </div>

    <?php if ($error): ?>
        <div class="flash flash-error" style="margin-top: 1rem;"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($result): ?>
        <article class="voucher-card verify-voucher accent-<?= e($result['accent_color']) ?>" style="margin-top: 1.25rem;">
            <div class="voucher-shine" aria-hidden="true"></div>
            <div class="voucher-top">
                <span class="voucher-brand">PlayGift</span>
                <?php render_platform_chip($result['category']); ?>
            </div>
            <?php render_media($result['image_path'], $result['design_title'], 'design-image', 'Место для картинки'); ?>
            <h3><?= e($result['design_title']) ?></h3>
            <div class="voucher-amount"><?= e(format_money((float) $result['amount'])) ?></div>
            <div class="voucher-recipient">для <strong><?= e($result['recipient_name']) ?></strong></div>
            <div class="cert-meta muted">
                <div><strong>Код:</strong> <span data-copy-text><?= e($result['certificate_code']) ?></span></div>
                <div><strong>Статус:</strong> <?= e(status_label($result['status'])) ?></div>
                <div><strong>Повод:</strong> <?= e(occasion_label($result['occasion'])) ?></div>
                <div><strong>Оформлен:</strong> <?= e(date('d.m.Y H:i', strtotime((string) $result['created_at']))) ?></div>
            </div>
            <div class="verify-tools">
                <img class="qr-image" src="<?= e(qr_image_url($shareUrl, 160)) ?>" alt="QR-код сертификата" width="160" height="160" loading="lazy">
                <div class="cta-row">
                    <button class="btn btn-secondary" type="button" data-copy="<?= e($result['certificate_code']) ?>">Копировать код</button>
                    <button class="btn btn-ghost" type="button" data-copy="<?= e($shareUrl) ?>">Копировать ссылку</button>
                    <a class="btn btn-primary" href="mailto:?subject=<?= rawurlencode('PlayGift сертификат') ?>&body=<?= rawurlencode("Код: {$result['certificate_code']}\nПроверка: {$shareUrl}") ?>">Отправить</a>
                </div>
            </div>
        </article>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
