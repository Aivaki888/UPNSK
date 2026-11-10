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
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
                <span></span><span></span>
            </button>
            <nav class="site-nav" id="site-nav" data-site-nav>
                <a class="<?= $activeNav === 'home' ? 'is-active' : '' ?>" href="index.php">Главная</a>
                <a class="<?= $activeNav === 'catalog' ? 'is-active' : '' ?>" href="catalog.php">Каталог</a>
                <a class="<?= $activeNav === 'order' ? 'is-active' : '' ?>" href="order.php">Оформить</a>
                <a class="<?= $activeNav === 'verify' ? 'is-active' : '' ?>" href="verify.php">Проверка</a>
                <a class="<?= $activeNav === 'contact' ? 'is-active' : '' ?>" href="contact.php">Контакты</a>
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
                <button class="theme-toggle" type="button" data-theme-toggle aria-label="Переключить тему">
                    <?php render_media('assets/img/theme-icon.png', 'Иконка темы', 'theme-slot', 'Иконка'); ?>
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
