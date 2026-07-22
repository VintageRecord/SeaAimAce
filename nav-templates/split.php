<?php
// Self-contained alternate nav: three-column full-width bar — logo left,
// links truly centered (independent of button width), CTA button right.
$_sp_bg     = $nav_bg   ?: '#ffffff';
$_sp_text   = $nav_text ?: '#222222';
$_sp_accent = $nav_accent ?: $_sp_text;
$_sp_accent_fg = $nav_accent ? '#fff' : $_sp_bg;
?>
<header id="sec-c67f" style="background:<?= h($_sp_bg) ?>;color:<?= h($_sp_text) ?>;border-bottom:1px solid rgba(0,0,0,.08);font-family:'Segoe UI',system-ui,sans-serif">
  <div style="max-width:1200px;margin:0 auto;padding:14px 24px;display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:16px">
    <a href="<?= h($_nav_base) ?>./" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;justify-self:start">
      <img src="<?= h($_nav_base . $site_logo) ?>" alt="<?= h($site_name) ?>" style="height:38px;width:auto;object-fit:contain">
      <?php if ($nav_logo_text): ?>
      <span style="font-size:1.1rem;font-weight:700;white-space:nowrap"><?= h($nav_logo_text) ?></span>
      <?php endif; ?>
    </a>

    <nav id="sp-links" aria-label="Primary navigation" style="display:flex;align-items:center;gap:26px;justify-self:center">
      <?php foreach ($nav_rows as $nl):
        $is_active = ($nl['url'] === $active_url);
      ?>
      <a href="<?= h($_nav_base . $nl['url']) ?>"
         style="color:<?= $is_active ? h($_sp_accent) : 'inherit' ?>;text-decoration:none;font-size:.88rem;font-weight:<?= $is_active ? '700' : '500' ?>;white-space:nowrap"
         <?= $is_active ? 'aria-current="page"' : '' ?>><?= h($nl['label']) ?></a>
      <?php endforeach; ?>
    </nav>

    <div style="justify-self:end;display:flex;align-items:center;gap:14px">
      <?php if ($nav_btn_text): ?>
      <a href="<?= h($_nav_base . ltrim($nav_btn_href, '/')) ?>"
         style="background:<?= h($_sp_accent) ?>;color:<?= h($_sp_accent_fg) ?>;padding:8px 20px;border-radius:8px;font-size:.82rem;font-weight:600;text-decoration:none;white-space:nowrap">
        <?= h($nav_btn_text) ?>
      </a>
      <?php endif; ?>
      <div id="sp-toggle" style="display:none;cursor:pointer;font-size:1.4rem;line-height:1" onclick="document.getElementById('sp-links').classList.toggle('sp-open')">&#9776;</div>
    </div>
  </div>
  <style>
    @media (max-width:800px){
      #sp-toggle{display:block!important}
      header#sec-c67f > div{grid-template-columns:1fr auto}
      #sp-links{display:none;position:absolute;left:0;right:0;top:100%;background:<?= h($_sp_bg) ?>;flex-direction:column;align-items:flex-start;gap:14px;padding:16px 24px;border-top:1px solid rgba(0,0,0,.08);z-index:50}
      #sp-links.sp-open{display:flex}
      header#sec-c67f{position:relative}
    }
  </style>
</header>
