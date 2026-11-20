<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'О нас — PlayGift';
$activeNav = 'about';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <span class="eyebrow">О PlayGift</span>
        <h1>Пополнение кошельков PS, Xbox и Steam</h1>
        <p>Сайт выдаёт цифровые сертификаты на баланс платформы. Ключи игр и подписки не продаём.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container about-story stagger-layout">
        <div class="panel">
            <div class="stagger-copy">
                <h2>О проекте</h2>
                <p class="muted">PlayGift сделан как учебный магазин: регистрация, профиль, тема, каталог, заказ сертификата и проверка кода. Данные хранятся в MySQL.</p>
                <p class="muted">В каталоге только три направления — PlayStation, Xbox и Steam. Сумма и оформление выбираются при заказе.</p>
            </div>
            <div class="feature-grid" style="margin-top: 1.5rem;">
                <article class="feature-item">
                    <h3>Только кошельки</h3>
                    <p>PSN, Xbox / Microsoft и Steam Wallet.</p>
                </article>
                <article class="feature-item">
                    <h3>Три платформы</h3>
                    <p>Отдельные дизайны под каждую платформу.</p>
                </article>
                <article class="feature-item">
                    <h3>Стек</h3>
                    <p>HTML, CSS, JavaScript, PHP и MySQL.</p>
                </article>
            </div>
        </div>
        <?php render_media('assets/img/about.jpg', 'О проекте', 'about-visual', 'Нет изображения'); ?>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Что пополняем</h2>
            <p class="section-text">Сертификат добавляет сумму на баланс. На что тратить — решает получатель.</p>
        </div>
        <div class="feature-grid stagger-layout">
            <article class="feature-item wallet-card">
                <?php render_platform_icon('PlayStation', 'platform-icon platform-icon-lg'); ?>
                <?php render_platform_chip('PlayStation'); ?>
                <h3>Кошелёк PSN</h3>
                <p>Пополнение PlayStation Network.</p>
            </article>
            <article class="feature-item wallet-card">
                <?php render_platform_icon('Xbox', 'platform-icon platform-icon-lg'); ?>
                <?php render_platform_chip('Xbox'); ?>
                <h3>Баланс Xbox</h3>
                <p>Пополнение счёта Microsoft / Xbox.</p>
            </article>
            <article class="feature-item wallet-card">
                <?php render_platform_icon('PC', 'platform-icon platform-icon-lg'); ?>
                <?php render_platform_chip('PC'); ?>
                <h3>Кошелёк Steam</h3>
                <p>Пополнение Steam Wallet.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container about-timeline stagger-layout">
        <div class="section-head">
            <h2 class="section-title">Как это устроено</h2>
            <p class="section-text">Четыре шага от каталога до кода.</p>
        </div>
        <ol class="timeline-list">
            <li class="panel timeline-item">
                <span class="timeline-step">01</span>
                <div>
                    <h3>Платформа</h3>
                    <p class="muted">Выбираете дизайн под PlayStation, Xbox или Steam.</p>
                </div>
            </li>
            <li class="panel timeline-item">
                <span class="timeline-step">02</span>
                <div>
                    <h3>Сумма и получатель</h3>
                    <p class="muted">Указываете номинал, повод, сообщение и способ доставки.</p>
                </div>
            </li>
            <li class="panel timeline-item">
                <span class="timeline-step">03</span>
                <div>
                    <h3>Код в кабинете</h3>
                    <p class="muted">Сертификат сохраняется в базе. Его можно проверить, скопировать или распечатать.</p>
                </div>
            </li>
            <li class="panel timeline-item">
                <span class="timeline-step">04</span>
                <div>
                    <h3>Проверка</h3>
                    <p class="muted">Коды учебные: генерируются на сайте и не активируют реальный баланс платформы.</p>
                </div>
            </li>
        </ol>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Почему только кошельки</h2>
            <p class="section-text">Один тип товара проще показать в каталоге и формах.</p>
        </div>
        <div class="about-reasons stagger-layout">
            <article class="panel reason-card">
                <h3>Понятный товар</h3>
                <p class="muted">Пополнение баланса, а не набор случайных ключей.</p>
            </article>
            <article class="panel reason-card">
                <h3>Выбор за получателем</h3>
                <p class="muted">Он сам решает, на что потратить сумму.</p>
            </article>
            <article class="panel reason-card">
                <h3>Узкая тема</h3>
                <p class="muted">Проще связать страницы: каталог, заказ, проверка, кабинет.</p>
            </article>
            <article class="panel reason-card">
                <h3>Без лишнего</h3>
                <p class="muted">Нет подписок и ключей — только кошелёк.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container about-values stagger-layout">
        <div class="panel about-values-copy">
            <div class="stagger-copy">
                <h2>Что есть на сайте</h2>
                <p class="muted">Базовый набор страниц и функций для учебного проекта.</p>
            </div>
            <ul class="value-list">
                <li><strong>Аккаунт.</strong> Регистрация, вход и профиль.</li>
                <li><strong>Заказ.</strong> Дизайн, сумма, получатель, код сертификата.</li>
                <li><strong>Дополнительно.</strong> Избранное, проверка кода, печать, тема.</li>
                <li><strong>Контакты.</strong> Форма пишет сообщения в MySQL.</li>
            </ul>
        </div>
        <div class="panel about-note">
            <h3>Состав работы</h3>
            <p class="muted">Есть регистрация и авторизация, профиль, смена темы, больше шести страниц и покупка сертификата с выбором дизайна.</p>
            <div class="cta-row" style="margin-top: 1.1rem;">
                <a class="btn btn-primary" href="order.php">Оформить</a>
                <a class="btn btn-secondary" href="catalog.php">Каталог</a>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Частые вопросы</h2>
            <p class="section-text">Коротко по кодам и заказу.</p>
        </div>
        <div class="faq-list stagger-layout">
            <details class="panel faq-item">
                <summary>Можно ли купить ключ игры?</summary>
                <p class="muted">Нет. Только сертификат на пополнение кошелька.</p>
            </details>
            <details class="panel faq-item">
                <summary>Это реальные коды PSN / Xbox / Steam?</summary>
                <p class="muted">Нет. Коды создаются локально и хранятся в MySQL.</p>
            </details>
            <details class="panel faq-item">
                <summary>Чем отличаются дизайны?</summary>
                <p class="muted">Это оформление ваучера. Сумма выбирается отдельно.</p>
            </details>
            <details class="panel faq-item">
                <summary>Как проверить сертификат?</summary>
                <p class="muted">Страница «Проверка» — введите код.</p>
            </details>
            <details class="panel faq-item">
                <summary>Куда писать?</summary>
                <p class="muted">Форма на странице «Контакты».</p>
            </details>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
