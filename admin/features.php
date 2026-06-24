<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();
$saved = false;
$error = '';

// Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $db->prepare('DELETE FROM features WHERE id = ?')->execute([(int)$_GET['delete']]);
    redirect('features.php?saved=1');
}

// Save (add or update all)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids    = $_POST['id']    ?? [];
    $icons  = $_POST['icon']  ?? [];
    $titles = $_POST['title'] ?? [];
    $descs  = $_POST['description'] ?? [];
    $s1n    = $_POST['stat1_num']   ?? [];
    $s1l    = $_POST['stat1_label'] ?? [];
    $s2n    = $_POST['stat2_num']   ?? [];
    $s2l    = $_POST['stat2_label'] ?? [];
    $s3n    = $_POST['stat3_num']   ?? [];
    $s3l    = $_POST['stat3_label'] ?? [];

    $db->exec('DELETE FROM features');
    $stmt = $db->prepare('INSERT INTO features (id,icon,title,description,stat1_num,stat1_label,stat2_num,stat2_label,stat3_num,stat3_label,sort_order) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
    foreach ($titles as $i => $title) {
        if (trim($title) === '') continue;
        $id = !empty($ids[$i]) && is_numeric($ids[$i]) ? (int)$ids[$i] : null;
        $stmt->execute([$id, $icons[$i] ?? '', $title, $descs[$i] ?? '', $s1n[$i] ?? '', $s1l[$i] ?? '', $s2n[$i] ?? '', $s2l[$i] ?? '', $s3n[$i] ?? '', $s3l[$i] ?? '', $i]);
    }
    $saved = true;
}

if (isset($_GET['saved'])) $saved = true;
$features = $db->query('SELECT * FROM features ORDER BY sort_order')->fetchAll();

$page_title = 'Features';
$active_nav = 'features';
include '_layout.php';
?>

<?php if ($saved): ?>
<div class="alert alert-success">✅ Features saved successfully.</div>
<?php endif; ?>

<form method="POST" id="features-form">
<div class="drag-hint">Tip: order is preserved by position on this page.</div>
<div id="features-list">
<?php foreach ($features as $i => $f): ?>
<div class="item-block">
    <div class="item-block-header">
        <span class="item-block-title">Feature #<?= $i + 1 ?>: <?= h($f['title']) ?></span>
        <button type="button" class="item-remove" onclick="removeBlock(this)" title="Remove">✕</button>
    </div>
    <input type="hidden" name="id[]" value="<?= $f['id'] ?>">
    <div class="form-grid">
        <div class="form-group">
            <label>Icon / Badge Text</label>
            <input type="text" name="icon[]" value="<?= h($f['icon']) ?>" placeholder="e.g. 24/7 or 1GB">
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title[]" value="<?= h($f['title']) ?>" required>
        </div>
        <div class="form-group full-width">
            <label>Description</label>
            <input type="text" name="description[]" value="<?= h($f['description']) ?>">
        </div>
    </div>
    <p class="form-section-title">Stats (3 columns)</p>
    <div class="form-grid" style="grid-template-columns:repeat(3,1fr)">
        <div class="form-group">
            <label>Stat 1 Number</label>
            <input type="text" name="stat1_num[]" value="<?= h($f['stat1_num']) ?>">
        </div>
        <div class="form-group">
            <label>Stat 2 Number</label>
            <input type="text" name="stat2_num[]" value="<?= h($f['stat2_num']) ?>">
        </div>
        <div class="form-group">
            <label>Stat 3 Number</label>
            <input type="text" name="stat3_num[]" value="<?= h($f['stat3_num']) ?>">
        </div>
        <div class="form-group">
            <label>Stat 1 Label</label>
            <input type="text" name="stat1_label[]" value="<?= h($f['stat1_label']) ?>">
        </div>
        <div class="form-group">
            <label>Stat 2 Label</label>
            <input type="text" name="stat2_label[]" value="<?= h($f['stat2_label']) ?>">
        </div>
        <div class="form-group">
            <label>Stat 3 Label</label>
            <input type="text" name="stat3_label[]" value="<?= h($f['stat3_label']) ?>">
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<button type="button" class="add-item-btn" onclick="addFeature()">+ Add Feature Card</button>

<div style="margin-top:16px">
    <button type="submit" class="btn btn-primary">💾 Save All Features</button>
</div>
</form>

<template id="feature-tpl">
<div class="item-block">
    <div class="item-block-header">
        <span class="item-block-title">New Feature</span>
        <button type="button" class="item-remove" onclick="removeBlock(this)" title="Remove">✕</button>
    </div>
    <input type="hidden" name="id[]" value="">
    <div class="form-grid">
        <div class="form-group">
            <label>Icon / Badge Text</label>
            <input type="text" name="icon[]" placeholder="e.g. 24/7 or 1GB">
        </div>
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title[]" required>
        </div>
        <div class="form-group full-width">
            <label>Description</label>
            <input type="text" name="description[]">
        </div>
    </div>
    <p class="form-section-title">Stats (3 columns)</p>
    <div class="form-grid" style="grid-template-columns:repeat(3,1fr)">
        <div class="form-group"><label>Stat 1 Number</label><input type="text" name="stat1_num[]"></div>
        <div class="form-group"><label>Stat 2 Number</label><input type="text" name="stat2_num[]"></div>
        <div class="form-group"><label>Stat 3 Number</label><input type="text" name="stat3_num[]"></div>
        <div class="form-group"><label>Stat 1 Label</label><input type="text" name="stat1_label[]"></div>
        <div class="form-group"><label>Stat 2 Label</label><input type="text" name="stat2_label[]"></div>
        <div class="form-group"><label>Stat 3 Label</label><input type="text" name="stat3_label[]"></div>
    </div>
</div>
</template>

<script>
function addFeature() {
    const tpl = document.getElementById('feature-tpl').content.cloneNode(true);
    document.getElementById('features-list').appendChild(tpl);
}
function removeBlock(btn) {
    btn.closest('.item-block').remove();
}
</script>

<?php include '_layout_end.php'; ?>
