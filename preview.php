<?php
require_once __DIR__ . '/config.php';

$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
    http_response_code(400);
    exit('Missing slug');
}

$slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));

$db   = get_db();
$stmt = $db->prepare('SELECT * FROM pages WHERE slug = ?');
$stmt->execute([$slug]);
$page = $stmt->fetch();

if (!$page) {
    http_response_code(404);
    exit('Page not found: ' . h($slug));
}

$logged_in = is_admin_logged_in();
$title = $page['meta_title'] ?: $page['title'];
$desc  = $page['meta_description'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($title) ?></title>
<?php if ($desc): ?><meta name="description" content="<?= h($desc) ?>"><?php endif; ?>
<link rel="stylesheet" href="tooplate-forge-style.css">
<?php if (!empty($page['css_content'])): ?>
<style><?= $page['css_content'] ?></style>
<?php endif; ?>
<style>body{margin:0;padding:0}</style>
</head>
<body>
<?= $page['html_content'] ?>

<?php if ($logged_in): ?>
<div id="cms-admin-bar" style="
    position:fixed;bottom:0;left:0;right:0;
    background:#161616;border-top:1px solid #2a2a2a;
    padding:10px 20px;
    display:flex;align-items:center;gap:12px;
    font-family:system-ui,sans-serif;font-size:13px;
    z-index:99999;
">
    <span style="color:#888;flex:1">Previewing: <strong style="color:#fff"><?= h($page['title']) ?></strong> <span style="color:#E63946;font-size:11px;text-transform:uppercase;letter-spacing:.05em;margin-left:6px"><?= h($page['status']) ?></span></span>
    <a href="admin/pages-edit.php?id=<?= $page['id'] ?>" style="padding:6px 14px;background:#E63946;color:#fff;border-radius:5px;text-decoration:none;font-weight:600">Edit Page</a>
    <a href="admin/index.php" style="padding:6px 14px;background:#1e1e1e;border:1px solid #2a2a2a;color:#e0e0e0;border-radius:5px;text-decoration:none">Dashboard</a>
</div>
<div style="height:48px"></div>
<?php endif; ?>
<script src="jquery.js" defer></script>
<script src="nicepage.js" defer></script>
</body>

</html>
