<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$category = trim((string) ($_GET['category'] ?? ''));
$params = [];
$sql = 'SELECT * FROM designs WHERE is_active = 1';

if ($category !== '') {
    $sql .= ' AND category = ?';
    $params[] = $category;
}

$sql .= ' ORDER BY sort_order ASC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$designs = $stmt->fetchAll();

$categories = db()->query('SELECT DISTINCT category FROM designs WHERE is_active = 1 ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Каталог дизайнов — Дари';
$activeNav = 'catalog';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1>Каталог дизайнов</h1>
        <p>Выберите картинку для сертификата. Каждый дизайн можно сразу отправить в оформление с нужной суммой и поводом.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container stack-sm">
        <div class="cta-row">
            <a class="btn <?= $category === '' ? 'btn-primary' : 'btn-secondary' ?>" href="catalog.php">Все</a>
            <?php foreach ($categories as $cat): ?>
                <a class="btn <?= $category === $cat ? 'btn-primary' : 'btn-secondary' ?>" href="catalog.php?category=<?= urlencode($cat) ?>"><?= e($cat) ?></a>
            <?php endforeach; ?>
        </div>

        <?php if (!$designs): ?>
            <div class="empty-state">В этой категории пока нет дизайнов.</div>
        <?php else: ?>
            <div class="design-grid">
                <?php foreach ($designs as $design): ?>
                    <article class="design-tile">
                        <div class="design-media">
                            <img src="<?= e($design['image_path']) ?>" alt="<?= e($design['title']) ?>">
                        </div>
                        <div class="design-body">
                            <span class="chip"><?= e($design['category']) ?></span>
                            <h3><?= e($design['title']) ?></h3>
                            <p class="muted"><?= e($design['description']) ?></p>
                            <div class="design-meta">
                                <span><?= (float) $design['price_base'] > 0 ? 'Оформление +' . format_money((float) $design['price_base']) : 'Базовый дизайн' ?></span>
                                <a class="btn btn-ghost" href="order.php?design=<?= (int) $design['id'] ?>">Оформить</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
