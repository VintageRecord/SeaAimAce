<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

$default_nav_links = [
    ['Home',     './'],
    ['About Us', 'about.php'],
    ['Gallery',  'gallery.php'],
    ['Our Team', 'team.php'],
    ['FAQ',      'faq.php'],
    ['Contact',  'contact.php'],
];

// Auto-fix old FORGE nav links
$existing_labels = $db->query("SELECT label FROM nav_links")->fetchAll(PDO::FETCH_COLUMN);
$forge_labels = ['Spaces', 'Pricing', 'Amenities'];
if (!empty(array_intersect($existing_labels, $forge_labels))) {
    $db->exec('DELETE FROM nav_links');
    $nlst = $db->prepare('INSERT INTO nav_links (label,url,sort_order) VALUES (?,?,?)');
    foreach ($default_nav_links as $i => $nl) $nlst->execute([...$nl, $i]);
}

$nav_template_options = [
    'default'  => ['label' => 'Default',  'desc' => 'Logo left, links right, mobile hamburger menu'],
    'centered' => ['label' => 'Centered', 'desc' => 'Logo centered on top, links centered below'],
    'boxed'    => ['label' => 'Boxed',    'desc' => 'Floating rounded rectangle bar, inset from page edges'],
    'split'    => ['label' => 'Split',    'desc' => 'Logo left, links truly centered, CTA button right'],
];
$footer_template_options = [
    'default' => ['label' => 'Default', 'desc' => 'Brand + link columns, bottom bar'],
    'minimal' => ['label' => 'Minimal', 'desc' => 'Single row — logo, copyright, links'],
];

// Hash of the current nav_links table, used to detect edits made elsewhere
// (e.g. Themed Pages' "Add to Nav") between when this form was loaded and submitted.
function nav_links_snapshot(PDO $db): string {
    $rows = $db->query('SELECT id, label, url, sort_order FROM nav_links ORDER BY id')->fetchAll();
    return md5(json_encode($rows));
}

$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? '';

    if ($action === 'reset_nav') {
        $db->exec('DELETE FROM nav_links');
        $nlst = $db->prepare('INSERT INTO nav_links (label,url,sort_order) VALUES (?,?,?)');
        foreach ($default_nav_links as $i => $nl) $nlst->execute([...$nl, $i]);
        header('Location: navigation.php?flash=nav_reset');
        exit;
    }

    // Settings
    foreach (['nav_logo_text','nav_book_btn_text','nav_book_btn_href',
              'nav_bg_color','nav_text_color','nav_accent_color','site_tagline',
              'footer_copyright','footer_bg_color','footer_text_color','footer_accent_color'] as $f) {
        if (isset($_POST[$f])) save_setting($f, trim($_POST[$f]));
    }
    if (isset($_POST['nav_template']) && array_key_exists($_POST['nav_template'], $nav_template_options)) {
        save_setting('nav_template', $_POST['nav_template']);
    }
    if (isset($_POST['footer_template']) && array_key_exists($_POST['footer_template'], $footer_template_options)) {
        save_setting('footer_template', $_POST['footer_template']);
    }

    // Nav links — skipped (with a flash warning) if the table changed since this
    // form was loaded, e.g. via Themed Pages' "Add to Nav", so we don't silently
    // wipe out a link added from another tab.
    $nav_conflict = isset($_POST['nav_snapshot']) && $_POST['nav_snapshot'] !== nav_links_snapshot($db);
    if (!$nav_conflict) {
        $nl_labels = $_POST['nl_label'] ?? [];
        $nl_urls   = $_POST['nl_url']   ?? [];
        $db->exec('DELETE FROM nav_links');
        $nlst = $db->prepare('INSERT INTO nav_links (label,url,sort_order) VALUES (?,?,?)');
        foreach ($nl_labels as $i => $label) {
            if (trim($label) === '') continue;
            $nlst->execute([trim($label), trim($nl_urls[$i] ?? ''), $i]);
        }
    }

    // Footer columns
    $fc_headings = $_POST['fc_heading'] ?? [];
    $fc_llabels  = $_POST['fc_link_label'] ?? [];
    $fc_lurls    = $_POST['fc_link_url']   ?? [];
    $db->exec('DELETE FROM footer_columns');
    $fcst = $db->prepare('INSERT INTO footer_columns (heading,links,sort_order) VALUES (?,?,?)');
    foreach ($fc_headings as $i => $heading) {
        if (trim($heading) === '') continue;
        $links = [];
        foreach ($fc_llabels[$i] ?? [] as $j => $lbl) {
            if (trim($lbl) !== '') $links[] = [trim($lbl), trim($fc_lurls[$i][$j] ?? '')];
        }
        $fcst->execute([trim($heading), json_encode($links), $i]);
    }

    if ($nav_conflict) {
        $nav_conflict_msg = 'Other settings were saved, but Nav Links were not — they changed elsewhere (e.g. Themed Pages "Add to Nav") since this page was loaded. Reload and try again.';
        if (!empty($_SERVER['HTTP_X_AJAX'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $nav_conflict_msg]);
            exit;
        }
        header('Location: navigation.php?flash=nav_conflict');
        exit;
    }

    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    header('Location: navigation.php?flash=saved');
    exit;
}

