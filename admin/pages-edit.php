<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

$page = null;
if ($id) {
    $stmt = $db->prepare('SELECT * FROM pages WHERE id = ?');
    $stmt->execute([$id]);
    $page = $stmt->fetch();
    if (!$page) redirect('pages.php');
}

// Save
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']            ?? '');
    $slug        = trim($_POST['slug']             ?? '');
    $meta_title  = trim($_POST['meta_title']       ?? '');
    $meta_desc   = trim($_POST['meta_description'] ?? '');
    $status      = $_POST['status'] === 'published' ? 'published' : 'draft';
    $html        = $_POST['html_content']          ?? '';
    $json        = $_POST['editor_json']           ?? '{}';

    // Auto-generate slug from title if empty
    if ($slug === '' && $title !== '') {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
        $slug = trim($slug, '-');
    }
    $slug = strtolower(preg_replace('/[^a-z0-9\-]/', '', $slug));

    if ($title === '') {
        $error = 'Title is required.';
    } elseif ($slug === '') {
        $error = 'Slug is required.';
    } else {
        try {
            if ($id) {
                $stmt = $db->prepare('UPDATE pages SET title=?,slug=?,meta_title=?,meta_description=?,status=?,html_content=?,editor_json=?,updated_at=datetime(\'now\') WHERE id=?');
                $stmt->execute([$title, $slug, $meta_title, $meta_desc, $status, $html, $json, $id]);
            } else {
                $stmt = $db->prepare('INSERT INTO pages (title,slug,meta_title,meta_description,status,html_content,editor_json) VALUES (?,?,?,?,?,?,?)');
                $stmt->execute([$title, $slug, $meta_title, $meta_desc, $status, $html, $json]);
                $id = $db->lastInsertId();
            }

            if (!empty($_SERVER['HTTP_X_AJAX'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'id' => $id, 'slug' => $slug]);
                exit;
            }
            redirect('pages-edit.php?id=' . $id . '&saved=1');
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'UNIQUE')) {
                $error = 'That slug is already in use. Please choose another.';
            } else {
                $error = 'Database error: ' . $e->getMessage();
            }
            if (!empty($_SERVER['HTTP_X_AJAX'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $error]);
                exit;
            }
        }
    }
}

