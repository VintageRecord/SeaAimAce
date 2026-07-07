<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$_sections_page = 'gallery';
require '_sections_handler.php';

$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save_settings') {
        foreach (['gallery_meta_title','gallery_hero_heading','gallery_hero_sub','gallery_section_heading','gallery_cta_heading','gallery_cta_text','gallery_cta_btn'] as $f) {
            save_setting($f, $_POST[$f] ?? '');
        }
    } elseif ($action === 'add_item') {
        $img = trim($_POST['image'] ?? '');
        $cap = trim($_POST['caption'] ?? '');
        $ord = (int)($_POST['sort_order'] ?? 0);
        if ($img) $db->prepare('INSERT INTO gallery_items (image,caption,sort_order) VALUES (?,?,?)')->execute([$img,$cap,$ord]);
    } elseif ($action === 'delete_item') {
        $db->prepare('DELETE FROM gallery_items WHERE id=?')->execute([(int)($_POST['id'] ?? 0)]);
    } elseif ($action === 'update_item') {
        $db->prepare('UPDATE gallery_items SET image=?,caption=?,sort_order=? WHERE id=?')->execute([
            trim($_POST['image']??''), trim($_POST['caption']??''), (int)($_POST['sort_order']??0), (int)($_POST['id']??0)
        ]);
    }
    if (!empty($_SERVER['HTTP_X_AJAX'])) { header('Content-Type: application/json'); echo json_encode(['success'=>true]); exit; }
    redirect('site-gallery.php?saved=1');
}

$items = $db->query('SELECT * FROM gallery_items ORDER BY sort_order ASC')->fetchAll();
$page_title = 'Gallery Page';
$active_nav = 'site-gallery';
require '_layout.php';
?>
<?php if (isset($_GET['saved'])): ?><div class="alert alert-success">Saved.</div><?php endif; ?>

<form method="POST" data-ajax>
<input type="hidden" name="action" value="save_settings">
<div class="form-section-title">Page Settings</div>
<div class="form-grid">
  <div class="form-group"><label>Meta Title</label><input type="text" name="gallery_meta_title" value="<?= h(setting('gallery_meta_title','Gallery')) ?>"></div>
  <div class="form-group full-width"><label>Hero Heading</label><input type="text" name="gallery_hero_heading" value="<?= h(setting('gallery_hero_heading','Where Can I Camp?')) ?>"></div>
  <div class="form-group full-width"><label>Hero Subtext</label><textarea name="gallery_hero_sub" rows="2"><?= h(setting('gallery_hero_sub','Explore our stunning collection of camping destinations.')) ?></textarea></div>
  <div class="form-group"><label>Section Heading</label><input type="text" name="gallery_section_heading" value="<?= h(setting('gallery_section_heading','Our Photo Gallery')) ?>"></div>
  <div class="form-group"><label>CTA Heading</label><input type="text" name="gallery_cta_heading" value="<?= h(setting('gallery_cta_heading','Ready to explore?')) ?>"></div>
  <div class="form-group"><label>CTA Button</label><input type="text" name="gallery_cta_btn" value="<?= h(setting('gallery_cta_btn','Book Now')) ?>"></div>
  <div class="form-group full-width"><label>CTA Text</label><input type="text" name="gallery_cta_text" value="<?= h(setting('gallery_cta_text','Book your camping adventure today.')) ?>"></div>
</div>
<div class="save-bar">
  <button type="submit" class="btn btn-primary">Save Settings</button>
  <span class="save-status" id="save-status"></span>
</div>
</form>

<div class="form-section-title" style="margin-top:24px;">Gallery Images (<?= count($items) ?> items)</div>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;margin-bottom:20px;">
<?php foreach ($items as $item): ?>
<div class="item-block" style="margin-bottom:0;">
  <form method="POST">
    <input type="hidden" name="action" value="update_item">
    <input type="hidden" name="id" value="<?= $item['id'] ?>">
    <?php if ($item['image']): ?>
    <img src="../<?= h($item['image']) ?>" style="width:100%;height:140px;object-fit:cover;border-radius:5px;margin-bottom:10px;" onerror="this.style.display='none'">
    <?php endif; ?>
    <div class="form-group"><label>Image Path</label><input type="text" name="image" value="<?= h($item['image']) ?>"></div>
    <div class="form-group"><label>Caption</label><input type="text" name="caption" value="<?= h($item['caption']) ?>"></div>
    <div class="form-group"><label>Sort</label><input type="number" name="sort_order" value="<?= $item['sort_order'] ?>" style="width:80px;"></div>
    <div style="display:flex;gap:8px;">
      <button type="submit" class="btn btn-secondary btn-sm">Update</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.item-block').querySelector('form[data-del]').submit()">Delete</button>
    </div>
  </form>
  <form method="POST" data-del style="display:none"><input type="hidden" name="action" value="delete_item"><input type="hidden" name="id" value="<?= $item['id'] ?>"></form>
</div>
<?php endforeach; ?>
</div>

<div class="card">
  <div class="card-header"><h2>Add Image</h2></div>
  <div class="card-body">
    <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:12px;">Upload an image via the <a href="media.php">Media Library</a>, then paste the path here.</p>
    <form method="POST">
      <input type="hidden" name="action" value="add_item">
      <div class="form-grid">
        <div class="form-group"><label>Image Path (e.g. uploads/photo.jpg)</label><input type="text" name="image" placeholder="new_images/3.jpg" required></div>
        <div class="form-group"><label>Caption (optional)</label><input type="text" name="caption" placeholder="Sunset at the lake"></div>
        <div class="form-group"><label>Sort Order</label><input type="number" name="sort_order" value="<?= count($items) ?>"></div>
      </div>
      <button type="submit" class="btn btn-primary">Add Image</button>
    </form>
  </div>
</div>

<?php $_sections_page = 'gallery'; require '_sections_editor.php'; ?>
<?php require '_layout_end.php'; ?>
