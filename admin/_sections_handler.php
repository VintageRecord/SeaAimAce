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
    $id        = (int)($_POST['_sec_id'] ?? 0);
    $heading   = trim($_POST['sec_heading']    ?? '');
    $body      = trim($_POST['sec_body']       ?? '');
    $bg        = trim($_POST['sec_bg_color']   ?? '#f4f6f8');
    $fg        = trim($_POST['sec_text_color'] ?? '#333333');
    $enabled   = isset($_POST['sec_enabled'])   ? 1 : 0;
    $sort      = (int)($_POST['sec_sort']      ?? 0);
    $img_size  = $_POST['sec_image_size'] ?? 'medium';
    if (!in_array($img_size, ['small','medium','large','full'])) $img_size = 'medium';
    $text_position = $_POST['sec_text_position'] ?? 'below';
    if (!in_array($text_position, ['above','below'])) $text_position = 'below';
    $media_order = $_POST['sec_media_order'] ?? 'images_first';
    if (!in_array($media_order, ['images_first','video_first'])) $media_order = 'images_first';

    $links = [];
    foreach (($_POST['sec_link_text'] ?? []) as $i => $lt) {
        $lt = trim($lt);
        $lu = trim($_POST['sec_link_url'][$i] ?? '');
        if ($lu !== '') $links[] = ['text' => $lt, 'url' => $lu];
    }
    $links_json = json_encode($links);

    $youtube_urls = [];
    foreach (($_POST['sec_youtube'] ?? []) as $yu) {
        $yu = trim($yu);
        if ($yu !== '') $youtube_urls[] = $yu;
    }
    $youtube_json = json_encode($youtube_urls);

    if ($id > 0) {
        $db->prepare('UPDATE custom_sections SET heading=?,body=?,links=?,youtube_urls=?,bg_color=?,text_color=?,sort_order=?,enabled=?,image_size=?,text_position=?,media_order=? WHERE id=? AND page=?')
           ->execute([$heading, $body, $links_json, $youtube_json, $bg, $fg, $sort, $enabled, $img_size, $text_position, $media_order, $id, $_sec_page]);
        $redirect_id = $id;
    } else {
        $max = $db->prepare('SELECT COALESCE(MAX(sort_order),0)+10 FROM custom_sections WHERE page=?');
        $max->execute([$_sec_page]);
        $next_sort = (int)$max->fetchColumn();
        $db->prepare('INSERT INTO custom_sections (page,heading,body,links,youtube_urls,bg_color,text_color,sort_order,enabled,image_size,text_position,media_order) VALUES (?,?,?,?,?,?,?,?,1,?,?,?)')
           ->execute([$_sec_page, $heading, $body, $links_json, $youtube_json, $bg, $fg, $next_sort, $img_size, $text_position, $media_order]);
        $redirect_id = (int)$db->lastInsertId();

        // Attach any images uploaded or picked from the library while creating this section,
        // now that it finally has an id to attach them to.
        $imgSort = 0;
        $insertImg = $db->prepare('INSERT INTO custom_section_images (section_id,image,sort_order) VALUES (?,?,?)');

        if (!empty($_FILES['sec_new_images']['name'])) {
            foreach ($_FILES['sec_new_images']['name'] as $i => $origName) {
                if ($_FILES['sec_new_images']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])) continue;
                $fname = uniqid('sec_', true) . '.' . $ext;
                $dest  = dirname(__DIR__) . '/uploads/' . $fname;
                if (move_uploaded_file($_FILES['sec_new_images']['tmp_name'][$i], $dest)) {
                    $db->prepare('INSERT OR IGNORE INTO media (filename,mime_type,file_size) VALUES (?,?,?)')
                       ->execute([$fname, mime_content_type($dest), $_FILES['sec_new_images']['size'][$i]]);
                    $insertImg->execute([$redirect_id, $fname, $imgSort]);
                    $imgSort += 10;
                }
            }
        }

        foreach (($_POST['sec_staged_images'] ?? []) as $staged) {
            if (preg_match('#^(new_images/|uploads/)?[A-Za-z0-9_.\-]+$#', $staged)) {
                $insertImg->execute([$redirect_id, $staged, $imgSort]);
                $imgSort += 10;
            }
        }
    }

    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sec_flash=saved&sec_edit=' . $redirect_id . '#sec-editor');
    exit;

} elseif ($action === 'delete') {
    $id = (int)($_POST['_sec_id'] ?? 0);
    if ($id) {
        $db->prepare('DELETE FROM custom_sections WHERE id=? AND page=?')->execute([$id, $_sec_page]);
        $db->prepare('DELETE FROM custom_section_images WHERE section_id=?')->execute([$id]);
    }
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sec_flash=deleted');
    exit;

} elseif ($action === 'toggle') {
    $id = (int)($_POST['_sec_id'] ?? 0);
    if ($id) $db->prepare('UPDATE custom_sections SET enabled=1-enabled WHERE id=? AND page=?')->execute([$id, $_sec_page]);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sec_flash=toggled');
    exit;

} elseif ($action === 'add_image') {
    $section_id = (int)($_POST['_sec_id'] ?? 0);
    $image      = trim($_POST['sec_new_image'] ?? '');

    // Confirm this section really belongs to this page before attaching an image to it.
    $owns = $db->prepare('SELECT COUNT(*) FROM custom_sections WHERE id=? AND page=?');
    $owns->execute([$section_id, $_sec_page]);
    if ($section_id && $owns->fetchColumn()) {
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
        if ($image !== '') {
            $max = $db->prepare('SELECT COALESCE(MAX(sort_order),0)+10 FROM custom_section_images WHERE section_id=?');
            $max->execute([$section_id]);
            $db->prepare('INSERT INTO custom_section_images (section_id,image,sort_order) VALUES (?,?,?)')
               ->execute([$section_id, $image, $max->fetchColumn()]);
        }
    }
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sec_edit=' . $section_id . '#sec-editor');
    exit;

} elseif ($action === 'delete_image') {
    $imgId      = (int)($_POST['_sec_img_id'] ?? 0);
    $section_id = (int)($_POST['_sec_id'] ?? 0);
    if ($imgId) $db->prepare('DELETE FROM custom_section_images WHERE id=?')->execute([$imgId]);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sec_edit=' . $section_id . '#sec-editor');
    exit;
}
