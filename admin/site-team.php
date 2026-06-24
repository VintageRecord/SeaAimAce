<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save_settings') {
        foreach (['team_meta_title','team_hero_heading','team_hero_sub','team_section_heading'] as $f) {
            save_setting($f, $_POST[$f] ?? '');
        }
    } elseif ($action === 'save_member') {
        $id  = (int)($_POST['id'] ?? 0);
        $data = [
            trim($_POST['name']??''), trim($_POST['role']??''), trim($_POST['bio']??''),
            trim($_POST['image']??''), trim($_POST['fb_url']??''), trim($_POST['tw_url']??''),
            trim($_POST['ig_url']??''), (int)($_POST['sort_order']??0)
        ];
        if ($id) {
            $db->prepare('UPDATE team_members SET name=?,role=?,bio=?,image=?,fb_url=?,tw_url=?,ig_url=?,sort_order=? WHERE id=?')->execute(array_merge($data,[$id]));
        } else {
            $db->prepare('INSERT INTO team_members (name,role,bio,image,fb_url,tw_url,ig_url,sort_order) VALUES (?,?,?,?,?,?,?,?)')->execute($data);
        }
    } elseif ($action === 'delete_member') {
        $db->prepare('DELETE FROM team_members WHERE id=?')->execute([(int)($_POST['id']??0)]);
    }
    if (!empty($_SERVER['HTTP_X_AJAX'])) { header('Content-Type: application/json'); echo json_encode(['success'=>true]); exit; }
    redirect('site-team.php?saved=1');
}

$members = $db->query('SELECT * FROM team_members ORDER BY sort_order ASC')->fetchAll();
$page_title = 'Team Page';
$active_nav = 'site-team';
require '_layout.php';
?>
<?php if (isset($_GET['saved'])): ?><div class="alert alert-success">Saved.</div><?php endif; ?>

<form method="POST" data-ajax>
<input type="hidden" name="action" value="save_settings">
<div class="form-section-title">Page Settings</div>
<div class="form-grid">
  <div class="form-group"><label>Meta Title</label><input type="text" name="team_meta_title" value="<?= h(setting('team_meta_title','Our Team')) ?>"></div>
  <div class="form-group full-width"><label>Hero Heading</label><input type="text" name="team_hero_heading" value="<?= h(setting('team_hero_heading','Our team is looking forward')) ?>"></div>
  <div class="form-group full-width"><label>Hero Subtext</label><textarea name="team_hero_sub" rows="2"><?= h(setting('team_hero_sub','We are a passionate team dedicated to delivering unforgettable camping experiences.')) ?></textarea></div>
  <div class="form-group full-width"><label>Section Heading</label><input type="text" name="team_section_heading" value="<?= h(setting('team_section_heading','Meet The Team')) ?>"></div>
</div>
<div class="save-bar">
  <button type="submit" class="btn btn-primary">Save Settings</button>
  <span class="save-status" id="save-status"></span>
</div>
</form>

<div class="form-section-title" style="margin-top:24px;">Team Members</div>
<?php foreach ($members as $m): ?>
<div class="item-block">
  <form method="POST">
    <input type="hidden" name="action" value="save_member">
    <input type="hidden" name="id" value="<?= $m['id'] ?>">
    <div class="item-block-header">
      <span class="item-block-title"><?= h($m['name']) ?></span>
      <button type="button" class="item-remove" onclick="this.closest('.item-block').querySelector('form[data-del]').submit()">&#10005;</button>
    </div>
    <div class="form-grid">
      <div class="form-group"><label>Name</label><input type="text" name="name" value="<?= h($m['name']) ?>" required></div>
      <div class="form-group"><label>Role / Title</label><input type="text" name="role" value="<?= h($m['role']) ?>"></div>
      <div class="form-group full-width"><label>Bio</label><textarea name="bio" rows="2"><?= h($m['bio']) ?></textarea></div>
      <div class="form-group full-width"><label>Photo Path (e.g. new_images/01.png or uploads/photo.jpg)</label><input type="text" name="image" value="<?= h($m['image']) ?>"></div>
      <div class="form-group"><label>Facebook URL</label><input type="text" name="fb_url" value="<?= h($m['fb_url']) ?>" placeholder="https://facebook.com/..."></div>
      <div class="form-group"><label>Twitter URL</label><input type="text" name="tw_url" value="<?= h($m['tw_url']) ?>" placeholder="https://twitter.com/..."></div>
      <div class="form-group"><label>Instagram URL</label><input type="text" name="ig_url" value="<?= h($m['ig_url']) ?>" placeholder="https://instagram.com/..."></div>
      <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= $m['sort_order'] ?>"></div>
    </div>
    <button type="submit" class="btn btn-secondary btn-sm">Update Member</button>
  </form>
  <form method="POST" data-del style="display:none"><input type="hidden" name="action" value="delete_member"><input type="hidden" name="id" value="<?= $m['id'] ?>"></form>
</div>
<?php endforeach; ?>

<div class="card" style="margin-top:12px;">
  <div class="card-header"><h2>Add Team Member</h2></div>
  <div class="card-body">
    <form method="POST">
      <input type="hidden" name="action" value="save_member">
      <div class="form-grid">
        <div class="form-group"><label>Name *</label><input type="text" name="name" required placeholder="Jane Smith"></div>
        <div class="form-group"><label>Role</label><input type="text" name="role" placeholder="Camp Director"></div>
        <div class="form-group full-width"><label>Bio</label><textarea name="bio" rows="2" placeholder="Short bio..."></textarea></div>
        <div class="form-group full-width"><label>Photo Path</label><input type="text" name="image" placeholder="new_images/01.png"></div>
        <div class="form-group"><label>Facebook URL</label><input type="text" name="fb_url" placeholder="https://..."></div>
        <div class="form-group"><label>Twitter URL</label><input type="text" name="tw_url" placeholder="https://..."></div>
        <div class="form-group"><label>Instagram URL</label><input type="text" name="ig_url" placeholder="https://..."></div>
        <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= count($members) ?>"></div>
      </div>
      <button type="submit" class="btn btn-primary">Add Member</button>
    </form>
  </div>
</div>

<?php require '_layout_end.php'; ?>
