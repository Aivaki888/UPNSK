<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (current_user()) {
    redirect('profile.php');
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Сессия устарела. Обновите страницу и попробуйте снова.';
    }

    $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $errors[] = 'Введите email и пароль.';
    }

    if (!$errors) {
        $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors[] = 'Неверный email или пароль.';
        } else {
            $_SESSION['user_id'] = (int) $user['id'];
            flash('success', 'Вы вошли в аккаунт.');
            redirect('profile.php');
        }
    }
}

$pageTitle = 'Вход — Дари';
$activeNav = 'login';

require __DIR__ . '/includes/header.php';
?>

<section class="container auth-layout stagger-layout">
    <div class="auth-side">
        <?php render_media('assets/img/auth.jpg', 'Авторизация', 'auth-media', 'Место для изображения'); ?>
        <div class="auth-side-copy stagger-copy">
            <h2>С возвращением</h2>
            <p>Войдите, чтобы открыть профиль, историю сертификатов и быстрое оформление подарка.</p>
        </div>
    </div>
    <div class="auth-card">
        <div class="stagger-copy">
            <h1>Вход</h1>
            <p class="muted">Используйте email и пароль, указанные при регистрации.</p>
        </div>

        <?php if ($errors): ?>
            <div class="flash flash-error" style="margin: 1rem 0;">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="form-grid stagger-form" method="post" action="login.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="form-row">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required value="<?= e($email) ?>" autocomplete="email">
            </div>
            <div class="form-row">
                <label for="password">Пароль</label>
                <input id="password" name="password" type="password" required autocomplete="current-password">
            </div>
            <button class="btn btn-primary" type="submit">Войти</button>
            <p class="muted form-note">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
        </form>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
