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
        'new_release' => 'Релиз игры',
        'tournament' => 'Турнир / победа',
        'friend_gift' => 'Подарок другу',
        'thankyou' => 'Спасибо за кооператив',
        'justbecause' => 'Просто так',
    ];

    return $map[$key] ?? $key;
}

function accent_label(string $key): string
{
    $map = [
        'ps_blue' => 'PlayStation',
        'xbox_green' => 'Xbox',
        'pc_cyan' => 'PC',
        'neon' => 'Неон',
        'carbon' => 'Карбон',
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
    return [500, 1000, 1500, 2500, 5000, 10000];
}

function project_root(): string
{
    return dirname(__DIR__);
}

function media_exists(?string $relativePath): bool
{
    if ($relativePath === null || trim($relativePath) === '') {
        return false;
    }

    $full = project_root() . '/' . ltrim($relativePath, '/');

    return is_file($full);
}

function render_media(?string $relativePath, string $alt = '', string $class = 'media-frame', string $hint = 'Место для изображения'): void
{
    if (media_exists($relativePath)) {
        echo '<img class="' . e($class) . '" src="' . e($relativePath) . '" alt="' . e($alt) . '">';
        return;
    }

    echo '<div class="' . e($class) . ' media-placeholder" role="img" aria-label="' . e($alt !== '' ? $alt : $hint) . '">';
    echo '<span>' . e($hint) . '</span>';
    if ($relativePath) {
        echo '<small>' . e($relativePath) . '</small>';
    }
    echo '</div>';
}

function ensure_extra_tables(): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    db()->exec(
        'CREATE TABLE IF NOT EXISTS favorites (
            user_id INT UNSIGNED NOT NULL,
            design_id INT UNSIGNED NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id, design_id)
        ) ENGINE=InnoDB'
    );

    db()->exec(
        'CREATE TABLE IF NOT EXISTS contact_messages (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED DEFAULT NULL,
            name VARCHAR(120) NOT NULL,
            email VARCHAR(190) NOT NULL,
            subject VARCHAR(160) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB'
    );

    $ready = true;
}

function user_favorite_ids(int $userId): array
{
    ensure_extra_tables();
    $stmt = db()->prepare('SELECT design_id FROM favorites WHERE user_id = ?');
    $stmt->execute([$userId]);
    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

function is_favorite(int $userId, int $designId): bool
{
    ensure_extra_tables();
    $stmt = db()->prepare('SELECT 1 FROM favorites WHERE user_id = ? AND design_id = ?');
    $stmt->execute([$userId, $designId]);
    return (bool) $stmt->fetchColumn();
}

function remember_recent_design(int $designId): void
{
    $recent = $_SESSION['recent_designs'] ?? [];
    $recent = array_values(array_filter($recent, static fn($id) => (int) $id !== $designId));
    array_unshift($recent, $designId);
    $_SESSION['recent_designs'] = array_slice($recent, 0, 6);
}

function status_label(string $status): string
{
    return match ($status) {
        'new' => 'Новый',
        'paid' => 'Оплачен',
        'sent' => 'Отправлен',
        'used' => 'Использован',
        default => $status,
    };
}

function platform_slug(string $category): string
{
    return match (mb_strtolower(trim($category))) {
        'playstation', 'ps', 'psn' => 'playstation',
        'xbox' => 'xbox',
        'pc', 'steam' => 'pc',
        default => 'pc',
    };
}

function platform_label(string $category): string
{
    return match (platform_slug($category)) {
        'playstation' => 'PlayStation',
        'xbox' => 'Xbox',
        default => 'Steam',
    };
}

function platform_icon_path(string $category): string
{
    $slug = platform_slug($category);
    foreach (['png', 'jpg', 'jpeg', 'webp', 'svg'] as $ext) {
        $path = 'assets/img/platforms/' . $slug . '.' . $ext;
        if (media_exists($path)) {
            return $path;
        }
    }

    return 'assets/img/platforms/' . $slug . '.svg';
}

function render_platform_icon(string $category, string $class = 'platform-icon'): void
{
    $path = platform_icon_path($category);
    $label = platform_label($category);
    if (media_exists($path)) {
        echo '<img class="' . e($class) . '" src="' . e($path) . '" alt="' . e($label) . '" width="40" height="40" loading="lazy">';
        return;
    }

    echo '<span class="' . e($class) . ' platform-icon-fallback" aria-hidden="true">' . e(mb_substr($label, 0, 1)) . '</span>';
}

function render_platform_chip(string $category, string $extraClass = ''): void
{
    $slug = platform_slug($category);
    $label = platform_label($category);
    $class = trim('chip chip-' . $slug . ' chip-platform ' . $extraClass);
    echo '<span class="' . e($class) . '">';
    render_platform_icon($category, 'platform-icon platform-icon-sm');
    echo '<span>' . e($label) . '</span>';
    echo '</span>';
}
