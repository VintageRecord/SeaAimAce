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
        _ensure_schema($pdo);
    }
    return $pdo;
}

function _ensure_schema(PDO $pdo): void {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            key   TEXT PRIMARY KEY,
            value TEXT NOT NULL DEFAULT ''
        );

        CREATE TABLE IF NOT EXISTS features (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            icon        TEXT NOT NULL DEFAULT '',
            title       TEXT NOT NULL DEFAULT '',
            description TEXT NOT NULL DEFAULT '',
            stat1_num   TEXT NOT NULL DEFAULT '',
            stat1_label TEXT NOT NULL DEFAULT '',
            stat2_num   TEXT NOT NULL DEFAULT '',
            stat2_label TEXT NOT NULL DEFAULT '',
            stat3_num   TEXT NOT NULL DEFAULT '',
            stat3_label TEXT NOT NULL DEFAULT '',
            sort_order  INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS spaces (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            title       TEXT NOT NULL DEFAULT '',
            description TEXT NOT NULL DEFAULT '',
            tag1        TEXT NOT NULL DEFAULT '',
            tag2        TEXT NOT NULL DEFAULT '',
            tag3        TEXT NOT NULL DEFAULT '',
            sort_order  INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS pricing_plans (
            id            INTEGER PRIMARY KEY AUTOINCREMENT,
            name          TEXT NOT NULL DEFAULT '',
            price_monthly INTEGER NOT NULL DEFAULT 0,
            price_yearly  INTEGER NOT NULL DEFAULT 0,
            features      TEXT NOT NULL DEFAULT '[]',
            is_featured   INTEGER NOT NULL DEFAULT 0,
            button_text   TEXT NOT NULL DEFAULT 'Get Started',
            sort_order    INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS amenities (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            icon        TEXT NOT NULL DEFAULT '',
            title       TEXT NOT NULL DEFAULT '',
            description TEXT NOT NULL DEFAULT '',
            sort_order  INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS contact_submissions (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            name       TEXT NOT NULL DEFAULT '',
            email      TEXT NOT NULL DEFAULT '',
            phone      TEXT NOT NULL DEFAULT '',
            company    TEXT NOT NULL DEFAULT '',
            interest   TEXT NOT NULL DEFAULT '',
            message    TEXT NOT NULL DEFAULT '',
            created_at TEXT NOT NULL DEFAULT (datetime('now'))
        );

        CREATE TABLE IF NOT EXISTS pages (
            id               INTEGER PRIMARY KEY AUTOINCREMENT,
            title            TEXT NOT NULL DEFAULT '',
            slug             TEXT NOT NULL UNIQUE,
            meta_title       TEXT NOT NULL DEFAULT '',
            meta_description TEXT NOT NULL DEFAULT '',
            status           TEXT NOT NULL DEFAULT 'draft',
            html_content     TEXT NOT NULL DEFAULT '',
            css_content      TEXT NOT NULL DEFAULT '',
            editor_json      TEXT NOT NULL DEFAULT '{}',
            updated_at       TEXT NOT NULL DEFAULT (datetime('now'))
        );

        CREATE TABLE IF NOT EXISTS media (
            id            INTEGER PRIMARY KEY AUTOINCREMENT,
            filename      TEXT NOT NULL,
            original_name TEXT NOT NULL DEFAULT '',
            mime_type     TEXT NOT NULL DEFAULT '',
            file_size     INTEGER NOT NULL DEFAULT 0,
            uploaded_at   TEXT NOT NULL DEFAULT (datetime('now'))
        );

        CREATE TABLE IF NOT EXISTS nav_links (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            label      TEXT NOT NULL DEFAULT '',
            url        TEXT NOT NULL DEFAULT '',
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS footer_columns (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            heading    TEXT NOT NULL DEFAULT '',
            links      TEXT NOT NULL DEFAULT '[]',
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS team_members (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            name       TEXT NOT NULL DEFAULT '',
            role       TEXT NOT NULL DEFAULT '',
            bio        TEXT NOT NULL DEFAULT '',
            image      TEXT NOT NULL DEFAULT '',
            fb_url     TEXT NOT NULL DEFAULT '',
            tw_url     TEXT NOT NULL DEFAULT '',
            ig_url     TEXT NOT NULL DEFAULT '',
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS faq_items (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            question   TEXT NOT NULL DEFAULT '',
            answer     TEXT NOT NULL DEFAULT '',
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS gallery_items (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            image      TEXT NOT NULL DEFAULT '',
            caption    TEXT NOT NULL DEFAULT '',
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS home_services (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            icon_img   TEXT NOT NULL DEFAULT '',
            title      TEXT NOT NULL DEFAULT '',
            sort_order INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS home_activities (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            image       TEXT NOT NULL DEFAULT '',
            title       TEXT NOT NULL DEFAULT '',
            description TEXT NOT NULL DEFAULT '',
            sort_order  INTEGER NOT NULL DEFAULT 0
        );

        CREATE TABLE IF NOT EXISTS custom_sections (
            id           INTEGER PRIMARY KEY AUTOINCREMENT,
            page         TEXT NOT NULL DEFAULT 'home',
            heading      TEXT NOT NULL DEFAULT '',
            body         TEXT NOT NULL DEFAULT '',
            image        TEXT NOT NULL DEFAULT '',
            youtube_url  TEXT NOT NULL DEFAULT '',
            bg_color     TEXT NOT NULL DEFAULT '#ffffff',
            text_color   TEXT NOT NULL DEFAULT '#333333',
            sort_order   INTEGER NOT NULL DEFAULT 0,
            enabled      INTEGER NOT NULL DEFAULT 1,
            created_at   TEXT NOT NULL DEFAULT (datetime('now'))
        );
    ");
    // Column migrations for existing databases
    try { $pdo->exec("ALTER TABLE pages ADD COLUMN css_content TEXT NOT NULL DEFAULT ''"); } catch (\Exception $e) {}
}

function setting(string $key, string $default = ''): string {
    static $cache = [];
    if (!isset($cache[$key])) {
        $stmt = get_db()->prepare('SELECT value FROM settings WHERE key = ?');
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        // Decode any accumulated HTML entities from repeated live-editor saves
        $val = $row ? $row['value'] : $default;
        if ($row && strpos($val, '&amp;') !== false) {
            $val = html_entity_decode($val, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        $cache[$key] = $val;
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

/**
 * Output a setting value as safe HTML (for content fields edited via live editor).
 * The live-edit-save.php clean() function already strips scripts/iframes, so this
 * just strips any remaining dangerous event handlers and returns the raw HTML.
 */
function sh(string $val): string {
    // Strip on* event handlers (e.g. onclick="...")
    $val = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]*)/i', '', $val);
    // Strip javascript: in href/src
    $val = preg_replace('/\b(href|src)\s*=\s*["\']?\s*javascript:/i', '$1="#" data-blocked=', $val);
    return $val;
}

function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}
