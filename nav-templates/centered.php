<?php
// Self-contained alternate nav: centered logo on top, links + CTA on a second row.
// Deliberately avoids nicepage.js/u-* classes so it renders identically regardless
// of what nicepage.css does to .u-header/.u-menu elsewhere on the page.
$_nc_bg     = $nav_bg   ?: '#ffffff';
$_nc_text   = $nav_text ?: '#222222';
$_nc_accent = $nav_accent ?: $_nc_text;
$_nc_accent_fg = $nav_accent ? '#fff' : $_nc_bg;
?>
<header id="sec-c67f" style="background:<?= h($_nc_bg) ?>;color:<?= h($_nc_text) ?>;border-bottom:1px solid rgba(0,0,0,.08);font-family:'Segoe UI',system-ui,sans-serif">
  <div style="max-width:1200px;margin:0 auto;padding:16px 24px;text-align:center">
    <a href="<?= h($_nav_base) ?>./" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;color:inherit;margin-bottom:12px">
      <img src="<?= h($_nav_base . $site_logo) ?>" alt="<?= h($site_name) ?>" style="height:44px;width:auto;object-fit:contain">
      <?php if ($nav_logo_text): ?>
      <span style="font-size:1.3rem;font-weight:700"><?= h($nav_logo_text) ?></span>
      <?php endif; ?>
    </a>

    <div id="nc-toggle" style="display:none;position:absolute;top:18px;right:20px;cursor:pointer;font-size:1.4rem;line-height:1" onclick="document.getElementById('nc-links').classList.toggle('nc-open')">&#9776;</div>

    <nav id="nc-links" aria-label="Primary navigation" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:28px">
      <?php foreach ($nav_rows as $nl):
        $is_active = ($nl['url'] === $active_url);
      ?>
      <a href="<?= h($_nav_base . $nl['url']) ?>"
         style="color:<?= $is_active ? h($_nc_accent) : 'inherit' ?>;text-decoration:none;font-size:.9rem;font-weight:<?= $is_active ? '700' : '500' ?>;<?= $is_active ? 'border-bottom:2px solid ' . h($_nc_accent) . ';padding-bottom:3px' : '' ?>"
         <?= $is_active ? 'aria-current="page"' : '' ?>><?= h($nl['label']) ?></a>
      <?php endforeach; ?>
      <?php if ($nav_btn_text): ?>
      <a href="<?= h($_nav_base . ltrim($nav_btn_href, '/')) ?>"
         style="background:<?= h($_nc_accent) ?>;color:<?= h($_nc_accent_fg) ?>;padding:8px 22px;border-radius:50px;font-size:.82rem;font-weight:600;text-decoration:none;white-space:nowrap">
        <?= h($nav_btn_text) ?>
      </a>
      <?php endif; ?>
    </nav>
  </div>
  <style>
    @media (max-width:700px){
      #nc-toggle{display:block!important}
      #nc-links{display:none;flex-direction:column;gap:14px;padding-top:6px}
      #nc-links.nc-open{display:flex}
    }
  </style>
</header>
