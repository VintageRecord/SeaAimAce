<?php
// Shared CMS chrome for all live-edit-*.php pages
// $cms_page_css  - extra CSS link (e.g. '../About.css')
// $cms_page_key  - page switcher selected value (e.g. 'live-edit-about.php')
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Live Editor | <?= htmlspecialchars($cms_page_title ?? 'Page', ENT_QUOTES, 'UTF-8') ?></title>
<link rel="stylesheet" href="../nicepage.css" media="screen">
<?php if (!empty($cms_page_css)): ?>
<link rel="stylesheet" href="<?= $cms_page_css ?>" media="screen">
<?php endif; ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&family=Montserrat:wght@400;600;700">
<style>
/* ── Editor chrome ── */
:root { --bar-h: 52px; --accent: #E63946; --accent2: #FF6B35; }
#cms-bar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 99999;
    height: var(--bar-h);
    background: #111;
    border-bottom: 1px solid #222;
    display: flex; align-items: center; padding: 0 18px; gap: 12px;
    font-family: 'Segoe UI', system-ui, sans-serif;
    box-shadow: 0 2px 20px rgba(0,0,0,.6);
}
#cms-bar .bar-logo { display:flex; align-items:center; gap:8px; }
#cms-bar .bar-logo svg { width:22px; height:22px; }
#cms-bar .bar-logo span { font-size:.9rem; font-weight:700; color:#fff; letter-spacing:.05em; }
#cms-bar .bar-sep { width:1px; height:24px; background:#2a2a2a; }
#cms-bar .bar-hint { font-size:.75rem; color:#555; }
#cms-bar .bar-spacer { flex:1; }
#cms-bar .save-indicator {
    font-size:.78rem; color:#2ecc71; opacity:0;
    transition: opacity .3s; white-space: nowrap;
}
#cms-bar .save-indicator.visible { opacity:1; }
#cms-bar .save-indicator.error { color: var(--accent); }
.cms-btn {
    padding: 6px 16px; border-radius: 5px; font-size:.8rem; font-weight:600;
    cursor:pointer; border:none; font-family:inherit; transition: background .12s;
}
.cms-btn-outline { background: transparent; color: #aaa; border: 1px solid #333; }
.cms-btn-outline:hover { border-color: #aaa; color: #fff; }
.cms-btn-primary { background: var(--accent); color: #fff; }
.cms-btn-primary:hover { background: #c0303b; }
body { padding-top: var(--bar-h) !important; }
[data-editable] {
    position: relative; outline: none; cursor: text;
    transition: box-shadow .15s; border-radius: 2px;
}
[data-editable]:hover { box-shadow: 0 0 0 2px rgba(230,57,70,.35); }
[data-editable]:focus, [data-editable][data-active] { box-shadow: 0 0 0 2px var(--accent) !important; outline: none; }
[data-editable].saving { box-shadow: 0 0 0 2px rgba(255,107,53,.5) !important; }
[data-editable].saved  { box-shadow: 0 0 0 2px rgba(46,204,113,.5) !important; }
#cms-toolbar {
    position: fixed; z-index: 100000;
    background: #1a1a1a; border: 1px solid #333; border-radius: 8px;
    padding: 5px 6px; display: none; align-items: center; gap: 2px;
    box-shadow: 0 6px 24px rgba(0,0,0,.6); flex-wrap: wrap;
    max-width: 560px; pointer-events: auto;
}
#cms-toolbar.visible { display: flex; }
#cms-toolbar .tb-sep { width:1px; height:20px; background:#333; margin:0 3px; }
.tb-btn {
    padding: 4px 8px; min-width: 28px; height: 28px;
    background: none; border: none; color: #ccc; cursor: pointer;
    border-radius: 4px; font-size:.82rem; font-family:inherit;
    display:flex; align-items:center; justify-content:center;
    transition: background .1s, color .1s; white-space: nowrap;
}
.tb-btn:hover { background:#2a2a2a; color:#fff; }
.tb-btn.active { background:#333; color:var(--accent); }
.tb-select {
    height: 28px; padding: 0 6px;
    background: #222; border: 1px solid #333; color: #ccc;
    border-radius: 4px; font-size:.78rem; font-family:inherit; cursor:pointer;
}
.tb-select:focus { outline:none; border-color: var(--accent); }
.tb-color-wrap { position:relative; display:flex; align-items:center; }
.tb-color {
    width:28px; height:28px; padding:4px; background:none;
    border:none; cursor:pointer; border-radius:4px;
}
.tb-color::-webkit-color-swatch-wrapper { padding:0; }
.tb-color::-webkit-color-swatch { border-radius:3px; border:1px solid #444; }
#cms-link-popup {
    position:fixed; z-index:100001;
    background:#1a1a1a; border:1px solid #333; border-radius:8px;
    padding:12px; display:none; gap:8px; align-items:center;
    box-shadow:0 6px 24px rgba(0,0,0,.6); min-width:300px;
}
#cms-link-popup.visible { display:flex; }
#cms-link-popup input {
    flex:1; background:#111; border:1px solid #333; color:#e0e0e0;
    padding:6px 10px; border-radius:5px; font-size:.82rem; font-family:inherit;
}
#cms-link-popup input:focus { outline:none; border-color:var(--accent); }
#cms-hint {
    position:fixed; bottom:16px; left:50%; transform:translateX(-50%);
    background:#111; border:1px solid #222; border-radius:20px;
    padding:7px 18px; font-size:.75rem; color:#555;
    font-family:system-ui,sans-serif; z-index:99998;
    pointer-events:none; transition:opacity .3s;
}
</style>
