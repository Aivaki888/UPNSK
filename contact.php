<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = current_user();
$errors = [];
$name = $user['name'] ?? '';
$email = $user['email'] ?? '';
$subject = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Сессия устарела. Обновите страницу.';
    }

    $name = trim((string) ($_POST['name'] ?? ''));
    $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
    $subject = trim((string) ($_POST['subject'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($name === '' || mb_strlen($name) < 2) {
        $errors[] = 'Укажите имя.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Укажите корректный email.';
    }
    if ($subject === '' || mb_strlen($subject) < 3) {
        $errors[] = 'Укажите тему сообщения.';
    }
    if ($message === '' || mb_strlen($message) < 10) {
        $errors[] = 'Сообщение слишком короткое.';
    }

    if (!$errors) {
        ensure_extra_tables();
        $stmt = db()->prepare(
            'INSERT INTO contact_messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $user ? (int) $user['id'] : null,
            $name,
            $email,
            $subject,
            $message,
        ]);
        flash('success', 'Сообщение отправлено. Мы ответим на указанный email.');
        redirect('contact.php');
    }
}

$pageTitle = 'Контакты — PlayGift';
$activeNav = 'contact';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>Контакты</h1>
        <p>Вопросы по пополнению кошельков PlayStation, Xbox и Steam, дизайнам или аккаунту.</p>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container contact-page">
        <div class="contact-info stagger-layout">
            <article class="panel contact-card">
                <h2>Email</h2>
                <p class="muted">Пишите напрямую или через форму справа.</p>
                <a class="contact-link" href="mailto:hello@playgift.local">hello@playgift.local</a>
            </article>
            <article class="panel contact-card">
                <h2>Время ответа</h2>
                <p class="muted">Отвечаем в рабочие дни.</p>
                <p class="contact-link">Пн–Пт · 10:00–19:00</p>
            </article>
            <article class="panel contact-card">
                <h2>Быстрые ссылки</h2>
                <div class="contact-actions">
                    <a class="btn btn-secondary" href="verify.php">Проверить код</a>
                    <a class="btn btn-ghost" href="about.php">О проекте</a>
                </div>
            </article>
        </div>

        <div class="auth-card contact-form-card">
            <div class="stagger-copy">
                <h2>Написать сообщение</h2>
                <p class="muted">Сообщение сохранится в базе MySQL учебного проекта.</p>
            </div>

            <?php if ($errors): ?>
                <div class="flash flash-error">
                    <?php foreach ($errors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form class="form-grid stagger-form" method="post" action="contact.php">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <div class="form-row two">
                    <div class="form-row">
                        <label for="name">Имя</label>
                        <input id="name" name="name" type="text" required value="<?= e($name) ?>">
                    </div>
                    <div class="form-row">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" required value="<?= e($email) ?>">
                    </div>
                </div>
                <div class="form-row">
                    <label for="subject">Тема</label>
                    <input id="subject" name="subject" type="text" required value="<?= e($subject) ?>" placeholder="Вопрос по сертификату">
                </div>
                <div class="form-row">
                    <label for="message">Сообщение</label>
                    <textarea id="message" name="message" required><?= e($message) ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Отправить</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
