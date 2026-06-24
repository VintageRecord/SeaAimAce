<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$fields = [
    'site_title',
    'nav_logo_text','nav_book_btn_text','nav_book_btn_href',
    'hero_heading_line1','hero_heading_line2','hero_subtitle',
    'hero_btn1_text','hero_btn1_href','hero_btn2_text','hero_btn2_href',
    'features_heading','features_subtext',
    'spaces_heading','spaces_subtext',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $f) {
        if (isset($_POST[$f])) save_setting($f, trim($_POST[$f]));
    }
    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}

$page_title = 'General & Hero';
$active_nav = 'general';
include '_layout.php';
?>

<form method="POST" data-ajax data-live>
<div class="card">
    <div class="card-header"><h2>Site Settings</h2></div>
    <div class="card-body">
        <div class="form-group">
            <label>Site Title (browser tab)</label>
            <input type="text" name="site_title" value="<?= h(setting('site_title')) ?>">
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Navigation</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group">
                <label>Logo Text</label>
                <input type="text" name="nav_logo_text" value="<?= h(setting('nav_logo_text')) ?>">
            </div>
            <div class="form-group">
                <label>Book Tour Button Text</label>
                <input type="text" name="nav_book_btn_text" value="<?= h(setting('nav_book_btn_text')) ?>">
            </div>
            <div class="form-group">
                <label>Book Tour Button Link</label>
                <input type="text" name="nav_book_btn_href" value="<?= h(setting('nav_book_btn_href')) ?>">
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Hero Section</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group">
                <label>Heading Line 1</label>
                <input type="text" name="hero_heading_line1" value="<?= h(setting('hero_heading_line1')) ?>">
            </div>
            <div class="form-group">
                <label>Heading Line 2 (accent colour)</label>
                <input type="text" name="hero_heading_line2" value="<?= h(setting('hero_heading_line2')) ?>">
            </div>
            <div class="form-group full-width">
                <label>Subtitle</label>
                <input type="text" name="hero_subtitle" value="<?= h(setting('hero_subtitle')) ?>">
            </div>
            <div class="form-group">
                <label>Primary Button Text</label>
                <input type="text" name="hero_btn1_text" value="<?= h(setting('hero_btn1_text')) ?>">
            </div>
            <div class="form-group">
                <label>Primary Button Link</label>
                <input type="text" name="hero_btn1_href" value="<?= h(setting('hero_btn1_href')) ?>">
            </div>
            <div class="form-group">
                <label>Secondary Button Text</label>
                <input type="text" name="hero_btn2_text" value="<?= h(setting('hero_btn2_text')) ?>">
            </div>
            <div class="form-group">
                <label>Secondary Button Link</label>
                <input type="text" name="hero_btn2_href" value="<?= h(setting('hero_btn2_href')) ?>">
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Section Headers</h2></div>
    <div class="card-body">
        <p class="form-section-title">Features Section</p>
        <div class="form-grid">
            <div class="form-group">
                <label>Heading</label>
                <input type="text" name="features_heading" value="<?= h(setting('features_heading')) ?>">
            </div>
            <div class="form-group">
                <label>Subtext</label>
                <input type="text" name="features_subtext" value="<?= h(setting('features_subtext')) ?>">
            </div>
        </div>
        <p class="form-section-title">Spaces Section</p>
        <div class="form-grid">
            <div class="form-group">
                <label>Heading</label>
                <input type="text" name="spaces_heading" value="<?= h(setting('spaces_heading')) ?>">
            </div>
            <div class="form-group">
                <label>Subtext</label>
                <input type="text" name="spaces_subtext" value="<?= h(setting('spaces_subtext')) ?>">
            </div>
        </div>
    </div>
</div>

<div class="save-bar">
    <button type="submit" class="btn btn-primary">Save</button>
    <span class="save-status"></span>
</div>
</form>

<?php include '_layout_end.php'; ?>
