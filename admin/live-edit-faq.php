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
    <section class="u-clearfix u-image u-shading u-section-3" data-image-width="1620" data-image-height="1080" id="block-3">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="data-layout-selected u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="0">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1">Contact Us</h2>
                  <p class="u-text u-text-body-alt-color u-text-2">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                  <a href="../contact.php" class="u-active-white u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2">Contact Us</a>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500">
                <div class="u-container-layout u-valign-middle u-container-layout-2">
                  <div class="u-form u-form-1">
                    <form action="https://service.nicepagesrv.com/form/v4/form-process" class="u-clearfix u-form-spacing-30 u-form-vertical u-inner-form" style="padding: 10px" source="email" name="form">
                      <div class="u-form-email u-form-group u-form-partition-factor-2">
                        <label class="u-label u-text-body-alt-color u-label-1">Email</label>
                        <input type="email" placeholder="Enter a valid email address" name="email" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-group u-form-name u-form-partition-factor-2">
                        <label class="u-label u-text-body-alt-color u-label-2">Name</label>
                        <input type="text" placeholder="Enter your Name" name="name" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-address u-form-group u-form-group-3">
                        <label class="u-label u-text-body-alt-color u-label-3">Address</label>
                        <input type="text" placeholder="Enter your address" name="address" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-group u-form-message">
                        <label class="u-label u-text-body-alt-color u-label-4">Message</label>
                        <textarea placeholder="Enter your message" rows="4" cols="50" name="message" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required=""></textarea>
                      </div>
                      <div class="u-align-left u-form-group u-form-submit">
                        <button type="submit" class="u-active-white u-border-none u-btn u-btn-round u-button-style u-hover-white u-palette-2-base u-radius-50">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
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
