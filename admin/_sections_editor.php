<?php
/**
 * Shared custom-sections editor panel (rendering only).
 * POST handling is done by _sections_handler.php, included at the top of each page.
 */

$_sec_page  = $_sections_page ?? 'home';
$_sec_flash = $_GET['sec_flash'] ?? '';
$db = get_db();

$_sec_edit_id = isset($_GET['sec_edit']) ? (int)$_GET['sec_edit'] : 0;
$_sec_edit    = null;
if ($_sec_edit_id) {
    $s = $db->prepare('SELECT * FROM custom_sections WHERE id=? AND page=?');
    $s->execute([$_sec_edit_id, $_sec_page]);
    $_sec_edit = $s->fetch() ?: null;
}

$_sec_rows = $db->prepare(
    'SELECT cs.*, (SELECT COUNT(*) FROM custom_section_images WHERE section_id = cs.id) AS image_count
     FROM custom_sections cs WHERE page=? ORDER BY sort_order'
);
$_sec_rows->execute([$_sec_page]);
$_sec_list = $_sec_rows->fetchAll();

$_sec_edit_images = [];
if ($_sec_edit) {
    $_sec_img_stmt = $db->prepare('SELECT * FROM custom_section_images WHERE section_id=? ORDER BY sort_order');
    $_sec_img_stmt->execute([$_sec_edit['id']]);
    $_sec_edit_images = $_sec_img_stmt->fetchAll();
}

$_sec_media = $db->query("SELECT filename FROM media ORDER BY id DESC")->fetchAll(PDO::FETCH_COLUMN);
$_sec_new_images = [];
$_sec_ni_dir = dirname(__DIR__) . '/new_images';
if (is_dir($_sec_ni_dir)) {
    foreach (scandir($_sec_ni_dir) as $_f) {
        $ext = strtolower(pathinfo($_f, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])) $_sec_new_images[] = $_f;
    }
}
?>

<div class="form-section-title" style="margin-top:32px">
  Custom Sections
  <span style="font-size:.72rem;font-weight:400;color:#94a3b8;margin-left:8px">Appear at the bottom of the page, just before the footer</span>
</div>

<?php if ($_sec_flash === 'saved'): ?>
<div class="alert alert-success" style="margin-bottom:12px">Section saved. It appears at the <strong>bottom of the live page</strong>, just before the footer. <a href="../<?= h($_sec_page === 'home' ? 'index' : $_sec_page) ?>.php#custom-sections" target="_blank" style="color:var(--accent2)">View on site &rarr;</a></div>
<?php elseif ($_sec_flash === 'deleted'): ?>
<div class="alert alert-danger" style="margin-bottom:12px">Section deleted.</div>
<?php elseif ($_sec_flash === 'toggled'): ?>
<div class="alert alert-success" style="margin-bottom:12px">Section visibility updated.</div>
<?php endif; ?>

<!-- Existing sections list -->
<?php if (!empty($_sec_list)): ?>
<div style="margin-bottom:18px">
  <?php foreach ($_sec_list as $_sr): ?>
  <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;margin-bottom:8px">
    <div style="flex:1;min-width:0">
      <span style="font-size:.85rem;font-weight:600;color:var(--text)"><?= h($_sr['heading'] ?: '(no heading)') ?></span>
      <?php if ($_sr['image_count']): ?><span style="font-size:.7rem;color:var(--text-muted);margin-left:8px">&#128247; <?= (int)$_sr['image_count'] ?></span><?php endif; ?>
      <?php if ($_sr['youtube_url']): ?><span style="font-size:.7rem;color:var(--text-muted);margin-left:4px">&#9654;</span><?php endif; ?>
    </div>
    <form method="post" style="display:inline">
      <input type="hidden" name="_sec_action" value="toggle">
      <input type="hidden" name="_sec_id" value="<?= (int)$_sr['id'] ?>">
      <button type="submit" style="font-size:.7rem;padding:2px 8px;border-radius:10px;border:none;cursor:pointer;background:<?= $_sr['enabled'] ? '#14532d' : '#1e1e1e' ?>;color:<?= $_sr['enabled'] ? '#86efac' : '#888' ?>">
        <?= $_sr['enabled'] ? 'Visible' : 'Hidden' ?>
      </button>
    </form>
    <a href="?sec_edit=<?= (int)$_sr['id'] ?>#sec-editor" style="font-size:.75rem;color:var(--accent2);text-decoration:none">Edit</a>
    <form method="post" style="display:inline" onsubmit="return confirm('Delete this section?')">
      <input type="hidden" name="_sec_action" value="delete">
      <input type="hidden" name="_sec_id" value="<?= (int)$_sr['id'] ?>">
      <button type="submit" style="font-size:.75rem;color:var(--accent);background:none;border:none;cursor:pointer;padding:0">Delete</button>
    </form>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Add / Edit form -->
