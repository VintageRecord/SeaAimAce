<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$page_id = isset($_GET['page_id']) && is_numeric($_GET['page_id']) ? (int)$_GET['page_id'] : null;
if (!$page_id) redirect('themed-pages.php');

$db   = get_db();
$stmt = $db->prepare('SELECT title, slug FROM pages WHERE id = ?');
$stmt->execute([$page_id]);
$page = $stmt->fetch();
if (!$page) redirect('themed-pages.php');

$url = 'preview.php?slug=' . $page['slug'];

$exists = $db->prepare('SELECT COUNT(*) FROM nav_links WHERE url = ?');
$exists->execute([$url]);
if ((int)$exists->fetchColumn() === 0) {
    $next_order = (int)$db->query('SELECT COALESCE(MAX(sort_order), -1) FROM nav_links')->fetchColumn() + 1;
    $ins = $db->prepare('INSERT INTO nav_links (label, url, sort_order) VALUES (?, ?, ?)');
    $ins->execute([$page['title'], $url, $next_order]);
}

redirect('themed-pages.php?nav_added=1');