if (isset($_GET['flash'])) {
    $flash = $_GET['flash'];
}

$nav_links      = $db->query('SELECT * FROM nav_links ORDER BY sort_order')->fetchAll();
$footer_columns = $db->query('SELECT * FROM footer_columns ORDER BY sort_order')->fetchAll();
$nav_snapshot   = nav_links_snapshot($db);

$nav_template    = setting('nav_template', 'default');
$footer_template = setting('footer_template', 'default');

$page_title = 'Navigation & Footer';
$active_nav = 'navigation';
include '_layout.php';
?>

<style>
/* ── Color picker row ── */
.color-row { display:flex; align-items:center; gap:10px; }
.color-row input[type="color"] {
    width:38px; height:38px; padding:2px; border:1px solid var(--border);
    border-radius:6px; cursor:pointer; background:none; flex-shrink:0;
}
.color-row input[type="text"] { flex:1; }
.color-row .color-clear {
    background:none; border:1px solid var(--border); border-radius:5px;
    padding:5px 10px; font-size:.75rem; color:var(--text-muted); cursor:pointer;
    white-space:nowrap;
}
.color-row .color-clear:hover { border-color:#aaa; color:var(--text); }

/* ── Nav link rows ── */
.nl-row {
    display:grid; grid-template-columns:1fr 1fr auto;
    gap:8px; align-items:center; padding:8px 10px;
    background:var(--surface-2,#1e1e1e); border:1px solid var(--border);
    border-radius:6px; margin-bottom:6px;
}
.nl-row input { margin:0; }
.nl-row .drag-handle {
    cursor:grab; color:var(--text-muted); font-size:1rem;
    padding:0 6px 0 0; user-select:none; align-self:center;
}
.nl-row { grid-template-columns:20px 1fr 1fr auto; }
.nl-row.dragging { opacity:.35; }
.nl-row.drag-over-top    { box-shadow:inset 0 2px 0 0 var(--accent,#E63946); }
.nl-row.drag-over-bottom { box-shadow:inset 0 -2px 0 0 var(--accent,#E63946); }

/* ── Footer column card ── */
.fc-card {
    border:1px solid var(--border); border-radius:8px;
    margin-bottom:14px; overflow:hidden;
}
.fc-card-head {
    background:var(--surface-2,#1e1e1e); padding:10px 14px;
    display:flex; align-items:center; gap:10px; border-bottom:1px solid var(--border);
}
.fc-card-head input[type="text"] {
    flex:1; background:transparent; border:none; font-weight:600;
    font-size:.9rem; color:var(--text); padding:0;
}
.fc-card-head input[type="text"]:focus { outline:none; border-bottom:1px solid var(--accent,#E63946); }
.fc-card-body { padding:12px 14px; }
.fc-link-row {
    display:grid; grid-template-columns:1fr 1fr auto;
    gap:8px; align-items:center; margin-bottom:6px;
}
.fc-link-row input { margin:0; }

/* ── Section header with action ── */
.section-head {
    display:flex; align-items:center; justify-content:space-between;
    margin:0 0 12px;
}
.section-head h3 { margin:0; font-size:.95rem; font-weight:600; }

/* ── Template swatch picker ── */
.tpl-swatch-row { display:flex; gap:12px; flex-wrap:wrap; }
.tpl-swatch {
    position:relative; width:180px; border:2px solid var(--border);
    border-radius:8px; padding:12px 14px; cursor:pointer;
    background:var(--surface-2,#1e1e1e); transition:border-color .15s;
}
.tpl-swatch:hover { border-color:#555; }
.tpl-swatch input[type="radio"] { position:absolute; top:10px; right:10px; margin:0; accent-color:var(--accent,#E63946); }
.tpl-swatch input[type="radio"]:checked ~ .tpl-swatch-label { color:#fff; }
.tpl-swatch:has(input:checked) { border-color:var(--accent,#E63946); }
.tpl-swatch-label { display:block; font-size:.85rem; font-weight:600; margin-bottom:4px; padding-right:20px; }
.tpl-swatch-desc { display:block; font-size:.72rem; color:var(--text-muted); line-height:1.4; }
</style>

<?php if ($flash === 'saved'): ?>
<div class="alert alert-success" style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:10px 16px;border-radius:6px;margin-bottom:16px;">
    Changes saved successfully.
</div>
<?php elseif ($flash === 'nav_reset'): ?>
<div class="alert alert-success" style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:10px 16px;border-radius:6px;margin-bottom:16px;">
    Navigation reset to defaults.
</div>
<?php elseif ($flash === 'nav_conflict'): ?>
<div class="alert" style="background:#3a2a0a;border:1px solid #5a4a1a;color:#e0b84a;padding:10px 16px;border-radius:6px;margin-bottom:16px;">
    Other settings were saved, but Nav Links were not — they changed elsewhere (e.g. Themed Pages "Add to Nav") since this page was loaded. Reload and try again.
</div>
<?php endif; ?>

<form method="POST" data-ajax data-live>
<input type="hidden" name="nav_snapshot" value="<?= h($nav_snapshot) ?>">

<!-- ══════════════════════════════════════
     NAVIGATION BAR
════════════════════════════════════════ -->
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h2>Navigation Bar</h2>
    </div>
    <div class="card-body">

        <!-- Layout Template -->
        <p class="form-section-title" style="margin-top:0">Layout Template</p>
        <div class="tpl-swatch-row" style="margin-bottom:20px">
            <?php foreach ($nav_template_options as $key => $opt): ?>
            <label class="tpl-swatch">
                <input type="radio" name="nav_template" value="<?= h($key) ?>" <?= $nav_template === $key ? 'checked' : '' ?>>
                <span class="tpl-swatch-label"><?= h($opt['label']) ?></span>
                <span class="tpl-swatch-desc"><?= h($opt['desc']) ?></span>
            </label>
            <?php endforeach; ?>
        </div>

        <!-- Appearance -->
        <p class="form-section-title">Appearance</p>
        <div class="form-grid">
            <div class="form-group">
                <label>Logo Text <span style="font-size:.75rem;color:var(--text-muted)">(shown beside logo image)</span></label>
                <input type="text" name="nav_logo_text" value="<?= h(setting('nav_logo_text')) ?>" placeholder="e.g. CampForge">
            </div>
            <div class="form-group">
                <label>Background Colour</label>
                <div class="color-row">
                    <input type="color" id="nav_bg_color_pick" value="<?= h(setting('nav_bg_color','#ffffff') ?: '#ffffff') ?>"
                           oninput="document.getElementById('nav_bg_color').value=this.value">
                    <input type="text" id="nav_bg_color" name="nav_bg_color" value="<?= h(setting('nav_bg_color')) ?>" placeholder="e.g. #ffffff or rgba(0,0,0,.9)"
                           oninput="syncColorPick('nav_bg_color_pick',this.value)">
                    <button type="button" class="color-clear" onclick="clearColor('nav_bg_color','nav_bg_color_pick')">Clear</button>
                </div>
            </div>
            <div class="form-group">
                <label>Text Colour</label>
                <div class="color-row">
                    <input type="color" id="nav_text_color_pick" value="<?= h(setting('nav_text_color','#222222') ?: '#222222') ?>"
                           oninput="document.getElementById('nav_text_color').value=this.value">
                    <input type="text" id="nav_text_color" name="nav_text_color" value="<?= h(setting('nav_text_color')) ?>" placeholder="e.g. #222222"
                           oninput="syncColorPick('nav_text_color_pick',this.value)">
                    <button type="button" class="color-clear" onclick="clearColor('nav_text_color','nav_text_color_pick')">Clear</button>
                </div>
            </div>
            <div class="form-group">
                <label>Accent Colour <span style="font-size:.75rem;color:var(--text-muted)">(CTA button &amp; active link)</span></label>
                <div class="color-row">
                    <input type="color" id="nav_accent_color_pick" value="<?= h(setting('nav_accent_color','#E63946') ?: '#E63946') ?>"
                           oninput="document.getElementById('nav_accent_color').value=this.value">
                    <input type="text" id="nav_accent_color" name="nav_accent_color" value="<?= h(setting('nav_accent_color')) ?>" placeholder="e.g. #E63946"
                           oninput="syncColorPick('nav_accent_color_pick',this.value)">
                    <button type="button" class="color-clear" onclick="clearColor('nav_accent_color','nav_accent_color_pick')">Clear</button>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <p class="form-section-title">Call-to-Action Button <span style="font-size:.75rem;font-weight:400;color:var(--text-muted)">(appears at the right end of the nav bar)</span></p>
        <div class="form-grid">
            <div class="form-group">
                <label>Button Label</label>
                <input type="text" name="nav_book_btn_text" value="<?= h(setting('nav_book_btn_text')) ?>" placeholder="e.g. Book a Tour">
            </div>
            <div class="form-group">
                <label>Button Link (URL)</label>
                <input type="text" name="nav_book_btn_href" value="<?= h(setting('nav_book_btn_href')) ?>" placeholder="e.g. contact.php">
            </div>
        </div>
        <p style="font-size:.75rem;color:var(--text-muted);margin-top:-8px">Leave Button Label empty to hide the button.</p>

        <!-- Nav Links -->
        <div class="section-head" style="margin-top:20px">
            <h3>Nav Links <span style="font-size:.75rem;font-weight:400;color:var(--text-muted)">— one row per link</span></h3>
            <button type="button" class="btn btn-secondary btn-sm" onclick="resetNav()" style="font-size:.75rem">↺ Reset to Defaults</button>
        </div>

        <div style="display:grid;grid-template-columns:20px 1fr 1fr auto;gap:8px;padding:0 10px;margin-bottom:4px">
            <span></span>
            <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em">Label</span>
            <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em">URL</span>
            <span></span>
        </div>
        <div id="nav-links-list">
        <?php foreach ($nav_links as $nl): ?>
        <div class="nl-row">
            <span class="drag-handle" draggable="true" title="Drag to reorder">⠿</span>
            <input type="text" name="nl_label[]" value="<?= h($nl['label']) ?>" placeholder="Label" aria-label="Link label">
            <input type="text" name="nl_url[]"   value="<?= h($nl['url']) ?>"   placeholder="URL"   aria-label="Link URL">
            <button type="button" class="item-remove" onclick="removeBlock(this)" title="Remove link">✕</button>
        </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="add-item-btn" style="margin-top:6px" onclick="addNavLink()">+ Add Link</button>
    </div>
</div>

<!-- ══════════════════════════════════════
     FOOTER
════════════════════════════════════════ -->
<div class="card">
    <div class="card-header"><h2>Footer</h2></div>
    <div class="card-body">

        <!-- Layout Template -->
        <p class="form-section-title" style="margin-top:0">Layout Template</p>
        <div class="tpl-swatch-row" style="margin-bottom:20px">
            <?php foreach ($footer_template_options as $key => $opt): ?>
            <label class="tpl-swatch">
                <input type="radio" name="footer_template" value="<?= h($key) ?>" <?= $footer_template === $key ? 'checked' : '' ?>>
                <span class="tpl-swatch-label"><?= h($opt['label']) ?></span>
                <span class="tpl-swatch-desc"><?= h($opt['desc']) ?></span>
            </label>
            <?php endforeach; ?>
        </div>

        <!-- Footer settings -->
        <p class="form-section-title">Appearance & Text</p>
        <div class="form-grid">
            <div class="form-group full-width">
                <label>Copyright Text</label>
                <input type="text" name="footer_copyright" value="<?= h(setting('footer_copyright')) ?>" placeholder="e.g. © 2025 CampForge. All rights reserved.">
            </div>
            <div class="form-group full-width">
                <label>Tagline <span style="font-size:.75rem;color:var(--text-muted)">(short description shown in the footer brand column)</span></label>
                <input type="text" name="site_tagline" value="<?= h(setting('site_tagline')) ?>" placeholder="e.g. Your gateway to the great outdoors.">
            </div>
            <div class="form-group">
                <label>Background Colour</label>
                <div class="color-row">
                    <input type="color" id="footer_bg_color_pick" value="<?= h(setting('footer_bg_color','#1a1a1a') ?: '#1a1a1a') ?>"
                           oninput="document.getElementById('footer_bg_color').value=this.value">
                    <input type="text" id="footer_bg_color" name="footer_bg_color" value="<?= h(setting('footer_bg_color')) ?>" placeholder="e.g. #1a1a1a"
                           oninput="syncColorPick('footer_bg_color_pick',this.value)">
                    <button type="button" class="color-clear" onclick="clearColor('footer_bg_color','footer_bg_color_pick')">Clear</button>
                </div>
            </div>
            <div class="form-group">
                <label>Text Colour</label>
                <div class="color-row">
                    <input type="color" id="footer_text_color_pick" value="<?= h(setting('footer_text_color','#aaaaaa') ?: '#aaaaaa') ?>"
                           oninput="document.getElementById('footer_text_color').value=this.value">
                    <input type="text" id="footer_text_color" name="footer_text_color" value="<?= h(setting('footer_text_color')) ?>" placeholder="e.g. #aaaaaa"
                           oninput="syncColorPick('footer_text_color_pick',this.value)">
                    <button type="button" class="color-clear" onclick="clearColor('footer_text_color','footer_text_color_pick')">Clear</button>
                </div>
            </div>
            <div class="form-group">
                <label>Accent Colour <span style="font-size:.75rem;color:var(--text-muted)">(column headings &amp; link hover)</span></label>
                <div class="color-row">
                    <input type="color" id="footer_accent_color_pick" value="<?= h(setting('footer_accent_color','#E63946') ?: '#E63946') ?>"
                           oninput="document.getElementById('footer_accent_color').value=this.value">
                    <input type="text" id="footer_accent_color" name="footer_accent_color" value="<?= h(setting('footer_accent_color')) ?>" placeholder="e.g. #E63946"
                           oninput="syncColorPick('footer_accent_color_pick',this.value)">
                    <button type="button" class="color-clear" onclick="clearColor('footer_accent_color','footer_accent_color_pick')">Clear</button>
                </div>
            </div>
        </div>

        <!-- Footer columns -->
        <p class="form-section-title" style="margin-top:20px">
            Link Columns
            <span style="font-size:.75rem;font-weight:400;color:var(--text-muted)"> — each column appears as a group of links in the footer</span>
        </p>
        <div id="footer-cols-list">
        <?php foreach ($footer_columns as $ci => $col):
            $col_links = json_decode($col['links'], true) ?: [];
        ?>
        <div class="fc-card" data-col-idx="<?= $ci ?>">
            <div class="fc-card-head">
                <span style="font-size:.78rem;color:var(--text-muted);white-space:nowrap;margin-right:4px">Heading:</span>
                <input type="text" name="fc_heading[]" value="<?= h($col['heading']) ?>" placeholder="e.g. Quick Links">
                <button type="button" class="item-remove" onclick="removeBlock(this)" title="Remove column">✕</button>
            </div>
            <div class="fc-card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:8px;padding:0 2px;margin-bottom:4px">
                    <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em">Link Label</span>
                    <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em">URL</span>
                    <span></span>
                </div>
                <div class="col-links-list">
                <?php foreach ($col_links as $li => $link): ?>
                <div class="fc-link-row">
                    <input type="text" name="fc_link_label[<?= $ci ?>][]" value="<?= h($link[0] ?? '') ?>" placeholder="e.g. About Us" aria-label="Link label">
                    <input type="text" name="fc_link_url[<?= $ci ?>][]"   value="<?= h($link[1] ?? '') ?>" placeholder="e.g. about.php" aria-label="Link URL">
                    <button type="button" class="item-remove" onclick="removeBlock(this)" title="Remove link">✕</button>
                </div>
                <?php endforeach; ?>
                </div>
                <button type="button" class="add-item-btn" style="font-size:.75rem;padding:5px 10px;margin-top:4px" onclick="addColLink(this, <?= $ci ?>)">+ Add Link</button>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="add-item-btn" onclick="addFooterCol()">+ Add Column</button>
    </div>
</div>

<div class="save-bar">
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <span class="save-status"></span>
</div>
</form>

<script>
let colIdx = <?= count($footer_columns) ?>;

// ── Nav link template ──
function addNavLink() {
    const row = document.createElement('div');
    row.className = 'nl-row';
    row.innerHTML = `
        <span class="drag-handle" draggable="true" title="Drag to reorder">⠿</span>
        <input type="text" name="nl_label[]" placeholder="Label" aria-label="Link label">
        <input type="text" name="nl_url[]"   placeholder="URL"   aria-label="Link URL">
        <button type="button" class="item-remove" onclick="removeBlock(this)" title="Remove">✕</button>`;
    document.getElementById('nav-links-list').appendChild(row);
    row.querySelector('input').focus();
}

// ── Nav link drag-to-reorder ──
// Row order in the DOM at submit time is what determines sort_order
// (nl_label[]/nl_url[] are saved in the order they appear in the form),
// so reordering the .nl-row elements here is the entire fix — no hidden
// order field needed.
(function () {
    const list = document.getElementById('nav-links-list');
    let draggedRow = null;

    list.addEventListener('dragstart', e => {
        const handle = e.target.closest('.drag-handle');
        if (!handle) return;
        draggedRow = handle.closest('.nl-row');
        draggedRow.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    });

    list.addEventListener('dragend', () => {
        if (draggedRow) draggedRow.classList.remove('dragging');
        draggedRow = null;
        list.querySelectorAll('.nl-row').forEach(r => r.classList.remove('drag-over-top', 'drag-over-bottom'));
    });

    list.addEventListener('dragover', e => {
        if (!draggedRow) return;
        const row = e.target.closest('.nl-row');
        if (!row || row === draggedRow) return;
        e.preventDefault();
        list.querySelectorAll('.nl-row').forEach(r => r.classList.remove('drag-over-top', 'drag-over-bottom'));
        const before = e.clientY < row.getBoundingClientRect().top + row.offsetHeight / 2;
        row.classList.add(before ? 'drag-over-top' : 'drag-over-bottom');
    });

    list.addEventListener('drop', e => {
        if (!draggedRow) return;
        const row = e.target.closest('.nl-row');
        if (!row || row === draggedRow) return;
        e.preventDefault();
        const before = e.clientY < row.getBoundingClientRect().top + row.offsetHeight / 2;
        row.insertAdjacentElement(before ? 'beforebegin' : 'afterend', draggedRow);
        row.classList.remove('drag-over-top', 'drag-over-bottom');
    });
})();

function resetNav() {
    if (!confirm('Reset all nav links to the camping site defaults?')) return;
    const f = document.createElement('form');
    f.method = 'POST';
    f.innerHTML = '<input name="_action" value="reset_nav">';
    document.body.appendChild(f);
    f.submit();
}

// ── Footer column ──
function addFooterCol() {
    const ci = colIdx++;
    const card = document.createElement('div');
    card.className = 'fc-card';
    card.dataset.colIdx = ci;
    card.innerHTML = `
        <div class="fc-card-head">
            <span style="font-size:.78rem;color:var(--text-muted);white-space:nowrap;margin-right:4px">Heading:</span>
            <input type="text" name="fc_heading[]" placeholder="e.g. Quick Links">
            <button type="button" class="item-remove" onclick="removeBlock(this)" title="Remove column">✕</button>
        </div>
        <div class="fc-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:8px;padding:0 2px;margin-bottom:4px">
                <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em">Link Label</span>
                <span style="font-size:.72rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em">URL</span>
                <span></span>
            </div>
            <div class="col-links-list"></div>
            <button type="button" class="add-item-btn" style="font-size:.75rem;padding:5px 10px;margin-top:4px" onclick="addColLink(this,${ci})">+ Add Link</button>
        </div>`;
    document.getElementById('footer-cols-list').appendChild(card);
    card.querySelector('input').focus();
}

function addColLink(btn, ci) {
    const list = btn.previousElementSibling;
    const row  = document.createElement('div');
    row.className = 'fc-link-row';
    row.innerHTML = `
        <input type="text" name="fc_link_label[${ci}][]" placeholder="e.g. About Us" aria-label="Link label">
        <input type="text" name="fc_link_url[${ci}][]"   placeholder="e.g. about.php" aria-label="Link URL">
        <button type="button" class="item-remove" onclick="removeBlock(this)" title="Remove">✕</button>`;
    list.appendChild(row);
    row.querySelector('input').focus();
}

// ── Color picker helpers ──
function syncColorPick(pickId, val) {
    // Only sync if val looks like a plain hex colour
    if (/^#[0-9a-fA-F]{3,6}$/.test(val.trim())) {
        document.getElementById(pickId).value = val.trim();
    }
}
function clearColor(textId, pickId) {
    document.getElementById(textId).value = '';
    document.getElementById(pickId).value = '#ffffff';
}
</script>

<?php include '_layout_end.php'; ?>
