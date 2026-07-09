<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$db = get_db();
$live_media_files = $db->query("SELECT filename FROM media ORDER BY id DESC")->fetchAll(PDO::FETCH_COLUMN);
$live_new_images  = [];
$_ni_dir = dirname(__DIR__) . '/new_images';
if (is_dir($_ni_dir)) {
    foreach (scandir($_ni_dir) as $_nf) {
        $ext = strtolower(pathinfo($_nf, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp','svg'])) $live_new_images[] = $_nf;
    }
    sort($live_new_images);
}
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
        <div class="data-layout-selected u-clearfix u-expanded-width-sm u-expanded-width-xs u-layout-custom-sm u-layout-custom-xs u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-align-center-xs u-align-right-lg u-align-right-md u-align-right-sm u-align-right-xl u-container-align-center-sm u-container-align-center-xs u-container-style u-layout-cell u-left-cell u-size-30-lg u-size-30-md u-size-30-sm u-size-30-xl u-size-60-xs u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-container-layout u-valign-middle-xs u-valign-top-lg u-valign-top-md u-valign-top-sm u-valign-top-xl u-container-layout-1">
                  <a href="#" class="u-active-white u-align-center-xs u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-1">view More</a>
                </div>
              </div>
              <div class="u-align-center-xs u-align-left-lg u-align-left-md u-align-left-sm u-align-left-xl u-container-align-center-xs u-container-align-left-lg u-container-align-left-md u-container-align-left-sm u-container-align-left-xl u-container-style u-layout-cell u-right-cell u-size-30-lg u-size-30-md u-size-30-sm u-size-30-xl u-size-60-xs u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-container-layout u-valign-top u-container-layout-2">
                  <a href="#" class="u-active-palette-2-base u-align-center-xs u-align-left-lg u-align-left-md u-align-left-sm u-align-left-xl u-border-2 u-border-active-palette-2-base u-border-hover-palette-2-base u-border-white u-btn u-btn-round u-button-style u-hover-palette-2-base u-radius-50 u-text-active-white u-text-hover-white u-white u-btn-2">Contact Us</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <p class="u-align-center u-text u-text-body-alt-color u-text-default u-text-3" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="1000">Image from&nbsp;<a href="https://www.freepik.com" class="u-active-none u-border-1 u-border-active-palette-2-light-2 u-border-hover-palette-2-light-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-hover-none u-none u-radius-0 u-text-body-alt-color u-top-left-radius-0 u-top-right-radius-0 u-btn-3" target="_blank">Freepik</a>
        </p>
      </div>
    </section>

    <section class="u-clearfix u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-sheet-1">
        <h2 class="u-text u-text-default u-text-1" data-editable data-type="setting" data-key="faq_section_heading"><?= h(setting('faq_section_heading','faq')) ?></h2>
        <img class="u-image u-image-circle u-image-1" src="<?= h('../' . setting('img_src_faq_s2_img1', 'new_images/photographer-man-smiling-while-h.jpg')) ?>" alt="" data-image-width="740" data-image-height="1110" data-img-key="faq_s2_img1">
        <p class="u-text u-text-grey-30 u-text-2" data-editable data-type="setting" data-key="faq_s2_body"><?= h(setting('faq_s2_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
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
                  <h2 class="u-text u-text-1" data-editable data-type="setting" data-key="faq_s3_heading"><?= h(setting('faq_s3_heading','Contact Us')) ?></h2>
                  <p class="u-text u-text-body-alt-color u-text-2" data-editable data-type="setting" data-key="faq_s3_body"><?= h(setting('faq_s3_body','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.')) ?></p>
                  <p class="u-align-left u-text u-text-3">Images from <a href="https://www.freepik.com/photos/man-with-dog" class="u-border-1 u-border-active-palette-2-light-2 u-border-hover-palette-2-light-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-btn u-button-link u-button-style u-none u-text-body-alt-color u-btn-1">Freepik</a>
                  </p>
                  <a href="#" class="u-active-white u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2" data-editable data-type="setting" data-key="faq_s3_btn"><?= h(setting('faq_s3_btn','Contact Us')) ?></a>
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
$_foot_base = '../';
require_once dirname(__DIR__) . '/_footer.php';
?>
    <script src="../jquery.js" defer></script>
    <script src="../nicepage.js" defer></script>

<?php include '_live_edit_js.php'; ?>
</body>
</html>
