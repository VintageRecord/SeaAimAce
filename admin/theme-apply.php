<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
require __DIR__ . '/theme-bundles.php';

function respond(array $data, int $code = 200): never {
    http_response_code($code);
    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    redirect('themed-pages.php' . (!empty($data['bundle']) ? '?applied=' . urlencode($data['bundle']) : ''));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(['success' => false, 'error' => 'POST required'], 405);
}

$bundle_id = is_string($_POST['bundle'] ?? null) ? trim($_POST['bundle']) : '';
if (!isset($THEME_BUNDLES[$bundle_id])) {
    respond(['success' => false, 'error' => 'Unknown theme bundle'], 400);
}

$bundle = $THEME_BUNDLES[$bundle_id];
$db     = get_db();

// Unique-ify a slug against existing rows (including ones we just inserted this run).
function unique_slug(PDO $db, string $base, array $taken): string {
    $slug = $base;
    $i    = 2;
    $stmt = $db->prepare('SELECT COUNT(*) FROM pages WHERE slug = ?');
    while (true) {
        $stmt->execute([$slug]);
        if ((int)$stmt->fetchColumn() === 0 && !in_array($slug, $taken, true)) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}

$insert = $db->prepare(
    'INSERT INTO pages (title, slug, meta_title, meta_description, status, html_content, css_content, editor_json, theme_group)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
);

$created = [];
$taken   = [];
$db->beginTransaction();
try {
    foreach ($bundle['pages'] as $pageDef) {
        $slug = unique_slug($db, $pageDef['slug'], $taken);
        $taken[] = $slug;
        $insert->execute([
            $pageDef['title'],
            $slug,
            $pageDef['title'],
            '',
            'draft',
            $pageDef['html'],
            $pageDef['css'] ?? '',
            '{}',
            $bundle_id,
        ]);
        $created[] = ['id' => $db->lastInsertId(), 'slug' => $slug, 'title' => $pageDef['title']];
    }
    $db->commit();
} catch (\Exception $e) {
    $db->rollBack();
    respond(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
}

respond(['success' => true, 'bundle' => $bundle_id, 'created' => $created]);
