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

$logged_in    = is_admin_logged_in();
$title        = $page['meta_title'] ?: $page['title'];
$desc         = $page['meta_description'];
$current_page = 'landing';
$_nav_base    = '';
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($title) ?></title>
<?php if ($desc): ?><meta name="description" content="<?= h($desc) ?>"><?php endif; ?>
<link rel="stylesheet" href="nicepage.css" media="screen">
<link rel="stylesheet" href="tooplate-forge-style.css">
<?php require __DIR__ . '/_bg_styles.php'; ?>
<?php if (!empty($page['css_content'])): ?>
<style><?= $page['css_content'] ?></style>
<?php endif; ?>
<style>
body{margin:0;padding:0}
/* Site nav: white background, dark text, no underlines */
.u-header{background:#fff!important;border-bottom:1px solid #eee}
.u-header .u-nav-link,.u-header a{color:#333!important;text-decoration:none!important}
.u-header .u-nav-link:hover,.u-header a:hover{color:#000!important;text-decoration:none!important}
.u-header .u-btn.u-palette-2-base,.u-header .u-border-palette-2-base{background:#c0303b!important;color:#fff!important;border-color:#c0303b!important;text-decoration:none!important}
.u-nav-link-active{border-bottom:none!important}
/* Prevent nicepage.js from making nav sticky/fixed on scroll */
.u-header.u-sticky{position:static!important;top:auto!important;box-shadow:none!important}
</style>
</head>
<body data-path-to-root="./" class="u-body u-clearfix u-xl-mode" data-lang="en">

<?php
ob_start();
require_once __DIR__ . '/_nav.php';
$_nav_html = ob_get_clean();
// Force white background on the header regardless of nav_bg_color setting
$_nav_html = preg_replace('/(<header\b[^>]*?)\s+style="[^"]*"/i', '$1', $_nav_html);
echo $_nav_html;
?>

<main>
<?php
// Strip any embedded site nav (<header id="sec-c67f"...>) that may have been
// saved inside the page content from old drag-and-drop blocks
// Strip any embedded nav/header blocks saved inside GrapesJS content
$page_body = $page['html_content'];
$page_body = preg_replace('/<header\b[^>]*>.*?<\/header>/si', '', $page_body);
$page_body = preg_replace('/<nav\b[^>]*class="[^"]*u-menu[^"]*"[^>]*>.*?<\/nav>/si', '', $page_body);
echo $page_body;
?>
</main>

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
<div style="height:52px"></div>
<?php endif; ?>

<?php
// _footer.php closes </body></html> and loads jquery.js + nicepage.js
$_foot_base = '';
require_once __DIR__ . '/_footer.php';
?>
