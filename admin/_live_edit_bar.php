<?php
// Shared CMS bar + toolbar HTML for all live editor pages
// Requires $cms_page_key to be set before include
$_pkey = $cms_page_key ?? 'live-edit.php';
$_pages = [
    'live-edit.php'         => 'Home',
    'live-edit-about.php'   => 'About Us',
    'live-edit-gallery.php' => 'Gallery',
    'live-edit-team.php'    => 'Our Team',
    'live-edit-faq.php'     => 'FAQ',
    'live-edit-contact.php' => 'Contact',
];
?>
<div id="cms-bar">
    <div class="bar-logo">
        <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 10 L5 30 L15 30 L15 20 L25 20 L25 30 L35 30 L35 10 L25 10 L25 15 L15 15 L15 10 Z" fill="#E63946"/>
            <rect x="18" y="5" width="4" height="30" fill="#FF6B35" opacity="0.8"/>
            <rect x="5" y="18" width="30" height="4" fill="#FF6B35" opacity="0.8"/>
        </svg>
        <span>Live Editor</span>
    </div>
    <div class="bar-sep"></div>
    <label style="font-size:.75rem;color:#777;font-family:inherit;margin-right:4px">Page:</label>
    <select class="tb-select" id="page-switcher" style="width:130px;font-size:.78rem" onchange="switchPage(this.value)" title="Switch page">
        <?php foreach ($_pages as $_val => $_label): ?>
        <option value="<?= $_val ?>"<?= $_val === $_pkey ? ' selected' : '' ?>><?= $_label ?></option>
        <?php endforeach; ?>
    </select>
    <div class="bar-sep"></div>
    <span class="bar-hint">Click any text to edit — select text to format</span>
    <div class="bar-spacer"></div>
    <span class="save-indicator" id="save-indicator"></span>
    <button class="cms-btn cms-btn-outline" onclick="location.href='index.php'">Dashboard</button>
    <button class="cms-btn cms-btn-primary" onclick="saveAll()">Save All</button>
</div>

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
    <button class="tb-btn" id="tb-bold"      onmousedown="e(event)" onclick="execCmd('bold')"          title="Bold"><b>B</b></button>
    <button class="tb-btn" id="tb-italic"    onmousedown="e(event)" onclick="execCmd('italic')"        title="Italic"><i>I</i></button>
    <button class="tb-btn" id="tb-underline" onmousedown="e(event)" onclick="execCmd('underline')"     title="Underline"><u>U</u></button>
    <button class="tb-btn" id="tb-strike"    onmousedown="e(event)" onclick="execCmd('strikeThrough')" title="Strikethrough"><s>S</s></button>
    <div class="tb-sep"></div>
    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('justifyLeft')"   title="Left">&#8676;</button>
    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('justifyCenter')" title="Center">&#8660;</button>
    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('justifyRight')"  title="Right">&#8677;</button>
    <div class="tb-sep"></div>
    <div class="tb-color-wrap" title="Text colour">
        <input type="color" class="tb-color" id="tb-color" value="#ffffff" onchange="applyColor(this.value)" title="Text colour">
    </div>
    <div class="tb-color-wrap" title="Highlight colour">
        <input type="color" class="tb-color" id="tb-bg-color" value="#000000" onchange="applyBgColor(this.value)" title="Highlight">
    </div>
    <div class="tb-sep"></div>
    <button class="tb-btn" onmousedown="e(event)" onclick="toggleLinkPopup()" title="Link">&#128279;</button>
    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('unlink')" title="Unlink">&#128280;</button>
    <div class="tb-sep"></div>
    <button class="tb-btn" onmousedown="e(event)" onclick="execCmd('removeFormat')" title="Clear">Tx</button>
</div>

<div id="cms-link-popup">
    <input type="url" id="link-url-input" placeholder="https://example.com" onkeydown="if(event.key==='Enter')applyLink()">
    <button class="cms-btn cms-btn-primary" style="padding:5px 12px;font-size:.78rem" onmousedown="e(event)" onclick="applyLink()">Apply</button>
    <button class="cms-btn cms-btn-outline" style="padding:5px 12px;font-size:.78rem" onmousedown="e(event)" onclick="closeLinkPopup()">Cancel</button>
</div>

<div id="cms-hint">Click any highlighted text to edit it</div>
