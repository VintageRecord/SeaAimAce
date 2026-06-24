<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save enterprise settings
    $enterprise_fields = [
        'pricing_heading','pricing_subtext',
        'pricing_enterprise_heading','pricing_enterprise_text1','pricing_enterprise_text2',
        'pricing_enterprise_feat1_num','pricing_enterprise_feat1_label',
        'pricing_enterprise_feat2_num','pricing_enterprise_feat2_label',
        'pricing_enterprise_feat3_num','pricing_enterprise_feat3_label',
        'pricing_enterprise_btn_text','pricing_enterprise_btn_href',
    ];
    foreach ($enterprise_fields as $f) {
        if (isset($_POST[$f])) save_setting($f, trim($_POST[$f]));
    }

    // Save plans
    $ids         = $_POST['plan_id']       ?? [];
    $names       = $_POST['plan_name']     ?? [];
    $monthlys    = $_POST['price_monthly'] ?? [];
    $yearlys     = $_POST['price_yearly']  ?? [];
    $btntexts    = $_POST['button_text']   ?? [];
    $featureds   = $_POST['is_featured']   ?? [];
    $feat_lines  = $_POST['plan_features'] ?? [];

    $db->exec('DELETE FROM pricing_plans');
    $stmt = $db->prepare('INSERT INTO pricing_plans (id,name,price_monthly,price_yearly,features,is_featured,button_text,sort_order) VALUES (?,?,?,?,?,?,?,?)');
    foreach ($names as $i => $name) {
        if (trim($name) === '') continue;
        $id = !empty($ids[$i]) && is_numeric($ids[$i]) ? (int)$ids[$i] : null;
        $lines = array_filter(array_map('trim', explode("\n", $feat_lines[$i] ?? '')));
        $feats_json = json_encode(array_values($lines));
        $featured = isset($featureds[$i]) ? 1 : 0;
        $stmt->execute([$id, $name, (int)($monthlys[$i] ?? 0), (int)($yearlys[$i] ?? 0), $feats_json, $featured, $btntexts[$i] ?? 'Get Started', $i]);
    }
    $saved = true;
}

$plans = $db->query('SELECT * FROM pricing_plans ORDER BY sort_order')->fetchAll();
$page_title = 'Pricing';
$active_nav = 'pricing';
include '_layout.php';
?>

<?php if ($saved): ?>
<div class="alert alert-success">✅ Pricing saved successfully.</div>
<?php endif; ?>

<form method="POST">

<div class="card">
    <div class="card-header"><h2>Section Header</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group">
                <label>Heading</label>
                <input type="text" name="pricing_heading" value="<?= h(setting('pricing_heading')) ?>">
            </div>
            <div class="form-group">
                <label>Subtext</label>
                <input type="text" name="pricing_subtext" value="<?= h(setting('pricing_subtext')) ?>">
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Pricing Plans</h2></div>
    <div class="card-body">
<div id="plans-list">
<?php foreach ($plans as $i => $p):
    $feat_text = implode("\n", json_decode($p['features'], true) ?: []);
