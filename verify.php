<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$result = null;
$error = null;
$code = strtoupper(trim((string) ($_GET['code'] ?? $_POST['code'] ?? '')));

if ($code !== '') {
    $stmt = db()->prepare(
        'SELECT c.*, d.title AS design_title, d.image_path, u.name AS owner_name
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

$pageTitle = 'Проверка сертификата — PlayGift';
$activeNav = 'verify';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>Проверка кода</h1>
        <p>Введите код сертификата на пополнение кошелька — увидите сумму, статус и получателя.</p>
    </div>
</section>

<section class="container narrow-wrap">
    <div class="panel">
        <form class="form-grid stagger-form" method="get" action="verify.php">
            <div class="form-row">
                <label for="code">Код сертификата</label>
                <input id="code" name="code" type="text" required value="<?= e($code) ?>" placeholder="Например, A1B2C3D4E5F6" autocomplete="off">
            </div>
            <button class="btn btn-primary" type="submit">Проверить</button>
        </form>
    </div>

    <?php if ($error): ?>
        <div class="flash flash-error" style="margin-top: 1rem;"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($result): ?>
        <article class="cert-item" style="margin-top: 1rem;">
            <?php render_media($result['image_path'], $result['design_title'], 'cert-image', 'Место для картинки'); ?>
            <div>
                <h3><?= e($result['design_title']) ?></h3>
                <div class="cert-meta muted">
                    <div><strong>Код:</strong> <?= e($result['certificate_code']) ?></div>
                    <div><strong>Сумма:</strong> <?= e(format_money((float) $result['amount'])) ?></div>
                    <div><strong>Статус:</strong> <?= e(status_label($result['status'])) ?></div>
                    <div><strong>Получатель:</strong> <?= e($result['recipient_name']) ?></div>
                    <div><strong>Повод:</strong> <?= e(occasion_label($result['occasion'])) ?></div>
                    <div><strong>Оформлен:</strong> <?= e(date('d.m.Y H:i', strtotime((string) $result['created_at']))) ?></div>
                </div>
            </div>
        </article>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
