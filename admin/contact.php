<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'contact_heading','contact_subtext',
        'contact_visit_heading','contact_visit_text',
        'contact_address','contact_phone','contact_email','contact_hours',
        'contact_offer_title','contact_offer_text',
        'contact_form_heading','contact_form_subtext',
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

$page_title = 'Contact Info';
$active_nav = 'contact';
include '_layout.php';
?>

<form method="POST" data-ajax data-live>
<div class="card">
    <div class="card-header"><h2>Section Header</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group"><label>Heading</label><input type="text" name="contact_heading" value="<?= h(setting('contact_heading')) ?>"></div>
            <div class="form-group"><label>Subtext</label><input type="text" name="contact_subtext" value="<?= h(setting('contact_subtext')) ?>"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Location Info</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group"><label>Block Heading</label><input type="text" name="contact_visit_heading" value="<?= h(setting('contact_visit_heading')) ?>"></div>
            <div class="form-group full-width"><label>Intro Text</label><textarea name="contact_visit_text"><?= h(setting('contact_visit_text')) ?></textarea></div>
            <div class="form-group"><label>Address</label><input type="text" name="contact_address" value="<?= h(setting('contact_address')) ?>"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="contact_phone" value="<?= h(setting('contact_phone')) ?>"></div>
            <div class="form-group"><label>Email</label><input type="email" name="contact_email" value="<?= h(setting('contact_email')) ?>"></div>
            <div class="form-group"><label>Hours</label><input type="text" name="contact_hours" value="<?= h(setting('contact_hours')) ?>"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Offer Banner</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group"><label>Title</label><input type="text" name="contact_offer_title" value="<?= h(setting('contact_offer_title')) ?>"></div>
            <div class="form-group"><label>Text</label><input type="text" name="contact_offer_text" value="<?= h(setting('contact_offer_text')) ?>"></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Contact Form Labels</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group"><label>Form Heading</label><input type="text" name="contact_form_heading" value="<?= h(setting('contact_form_heading')) ?>"></div>
            <div class="form-group"><label>Form Subtext</label><input type="text" name="contact_form_subtext" value="<?= h(setting('contact_form_subtext')) ?>"></div>
        </div>
    </div>
</div>

<div class="save-bar">
    <button type="submit" class="btn btn-primary">Save</button>
    <span class="save-status"></span>
</div>
</form>

<?php include '_layout_end.php'; ?>
