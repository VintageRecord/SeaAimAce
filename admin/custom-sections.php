<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

$pages_list = [
    'home'    => 'Home',
    'about'   => 'About Us',
    'gallery' => 'Gallery',
    'team'    => 'Our Team',
    'faq'     => 'FAQ',
    'contact' => 'Contact',
];

$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? '';

    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $page      = $_POST['page']      ?? 'home';
        $heading   = trim($_POST['heading']  ?? '');
        $body      = trim($_POST['body']     ?? '');
        $bg        = trim($_POST['bg_color']    ?? '#ffffff');
        $fg        = trim($_POST['text_color']  ?? '#333333');
        $enabled   = isset($_POST['enabled']) ? 1 : 0;
        $sort      = (int)($_POST['sort_order'] ?? 0);
        $img_size  = $_POST['image_size'] ?? 'medium';

        $links = [];
        foreach (($_POST['link_text'] ?? []) as $i => $lt) {
            $lt  = trim($lt);
            $lu  = trim($_POST['link_url'][$i] ?? '');
            if ($lu !== '') $links[] = ['text' => $lt, 'url' => $lu];
        }
        $links_json = json_encode($links);

        $youtube_urls = [];
        foreach (($_POST['youtube_url'] ?? []) as $yu) {
            $yu = trim($yu);
            if ($yu !== '') $youtube_urls[] = $yu;
        }
        $youtube_json = json_encode($youtube_urls);

        if (!in_array($page, array_keys($pages_list))) $page = 'home';
        if (!in_array($img_size, ['small','medium','large','full'])) $img_size = 'medium';

        if ($id > 0) {
            $db->prepare('UPDATE custom_sections SET page=?,heading=?,body=?,links=?,youtube_urls=?,bg_color=?,text_color=?,sort_order=?,enabled=?,image_size=? WHERE id=?')
               ->execute([$page,$heading,$body,$links_json,$youtube_json,$bg,$fg,$sort,$enabled,$img_size,$id]);
            redirect('custom-sections.php?edit=' . $id . '&flash=saved');
        } else {
            $max = $db->query('SELECT COALESCE(MAX(sort_order),0)+10 FROM custom_sections')->fetchColumn();
            $db->prepare('INSERT INTO custom_sections (page,heading,body,links,youtube_urls,bg_color,text_color,sort_order,enabled,image_size) VALUES (?,?,?,?,?,?,?,?,?,?)')
               ->execute([$page,$heading,$body,$links_json,$youtube_json,$bg,$fg,$max,1,$img_size]);
            $newId = $db->lastInsertId();
            redirect('custom-sections.php?edit=' . $newId . '&flash=saved');
        }

    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $db->prepare('DELETE FROM custom_sections WHERE id=?')->execute([$id]);
            $db->prepare('DELETE FROM custom_section_images WHERE section_id=?')->execute([$id]);
        }
        redirect('custom-sections.php?flash=deleted');

    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        $db->prepare('UPDATE custom_sections SET enabled = 1 - enabled WHERE id=?')->execute([$id]);
        redirect('custom-sections.php');

    } elseif ($action === 'add_image') {
        $section_id = (int)($_POST['section_id'] ?? 0);
        $image      = trim($_POST['image'] ?? '');

        if ($section_id && !empty($_FILES['image_upload']) && $_FILES['image_upload']['error'] === UPLOAD_ERR_OK) {
            $f = $_FILES['image_upload'];
            $ALLOWED_EXT = ['jpg','jpeg','png','gif','webp','svg'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $ALLOWED_EXT)) {
                $fname = uniqid('sec_', true) . '.' . $ext;
                $dest = dirname(__DIR__) . '/uploads/' . $fname;
                if (move_uploaded_file($f['tmp_name'], $dest)) {
                    $db->prepare('INSERT OR IGNORE INTO media (filename,mime_type,file_size) VALUES (?,?,?)')
                       ->execute([$fname, mime_content_type($dest), $f['size']]);
                    $image = $fname;
                }
            }
        }

        if ($section_id && $image !== '') {
            $max = $db->prepare('SELECT COALESCE(MAX(sort_order),0)+10 FROM custom_section_images WHERE section_id=?');
            $max->execute([$section_id]);
            $db->prepare('INSERT INTO custom_section_images (section_id,image,sort_order) VALUES (?,?,?)')
               ->execute([$section_id, $image, $max->fetchColumn()]);
        }
        redirect('custom-sections.php?edit=' . $section_id . '&flash=saved');

    } elseif ($action === 'delete_image') {
        $id         = (int)($_POST['id'] ?? 0);
        $section_id = (int)($_POST['section_id'] ?? 0);
        if ($id) $db->prepare('DELETE FROM custom_section_images WHERE id=?')->execute([$id]);
        redirect('custom-sections.php?edit=' . $section_id);
    }
}

