<?php
/**
 * POST handler for custom sections. Include at the TOP of page editors,
 * before _layout.php, with $_sections_page already set.
 * Handles save/delete/toggle then redirects (PRG pattern).
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['_sec_action'])) return;

$_sec_page = $_sections_page ?? 'home';
$db = get_db();
$action = $_POST['_sec_action'];

if ($action === 'save') {
    $id      = (int)($_POST['_sec_id'] ?? 0);
    $heading = trim($_POST['sec_heading']    ?? '');
    $body    = trim($_POST['sec_body']       ?? '');
    $image   = trim($_POST['sec_image']      ?? '');
    $youtube = trim($_POST['sec_youtube']    ?? '');
    $bg      = trim($_POST['sec_bg_color']   ?? '#f4f6f8');
    $fg      = trim($_POST['sec_text_color'] ?? '#333333');
    $enabled = isset($_POST['sec_enabled'])   ? 1 : 0;
    $sort    = (int)($_POST['sec_sort']      ?? 0);

    // Handle image upload
    if (!empty($_FILES['sec_image_upload']) && $_FILES['sec_image_upload']['error'] === UPLOAD_ERR_OK) {
        $f   = $_FILES['sec_image_upload'];
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])) {
            $fname = uniqid('sec_', true) . '.' . $ext;
            $dest  = dirname(__DIR__) . '/uploads/' . $fname;
            if (move_uploaded_file($f['tmp_name'], $dest)) {
                $db->prepare('INSERT OR IGNORE INTO media (filename,mime_type,file_size) VALUES (?,?,?)')
                   ->execute([$fname, mime_content_type($dest), $f['size']]);
                $image = $fname;
            }
        }
    }

    if ($id > 0) {
        $db->prepare('UPDATE custom_sections SET heading=?,body=?,image=?,youtube_url=?,bg_color=?,text_color=?,sort_order=?,enabled=? WHERE id=? AND page=?')
           ->execute([$heading, $body, $image, $youtube, $bg, $fg, $sort, $enabled, $id, $_sec_page]);
        $redirect_id = $id;
    } else {
        $max = $db->prepare('SELECT COALESCE(MAX(sort_order),0)+10 FROM custom_sections WHERE page=?');
        $max->execute([$_sec_page]);
        $next_sort = (int)$max->fetchColumn();
        $db->prepare('INSERT INTO custom_sections (page,heading,body,image,youtube_url,bg_color,text_color,sort_order,enabled) VALUES (?,?,?,?,?,?,?,?,1)')
           ->execute([$_sec_page, $heading, $body, $image, $youtube, $bg, $fg, $next_sort]);
        $redirect_id = (int)$db->lastInsertId();
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sec_flash=saved&sec_edit=' . $redirect_id . '#sec-editor');
    exit;

} elseif ($action === 'delete') {
    $id = (int)($_POST['_sec_id'] ?? 0);
    if ($id) $db->prepare('DELETE FROM custom_sections WHERE id=? AND page=?')->execute([$id, $_sec_page]);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sec_flash=deleted');
    exit;

} elseif ($action === 'toggle') {
    $id = (int)($_POST['_sec_id'] ?? 0);
    if ($id) $db->prepare('UPDATE custom_sections SET enabled=1-enabled WHERE id=? AND page=?')->execute([$id, $_sec_page]);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sec_flash=toggled');
    exit;
}
