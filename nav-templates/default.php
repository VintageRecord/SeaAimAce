<header class="u-clearfix u-header u-header" id="sec-c67f"<?= $nav_style ? ' style="' . h($nav_style) . '"' : '' ?>>
  <div class="u-clearfix u-sheet u-valign-middle u-sheet-1" style="display:flex;align-items:center;gap:0">
    <!-- Logo -->
    <a href="<?= h($_nav_base) ?>./" class="u-image u-logo u-image-1" data-image-width="165" data-image-height="188" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0">
      <img src="<?= h($_nav_base . $site_logo) ?>" class="u-logo-image u-logo-image-1" alt="<?= h($site_name) ?>">
      <?php if ($nav_logo_text): ?>
      <span style="font-size:1.15rem;font-weight:700;white-space:nowrap;<?= $nav_text ? 'color:' . h($nav_text) . ';' : '' ?>"><?= h($nav_logo_text) ?></span>
      <?php endif; ?>
    </a>

    <nav class="u-menu u-menu-one-level u-offcanvas u-menu-1" data-responsive-from="MD" role="navigation" style="flex:1">
      <div class="menu-collapse" style="font-size:1rem;letter-spacing:0;font-weight:500;">
        <a class="u-button-style u-hamburger-link u-nav-link" href="#" tabindex="-1" aria-label="Open menu" aria-controls="nav-mobile">
          <svg class="u-svg-link" viewBox="0 0 24 24"><use xlink:href="#menu-hamburger"></use></svg>
          <svg class="u-svg-content" version="1.1" id="menu-hamburger" viewBox="0 0 16 16" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><g><rect y="1" width="16" height="2"></rect><rect y="7" width="16" height="2"></rect><rect y="13" width="16" height="2"></rect></g></svg>
        </a>
      </div>
      <div class="u-custom-menu u-nav-container" style="display:flex;align-items:center;justify-content:flex-end;gap:4px">
        <ul class="u-nav u-spacing-2 u-unstyled u-nav-1" role="menubar" style="display:flex;align-items:center;gap:0;margin:0;padding:0;list-style:none">
          <?php foreach ($nav_rows as $nl):
            $is_active = ($nl['url'] === $active_url);
          ?>
          <li class="u-nav-item" role="none">
            <a class="u-active-grey-5 u-button-style u-hover-grey-10 u-nav-link u-text-active-grey-90 u-text-grey-90 u-text-hover-grey-90 u-nav-link-2<?= $is_active ? ' u-nav-link-active' : '' ?>"
               href="<?= h($_nav_base . $nl['url']) ?>"
               role="menuitem"
               <?= $nav_text && !$is_active ? 'style="color:' . h($nav_text) . '"' : '' ?>
               <?= $is_active ? 'aria-current="page"' : '' ?>><?= h($nl['label']) ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php if ($nav_btn_text): ?>
        <a href="<?= h($_nav_base . ltrim($nav_btn_href, '/')) ?>"
           class="u-border-2 u-border-palette-2-base u-btn u-btn-round u-button-style u-palette-2-base u-radius-50"
           style="margin-left:16px;padding:8px 22px;font-size:.82rem;font-weight:600;white-space:nowrap;flex-shrink:0">
          <?= h($nav_btn_text) ?>
        </a>
        <?php endif; ?>
      </div>
      <div class="u-custom-menu u-nav-container-collapse" id="nav-mobile" role="region" aria-label="Menu panel">
        <div class="u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
          <div class="u-inner-container-layout u-sidenav-overflow">
            <div class="u-menu-close" tabindex="-1" aria-label="Close menu"></div>
            <ul class="u-align-center u-nav u-popupmenu-items u-unstyled u-nav-2">
              <?php foreach ($nav_rows as $nl):
                $is_active = ($nl['url'] === $active_url);
              ?>
              <li class="u-nav-item">
                <a class="u-button-style u-nav-link<?= $is_active ? ' u-nav-link-active' : '' ?>"
                   href="<?= h($_nav_base . $nl['url']) ?>"
                   <?= $is_active ? 'aria-current="page"' : '' ?>><?= h($nl['label']) ?></a>
              </li>
              <?php endforeach; ?>
              <?php if ($nav_btn_text): ?>
              <li class="u-nav-item" style="margin-top:12px">
                <a href="<?= h($_nav_base . ltrim($nav_btn_href, '/')) ?>"
                   class="u-border-2 u-border-palette-2-base u-btn u-btn-round u-button-style u-palette-2-base u-radius-50"
                   style="display:inline-block;padding:8px 22px;font-size:.85rem;font-weight:600">
                  <?= h($nav_btn_text) ?>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
        <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
      </div>
      <style class="menu-style">
        @media (max-width:939px){
          [data-responsive-from="MD"] .u-nav-container{display:none}
          [data-responsive-from="MD"] .menu-collapse{display:block}
        }
        .u-nav-link-active {
          border-bottom: 2px solid currentColor;
          font-weight: 700 !important;
        }
      </style>
    </nav>
  </div>
</header>