if (isset($_GET['flash'])) $flash = $_GET['flash'];

$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$edit_row = null;
$edit_images = [];
if ($edit_id) {
    $edit_row = $db->prepare('SELECT * FROM custom_sections WHERE id=?');
    $edit_row->execute([$edit_id]);
    $edit_row = $edit_row->fetch();
    if ($edit_row) {
        $edit_images_stmt = $db->prepare('SELECT * FROM custom_section_images WHERE section_id=? ORDER BY sort_order');
        $edit_images_stmt->execute([$edit_id]);
        $edit_images = $edit_images_stmt->fetchAll();
    }
}

$sections = $db->query(
    'SELECT cs.*, (SELECT COUNT(*) FROM custom_section_images WHERE section_id = cs.id) AS image_count
     FROM custom_sections cs ORDER BY page, sort_order'
)->fetchAll();
$media_files = $db->query("SELECT filename FROM media ORDER BY id DESC")->fetchAll(PDO::FETCH_COLUMN);
$new_images = [];
$ni_dir = dirname(__DIR__) . '/new_images';
if (is_dir($ni_dir)) {
    foreach (scandir($ni_dir) as $f) {
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])) $new_images[] = $f;
    }
}

$cs_page_urls = [
    'home'    => '../index.php',
    'about'   => '../about.php',
    'gallery' => '../gallery.php',
    'team'    => '../team.php',
    'faq'     => '../faq.php',
    'contact' => '../contact.php',
];
$page_title  = 'Custom Sections';
$active_nav  = 'custom-sections';
$preview_url = $cs_page_urls[$edit_row['page'] ?? 'home'] ?? '../index.php';
include '_layout.php';
?>
<style>
.cs-flash-ok  { background:#dcfce7;color:#166534;border:1px solid #bbf7d0;border-radius:6px;padding:10px 16px;margin-bottom:18px;font-size:.85rem; }
.cs-flash-del { background:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:6px;padding:10px 16px;margin-bottom:18px;font-size:.85rem; }

.cs-table { width:100%;border-collapse:collapse;font-size:.83rem; }
.cs-table th { text-align:left;padding:8px 10px;border-bottom:2px solid #e2e8f0;color:#64748b;font-size:.72rem;text-transform:uppercase;letter-spacing:.06em; }
.cs-table td { padding:10px 10px;border-bottom:1px solid #f1f5f9;vertical-align:middle; }
.cs-table tr:hover td { background:#f8fafc; }
.cs-badge { display:inline-block;font-size:.68rem;padding:2px 8px;border-radius:20px;font-weight:600; }
.cs-badge-on  { background:#dcfce7;color:#166534; }
.cs-badge-off { background:#f1f5f9;color:#94a3b8; }
.cs-page-badge { display:inline-block;font-size:.7rem;padding:2px 8px;border-radius:4px;background:#e0f2fe;color:#075985; }

.cs-form-box { background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;margin-bottom:28px; }
.cs-form-box h3 { margin:0 0 20px;font-size:1rem;font-weight:700;color:#1e293b; }
.cs-grid { display:grid;grid-template-columns:1fr 1fr;gap:16px; }
.cs-field { margin-bottom:14px; }
.cs-field label { display:block;font-size:.78rem;font-weight:600;color:#475569;margin-bottom:5px; }
.cs-field input[type=text],.cs-field input[type=url],.cs-field input[type=color],
.cs-field select,.cs-field textarea { width:100%;border:1px solid #cbd5e1;border-radius:6px;padding:7px 10px;font-size:.83rem;box-sizing:border-box;font-family:inherit; }
.cs-field textarea { min-height:90px;resize:vertical; }
.cs-field .color-row { display:flex;gap:8px;align-items:center; }
.cs-field .color-row input[type=color] { width:44px;height:34px;padding:2px;cursor:pointer; }
.cs-field .color-row input[type=text]  { flex:1; }
.cs-preview-thumb { max-width:120px;border-radius:6px;margin-top:6px;display:block; }
.cs-pick-btn { font-size:.72rem;padding:4px 9px;background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;border-radius:5px;cursor:pointer;margin-top:6px; }
.cs-pick-btn:hover { background:#e2e8f0; }
.cs-info { font-size:.72rem;color:#94a3b8;margin-top:3px; }

.cs-empty { text-align:center;padding:40px;color:#94a3b8;font-size:.9rem; }

.cs-image-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px; }
.cs-image-item { position:relative;border-radius:8px;overflow:hidden;border:1px solid #e2e8f0; }
.cs-image-item img { width:100%;aspect-ratio:1;object-fit:cover;display:block; }
.cs-image-item form { position:absolute;top:5px;right:5px; }
.cs-image-remove { background:#ef4444;color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:.68rem;cursor:pointer;line-height:1; }

.cs-link-row { display:flex;gap:6px;margin-bottom:6px; }
.cs-link-row input { flex:1; }
.cs-link-remove { background:#fee2e2;color:#ef4444;border:1px solid #fecaca;border-radius:6px;width:32px;cursor:pointer;font-size:.72rem;flex-shrink:0; }

/* picker modal */
#cs-picker-modal { display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);align-items:center;justify-content:center; }
#cs-picker-modal.open { display:flex; }
.cs-picker-box { background:#fff;border-radius:12px;width:min(700px,95vw);max-height:80vh;display:flex;flex-direction:column;overflow:hidden; }
.cs-picker-head { padding:14px 18px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center; }
.cs-picker-head h3 { margin:0;font-size:.92rem; }
.cs-picker-close { background:none;border:none;font-size:1.3rem;cursor:pointer;color:#64748b; }
.cs-picker-tabs { display:flex;border-bottom:1px solid #e2e8f0; }
.cs-picker-tab { flex:1;padding:9px 0;background:none;border:none;font-size:.8rem;color:#64748b;cursor:pointer;border-bottom:2px solid transparent; }
.cs-picker-tab.active { color:#3b82f6;border-bottom-color:#3b82f6;font-weight:600; }
.cs-picker-grid { padding:14px;overflow-y:auto;display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:7px; }
.cs-picker-thumb { aspect-ratio:1;object-fit:cover;border-radius:5px;cursor:pointer;border:2px solid transparent;width:100%; }
.cs-picker-thumb:hover { border-color:#3b82f6; }
</style>

<?php if ($flash === 'saved'): ?><div class="cs-flash-ok">Section saved.</div><?php endif; ?>
<?php if ($flash === 'deleted'): ?><div class="cs-flash-del">Section deleted.</div><?php endif; ?>

<!-- Add / Edit Form -->
<div class="cs-form-box">
  <h3><?= $edit_row ? 'Edit Section' : 'Add New Section' ?></h3>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="_action" value="save">
    <?php if ($edit_row): ?>
    <input type="hidden" name="id" value="<?= (int)$edit_row['id'] ?>">
    <?php endif; ?>

    <div class="cs-grid">
      <div>
        <div class="cs-field">
          <label>Page</label>
          <select name="page" onchange="if (typeof setPreviewTarget === 'function') setPreviewTarget(CS_PAGE_URLS[this.value] || '../index.php')">
            <?php foreach ($pages_list as $k => $v): ?>
            <option value="<?= h($k) ?>" <?= ($edit_row && $edit_row['page'] === $k) ? 'selected' : '' ?>><?= h($v) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="cs-field">
          <label>Heading / Title</label>
          <input type="text" name="heading" value="<?= h($edit_row['heading'] ?? '') ?>" placeholder="Section heading">
        </div>
        <div class="cs-field">
          <label>Body Text (supports <b>bold</b>, <i>italic</i>, links)</label>
          <textarea name="body" id="cs-body-editor" placeholder="Section body text…" style="display:none"><?= h($edit_row['body'] ?? '') ?></textarea>
          <div id="cs-body-toolbar" style="display:flex;gap:5px;margin-top:6px;flex-wrap:wrap">
            <button type="button" class="cs-pick-btn" onclick="csExec('bold')"><b>B</b></button>
            <button type="button" class="cs-pick-btn" onclick="csExec('italic')"><i>I</i></button>
            <button type="button" class="cs-pick-btn" onclick="csExec('underline')"><u>U</u></button>
            <select class="cs-pick-btn" style="padding:3px 6px" onchange="csExecSize(this.value);this.value=''">
              <option value="">Size</option>
              <?php foreach([12,14,16,18,20,24,28,32,36,42,48,56,64] as $s): ?>
              <option value="<?= $s ?>"><?= $s ?>px</option>
              <?php endforeach; ?>
            </select>
            <input type="color" value="#000000" style="height:28px;width:36px;padding:2px;border:1px solid #cbd5e1;border-radius:4px;cursor:pointer"
                   onchange="csExecColor(this.value)" title="Text colour">
            <button type="button" class="cs-pick-btn" onclick="csClearFormat()">Tx</button>
          </div>
          <div id="cs-body-editable" contenteditable="true"
               style="border:1px solid #cbd5e1;border-radius:6px;padding:10px;min-height:90px;margin-top:8px;font-size:.9rem;line-height:1.6;font-family:inherit"><?= $edit_row ? sh($edit_row['body']) : '' ?></div>
          <p class="cs-info">Type and format here — the textarea above is auto-synced on save.</p>
        </div>
      </div>
      <div>
        <div class="cs-field">
          <label>Images</label>
          <?php if ($edit_row): ?>
          <p class="cs-info">Manage this section's photos in the Images panel below — you can add as many as you like.</p>
          <?php else: ?>
          <p style="background:#fef9c3;color:#854d0e;border:1px solid #fde68a;border-radius:6px;padding:8px 10px;font-size:.78rem;margin:0">&#9888; Click <strong>Save Section</strong> below first — you can't add photos until the section exists. The Images panel will appear right here once it's saved.</p>
          <?php endif; ?>
        </div>
        <div class="cs-field">
          <label>Image Size</label>
          <?php $img_size_val = $edit_row['image_size'] ?? 'medium'; ?>
          <select name="image_size">
            <option value="small"  <?= $img_size_val === 'small'  ? 'selected' : '' ?>>Small</option>
            <option value="medium" <?= $img_size_val === 'medium' ? 'selected' : '' ?>>Medium</option>
            <option value="large"  <?= $img_size_val === 'large'  ? 'selected' : '' ?>>Large</option>
            <option value="full"   <?= $img_size_val === 'full'   ? 'selected' : '' ?>>Full width</option>
          </select>
          <p class="cs-info">Applies to every photo in this section</p>
        </div>
        <div class="cs-field">
          <label>Links (optional buttons)</label>
          <div id="cs-links-list">
            <?php
            $_links = json_decode($edit_row['links'] ?? '[]', true) ?: [];
            foreach ($_links as $_lnk): ?>
            <div class="cs-link-row">
              <input type="text" name="link_text[]" value="<?= h($_lnk['text'] ?? '') ?>" placeholder="Button text, e.g. Learn more">
              <input type="url" name="link_url[]" value="<?= h($_lnk['url'] ?? '') ?>" placeholder="https://... or a page like about.php">
              <button type="button" class="cs-link-remove" onclick="this.closest('.cs-link-row').remove()">&#10005;</button>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="cs-pick-btn" onclick="csAddLinkRow()">+ Add Link</button>
          <p class="cs-info">Each one shows as its own button under the section text</p>
        </div>
        <div class="cs-field">
          <label>YouTube Videos</label>
          <div id="cs-youtube-list">
            <?php
            $_yts = json_decode($edit_row['youtube_urls'] ?? '[]', true) ?: [];
            foreach ($_yts as $_yt): ?>
            <div class="cs-yt-row" style="display:flex;gap:6px;margin-bottom:6px">
              <input type="url" name="youtube_url[]" value="<?= h($_yt) ?>" placeholder="https://www.youtube.com/watch?v=..." style="flex:1">
              <button type="button" class="cs-link-remove" onclick="this.closest('.cs-yt-row').remove()">&#10005;</button>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="cs-pick-btn" onclick="csAddYoutubeRow()">+ Add Video</button>
          <p class="cs-info">Paste any YouTube link — full URL or short link</p>
        </div>
        <div class="cs-field">
          <label>Background Color</label>
          <div class="color-row">
            <input type="color" id="cs-bg-pick" value="<?= h($edit_row['bg_color'] ?? '#ffffff') ?>" onchange="document.getElementById('cs-bg-txt').value=this.value">
            <input type="text" id="cs-bg-txt" name="bg_color" value="<?= h($edit_row['bg_color'] ?? '#ffffff') ?>" oninput="document.getElementById('cs-bg-pick').value=this.value" placeholder="#ffffff">
          </div>
        </div>
        <div class="cs-field">
          <label>Text Color</label>
          <div class="color-row">
            <input type="color" id="cs-fg-pick" value="<?= h($edit_row['text_color'] ?? '#333333') ?>" onchange="document.getElementById('cs-fg-txt').value=this.value">
            <input type="text" id="cs-fg-txt" name="text_color" value="<?= h($edit_row['text_color'] ?? '#333333') ?>" oninput="document.getElementById('cs-fg-pick').value=this.value" placeholder="#333333">
          </div>
        </div>
        <div class="cs-field">
          <label>Display Order</label>
          <input type="text" name="sort_order" value="<?= h((string)($edit_row['sort_order'] ?? 10)) ?>" style="width:80px">
        </div>
        <?php if ($edit_row): ?>
        <div class="cs-field">
          <label><input type="checkbox" name="enabled" value="1" <?= $edit_row['enabled'] ? 'checked' : '' ?>> Visible on site</label>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:8px">
      <button type="submit" class="save-btn">Save Section</button>
      <?php if ($edit_row): ?>
      <a href="custom-sections.php" class="save-btn" style="background:#64748b;text-decoration:none">Cancel</a>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php if ($edit_row): ?>
<!-- Images for this section -->
<div class="cs-form-box">
  <h3>Images for "<?= h($edit_row['heading'] ?: 'this section') ?>"</h3>
  <?php if (!empty($edit_images)): ?>
  <div class="cs-image-grid">
    <?php foreach ($edit_images as $ei): ?>
    <?php $ei_url = strpos($ei['image'], '/') === false ? '../uploads/' . h($ei['image']) : '../' . h($ei['image']); ?>
    <div class="cs-image-item">
      <img src="<?= $ei_url ?>" alt="">
      <form method="post" onsubmit="return confirm('Remove this image?')">
        <input type="hidden" name="_action" value="delete_image">
        <input type="hidden" name="id" value="<?= (int)$ei['id'] ?>">
        <input type="hidden" name="section_id" value="<?= (int)$edit_row['id'] ?>">
        <button type="submit" class="cs-image-remove" title="Remove">&#10005;</button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <p class="cs-info" style="margin-bottom:14px">No images yet.</p>
  <?php endif; ?>

  <div style="display:flex;gap:20px;flex-wrap:wrap;align-items:flex-end;margin-top:10px">
    <form method="post" enctype="multipart/form-data" style="display:flex;gap:8px;align-items:center">
      <input type="hidden" name="_action" value="add_image">
      <input type="hidden" name="section_id" value="<?= (int)$edit_row['id'] ?>">
      <input type="file" name="image_upload" accept="image/*" style="font-size:.78rem">
      <button type="submit" class="cs-pick-btn">Upload &amp; Add</button>
    </form>
    <button type="button" class="cs-pick-btn" onclick="csOpenPicker()">&#128247; Choose from Library</button>
  </div>
</div>
<?php endif; ?>

<!-- Existing sections table -->
<?php if (empty($sections)): ?>
<div class="cs-empty">No custom sections yet. Add one above.</div>
<?php else: ?>
<table class="cs-table">
  <thead>
    <tr>
      <th>Page</th>
      <th>Heading</th>
      <th>Images</th>
      <th>Has Video</th>
      <th>Status</th>
      <th>Order</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($sections as $cs): ?>
  <tr>
    <td><span class="cs-page-badge"><?= h($pages_list[$cs['page']] ?? $cs['page']) ?></span></td>
    <td><?= h(mb_strimwidth($cs['heading'], 0, 50, '…')) ?: '<em style="color:#94a3b8">(no heading)</em>' ?></td>
    <td><?= (int)$cs['image_count'] ?: '—' ?></td>
    <td><?= $cs['youtube_url'] ? '&#9654;' : '—' ?></td>
    <td>
      <form method="post" style="display:inline">
        <input type="hidden" name="_action" value="toggle">
        <input type="hidden" name="id" value="<?= (int)$cs['id'] ?>">
        <button type="submit" class="cs-badge <?= $cs['enabled'] ? 'cs-badge-on' : 'cs-badge-off' ?>" style="cursor:pointer;border:none">
          <?= $cs['enabled'] ? 'Visible' : 'Hidden' ?>
        </button>
      </form>
    </td>
    <td><?= (int)$cs['sort_order'] ?></td>
    <td style="white-space:nowrap">
      <a href="custom-sections.php?edit=<?= (int)$cs['id'] ?>" style="font-size:.78rem;color:#3b82f6;text-decoration:none;margin-right:10px">Edit</a>
      <form method="post" style="display:inline" onsubmit="return confirm('Delete this section?')">
        <input type="hidden" name="_action" value="delete">
        <input type="hidden" name="id" value="<?= (int)$cs['id'] ?>">
        <button type="submit" style="font-size:.78rem;color:#ef4444;background:none;border:none;cursor:pointer;padding:0">Delete</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<!-- Image picker modal -->
<div id="cs-picker-modal">
  <div class="cs-picker-box">
    <div class="cs-picker-head">
      <h3>Choose Image</h3>
      <button class="cs-picker-close" onclick="csClosePicker()">&#10005;</button>
    </div>
    <div class="cs-picker-tabs">
      <button class="cs-picker-tab active" onclick="csSwitchTab('site')" id="cs-tab-site">Site Images</button>
      <button class="cs-picker-tab" onclick="csSwitchTab('uploads')" id="cs-tab-uploads">Uploaded</button>
    </div>
    <div class="cs-picker-grid" id="cs-pane-site">
      <?php foreach ($new_images as $mf): ?>
      <img src="../new_images/<?= h($mf) ?>" class="cs-picker-thumb" title="<?= h($mf) ?>"
           onclick="csPickImg('new_images/<?= h(addslashes($mf)) ?>')">
      <?php endforeach; ?>
    </div>
    <div class="cs-picker-grid" id="cs-pane-uploads" style="display:none">
      <?php foreach ($media_files as $mf): ?>
      <img src="../uploads/<?= h($mf) ?>" class="cs-picker-thumb" title="<?= h($mf) ?>"
           onclick="csPickImg('<?= h(addslashes($mf)) ?>')">
      <?php endforeach; ?>
      <?php if (empty($media_files)): ?>
      <p style="grid-column:1/-1;color:#94a3b8;font-size:.83rem">No uploads yet.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<form method="post" id="cs-add-image-form" style="display:none">
  <input type="hidden" name="_action" value="add_image">
  <input type="hidden" name="section_id" value="<?= (int)($edit_row['id'] ?? 0) ?>">
  <input type="hidden" name="image" id="cs-add-image-path">
</form>

<script>
const CS_PAGE_URLS = <?= json_encode($cs_page_urls) ?>;
// ---- Rich text body editor ----
var _csEditable = document.getElementById('cs-body-editable');
var _csBodyTA   = document.getElementById('cs-body-editor');
var _csSel      = null;

_csEditable.addEventListener('mouseup', function() {
  var s = window.getSelection();
  if (s && !s.isCollapsed) _csSel = s.getRangeAt(0).cloneRange();
});
_csEditable.addEventListener('keyup', function() {
  var s = window.getSelection();
  if (s && !s.isCollapsed) _csSel = s.getRangeAt(0).cloneRange();
});

function csRestoreSel() {
  if (!_csSel) return;
  var s = window.getSelection();
  s.removeAllRanges(); s.addRange(_csSel);
}
function csExec(cmd) {
  _csEditable.focus(); csRestoreSel();
  document.execCommand(cmd, false, null);
}
function csExecSize(px) {
  if (!px) return;
  _csEditable.focus(); csRestoreSel();
  var s = window.getSelection();
  if (!s || s.isCollapsed) return;
  var range = s.getRangeAt(0);
  var span = document.createElement('span');
  span.style.fontSize = px + 'px';
  try { range.surroundContents(span); }
  catch(e) { span.appendChild(range.extractContents()); range.insertNode(span); }
}
function csExecColor(c) {
  _csEditable.focus(); csRestoreSel();
  document.execCommand('foreColor', false, c);
}
function csClearFormat() {
  _csEditable.focus(); csRestoreSel();
  document.execCommand('removeFormat', false, null);
}

// Sync editable → textarea on form submit
document.querySelector('form').addEventListener('submit', function() {
  _csBodyTA.value = _csEditable.innerHTML;
});

// ---- Add image from library picker ----
function csPickImg(fname) {
  document.getElementById('cs-add-image-path').value = fname;
  document.getElementById('cs-add-image-form').submit();
}

// ---- Links list ----
function csAddLinkRow() {
  var row = document.createElement('div');
  row.className = 'cs-link-row';
  row.innerHTML = '<input type="text" name="link_text[]" placeholder="Button text, e.g. Learn more">' +
                   '<input type="url" name="link_url[]" placeholder="https://... or a page like about.php">' +
                   '<button type="button" class="cs-link-remove" onclick="this.closest(\'.cs-link-row\').remove()">&#10005;</button>';
  document.getElementById('cs-links-list').appendChild(row);
}

// ---- YouTube videos list ----
function csAddYoutubeRow() {
  var row = document.createElement('div');
  row.className = 'cs-yt-row';
  row.style.cssText = 'display:flex;gap:6px;margin-bottom:6px';
  row.innerHTML = '<input type="url" name="youtube_url[]" placeholder="https://www.youtube.com/watch?v=..." style="flex:1">' +
                   '<button type="button" class="cs-link-remove" onclick="this.closest(\'.cs-yt-row\').remove()">&#10005;</button>';
  document.getElementById('cs-youtube-list').appendChild(row);
}

// ---- Picker modal ----
function csOpenPicker() { document.getElementById('cs-picker-modal').classList.add('open'); }
function csClosePicker() { document.getElementById('cs-picker-modal').classList.remove('open'); }
function csSwitchTab(t) {
  document.getElementById('cs-pane-site').style.display    = t==='site'    ? '' : 'none';
  document.getElementById('cs-pane-uploads').style.display = t==='uploads' ? '' : 'none';
  document.getElementById('cs-tab-site').classList.toggle('active', t==='site');
  document.getElementById('cs-tab-uploads').classList.toggle('active', t==='uploads');
}
document.getElementById('cs-picker-modal').addEventListener('click', function(e) {
  if (e.target === this) csClosePicker();
});
</script>

<?php include '_layout_end.php'; ?>
