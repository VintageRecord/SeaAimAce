<?php
/**
 * Shared custom-sections editor panel.
 * Include at the bottom of any per-page admin editor (site-about.php etc.)
 * Requires: $db, $_sections_page (e.g. 'about'), $active_nav already set.
 *
 * Handles its own POST via ?_sec_action=... so the parent form is not affected.
 */

$_sec_page  = $_sections_page ?? 'home';
$_sec_flash = '';
$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_sec_action'])) {
    $action = $_POST['_sec_action'];

    if ($action === 'save') {
        $id      = (int)($_POST['_sec_id'] ?? 0);
        $heading = trim($_POST['sec_heading']   ?? '');
        $body    = trim($_POST['sec_body']      ?? '');
        $image   = trim($_POST['sec_image']     ?? '');
        $youtube = trim($_POST['sec_youtube']   ?? '');
        $bg      = trim($_POST['sec_bg_color']  ?? '#f4f6f8');
        $fg      = trim($_POST['sec_text_color']?? '#333333');
        $enabled = isset($_POST['sec_enabled']) ? 1 : 0;
        $sort    = (int)($_POST['sec_sort']     ?? 0);

        // Handle image upload
        if (!empty($_FILES['sec_image_upload']) && $_FILES['sec_image_upload']['error'] === UPLOAD_ERR_OK) {
            $f   = $_FILES['sec_image_upload'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])) {
                $fname = uniqid('sec_', true) . '.' . $ext;
                $dest  = dirname(__DIR__) . '/uploads/' . $fname;
                if (move_uploaded_file($f['tmp_name'], $dest)) {
                    $db->prepare('INSERT OR IGNORE INTO media (filename,mime_type,file_size) VALUES (?,?,?)')
                       ->execute([$fname, mime_content_type($dest), $f['size']]);
                    $image = $fname;
                }
            }
        }

        if ($id > 0) {
            $db->prepare('UPDATE custom_sections SET heading=?,body=?,image=?,youtube_url=?,bg_color=?,text_color=?,sort_order=?,enabled=? WHERE id=? AND page=?')
               ->execute([$heading,$body,$image,$youtube,$bg,$fg,$sort,$enabled,$id,$_sec_page]);
        } else {
            $max = $db->query("SELECT COALESCE(MAX(sort_order),0)+10 FROM custom_sections WHERE page='$_sec_page'")->fetchColumn();
            $db->prepare('INSERT INTO custom_sections (page,heading,body,image,youtube_url,bg_color,text_color,sort_order,enabled) VALUES (?,?,?,?,?,?,?,?,1)')
               ->execute([$_sec_page,$heading,$body,$image,$youtube,$bg,$fg,$max]);
        }
        $_sec_flash = 'saved';

    } elseif ($action === 'delete') {
        $id = (int)($_POST['_sec_id'] ?? 0);
        if ($id) $db->prepare('DELETE FROM custom_sections WHERE id=? AND page=?')->execute([$id,$_sec_page]);
        $_sec_flash = 'deleted';

    } elseif ($action === 'toggle') {
        $id = (int)($_POST['_sec_id'] ?? 0);
        if ($id) $db->prepare('UPDATE custom_sections SET enabled=1-enabled WHERE id=? AND page=?')->execute([$id,$_sec_page]);
    }
}

$_sec_edit_id = isset($_GET['sec_edit']) ? (int)$_GET['sec_edit'] : 0;
$_sec_edit    = null;
if ($_sec_edit_id) {
    $s = $db->prepare('SELECT * FROM custom_sections WHERE id=? AND page=?');
    $s->execute([$_sec_edit_id, $_sec_page]);
    $_sec_edit = $s->fetch() ?: null;
}

$_sec_rows = $db->prepare('SELECT * FROM custom_sections WHERE page=? ORDER BY sort_order');
$_sec_rows->execute([$_sec_page]);
$_sec_list = $_sec_rows->fetchAll();

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
<div class="alert alert-success" style="margin-bottom:12px;background:#fee2e2;color:#991b1b;border-color:#fecaca">Section deleted.</div>
<?php endif; ?>

<!-- Existing sections list -->
<?php if (!empty($_sec_list)): ?>
<div style="margin-bottom:18px">
  <?php foreach ($_sec_list as $_sr): ?>
  <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;margin-bottom:8px">
    <div style="flex:1;min-width:0">
      <span style="font-size:.85rem;font-weight:600;color:var(--text)"><?= h($_sr['heading'] ?: '(no heading)') ?></span>
      <?php if ($_sr['image']): ?><span style="font-size:.7rem;color:var(--text-muted);margin-left:8px">&#128247;</span><?php endif; ?>
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
        <label>Image</label>
        <?php
        $_si = $_sec_edit['image'] ?? '';
        $_si_url = $_si ? (strpos($_si,'/') !== false ? '../'.$_si : '../uploads/'.$_si) : '';
        ?>
        <?php if ($_si_url): ?>
        <img src="<?= h($_si_url) ?>" id="sec-img-preview" style="max-width:140px;border-radius:6px;display:block;margin-bottom:6px" alt="">
        <?php else: ?>
        <div id="sec-img-preview" style="display:none"></div>
        <?php endif; ?>
        <input type="file" name="sec_image_upload" accept="image/*" style="font-size:.78rem;margin-bottom:6px" onchange="secPreviewFile(this)">
        <input type="text" name="sec_image" id="sec-img-val" value="<?= h($_si) ?>" placeholder="or pick from library" style="margin-bottom:4px">
        <button type="button" class="sec-tb-btn" onclick="secOpenPicker()">&#128247; Library</button>
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

window.secPreviewFile = function(input) {
  if(!input.files||!input.files[0]) return;
  var r=new FileReader();
  r.onload=function(e) { secShowPreview(e.target.result); };
  r.readAsDataURL(input.files[0]);
};
function secShowPreview(url) {
  var el=document.getElementById('sec-img-preview');
  if(el.tagName==='DIV') {
    var img=document.createElement('img');
    img.id='sec-img-preview';
    img.style.cssText='max-width:140px;border-radius:6px;display:block;margin-bottom:6px';
    el.parentNode.replaceChild(img,el); el=img;
  }
  el.src=url; el.style.display='block';
}

window.secPickImg = function(fname) {
  document.getElementById('sec-img-val').value=fname;
  var url=fname.indexOf('/')===−1?'../uploads/'+fname:'../'+fname;
  secShowPreview(url);
  secClosePicker();
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
