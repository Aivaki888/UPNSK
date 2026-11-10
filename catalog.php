<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = current_user();
$category = trim((string) ($_GET['category'] ?? ''));
$q = trim((string) ($_GET['q'] ?? ''));
$sort = (string) ($_GET['sort'] ?? 'default');
$onlyFavorites = isset($_GET['fav']) && $_GET['fav'] === '1';

$params = [];
$sql = 'SELECT d.* FROM designs d WHERE d.is_active = 1';

if ($onlyFavorites && $user) {
    ensure_extra_tables();
    $sql .= ' INNER JOIN favorites f ON f.design_id = d.id AND f.user_id = ?';
    $params[] = (int) $user['id'];
}

if ($category !== '') {
    $sql .= ' AND d.category = ?';
    $params[] = $category;
}

if ($q !== '') {
    $sql .= ' AND (d.title LIKE ? OR d.description LIKE ? OR d.category LIKE ?)';
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql .= match ($sort) {
    'price_asc' => ' ORDER BY d.price_base ASC, d.sort_order ASC',
    'price_desc' => ' ORDER BY d.price_base DESC, d.sort_order ASC',
    'title' => ' ORDER BY d.title ASC',
    default => ' ORDER BY d.sort_order ASC',
};

$stmt = db()->prepare($sql);
$stmt->execute($params);
$designs = $stmt->fetchAll();

$categories = db()->query('SELECT DISTINCT category FROM designs WHERE is_active = 1 ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
$favoriteIds = $user ? user_favorite_ids((int) $user['id']) : [];

$pageTitle = 'Каталог платформ — PlayGift';
$activeNav = 'catalog';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>Каталог платформ</h1>
        <p>Фильтруйте по PlayStation, Xbox и PC, ищите по названию и сохраняйте избранное.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container stack-sm">
        <form class="toolbar panel stagger-form" method="get" action="catalog.php">
            <div class="form-row two">
                <div class="form-row">
                    <label for="q">Поиск</label>
                    <input id="q" name="q" type="search" value="<?= e($q) ?>" placeholder="Название или описание">
                </div>
                <div class="form-row">
                    <label for="sort">Сортировка</label>
                    <select id="sort" name="sort">
                        <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>По умолчанию</option>
                        <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>По названию</option>
                        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Доплата: сначала меньше</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Доплата: сначала больше</option>
                    </select>
                </div>
            </div>
            <?php if ($category !== ''): ?>
                <input type="hidden" name="category" value="<?= e($category) ?>">
            <?php endif; ?>
            <?php if ($onlyFavorites): ?>
                <input type="hidden" name="fav" value="1">
            <?php endif; ?>
            <div class="cta-row">
                <button class="btn btn-primary" type="submit">Применить</button>
                <a class="btn btn-secondary" href="catalog.php">Сбросить</a>
                <?php if ($user): ?>
                    <a class="btn <?= $onlyFavorites ? 'btn-primary' : 'btn-ghost' ?>" href="catalog.php?fav=1<?= $q !== '' ? '&q=' . urlencode($q) : '' ?>">Только избранное</a>
                <?php endif; ?>
            </div>
        </form>

        <div class="cta-row">
            <a class="btn <?= $category === '' && !$onlyFavorites ? 'btn-primary' : 'btn-secondary' ?>" href="catalog.php">Все</a>
            <?php foreach ($categories as $cat): ?>
                <a class="btn <?= $category === $cat ? 'btn-primary' : 'btn-secondary' ?>" href="catalog.php?category=<?= urlencode($cat) ?>"><?= e($cat) ?></a>
            <?php endforeach; ?>
        </div>

        <?php if (!$designs): ?>
            <div class="empty-state">Ничего не найдено. Попробуйте другой запрос.</div>
        <?php else: ?>
            <div class="design-grid">
                <?php foreach ($designs as $design): ?>
                    <?php $isFav = in_array((int) $design['id'], $favoriteIds, true); ?>
                    <article class="design-tile">
                        <div class="design-media">
                            <?php render_media($design['image_path'], $design['title'], 'design-image', 'Место для картинки дизайна'); ?>
                        </div>
                        <div class="design-body">
                            <span class="chip chip-<?= e(strtolower($design['category'])) ?>"><?= e($design['category']) ?></span>
                            <h3><?= e($design['title']) ?></h3>
                            <p class="muted"><?= e($design['description']) ?></p>
                            <div class="design-meta">
                                <span><?= (float) $design['price_base'] > 0 ? 'Оформление +' . format_money((float) $design['price_base']) : 'Базовый дизайн' ?></span>
                                <a class="btn btn-ghost" href="order.php?design=<?= (int) $design['id'] ?>">Оформить</a>
                            </div>
                            <?php if ($user): ?>
                                <form method="post" action="favorite-toggle.php" class="inline-form">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="design_id" value="<?= (int) $design['id'] ?>">
                                    <input type="hidden" name="redirect" value="<?= e($_SERVER['REQUEST_URI'] ?? 'catalog.php') ?>">
                                    <button class="btn btn-secondary" type="submit"><?= $isFav ? 'В избранном' : 'В избранное' ?></button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
