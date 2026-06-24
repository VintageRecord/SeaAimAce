<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    save_setting('amenities_heading', trim($_POST['amenities_heading'] ?? ''));
    save_setting('amenities_subtext', trim($_POST['amenities_subtext'] ?? ''));

    $ids   = $_POST['id']          ?? [];
    $icons = $_POST['icon']        ?? [];
    $titls = $_POST['title']       ?? [];
    $descs = $_POST['description'] ?? [];

    $db->exec('DELETE FROM amenities');
    $stmt = $db->prepare('INSERT INTO amenities (id,icon,title,description,sort_order) VALUES (?,?,?,?,?)');
    foreach ($titls as $i => $title) {
        if (trim($title) === '') continue;
        $id = !empty($ids[$i]) && is_numeric($ids[$i]) ? (int)$ids[$i] : null;
        $stmt->execute([$id, $icons[$i] ?? '', $title, $descs[$i] ?? '', $i]);
    }

    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}

$amenities = $db->query('SELECT * FROM amenities ORDER BY sort_order')->fetchAll();
$page_title = 'Amenities';
$active_nav = 'amenities';
include '_layout.php';
?>

<form method="POST" data-ajax data-live>
<div class="card">
    <div class="card-header"><h2>Section Header</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group"><label>Heading</label><input type="text" name="amenities_heading" value="<?= h(setting('amenities_heading')) ?>"></div>
            <div class="form-group"><label>Subtext</label><input type="text" name="amenities_subtext" value="<?= h(setting('amenities_subtext')) ?>"></div>
        </div>
    </div>
</div>

<div id="amenities-list">
<?php foreach ($amenities as $i => $a): ?>
<div class="item-block">
    <div class="item-block-header">
        <span class="item-block-title"><?= h($a['title']) ?></span>
        <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
    </div>
    <input type="hidden" name="id[]" value="<?= $a['id'] ?>">
    <div class="form-grid" style="grid-template-columns:90px 1fr 2fr">
        <div class="form-group"><label>Icon (emoji)</label><input type="text" name="icon[]" value="<?= h($a['icon']) ?>" placeholder="e.g. coffee icon"></div>
        <div class="form-group"><label>Title</label><input type="text" name="title[]" value="<?= h($a['title']) ?>" required></div>
        <div class="form-group"><label>Description</label><input type="text" name="description[]" value="<?= h($a['description']) ?>"></div>
    </div>
</div>
<?php endforeach; ?>
</div>

<button type="button" class="add-item-btn" onclick="addAmenity()">+ Add Amenity</button>

<div class="save-bar">
    <button type="submit" class="btn btn-primary">Save</button>
    <span class="save-status"></span>
</div>
</form>

<template id="amenity-tpl">
<div class="item-block">
    <div class="item-block-header">
        <span class="item-block-title">New Amenity</span>
        <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
    </div>
    <input type="hidden" name="id[]" value="">
    <div class="form-grid" style="grid-template-columns:90px 1fr 2fr">
        <div class="form-group"><label>Icon (emoji)</label><input type="text" name="icon[]"></div>
        <div class="form-group"><label>Title</label><input type="text" name="title[]" required></div>
        <div class="form-group"><label>Description</label><input type="text" name="description[]"></div>
    </div>
</div>
</template>
<script>
function addAmenity() {
    document.getElementById('amenities-list').appendChild(
        document.getElementById('amenity-tpl').content.cloneNode(true)
    );
}
</script>

<?php include '_layout_end.php'; ?>
