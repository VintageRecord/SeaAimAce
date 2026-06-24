<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();
$features  = $db->query('SELECT * FROM features ORDER BY sort_order')->fetchAll();
$spaces    = $db->query('SELECT * FROM spaces ORDER BY sort_order')->fetchAll();
$plans     = $db->query('SELECT * FROM pricing_plans ORDER BY sort_order')->fetchAll();
$amenities = $db->query('SELECT * FROM amenities ORDER BY sort_order')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Live Editor | FORGE CMS</title>
<link rel="stylesheet" href="../tooplate-forge-style.css">
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
.cms-btn-outline {
    background: transparent; color: #aaa; border: 1px solid #333;
}
.cms-btn-outline:hover { border-color: #aaa; color: #fff; }
.cms-btn-primary { background: var(--accent); color: #fff; }
.cms-btn-primary:hover { background: #c0303b; }

body { padding-top: var(--bar-h) !important; }

/* ── Editable element states ── */
[data-editable] {
    position: relative;
    outline: none;
    cursor: text;
    transition: box-shadow .15s;
    border-radius: 2px;
}
[data-editable]:hover {
    box-shadow: 0 0 0 2px rgba(230,57,70,.35);
}
[data-editable]:focus,
[data-editable][data-active] {
    box-shadow: 0 0 0 2px var(--accent) !important;
    outline: none;
}
[data-editable].saving { box-shadow: 0 0 0 2px rgba(255,107,53,.5) !important; }
[data-editable].saved  { box-shadow: 0 0 0 2px rgba(46,204,113,.5) !important; }

/* ── Floating toolbar ── */
#cms-toolbar {
    position: fixed;
    z-index: 100000;
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 8px;
    padding: 5px 6px;
    display: none;
    align-items: center;
    gap: 2px;
    box-shadow: 0 6px 24px rgba(0,0,0,.6);
    flex-wrap: wrap;
    max-width: 560px;
    pointer-events: auto;
}
#cms-toolbar.visible { display: flex; }

#cms-toolbar .tb-sep { width:1px; height:20px; background:#333; margin:0 3px; }

.tb-btn {
    padding: 4px 8px; min-width: 28px; height: 28px;
    background: none; border: none; color: #ccc; cursor: pointer;
    border-radius: 4px; font-size:.82rem; font-family:inherit;
    display:flex; align-items:center; justify-content:center;
    transition: background .1s, color .1s;
    white-space: nowrap;
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

/* ── Link popup ── */
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

/* ── Click hint ── */
#cms-hint {
    position:fixed; bottom:16px; left:50%; transform:translateX(-50%);
    background:#111; border:1px solid #222; border-radius:20px;
    padding:7px 18px; font-size:.75rem; color:#555;
    font-family:system-ui,sans-serif; z-index:99998;
    pointer-events:none; transition:opacity .3s;
}
</style>
</head>
<body id="top">

<!-- CMS Top Bar -->
<div id="cms-bar">
    <div class="bar-logo">
        <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 10 L5 30 L15 30 L15 20 L25 20 L25 30 L35 30 L35 10 L25 10 L25 15 L15 15 L15 10 Z" fill="#E63946"/>
            <rect x="18" y="5" width="4" height="30" fill="#FF6B35" opacity="0.8"/>
            <rect x="5" y="18" width="30" height="4" fill="#FF6B35" opacity="0.8"/>
        </svg>
        <span>FORGE</span>
    </div>
    <div class="bar-sep"></div>
    <span class="bar-hint">Click any text to edit — select text to format</span>
    <div class="bar-spacer"></div>
    <span class="save-indicator" id="save-indicator"></span>
    <button class="cms-btn cms-btn-outline" onclick="location.href='index.php'">Dashboard</button>
    <button class="cms-btn cms-btn-primary" onclick="saveAll()">Save All</button>
</div>

<!-- Floating Format Toolbar -->
<div id="cms-toolbar">
    <select class="tb-select" id="tb-font" onchange="execCmd('fontName',this.value)" title="Font family" style="width:110px">
        <option value="">Font</option>
        <option value="inherit">Default</option>
        <option value="'Segoe UI',system-ui,sans-serif">System UI</option>
        <option value="Arial,sans-serif">Arial</option>
        <option value="Georgia,serif">Georgia</option>
        <option value="'Courier New',monospace">Courier New</option>
        <option value="Impact,sans-serif">Impact</option>
        <option value="Verdana,sans-serif">Verdana</option>
        <option value="Trebuchet MS,sans-serif">Trebuchet</option>
    </select>

    <select class="tb-select" id="tb-size" onchange="applyFontSize(this.value)" title="Font size" style="width:68px">
        <option value="">Size</option>
        <?php foreach ([10,12,14,16,18,20,24,28,32,36,42,48,56,64,72,80,96] as $s): ?>
        <option value="<?= $s ?>"><?= $s ?>px</option>
        <?php endforeach; ?>
    </select>

    <div class="tb-sep"></div>

    <button class="tb-btn" id="tb-bold"      onmousedown="e(event)" onclick="execCmd('bold')"          title="Bold (Ctrl+B)"><b>B</b></button>
    <button class="tb-btn" id="tb-italic"    onmousedown="e(event)" onclick="execCmd('italic')"        title="Italic (Ctrl+I)"><i>I</i></button>
    <button class="tb-btn" id="tb-underline" onmousedown="e(event)" onclick="execCmd('underline')"     title="Underline (Ctrl+U)"><u>U</u></button>
    <button class="tb-btn" id="tb-strike"    onmousedown="e(event)" onclick="execCmd('strikeThrough')" title="Strikethrough"><s>S</s></button>

    <div class="tb-sep"></div>

    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('justifyLeft')"   title="Align left">&#8676;</button>
    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('justifyCenter')" title="Center">&#8660;</button>
    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('justifyRight')"  title="Align right">&#8677;</button>

    <div class="tb-sep"></div>

    <div class="tb-color-wrap" title="Text colour">
        <input type="color" class="tb-color" id="tb-color" value="#ffffff" onchange="applyColor(this.value)" title="Text colour">
    </div>
    <div class="tb-color-wrap" title="Highlight colour">
        <input type="color" class="tb-color" id="tb-bg-color" value="#000000" onchange="applyBgColor(this.value)" title="Highlight">
    </div>

    <div class="tb-sep"></div>

    <button class="tb-btn" onmousedown="e(event)" onclick="toggleLinkPopup()" title="Insert link">&#128279;</button>
    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('unlink')" title="Remove link">&#128280;</button>

    <div class="tb-sep"></div>

    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('removeFormat')" title="Clear formatting">Tx</button>
</div>

<!-- Link Popup -->
<div id="cms-link-popup">
    <input type="url" id="link-url-input" placeholder="https://example.com" onkeydown="if(event.key==='Enter')applyLink()">
    <button class="cms-btn cms-btn-primary" style="padding:5px 12px;font-size:.78rem" onmousedown="e(event)" onclick="applyLink()">Apply</button>
    <button class="cms-btn cms-btn-outline" style="padding:5px 12px;font-size:.78rem" onmousedown="e(event)" onclick="closeLinkPopup()">Cancel</button>
</div>

<!-- Hint -->
<div id="cms-hint">Click any highlighted text to edit it</div>

<!-- ═══════════════════════════════════════════════
     SITE HTML (with data-editable attributes)
     ═══════════════════════════════════════════════ -->
<div class="loading-bar"></div>

<nav>
    <div class="nav-container">
        <a href="#top" class="logo">
            <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 10 L5 30 L15 30 L15 20 L25 20 L25 30 L35 30 L35 10 L25 10 L25 15 L15 15 L15 10 Z" fill="#E63946"/>
                <rect x="18" y="5" width="4" height="30" fill="#FF6B35" opacity="0.8"/>
                <rect x="5" y="18" width="30" height="4" fill="#FF6B35" opacity="0.8"/>
            </svg>
            <span data-editable data-type="setting" data-key="nav_logo_text"><?= h(setting('nav_logo_text')) ?></span>
        </a>
        <ul class="nav-links">
            <li><a href="#spaces" class="nav-link">Spaces</a></li>
            <li><a href="#pricing" class="nav-link">Pricing</a></li>
            <li><a href="#amenities" class="nav-link">Amenities</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>
        </ul>
        <a href="#contact" class="book-tour-btn">
            <span data-editable data-type="setting" data-key="nav_book_btn_text"><?= h(setting('nav_book_btn_text')) ?></span>
        </a>
        <div class="menu-toggle"><span></span><span></span><span></span></div>
    </div>
</nav>

<section class="hero">
    <div class="hero-backgrounds">
        <div class="hero-bg"></div><div class="hero-bg"></div><div class="hero-bg"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <h1>
                <span data-editable data-type="setting" data-key="hero_heading_line1"><?= h(setting('hero_heading_line1')) ?></span><br>
                <span data-editable data-type="setting" data-key="hero_heading_line2"><?= h(setting('hero_heading_line2')) ?></span>
            </h1>
            <p class="hero-subtitle" data-editable data-type="setting" data-key="hero_subtitle"><?= h(setting('hero_subtitle')) ?></p>
            <div class="hero-buttons">
                <a href="#pricing" class="btn-primary" data-editable data-type="setting" data-key="hero_btn1_text"><?= h(setting('hero_btn1_text')) ?></a>
                <a href="#contact" class="btn-secondary" data-editable data-type="setting" data-key="hero_btn2_text"><?= h(setting('hero_btn2_text')) ?></a>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="section-header">
            <h2 data-editable data-type="setting" data-key="features_heading"><?= h(setting('features_heading')) ?></h2>
            <p data-editable data-type="setting" data-key="features_subtext"><?= h(setting('features_subtext')) ?></p>
        </div>
        <div class="features-grid">
            <?php foreach ($features as $f): ?>
            <div class="feature-card">
                <div class="feature-icon <?= $f['icon']==='24/7'?'icon-24-7':'' ?>" data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="icon"><?= h($f['icon']) ?></div>
                <h3 data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="title"><?= h($f['title']) ?></h3>
                <p data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="description"><?= h($f['description']) ?></p>
                <div class="feature-stats">
                    <div class="stat-item">
                        <span class="stat-number" data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="stat1_num"><?= h($f['stat1_num']) ?></span>
                        <span class="stat-label"  data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="stat1_label"><?= h($f['stat1_label']) ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="stat2_num"><?= h($f['stat2_num']) ?></span>
                        <span class="stat-label"  data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="stat2_label"><?= h($f['stat2_label']) ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="stat3_num"><?= h($f['stat3_num']) ?></span>
                        <span class="stat-label"  data-editable data-type="feature" data-id="<?= $f['id'] ?>" data-field="stat3_label"><?= h($f['stat3_label']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="spaces" id="spaces">
    <div class="container">
        <div class="section-header">
            <h2 data-editable data-type="setting" data-key="spaces_heading"><?= h(setting('spaces_heading')) ?></h2>
            <p data-editable data-type="setting" data-key="spaces_subtext"><?= h(setting('spaces_subtext')) ?></p>
        </div>
        <div class="spaces-grid">
            <?php foreach ($spaces as $s): ?>
            <div class="space-card">
                <div class="space-thumbnail"></div>
                <div class="space-info">
                    <h3 data-editable data-type="space" data-id="<?= $s['id'] ?>" data-field="title"><?= h($s['title']) ?></h3>
                    <p data-editable data-type="space" data-id="<?= $s['id'] ?>" data-field="description"><?= h($s['description']) ?></p>
                    <div class="space-features">
                        <span class="space-feature-tag" data-editable data-type="space" data-id="<?= $s['id'] ?>" data-field="tag1"><?= h($s['tag1']) ?></span>
                        <span class="space-feature-tag" data-editable data-type="space" data-id="<?= $s['id'] ?>" data-field="tag2"><?= h($s['tag2']) ?></span>
                        <span class="space-feature-tag" data-editable data-type="space" data-id="<?= $s['id'] ?>" data-field="tag3"><?= h($s['tag3']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="pricing" id="pricing">
    <div class="container">
        <div class="section-header">
            <h2 data-editable data-type="setting" data-key="pricing_heading"><?= h(setting('pricing_heading')) ?></h2>
            <p data-editable data-type="setting" data-key="pricing_subtext"><?= h(setting('pricing_subtext')) ?></p>
        </div>
        <div class="pricing-toggle">
            <span class="pricing-toggle-label active" id="monthly-label">Monthly</span>
            <div class="toggle-switch" id="pricing-toggle"></div>
            <span class="pricing-toggle-label" id="yearly-label">Yearly<span class="pricing-badge">Save 20%</span></span>
        </div>
        <div class="pricing-grid">
            <?php foreach ($plans as $plan): ?>
            <div class="pricing-card <?= $plan['is_featured']?'featured':'' ?>">
                <h3 class="plan-name" data-editable data-type="pricing" data-id="<?= $plan['id'] ?>" data-field="name"><?= h($plan['name']) ?></h3>
                <div class="plan-price">$<span class="price-amount" data-monthly="<?= (int)$plan['price_monthly'] ?>" data-yearly="<?= (int)$plan['price_yearly'] ?>"><?= (int)$plan['price_monthly'] ?></span><span class="price-period">/month</span></div>
                <ul class="plan-features">
                    <?php foreach (json_decode($plan['features'],true) as $feat): ?>
                    <li data-editable data-type="pricing-feat" data-id="<?= $plan['id'] ?>"><?= h($feat) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button class="btn-primary" style="width:100%;text-align:center" data-editable data-type="pricing" data-id="<?= $plan['id'] ?>" data-field="button_text"><?= h($plan['button_text']) ?></button>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="special-pricing">
            <h3 data-editable data-type="setting" data-key="pricing_enterprise_heading"><?= h(setting('pricing_enterprise_heading')) ?></h3>
            <p data-editable data-type="setting" data-key="pricing_enterprise_text1"><?= h(setting('pricing_enterprise_text1')) ?></p>
            <p data-editable data-type="setting" data-key="pricing_enterprise_text2"><?= h(setting('pricing_enterprise_text2')) ?></p>
            <a href="#contact" class="btn-primary" data-editable data-type="setting" data-key="pricing_enterprise_btn_text"><?= h(setting('pricing_enterprise_btn_text')) ?></a>
        </div>
    </div>
</section>

<section class="amenities" id="amenities">
    <div class="container">
        <div class="section-header">
            <h2 data-editable data-type="setting" data-key="amenities_heading"><?= h(setting('amenities_heading')) ?></h2>
            <p data-editable data-type="setting" data-key="amenities_subtext"><?= h(setting('amenities_subtext')) ?></p>
        </div>
        <div class="amenities-grid">
            <?php foreach ($amenities as $a): ?>
            <div class="amenity-item">
                <div class="amenity-icon"><?= h($a['icon']) ?></div>
                <h3 data-editable data-type="amenity" data-id="<?= $a['id'] ?>" data-field="title"><?= h($a['title']) ?></h3>
                <p  data-editable data-type="amenity" data-id="<?= $a['id'] ?>" data-field="description"><?= h($a['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="contact" id="contact">
    <div class="container">
        <div class="section-header">
            <h2 data-editable data-type="setting" data-key="contact_heading"><?= h(setting('contact_heading')) ?></h2>
            <p data-editable data-type="setting" data-key="contact_subtext"><?= h(setting('contact_subtext')) ?></p>
        </div>
        <div class="contact-grid">
            <div class="contact-left">
                <div class="contact-info">
                    <h3 data-editable data-type="setting" data-key="contact_visit_heading"><?= h(setting('contact_visit_heading')) ?></h3>
                    <p data-editable data-type="setting" data-key="contact_visit_text"><?= h(setting('contact_visit_text')) ?></p>
                    <ul class="contact-details">
                        <li><strong>Address</strong><span data-editable data-type="setting" data-key="contact_address"><?= h(setting('contact_address')) ?></span></li>
                        <li><strong>Phone</strong><span data-editable data-type="setting" data-key="contact_phone"><?= h(setting('contact_phone')) ?></span></li>
                        <li><strong>Email</strong><span data-editable data-type="setting" data-key="contact_email"><?= h(setting('contact_email')) ?></span></li>
                        <li><strong>Hours</strong><span data-editable data-type="setting" data-key="contact_hours"><?= h(setting('contact_hours')) ?></span></li>
                    </ul>
                    <div class="contact-offer">
                        <strong data-editable data-type="setting" data-key="contact_offer_title"><?= h(setting('contact_offer_title')) ?></strong>
                        <p data-editable data-type="setting" data-key="contact_offer_text"><?= h(setting('contact_offer_text')) ?></p>
                    </div>
                </div>
            </div>
            <div class="contact-form-wrapper">
                <div class="form-header">
                    <h3 data-editable data-type="setting" data-key="contact_form_heading"><?= h(setting('contact_form_heading')) ?></h3>
                    <p data-editable data-type="setting" data-key="contact_form_subtext"><?= h(setting('contact_form_subtext')) ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container">
        <div class="cta-content">
            <h2 data-editable data-type="setting" data-key="cta_heading"><?= h(setting('cta_heading')) ?></h2>
            <p  data-editable data-type="setting" data-key="cta_subtext"><?= h(setting('cta_subtext')) ?></p>
            <div class="cta-buttons">
                <a href="#pricing" class="btn-primary"   data-editable data-type="setting" data-key="cta_btn1_text"><?= h(setting('cta_btn1_text')) ?></a>
                <a href="#contact" class="btn-secondary" data-editable data-type="setting" data-key="cta_btn2_text"><?= h(setting('cta_btn2_text')) ?></a>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="footer-bottom">
            <div class="footer-credits">
                <p data-editable data-type="setting" data-key="footer_copyright"><?= h(setting('footer_copyright')) ?></p>
            </div>
        </div>
    </div>
</footer>

<script src="../tooplate-forge-script.js"></script>
<script>
// ════════════════════════════════════════════════
//  FORGE CMS — Inline Live Editor
// ════════════════════════════════════════════════

const toolbar    = document.getElementById('cms-toolbar');
const linkPopup  = document.getElementById('cms-link-popup');
const indicator  = document.getElementById('save-indicator');
const hint       = document.getElementById('cms-hint');

let savedSelection = null;  // saved range before toolbar interaction
let activeEl       = null;  // currently focused editable element
let pendingSaves   = {};    // { key: {el, type, ...} }
let hideToolbarTimer;

// ── Make all [data-editable] elements contenteditable ──
document.querySelectorAll('[data-editable]').forEach(el => {
    el.contentEditable = 'true';
    el.spellcheck = true;

    el.addEventListener('focus', () => {
        activeEl = el;
        el.dataset.active = '1';
        clearTimeout(hideToolbarTimer);
    });

    el.addEventListener('blur', evt => {
        // Don't hide if focus moved to toolbar
        const toEl = evt.relatedTarget;
        if (toolbar.contains(toEl) || linkPopup.contains(toEl)) return;
        el.removeAttribute('data-active');
        if (activeEl === el) activeEl = null;
        scheduleHideToolbar();
        autoSave(el);
    });

    el.addEventListener('keydown', evt => {
        if (evt.key === 'Enter' && !evt.shiftKey) {
            const tag = el.tagName;
            // For single-line elements, prevent new lines
            if (['SPAN','H1','H2','H3','H4','BUTTON','A'].includes(tag)) {
                evt.preventDefault();
                el.blur();
            }
        }
        // Ctrl+S / Cmd+S
        if ((evt.ctrlKey || evt.metaKey) && evt.key === 's') {
            evt.preventDefault();
            saveAll();
        }
    });

    // Prevent links from navigating
    el.addEventListener('click', evt => {
        if (el.tagName === 'A' || el.closest('a')) evt.preventDefault();
    });
});

// ── Selection → show toolbar ──
document.addEventListener('selectionchange', () => {
    const sel = window.getSelection();
    if (!sel || sel.isCollapsed) {
        scheduleHideToolbar();
        return;
    }
    const range = sel.getRangeAt(0);
    const container = range.commonAncestorContainer;
    const inEditable = container.nodeType === 3
        ? container.parentElement.closest('[data-editable]')
        : container.closest?.('[data-editable]');

    if (!inEditable) { scheduleHideToolbar(); return; }

    clearTimeout(hideToolbarTimer);
    savedSelection = range.cloneRange();
    positionToolbar(range);
    updateToolbarState();
    toolbar.classList.add('visible');
    hint.style.opacity = '0';
});

function scheduleHideToolbar() {
    hideToolbarTimer = setTimeout(() => {
        toolbar.classList.remove('visible');
        closeLinkPopup();
    }, 200);
}

function positionToolbar(range) {
    const rect = range.getBoundingClientRect();
    const tbW  = toolbar.offsetWidth || 520;
    const tbH  = toolbar.offsetHeight || 44;
    let top  = rect.top  - tbH - 10 + window.scrollY;
    let left = rect.left + (rect.width / 2) - (tbW / 2) + window.scrollX;
    if (top < 56) top = rect.bottom + 10 + window.scrollY;
    if (left < 8) left = 8;
    if (left + tbW > window.innerWidth - 8) left = window.innerWidth - tbW - 8;
    toolbar.style.top  = top  + 'px';
    toolbar.style.left = left + 'px';
}

// ── Toolbar state (bold/italic/etc active) ──
function updateToolbarState() {
    document.getElementById('tb-bold')     .classList.toggle('active', document.queryCommandState('bold'));
    document.getElementById('tb-italic')   .classList.toggle('active', document.queryCommandState('italic'));
    document.getElementById('tb-underline').classList.toggle('active', document.queryCommandState('underline'));
    document.getElementById('tb-strike')   .classList.toggle('active', document.queryCommandState('strikeThrough'));
}

// ── execCommand wrapper (restores selection first) ──
function e(evt) { evt.preventDefault(); restoreSelection(); }

function execCmd(cmd, val) {
    restoreSelection();
    document.execCommand(cmd, false, val || null);
    updateToolbarState();
    // Mark active element as pending save
    const sel = window.getSelection();
    if (sel && !sel.isCollapsed) {
        const container = sel.getRangeAt(0).commonAncestorContainer;
        const el = container.nodeType === 3
            ? container.parentElement.closest('[data-editable]')
            : container.closest?.('[data-editable]');
        if (el) markPending(el);
    }
}

function restoreSelection() {
    if (!savedSelection) return;
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(savedSelection);
}

function applyFontSize(px) {
    if (!px) return;
    restoreSelection();
    const sel = window.getSelection();
    if (!sel || sel.isCollapsed) return;
    const range = sel.getRangeAt(0);
    const span  = document.createElement('span');
    span.style.fontSize = px + 'px';
    try { range.surroundContents(span); }
    catch(err) {
        span.appendChild(range.extractContents());
        range.insertNode(span);
    }
    // Mark pending
    const el = span.closest('[data-editable]');
    if (el) markPending(el);
}

function applyColor(val) {
    restoreSelection();
    document.execCommand('foreColor', false, val);
    const el = activeEl || document.querySelector('[data-editable][data-active]');
    if (el) markPending(el);
}

function applyBgColor(val) {
    restoreSelection();
    document.execCommand('hiliteColor', false, val);
    const el = activeEl || document.querySelector('[data-editable][data-active]');
    if (el) markPending(el);
}

// ── Link ──
function toggleLinkPopup() {
    restoreSelection();
    if (linkPopup.classList.contains('visible')) { closeLinkPopup(); return; }
    const sel = window.getSelection();
    const url = sel?.anchorNode?.parentElement?.closest('a')?.href || '';
    document.getElementById('link-url-input').value = url;
    const tbRect = toolbar.getBoundingClientRect();
    linkPopup.style.top  = (tbRect.bottom + 8 + window.scrollY) + 'px';
    linkPopup.style.left = tbRect.left + 'px';
    linkPopup.classList.add('visible');
    document.getElementById('link-url-input').focus();
}
function closeLinkPopup() { linkPopup.classList.remove('visible'); }
function applyLink() {
    const url = document.getElementById('link-url-input').value.trim();
    restoreSelection();
    if (url) document.execCommand('createLink', false, url);
    else     document.execCommand('unlink', false, null);
    closeLinkPopup();
}

// ══════════════════════════════════
//  SAVE LOGIC
// ══════════════════════════════════
function markPending(el) {
    const key = buildKey(el);
    pendingSaves[key] = el;
}

function buildKey(el) {
    const t = el.dataset.type;
    if (t === 'setting')     return `setting:${el.dataset.key}`;
    if (t === 'pricing-feat') return `pricing-feat:${el.dataset.id}:${Array.from(el.parentElement.children).indexOf(el)}`;
    return `${t}:${el.dataset.id}:${el.dataset.field}`;
}

function getPayload(el) {
    const t  = el.dataset.type;
    const val = el.innerHTML; // preserve inline formatting

    if (t === 'setting') return { type: 'setting', key: el.dataset.key, value: val };
    if (t === 'pricing-feat') {
        // Collect all li siblings for this plan
        const items = Array.from(el.parentElement.querySelectorAll('[data-type="pricing-feat"]'));
        return { type: 'pricing-feat', id: el.dataset.id, features: items.map(i => i.innerHTML) };
    }
    return { type: t, id: el.dataset.id, field: el.dataset.field, value: val };
}

async function autoSave(el) {
    markPending(el);
    const payload = getPayload(el);
    el.classList.add('saving');
    try {
        const res  = await fetch('live-edit-save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Ajax': '1' },
            body: JSON.stringify(payload)
        });
        const json = await res.json();
        el.classList.remove('saving');
        if (json.success) {
            el.classList.add('saved');
            setTimeout(() => el.classList.remove('saved'), 1200);
            delete pendingSaves[buildKey(el)];
        } else {
            showIndicator(json.error || 'Save failed', true);
        }
    } catch(err) {
        el.classList.remove('saving');
        showIndicator('Network error', true);
    }
}

async function saveAll() {
    const all = document.querySelectorAll('[data-editable]');
    const seen = new Set();
    const jobs = [];

    all.forEach(el => {
        const key = buildKey(el);
        if (seen.has(key)) return;
        seen.add(key);
        jobs.push(fetch('live-edit-save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Ajax': '1' },
            body: JSON.stringify(getPayload(el))
        }));
    });

    showIndicator('Saving...');
    try {
        await Promise.all(jobs);
        pendingSaves = {};
        showIndicator('All saved');
    } catch(err) {
        showIndicator('Some saves failed', true);
    }
}

function showIndicator(msg, isError) {
    indicator.textContent = msg;
    indicator.classList.toggle('error', !!isError);
    indicator.classList.add('visible');
    if (!isError) setTimeout(() => indicator.classList.remove('visible'), 2500);
}

// Warn before leaving with unsaved changes
window.addEventListener('beforeunload', e => {
    if (Object.keys(pendingSaves).length > 0) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Hide hint after first interaction
document.addEventListener('click', () => { hint.style.opacity = '0'; }, { once: true });
</script>
</body>
</html>
