<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$designs = db()->query('SELECT * FROM designs WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 3')->fetchAll();

$pageTitle = 'Дари — подарочные сертификаты';
$activeNav = 'home';
$bodyClass = 'page-home';

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">Подарочные сертификаты</span>
        <h1>Дари</h1>
        <p class="lead">Персональный сертификат за несколько минут: дизайн, сумма, повод и короткое послание.</p>
        <div class="cta-row">
            <a class="btn btn-primary" href="order.php">Оформить</a>
            <a class="btn btn-secondary" href="catalog.php">Каталог</a>
        </div>
    </div>
    <div class="hero-visual" aria-hidden="true">
        <div class="hero-seal">Dari</div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Шесть характеров</h2>
            <p class="section-text">От спокойного минимала до зимнего пейзажа — выберите визуальный тон подарка.</p>
        </div>
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
            <h2 class="section-title">Три шага</h2>
            <p class="section-text">Короткий путь от идеи до кода сертификата.</p>
        </div>
        <div class="feature-grid">
            <article class="feature-item">
                <h3>01 · Аккаунт</h3>
                <p>Регистрация открывает профиль и историю оформлений.</p>
            </article>
            <article class="feature-item">
                <h3>02 · Критерии</h3>
                <p>Картинка, сумма, повод, получатель, акцент и доставка.</p>
            </article>
            <article class="feature-item">
                <h3>03 · Код</h3>
                <p>Готовый сертификат сохраняется в разделе «Мои сертификаты».</p>
            </article>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
