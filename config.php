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

        CREATE TABLE IF NOT EXISTS custom_section_images (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            section_id INTEGER NOT NULL,
            image      TEXT NOT NULL DEFAULT '',
            sort_order INTEGER NOT NULL DEFAULT 0
        );
    ");
    // Column migrations for existing databases
    try { $pdo->exec("ALTER TABLE pages ADD COLUMN css_content TEXT NOT NULL DEFAULT ''"); } catch (\Exception $e) {}
    try { $pdo->exec("ALTER TABLE custom_sections ADD COLUMN link_url TEXT NOT NULL DEFAULT ''"); } catch (\Exception $e) {}
    try { $pdo->exec("ALTER TABLE custom_sections ADD COLUMN link_text TEXT NOT NULL DEFAULT ''"); } catch (\Exception $e) {}
    try { $pdo->exec("ALTER TABLE custom_sections ADD COLUMN image_size TEXT NOT NULL DEFAULT 'medium'"); } catch (\Exception $e) {}

    // Data migration: the team.php roster was static Nicepage markup that never matched the
    // team_members table, so early installs seeded generic placeholder rows (bio '', 01.png..06.png)
    // that were never shown anywhere. Bring those untouched placeholder rows in line with the
    // content that has always been live on team.php. Rows an admin has already edited (bio or
    // image no longer match the placeholder) are left alone.
    try {
        $legacyBio = 'Glavi amet ritnisl libero molestie ante ut fringilla purus eros quis glavrid from dolor amet iquam lorem bibendum';
        $legacy = [
            ['Ann Brown',      'new_images/01.png', 'new_images/portrait-woman-taking-photo-with-device-world-photography-day_23-2151704486.jpg'],
            ['David Villegas', 'new_images/02.png', 'new_images/side-view-adventurous-man-bivoua.jpg'],
            ['Clayton Lane',   'new_images/03.png', 'new_images/close-up-handsome-man-smiling.jpg'],
            ['Robert Fifield', 'new_images/04.png', 'new_images/close-up-man-smiling-nature_23-2.jpg'],
            ['Dan Spinello',   'new_images/05.png', 'new_images/photographer-man-smiling-while-h.jpg'],
            ['Dwight Atkins',  'new_images/06.png', 'new_images/front-view-man-posing-outdoors_2.jpg'],
        ];
        $stmt = $pdo->prepare("UPDATE team_members SET bio = ?, image = ? WHERE name = ? AND bio = '' AND image = ?");
        foreach ($legacy as [$name, $oldImage, $newImage]) {
            $stmt->execute([$legacyBio, $newImage, $name, $oldImage]);
        }
    } catch (\Exception $e) {}

    // Data migration: the Home/About dashboard forms used to save meta descriptions under
    // *_meta_desc, but the pages have always read *_meta_title_desc — a different setting key
    // — so saved descriptions silently never appeared in <meta name="description">. Carry
    // forward anything already saved under the old key before the forms move to the new one.
    try {
        $rename = [
            'home_meta_desc' => 'home_meta_title_desc',
            'about_meta_desc' => 'about_meta_title_desc',
            // team.php's hero subtext was never wired to a setting at all (hardcoded text), and
            // the dashboard/Live Editor disagreed on the key to use once it was (team_hero_sub
            // vs team_hero_body). team_hero_body won since that's what the Live Editor writes.
            'team_hero_sub' => 'team_hero_body',
        ];
        foreach ($rename as $old => $new) {
            $oldRow = $pdo->query("SELECT value FROM settings WHERE key = '{$old}'")->fetch();
            if (!$oldRow) continue;
            // Don't clobber a value already saved under the new key (e.g. the Live Editor may
            // already have written team_hero_body directly) — only backfill if it's unset.
            $newRow = $pdo->query("SELECT value FROM settings WHERE key = '{$new}'")->fetch();
            if (!$newRow && $oldRow['value'] !== '') {
                $pdo->prepare('INSERT INTO settings (key, value) VALUES (?, ?) ON CONFLICT(key) DO UPDATE SET value = excluded.value')
                    ->execute([$new, $oldRow['value']]);
            }
            // Delete the old key either way so this migration is a true one-time backfill and
            // never re-runs to stomp on a later, deliberate edit to the new key.
            $pdo->prepare('DELETE FROM settings WHERE key = ?')->execute([$old]);
        }
    } catch (\Exception $e) {}

    // Data migration: custom_sections.image only ever held one photo. Custom sections now
    // support a gallery of images via custom_section_images. Carry each section's existing
    // single image forward as the first row of its gallery, once — guarded by "no rows for
    // this section yet" so it never re-adds a duplicate after someone manages the gallery.
    try {
        $legacyImgs = $pdo->query("SELECT id, image FROM custom_sections WHERE image != ''")->fetchAll();
        $hasImages  = $pdo->prepare('SELECT COUNT(*) FROM custom_section_images WHERE section_id = ?');
        $insertImg  = $pdo->prepare('INSERT INTO custom_section_images (section_id, image, sort_order) VALUES (?, ?, 0)');
        foreach ($legacyImgs as $row) {
            $hasImages->execute([$row['id']]);
            if ((int)$hasImages->fetchColumn() === 0) {
                $insertImg->execute([$row['id'], $row['image']]);
            }
        }
    } catch (\Exception $e) {}
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
