<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$db = get_db();
$cms_page_key   = 'live-edit-gallery.php';
$cms_page_title = 'Gallery';
$cms_page_css   = '../Gallery.css';
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

    <section class="skrollable skrollable-between u-align-center u-clearfix u-image u-shading u-section-1" src="" data-image-width="1980" data-image-height="1131" id="block-1">
      <div class="u-clearfix u-sheet u-valign-top-lg u-valign-top-xl u-sheet-1">
        <h1 class="u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1000" data-editable data-type="setting" data-key="gallery_hero_heading"><?= h(setting('gallery_hero_heading','Where Can I Camp?')) ?></h1>
        <p class="u-large-text u-text u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-editable data-type="setting" data-key="gallery_hero_subtext"><?= h(setting('gallery_hero_subtext','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit')) ?></p>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-section-3" id="block-3">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-editable data-type="setting" data-key="gallery_sec3_heading"><?= h(setting('gallery_sec3_heading','Outdoor Recreation')) ?></h2>
        <p class="u-align-center u-text u-text-2" data-editable data-type="setting" data-key="gallery_sec3_body"><?= h(setting('gallery_sec3_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
        <div class="u-expanded-width u-gallery u-layout-grid u-lightbox u-show-text-on-hover u-gallery-1">
          <div class="u-gallery-inner u-gallery-inner-1">
            <div class="u-effect-fade u-effect-hover-zoom u-gallery-item">
              <div class="u-back-slide" data-image-width="1380" data-image-height="920">
                <img class="u-back-image u-expanded" src="../new_images/3.jpg" alt="">
              </div>
              <div class="u-over-slide u-shading u-over-slide-1">
                <h3 class="u-gallery-heading">Sample Headline</h3>
                <p class="u-gallery-text">sample text</p>
              </div>
            </div>
            <div class="u-effect-fade u-effect-hover-zoom u-gallery-item">
              <div class="u-back-slide" data-image-width="1480" data-image-height="833">
                <img class="u-back-image u-expanded" src="../new_images/37.jpg" alt="">
              </div>
              <div class="u-over-slide u-shading u-over-slide-2">
                <h3 class="u-gallery-heading">Sample Headline</h3>
                <p class="u-gallery-text">sample text</p>
              </div>
            </div>
            <div class="u-effect-fade u-effect-hover-zoom u-gallery-item">
              <div class="u-back-slide" data-image-width="740" data-image-height="833">
                <img class="u-back-image u-expanded" src="../new_images/fd.jpg" alt="">
              </div>
              <div class="u-over-slide u-shading u-over-slide-3">
                <h3 class="u-gallery-heading">Sample Headline</h3>
                <p class="u-gallery-text">sample text</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-palette-2-base u-section-4" id="block-4">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="gallery_sec4_heading"><?= h(setting('gallery_sec4_heading','Find your next getaway')) ?></h2>
        <p class="u-align-center u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-editable data-type="setting" data-key="gallery_sec4_body"><?= h(setting('gallery_sec4_body','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
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
