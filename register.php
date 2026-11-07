<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (current_user()) {
    redirect('profile.php');
}

$errors = [];
$name = '';
$email = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Сессия устарела. Обновите страницу и попробуйте снова.';
    }

    $name = trim((string) ($_POST['name'] ?? ''));
    $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) {
        $errors[] = 'Укажите имя не короче 2 символов.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введите корректный email.';
    }
    if (mb_strlen($password) < 6) {
        $errors[] = 'Пароль должен быть не короче 6 символов.';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = 'Пароли не совпадают.';
    }

    if (!$errors) {
        $exists = db()->prepare('SELECT id FROM users WHERE email = ?');
        $exists->execute([$email]);
        if ($exists->fetch()) {
            $errors[] = 'Пользователь с таким email уже зарегистрирован.';
        }
    }

    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO users (name, email, password_hash, phone) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $phone !== '' ? $phone : null,
        ]);

        $_SESSION['user_id'] = (int) db()->lastInsertId();
        flash('success', 'Добро пожаловать! Аккаунт создан.');
        redirect('profile.php');
    }
}

$pageTitle = 'Регистрация — Дари';
$activeNav = 'register';

require __DIR__ . '/includes/header.php';
?>

<section class="container auth-layout">
    <div class="auth-side">
        <h2>Создайте профиль</h2>
        <p>После регистрации можно оформлять сертификаты, сохранять данные получателей и смотреть историю заказов.</p>
    </div>
    <div class="auth-card">
        <h1>Регистрация</h1>
        <p class="muted">Заполните форму — вход и профиль появятся сразу после создания аккаунта.</p>

        <?php if ($errors): ?>
            <div class="flash flash-error" style="margin: 1rem 0;">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="form-grid" method="post" action="register.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <div class="form-row">
                <label for="name">Имя</label>
                <input id="name" name="name" type="text" required value="<?= e($name) ?>" autocomplete="name">
            </div>
            <div class="form-row">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required value="<?= e($email) ?>" autocomplete="email">
            </div>
            <div class="form-row">
                <label for="phone">Телефон</label>
                <input id="phone" name="phone" type="tel" value="<?= e($phone) ?>" autocomplete="tel" placeholder="+7 ...">
            </div>
            <div class="form-row two">
                <div class="form-row">
                    <label for="password">Пароль</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password">
                </div>
                <div class="form-row">
                    <label for="password_confirm">Повтор пароля</label>
                    <input id="password_confirm" name="password_confirm" type="password" required autocomplete="new-password">
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Зарегистрироваться</button>
        </form>
        <p class="muted" style="margin-top: 1rem;">Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