$is_new     = !$page && !$id;
$page_title = $is_new ? 'New Page' : 'Edit Page: ' . ($page['title'] ?? '');
$active_nav = 'pages';
$show_preview = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($page_title) ?> | FORGE CMS</title>
<link rel="stylesheet" href="style.css">
<!-- GrapesJS -->
<link rel="stylesheet" href="https://unpkg.com/grapesjs@0.21.13/dist/css/grapes.min.css">
<style>
body { overflow: hidden; }
.page-editor-layout { display:flex; height:100vh; overflow:hidden; }
.editor-sidebar {
    width: 300px; flex-shrink: 0;
    background: var(--surface);
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column;
    overflow-y: auto;
}
.editor-sidebar .sidebar-logo { padding: 14px 16px; border-bottom: 1px solid var(--border); display:flex; align-items:center; gap:8px; }
.editor-sidebar .sidebar-logo svg { width:22px; height:22px; }
.editor-sidebar .sidebar-logo span { font-weight:700; color:#fff; font-size:.95rem; }
.editor-sidebar .sidebar-logo small { color: var(--text-muted); font-size:.7rem; }
.editor-sidebar nav a { display:block; padding:8px 16px; color:var(--text-muted); font-size:.83rem; border-left:3px solid transparent; }
.editor-sidebar nav a:hover, .editor-sidebar nav a.active { background:var(--surface2); color:#fff; border-left-color:var(--accent); }
.editor-sidebar .nav-group-label { padding:10px 16px 2px; font-size:.62rem; text-transform:uppercase; letter-spacing:.1em; color:var(--text-muted); font-weight:600; }

.editor-main { flex:1; display:flex; flex-direction:column; min-width:0; overflow:hidden; }

.editor-topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 10px 16px;
    display: flex; align-items: center; gap: 10px;
    flex-shrink: 0;
}
.editor-topbar h1 { font-size:.9rem; font-weight:600; color:#fff; flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.save-status { font-size:.8rem; color: var(--success); opacity:0; transition:opacity .3s; }
.save-status.visible { opacity:1; }
.save-status.error { color:var(--accent); }

.meta-panel {
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
    padding: 12px 16px;
    display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap;
    flex-shrink: 0;
}
.meta-panel .form-group { margin-bottom:0; }
.meta-panel .form-group label { font-size:.72rem; }
.meta-panel .form-group input { padding:6px 10px; font-size:.83rem; }
.meta-panel .slug-field { width:180px; }
.meta-panel .title-field { width:220px; }
.meta-panel .status-toggle { display:flex; align-items:center; gap:6px; font-size:.83rem; color:var(--text-muted); white-space:nowrap; }
.meta-panel .status-toggle input[type=checkbox] { width:auto; accent-color:var(--accent); }
.meta-panel-right { display:flex; align-items:center; gap:8px; margin-left:auto; flex-shrink:0; }

.gjs-wrap { flex:1; overflow:hidden; }
#gjs { height:100%; }

/* GrapesJS dark theme tweaks */
.gjs-one-bg { background-color: #161616; }
.gjs-two-color { color: #e0e0e0; }
.gjs-three-bg { background-color: #1e1e1e; }
.gjs-four-color, .gjs-four-color-h:hover { color: #E63946; }
</style>
</head>
<body>
<div class="page-editor-layout">
    <!-- Sidebar -->
    <aside class="editor-sidebar">
        <div class="sidebar-logo">
            <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 10 L5 30 L15 30 L15 20 L25 20 L25 30 L35 30 L35 10 L25 10 L25 15 L15 15 L15 10 Z" fill="#E63946"/>
                <rect x="18" y="5" width="4" height="30" fill="#FF6B35" opacity="0.8"/>
                <rect x="5" y="18" width="30" height="4" fill="#FF6B35" opacity="0.8"/>
            </svg>
            <div><span>FORGE</span><br><small>Page Editor</small></div>
        </div>
        <nav>
            <div class="nav-group-label">Navigate</div>
            <a href="index.php">Dashboard</a>
            <a href="pages.php" class="active">All Pages</a>
            <div class="nav-group-label">Editor Panels</div>
            <a href="#" onclick="showGjsPanel('blocks');return false" id="panel-blocks-link">Blocks</a>
            <a href="#" onclick="showGjsPanel('styles');return false" id="panel-styles-link">Styles</a>
            <a href="#" onclick="showGjsPanel('traits');return false" id="panel-traits-link">Settings</a>
            <a href="#" onclick="showGjsPanel('layers');return false" id="panel-layers-link">Layers</a>
            <div class="nav-group-label">SEO</div>
        </nav>
        <!-- SEO fields -->
        <div style="padding:12px 16px">
            <div class="form-group">
                <label>Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" value="<?= h($page['meta_title'] ?? '') ?>" placeholder="Leave blank to use page title">
            </div>
            <div class="form-group">
                <label>Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="3" placeholder="Brief description for search engines"><?= h($page['meta_description'] ?? '') ?></textarea>
            </div>
        </div>
    </aside>

    <!-- Main editor -->
    <div class="editor-main">
        <div class="editor-topbar">
            <h1 id="topbar-title"><?= h($is_new ? 'New Page' : ($page['title'] ?? 'Edit Page')) ?></h1>
            <span class="save-status" id="save-status"></span>
            <?php if ($page && $page['slug']): ?>
            <a href="../preview.php?slug=<?= h($page['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm">Preview</a>
            <?php endif; ?>
            <button class="btn btn-primary btn-sm" onclick="savePage()">Save</button>
            <a href="pages.php" class="btn btn-secondary btn-sm">Back</a>
        </div>

        <div class="meta-panel">
            <div class="form-group title-field">
                <label>Page Title *</label>
                <input type="text" id="page_title_input" value="<?= h($page['title'] ?? '') ?>" placeholder="My Page" required oninput="autoSlug(this.value)">
            </div>
            <div class="form-group slug-field">
                <label>Slug</label>
                <input type="text" id="page_slug" value="<?= h($page['slug'] ?? '') ?>" placeholder="my-page" pattern="[a-z0-9\-]+">
            </div>
            <div class="status-toggle">
                <input type="checkbox" id="page_status" <?= (($page['status'] ?? '') === 'published') ? 'checked' : '' ?>>
                <label for="page_status">Published</label>
            </div>
            <div class="meta-panel-right">
                <span style="font-size:.75rem;color:var(--text-muted)" id="device-label">Desktop</span>
                <button class="btn btn-secondary btn-sm" onclick="setDevice('desktop')">D</button>
                <button class="btn btn-secondary btn-sm" onclick="setDevice('tablet')">T</button>
                <button class="btn btn-secondary btn-sm" onclick="setDevice('mobile')">M</button>
            </div>
        </div>

        <div class="gjs-wrap">
            <div id="gjs"></div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/grapesjs@0.21.13/dist/grapes.min.js"></script>
<script src="https://unpkg.com/grapesjs-blocks-basic@1.0.2/dist/index.js"></script>
<script src="https://unpkg.com/grapesjs-preset-webpage@1.0.3/dist/index.js"></script>
<script>
const PAGE_ID   = <?= json_encode($id) ?>;
const INIT_HTML = <?= json_encode($page['html_content'] ?? '') ?>;
const INIT_JSON = <?= json_encode($page['editor_json'] ?? '{}') ?>;

const editor = grapesjs.init({
    container: '#gjs',
    height: '100%',
    width: '100%',
    storageManager: false,
    plugins: ['gjs-blocks-basic', 'grapesjs-preset-webpage'],
    pluginsOpts: {
        'gjs-blocks-basic': {},
        'grapesjs-preset-webpage': {}
    },
    canvas: {
        styles: ['../tooplate-forge-style.css']
    },
    panels: { defaults: [] }
});

// Load saved content
if (INIT_JSON && INIT_JSON !== '{}') {
    try {
        const state = JSON.parse(INIT_JSON);
        editor.loadData(state);
    } catch(e) {
        if (INIT_HTML) editor.setComponents(INIT_HTML);
    }
} else if (INIT_HTML) {
    editor.setComponents(INIT_HTML);
}

// Panel switching
const panels = ['blocks','styles','traits','layers'];
let currentPanel = 'blocks';
function showGjsPanel(name) {
    panels.forEach(p => {
        const el = document.querySelector('.gjs-pn-' + p) || document.querySelector('[class*="gjs-pn-' + p + '"]');
    });
    editor.Panels.getButton('views', name)?.set('active', true);
    document.querySelectorAll('[id^="panel-"]').forEach(a => a.style.color = '');
    const lnk = document.getElementById('panel-' + name + '-link');
    if (lnk) lnk.style.color = '#E63946';
    currentPanel = name;
}

// Device switching
function setDevice(d) {
    const map = { desktop:'Desktop', tablet:'Tablet', mobile:'Mobile portrait' };
    editor.setDevice(map[d] || 'Desktop');
    document.getElementById('device-label').textContent = map[d] || 'Desktop';
}

// Auto-generate slug from title
let slugManuallyEdited = <?= json_encode(!$is_new) ?>;
document.getElementById('page_slug').addEventListener('input', () => { slugManuallyEdited = true; });
function autoSlug(val) {
    document.getElementById('topbar-title').textContent = val || 'New Page';
    if (slugManuallyEdited) return;
    const slug = val.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
    document.getElementById('page_slug').value = slug;
}

// Save
const statusEl = document.getElementById('save-status');
function showStatus(msg, isError) {
    statusEl.textContent = msg;
    statusEl.classList.toggle('error', !!isError);
    statusEl.classList.add('visible');
    if (!isError) setTimeout(() => statusEl.classList.remove('visible'), 2500);
}

async function savePage() {
    const title  = document.getElementById('page_title_input').value.trim();
    const slug   = document.getElementById('page_slug').value.trim();
    const status = document.getElementById('page_status').checked ? 'published' : 'draft';
    const metaTitle = document.getElementById('meta_title').value.trim();
    const metaDesc  = document.getElementById('meta_description').value.trim();

    if (!title) { showStatus('Title is required', true); return; }

    const html     = editor.getHtml();
    const editorJson = JSON.stringify(editor.storeData());

    const fd = new FormData();
    fd.append('title',            title);
    fd.append('slug',             slug);
    fd.append('meta_title',       metaTitle);
    fd.append('meta_description', metaDesc);
    fd.append('status',           status);
    fd.append('html_content',     html);
    fd.append('editor_json',      editorJson);
    if (PAGE_ID) fd.append('id',  PAGE_ID);

    try {
        const res  = await fetch(window.location.pathname + (PAGE_ID ? '?id=' + PAGE_ID : ''), {
            method: 'POST', body: fd, headers: { 'X-Ajax': '1' }
        });
        const json = await res.json();
        if (json.success) {
            showStatus('Saved');
            if (!PAGE_ID && json.id) {
                history.replaceState({}, '', 'pages-edit.php?id=' + json.id);
            }
        } else {
            showStatus(json.error || 'Save failed', true);
        }
    } catch(e) {
        showStatus('Network error', true);
    }
}

// Ctrl+S / Cmd+S
document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') { e.preventDefault(); savePage(); }
});
</script>
</body>
</html>
