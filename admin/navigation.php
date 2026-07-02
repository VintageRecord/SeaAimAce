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

// Detect and auto-fix old FORGE nav links
$existing_labels = $db->query("SELECT label FROM nav_links")->fetchAll(PDO::FETCH_COLUMN);
$forge_labels = ['Spaces', 'Pricing', 'Amenities'];
if (!empty(array_intersect($existing_labels, $forge_labels))) {
    $db->exec('DELETE FROM nav_links');
    $nlst = $db->prepare('INSERT INTO nav_links (label,url,sort_order) VALUES (?,?,?)');
    foreach ($default_nav_links as $i => $nl) $nlst->execute([...$nl, $i]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reset to defaults
    if (isset($_POST['reset_nav'])) {
        $db->exec('DELETE FROM nav_links');
        $nlst = $db->prepare('INSERT INTO nav_links (label,url,sort_order) VALUES (?,?,?)');
        foreach ($default_nav_links as $i => $nl) $nlst->execute([...$nl, $i]);
        header('Location: navigation.php?reset=1');
        exit;
    }

    // Style settings
    foreach (['nav_logo_text','nav_book_btn_text','nav_book_btn_href','nav_bg_color','nav_text_color',
              'footer_copyright','footer_bg_color','footer_text_color'] as $f) {
        if (isset($_POST[$f])) save_setting($f, trim($_POST[$f]));
    }

    // Nav links
    $nl_labels = $_POST['nl_label'] ?? [];
    $nl_urls   = $_POST['nl_url']   ?? [];
    $db->exec('DELETE FROM nav_links');
    $nlst = $db->prepare('INSERT INTO nav_links (label,url,sort_order) VALUES (?,?,?)');
    foreach ($nl_labels as $i => $label) {
        if (trim($label) === '') continue;
        $nlst->execute([trim($label), trim($nl_urls[$i] ?? ''), $i]);
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

    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}

$nav_links      = $db->query('SELECT * FROM nav_links ORDER BY sort_order')->fetchAll();
$footer_columns = $db->query('SELECT * FROM footer_columns ORDER BY sort_order')->fetchAll();

$page_title = 'Navigation & Footer';
$active_nav = 'navigation';
include '_layout.php';
?>

<form method="POST" data-ajax data-live>

<?php if (isset($_GET['reset'])): ?>
<div class="alert alert-success" style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:12px 18px;border-radius:6px;margin-bottom:18px;">
    Navigation reset to camping site defaults.
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Navigation Bar</h2>
        <form method="POST" style="margin:0">
            <input type="hidden" name="reset_nav" value="1">
            <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Reset all nav links to camping site defaults?')">Reset Nav to Defaults</button>
        </form>
    </div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group"><label>Logo Text</label><input type="text" name="nav_logo_text" value="<?= h(setting('nav_logo_text')) ?>"></div>
            <div class="form-group"><label>Book Tour Button Text</label><input type="text" name="nav_book_btn_text" value="<?= h(setting('nav_book_btn_text')) ?>"></div>
            <div class="form-group"><label>Book Tour Button Link</label><input type="text" name="nav_book_btn_href" value="<?= h(setting('nav_book_btn_href')) ?>"></div>
            <div class="form-group"><label>Nav Background Colour (CSS value)</label><input type="text" name="nav_bg_color" value="<?= h(setting('nav_bg_color')) ?>" placeholder="e.g. #111 or rgba(0,0,0,0.9)"></div>
            <div class="form-group"><label>Nav Text Colour</label><input type="text" name="nav_text_color" value="<?= h(setting('nav_text_color')) ?>" placeholder="e.g. #ffffff"></div>
        </div>

        <p class="form-section-title">Nav Links</p>
        <div id="nav-links-list">
        <?php foreach ($nav_links as $nl): ?>
        <div class="item-block" style="margin-bottom:8px">
            <div class="item-block-header">
                <span class="item-block-title"><?= h($nl['label']) ?></span>
                <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
            </div>
            <div class="form-grid">
                <div class="form-group"><label>Label</label><input type="text" name="nl_label[]" value="<?= h($nl['label']) ?>"></div>
                <div class="form-group"><label>URL</label><input type="text" name="nl_url[]" value="<?= h($nl['url']) ?>"></div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="add-item-btn" onclick="addNavLink()">+ Add Nav Link</button>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Footer</h2></div>
    <div class="card-body">
        <div class="form-grid">
            <div class="form-group full-width"><label>Copyright Text</label><input type="text" name="footer_copyright" value="<?= h(setting('footer_copyright')) ?>"></div>
            <div class="form-group"><label>Footer Background Colour</label><input type="text" name="footer_bg_color" value="<?= h(setting('footer_bg_color')) ?>" placeholder="e.g. #0a0a0a"></div>
            <div class="form-group"><label>Footer Text Colour</label><input type="text" name="footer_text_color" value="<?= h(setting('footer_text_color')) ?>" placeholder="e.g. #aaaaaa"></div>
        </div>

        <p class="form-section-title">Footer Columns</p>
        <div id="footer-cols-list">
        <?php foreach ($footer_columns as $ci => $col):
            $col_links = json_decode($col['links'], true) ?: [];
        ?>
        <div class="item-block" data-col-idx="<?= $ci ?>">
            <div class="item-block-header">
                <span class="item-block-title">Column: <?= h($col['heading']) ?></span>
                <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
            </div>
            <div class="form-group">
                <label>Column Heading</label>
                <input type="text" name="fc_heading[]" value="<?= h($col['heading']) ?>">
            </div>
            <p style="font-size:.75rem;color:var(--text-muted);margin-bottom:8px">Links</p>
            <div class="col-links-list" style="margin-bottom:8px">
            <?php foreach ($col_links as $li => $link): ?>
            <div class="item-block" style="margin-bottom:6px;padding:10px 12px">
                <div class="item-block-header" style="margin-bottom:8px">
                    <span style="font-size:.78rem;color:var(--text-muted)">Link <?= $li+1 ?></span>
                    <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
                </div>
                <div class="form-grid">
                    <div class="form-group"><label>Label</label><input type="text" name="fc_link_label[<?= $ci ?>][]" value="<?= h($link[0] ?? '') ?>"></div>
                    <div class="form-group"><label>URL</label><input type="text" name="fc_link_url[<?= $ci ?>][]" value="<?= h($link[1] ?? '') ?>"></div>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
            <button type="button" class="add-item-btn" style="font-size:.75rem;padding:6px" onclick="addColLink(this, <?= $ci ?>)">+ Add Link</button>
        </div>
        <?php endforeach; ?>
        </div>
        <button type="button" class="add-item-btn" onclick="addFooterCol()">+ Add Footer Column</button>
    </div>
</div>

<div class="save-bar">
    <button type="submit" class="btn btn-primary">Save</button>
    <span class="save-status"></span>
</div>
</form>

<template id="nav-link-tpl">
<div class="item-block" style="margin-bottom:8px">
    <div class="item-block-header">
        <span class="item-block-title">New Link</span>
        <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
    </div>
    <div class="form-grid">
        <div class="form-group"><label>Label</label><input type="text" name="nl_label[]"></div>
        <div class="form-group"><label>URL</label><input type="text" name="nl_url[]"></div>
    </div>
</div>
</template>

<script>
let colIdx = <?= count($footer_columns) ?>;

function addNavLink() {
    document.getElementById('nav-links-list').appendChild(
        document.getElementById('nav-link-tpl').content.cloneNode(true)
    );
}

function addFooterCol() {
    const ci = colIdx++;
    const div = document.createElement('div');
    div.className = 'item-block';
    div.dataset.colIdx = ci;
    div.innerHTML = `
        <div class="item-block-header">
            <span class="item-block-title">New Column</span>
            <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
        </div>
        <div class="form-group">
            <label>Column Heading</label>
            <input type="text" name="fc_heading[]">
        </div>
        <p style="font-size:.75rem;color:var(--text-muted);margin-bottom:8px">Links</p>
        <div class="col-links-list" style="margin-bottom:8px"></div>
        <button type="button" class="add-item-btn" style="font-size:.75rem;padding:6px" onclick="addColLink(this,${ci})">+ Add Link</button>`;
    document.getElementById('footer-cols-list').appendChild(div);
}

function addColLink(btn, ci) {
    const list = btn.previousElementSibling;
    const div  = document.createElement('div');
    div.className = 'item-block';
    div.style.cssText = 'margin-bottom:6px;padding:10px 12px';
    div.innerHTML = `
        <div class="item-block-header" style="margin-bottom:8px">
            <span style="font-size:.78rem;color:var(--text-muted)">Link</span>
            <button type="button" class="item-remove" onclick="removeBlock(this)">x</button>
        </div>
        <div class="form-grid">
            <div class="form-group"><label>Label</label><input type="text" name="fc_link_label[${ci}][]"></div>
            <div class="form-group"><label>URL</label><input type="text" name="fc_link_url[${ci}][]"></div>
        </div>`;
    list.appendChild(div);
}
</script>

<?php include '_layout_end.php'; ?>
