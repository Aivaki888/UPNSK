<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
ensure_extra_tables();

$designs = db()->query('SELECT * FROM designs WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 3')->fetchAll();
$allDesigns = db()->query('SELECT * FROM designs WHERE is_active = 1 ORDER BY sort_order ASC')->fetchAll();
$counters = site_counters();
$stats = platform_stats();
$ideas = db()->query('SELECT * FROM gift_ideas ORDER BY sort_order ASC LIMIT 3')->fetchAll();

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

$pageTitle = 'PlayGift — пополнение кошельков PS · Xbox · Steam';
$activeNav = 'home';
$bodyClass = 'page-home';

require __DIR__ . '/includes/header.php';
?>

<section class="hero hero-spectacular">
    <div class="hero-orb hero-orb-a" aria-hidden="true"></div>
    <div class="hero-orb hero-orb-b" aria-hidden="true"></div>
    <div class="hero-orb hero-orb-c" aria-hidden="true"></div>
    <div class="hero-copy">
        <span class="eyebrow">Только пополнение кошельков</span>
        <h1>PlayGift</h1>
        <p class="lead">Цифровые сертификаты для баланса PlayStation, Xbox и Steam. Красиво оформить, мгновенно проверить, легко подарить.</p>
        <div class="cta-row">
            <a class="btn btn-primary" href="order.php">Оформить пополнение</a>
            <a class="btn btn-secondary" href="quiz.php">Подобрать кошелёк</a>
            <a class="btn btn-ghost" href="guide.php">Гид подарков</a>
        </div>
    </div>
    <div class="hero-visual">
        <?php render_media('assets/img/hero.jpg', 'Главный баннер', 'hero-media', 'Место для главного изображения'); ?>
        <div class="floating-badges" aria-hidden="true">
            <?php render_platform_icon('PlayStation', 'platform-icon platform-icon-lg float-badge'); ?>
            <?php render_platform_icon('Xbox', 'platform-icon platform-icon-lg float-badge'); ?>
            <?php render_platform_icon('PC', 'platform-icon platform-icon-lg float-badge'); ?>
        </div>
    </div>
</section>

<section class="section" style="padding-bottom: 0;">
    <div class="container">
        <div class="counter-grid stagger-layout">
            <article class="counter-card">
                <strong data-count-up="<?= (int) $counters['designs'] ?>">0</strong>
                <span>оформлений кошельков</span>
            </article>
            <article class="counter-card">
                <strong data-count-up="<?= max(12, (int) $counters['certificates']) ?>">0</strong>
                <span>сертификатов в системе</span>
            </article>
            <article class="counter-card">
                <strong data-count-up="<?= max(3, (int) $counters['users']) ?>">0</strong>
                <span>аккаунтов</span>
            </article>
            <article class="counter-card">
                <strong data-count-up="<?= max(6, (int) $counters['favorites']) ?>">0</strong>
                <span>в избранном</span>
            </article>
        </div>
    </div>
</section>

<section class="section" style="padding-bottom: 0;">
    <div class="container">
        <div class="feature-grid wallet-grid">
            <a class="feature-item wallet-card" href="catalog.php?category=PlayStation">
                <?php render_platform_icon('PlayStation', 'platform-icon platform-icon-lg'); ?>
                <h3>PlayStation</h3>
                <p>Сертификат на пополнение кошелька PSN.</p>
            </a>
            <a class="feature-item wallet-card" href="catalog.php?category=Xbox">
                <?php render_platform_icon('Xbox', 'platform-icon platform-icon-lg'); ?>
                <h3>Xbox</h3>
                <p>Сертификат на пополнение баланса Microsoft / Xbox.</p>
            </a>
            <a class="feature-item wallet-card" href="catalog.php?category=PC">
                <?php render_platform_icon('PC', 'platform-icon platform-icon-lg'); ?>
                <h3>Steam</h3>
                <p>Сертификат на пополнение кошелька Steam.</p>
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container spotlight-layout">
        <div class="section-head">
            <h2 class="section-title">Выберите кошелёк</h2>
            <p class="section-text">Шесть оформлений под PlayStation, Xbox и Steam.</p>
        </div>
        <div class="design-grid">
            <?php foreach ($designs as $design): ?>
                <article class="design-tile">
                    <div class="design-media">
                        <?php render_media($design['image_path'], $design['title'], 'design-image', 'Место для картинки дизайна'); ?>
                        <div class="design-glow" aria-hidden="true"></div>
                    </div>
                    <div class="design-body">
                        <?php render_platform_chip($design['category']); ?>
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
        <div class="cta-row" style="margin-top: 1.25rem;">
            <a class="btn btn-secondary" href="catalog.php">Весь каталог</a>
            <a class="btn btn-ghost" href="order.php" data-surprise-home>Случайный дизайн</a>
        </div>
    </div>
</section>

<?php if ($stats): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Пульс платформ</h2>
            <p class="section-text">Живая статистика заказов по кошелькам.</p>
        </div>
        <div class="pulse-grid stagger-layout">
            <?php
            $maxOrders = max(1, ...(array_map(static fn($row) => (int) $row['orders'], $stats)));
            foreach ($stats as $row):
                $pct = (int) round(((int) $row['orders'] / $maxOrders) * 100);
            ?>
                <article class="panel pulse-card">
                    <div class="pulse-head">
                        <?php render_platform_icon($row['platform'], 'platform-icon platform-icon-sm'); ?>
                        <strong><?= e(platform_label($row['platform'])) ?></strong>
                    </div>
                    <div class="pulse-bar"><span style="--pulse: <?= $pct ?>%"></span></div>
                    <div class="pulse-meta muted">
                        <span><?= (int) $row['orders'] ?> заказов</span>
                        <span><?= e(format_money((float) $row['total'])) ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Идеи на сейчас</h2>
            <p class="section-text">Три готовых сценария подарка — или пройдите квиз.</p>
        </div>
        <div class="idea-grid stagger-layout">
            <?php foreach ($ideas as $idea): ?>
                <article class="panel idea-card">
                    <?php render_platform_chip($idea['platform']); ?>
                    <h3><?= e($idea['title']) ?></h3>
                    <p class="muted"><?= e($idea['tip']) ?></p>
                    <div class="idea-meta">
                        <span class="stat-pill"><?= e(format_money((float) $idea['amount'])) ?></span>
                        <a class="btn btn-primary" href="order.php?amount=<?= (int) $idea['amount'] ?>&platform=<?= urlencode($idea['platform']) ?>">Оформить</a>
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
                <p>Проверьте код, покажите QR или распечатайте ваучер.</p>
            </article>
        </div>
    </div>
</section>

<?php if ($recentDesigns): ?>
<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Недавно смотрели</h2>
            <p class="section-text">Оформления кошельков из этой сессии.</p>
        </div>
        <div class="design-grid">
            <?php foreach ($recentDesigns as $design): ?>
                <article class="design-tile">
                    <div class="design-media">
                        <?php render_media($design['image_path'], $design['title'], 'design-image', 'Место для картинки дизайна'); ?>
                    </div>
                    <div class="design-body">
                        <?php render_platform_chip($design['category']); ?>
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

<script>
  window.__PLAYGIFT_DESIGNS__ = <?= json_encode(array_map(static fn($d) => (int) $d['id'], $allDesigns), JSON_UNESCAPED_UNICODE) ?>;
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