?>
<div class="item-block">
    <div class="item-block-header">
        <span class="item-block-title"><?= h($p['name']) ?> — $<?= $p['price_monthly'] ?>/mo</span>
        <button type="button" class="item-remove" onclick="removeBlock(this)">✕</button>
    </div>
    <input type="hidden" name="plan_id[]" value="<?= $p['id'] ?>">
    <div class="form-grid">
        <div class="form-group">
            <label>Plan Name</label>
            <input type="text" name="plan_name[]" value="<?= h($p['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Monthly Price ($)</label>
            <input type="number" name="price_monthly[]" value="<?= (int)$p['price_monthly'] ?>" min="0">
        </div>
        <div class="form-group">
            <label>Yearly Price ($)</label>
            <input type="number" name="price_yearly[]" value="<?= (int)$p['price_yearly'] ?>" min="0">
        </div>
        <div class="form-group">
            <label>Button Text</label>
            <input type="text" name="button_text[]" value="<?= h($p['button_text']) ?>">
        </div>
        <div class="form-group full-width">
            <label>Features (one per line)</label>
            <textarea name="plan_features[]" rows="6"><?= h($feat_text) ?></textarea>
        </div>
        <div class="form-group full-width">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="is_featured[<?= $i ?>]" <?= $p['is_featured'] ? 'checked' : '' ?> style="width:auto">
                Featured plan (highlighted card)
            </label>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<button type="button" class="add-item-btn" onclick="addPlan()">+ Add Plan</button>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Enterprise Block</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Heading</label>
                <input type="text" name="pricing_enterprise_heading" value="<?= h(setting('pricing_enterprise_heading')) ?>">
            </div>
            <div class="form-group full-width">
                <label>Paragraph 1</label>
                <textarea name="pricing_enterprise_text1"><?= h(setting('pricing_enterprise_text1')) ?></textarea>
            </div>
            <div class="form-group full-width">
                <label>Paragraph 2</label>
                <textarea name="pricing_enterprise_text2"><?= h(setting('pricing_enterprise_text2')) ?></textarea>
            </div>
            <div class="form-group">
                <label>Feature 1 Number</label>
                <input type="text" name="pricing_enterprise_feat1_num" value="<?= h(setting('pricing_enterprise_feat1_num')) ?>">
            </div>
            <div class="form-group">
                <label>Feature 1 Label</label>
                <input type="text" name="pricing_enterprise_feat1_label" value="<?= h(setting('pricing_enterprise_feat1_label')) ?>">
            </div>
            <div class="form-group">
                <label>Feature 2 Number</label>
                <input type="text" name="pricing_enterprise_feat2_num" value="<?= h(setting('pricing_enterprise_feat2_num')) ?>">
            </div>
            <div class="form-group">
                <label>Feature 2 Label</label>
                <input type="text" name="pricing_enterprise_feat2_label" value="<?= h(setting('pricing_enterprise_feat2_label')) ?>">
            </div>
            <div class="form-group">
                <label>Feature 3 Number</label>
                <input type="text" name="pricing_enterprise_feat3_num" value="<?= h(setting('pricing_enterprise_feat3_num')) ?>">
            </div>
            <div class="form-group">
                <label>Feature 3 Label</label>
                <input type="text" name="pricing_enterprise_feat3_label" value="<?= h(setting('pricing_enterprise_feat3_label')) ?>">
            </div>
            <div class="form-group">
                <label>Button Text</label>
                <input type="text" name="pricing_enterprise_btn_text" value="<?= h(setting('pricing_enterprise_btn_text')) ?>">
            </div>
            <div class="form-group">
                <label>Button Link</label>
                <input type="text" name="pricing_enterprise_btn_href" value="<?= h(setting('pricing_enterprise_btn_href')) ?>">
            </div>
        </div>
    </div>
</div>

<div style="margin-top:8px">
    <button type="submit" class="btn btn-primary">💾 Save Pricing</button>
</div>
</form>

<template id="plan-tpl">
<div class="item-block">
    <div class="item-block-header">
        <span class="item-block-title">New Plan</span>
        <button type="button" class="item-remove" onclick="removeBlock(this)">✕</button>
    </div>
    <input type="hidden" name="plan_id[]" value="">
    <div class="form-grid">
        <div class="form-group"><label>Plan Name</label><input type="text" name="plan_name[]" required></div>
        <div class="form-group"><label>Monthly Price ($)</label><input type="number" name="price_monthly[]" value="0" min="0"></div>
        <div class="form-group"><label>Yearly Price ($)</label><input type="number" name="price_yearly[]" value="0" min="0"></div>
        <div class="form-group"><label>Button Text</label><input type="text" name="button_text[]" value="Get Started"></div>
        <div class="form-group full-width"><label>Features (one per line)</label><textarea name="plan_features[]" rows="6"></textarea></div>
        <div class="form-group full-width"><label style="display:flex;align-items:center;gap:8px;cursor:pointer"><input type="checkbox" name="is_featured_new[]" style="width:auto"> Featured plan</label></div>
    </div>
</div>
</template>
<script>
let planIdx = <?= count($plans) ?>;
function addPlan() {
    const tpl = document.getElementById('plan-tpl').content.cloneNode(true);
    const cb = tpl.querySelector('input[type=checkbox]');
    if (cb) cb.name = 'is_featured[' + planIdx + ']';
    planIdx++;
    document.getElementById('plans-list').appendChild(tpl);
}
function removeBlock(btn) { btn.closest('.item-block').remove(); }
</script>

<?php include '_layout_end.php'; ?>
