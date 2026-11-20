<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
ensure_extra_tables();

$rows = leaderboard_rows(12);
$activity = recent_activity(10);

$pageTitle = 'Рейтинг дарителей — PlayGift';
$activeNav = 'leaderboard';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <span class="eyebrow">Соревнование</span>
        <h1>Рейтинг дарителей</h1>
        <p>Пользователи с наибольшей суммой оформленных сертификатов.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container board-layout">
        <div class="panel">
            <h2>Топ по сумме подарков</h2>
            <?php if (!$rows): ?>
                <div class="empty-state">
                    <p>Пока пусто. Оформите первый сертификат.</p>
                    <a class="btn btn-primary" href="order.php">Оформить</a>
                </div>
            <?php else: ?>
                <ol class="leaderboard-list stagger-layout">
                    <?php foreach ($rows as $i => $row): ?>
                        <li class="leaderboard-row">
                            <span class="lb-rank">#<?= $i + 1 ?></span>
                            <div>
                                <strong><?= e($row['name']) ?></strong>
                                <span class="muted"><?= (int) $row['gifts'] ?> подарков</span>
                            </div>
                            <span class="stat-pill"><?= e(format_money((float) $row['total'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>

        <aside class="panel">
            <h2>Последние заказы</h2>
            <?php if (!$activity): ?>
                <p class="muted">Пока нет заказов.</p>
            <?php else: ?>
                <ul class="activity-list stagger-layout">
                    <?php foreach ($activity as $item): ?>
                        <li>
                            <?php render_platform_icon($item['category'], 'platform-icon platform-icon-xs'); ?>
                            <div>
                                <strong><?= e(mask_name((string) $item['giver'])) ?></strong>
                                <span class="muted">подарил <?= e(format_money((float) $item['amount'])) ?> · <?= e(platform_label($item['category'])) ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
