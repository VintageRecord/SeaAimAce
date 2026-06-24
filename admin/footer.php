<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'cta_heading','cta_subtext',
        'cta_btn1_text','cta_btn1_href',
        'cta_btn2_text','cta_btn2_href',
        'footer_copyright',
    ];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) save_setting($f, trim($_POST[$f]));
    }

    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}

$page_title = 'CTA & Footer';
$active_nav = 'footer';
include '_layout.php';
?>

<form method="POST" data-ajax data-live>
<div class="card">
    <div class="card-header"><h2>Call-to-Action Section</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group full-width"><label>Heading</label><input type="text" name="cta_heading" value="<?= h(setting('cta_heading')) ?>"></div>
            <div class="form-group full-width"><label>Subtext</label><input type="text" name="cta_subtext" value="<?= h(setting('cta_subtext')) ?>"></div>
            <div class="form-group"><label>Primary Button Text</label><input type="text" name="cta_btn1_text" value="<?= h(setting('cta_btn1_text')) ?>"></div>
            <div class="form-group"><label>Primary Button Link</label><input type="text" name="cta_btn1_href" value="<?= h(setting('cta_btn1_href')) ?>"></div>
            <div class="form-group"><label>Secondary Button Text</label><input type="text" name="cta_btn2_text" value="<?= h(setting('cta_btn2_text')) ?>"></div>
            <div class="form-group"><label>Secondary Button Link</label><input type="text" name="cta_btn2_href" value="<?= h(setting('cta_btn2_href')) ?>"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Footer</h2></div>
    <div class="card-body">
        <div class="form-group">
            <label>Copyright Text</label>
            <input type="text" name="footer_copyright" value="<?= h(setting('footer_copyright')) ?>">
        </div>
    </div>
</div>

<div class="save-bar">
    <button type="submit" class="btn btn-primary">Save</button>
    <span class="save-status"></span>
</div>
</form>

<?php include '_layout_end.php'; ?>
