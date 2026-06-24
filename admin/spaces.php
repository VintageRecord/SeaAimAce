<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids    = $_POST['id']          ?? [];
    $titles = $_POST['title']       ?? [];
    $descs  = $_POST['description'] ?? [];
    $tag1s  = $_POST['tag1']        ?? [];
    $tag2s  = $_POST['tag2']        ?? [];
    $tag3s  = $_POST['tag3']        ?? [];

    $db->exec('DELETE FROM spaces');
    $stmt = $db->prepare('INSERT INTO spaces (id,title,description,tag1,tag2,tag3,sort_order) VALUES (?,?,?,?,?,?,?)');
    foreach ($titles as $i => $title) {
        if (trim($title) === '') continue;
        $id = !empty($ids[$i]) && is_numeric($ids[$i]) ? (int)$ids[$i] : null;
        $stmt->execute([$id, $title, $descs[$i] ?? '', $tag1s[$i] ?? '', $tag2s[$i] ?? '', $tag3s[$i] ?? '', $i]);
    }

    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}

$spaces = $db->query('SELECT * FROM spaces ORDER BY sort_order')->fetchAll();
$page_title = 'Spaces';
$active_nav = 'spaces';
include '_layout.php';
?>

<form method="POST" data-ajax data-live>
<div id="spaces-list">
<?php foreach ($spaces as $i => $s): ?>
<div class="item-block">
    <div class="item-block-header">
        <span class="item-block-title"><?= h($s['title']) ?></span>
        <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
    </div>
    <input type="hidden" name="id[]" value="<?= $s['id'] ?>">
    <div class="form-grid">
        <div class="form-group"><label>Space Name</label><input type="text" name="title[]" value="<?= h($s['title']) ?>" required></div>
        <div class="form-group"><label>Short Description</label><input type="text" name="description[]" value="<?= h($s['description']) ?>"></div>
        <div class="form-group"><label>Tag 1</label><input type="text" name="tag1[]" value="<?= h($s['tag1']) ?>" placeholder="e.g. Flexible"></div>
        <div class="form-group"><label>Tag 2</label><input type="text" name="tag2[]" value="<?= h($s['tag2']) ?>" placeholder="e.g. Community"></div>
        <div class="form-group"><label>Tag 3 (price)</label><input type="text" name="tag3[]" value="<?= h($s['tag3']) ?>" placeholder="e.g. $160/mo"></div>
    </div>
</div>
<?php endforeach; ?>
</div>

<button type="button" class="add-item-btn" onclick="addSpace()">+ Add Space</button>

<div class="save-bar">
    <button type="submit" class="btn btn-primary">Save</button>
    <span class="save-status"></span>
</div>
</form>

<template id="space-tpl">
<div class="item-block">
    <div class="item-block-header">
        <span class="item-block-title">New Space</span>
        <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
    </div>
    <input type="hidden" name="id[]" value="">
    <div class="form-grid">
        <div class="form-group"><label>Space Name</label><input type="text" name="title[]" required></div>
        <div class="form-group"><label>Short Description</label><input type="text" name="description[]"></div>
        <div class="form-group"><label>Tag 1</label><input type="text" name="tag1[]"></div>
        <div class="form-group"><label>Tag 2</label><input type="text" name="tag2[]"></div>
        <div class="form-group"><label>Tag 3 (price)</label><input type="text" name="tag3[]"></div>
    </div>
</div>
</template>
<script>
function addSpace() {
    document.getElementById('spaces-list').appendChild(
        document.getElementById('space-tpl').content.cloneNode(true)
    );
}
</script>

<?php include '_layout_end.php'; ?>
