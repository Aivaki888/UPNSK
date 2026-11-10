<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = require_login();
ensure_extra_tables();

$stmt = db()->prepare(
    'SELECT d.*
     FROM favorites f
     INNER JOIN designs d ON d.id = f.design_id
     WHERE f.user_id = ? AND d.is_active = 1
     ORDER BY f.created_at DESC'
);
$stmt->execute([(int) $user['id']]);
$designs = $stmt->fetchAll();

$pageTitle = 'Избранное — PlayGift';
$activeNav = 'favorites';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>Избранное</h1>
        <p>Оформления ваучеров на кошельки PlayStation, Xbox и Steam, которые вы сохранили.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container">
        <?php if (!$designs): ?>
            <div class="empty-state">
                <p>Пока пусто. Добавляйте дизайны из каталога кнопкой «В избранное».</p>
                <a class="btn btn-primary" href="catalog.php">Открыть каталог</a>
            </div>
        <?php else: ?>
            <div class="design-grid">
                <?php foreach ($designs as $design): ?>
                    <article class="design-tile">
                        <div class="design-media">
                            <?php render_media($design['image_path'], $design['title'], 'design-image', 'Место для картинки дизайна'); ?>
                        </div>
                        <div class="design-body">
                            <?php render_platform_chip($design['category']); ?>
                            <h3><?= e($design['title']) ?></h3>
                            <p class="muted"><?= e($design['description']) ?></p>
                            <div class="design-meta">
                                <a class="btn btn-primary" href="order.php?design=<?= (int) $design['id'] ?>">Оформить</a>
                                <form method="post" action="favorite-toggle.php">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="design_id" value="<?= (int) $design['id'] ?>">
                                    <input type="hidden" name="redirect" value="favorites.php">
                                    <button class="btn btn-ghost" type="submit">Убрать</button>
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
