<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
ensure_extra_tables();

$items = wall_certificates(12);

$pageTitle = 'Стена сертификатов — PlayGift';
$activeNav = 'wall';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <span class="eyebrow">Витрина</span>
        <h1>Стена сертификатов</h1>
        <p>Последние оформленные ваучеры на пополнение кошельков — без секретных данных.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container">
        <?php if (!$items): ?>
            <div class="empty-state panel">
                <p>Стена пока пустая. Первый сертификат станет первым экспонатом.</p>
                <a class="btn btn-primary" href="order.php">Оформить</a>
            </div>
        <?php else: ?>
            <div class="wall-grid stagger-layout">
                <?php foreach ($items as $item): ?>
                    <article class="voucher-card wall-card accent-<?= e($item['accent_color']) ?>">
                        <div class="voucher-shine" aria-hidden="true"></div>
                        <div class="voucher-top">
                            <?php render_platform_chip($item['category']); ?>
                            <span class="muted"><?= e(date('d.m', strtotime((string) $item['created_at']))) ?></span>
                        </div>
                        <?php render_media($item['image_path'], $item['design_title'], 'design-image', 'Оформление'); ?>
                        <h3><?= e($item['design_title']) ?></h3>
                        <div class="voucher-amount"><?= e(format_money((float) $item['amount'])) ?></div>
                        <div class="muted"><?= e(occasion_label($item['occasion'])) ?></div>
                        <a class="btn btn-ghost" href="verify.php?code=<?= urlencode($item['certificate_code']) ?>">Проверить код</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
