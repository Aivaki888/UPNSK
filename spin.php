<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$segments = [
    ['label' => '500 ₽', 'amount' => 500, 'platform' => 'PC', 'color' => '#0f8f9f'],
    ['label' => '1000 ₽', 'amount' => 1000, 'platform' => 'Xbox', 'color' => '#107c10'],
    ['label' => '1500 ₽', 'amount' => 1500, 'platform' => 'PlayStation', 'color' => '#0068d9'],
    ['label' => '2500 ₽', 'amount' => 2500, 'platform' => 'PC', 'color' => '#1b2838'],
    ['label' => '5000 ₽', 'amount' => 5000, 'platform' => 'Xbox', 'color' => '#54d168'],
    ['label' => '1000 ₽', 'amount' => 1000, 'platform' => 'PlayStation', 'color' => '#3aa0ff'],
    ['label' => '1500 ₽', 'amount' => 1500, 'platform' => 'PC', 'color' => '#0f8f9f'],
    ['label' => '2500 ₽', 'amount' => 2500, 'platform' => 'PlayStation', 'color' => '#0068d9'],
];

$pageTitle = 'Колесо удачи — PlayGift';
$activeNav = 'spin';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <span class="eyebrow">Мини-игра</span>
        <h1>Колесо удачи</h1>
        <p>Крутаните диск — PlayGift подскажет номинал и кошелёк для подарка.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container spin-layout">
        <div class="panel spin-stage">
            <div class="spin-pointer" aria-hidden="true"></div>
            <div class="spin-wheel" data-spin-wheel style="--segments: <?= count($segments) ?>">
                <?php foreach ($segments as $i => $seg): ?>
                    <div class="spin-slice" style="--i: <?= $i ?>; --c: <?= e($seg['color']) ?>"></div>
                <?php endforeach; ?>
                <div class="spin-center">GO</div>
            </div>
            <button class="btn btn-primary" type="button" data-spin-btn>Крутить</button>
        </div>

        <aside class="panel spin-result" data-spin-result>
            <h2>Результат</h2>
            <p class="muted">Нажмите «Крутить», чтобы получить случайную связку сумма + платформа.</p>
            <div class="spin-result-card is-empty" data-spin-card>
                <strong data-spin-amount>—</strong>
                <span data-spin-platform>—</span>
            </div>
            <a class="btn btn-secondary" href="order.php" data-spin-order hidden>Оформить этот вариант</a>
        </aside>
    </div>
</section>

<script>
  window.__PLAYGIFT_SPIN__ = <?= json_encode($segments, JSON_UNESCAPED_UNICODE) ?>;
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
