<?php
$page_title ??= 'Admin';
$active_nav ??= '';
$show_preview ??= true; // set to false on pages where preview doesn't make sense
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($page_title) ?> | FORGE CMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 10 L5 30 L15 30 L15 20 L25 20 L25 30 L35 30 L35 10 L25 10 L25 15 L15 15 L15 10 Z" fill="#E63946"/>
                <rect x="18" y="5" width="4" height="30" fill="#FF6B35" opacity="0.8"/>
                <rect x="5" y="18" width="30" height="4" fill="#FF6B35" opacity="0.8"/>
            </svg>
            <div class="sidebar-logo-text">
                <span>FORGE</span>
                <small>CMS Admin</small>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-group-label">Overview</div>
            <a href="index.php" class="<?= $active_nav === 'dashboard' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Dashboard
            </a>
            <div class="nav-group-label">Pages</div>
            <a href="pages.php" class="<?= $active_nav === 'pages' ? 'active' : '' ?>">
                <span class="nav-dot"></span> All Pages
            </a>
            <a href="pages-edit.php" class="<?= $active_nav === 'page-new' ? 'active' : '' ?>">
                <span class="nav-dot"></span> New Page
            </a>
            <div class="nav-group-label">Media</div>
            <a href="media.php" class="<?= $active_nav === 'media' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Media Library
            </a>
            <div class="nav-group-label">Homepage Content</div>
            <a href="general.php" class="<?= $active_nav === 'general' ? 'active' : '' ?>">
                <span class="nav-dot"></span> General &amp; Hero
            </a>
            <a href="features.php" class="<?= $active_nav === 'features' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Features
            </a>
            <a href="spaces.php" class="<?= $active_nav === 'spaces' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Spaces
            </a>
            <a href="pricing.php" class="<?= $active_nav === 'pricing' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Pricing
            </a>
            <a href="amenities.php" class="<?= $active_nav === 'amenities' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Amenities
            </a>
            <a href="contact.php" class="<?= $active_nav === 'contact' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Contact Info
            </a>
            <a href="footer.php" class="<?= $active_nav === 'footer' ? 'active' : '' ?>">
                <span class="nav-dot"></span> CTA &amp; Footer
            </a>
            <div class="nav-group-label">Site Settings</div>
            <a href="navigation.php" class="<?= $active_nav === 'navigation' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Navigation &amp; Footer
            </a>
            <div class="nav-group-label">Inbox</div>
            <a href="submissions.php" class="<?= $active_nav === 'submissions' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Submissions
            </a>
            <div class="nav-group-label">Account</div>
            <a href="account.php" class="<?= $active_nav === 'account' ? 'active' : '' ?>">
                <span class="nav-dot"></span> Change Password
            </a>
        </nav>
        <div class="sidebar-footer">
            <span>admin</span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </aside>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <div class="breadcrumb"><a href="index.php">Admin</a> / <?= h($page_title) ?></div>
                <h1><?= h($page_title) ?></h1>
            </div>
            <div class="topbar-actions">
                <?php if ($show_preview): ?>
                <button class="preview-toggle-btn" id="preview-toggle-btn" onclick="togglePreview()">Show Preview</button>
                <?php endif; ?>
                <a href="live-edit.php" class="view-site-btn" style="background:var(--accent2)">Live Edit</a>
                <a href="../index.php" target="_blank" class="view-site-btn">View Site</a>
            </div>
        </div>

        <div class="split-layout" id="split-layout">
            <div class="edit-panel" id="edit-panel">
