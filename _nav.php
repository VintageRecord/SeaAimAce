<?php
// Shared navigation — included by every front-end page
// $current_page: 'home'|'about'|'contact'|'faq'|'gallery'|'team'|'landing'
// $_nav_base: optional prefix for asset/page URLs (e.g. '../' when included from admin/)
$current_page ??= '';
$_nav_base ??= '';
$site_logo = setting('site_logo', 'new_images/-.png');
$site_name = setting('site_name', 'CampForge');

// Nav links from DB; fallback to defaults
$db = get_db();
$nav_rows = $db->query('SELECT * FROM nav_links ORDER BY sort_order ASC')->fetchAll();
if (empty($nav_rows)) {
    $nav_rows = [
        ['label' => 'Home',     'url' => './'],
        ['label' => 'About Us', 'url' => 'about.php'],
        ['label' => 'Gallery',  'url' => 'gallery.php'],
        ['label' => 'Our Team', 'url' => 'team.php'],
        ['label' => 'FAQ',      'url' => 'faq.php'],
        ['label' => 'Contact',  'url' => 'contact.php'],
    ];
}
?>
<header class="u-clearfix u-header u-header" id="sec-c67f">
  <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
    <a href="<?= h($_nav_base) ?>./" class="u-image u-logo u-image-1" data-image-width="165" data-image-height="188">
      <img src="<?= h($_nav_base . $site_logo) ?>" class="u-logo-image u-logo-image-1" alt="<?= h($site_name) ?>">
    </a>
    <nav class="u-menu u-menu-one-level u-offcanvas u-menu-1" data-responsive-from="MD" role="navigation">
      <div class="menu-collapse" style="font-size:1rem;letter-spacing:0;font-weight:500;">
        <a class="u-button-style u-hamburger-link u-nav-link" href="#" tabindex="-1" aria-label="Open menu" aria-controls="nav-mobile">
          <svg class="u-svg-link" viewBox="0 0 24 24"><use xlink:href="#menu-hamburger"></use></svg>
          <svg class="u-svg-content" version="1.1" id="menu-hamburger" viewBox="0 0 16 16" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><g><rect y="1" width="16" height="2"></rect><rect y="7" width="16" height="2"></rect><rect y="13" width="16" height="2"></rect></g></svg>
        </a>
      </div>
      <div class="u-custom-menu u-nav-container">
        <ul class="u-nav u-spacing-2 u-unstyled u-nav-1" role="menubar">
          <?php foreach ($nav_rows as $nl): ?>
          <li class="u-nav-item" role="none">
            <a class="u-active-grey-5 u-button-style u-hover-grey-10 u-nav-link u-text-active-grey-90 u-text-grey-90 u-text-hover-grey-90 u-nav-link-2" href="<?= h($_nav_base . $nl['url']) ?>" role="menuitem"><?= h($nl['label']) ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="u-custom-menu u-nav-container-collapse" id="nav-mobile" role="region" aria-label="Menu panel">
        <div class="u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
          <div class="u-inner-container-layout u-sidenav-overflow">
            <div class="u-menu-close" tabindex="-1" aria-label="Close menu"></div>
            <ul class="u-align-center u-nav u-popupmenu-items u-unstyled u-nav-2">
              <?php foreach ($nav_rows as $nl): ?>
              <li class="u-nav-item"><a class="u-button-style u-nav-link" href="<?= h($_nav_base . $nl['url']) ?>"><?= h($nl['label']) ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
      </div>
      <style class="menu-style">@media (max-width:939px){[data-responsive-from="MD"] .u-nav-container{display:none}[data-responsive-from="MD"] .menu-collapse{display:block}}</style>
    </nav>
  </div>
</header>
