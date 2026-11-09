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

$pageTitle = 'Контакты — Дари';
$activeNav = 'contact';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero stagger-copy">
    <div class="container">
        <h1>Контакты</h1>
        <p>Напишите нам по вопросам сертификатов, дизайнов или аккаунта.</p>
    </div>
</section>

<section class="container auth-layout stagger-layout">
    <div class="panel">
        <div class="stagger-copy">
            <h2>Связаться с нами</h2>
            <p class="muted">Обычно отвечаем в рабочие дни. Учебный проект сохраняет сообщения в MySQL.</p>
        </div>
        <div class="feature-grid" style="margin-top: 1.25rem;">
            <article class="feature-item">
                <h3>Email</h3>
                <p>hello@dari.local</p>
            </article>
            <article class="feature-item">
                <h3>Время</h3>
                <p>Пн–Пт · 10:00–19:00</p>
            </article>
            <article class="feature-item">
                <h3>Проверка</h3>
                <p><a href="verify.php">Проверить код сертификата</a></p>
            </article>
        </div>
    </div>

    <div class="auth-card">
        <?php if ($errors): ?>
            <div class="flash flash-error" style="margin-bottom: 1rem;">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="form-grid stagger-form" method="post" action="contact.php">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="form-row">
                <label for="name">Имя</label>
                <input id="name" name="name" type="text" required value="<?= e($name) ?>">
            </div>
            <div class="form-row">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required value="<?= e($email) ?>">
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
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
