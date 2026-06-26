<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Live Editor | FORGE CMS</title>
<link rel="stylesheet" href="../nicepage.css" media="screen">
<link rel="stylesheet" href="../index.css" media="screen">
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
<body data-path-to-root="../" class="u-body u-clearfix u-xl-mode" data-lang="en">

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

<?php
$_nav_base = '../';
require dirname(__DIR__) . '/_nav.php';
?>

    <section class="skrollable skrollable-between u-align-center u-clearfix u-container-align-center u-image u-shading u-section-1" src="" data-image-width="1622" data-image-height="1080" id="block-1">
      <div class="u-clearfix u-sheet u-sheet-1">
        <h1 class="u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><span data-editable data-type="setting" data-key="home_hero_heading"><?= h(setting('home_hero_heading','Best Camping in the National Park')) ?></span></h1>
        <p class="u-large-text u-text u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500"> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit&nbsp;</p>
        <div class="u-clearfix u-expanded-width-xs u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-align-center-sm u-align-center-xs u-align-right-lg u-align-right-md u-align-right-xl u-container-align-right u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-middle-xs u-valign-top-lg u-valign-top-md u-valign-top-sm u-valign-top-xl u-container-layout-1">
                  <a href="#" class="u-align-right u-border-2 u-border-palette-2-base u-btn u-btn-round u-button-style u-palette-2-base u-radius-50 u-btn-1"> Our story</a>
                </div>
              </div>
              <div class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-container-align-left u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-top u-container-layout-2">
                  <a href="#" class="u-active-white u-align-left u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2">Contact Us</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-1" data-animation-name="customAnimationIn" data-animation-duration="1500">
              <div class="u-container-layout u-similar-container u-container-layout-3"><span class="u-file-icon u-icon u-text-white u-icon-1"><img src="../new_images/2325148-28c38e53.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-3">Trekking</h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-2" data-animation-name="customAnimationIn" data-animation-duration="1500">
              <div class="u-container-layout u-similar-container u-container-layout-4"><span class="u-file-icon u-icon u-text-white u-icon-2"><img src="../new_images/7401471-4294aa1a.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-4">Camping</h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-3" data-animation-name="customAnimationIn" data-animation-duration="1500">
              <div class="u-container-layout u-similar-container u-container-layout-5"><span class="u-file-icon u-icon u-text-white u-icon-3"><img src="../new_images/931077-6ca510ad.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-5"> Beach Tents</h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-4" data-animation-name="customAnimationIn" data-animation-duration="1500">
              <div class="u-container-layout u-similar-container u-container-layout-6"><span class="u-file-icon u-icon u-text-white u-icon-4"><img src="../new_images/2560416-11b1db70.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-6"> News &amp; Events</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-valign-middle-lg u-valign-middle-md u-valign-middle-sm u-valign-middle-xl u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-size-35-lg u-size-35-xl u-size-60-md u-size-60-sm u-size-60-xs">
                <div class="u-layout-col">
                  <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                    <div class="u-container-layout u-valign-middle u-container-layout-1">
                      <h2 class="u-text u-text-1"><span data-editable data-type="setting" data-key="home_sec2_heading"><?= h(setting('home_sec2_heading','10 Amazing Camping Tours')) ?></span></h2>
                      <p class="u-text u-text-2"> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </p>
                      <a href="#" class="u-active-palette-2-light-1 u-border-none u-btn u-btn-round u-button-style u-hover-palette-2-light-1 u-palette-2-base u-radius-50 u-text-active-white u-text-body-alt-color u-text-hover-white u-btn-2">learn more</a>
                    </div>
                  </div>
                  <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                    <div class="u-container-layout u-valign-top u-container-layout-2">
                      <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-gallery u-layout-grid u-lightbox u-no-transition u-show-text-none u-gallery-1">
                        <div class="u-gallery-inner u-gallery-inner-1">
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="887" data-image-height="887">
                              <img class="u-back-image u-expanded" src="../new_images/bnnnb.jpg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-1"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="696" data-image-height="696">
                              <img class="u-back-image u-expanded" src="../new_images/nbbnbnnnnnnnnn.jpg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-2"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="700" data-image-height="976">
                              <img class="u-back-image u-expanded" src="../new_images/b4f5b21c-2998-57d4-57d9-d0089b671caa.jpg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-3"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="1380" data-image-height="987">
                              <img class="u-back-image u-expanded" src="../new_images/breathtaking-scenery-snowy-rocks-cloudy-sky-dolomiten-italy_181624-12706.webp">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-4"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="1920" data-image-height="737">
                              <img class="u-back-image u-expanded" src="../new_images/cvcvcv-min.jpg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-5"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="720" data-image-height="1080">
                              <img class="u-back-image u-expanded" src="../new_images/d3e5609c-0bf4-4df0-853d-5cced0ca48e1.jpeg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-6"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="u-size-25-lg u-size-25-xl u-size-60-md u-size-60-sm u-size-60-xs">
                <div class="u-layout-col">
                  <div class="u-container-style u-image u-layout-cell u-size-60 u-image-1" data-image-width="717" data-image-height="1080" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                    <div class="u-container-layout u-container-layout-3"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-palette-2-base u-section-3" id="block-3">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-container-layout u-container-layout-1">
                  <h3 class="u-text u-text-1"><span data-editable data-type="setting" data-key="home_amenities_heading"><?= h(setting('home_amenities_heading','Available to campsite guests:')) ?></span></h3>
                  <ul class="u-custom-list u-file-icon u-text u-text-2">
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div> store (with eco products)</li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div>children's playground with a climbing wall</li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div>climbing tower * (8 m high)</li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div>volleyball court</li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div>bike hire (also for children)</li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div>internet access</li>
                  </ul>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                <div class="u-container-layout u-container-layout-2">
                  <h3 class="u-text u-text-3"> In the campsite, you can:</h3>
                  <ul class="u-custom-list u-text u-text-4">
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div> hire a climbing instructor</li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div>buy kayaking permits</li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div>tandem paragliding available</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-section-4" id="block-4">
      <div class="u-container-style u-expanded-width u-group u-image u-shading u-image-1" data-image-width="1620" data-image-height="1080">
        <div class="u-container-layout u-valign-top u-container-layout-1">
          <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><span data-editable data-type="setting" data-key="home_activities_heading"><?= h(setting('home_activities_heading','Our Services')) ?></span></h2>
        </div>
      </div>
      <div class="u-list u-list-1">
        <div class="u-repeater u-repeater-1">
          <div class="u-align-center u-border-1 u-border-palette-2-base u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-white u-list-item-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
            <div class="u-container-layout u-similar-container u-valign-top u-container-layout-2">
              <img class="u-expanded-width u-image u-image-default u-image-2" src="../new_images/32.jpg" alt="" data-image-width="900" data-image-height="600">
              <h4 class="u-hover-feature u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">Sport Activities</h4>
              <p class="u-hover-feature u-text u-text-3">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
              <a href="#" class="u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-hover-feature u-none u-radius-0 u-text-active-palette-2-base u-text-hover-palette-2-base u-text-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-1">more</a>
            </div>
          </div>
          <div class="u-align-center u-border-1 u-border-palette-2-base u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-video-cover u-white u-list-item-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
            <div class="u-container-layout u-similar-container u-valign-top u-container-layout-3">
              <img class="u-expanded-width u-image u-image-default u-image-3" src="../new_images/1.jpg" alt="" data-image-width="900" data-image-height="600">
              <h4 class="u-hover-feature u-text u-text-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">Internet Access</h4>
              <p class="u-hover-feature u-text u-text-5">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
              <a href="#" class="u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-hover-feature u-none u-radius-0 u-text-active-palette-2-base u-text-hover-palette-2-base u-text-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-2">more</a>
            </div>
          </div>
          <div class="u-align-center u-border-1 u-border-palette-2-base u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-video-cover u-white u-list-item-3" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
            <div class="u-container-layout u-similar-container u-valign-top u-container-layout-4">
              <img class="u-expanded-width u-image u-image-default u-image-4" src="../new_images/777.jpg" alt="" data-image-width="900" data-image-height="600">
              <h4 class="u-hover-feature u-text u-text-6" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">Climbing Instructor</h4>
              <p class="u-hover-feature u-text u-text-7">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
              <a href="#" class="u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-hover-feature u-none u-radius-0 u-text-active-palette-2-base u-text-hover-palette-2-base u-text-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-3">more</a>
            </div>
          </div>
          <div class="u-align-center u-border-1 u-border-palette-2-base u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-video-cover u-white u-list-item-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
            <div class="u-container-layout u-similar-container u-valign-top u-container-layout-5">
              <img class="u-expanded-width u-image u-image-default u-image-5" src="../new_images/dfdf.jpg" alt="" data-image-width="900" data-image-height="600">
              <h4 class="u-hover-feature u-text u-text-8" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">Mountain Bikes</h4>
              <p class="u-hover-feature u-text u-text-9">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
              <a href="#" class="u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-hover-feature u-none u-radius-0 u-text-active-palette-2-base u-text-hover-palette-2-base u-text-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-4">more</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-section-5" id="block-5">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-gutter-0 u-layout">
            <div class="u-layout-row">
              <div class="u-container-align-left u-container-style u-layout-cell u-shape-rectangle u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-top u-container-layout-1">
                  <h2 class="u-align-left u-text u-text-1">Our Camping</h2>
                  <p class="u-align-left u-text u-text-2">Podcasting operational change management inside of workflows to establish a framework. Taking seamless key performance indicators offline to maximise the long tail. Keeping your eye on the ball while performing a deep dive on the start-up mentality to derive convergence on cross-platform integration.</p>
                  <a href="#" class="u-active-palette-2-light-1 u-align-left u-border-none u-btn u-btn-round u-button-style u-hover-palette-2-light-1 u-palette-2-base u-radius-50 u-text-active-white u-text-body-alt-color u-text-hover-white u-btn-1">learn more</a>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-top u-container-layout-2">
                  <h4 class="u-custom-font u-text u-text-font u-text-3">National Park Service Camping Guide</h4>
                  <p class="u-text u-text-4">Podcasting operational change management inside of workflows to establish a framework. Taking seamless key performance indicators offline to maximise the long tail.</p>
                  <p class="u-text u-text-palette-2-base u-text-5">Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-image u-section-6" data-image-width="1620" data-image-height="1080" id="block-6">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-gutter-0 u-layout">
            <div class="u-layout-row">
              <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-size-30 u-white u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1">Family Camp</h2>
                  <p class="u-text u-text-default u-text-2">The trekking in the enchanting mountains or rafting in the wild rivers, exploring the dense forest, canyoning in the refreshing waterfall, gliding across the highest peaks and the beautiful valley etc. are some of the adventures you can imagine.</p>
                  <a href="#" class="u-active-palette-2-light-1 u-border-none u-btn u-btn-round u-button-style u-hover-palette-2-light-1 u-palette-2-base u-radius-50 u-text-active-white u-text-body-alt-color u-text-hover-white u-btn-2">learn more</a>
                </div>
              </div>
              <div class="u-container-style u-image u-layout-cell u-size-30 u-image-1" data-image-width="721" data-image-height="1080" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-border-20 u-border-white u-container-layout u-container-layout-2"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-image u-shading u-section-8" data-image-width="1620" data-image-height="1080" id="block-8">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1750" data-animation-delay="250">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1">Contact Us</h2>
                  <p class="u-text u-text-body-alt-color u-text-2">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                  <a href="../contact.php" class="u-active-white u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2">Contact Us</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

<?php
$footer_text = setting('footer_text', '© ' . date('Y') . ' CampForge. All rights reserved.');
?>
    <footer class="u-align-center u-clearfix u-container-align-center u-footer u-grey-80 u-footer" id="sec-b7f2">
      <div class="u-clearfix u-sheet u-sheet-1">
        <p class="u-small-text u-text u-text-variant u-text-1"><?= h($footer_text) ?></p>
      </div>
    </footer>
    <script src="../jquery.js" defer></script>
    <script src="../nicepage.js" defer></script>

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
    if (t === 'setting') return `setting:${el.dataset.key}`;
    return `${t}:${el.dataset.id}:${el.dataset.field}`;
}

function getPayload(el) {
    const t  = el.dataset.type;
    const val = el.innerHTML; // preserve inline formatting
    if (t === 'setting') return { type: 'setting', key: el.dataset.key, value: val };
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
