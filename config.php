<?php
define('DB_PATH', __DIR__ . '/database/forge.db');
define('ADMIN_SESSION_KEY', 'forge_admin_logged_in');

function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dir = dirname(DB_PATH);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA journal_mode=WAL');
    }
    return $pdo;
}

function setting(string $key, string $default = ''): string {
    static $cache = [];
    if (!isset($cache[$key])) {
        $stmt = get_db()->prepare('SELECT value FROM settings WHERE key = ?');
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        $cache[$key] = $row ? $row['value'] : $default;
    }
    return $cache[$key];
}

function save_setting(string $key, string $value): void {
    $db = get_db();
    $stmt = $db->prepare('INSERT INTO settings (key, value) VALUES (?, ?) ON CONFLICT(key) DO UPDATE SET value = excluded.value');
    $stmt->execute([$key, $value]);
}

function is_admin_logged_in(): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION[ADMIN_SESSION_KEY]);
}

function require_admin_login(): void {
    if (!is_admin_logged_in()) {
        header('Location: ' . admin_url('login.php'));
        exit;
    }
}

function admin_url(string $path = ''): string {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    // if we're already inside admin/, go up
    if (basename(dirname($_SERVER['SCRIPT_NAME'])) === 'admin') {
        $base = dirname($base);
    }
    return $base . '/admin/' . ltrim($path, '/');
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}
