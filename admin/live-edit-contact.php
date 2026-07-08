<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$db = get_db();
$cms_page_key   = 'live-edit-contact.php';
$cms_page_title = 'Contact';
$cms_page_css   = '../Contact.css';
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
        <h1 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="contact_hero_heading"><?= h(setting('contact_hero_heading','Plan Your Camping Trip')) ?></h1>
        <p class="u-align-center u-large-text u-text u-text-body-alt-color u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="contact_hero_subtext"><?= h(setting('contact_hero_subtext','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
      </div>
    </section>
    <section class="u-clearfix u-container-align-center u-palette-2-base u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-font u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250"> How to make reservations</h2>
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-size-30-lg u-size-30-xl u-size-60-md u-size-60-sm u-size-60-xs u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500">
                <div class="u-container-layout u-valign-top-lg u-valign-top-md u-valign-top-sm u-valign-top-xl u-container-layout-1">
                  <h3 class="u-align-left u-text u-text-2"> Follow these steps to make a reservation</h3>
                  <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-list u-list-1">
                    <div class="u-repeater u-repeater-1">
                      <div class="u-container-style u-list-item u-repeater-item">
                        <div class="u-container-layout u-similar-container u-valign-top-xs u-container-layout-2">
                          <div class="u-container-align-center u-container-style u-group u-preserve-proportions u-shape-circle u-white u-group-1">
                            <div class="u-container-layout u-valign-middle">
                              <h3 class="u-align-center u-custom-font u-text u-text-default u-text-font u-text-palette-2-base u-text-3">1</h3>
                            </div>
                          </div>
                          <p class="u-text u-text-4"> Prepare for launch day</p>
                        </div>
                      </div>
                      <div class="u-container-style u-list-item u-repeater-item">
                        <div class="u-container-layout u-similar-container u-valign-top-xs u-container-layout-4">
                          <div class="u-container-align-center u-container-style u-group u-preserve-proportions u-shape-circle u-white u-group-2">
                            <div class="u-container-layout u-valign-middle">
                              <h3 class="u-align-center u-custom-font u-text u-text-default u-text-font u-text-palette-2-base u-text-5">2</h3>
                            </div>
                          </div>
                          <p class="u-text u-text-6"> Create a new account</p>
                        </div>
                      </div>
                      <div class="u-container-style u-list-item u-repeater-item">
                        <div class="u-container-layout u-similar-container u-valign-top-xs u-container-layout-6">
                          <div class="u-container-align-center u-container-style u-group u-preserve-proportions u-shape-circle u-white u-group-3">
                            <div class="u-container-layout u-valign-middle">
                              <h3 class="u-align-center u-custom-font u-text u-text-default u-text-font u-text-palette-2-base u-text-7">3</h3>
                            </div>
                          </div>
                          <p class="u-text u-text-8"> Before you reserve</p>
                        </div>
                      </div>
                      <div class="u-container-style u-list-item u-repeater-item">
                        <div class="u-container-layout u-similar-container u-valign-top-xs u-container-layout-8">
                          <div class="u-container-align-center u-container-style u-group u-preserve-proportions u-shape-circle u-white u-group-4">
                            <div class="u-container-layout u-valign-middle">
                              <h3 class="u-align-center u-custom-font u-text u-text-default u-text-font u-text-palette-2-base u-text-9">4</h3>
                            </div>
                          </div>
                          <p class="u-text u-text-10"> Reserve</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="u-container-align-left u-container-style u-layout-cell u-size-30-lg u-size-30-xl u-size-60-md u-size-60-sm u-size-60-xs u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-top u-container-layout-10">
                  <h3 class="u-align-left u-text u-text-default u-text-11"> If your first choice of campsite or accommodation isn't available</h3>
                  <ul class="u-align-left u-custom-list u-file-icon u-spacing-20 u-text u-text-default u-text-12">
                    <li style="padding-left: 10px;"><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512" id="svg-30e6"><path d="m202.6 478-202.6-186.6 70.5-76.6 121.5 111.9 239.4-292.7 80.6 65.9z" fill="currentColor"></path></svg></div> Select the campsite to open the site description. Select "Site Calendar" to see a monthly calendar overview of when that site is available</li>
                    <li style="padding-left: 10px;"><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512" id="svg-30e6"><path d="m202.6 478-202.6-186.6 70.5-76.6 121.5 111.9 239.4-292.7 80.6 65.9z" fill="currentColor"></path></svg></div>At the campground loop level, select the "Calendar" button near the map to see a calendar overview of when all sites in that campground are available.</li>
                    <li style="padding-left: 10px;"><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512" id="svg-30e6"><path d="m202.6 478-202.6-186.6 70.5-76.6 121.5 111.9 239.4-292.7 80.6 65.9z" fill="currentColor"></path></svg></div>If you want to stay multiple nights, and different sites are available for different portions of your stay, consider a night-by-night reservation by selecting "Build Your Stay"</li>
                    <li style="padding-left: 10px;"><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512" id="svg-30e6"><path d="m202.6 478-202.6-186.6 70.5-76.6 121.5 111.9 239.4-292.7 80.6 65.9z" fill="currentColor"></path></svg></div>Navigate to other areas of the campground on the map. Use the breadcrumb links to go back and choose another campground within that park</li>
                    <li style="padding-left: 10px;"><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512" id="svg-30e6"><path d="m202.6 478-202.6-186.6 70.5-76.6 121.5 111.9 239.4-292.7 80.6 65.9z" fill="currentColor"></path></svg></div>Look at campgrounds in other parks by changing your park selection</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-palette-5-light-2 u-valign-top-lg u-valign-top-xl u-section-3" id="block-3">
      <div class="custom-expanded u-container-align-center u-container-style u-expanded-width-lg u-expanded-width-xl u-group u-palette-2-base u-shape-rectangle u-group-1">
        <div class="u-container-layout u-valign-top u-container-layout-1">
          <h1 class="u-align-center u-text u-text-body-alt-color u-text-1"> Why Camp?</h1>
        </div>
      </div>
      <div class="data-layout-selected u-clearfix u-gutter-20 u-layout-wrap u-layout-wrap-1">
        <div class="u-layout">
          <div class="u-layout-row">
            <div class="u-size-30 u-size-60-md">
              <div class="u-layout-col">
                <div class="u-size-40">
                  <div class="u-layout-row">
                    <div class="u-container-align-center u-container-style u-image u-layout-cell u-shading u-size-60 u-image-1" data-image-width="1380" data-image-height="920">
                      <div class="u-container-layout u-valign-middle u-container-layout-2">
                        <h3 class="u-align-center u-text u-text-default u-text-2"> Develop Life Skills</h3>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="u-size-20">
                  <div class="u-layout-row">
                    <div class="u-container-align-center u-container-style u-image u-layout-cell u-shading u-size-30 u-image-2" data-image-width="800" data-image-height="800">
                      <div class="u-container-layout u-valign-middle u-container-layout-3">
                        <h3 class="u-align-center u-text u-text-default u-text-3"> Tradition</h3>
                      </div>
                    </div>
                    <div class="u-container-align-center u-container-style u-image u-layout-cell u-shading u-size-30 u-image-3" data-image-width="740" data-image-height="925">
                      <div class="u-container-layout u-valign-middle u-container-layout-4">
                        <h3 class="u-align-center u-text u-text-default u-text-4"> Digital Detox</h3>
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
                    <div class="u-container-align-center u-container-style u-image u-layout-cell u-shading u-size-30 u-image-4" data-image-width="800" data-image-height="533">
                      <div class="u-container-layout u-valign-middle u-container-layout-5">
                        <h3 class="u-align-center u-text u-text-default u-text-5"> Improve Health</h3>
                      </div>
                    </div>
                    <div class="u-container-align-center u-container-style u-image u-layout-cell u-shading u-size-30 u-image-5" data-image-width="1480" data-image-height="833">
                      <div class="u-container-layout u-valign-middle u-container-layout-6">
                        <h3 class="u-align-center u-text u-text-default u-text-6"> Explore Nature</h3>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="u-size-40">
                  <div class="u-layout-row">
                    <div class="u-container-align-center u-container-style u-image u-layout-cell u-shading u-size-60 u-image-6" data-image-width="732" data-image-height="754">
                      <div class="u-container-layout u-valign-middle u-container-layout-7">
                        <h3 class="u-align-center u-text u-text-default u-text-7"> Strengthen Relationships</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p class="u-align-center u-text u-text-default u-text-8">Images from <a href="https://www.freepik.com/" class="u-active-none u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-5-dark-2 u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-hover-none u-none u-radius-0 u-text-active-black u-text-body-color u-text-hover-black u-top-left-radius-0 u-top-right-radius-0 u-btn-1" target="_blank">Freepik</a>
      </p>
    </section>
    <section class="u-clearfix u-image u-shading u-section-4" data-image-width="1620" data-image-height="1080" id="block-4">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1" data-editable data-type="setting" data-key="contact_cta_heading"><?= h(setting('contact_cta_heading','Contact Us')) ?></h2>
                  <p class="u-text u-text-body-alt-color u-text-2" data-editable data-type="setting" data-key="contact_cta_body"><?= h(setting('contact_cta_body','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.')) ?></p>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500">
                <div class="u-container-layout u-valign-middle u-container-layout-2">
                  <div class="u-form u-form-1">
                    <form action="../contact.php" method="POST" class="u-clearfix u-form-spacing-30 u-form-vertical u-inner-form" style="padding: 10px">
                      <div class="u-form-email u-form-group u-form-partition-factor-2">
                        <label class="u-label u-text-body-alt-color u-label-1">Email</label>
                        <input type="email" placeholder="Enter a valid email address" name="email" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-group u-form-name u-form-partition-factor-2">
                        <label class="u-label u-text-body-alt-color u-label-2">Name</label>
                        <input type="text" placeholder="Enter your Name" name="name" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-group u-form-message">
                        <label class="u-label u-text-body-alt-color u-label-4">Message</label>
                        <textarea placeholder="Enter your message" rows="4" cols="50" name="message" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required=""></textarea>
                      </div>
                      <div class="u-align-left u-form-group u-form-submit">
                        <button type="submit" class="u-active-white u-border-none u-btn u-btn-round u-button-style u-hover-white u-palette-2-base u-radius-50" style="cursor:pointer">Submit</button>
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
