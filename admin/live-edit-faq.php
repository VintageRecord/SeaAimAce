<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$db = get_db();
$cms_page_key   = 'live-edit-faq.php';
$cms_page_title = 'FAQ';
$cms_page_css   = '../FAQ-Page.css';
$faq_items = $db->query('SELECT * FROM faq_items ORDER BY sort_order')->fetchAll();
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
<?php include '_live_edit_head.php'; ?>
</head>
<body data-path-to-root="../" class="u-body u-clearfix u-xl-mode" data-lang="en">

<?php include '_live_edit_bar.php'; ?>

<?php
$_nav_base = '../';
require dirname(__DIR__) . '/_nav.php';
?>

    <section class="skrollable skrollable-between u-align-center u-clearfix u-container-align-center u-image u-shading u-section-1" src="" data-image-width="1980" data-image-height="1320" id="block-1">
      <div class="u-clearfix u-sheet u-valign-middle-xs u-sheet-1">
        <h1 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="faq_hero_heading"><?= h(setting('faq_hero_heading','Plan Your Camping Trip')) ?></h1>
        <p class="u-align-center u-large-text u-text u-text-body-alt-color u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="faq_hero_subtext"><?= h(setting('faq_hero_subtext','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
      </div>
    </section>

    <section class="u-clearfix u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-editable data-type="setting" data-key="faq_section_heading"><?= h(setting('faq_section_heading','Frequently Asked Questions')) ?></h2>
        <?php if (!empty($faq_items)): ?>
        <div class="u-accordion u-expanded-width u-accordion-1">
          <?php foreach ($faq_items as $fi): ?>
          <div class="u-accordion-item">
            <a class="u-accordion-link" style="cursor:default;display:block;padding:12px 0;border-bottom:1px solid #e0e0e0">
              <span style="font-weight:600"><?= h($fi['question']) ?></span>
            </a>
            <div style="padding:12px 0 16px;color:#555;border-bottom:1px solid #eee"><?= h($fi['answer']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="text-align:center;color:#888;padding:40px">No FAQ items yet. Add them in <a href="faq.php" style="color:#E63946">Dashboard → FAQ</a>.</p>
        <?php endif; ?>
      </div>
    </section>

<?php
$footer_text = setting('footer_text', '© ' . date('Y') . ' CampForge. All rights reserved.');
?>
    <footer class="u-align-center u-clearfix u-container-align-center u-footer u-grey-80 u-footer" id="sec-b7f2">
      <div class="u-clearfix u-sheet u-sheet-1">
        <p class="u-small-text u-text u-text-variant u-text-1" data-editable data-type="setting" data-key="footer_text"><?= h($footer_text) ?></p>
      </div>
    </footer>
    <script src="../jquery.js" defer></script>
    <script src="../nicepage.js" defer></script>

<?php include '_live_edit_js.php'; ?>
</body>
</html>
