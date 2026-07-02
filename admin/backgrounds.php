<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

$bg_slots = [
    'Home' => [
        ['bg_home_hero',    'Hero Section'],
        ['bg_home_sec2',    'Section 2 Image'],
        ['bg_home_sec4',    'Section 4 Image'],
        ['bg_home_sec6',    'Section 6 Background'],
        ['bg_home_contact', 'Contact Section'],
    ],
    'About' => [
        ['bg_about_hero',    'Hero Section'],
        ['bg_about_contact', 'Contact Section'],
    ],
    'Gallery' => [
        ['bg_gallery_hero', 'Hero Section'],
        ['bg_gallery_sec6', 'Bottom Section'],
    ],
    'Team' => [
        ['bg_team_hero', 'Hero Image'],
    ],
    'FAQ' => [
        ['bg_faq_hero', 'Hero Section'],
    ],
    'Contact' => [
        ['bg_contact_hero', 'Hero Section'],
        ['bg_contact_sec4', 'CTA Section'],
    ],
];

$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $k => $v) {
        if (str_starts_with($k, 'bg_')) {
            save_setting($k, trim($v));
        }
    }
    if (!empty($_FILES)) {
        $UPLOADS_DIR = dirname(__DIR__) . '/uploads';
        $ALLOWED_EXT = ['jpg','jpeg','png','gif','webp','svg'];
        foreach ($_FILES as $k => $f) {
            if (!str_ends_with($k, '_upload') || $f['error'] !== UPLOAD_ERR_OK) continue;
            $setting_key = substr($k, 0, -7);
            if (!str_starts_with($setting_key, 'bg_')) continue;
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $ALLOWED_EXT)) continue;
            $fname = uniqid('bg_', true) . '.' . $ext;
            if (move_uploaded_file($f['tmp_name'], $UPLOADS_DIR . '/' . $fname)) {
                $db->prepare('INSERT OR IGNORE INTO media (filename,mime,size) VALUES (?,?,?)')
                   ->execute([$fname, mime_content_type($UPLOADS_DIR . '/' . $fname), $f['size']]);
                save_setting($setting_key, $fname);
            }
        }
    }
    redirect('backgrounds.php?flash=saved');
}

if (isset($_GET['flash'])) $flash = $_GET['flash'];

$media_files = $db->query("SELECT filename FROM media ORDER BY id DESC")->fetchAll(PDO::FETCH_COLUMN);

