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
        <p>Коротко и честно: чем отличаются PSN, Xbox и Steam в логике PlayGift.</p>
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
                        <td>Кому дарить</td>
                        <td>Любителям эксклюзивов</td>
                        <td>Тем, кто играет с друзьями</td>
                        <td>Охотникам за распродажами</td>
                    </tr>
                    <tr>
                        <td>Гибкость трат</td>
                        <td>Высокая</td>
                        <td>Высокая</td>
                        <td>Очень высокая</td>
                    </tr>
                    <tr>
                        <td>Стиль оформления</td>
                        <td>Синий pulse / midnight</td>
                        <td>Зелёный core / carbon</td>
                        <td>RGB / clean deck</td>
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
            <a class="btn btn-secondary" href="spin.php">Крутануть колесо</a>
            <a class="btn btn-ghost" href="order.php">Сразу оформить</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
