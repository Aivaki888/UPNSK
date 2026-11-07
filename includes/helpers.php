<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function take_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function generate_certificate_code(): string
{
    return strtoupper(substr(bin2hex(random_bytes(6)), 0, 12));
}

function format_money(float|string $amount): string
{
    return number_format((float) $amount, 0, ',', ' ') . ' ₽';
}

function occasion_label(string $key): string
{
    $map = [
        'birthday' => 'День рождения',
        'wedding' => 'Свадьба',
        'newyear' => 'Новый год',
        'corporate' => 'Корпоратив',
        'thankyou' => 'Благодарность',
        'justbecause' => 'Просто так',
    ];

    return $map[$key] ?? $key;
}

function accent_label(string $key): string
{
    $map = [
        'forest' => 'Лесной',
        'champagne' => 'Шампань',
        'ink' => 'Чернила',
        'berry' => 'Ягодный',
        'sky' => 'Небесный',
    ];

    return $map[$key] ?? $key;
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    static $cached = false;
    static $user = null;

    if ($cached) {
        return $user;
    }

    $stmt = db()->prepare('SELECT id, name, email, phone, city, theme_pref, created_at FROM users WHERE id = ?');
    $stmt->execute([(int) $_SESSION['user_id']]);
    $user = $stmt->fetch() ?: null;
    $cached = true;

    if (!$user) {
        unset($_SESSION['user_id']);
    }

    return $user;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        flash('error', 'Войдите в аккаунт, чтобы продолжить.');
        redirect('login.php');
    }

    return $user;
}

function amount_options(): array
{
    return [1000, 2000, 3000, 5000, 7000, 10000];
}
