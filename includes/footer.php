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
                <p class="footer-copy">Игровые сертификаты для PlayStation, Xbox и PC. Выберите платформу, сумму и дизайн — учебный проект на HTML, CSS, JS, PHP и MySQL.</p>
            </div>

            <div class="footer-col">
                <h2 class="footer-title">Платформы</h2>
                <ul class="footer-links">
                    <li><a href="catalog.php?category=PlayStation">PlayStation</a></li>
                    <li><a href="catalog.php?category=Xbox">Xbox</a></li>
                    <li><a href="catalog.php?category=PC">PC</a></li>
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

        <div class="container footer-bottom">
            <span>© <?= date('Y') ?> PlayGift</span>
            <span>PS · Xbox · PC · светлая и тёмная тема</span>
        </div>
    </footer>
</div>
<script src="assets/js/main.js" defer></script>
</body>
</html>
