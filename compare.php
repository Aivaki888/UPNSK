<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Сравнение кошельков — PlayGift';
$activeNav = 'compare';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <span class="eyebrow">Выбор платформы</span>
        <h1>Сравнение кошельков</h1>
        <p>Чем отличаются PSN, Xbox и Steam на этом сайте.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="compare-table-wrap panel">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Параметр</th>
                        <th><?php render_platform_icon('PlayStation', 'platform-icon platform-icon-sm'); ?> PlayStation</th>
                        <th><?php render_platform_icon('Xbox', 'platform-icon platform-icon-sm'); ?> Xbox</th>
                        <th><?php render_platform_icon('PC', 'platform-icon platform-icon-sm'); ?> Steam</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Что пополняем</td>
                        <td>Кошелёк PSN</td>
                        <td>Баланс Microsoft / Xbox</td>
                        <td>Steam Wallet</td>
                    </tr>
                    <tr>
                        <td>Кому подходит</td>
                        <td>Игры на PlayStation</td>
                        <td>Игры на Xbox</td>
                        <td>Игры в Steam</td>
                    </tr>
                    <tr>
                        <td>На что тратится</td>
                        <td>Магазин PSN</td>
                        <td>Microsoft Store / Xbox</td>
                        <td>Магазин Steam</td>
                    </tr>
                    <tr>
                        <td>Оформление на сайте</td>
                        <td>Синие дизайны</td>
                        <td>Зелёные дизайны</td>
                        <td>PC / Steam дизайны</td>
                    </tr>
                    <tr>
                        <td>Быстрый старт</td>
                        <td><a href="catalog.php?category=PlayStation">Каталог PS</a></td>
                        <td><a href="catalog.php?category=Xbox">Каталог Xbox</a></td>
                        <td><a href="catalog.php?category=PC">Каталог Steam</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="cta-row" style="margin-top: 1.25rem;">
            <a class="btn btn-primary" href="quiz.php">Подобрать через квиз</a>
            <a class="btn btn-secondary" href="spin.php">Колесо удачи</a>
            <a class="btn btn-ghost" href="order.php">Оформить</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
