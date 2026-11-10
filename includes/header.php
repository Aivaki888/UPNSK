<?php

declare(strict_types=1);

$user = current_user();
$flash = take_flash();
$pageTitle = $pageTitle ?? 'PlayGift — пополнение кошельков';
$bodyClass = $bodyClass ?? '';
$activeNav = $activeNav ?? '';
$themePref = $user['theme_pref'] ?? ($_COOKIE['theme'] ?? 'system');
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light" data-theme-pref="<?= e($themePref) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="PlayGift — сертификаты только на пополнение кошельков PlayStation, Xbox и Steam.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
      (function () {
        try {
          var stored = localStorage.getItem('playgift-theme') || localStorage.getItem('dari-theme');
          var pref = stored || document.documentElement.getAttribute('data-theme-pref') || 'system';
          var theme = pref;
          if (pref !== 'dark' && pref !== 'light') {
            theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            pref = 'system';
          }
          document.documentElement.setAttribute('data-theme', theme);
          document.documentElement.setAttribute('data-theme-pref', pref);
          var accent = localStorage.getItem('playgift-accent') || 'ps';
          document.documentElement.setAttribute('data-accent', accent);
        } catch (e) {}
      })();
    </script>
</head>
<body class="<?= e($bodyClass) ?>">
<div class="page-shell">
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="index.php" aria-label="PlayGift — на главную">
                <?php render_media('assets/img/logo.png', 'Логотип PlayGift', 'brand-logo', 'Лого'); ?>
                <span class="brand-text">PlayGift</span>
            </a>
            <nav class="site-nav" id="site-nav" data-site-nav>
                <a class="<?= $activeNav === 'home' ? 'is-active' : '' ?>" href="index.php">Главная</a>
                <a class="<?= $activeNav === 'catalog' ? 'is-active' : '' ?>" href="catalog.php">Каталог</a>
                <a class="<?= $activeNav === 'order' ? 'is-active' : '' ?>" href="order.php">Оформить</a>
                <a class="<?= $activeNav === 'quiz' ? 'is-active' : '' ?>" href="quiz.php">Квиз</a>
                <a class="<?= $activeNav === 'guide' ? 'is-active' : '' ?>" href="guide.php">Гид</a>
                <a class="<?= $activeNav === 'verify' ? 'is-active' : '' ?>" href="verify.php">Проверка</a>
                <a class="<?= $activeNav === 'about' ? 'is-active' : '' ?>" href="about.php">О нас</a>
                <?php if ($user): ?>
                    <a class="<?= $activeNav === 'favorites' ? 'is-active' : '' ?>" href="favorites.php">Избранное</a>
                    <a class="<?= $activeNav === 'certificates' ? 'is-active' : '' ?>" href="certificates.php">Мои сертификаты</a>
                    <a class="<?= $activeNav === 'profile' ? 'is-active' : '' ?>" href="profile.php">Профиль</a>
                <?php else: ?>
                    <a class="<?= $activeNav === 'login' ? 'is-active' : '' ?>" href="login.php">Вход</a>
                    <a class="nav-cta <?= $activeNav === 'register' ? 'is-active' : '' ?>" href="register.php">Регистрация</a>
                <?php endif; ?>
            </nav>
            <div class="header-actions">
                <div class="accent-switch" data-accent-switch aria-label="Акцент платформы">
                    <button type="button" data-accent="ps" title="Акцент PlayStation">PS</button>
                    <button type="button" data-accent="xbox" title="Акцент Xbox">XB</button>
                    <button type="button" data-accent="steam" title="Акцент Steam">ST</button>
                </div>
                <button class="theme-toggle" type="button" data-theme-toggle aria-label="Переключить тему">
                    <svg class="theme-icon theme-icon-sun" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <circle cx="12" cy="12" r="4.2" fill="currentColor"/>
                        <path d="M12 2.5v2.2M12 19.3v2.2M2.5 12h2.2M19.3 12h2.2M5.05 5.05l1.56 1.56M17.39 17.39l1.56 1.56M5.05 18.95l1.56-1.56M17.39 6.61l1.56-1.56" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <svg class="theme-icon theme-icon-moon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path fill="currentColor" d="M16.8 14.8A7.2 7.2 0 0 1 9.2 7.2c0-.5.05-1 .14-1.48A7.8 7.8 0 1 0 18.28 15.9c-.47.1-.96.15-1.48.15z"/>
                    </svg>
                </button>
                <button class="nav-toggle" type="button" aria-label="Меню" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <?php if ($flash): ?>
        <div class="container">
            <div class="flash flash-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
        </div>
    <?php endif; ?>

    <main class="site-main">