$page_title = 'Background Images';
$active_nav = 'backgrounds';
include '_layout.php';
?>
<style>
.bg-page-section { margin-bottom:36px; }
.bg-page-section h2 { font-size:.85rem; font-weight:700; color:#94a3b8; letter-spacing:.08em; text-transform:uppercase; margin:0 0 14px; }
.bg-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:14px; }
.bg-card { background:#fff; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; }
.bg-card-preview {
    height:120px; background:#f1f5f9 center/cover no-repeat;
    display:flex; align-items:center; justify-content:center;
    color:#94a3b8; font-size:.72rem; position:relative;
}
.bg-clear-btn {
    position:absolute; top:6px; right:6px; display:none;
    background:rgba(0,0,0,.55); color:#fff; border:none; border-radius:4px;
    font-size:.68rem; padding:3px 7px; cursor:pointer;
}
.bg-card-preview.has-img .bg-clear-btn,
.bg-card-preview:hover .bg-clear-btn { display:block; }
.bg-card-body { padding:10px 12px; }
.bg-card-label { font-size:.78rem; font-weight:600; color:#475569; margin:0 0 8px; }
.bg-card-actions { display:flex; gap:6px; flex-wrap:wrap; }
.bg-upload-btn { font-size:.72rem; padding:4px 9px; background:#3b82f6; color:#fff; border:none; border-radius:5px; cursor:pointer; }
.bg-upload-btn:hover { background:#2563eb; }
.bg-pick-btn { font-size:.72rem; padding:4px 9px; background:#f1f5f9; color:#334155; border:1px solid #cbd5e1; border-radius:5px; cursor:pointer; }
.bg-pick-btn:hover { background:#e2e8f0; }
.bg-file-input { display:none; }
.bg-val-input { width:100%; font-size:.7rem; color:#64748b; border:1px solid #e2e8f0; border-radius:4px; padding:3px 6px; margin-top:6px; box-sizing:border-box; }

#picker-modal { display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.5); align-items:center; justify-content:center; }
#picker-modal.open { display:flex; }
.picker-box { background:#fff; border-radius:12px; width:min(700px,95vw); max-height:80vh; display:flex; flex-direction:column; overflow:hidden; }
.picker-head { padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; }
.picker-head h3 { margin:0; font-size:.95rem; }
.picker-close { background:none; border:none; font-size:1.3rem; cursor:pointer; color:#64748b; }
.picker-grid { padding:16px; overflow-y:auto; display:grid; grid-template-columns:repeat(auto-fill,minmax(90px,1fr)); gap:8px; }
.picker-thumb { aspect-ratio:1; object-fit:cover; border-radius:6px; cursor:pointer; border:2px solid transparent; width:100%; }
.picker-thumb:hover { border-color:#3b82f6; }

.flash-ok { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; border-radius:6px; padding:10px 16px; margin-bottom:20px; font-size:.85rem; }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
  <p style="margin:0;color:#64748b;font-size:.85rem">Set custom background images for each page section. Leave blank to use the default.</p>
  <button form="bg-form" type="submit" class="save-btn">Save All</button>
</div>

<?php if ($flash === 'saved'): ?>
<div class="flash-ok">Background images saved successfully.</div>
<?php endif; ?>

<form id="bg-form" method="post" enctype="multipart/form-data">
<?php foreach ($bg_slots as $page_label => $slots): ?>
<div class="bg-page-section">
  <h2><?= h($page_label) ?></h2>
  <div class="bg-grid">
  <?php foreach ($slots as [$key, $label]):
    $cv = setting($key, '');
    $preview_url = $cv !== '' ? ('../uploads/' . ltrim($cv, 'uploads/')) : '';
  ?>
  <div class="bg-card">
    <div class="bg-card-preview <?= $cv !== '' ? 'has-img' : '' ?>"
         id="preview-<?= h($key) ?>"
         <?= $preview_url ? 'style="background-image:url(\'' . h($preview_url) . '\')"' : '' ?>>
      <?php if ($cv === ''): ?><span id="noimg-<?= h($key) ?>">No image set</span><?php endif; ?>
      <button type="button" class="bg-clear-btn" onclick="clearBg('<?= h($key) ?>')">&#10005; Clear</button>
    </div>
    <div class="bg-card-body">
      <div class="bg-card-label"><?= h($label) ?></div>
      <div class="bg-card-actions">
        <label class="bg-upload-btn">
          Upload
          <input type="file" name="<?= h($key) ?>_upload" accept="image/*" class="bg-file-input"
                 onchange="previewUpload(this,'<?= h($key) ?>')">
        </label>
        <button type="button" class="bg-pick-btn" onclick="openPicker('<?= h($key) ?>')">Library</button>
      </div>
      <input type="text" name="<?= h($key) ?>" id="val-<?= h($key) ?>"
             value="<?= h($cv) ?>" placeholder="filename.jpg"
             class="bg-val-input" oninput="refreshPreview('<?= h($key) ?>')">
    </div>
  </div>
  <?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>
</form>

<!-- Media picker modal -->
<div id="picker-modal">
  <div class="picker-box">
    <div class="picker-head">
      <h3>Media Library</h3>
      <button class="picker-close" onclick="closePicker()">&#10005;</button>
    </div>
    <div class="picker-grid">
      <?php foreach ($media_files as $mf): ?>
      <img src="../uploads/<?= h($mf) ?>"
           class="picker-thumb"
           title="<?= h($mf) ?>"
           onclick="pickMedia('<?= h(addslashes($mf)) ?>')">
      <?php endforeach; ?>
      <?php if (empty($media_files)): ?>
      <p style="grid-column:1/-1;color:#94a3b8;font-size:.85rem">No images yet. <a href="media.php">Upload via Media Library.</a></p>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
var _pickerTarget = null;

function openPicker(key) {
  _pickerTarget = key;
  document.getElementById('picker-modal').classList.add('open');
}
function closePicker() {
  document.getElementById('picker-modal').classList.remove('open');
  _pickerTarget = null;
}
function pickMedia(fname) {
  if (!_pickerTarget) return;
  document.getElementById('val-' + _pickerTarget).value = fname;
  refreshPreview(_pickerTarget);
  closePicker();
}

function refreshPreview(key) {
  var val = (document.getElementById('val-' + key).value || '').trim();
  var el  = document.getElementById('preview-' + key);
  var ni  = document.getElementById('noimg-' + key);
  if (val && val !== '(uploading...)') {
    var url = val.indexOf('/') === -1 ? '../uploads/' + val : '../' + val;
    el.style.backgroundImage = "url('" + url + "')";
    el.classList.add('has-img');
    if (ni) ni.style.display = 'none';
  } else if (!val) {
    el.style.backgroundImage = '';
    el.classList.remove('has-img');
    if (ni) ni.style.display = '';
  }
}

function clearBg(key) {
  document.getElementById('val-' + key).value = '';
  refreshPreview(key);
}

function previewUpload(input, key) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var el = document.getElementById('preview-' + key);
    el.style.backgroundImage = "url('" + e.target.result + "')";
    el.classList.add('has-img');
    var ni = document.getElementById('noimg-' + key);
    if (ni) ni.style.display = 'none';
    document.getElementById('val-' + key).value = '(uploading...)';
  };
  reader.readAsDataURL(input.files[0]);
}

document.getElementById('picker-modal').addEventListener('click', function(e) {
  if (e.target === this) closePicker();
});
</script>

<?php include '_layout_end.php'; ?>
