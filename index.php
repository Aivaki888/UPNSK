<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$designs = db()->query('SELECT * FROM designs WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 3')->fetchAll();

$recentIds = array_map('intval', $_SESSION['recent_designs'] ?? []);
$recentDesigns = [];
if ($recentIds) {
    $placeholders = implode(',', array_fill(0, count($recentIds), '?'));
    $stmt = db()->prepare("SELECT * FROM designs WHERE is_active = 1 AND id IN ($placeholders)");
    $stmt->execute($recentIds);
    $byId = [];
    foreach ($stmt->fetchAll() as $row) {
        $byId[(int) $row['id']] = $row;
    }
    foreach ($recentIds as $id) {
        if (isset($byId[$id])) {
            $recentDesigns[] = $byId[$id];
        }
    }
}

$pageTitle = 'PlayGift — пополнение кошельков PS · Xbox · PC';
$activeNav = 'home';
$bodyClass = 'page-home';

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">Только пополнение кошельков</span>
        <h1>PlayGift</h1>
        <p class="lead">Цифровые сертификаты для баланса PlayStation, Xbox и Steam. Без ключей игр — только сумма на кошелёк.</p>
        <div class="cta-row">
            <a class="btn btn-primary" href="order.php">Оформить пополнение</a>
            <a class="btn btn-secondary" href="catalog.php">Каталог кошельков</a>
            <a class="btn btn-ghost" href="verify.php">Проверить код</a>
        </div>
    </div>
    <div class="hero-visual">
        <?php render_media('assets/img/hero.jpg', 'Главный баннер', 'hero-media', 'Место для главного изображения'); ?>
    </div>
</section>

<section class="section" style="padding-bottom: 0;">
    <div class="container">
        <div class="feature-grid">
            <a class="feature-item" href="catalog.php?category=PlayStation">
                <h3>PlayStation</h3>
                <p>Сертификат на пополнение кошелька PSN.</p>
            </a>
            <a class="feature-item" href="catalog.php?category=Xbox">
                <h3>Xbox</h3>
                <p>Сертификат на пополнение баланса Microsoft / Xbox.</p>
            </a>
            <a class="feature-item" href="catalog.php?category=PC">
                <h3>PC</h3>
                <p>Сертификат на пополнение кошелька Steam.</p>
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Выберите кошелёк</h2>
            <p class="section-text">Шесть оформлений под PlayStation, Xbox и Steam — картинки вставите сами.</p>
        </div>
        <div class="design-grid">
            <?php foreach ($designs as $design): ?>
                <article class="design-tile">
                    <div class="design-media">
                        <?php render_media($design['image_path'], $design['title'], 'design-image', 'Место для картинки дизайна'); ?>
                    </div>
                    <div class="design-body">
                        <span class="chip chip-<?= e(strtolower($design['category'])) ?>"><?= e($design['category']) ?></span>
                        <h3><?= e($design['title']) ?></h3>
                        <p class="muted"><?= e($design['description']) ?></p>
                        <div class="design-meta">
                            <span><?= (float) $design['price_base'] > 0 ? '+' . format_money((float) $design['price_base']) : 'Без доплаты' ?></span>
                            <a href="order.php?design=<?= (int) $design['id'] ?>">Выбрать</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Как это работает</h2>
            <p class="section-text">Три шага до сертификата на пополнение кошелька.</p>
        </div>
        <div class="feature-grid">
            <article class="feature-item">
                <h3>1. Аккаунт</h3>
                <p>Зарегистрируйтесь, чтобы хранить заказы и избранные дизайны.</p>
            </article>
            <article class="feature-item">
                <h3>2. Кошелёк</h3>
                <p>Выберите PS, Xbox или Steam, сумму, повод и получателя.</p>
            </article>
            <article class="feature-item">
                <h3>3. Код готов</h3>
                <p>Сертификат появится в «Мои сертификаты» — можно проверить и распечатать.</p>
            </article>
        </div>
    </div>
</section>

<?php if ($recentDesigns): ?>
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Недавно смотрели</h2>
            <p class="section-text">Оформления кошельков, к которым вы возвращались в этой сессии.</p>
        </div>
        <div class="design-grid">
            <?php foreach ($recentDesigns as $design): ?>
                <article class="design-tile">
                    <div class="design-media">
                        <?php render_media($design['image_path'], $design['title'], 'design-image', 'Место для картинки дизайна'); ?>
                    </div>
                    <div class="design-body">
                        <span class="chip chip-<?= e(strtolower($design['category'])) ?>"><?= e($design['category']) ?></span>
                        <h3><?= e($design['title']) ?></h3>
                        <div class="design-meta">
                            <a href="order.php?design=<?= (int) $design['id'] ?>">Оформить снова</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
