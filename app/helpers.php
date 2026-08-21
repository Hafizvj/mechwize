<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((string) ($_SERVER['SERVER_PORT'] ?? '') === '443');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function consume_flash(): ?array
{
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function request_method(): string
{
    return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
}

function is_post(): bool
{
    return request_method() === 'POST';
}

function base_path(): string
{
    static $base = null;

    if ($base !== null) {
        return $base;
    }

    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');

    if (str_ends_with($dir, '/admin')) {
        $dir = substr($dir, 0, -6);
    }

    $base = $dir === '' || $dir === '/' ? '' : $dir;

    return $base;
}

function url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');
    if ($path === '/') {
        return base_path() === '' ? '/' : base_path() . '/';
    }

    return base_path() . $path;
}

function asset(string $path): string
{
    return url(ltrim($path, '/'));
}

function absolute_url(string $path = ''): string
{
    $settings = site_settings();
    $siteUrl = rtrim((string) ($settings['site_url'] ?? ''), '/');

    if ($siteUrl === '') {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ((string) ($_SERVER['SERVER_PORT'] ?? '') === '443');
        $host = (string) ($_SERVER['HTTP_HOST'] ?? 'mechwize.com');
        $siteUrl = ($https ? 'https://' : 'http://') . $host;
    }

    if ($path === '' || $path === '/') {
        return $siteUrl . '/';
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    return $siteUrl . '/' . ltrim($path, '/');
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'item';
}

function current_path(): string
{
    $uri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';
    $base = base_path();

    if ($base !== '' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base)) ?: '/';
    }

    return $path === '' ? '/' : $path;
}

function active_nav(string $needle): string
{
    $path = current_path();

    if ($needle === '/' && ($path === '/' || $path === '/index.php')) {
        return 'is-active';
    }

    return str_starts_with($path, $needle) ? 'is-active' : '';
}

function truncate(string $text, int $limit = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');
    if (strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit - 1)) . '…';
}

function nl2p(string $text): string
{
    $parts = preg_split("/\n{2,}/", trim($text)) ?: [];
    $html = '';

    foreach ($parts as $part) {
        $html .= '<p>' . nl2br(e(trim($part))) . '</p>';
    }

    return $html;
}

function format_phone_href(string $phone): string
{
    return 'tel:' . preg_replace('/[^0-9+]/', '', $phone);
}

function whatsapp_href(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone) ?? '';
    return 'https://wa.me/' . $digits;
}

function render_flash(?array $flash): string
{
    if (!$flash) {
        return '';
    }

    $type = ($flash['type'] ?? 'info') === 'error' ? 'error' : 'success';

    return '<div class="flash ' . e($type) . '" role="status">' . e((string) ($flash['message'] ?? '')) . '</div>';
}
