<?php

declare(strict_types=1);
?>
    </main>
    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <div class="brand brand-footer">
                    <?php render_media('assets/img/logo.png', 'Логотип Дари', 'brand-logo', 'Лого'); ?>
                    <span class="brand-text">Дари</span>
                </div>
                <p class="footer-copy">Подарочные сертификаты с выбором дизайна, суммы и повода. Учебный проект на HTML, CSS, JS, PHP и MySQL.</p>
            </div>

            <div class="footer-col">
                <h2 class="footer-title">Разделы</h2>
                <ul class="footer-links">
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="catalog.php">Каталог</a></li>
                    <li><a href="order.php">Оформить</a></li>
                    <li><a href="about.php">О проекте</a></li>
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
                    <li><span>Email</span><a href="mailto:hello@dari.local">hello@dari.local</a></li>
                    <li><span>Время</span><span>Пн–Пт · 10:00–19:00</span></li>
                    <li><span>Связь</span><a href="contact.php">Написать нам</a></li>
                    <li><span>© <?= date('Y') ?></span><span>Дари</span></li>
                </ul>
            </div>
        </div>
    </footer>
</div>
<script src="assets/js/main.js" defer></script>
</body>
</html>
