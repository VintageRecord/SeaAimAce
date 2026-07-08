<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$db = get_db();
$cms_page_key   = 'live-edit-about.php';
$cms_page_title = 'About Us';
$cms_page_css   = '../About.css';
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
        <h1 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="about_hero_heading"><?= h(setting('about_hero_heading','Find yourself outside')) ?></h1>
        <p class="u-align-center u-large-text u-text u-text-body-alt-color u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="about_hero_subtext"><?= h(setting('about_hero_subtext','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
      </div>
    </section>
    <section class="u-clearfix u-container-align-center u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h4 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="about_mission_heading"><?= h(setting('about_mission_heading','Our mission')) ?></h4>
        <h2 class="u-align-center u-text u-text-default u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="about_mission_subheading"><?= h(setting('about_mission_subheading','Get more people outside')) ?></h2>
        <p class="u-align-center u-text u-text-3" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250" data-editable data-type="setting" data-key="about_mission_body"><?= h(setting('about_mission_body','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.')) ?></p>
      </div>
    </section>
    <section class="u-clearfix u-container-align-center u-palette-2-base u-section-3" id="block-3">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250"> Camping near hot springs</h2>
        <div class="u-clearfix u-expanded-width u-gutter-32 u-layout-wrap u-layout-wrap-1" data-animation-name="customAnimationIn" data-animation-duration="1750" data-animation-delay="500">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-size-30 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-size-40">
                    <div class="u-layout-row">
                      <div class="u-container-style u-image u-layout-cell u-left-cell u-size-60 u-image-1" src="" data-image-width="1380" data-image-height="920">
                        <div class="u-container-layout u-valign-middle u-container-layout-1"></div>
                      </div>
                    </div>
                  </div>
                  <div class="u-size-20">
                    <div class="u-layout-row">
                      <div class="u-container-style u-image u-layout-cell u-left-cell u-size-30 u-image-2" src="" data-image-width="803" data-image-height="803">
                        <div class="u-container-layout u-valign-middle u-container-layout-2"></div>
                      </div>
                      <div class="u-align-left u-black u-container-align-left u-container-style u-layout-cell u-size-30 u-layout-cell-3">
                        <div class="u-container-layout u-valign-middle u-container-layout-3">
                          <h3 class="u-align-left u-text u-text-2"> Tent camping</h3>
                          <p class="u-align-left u-text u-text-3">Sample text. Click to select the text box. Click again or double click to start editing the text.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="u-size-30 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-size-20">
                    <div class="u-layout-row">
                      <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-size-30 u-white u-layout-cell-4">
                        <div class="u-container-layout u-valign-middle u-container-layout-4">
                          <h3 class="u-align-left u-text u-text-4"> Lake camping</h3>
                          <p class="u-align-left u-text u-text-5">Sample text. Click to select the text box. Click again or double click to start editing the text.</p>
                        </div>
                      </div>
                      <div class="u-container-style u-image u-layout-cell u-right-cell u-size-30 u-image-3" src="" data-image-width="732" data-image-height="754">
                        <div class="u-container-layout u-valign-middle u-container-layout-5"></div>
                      </div>
                    </div>
                  </div>
                  <div class="u-size-40">
                    <div class="u-layout-row">
                      <div class="u-container-style u-image u-layout-cell u-right-cell u-size-60 u-image-4" src="" data-image-width="1380" data-image-height="920">
                        <div class="u-container-layout u-valign-middle u-container-layout-6"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <p class="u-align-center u-text u-text-body-alt-color u-text-default u-text-6" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-direction="X" data-animation-delay="750">Images from <a href="https://www.freepik.com/" class="u-border-1 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-border-white u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-none u-radius-0 u-text-body-alt-color u-top-left-radius-0 u-top-right-radius-0 u-btn-1" target="_blank">Freepik</a>
        </p>
      </div>
    </section>
    <section class="u-align-center-lg u-align-center-md u-align-center-xl u-align-left-sm u-align-left-xs u-clearfix u-container-align-center u-section-4" data-image-width="2000" data-image-height="1333" id="block-4">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="0">Unique camping</h2>
        <p class="u-align-center u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">Sample text. Click to select the text box. Click again or double click to start editing the text.&nbsp;Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        <div class="data-layout-selected u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-gutter-0 u-layout">
            <div class="u-layout-row">
              <div class="u-size-20 u-size-30-md">
                <div class="u-layout-col">
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-left-cell u-size-20 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-1">
                      <h3 class="u-text u-text-default u-text-3">Adventures</h3>
                      <p class="u-text u-text-4">Sample text. Click to select the text box. Click again or double click to start editing the text.</p>
                    </div>
                  </div>
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-left-cell u-size-20 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-2">
                      <h3 class="u-text u-text-default u-text-5">Hiking</h3>
                      <p class="u-text u-text-6">Sample text. Click to select the text box. Click again or double click to start editing the text.</p>
                    </div>
                  </div>
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-left-cell u-size-20 u-layout-cell-3" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-3">
                      <h3 class="u-text u-text-default u-text-7"> Bicycling</h3>
                      <p class="u-text u-text-8">Sample text. Click to select the text box. Click again or double click to start editing the text.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="u-size-20 u-size-30-md">
                <div class="u-layout-row">
                  <div class="u-align-left u-container-align-left u-container-style u-image u-layout-cell u-size-60 u-image-1" src="" data-image-width="800" data-image-height="1200" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-4" src=""></div>
                  </div>
                </div>
              </div>
              <div class="u-size-20 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-right-cell u-size-20 u-layout-cell-5" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-5">
                      <h3 class="u-text u-text-default u-text-9">Camping</h3>
                      <p class="u-text u-text-10">Sample text. Click to select the text box. Click again or double click to start editing the text.</p>
                    </div>
                  </div>
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-right-cell u-size-20 u-layout-cell-6" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-6">
                      <h3 class="u-text u-text-default u-text-11"> Recreation Activities</h3>
                      <p class="u-text u-text-12">Sample text. Click to select the text box. Click again or double click to start editing the text.</p>
                    </div>
                  </div>
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-right-cell u-size-20 u-layout-cell-7" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-7">
                      <h3 class="u-text u-text-default u-text-13"> Equestrian Services</h3>
                      <p class="u-text u-text-14">Sample text. Click to select the text box. Click again or double click to start editing the text.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <p class="u-align-center u-text u-text-default u-text-15" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">Image from <a href="https://www.freepik.com" class="u-border-1 u-border-active-palette-2-base u-border-black u-border-hover-palette-2-base u-border-no-left u-border-no-right u-border-no-top u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-none u-radius-0 u-text-body-color u-top-left-radius-0 u-top-right-radius-0 u-btn-1" target="_blank">Freepik</a>
        </p>
      </div>
    </section>
    <section class="u-black u-clearfix u-container-align-center u-section-5" id="block-5">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-editable data-type="setting" data-key="about_community_heading"><?= h(setting('about_community_heading','Build resilient communities')) ?></h2>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-white u-section-6" id="block-6">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="0"> Find your next getaway</h2>
        <p class="u-align-center u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250"> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-align-center u-container-align-center u-container-align-center-lg u-container-align-center-md u-container-align-center-sm u-container-align-center-xs u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-video-cover u-list-item-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-1">
                <img class="u-expanded-width u-image u-image-default u-image-1" src="../new_images/1244.jpg" alt="" data-image-width="800" data-image-height="533">
                <h4 class="u-align-center u-text u-text-3"> Best RV camping</h4>
                <p class="u-align-center u-text u-text-4">Sample text. Click to select the Text Element.</p>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-list-item-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-2">
                <img class="u-expanded-width u-image u-image-default u-image-2" src="../new_images/8.jpg" alt="" data-image-width="800" data-image-height="533">
                <h4 class="u-align-center u-text u-text-5"> Lake camping</h4>
                <p class="u-align-center u-text u-text-6">Sample text. Click to select the Text Element.</p>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-align-center-lg u-container-align-center-md u-container-align-center-sm u-container-align-center-xs u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-video-cover u-list-item-3" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-3">
                <img class="u-expanded-width u-image u-image-default u-image-3" src="../new_images/7894.jpg" alt="" data-image-width="626" data-image-height="533">
                <h4 class="u-align-center u-text u-text-7"> Beach stays</h4>
                <p class="u-align-center u-text u-text-8">Sample text. Click to select the Text Element.</p>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-video-cover u-list-item-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-4">
                <img class="u-expanded-width u-image u-image-default u-image-4" src="../new_images/53.jpg" alt="" data-image-width="800" data-image-height="533">
                <h4 class="u-align-center u-text u-text-9"> Sequoia</h4>
                <p class="u-align-center u-text u-text-10">Sample text. Click to select the Text Element.</p>
              </div>
            </div>
          </div>
        </div>
        <p class="u-align-center u-text u-text-default u-text-11" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500">Image from <a href="https://www.freepik.com" class="u-border-1 u-border-active-palette-2-base u-border-black u-border-hover-palette-2-base u-border-no-left u-border-no-right u-border-no-top u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-none u-radius-0 u-text-body-color u-top-left-radius-0 u-top-right-radius-0 u-btn-1" target="_blank">Freepik</a>
        </p>
      </div>
    </section>
    <section class="u-clearfix u-image u-shading u-section-7" data-image-width="1620" data-image-height="1080" id="block-7">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1" data-editable data-type="setting" data-key="about_contact_heading"><?= h(setting('about_contact_heading','Contact Us')) ?></h2>
                  <p class="u-text u-text-body-alt-color u-text-2" data-editable data-type="setting" data-key="about_contact_body"><?= h(setting('about_contact_body','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.')) ?></p>
                  <a href="../contact.php" class="u-active-white u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2" data-editable data-type="setting" data-key="about_contact_btn"><?= h(setting('about_contact_btn','Contact Us')) ?></a>
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
