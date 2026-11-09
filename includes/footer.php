<?php

declare(strict_types=1);
?>
    </main>
    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <div class="brand brand-footer">
                    <?php render_media('assets/img/logo.png', 'Логотип Дари', 'brand-logo', 'Лого'); ?>
                    <span class="brand-text">Дари</span>
                </div>
                <p class="footer-copy">Сертификаты с характером: дизайн, сумма, повод. Учебный проект на HTML, CSS, JS, PHP и MySQL.</p>
            </div>
            <div>
                <h2 class="footer-title">Разделы</h2>
                <ul class="footer-links">
                    <li><a href="catalog.php">Каталог</a></li>
                    <li><a href="order.php">Оформить</a></li>
                    <li><a href="verify.php">Проверка кода</a></li>
                    <li><a href="contact.php">Контакты</a></li>
                    <li><a href="favorites.php">Избранное</a></li>
                    <li><a href="about.php">О проекте</a></li>
                </ul>
            </div>
            <div>
                <h2 class="footer-title">Контакты</h2>
                <p class="footer-meta">hello@dari.local<br>Пн–Пт · 10:00–19:00<br><a href="contact.php">Написать нам</a></p>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© <?= date('Y') ?> Дари</span>
            <span>Светлая и тёмная тема · безопасная авторизация</span>
        </div>
    </footer>
</div>
<script src="assets/js/main.js" defer></script>
</body>
</html>
