<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['contact_meta_title','contact_hero_heading','contact_hero_sub','contact_form_heading',
               'contact_submit_btn','contact_success_msg',
               'contact_info1_title','contact_info1_text','contact_info2_title','contact_info2_text',
               'contact_info3_title','contact_info3_text','contact_info4_title','contact_info4_text'];
    foreach ($fields as $f) save_setting($f, $_POST[$f] ?? '');
    if (!empty($_SERVER['HTTP_X_AJAX'])) { header('Content-Type: application/json'); echo json_encode(['success'=>true]); exit; }
    $saved = true;
}

$page_title = 'Contact Page';
$active_nav = 'site-contact';
require '_layout.php';
?>
<?php if (!empty($saved)): ?><div class="alert alert-success">Saved.</div><?php endif; ?>
<form method="POST" data-ajax data-live>
<div class="form-section-title">Hero</div>
<div class="form-grid">
  <div class="form-group"><label>Meta Title</label><input type="text" name="contact_meta_title" value="<?= h(setting('contact_meta_title','Contact Us')) ?>"></div>
  <div class="form-group full-width"><label>Hero Heading</label><input type="text" name="contact_hero_heading" value="<?= h(setting('contact_hero_heading','Plan Your Camping Trip')) ?>"></div>
  <div class="form-group full-width"><label>Hero Subtext</label><textarea name="contact_hero_sub" rows="2"><?= h(setting('contact_hero_sub','We\'d love to help you plan the perfect outdoor adventure.')) ?></textarea></div>
</div>
<div class="form-section-title">Contact Form</div>
<div class="form-grid">
  <div class="form-group"><label>Form Heading</label><input type="text" name="contact_form_heading" value="<?= h(setting('contact_form_heading','Get in Touch')) ?>"></div>
  <div class="form-group"><label>Submit Button Text</label><input type="text" name="contact_submit_btn" value="<?= h(setting('contact_submit_btn','Send Message')) ?>"></div>
  <div class="form-group full-width"><label>Success Message</label><input type="text" name="contact_success_msg" value="<?= h(setting('contact_success_msg','Thank you! We\'ll be in touch soon.')) ?>"></div>
</div>
<div class="form-section-title">Contact Info Boxes</div>
<?php
$infos = [
    ['title'=>'Address','text'=>"123 National Park Road\nWilderness, CA 90210"],
    ['title'=>'Phone','text'=>'+1 (555) 000-0000'],
    ['title'=>'Email','text'=>'hello@campforge.com'],
    ['title'=>'Hours','text'=>"Mon–Fri: 8am – 6pm\nWeekends: 9am – 5pm"],
];
foreach ($infos as $i => $def): $n = $i + 1; ?>
<div class="form-grid">
  <div class="form-group"><label>Box <?= $n ?> Title</label><input type="text" name="contact_info<?= $n ?>_title" value="<?= h(setting("contact_info{$n}_title",$def['title'])) ?>"></div>
  <div class="form-group"><label>Box <?= $n ?> Text</label><textarea name="contact_info<?= $n ?>_text" rows="2"><?= h(setting("contact_info{$n}_text",$def['text'])) ?></textarea></div>
</div>
<?php endforeach; ?>
<div class="save-bar">
  <button type="submit" class="btn btn-primary">Save Changes</button>
  <span class="save-status" id="save-status"></span>
</div>
</form>
<?php require '_layout_end.php'; ?>
