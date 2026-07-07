<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['site_name','site_logo','home_meta_title','home_meta_desc',
               'home_hero_heading','home_hero_sub','home_hero_btn1','home_hero_btn1url','home_hero_btn2','home_hero_btn2url',
               'home_sec2_heading','home_sec2_text',
               'home_amenities_heading','home_amenities_list',
               'home_family_heading','home_family_text','home_family_btn',
               'home_activities_heading','home_sport_heading','home_sport_text','home_sport_btn',
               'footer_text'];
    foreach ($fields as $f) {
        save_setting($f, $_POST[$f] ?? '');
    }
    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    $saved = true;
}

$page_title = 'Home Page';
$active_nav = 'general';
require '_layout.php';
?>
<?php if (!empty($saved)): ?>
<div class="alert alert-success">Settings saved.</div>
<?php endif; ?>

<form method="POST" data-ajax data-live>
<div class="form-section-title">Site Identity</div>
<div class="form-grid">
  <div class="form-group">
    <label>Site Name</label>
    <input type="text" name="site_name" value="<?= h(setting('site_name','CampForge')) ?>">
  </div>
  <div class="form-group">
    <label>Logo Image Path (relative to site root)</label>
    <input type="text" name="site_logo" value="<?= h(setting('site_logo','new_images/-.png')) ?>">
  </div>
  <div class="form-group">
    <label>Home Meta Title</label>
    <input type="text" name="home_meta_title" value="<?= h(setting('home_meta_title','Home')) ?>">
  </div>
  <div class="form-group">
    <label>Home Meta Description</label>
    <input type="text" name="home_meta_desc" value="<?= h(setting('home_meta_desc','Best Camping in the National Park')) ?>">
  </div>
</div>

<div class="form-section-title">Hero Section</div>
<div class="form-grid">
  <div class="form-group full-width">
    <label>Hero Heading</label>
    <input type="text" name="home_hero_heading" value="<?= h(setting('home_hero_heading','Best Camping in the National Park')) ?>">
  </div>
  <div class="form-group full-width">
    <label>Hero Subtext</label>
    <textarea name="home_hero_sub" rows="2"><?= h(setting('home_hero_sub','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.')) ?></textarea>
  </div>
  <div class="form-group">
    <label>Button 1 Text</label>
    <input type="text" name="home_hero_btn1" value="<?= h(setting('home_hero_btn1','Our Story')) ?>">
  </div>
  <div class="form-group">
    <label>Button 1 URL</label>
    <input type="text" name="home_hero_btn1url" value="<?= h(setting('home_hero_btn1url','about.php')) ?>">
  </div>
  <div class="form-group">
    <label>Button 2 Text</label>
    <input type="text" name="home_hero_btn2" value="<?= h(setting('home_hero_btn2','Contact Us')) ?>">
  </div>
  <div class="form-group">
    <label>Button 2 URL</label>
    <input type="text" name="home_hero_btn2url" value="<?= h(setting('home_hero_btn2url','contact.php')) ?>">
  </div>
</div>

<div class="form-section-title">Tours Section</div>
<div class="form-grid">
  <div class="form-group">
    <label>Heading</label>
    <input type="text" name="home_sec2_heading" value="<?= h(setting('home_sec2_heading','10 Amazing Camping Tours')) ?>">
  </div>
  <div class="form-group full-width">
    <label>Text</label>
    <textarea name="home_sec2_text" rows="2"><?= h(setting('home_sec2_text','Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.')) ?></textarea>
  </div>
</div>

<div class="form-section-title">Amenities Section</div>
<div class="form-grid">
  <div class="form-group">
    <label>Section Heading</label>
    <input type="text" name="home_amenities_heading" value="<?= h(setting('home_amenities_heading','Available to campsite guests:')) ?>">
  </div>
  <div class="form-group full-width">
    <label>Amenity Items (one per line)</label>
    <textarea name="home_amenities_list" rows="6"><?= h(setting('home_amenities_list',"store (with eco products)\nchildren's playground\nclimbing tower\nvolleyball court\nbike hire\nmountain bike hire\npétanque court\ntable tennis")) ?></textarea>
  </div>
</div>

<div class="form-section-title">Family Camp Section</div>
<div class="form-grid">
  <div class="form-group">
    <label>Heading</label>
    <input type="text" name="home_family_heading" value="<?= h(setting('home_family_heading','Family Camp')) ?>">
  </div>
  <div class="form-group">
    <label>Button Text</label>
    <input type="text" name="home_family_btn" value="<?= h(setting('home_family_btn','Book Now')) ?>">
  </div>
  <div class="form-group full-width">
    <label>Text</label>
    <textarea name="home_family_text" rows="2"><?= h(setting('home_family_text','Lorem ipsum dolor sit amet, consectetur adipiscing elit.')) ?></textarea>
  </div>
</div>

<div class="form-section-title">Our Camping Section</div>
<div class="form-grid">
  <div class="form-group full-width">
    <label>Section Heading</label>
    <input type="text" name="home_activities_heading" value="<?= h(setting('home_activities_heading','Our Camping')) ?>">
  </div>
</div>

<div class="form-section-title">Sport Activities Section</div>
<div class="form-grid">
  <div class="form-group">
    <label>Heading</label>
    <input type="text" name="home_sport_heading" value="<?= h(setting('home_sport_heading','Sport activities')) ?>">
  </div>
  <div class="form-group">
    <label>Button Text</label>
    <input type="text" name="home_sport_btn" value="<?= h(setting('home_sport_btn','Contact Us')) ?>">
  </div>
  <div class="form-group full-width">
    <label>Text</label>
    <textarea name="home_sport_text" rows="2"><?= h(setting('home_sport_text','Lorem ipsum dolor sit amet, consectetur adipiscing elit.')) ?></textarea>
  </div>
</div>

<div class="form-section-title">Footer</div>
<div class="form-group">
  <label>Footer Copyright Text</label>
  <input type="text" name="footer_text" value="<?= h(setting('footer_text','© ' . date('Y') . ' CampForge. All rights reserved.')) ?>">
</div>

<div class="save-bar">
  <button type="submit" class="btn btn-primary">Save Changes</button>
  <span class="save-status" id="save-status"></span>
</div>
</form>

<?php $_sections_page = 'home'; require '_sections_editor.php'; ?>
<?php require '_layout_end.php'; ?>
