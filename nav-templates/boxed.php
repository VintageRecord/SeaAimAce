<?php
// Self-contained alternate nav: a floating rounded rectangular bar inset from
// the page edges with a shadow, rather than a full-width bar.
$_bx_bg     = $nav_bg   ?: '#ffffff';
$_bx_text   = $nav_text ?: '#222222';
$_bx_accent = $nav_accent ?: $_bx_text;
$_bx_accent_fg = $nav_accent ? '#fff' : $_bx_bg;
?>
<header id="sec-c67f" style="background:transparent;padding:16px 20px 0;font-family:'Segoe UI',system-ui,sans-serif">
  <div style="max-width:1200px;margin:0 auto;background:<?= h($_bx_bg) ?>;color:<?= h($_bx_text) ?>;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.10);padding:14px 24px;display:flex;align-items:center;gap:20px;position:relative">
    <a href="<?= h($_nav_base) ?>./" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;flex-shrink:0">
      <img src="<?= h($_nav_base . $site_logo) ?>" alt="<?= h($site_name) ?>" style="height:38px;width:auto;object-fit:contain">
      <?php if ($nav_logo_text): ?>
      <span style="font-size:1.1rem;font-weight:700;white-space:nowrap"><?= h($nav_logo_text) ?></span>
      <?php endif; ?>
    </a>

    <nav id="bx-links" aria-label="Primary navigation" style="display:flex;flex-wrap:wrap;align-items:center;gap:24px;flex:1;justify-content:flex-end">
      <?php foreach ($nav_rows as $nl):
        $is_active = ($nl['url'] === $active_url);
      ?>
      <a href="<?= h($_nav_base . $nl['url']) ?>"
         style="color:<?= $is_active ? h($_bx_accent) : 'inherit' ?>;text-decoration:none;font-size:.88rem;font-weight:<?= $is_active ? '700' : '500' ?>"
         <?= $is_active ? 'aria-current="page"' : '' ?>><?= h($nl['label']) ?></a>
      <?php endforeach; ?>
      <?php if ($nav_btn_text): ?>
      <a href="<?= h($_nav_base . ltrim($nav_btn_href, '/')) ?>"
         style="background:<?= h($_bx_accent) ?>;color:<?= h($_bx_accent_fg) ?>;padding:9px 22px;border-radius:10px;font-size:.82rem;font-weight:600;text-decoration:none;white-space:nowrap">
        <?= h($nav_btn_text) ?>
      </a>
      <?php endif; ?>
    </nav>

    <div id="bx-toggle" style="display:none;cursor:pointer;font-size:1.4rem;line-height:1;margin-left:auto" onclick="document.getElementById('bx-links').classList.toggle('bx-open')">&#9776;</div>
  </div>
  <style>
    @media (max-width:800px){
      #bx-toggle{display:block!important}
      #bx-links{display:none;flex-direction:column;align-items:flex-start;gap:14px;width:100%;margin-top:14px}
      #bx-links.bx-open{display:flex}
      header#sec-c67f > div{flex-wrap:wrap}
    }
  </style>
</header>