<div id="sec-editor" style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:20px;margin-bottom:24px">
  <div style="font-size:.85rem;font-weight:700;color:var(--text);margin-bottom:16px">
    <?= $_sec_edit ? 'Edit Section' : 'Add Section' ?>
    <?php if ($_sec_edit): ?>
    <a href="?" style="font-size:.72rem;font-weight:400;color:var(--text-muted);margin-left:10px;text-decoration:none">&#10005; Cancel</a>
    <?php endif; ?>
  </div>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="_sec_action" value="save">
    <input type="hidden" name="_sec_id" value="<?= (int)($_sec_edit['id'] ?? 0) ?>">

    <div class="form-grid">
      <div class="form-group full-width">
        <label>Heading / Title</label>
        <input type="text" name="sec_heading" value="<?= h($_sec_edit['heading'] ?? '') ?>" placeholder="Section title">
      </div>

      <div class="form-group full-width">
        <label>Body Text</label>
        <!-- Mini toolbar -->
        <div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:6px">
          <button type="button" class="sec-tb-btn" onclick="secExec('bold')"><b>B</b></button>
          <button type="button" class="sec-tb-btn" onclick="secExec('italic')"><i>I</i></button>
          <button type="button" class="sec-tb-btn" onclick="secExec('underline')"><u>U</u></button>
          <select class="sec-tb-btn" style="padding:2px 5px;font-size:.75rem" onchange="secExecSize(this.value);this.value=''">
            <option value="">Size</option>
            <?php foreach([12,14,16,18,20,24,28,32,36,42,48,56,64] as $_sz): ?>
            <option value="<?= $_sz ?>"><?= $_sz ?>px</option>
            <?php endforeach; ?>
          </select>
          <input type="color" value="#000000" title="Text colour"
                 style="height:26px;width:32px;padding:2px;border:1px solid #cbd5e1;border-radius:4px;cursor:pointer"
                 onchange="secExecColor(this.value)">
          <button type="button" class="sec-tb-btn" onclick="secClear()">Tx</button>
        </div>
        <div id="sec-editable" contenteditable="true"
             style="border:1px solid var(--border);border-radius:6px;padding:10px;min-height:80px;font-size:.88rem;line-height:1.65;font-family:inherit;background:var(--surface);color:var(--text)"><?= $_sec_edit ? sh($_sec_edit['body']) : '' ?></div>
        <textarea name="sec_body" id="sec-body-ta" style="display:none"><?= h($_sec_edit['body'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label>Images</label>
        <?php if ($_sec_edit): ?>
        <div style="font-size:.72rem;color:#94a3b8">Manage this section's photos in the Images panel below.</div>
        <?php else: ?>
        <div style="font-size:.72rem;color:#94a3b8">Save the section first, then you can add one or more images to it.</div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label>Image Size</label>
        <?php $_sec_img_size = $_sec_edit['image_size'] ?? 'medium'; ?>
        <select name="sec_image_size">
          <option value="small"  <?= $_sec_img_size === 'small'  ? 'selected' : '' ?>>Small</option>
          <option value="medium" <?= $_sec_img_size === 'medium' ? 'selected' : '' ?>>Medium</option>
          <option value="large"  <?= $_sec_img_size === 'large'  ? 'selected' : '' ?>>Large</option>
          <option value="full"   <?= $_sec_img_size === 'full'   ? 'selected' : '' ?>>Full width</option>
        </select>
        <div style="font-size:.72rem;color:#94a3b8;margin-top:3px">Applies to every photo in this section</div>
      </div>

      <div class="form-group">
        <label>Links (optional buttons)</label>
        <div id="sec-links-list">
          <?php
          $_sec_links = json_decode($_sec_edit['links'] ?? '[]', true) ?: [];
          foreach ($_sec_links as $_slnk): ?>
          <div class="sec-link-row" style="display:flex;gap:6px;margin-bottom:6px">
            <input type="text" name="sec_link_text[]" value="<?= h($_slnk['text'] ?? '') ?>" placeholder="Button text, e.g. Learn more" style="flex:1">
            <input type="url" name="sec_link_url[]" value="<?= h($_slnk['url'] ?? '') ?>" placeholder="https://... or a page like about.php" style="flex:1">
            <button type="button" onclick="this.closest('.sec-link-row').remove()" style="background:#fee2e2;color:#ef4444;border:1px solid #fecaca;border-radius:6px;width:32px;cursor:pointer;font-size:.72rem;flex-shrink:0">&#10005;</button>
          </div>
          <?php endforeach; ?>
        </div>
        <button type="button" class="sec-tb-btn" onclick="secAddLinkRow()">+ Add Link</button>
        <div style="font-size:.72rem;color:#94a3b8;margin-top:3px">Each one shows as its own button under the section text</div>
      </div>

      <div class="form-group">
        <label>YouTube URL</label>
        <input type="url" name="sec_youtube" value="<?= h($_sec_edit['youtube_url'] ?? '') ?>" placeholder="https://www.youtube.com/watch?v=...">
        <div style="font-size:.72rem;color:#94a3b8;margin-top:3px">Paste any YouTube link</div>

        <label style="margin-top:12px;display:block">Background Colour</label>
        <div style="display:flex;gap:6px;align-items:center">
          <input type="color" id="sec-bg-pick" value="<?= h($_sec_edit['bg_color'] ?? '#f4f6f8') ?>"
                 onchange="document.getElementById('sec-bg-txt').value=this.value"
                 style="width:38px;height:30px;padding:2px;cursor:pointer;border:1px solid #cbd5e1;border-radius:4px">
          <input type="text" id="sec-bg-txt" name="sec_bg_color" value="<?= h($_sec_edit['bg_color'] ?? '#f4f6f8') ?>"
                 oninput="document.getElementById('sec-bg-pick').value=this.value" style="flex:1">
        </div>

        <label style="margin-top:12px;display:block">Text Colour</label>
        <div style="display:flex;gap:6px;align-items:center">
          <input type="color" id="sec-fg-pick" value="<?= h($_sec_edit['text_color'] ?? '#333333') ?>"
                 onchange="document.getElementById('sec-fg-txt').value=this.value"
                 style="width:38px;height:30px;padding:2px;cursor:pointer;border:1px solid #cbd5e1;border-radius:4px">
          <input type="text" id="sec-fg-txt" name="sec_text_color" value="<?= h($_sec_edit['text_color'] ?? '#333333') ?>"
                 oninput="document.getElementById('sec-fg-pick').value=this.value" style="flex:1">
        </div>

        <label style="margin-top:12px;display:block">Display Order</label>
        <input type="number" name="sec_sort" value="<?= (int)($_sec_edit['sort_order'] ?? 0) ?>" style="width:80px">

        <?php if ($_sec_edit): ?>
        <label style="margin-top:10px;display:flex;align-items:center;gap:6px;cursor:pointer">
          <input type="checkbox" name="sec_enabled" value="1" <?= ($_sec_edit['enabled'] ?? 1) ? 'checked' : '' ?>>
          <span style="font-size:.82rem">Visible on site</span>
        </label>
        <?php endif; ?>
      </div>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top:8px">
      <?= $_sec_edit ? 'Update Section' : 'Add Section' ?>
    </button>
  </form>
</div>

<?php if ($_sec_edit): ?>
<!-- Images for this section -->
<div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:20px;margin-bottom:24px">
  <div style="font-size:.85rem;font-weight:700;color:var(--text);margin-bottom:14px">Images for "<?= h($_sec_edit['heading'] ?: 'this section') ?>"</div>
  <?php if (!empty($_sec_edit_images)): ?>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;margin-bottom:14px">
    <?php foreach ($_sec_edit_images as $_ei): ?>
    <?php $_ei_url = strpos($_ei['image'], '/') === false ? '../uploads/' . h($_ei['image']) : '../' . h($_ei['image']); ?>
    <div style="position:relative;border-radius:8px;overflow:hidden;border:1px solid var(--border)">
      <img src="<?= $_ei_url ?>" alt="" style="width:100%;aspect-ratio:1;object-fit:cover;display:block">
      <form method="post" onsubmit="return confirm('Remove this image?')" style="position:absolute;top:5px;right:5px">
        <input type="hidden" name="_sec_action" value="delete_image">
        <input type="hidden" name="_sec_img_id" value="<?= (int)$_ei['id'] ?>">
        <input type="hidden" name="_sec_id" value="<?= (int)$_sec_edit['id'] ?>">
        <button type="submit" style="background:#ef4444;color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:.68rem;cursor:pointer;line-height:1">&#10005;</button>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:14px">No images yet.</p>
  <?php endif; ?>
  <div style="display:flex;gap:16px;flex-wrap:wrap;align-items:center">
    <form method="post" enctype="multipart/form-data" style="display:flex;gap:8px;align-items:center">
      <input type="hidden" name="_sec_action" value="add_image">
      <input type="hidden" name="_sec_id" value="<?= (int)$_sec_edit['id'] ?>">
      <input type="file" name="sec_image_upload" accept="image/*" style="font-size:.78rem">
      <button type="submit" class="sec-tb-btn">Upload &amp; Add</button>
    </form>
    <button type="button" class="sec-tb-btn" onclick="secOpenPicker()">&#128247; Choose from Library</button>
  </div>
</div>

<form method="post" id="sec-add-image-form" style="display:none">
  <input type="hidden" name="_sec_action" value="add_image">
  <input type="hidden" name="_sec_id" value="<?= (int)$_sec_edit['id'] ?>">
  <input type="hidden" name="sec_new_image" id="sec-add-image-path">
</form>
<?php endif; ?>

<!-- Image picker modal -->
<div id="sec-picker-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.7);align-items:center;justify-content:center">
  <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;width:min(680px,95vw);max-height:80vh;display:flex;flex-direction:column;overflow:hidden">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
      <strong style="font-size:.92rem;color:var(--text)">Choose Image</strong>
      <button onclick="secClosePicker()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:var(--text-muted)">&#10005;</button>
    </div>
    <div style="display:flex;border-bottom:1px solid var(--border)">
      <button class="sec-picker-tab active" onclick="secSwitchTab('site')" id="sec-tab-site" style="flex:1;padding:9px;background:none;border:none;border-bottom:2px solid var(--accent2);font-size:.8rem;color:var(--accent2);cursor:pointer;font-weight:600">Site Images</button>
      <button class="sec-picker-tab" onclick="secSwitchTab('uploads')" id="sec-tab-uploads" style="flex:1;padding:9px;background:none;border:none;border-bottom:2px solid transparent;font-size:.8rem;color:var(--text-muted);cursor:pointer">Uploaded</button>
    </div>
    <div id="sec-pane-site" style="padding:12px;overflow-y:auto;display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:6px;background:var(--surface2)">
      <?php foreach ($_sec_new_images as $_mf): ?>
      <img src="../new_images/<?= h($_mf) ?>" title="<?= h($_mf) ?>"
           style="aspect-ratio:1;object-fit:cover;border-radius:5px;cursor:pointer;border:2px solid transparent;width:100%"
           onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='transparent'"
           onclick="secPickImg('new_images/<?= h(addslashes($_mf)) ?>')">
      <?php endforeach; ?>
    </div>
    <div id="sec-pane-uploads" style="display:none;padding:12px;overflow-y:auto;display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:6px;background:var(--surface2)">
      <?php foreach ($_sec_media as $_mf): ?>
      <img src="../uploads/<?= h($_mf) ?>" title="<?= h($_mf) ?>"
           style="aspect-ratio:1;object-fit:cover;border-radius:5px;cursor:pointer;border:2px solid transparent;width:100%"
           onmouseover="this.style.borderColor='#3b82f6'" onmouseout="this.style.borderColor='transparent'"
           onclick="secPickImg('<?= h(addslashes($_mf)) ?>')">
      <?php endforeach; ?>
      <?php if (empty($_sec_media)): ?>
      <p style="grid-column:1/-1;color:#94a3b8;font-size:.82rem">No uploads yet.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
.sec-tb-btn { font-size:.75rem;padding:3px 8px;background:var(--surface);color:var(--text);border:1px solid var(--border);border-radius:4px;cursor:pointer; }
.sec-tb-btn:hover { background:var(--surface2);border-color:#444; }
</style>

<script>
(function() {
var _ed   = document.getElementById('sec-editable');
var _ta   = document.getElementById('sec-body-ta');
var _sel  = null;

_ed.addEventListener('mouseup', function() { var s=window.getSelection(); if(s&&!s.isCollapsed) _sel=s.getRangeAt(0).cloneRange(); });
_ed.addEventListener('keyup',   function() { var s=window.getSelection(); if(s&&!s.isCollapsed) _sel=s.getRangeAt(0).cloneRange(); });

function secRestore() { if(!_sel) return; var s=window.getSelection(); s.removeAllRanges(); s.addRange(_sel); }

window.secExec = function(cmd) { _ed.focus(); secRestore(); document.execCommand(cmd,false,null); };
window.secExecSize = function(px) {
  if(!px) return; _ed.focus(); secRestore();
  var s=window.getSelection(); if(!s||s.isCollapsed) return;
  var r=s.getRangeAt(0), sp=document.createElement('span');
  sp.style.fontSize=px+'px';
  try { r.surroundContents(sp); } catch(e) { sp.appendChild(r.extractContents()); r.insertNode(sp); }
};
window.secExecColor = function(c) { _ed.focus(); secRestore(); document.execCommand('foreColor',false,c); };
window.secClear = function() { _ed.focus(); secRestore(); document.execCommand('removeFormat',false,null); };

// Sync editable → hidden textarea before parent form submit (and this form)
document.querySelectorAll('form').forEach(function(f) {
  f.addEventListener('submit', function() { _ta.value = _ed.innerHTML; });
});

window.secPickImg = function(fname) {
  var pathEl = document.getElementById('sec-add-image-path');
  var formEl = document.getElementById('sec-add-image-form');
  if (pathEl && formEl) { pathEl.value = fname; formEl.submit(); }
};

window.secAddLinkRow = function() {
  var row = document.createElement('div');
  row.className = 'sec-link-row';
  row.style.cssText = 'display:flex;gap:6px;margin-bottom:6px';
  row.innerHTML = '<input type="text" name="sec_link_text[]" placeholder="Button text, e.g. Learn more" style="flex:1">' +
                   '<input type="url" name="sec_link_url[]" placeholder="https://... or a page like about.php" style="flex:1">' +
                   '<button type="button" onclick="this.closest(\'.sec-link-row\').remove()" style="background:#fee2e2;color:#ef4444;border:1px solid #fecaca;border-radius:6px;width:32px;cursor:pointer;font-size:.72rem;flex-shrink:0">&#10005;</button>';
  document.getElementById('sec-links-list').appendChild(row);
};

window.secOpenPicker  = function() { document.getElementById('sec-picker-modal').style.display='flex'; };
window.secClosePicker = function() { document.getElementById('sec-picker-modal').style.display='none'; };
window.secSwitchTab   = function(t) {
  document.getElementById('sec-pane-site').style.display    = t==='site'    ?'grid':'none';
  document.getElementById('sec-pane-uploads').style.display = t==='uploads' ?'grid':'none';
  document.getElementById('sec-tab-site').style.cssText    += t==='site'    ?';color:#3b82f6;border-bottom-color:#3b82f6':';color:#64748b;border-bottom-color:transparent';
  document.getElementById('sec-tab-uploads').style.cssText += t==='uploads' ?';color:#3b82f6;border-bottom-color:#3b82f6':';color:#64748b;border-bottom-color:transparent';
};

document.getElementById('sec-picker-modal').addEventListener('click', function(e) {
  if(e.target===this) secClosePicker();
});
})();
</script>
