<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$user = require_login();
$errors = [];
$success = null;

$name = $user['name'];
$phone = (string) ($user['phone'] ?? '');
$city = (string) ($user['city'] ?? '');
$themePref = $user['theme_pref'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Сессия устарела. Обновите страницу и попробуйте снова.';
    }

    $action = (string) ($_POST['action'] ?? 'profile');

    if ($action === 'profile') {
        $name = trim((string) ($_POST['name'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $city = trim((string) ($_POST['city'] ?? ''));
        $themePref = (string) ($_POST['theme_pref'] ?? 'system');

        if ($name === '' || mb_strlen($name) < 2) {
            $errors[] = 'Имя должно содержать минимум 2 символа.';
        }
        if (!in_array($themePref, ['light', 'dark', 'system'], true)) {
            $errors[] = 'Некорректная тема.';
        }

        if (!$errors) {
            $stmt = db()->prepare('UPDATE users SET name = ?, phone = ?, city = ?, theme_pref = ? WHERE id = ?');
            $stmt->execute([
                $name,
                $phone !== '' ? $phone : null,
                $city !== '' ? $city : null,
                $themePref,
                (int) $user['id'],
            ]);
            flash('success', 'Профиль обновлён.');
            redirect('profile.php');
        }
    }

    if ($action === 'password') {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        $stmt = db()->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([(int) $user['id']]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($currentPassword, $row['password_hash'])) {
            $errors[] = 'Текущий пароль указан неверно.';
        } elseif (mb_strlen($newPassword) < 6) {
            $errors[] = 'Новый пароль должен быть не короче 6 символов.';
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = 'Новые пароли не совпадают.';
        } else {
            $update = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $update->execute([password_hash($newPassword, PASSWORD_DEFAULT), (int) $user['id']]);
            flash('success', 'Пароль изменён.');
            redirect('profile.php');
        }
    }
}

$countStmt = db()->prepare('SELECT COUNT(*) FROM certificates WHERE user_id = ?');
$countStmt->execute([(int) $user['id']]);
$certCount = (int) $countStmt->fetchColumn();

$pageTitle = 'Профиль — PlayGift';
$activeNav = 'profile';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1>Профиль</h1>
        <p>Управляйте данными аккаунта, предпочтением темы и паролем.</p>
    </div>
</section>

<section class="container profile-layout">
    <aside class="profile-summary">
        <h2><?= e($user['name']) ?></h2>
        <p class="muted"><?= e($user['email']) ?></p>
        <dl style="margin-top: 1.25rem;">
            <div>
                <dt>Сертификатов</dt>
                <dd><?= $certCount ?></dd>
            </div>
            <div>
                <dt>Дата регистрации</dt>
                <dd><?= e(date('d.m.Y', strtotime((string) $user['created_at']))) ?></dd>
            </div>
            <div>
                <dt>Тема по умолчанию</dt>
                <dd><?= e(['light' => 'Светлая', 'dark' => 'Тёмная', 'system' => 'Как в системе'][$user['theme_pref']] ?? $user['theme_pref']) ?></dd>
            </div>
        </dl>
        <div class="cta-row" style="margin-top: 1.5rem;">
            <a class="btn btn-primary" href="certificates.php">Мои сертификаты</a>
            <a class="btn btn-secondary" href="logout.php">Выйти</a>
        </div>
    </aside>

    <div class="stack-sm">
        <?php if ($errors): ?>
            <div class="flash flash-error">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="panel">
            <h2>Личные данные</h2>
            <form class="form-grid" method="post" action="profile.php">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="profile">
                <div class="form-row">
                    <label for="name">Имя</label>
                    <input id="name" name="name" type="text" required value="<?= e($name) ?>">
                </div>
                <div class="form-row two">
                    <div class="form-row">
                        <label for="phone">Телефон</label>
                        <input id="phone" name="phone" type="tel" value="<?= e($phone) ?>">
                    </div>
                    <div class="form-row">
                        <label for="city">Город</label>
                        <input id="city" name="city" type="text" value="<?= e($city) ?>">
                    </div>
                </div>
                <div class="form-row">
                    <label for="theme_pref">Предпочтительная тема</label>
                    <select id="theme_pref" name="theme_pref">
                        <option value="system" <?= $themePref === 'system' ? 'selected' : '' ?>>Как в системе</option>
                        <option value="light" <?= $themePref === 'light' ? 'selected' : '' ?>>Светлая</option>
                        <option value="dark" <?= $themePref === 'dark' ? 'selected' : '' ?>>Тёмная</option>
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Сохранить</button>
            </form>
        </div>

        <div class="panel">
            <h2>Смена пароля</h2>
            <form class="form-grid" method="post" action="profile.php">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="password">
                <div class="form-row">
                    <label for="current_password">Текущий пароль</label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password">
                </div>
                <div class="form-row two">
                    <div class="form-row">
                        <label for="new_password">Новый пароль</label>
                        <input id="new_password" name="new_password" type="password" required autocomplete="new-password">
                    </div>
                    <div class="form-row">
                        <label for="confirm_password">Повтор нового пароля</label>
                        <input id="confirm_password" name="confirm_password" type="password" required autocomplete="new-password">
                    </div>
                </div>
                <button class="btn btn-secondary" type="submit">Обновить пароль</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
