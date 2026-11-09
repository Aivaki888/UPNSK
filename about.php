<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'О нас — Дари';
$activeNav = 'about';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1>О проекте Дари</h1>
        <p>Учебный сайт подарочных сертификатов: регистрация, профиль, темы оформления и полноценный сценарий покупки.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container about-story">
        <div class="panel">
            <h2>Зачем этот сайт</h2>
            <p class="muted">«Дари» показывает полный цикл небольшого интернет-магазина без лишней сложности: пользователь регистрируется, выбирает дизайн и критерии сертификата, сохраняет заказ в MySQL и видит его в личном кабинете.</p>
            <div class="feature-grid" style="margin-top: 1.5rem;">
                <article class="feature-item">
                    <h3>Стек</h3>
                    <p>HTML, CSS, JavaScript, PHP и MySQL — как требует учебное задание.</p>
                </article>
                <article class="feature-item">
                    <h3>7 страниц</h3>
                    <p>Главная, каталог, оформление, о нас, вход, регистрация, профиль и мои сертификаты.</p>
                </article>
                <article class="feature-item">
                    <h3>Темы</h3>
                    <p>Светлая и тёмная тема переключаются в шапке и запоминаются в браузере.</p>
                </article>
            </div>
        </div>
        <?php render_media('assets/img/about.jpg', 'О проекте', 'about-visual', 'Место для изображения'); ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
