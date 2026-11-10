<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
ensure_extra_tables();

$platform = trim((string) ($_GET['platform'] ?? ''));
$params = [];
$sql = 'SELECT * FROM gift_ideas';
if ($platform !== '') {
    $sql .= ' WHERE platform = ?';
    $params[] = $platform;
}
$sql .= ' ORDER BY sort_order ASC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$ideas = $stmt->fetchAll();

$pageTitle = 'Гид по подаркам — PlayGift';
$activeNav = 'guide';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <span class="eyebrow">Идеи подарков</span>
        <h1>Гид по пополнению кошельков</h1>
        <p>Готовые связки «повод + платформа + сумма» — берите как есть или крутите под себя.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container stack-sm">
        <div class="cta-row platform-filter-row">
            <a class="btn <?= $platform === '' ? 'btn-primary' : 'btn-secondary' ?>" href="guide.php">Все идеи</a>
            <?php foreach (['PlayStation', 'Xbox', 'PC'] as $cat): ?>
                <a class="btn platform-filter-btn <?= $platform === $cat ? 'btn-primary' : 'btn-secondary' ?>" href="guide.php?platform=<?= urlencode($cat) ?>">
                    <?php render_platform_icon($cat, 'platform-icon platform-icon-sm'); ?>
                    <span><?= e(platform_label($cat)) ?></span>
                </a>
            <?php endforeach; ?>
            <a class="btn btn-ghost" href="quiz.php">Пройти квиз</a>
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
            <h2 class="section-title">Быстрые правила красивого подарка</h2>
            <p class="section-text">Короткий чеклист, чтобы сертификат выглядел продуманным.</p>
        </div>
        <div class="feature-grid">
            <article class="feature-item">
                <h3>Платформа важнее картинки</h3>
                <p>Сначала кошелёк, которым пользуются. Дизайн — атмосфера поверх.</p>
            </article>
            <article class="feature-item">
                <h3>Сумма с запасом</h3>
                <p>Лучше чуть больше комфортного минимума: приятно хватает на игру + мелочь.</p>
            </article>
            <article class="feature-item">
                <h3>Личное сообщение</h3>
                <p>Два предложения сильнее длинного текста. В форме есть готовые шаблоны.</p>
            </article>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
