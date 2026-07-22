<footer style="<?= h($bg_style . $text_style) ?> padding:60px 0 0;font-family:'Segoe UI',system-ui,sans-serif;" id="sec-b7f2">
  <div style="max-width:1200px;margin:0 auto;padding:0 32px">

    <?php if (!empty($footer_columns)): ?>
    <!-- Footer columns grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:40px 32px;margin-bottom:48px">
      <!-- Brand column -->
      <div>
        <a href="<?= h($_foot_base) ?>./" style="display:flex;align-items:center;gap:8px;text-decoration:none;margin-bottom:12px">
          <img src="<?= h($_foot_base . $site_logo) ?>" alt="<?= h($site_name) ?>" style="height:36px;width:auto;object-fit:contain">
          <?php if ($nav_logo_text): ?>
          <span style="font-size:1rem;font-weight:700;<?= h($text_style) ?>"><?= h($nav_logo_text) ?></span>
          <?php endif; ?>
        </a>
        <p style="font-size:.82rem;line-height:1.6;margin:0;opacity:.7"><?= h(setting('site_tagline', 'Your gateway to the great outdoors.')) ?></p>
      </div>

      <?php foreach ($footer_columns as $col):
        $links = json_decode($col['links'], true) ?: [];
      ?>
      <div>
        <h4 style="font-size:.78rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin:0 0 14px;<?= h($_foot_heading_style) ?>"><?= h($col['heading']) ?></h4>
        <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:10px">
          <?php foreach ($links as $lnk): ?>
          <li>
            <a href="<?= h($_foot_base . ($lnk[1] ?? '#')) ?>" style="<?= h($link_style) ?> font-size:.85rem;line-height:1.4"
               onmouseover="<?= h($_foot_link_hover_in) ?>" onmouseout="<?= h($_foot_link_hover_out) ?>"><?= h($lnk[0] ?? '') ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
    <hr style="border:none;border-top:1px solid rgba(255,255,255,.08);margin:0 0 24px">
    <?php else: ?>
    <!-- Simple footer — no columns configured -->
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
      <img src="<?= h($_foot_base . $site_logo) ?>" alt="<?= h($site_name) ?>" style="height:30px;width:auto;object-fit:contain;opacity:.6">
      <?php if ($nav_logo_text): ?>
      <span style="font-size:.95rem;font-weight:700;opacity:.6"><?= h($nav_logo_text) ?></span>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Bottom bar -->
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;padding-bottom:28px;font-size:.78rem;opacity:.55">
      <p style="margin:0"><?= sh($footer_copyright) ?></p>
      <nav aria-label="Footer navigation" style="display:flex;flex-wrap:wrap;gap:18px">
        <?php foreach ($fnav as $fn): ?>
        <a href="<?= h($_foot_base . $fn['url']) ?>" style="<?= h($link_style) ?> font-size:.78rem"
           onmouseover="<?= h($_foot_link_hover_in) ?>" onmouseout="<?= h($_foot_link_hover_out) ?>"><?= h($fn['label']) ?></a>
        <?php endforeach; ?>
      </nav>
    </div>
  </div>
</footer>
