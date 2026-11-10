<?php

declare(strict_types=1);
?>
    </main>
    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <div class="brand brand-footer">
                    <?php render_media('assets/img/logo.png', 'Логотип PlayGift', 'brand-logo', 'Лого'); ?>
                    <span class="brand-text">PlayGift</span>
                </div>
                <p class="footer-copy">Сертификаты только на пополнение кошельков PlayStation, Xbox и Steam. Учебный проект на HTML, CSS, JS, PHP и MySQL.</p>
            </div>

            <div class="footer-col">
                <h2 class="footer-title">Кошельки</h2>
                <ul class="footer-links footer-wallet-links">
                    <li>
                        <a href="catalog.php?category=PlayStation">
                            <?php render_platform_icon('PlayStation', 'platform-icon platform-icon-xs'); ?>
                            <span>PSN Wallet</span>
                        </a>
                    </li>
                    <li>
                        <a href="catalog.php?category=Xbox">
                            <?php render_platform_icon('Xbox', 'platform-icon platform-icon-xs'); ?>
                            <span>Xbox Balance</span>
                        </a>
                    </li>
                    <li>
                        <a href="catalog.php?category=PC">
                            <?php render_platform_icon('PC', 'platform-icon platform-icon-xs'); ?>
                            <span>Steam Wallet</span>
                        </a>
                    </li>
                    <li><a href="order.php">Оформить</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h2 class="footer-title">Сервис</h2>
                <ul class="footer-links">
                    <li><a href="verify.php">Проверка кода</a></li>
                    <li><a href="favorites.php">Избранное</a></li>
                    <li><a href="certificates.php">Мои сертификаты</a></li>
                    <li><a href="profile.php">Профиль</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h2 class="footer-title">Контакты</h2>
                <ul class="footer-contacts">
                    <li><span>Email</span><a href="mailto:hello@playgift.local">hello@playgift.local</a></li>
                    <li><span>Время</span><span>Пн–Пт · 10:00–19:00</span></li>
                    <li><span>Связь</span><a href="contact.php">Написать нам</a></li>
                </ul>
            </div>
        </div>

        <div class="container footer-newsletter panel">
            <div>
                <h2 class="footer-title">Рассылка идей</h2>
                <p class="footer-copy">Короткие подсказки по подаркам на кошельки — без спама.</p>
            </div>
            <form class="newsletter-form" method="post" action="newsletter.php">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="redirect" value="<?= e($_SERVER['REQUEST_URI'] ?? 'index.php') ?>">
                <input type="email" name="email" required placeholder="you@email.com" aria-label="Email для рассылки">
                <button class="btn btn-primary" type="submit">Подписаться</button>
            </form>
        </div>

        <div class="container footer-bottom">
            <span>© <?= date('Y') ?> PlayGift</span>
            <span>Только кошельки · PS · Xbox · Steam</span>
        </div>
    </footer>
</div>
<canvas id="confetti-canvas" aria-hidden="true"></canvas>
<script src="assets/js/main.js" defer></script>
</body>
</html>
