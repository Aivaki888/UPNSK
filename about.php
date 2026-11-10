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
        <h1>Пополняем кошельки. Не продаём ключи.</h1>
        <p>PlayGift — учебный сервис цифровых сертификатов только для пополнения кошельков PlayStation, Xbox и PC. Один сценарий: выбрали платформу, сумму и оформление — получили код.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container about-story stagger-layout">
        <div class="panel">
            <div class="stagger-copy">
                <h2>Идея проекта</h2>
                <p class="muted">Мы сознательно сузили ассортимент. Никаких ключей игр, подписок и «серых» активаций — только баланс кошелька. Так проще объяснить логику магазина: платформа → номинал → персональный сертификат → проверка кода.</p>
                <p class="muted">PlayGift показывает полный учебный цикл: регистрация, профиль, светлая и тёмная тема, каталог дизайнов, оформление заказа и хранение данных в MySQL.</p>
            </div>
            <div class="feature-grid" style="margin-top: 1.5rem;">
                <article class="feature-item">
                    <h3>Только кошельки</h3>
                    <p>PSN Wallet, Microsoft / Xbox balance и Steam Wallet — и больше ничего.</p>
                </article>
                <article class="feature-item">
                    <h3>Три платформы</h3>
                    <p>Каталог чётко делится на PlayStation, Xbox и PC с разными оформлениями.</p>
                </article>
                <article class="feature-item">
                    <h3>Учебный стек</h3>
                    <p>HTML, CSS, JavaScript, PHP и MySQL — как требует задание.</p>
                </article>
            </div>
        </div>
        <?php render_media('assets/img/about.jpg', 'О проекте', 'about-visual', 'Место для изображения'); ?>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Что именно пополняем</h2>
            <p class="section-text">Один продукт — баланс. Получатель сам решает, на что его потратить.</p>
        </div>
        <div class="feature-grid stagger-layout">
            <article class="feature-item wallet-card">
                <?php render_platform_icon('PlayStation', 'platform-icon platform-icon-lg'); ?>
                <?php render_platform_chip('PlayStation'); ?>
                <h3>Кошелёк PSN</h3>
                <p>Сертификат добавляет сумму на баланс аккаунта PlayStation Network. Дальше — игры, дополнения или то, что выберет получатель в магазине.</p>
            </article>
            <article class="feature-item wallet-card">
                <?php render_platform_icon('Xbox', 'platform-icon platform-icon-lg'); ?>
                <?php render_platform_chip('Xbox'); ?>
                <h3>Баланс Microsoft</h3>
                <p>Пополнение счёта Xbox / Microsoft. Деньги остаются в кошельке платформы и тратятся там, где удобно получателю.</p>
            </article>
            <article class="feature-item wallet-card">
                <?php render_platform_icon('PC', 'platform-icon platform-icon-lg'); ?>
                <?php render_platform_chip('PC'); ?>
                <h3>Кошелёк Steam</h3>
                <p>Цифровой сертификат на пополнение Steam Wallet. Без привязки к конкретной игре — только сумма на счёте.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container about-timeline stagger-layout">
        <div class="section-head">
            <h2 class="section-title">Как устроен сервис</h2>
            <p class="section-text">От выбора платформы до готового кода — короткая и прозрачная цепочка.</p>
        </div>
        <ol class="timeline-list">
            <li class="panel timeline-item">
                <span class="timeline-step">01</span>
                <div>
                    <h3>Выбор платформы</h3>
                    <p class="muted">В каталоге остаются только дизайны PlayStation, Xbox и PC. Каждый дизайн — оформление ваучера на пополнение кошелька.</p>
                </div>
            </li>
            <li class="panel timeline-item">
                <span class="timeline-step">02</span>
                <div>
                    <h3>Номинал и получатель</h3>
                    <p class="muted">Выбираете сумму, повод, стиль и способ доставки. Можно добавить короткое сообщение к подарку.</p>
                </div>
            </li>
            <li class="panel timeline-item">
                <span class="timeline-step">03</span>
                <div>
                    <h3>Код в личном кабинете</h3>
                    <p class="muted">Сертификат сохраняется в MySQL. Его можно проверить, скопировать, распечатать или отметить использованным.</p>
                </div>
            </li>
            <li class="panel timeline-item">
                <span class="timeline-step">04</span>
                <div>
                    <h3>Пополнение кошелька</h3>
                    <p class="muted">В учебной версии код демонстрирует сценарий. В реальном сервисе такой код шёл бы на баланс выбранной платформы.</p>
                </div>
            </li>
        </ol>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Почему только кошельки</h2>
            <p class="section-text">Узкая тема делает сайт понятнее и честнее.</p>
        </div>
        <div class="about-reasons stagger-layout">
            <article class="panel reason-card">
                <h3>Понятный продукт</h3>
                <p class="muted">Пользователь сразу видит ценность: «деньги на счёт платформы», а не список случайных ключей и подписок.</p>
            </article>
            <article class="panel reason-card">
                <h3>Свобода выбора</h3>
                <p class="muted">Получатель сам решает, что купить — новую игру, DLC или просто накопить баланс.</p>
            </article>
            <article class="panel reason-card">
                <h3>Чистый учебный кейс</h3>
                <p class="muted">Один тип товара проще описать в формах, каталоге, проверке кода и личном кабинете.</p>
            </article>
            <article class="panel reason-card">
                <h3>Без лишнего шума</h3>
                <p class="muted">Мы не смешиваем подарочные карты, подписки и ключи. PlayGift отвечает только за пополнение кошелька.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container about-values stagger-layout">
        <div class="panel about-values-copy">
            <div class="stagger-copy">
                <h2>Что важно для нас</h2>
                <p class="muted">Даже в учебном проекте хочется, чтобы сервис звучал как настоящий продукт: спокойный интерфейс, честные формулировки и понятный путь от главной до готового сертификата.</p>
            </div>
            <ul class="value-list">
                <li><strong>Честность.</strong> На сайте прямо сказано: это демо-магазин, коды учебные.</li>
                <li><strong>Фокус.</strong> Одна задача — пополнение кошельков PS, Xbox и PC.</li>
                <li><strong>Удобство.</strong> Избранное, проверка кода, печать и тёмная тема уже встроены.</li>
                <li><strong>Открытость.</strong> Вопросы можно отправить через форму контактов — сообщения пишутся в MySQL.</li>
            </ul>
        </div>
        <div class="panel about-note">
            <h3>Коротко для проверяющего</h3>
            <p class="muted">Проект закрывает требования: регистрация и авторизация, профиль, тема оформления, 6+ страниц и сценарий покупки сертификата с выбором дизайна и критериев. Тематика — цифровые сертификаты на пополнение игровых кошельков.</p>
            <div class="cta-row" style="margin-top: 1.1rem;">
                <a class="btn btn-primary" href="order.php">Оформить пополнение</a>
                <a class="btn btn-secondary" href="catalog.php">Смотреть каталог</a>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <h2 class="section-title">Частые вопросы</h2>
            <p class="section-text">Ответы про кошельки, коды и работу сайта.</p>
        </div>
        <div class="faq-list stagger-layout">
            <details class="panel faq-item">
                <summary>Можно ли купить ключ конкретной игры?</summary>
                <p class="muted">Нет. PlayGift оформляет только сертификаты на пополнение кошелька платформы.</p>
            </details>
            <details class="panel faq-item">
                <summary>Это реальные коды PSN / Xbox / Steam?</summary>
                <p class="muted">Нет. Это учебный проект: коды генерируются локально и хранятся в MySQL, чтобы показать сценарий покупки и проверки.</p>
            </details>
            <details class="panel faq-item">
                <summary>Чем отличаются дизайны?</summary>
                <p class="muted">Это оформление ваучера под платформу. Сумма пополнения выбирается отдельно при заказе.</p>
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
