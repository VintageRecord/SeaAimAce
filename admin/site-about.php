<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$_sections_page = 'about';
require '_sections_handler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['_sec_action'])) {
    $fields = ['about_meta_title','about_meta_title_desc','about_hero_heading','about_hero_sub','about_hero_btn1','about_hero_btn2',
               'about_mission_heading','about_mission_sub','about_mission_text',
               'about_act1_title','about_act1_text','about_act2_title','about_act2_text',
               'about_act3_title','about_act3_text','about_act4_title','about_act4_text',
               'about_act5_title','about_act5_text','about_act6_title','about_act6_text',
               'about_getaway_heading','about_gtw1_title','about_gtw2_title','about_gtw3_title','about_gtw4_title'];
    foreach ($fields as $f) save_setting($f, $_POST[$f] ?? '');
    if (!empty($_SERVER['HTTP_X_AJAX'])) { header('Content-Type: application/json'); echo json_encode(['success'=>true]); exit; }
    $saved = true;
}

$page_title = 'About Page';
$active_nav = 'site-about';
$preview_url = '../about.php';
require '_layout.php';
?>
<?php if (!empty($saved)): ?><div class="alert alert-success">Saved.</div><?php endif; ?>
<form method="POST" data-ajax data-live>
<div class="form-section-title">Meta</div>
<div class="form-grid">
  <div class="form-group"><label>Meta Title</label><input type="text" name="about_meta_title" value="<?= h(setting('about_meta_title','About Us')) ?>"></div>
  <div class="form-group"><label>Meta Description</label><input type="text" name="about_meta_title_desc" value="<?= h(setting('about_meta_title_desc','About us - Find yourself outside')) ?>"></div>
</div>
<div class="form-section-title">Hero</div>
<div class="form-grid">
  <div class="form-group full-width"><label>Heading</label><input type="text" name="about_hero_heading" value="<?= h(setting('about_hero_heading','Find yourself outside')) ?>"></div>
  <div class="form-group full-width"><label>Subtext</label><textarea name="about_hero_sub" rows="2"><?= h(setting('about_hero_sub','Lorem ipsum dolor sit amet.')) ?></textarea></div>
  <div class="form-group"><label>Button 1</label><input type="text" name="about_hero_btn1" value="<?= h(setting('about_hero_btn1','View More')) ?>"></div>
  <div class="form-group"><label>Button 2</label><input type="text" name="about_hero_btn2" value="<?= h(setting('about_hero_btn2','Contact Us')) ?>"></div>
</div>
<div class="form-section-title">Mission Section</div>
<div class="form-grid">
  <div class="form-group"><label>Heading</label><input type="text" name="about_mission_heading" value="<?= h(setting('about_mission_heading','Our mission')) ?>"></div>
  <div class="form-group"><label>Subheading</label><input type="text" name="about_mission_sub" value="<?= h(setting('about_mission_sub','Get more people outside')) ?>"></div>
  <div class="form-group full-width"><label>Text</label><textarea name="about_mission_text" rows="3"><?= h(setting('about_mission_text','Lorem ipsum dolor sit amet, consectetur adipiscing elit.')) ?></textarea></div>
</div>
<div class="form-section-title">Activities (6 items)</div>
<?php for ($i = 1; $i <= 6; $i++): ?>
<div class="form-grid">
  <div class="form-group"><label>Activity <?= $i ?> Title</label><input type="text" name="about_act<?= $i ?>_title" value="<?= h(setting("about_act{$i}_title", '')) ?>"></div>
  <div class="form-group"><label>Activity <?= $i ?> Text</label><input type="text" name="about_act<?= $i ?>_text" value="<?= h(setting("about_act{$i}_text", '')) ?>"></div>
</div>
<?php endfor; ?>
<div class="form-section-title">Best Getaways</div>
<div class="form-grid">
  <div class="form-group full-width"><label>Section Heading</label><input type="text" name="about_getaway_heading" value="<?= h(setting('about_getaway_heading','Best Getaways')) ?>"></div>
  <?php for ($i = 1; $i <= 4; $i++): ?>
  <div class="form-group"><label>Card <?= $i ?> Title</label><input type="text" name="about_gtw<?= $i ?>_title" value="<?= h(setting("about_gtw{$i}_title",'')) ?>"></div>
  <?php endfor; ?>
</div>
<div class="save-bar">
  <button type="submit" class="btn btn-primary">Save Changes</button>
  <span class="save-status" id="save-status"></span>
</div>
</form>
<?php $_sections_page = 'about'; require '_sections_editor.php'; ?>
<?php require '_layout_end.php'; ?>
