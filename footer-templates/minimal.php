<footer style="<?= h($bg_style . $text_style) ?> padding:32px 0;font-family:'Segoe UI',system-ui,sans-serif;" id="sec-b7f2">
  <div style="max-width:1200px;margin:0 auto;padding:0 32px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px">
    <a href="<?= h($_foot_base) ?>./" style="display:flex;align-items:center;gap:8px;text-decoration:none">
      <img src="<?= h($_foot_base . $site_logo) ?>" alt="<?= h($site_name) ?>" style="height:28px;width:auto;object-fit:contain;opacity:.75">
      <?php if ($nav_logo_text): ?>
      <span style="font-size:.9rem;font-weight:700;opacity:.75"><?= h($nav_logo_text) ?></span>
      <?php endif; ?>
    </a>

    <p style="margin:0;font-size:.78rem;opacity:.55"><?= sh($footer_copyright) ?></p>

    <nav aria-label="Footer navigation" style="display:flex;flex-wrap:wrap;gap:18px">
      <?php foreach ($fnav as $fn): ?>
      <a href="<?= h($_foot_base . $fn['url']) ?>" style="<?= h($link_style) ?> font-size:.78rem"
         onmouseover="this.style.opacity='.6'" onmouseout="this.style.opacity='1'"><?= h($fn['label']) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</footer>
