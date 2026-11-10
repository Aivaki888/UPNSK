<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'О нас — PlayGift';
$activeNav = 'about';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>О проекте PlayGift</h1>
        <p>Учебный магазин игровых сертификатов для PlayStation, Xbox и PC: аккаунт, темы и полный сценарий покупки.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container about-story stagger-layout">
        <div class="panel">
            <div class="stagger-copy">
                <h2>Зачем этот сайт</h2>
                <p class="muted">PlayGift показывает цикл небольшого интернет-магазина: регистрация, выбор платформы и дизайна, критерии сертификата, сохранение заказа в MySQL и просмотр в личном кабинете.</p>
            </div>
            <div class="feature-grid" style="margin-top: 1.5rem;">
                <article class="feature-item">
                    <h3>Стек</h3>
                    <p>HTML, CSS, JavaScript, PHP и MySQL — как требует учебное задание.</p>
                </article>
                <article class="feature-item">
                    <h3>Платформы</h3>
                    <p>Каталог разделён на PlayStation, Xbox и PC с разными дизайнами.</p>
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

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Частые вопросы</h2>
            <p class="section-text">Короткие ответы по работе PlayGift.</p>
        </div>
        <div class="faq-list stagger-layout">
            <details class="panel faq-item">
                <summary>Это реальные коды PSN / Xbox / Steam?</summary>
                <p class="muted">Нет. Это учебный проект: коды генерируются локально и хранятся в MySQL для демонстрации сценария.</p>
            </details>
            <details class="panel faq-item">
                <summary>Как выбрать платформу?</summary>
                <p class="muted">Откройте каталог и фильтруйте по PlayStation, Xbox или PC — либо сразу оформите нужный дизайн.</p>
            </details>
            <details class="panel faq-item">
                <summary>Как проверить сертификат?</summary>
                <p class="muted">Откройте «Проверка» и введите код — увидите сумму, статус и получателя.</p>
            </details>
            <details class="panel faq-item">
                <summary>Куда писать вопросы?</summary>
                <p class="muted">Форма на странице «Контакты» сохраняет сообщения в базе данных.</p>
            </details>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
