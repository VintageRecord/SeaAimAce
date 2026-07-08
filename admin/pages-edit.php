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
    $css         = $_POST['css_content']           ?? '';
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
                $stmt = $db->prepare('UPDATE pages SET title=?,slug=?,meta_title=?,meta_description=?,status=?,html_content=?,css_content=?,editor_json=?,updated_at=datetime(\'now\') WHERE id=?');
                $stmt->execute([$title, $slug, $meta_title, $meta_desc, $status, $html, $css, $json, $id]);
            } else {
                $stmt = $db->prepare('INSERT INTO pages (title,slug,meta_title,meta_description,status,html_content,css_content,editor_json) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute([$title, $slug, $meta_title, $meta_desc, $status, $html, $css, $json]);
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

/* Template picker modal */
#template-modal {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.75);
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(3px);
}
.tpl-modal-box {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    width: 820px; max-width: 95vw;
    max-height: 88vh;
    display: flex; flex-direction: column;
    overflow: hidden;
}
.tpl-modal-head {
    padding: 20px 24px 14px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}
.tpl-modal-head h2 { font-size: 1.05rem; font-weight: 700; color: #fff; }
.tpl-modal-head p  { font-size: .8rem; color: var(--text-muted); margin-top: 3px; }
.tpl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 14px;
    padding: 20px 24px;
    overflow-y: auto;
}
.tpl-card {
    background: var(--surface2);
    border: 2px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
    transition: border-color .15s, transform .1s;
    overflow: hidden;
    text-align: center;
}
.tpl-card:hover { border-color: var(--accent); transform: translateY(-2px); }
.tpl-card.selected { border-color: var(--accent); }
.tpl-preview {
    height: 130px;
    display: flex; align-items: center; justify-content: center;
    background: #111;
    font-size: 2.4rem;
    border-bottom: 1px solid var(--border);
}
.tpl-info { padding: 10px 12px; }
.tpl-info strong { display: block; font-size: .85rem; color: #fff; }
.tpl-info span   { font-size: .72rem; color: var(--text-muted); }
.tpl-modal-foot {
    padding: 14px 24px;
    border-top: 1px solid var(--border);
    display: flex; justify-content: flex-end; gap: 10px;
    flex-shrink: 0;
}
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

<?php if ($is_new): ?>
<!-- Template picker modal (shown only for new pages) -->
<div id="template-modal">
    <div class="tpl-modal-box">
        <div class="tpl-modal-head">
            <h2>Choose a Template</h2>
            <p>Start with a pre-built layout or begin with a blank canvas.</p>
        </div>
        <div class="tpl-grid" id="tpl-grid"></div>
        <div class="tpl-modal-foot">
            <button class="btn btn-secondary" onclick="pickTemplate(null)">Start Blank</button>
            <button class="btn btn-primary" id="tpl-use-btn" onclick="useSelectedTemplate()" disabled>Use Template</button>
        </div>
    </div>
</div>
<?php endif; ?>

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
        styles: ['../tooplate-forge-style.css', 'body{margin:0;padding:0}']
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
    const css      = editor.getCss();
    const editorJson = JSON.stringify(editor.storeData());

    const fd = new FormData();
    fd.append('title',            title);
    fd.append('slug',             slug);
    fd.append('meta_title',       metaTitle);
    fd.append('meta_description', metaDesc);
    fd.append('status',           status);
    fd.append('html_content',     html);
    fd.append('css_content',      css);
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

// ── Template Picker ──────────────────────────────────────────────────────────
const TEMPLATES = [
    {
        id: 'blank',
        name: 'Blank',
        desc: 'Empty canvas',
        icon: '□',
        html: '<div style="padding:60px 20px;text-align:center;font-family:sans-serif;color:#333;"><h1>New Page</h1><p>Start building your page.</p></div>'
    },
    {
        id: 'landing',
        name: 'Landing Page',
        desc: 'Hero + features + CTA',
        icon: '▣',
        html: `<section style="background:#111;color:#fff;padding:100px 40px;text-align:center;">
  <h1 style="font-size:3rem;margin-bottom:16px;">Your Headline Here</h1>
  <p style="font-size:1.2rem;color:#aaa;max-width:600px;margin:0 auto 32px;">A compelling subheading that explains your value proposition in one or two sentences.</p>
  <a href="#" style="background:#E63946;color:#fff;padding:14px 32px;border-radius:6px;font-weight:700;text-decoration:none;font-size:1rem;">Get Started</a>
</section>
<section style="padding:80px 40px;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:40px;text-align:center;">
  <div><div style="font-size:2rem;margin-bottom:12px;">★</div><h3 style="margin-bottom:8px;">Feature One</h3><p style="color:#666;">Short description of this feature and why it matters to your users.</p></div>
  <div><div style="font-size:2rem;margin-bottom:12px;">◆</div><h3 style="margin-bottom:8px;">Feature Two</h3><p style="color:#666;">Short description of this feature and why it matters to your users.</p></div>
  <div><div style="font-size:2rem;margin-bottom:12px;">●</div><h3 style="margin-bottom:8px;">Feature Three</h3><p style="color:#666;">Short description of this feature and why it matters to your users.</p></div>
</section>
<section style="background:#f5f5f5;padding:80px 40px;text-align:center;">
  <h2 style="font-size:2rem;margin-bottom:16px;">Ready to get started?</h2>
  <p style="color:#666;margin-bottom:28px;">Join thousands of others who have already made the switch.</p>
  <a href="#" style="background:#E63946;color:#fff;padding:14px 32px;border-radius:6px;font-weight:700;text-decoration:none;">Sign Up Free</a>
</section>`
    },
    {
        id: 'about',
        name: 'About Us',
        desc: 'Team + story + values',
        icon: '◉',
        html: `<section style="background:#111;color:#fff;padding:80px 40px;text-align:center;">
  <h1 style="font-size:2.5rem;margin-bottom:16px;">About Us</h1>
  <p style="color:#aaa;max-width:640px;margin:0 auto;">We are a passionate team dedicated to delivering exceptional results for our clients.</p>
</section>
<section style="padding:80px 40px;max-width:900px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">
  <div><h2 style="font-size:1.8rem;margin-bottom:16px;">Our Story</h2><p style="color:#555;line-height:1.8;">Founded in 2020, we started with a simple mission: to make great work accessible to everyone. Over the years we've grown into a team of dedicated professionals who love what they do.</p></div>
  <div style="background:#eee;height:300px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#999;">Image Placeholder</div>
</section>
<section style="background:#f9f9f9;padding:80px 40px;text-align:center;">
  <h2 style="font-size:1.8rem;margin-bottom:48px;">Our Values</h2>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:32px;max-width:900px;margin:0 auto;">
    <div style="background:#fff;padding:28px;border-radius:8px;box-shadow:0 2px 12px rgba(0,0,0,.06);"><h3 style="margin-bottom:10px;">Integrity</h3><p style="color:#666;font-size:.9rem;">We do the right thing, always.</p></div>
    <div style="background:#fff;padding:28px;border-radius:8px;box-shadow:0 2px 12px rgba(0,0,0,.06);"><h3 style="margin-bottom:10px;">Innovation</h3><p style="color:#666;font-size:.9rem;">We embrace new ideas and challenge the status quo.</p></div>
    <div style="background:#fff;padding:28px;border-radius:8px;box-shadow:0 2px 12px rgba(0,0,0,.06);"><h3 style="margin-bottom:10px;">Excellence</h3><p style="color:#666;font-size:.9rem;">We hold ourselves to the highest standard.</p></div>
  </div>
</section>`
    },
    {
        id: 'services',
        name: 'Services',
        desc: 'Service cards + pricing',
        icon: '▦',
        html: `<section style="background:#111;color:#fff;padding:80px 40px;text-align:center;">
  <h1 style="font-size:2.5rem;margin-bottom:16px;">Our Services</h1>
  <p style="color:#aaa;max-width:600px;margin:0 auto;">Everything you need to grow your business, all under one roof.</p>
</section>
<section style="padding:80px 40px;max-width:1100px;margin:0 auto;">
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:28px;">
    <div style="border:1px solid #e5e5e5;border-radius:10px;padding:32px;"><div style="font-size:2.2rem;margin-bottom:16px;">◈</div><h3 style="margin-bottom:10px;">Strategy</h3><p style="color:#666;font-size:.9rem;line-height:1.7;">We craft bespoke strategies tailored to your unique goals and market position.</p><a href="#" style="display:inline-block;margin-top:16px;color:#E63946;font-weight:600;font-size:.88rem;">Learn more →</a></div>
    <div style="border:1px solid #e5e5e5;border-radius:10px;padding:32px;background:#fff;box-shadow:0 4px 24px rgba(0,0,0,.08);"><div style="font-size:2.2rem;margin-bottom:16px;">⬡</div><h3 style="margin-bottom:10px;">Design</h3><p style="color:#666;font-size:.9rem;line-height:1.7;">Beautiful, user-centric designs that convert visitors into loyal customers.</p><a href="#" style="display:inline-block;margin-top:16px;color:#E63946;font-weight:600;font-size:.88rem;">Learn more →</a></div>
    <div style="border:1px solid #e5e5e5;border-radius:10px;padding:32px;"><div style="font-size:2.2rem;margin-bottom:16px;">⬢</div><h3 style="margin-bottom:10px;">Development</h3><p style="color:#666;font-size:.9rem;line-height:1.7;">Robust, scalable development that brings your vision to life with clean code.</p><a href="#" style="display:inline-block;margin-top:16px;color:#E63946;font-weight:600;font-size:.88rem;">Learn more →</a></div>
  </div>
</section>`
    },
    {
        id: 'contact',
        name: 'Contact',
        desc: 'Contact form + map area',
        icon: '✉',
        html: `<section style="background:#111;color:#fff;padding:80px 40px;text-align:center;">
  <h1 style="font-size:2.5rem;margin-bottom:16px;">Contact Us</h1>
  <p style="color:#aaa;">We'd love to hear from you. Send us a message and we'll respond within 24 hours.</p>
</section>
<section style="padding:80px 40px;max-width:900px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:60px;">
  <div>
    <h2 style="font-size:1.4rem;margin-bottom:24px;">Send a Message</h2>
    <form>
      <div style="margin-bottom:16px;"><label style="display:block;font-size:.85rem;color:#555;margin-bottom:6px;">Full Name</label><input type="text" placeholder="Jane Smith" style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:6px;font-size:.95rem;"></div>
      <div style="margin-bottom:16px;"><label style="display:block;font-size:.85rem;color:#555;margin-bottom:6px;">Email</label><input type="email" placeholder="jane@example.com" style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:6px;font-size:.95rem;"></div>
      <div style="margin-bottom:20px;"><label style="display:block;font-size:.85rem;color:#555;margin-bottom:6px;">Message</label><textarea rows="5" placeholder="Your message..." style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:6px;font-size:.95rem;resize:vertical;"></textarea></div>
      <button type="submit" style="background:#E63946;color:#fff;padding:12px 28px;border:none;border-radius:6px;font-weight:700;font-size:.95rem;cursor:pointer;">Send Message</button>
    </form>
  </div>
  <div>
    <h2 style="font-size:1.4rem;margin-bottom:24px;">Get in Touch</h2>
    <p style="color:#555;line-height:1.8;margin-bottom:24px;">Whether you have a question, a project in mind, or just want to say hello — our door is always open.</p>
    <div style="margin-bottom:14px;display:flex;gap:12px;align-items:flex-start;"><span style="font-size:1.1rem;">📍</span><span style="color:#555;">123 Main Street, Suite 100<br>New York, NY 10001</span></div>
    <div style="margin-bottom:14px;display:flex;gap:12px;align-items:center;"><span style="font-size:1.1rem;">📞</span><span style="color:#555;">+1 (555) 000-0000</span></div>
    <div style="display:flex;gap:12px;align-items:center;"><span style="font-size:1.1rem;">✉</span><span style="color:#555;">hello@yourcompany.com</span></div>
  </div>
</section>`
    },
    {
        id: 'faq',
        name: 'FAQ',
        desc: 'Accordion-style Q&A',
        icon: '?',
        html: `<section style="background:#111;color:#fff;padding:80px 40px;text-align:center;">
  <h1 style="font-size:2.5rem;margin-bottom:16px;">Frequently Asked Questions</h1>
  <p style="color:#aaa;max-width:560px;margin:0 auto;">Find answers to common questions below. Still need help? Reach out to our team.</p>
</section>
<section style="padding:80px 40px;max-width:760px;margin:0 auto;">
  <div style="border-bottom:1px solid #eee;padding:24px 0;">
    <h3 style="font-size:1.05rem;margin-bottom:10px;cursor:pointer;">What is your refund policy?</h3>
    <p style="color:#666;line-height:1.8;">We offer a full refund within 30 days of purchase, no questions asked. Simply contact our support team and we'll process it promptly.</p>
  </div>
  <div style="border-bottom:1px solid #eee;padding:24px 0;">
    <h3 style="font-size:1.05rem;margin-bottom:10px;cursor:pointer;">How do I get started?</h3>
    <p style="color:#666;line-height:1.8;">Simply sign up for an account, choose a plan that fits your needs, and follow our quick-start guide. You'll be up and running in minutes.</p>
  </div>
  <div style="border-bottom:1px solid #eee;padding:24px 0;">
    <h3 style="font-size:1.05rem;margin-bottom:10px;cursor:pointer;">Do you offer custom plans?</h3>
    <p style="color:#666;line-height:1.8;">Yes! For teams and enterprises with specific requirements, we offer tailored plans. Get in touch with our sales team to discuss your needs.</p>
  </div>
  <div style="padding:24px 0;">
    <h3 style="font-size:1.05rem;margin-bottom:10px;cursor:pointer;">Is there a free trial?</h3>
    <p style="color:#666;line-height:1.8;">Absolutely. All plans come with a 14-day free trial — no credit card required. Experience the full product before committing.</p>
  </div>
</section>`
    },
];

let selectedTemplateId = null;

function initTemplatePicker() {
    const modal = document.getElementById('template-modal');
    if (!modal) return;

    const grid = document.getElementById('tpl-grid');
    TEMPLATES.forEach(tpl => {
        const card = document.createElement('div');
        card.className = 'tpl-card';
        card.dataset.id = tpl.id;
        card.innerHTML = `<div class="tpl-preview">${tpl.icon}</div><div class="tpl-info"><strong>${tpl.name}</strong><span>${tpl.desc}</span></div>`;
        card.addEventListener('click', () => {
            document.querySelectorAll('.tpl-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            selectedTemplateId = tpl.id;
            document.getElementById('tpl-use-btn').disabled = false;
        });
        card.addEventListener('dblclick', () => { selectedTemplateId = tpl.id; useSelectedTemplate(); });
        grid.appendChild(card);
    });
}

function pickTemplate(id) {
    const modal = document.getElementById('template-modal');
    if (!modal) return;

    const tpl = id ? TEMPLATES.find(t => t.id === id) : null;
    if (tpl) {
        editor.setComponents(tpl.html);
    }
    modal.remove();
}

function useSelectedTemplate() {
    if (selectedTemplateId) pickTemplate(selectedTemplateId);
}

// ── Site Nav & Footer blocks ─────────────────────────────────────────────────
fetch('get-site-blocks.php')
    .then(r => r.json())
    .then(data => {
        const bm = editor.BlockManager;

        bm.add('site-nav', {
            label: 'Site Navigation',
            category: 'Site Blocks',
            content: data.nav,
            media: '<svg viewBox="0 0 24 24" width="40"><path fill="currentColor" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>',
        });

        bm.add('site-footer', {
            label: 'Site Footer',
            category: 'Site Blocks',
            content: data.footer,
            media: '<svg viewBox="0 0 24 24" width="40"><path fill="currentColor" d="M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.89 2-2V5c0-1.11-.89-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4z"/></svg>',
        });
    })
    .catch(() => {}); // silently ignore if endpoint unavailable

// Init after editor is ready
editor.on('load', () => { initTemplatePicker(); });
</script>
</body>
</html>
