<?php
require_once __DIR__ . '/config.php';
$db = get_db();
$current_page = 'index';
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="description" content="<?= h(setting('home_meta_title_desc','')) ?>">
  <title><?= h(setting('home_meta_title','Home')) ?> | <?= h(setting('site_name','CampForge')) ?></title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="index.css" media="screen">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&family=Montserrat:wght@400;600;700">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
</head>
<body data-path-to-root="./" class="u-body u-clearfix u-xl-mode" data-lang="en">
<?php require __DIR__ . '/_nav.php'; ?>
 
    <section class="skrollable skrollable-between u-align-center u-clearfix u-container-align-center u-image u-shading u-section-1" src="" data-image-width="1622" data-image-height="1080" id="block-1">
      <div class="u-clearfix u-sheet u-sheet-1">
        <h1 class="u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('home_hero_heading','Best Camping in the National Park')) ?></h1>
        <p class="u-large-text u-text u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('home_hero_subtext','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit')) ?></p>
        <div class="u-clearfix u-expanded-width-xs u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-align-center-sm u-align-center-xs u-align-right-lg u-align-right-md u-align-right-xl u-container-align-right u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-middle-xs u-valign-top-lg u-valign-top-md u-valign-top-sm u-valign-top-xl u-container-layout-1">
                  <a href="about.php" class="u-align-right u-border-2 u-border-palette-2-base u-btn u-btn-round u-button-style u-palette-2-base u-radius-50 u-btn-1"><?= h(setting('home_hero_btn1','Our story')) ?></a>
                </div>
              </div>
              <div class="u-align-center-sm u-align-center-xs u-align-left-lg u-align-left-md u-align-left-xl u-container-align-left u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-top u-container-layout-2">
                  <a href="contact.php" class="u-active-white u-align-left u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2"><?= h(setting('home_hero_btn2','Contact Us')) ?></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-1" data-animation-name="customAnimationIn" data-animation-duration="1500">
              <div class="u-container-layout u-similar-container u-container-layout-3"><span class="u-file-icon u-icon u-text-white u-icon-1"><img src="new_images/2325148-28c38e53.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-3"><?= h(setting('home_icon1_label','Trekking')) ?></h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-2" data-animation-name="customAnimationIn" data-animation-duration="1500">
              <div class="u-container-layout u-similar-container u-container-layout-4"><span class="u-file-icon u-icon u-text-white u-icon-2"><img src="new_images/7401471-4294aa1a.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-4"><?= h(setting('home_icon2_label','Camping')) ?></h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-3" data-animation-name="customAnimationIn" data-animation-duration="1500">
              <div class="u-container-layout u-similar-container u-container-layout-5"><span class="u-file-icon u-icon u-text-white u-icon-3"><img src="new_images/931077-6ca510ad.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-5"><?= h(setting('home_icon3_label','Beach Tents')) ?></h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-4" data-animation-name="customAnimationIn" data-animation-duration="1500">
              <div class="u-container-layout u-similar-container u-container-layout-6"><span class="u-file-icon u-icon u-text-white u-icon-4"><img src="new_images/2560416-11b1db70.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-6"><?= h(setting('home_icon4_label','News &amp; Events')) ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-valign-middle-lg u-valign-middle-md u-valign-middle-sm u-valign-middle-xl u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-size-35-lg u-size-35-xl u-size-60-md u-size-60-sm u-size-60-xs">
                <div class="u-layout-col">
                  <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                    <div class="u-container-layout u-valign-middle u-container-layout-1">
                      <h2 class="u-text u-text-1"><?= h(setting('home_sec2_heading','10 Amazing Camping Tours')) ?></h2>
                      <p class="u-text u-text-2"><?= h(setting('home_sec2_body','Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.')) ?></p>
                      <a href="about.php" class="u-active-palette-2-light-1 u-border-none u-btn u-btn-round u-button-style u-hover-palette-2-light-1 u-palette-2-base u-radius-50 u-text-active-white u-text-body-alt-color u-text-hover-white u-btn-2"><?= h(setting('home_sec2_btn','learn more')) ?></a>
                    </div>
                  </div>
                  <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                    <div class="u-container-layout u-valign-top u-container-layout-2">
                      <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-gallery u-layout-grid u-lightbox u-no-transition u-show-text-none u-gallery-1">
                        <div class="u-gallery-inner u-gallery-inner-1">
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="887" data-image-height="887">
                              <img class="u-back-image u-expanded" src="new_images/bnnnb.jpg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-1"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="696" data-image-height="696">
                              <img class="u-back-image u-expanded" src="new_images/nbbnbnnnnnnnnn.jpg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-2"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="700" data-image-height="976">
                              <img class="u-back-image u-expanded" src="new_images/b4f5b21c-2998-57d4-57d9-d0089b671caa.jpg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-3"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="1380" data-image-height="987">
                              <img class="u-back-image u-expanded" src="new_images/breathtaking-scenery-snowy-rocks-cloudy-sky-dolomiten-italy_181624-12706.webp">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-4"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="1920" data-image-height="737">
                              <img class="u-back-image u-expanded" src="new_images/cvcvcv-min.jpg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-5"></div>
                          </div>
                          <div class="u-effect-hover-zoom u-gallery-item">
                            <div class="u-back-slide" data-image-width="720" data-image-height="1080">
                              <img class="u-back-image u-expanded" src="new_images/d3e5609c-0bf4-4df0-853d-5cced0ca48e1.jpeg">
                            </div>
                            <div class="u-over-slide u-shading u-over-slide-6"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="u-size-25-lg u-size-25-xl u-size-60-md u-size-60-sm u-size-60-xs">
                <div class="u-layout-col">
                  <div class="u-container-style u-image u-layout-cell u-size-60 u-image-1" data-image-width="717" data-image-height="1080" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                    <div class="u-container-layout u-container-layout-3"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-palette-2-base u-section-3" id="block-3">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-container-layout u-container-layout-1">
                  <h3 class="u-text u-text-1"><?= h(setting('home_amenities_heading','Available to campsite guests:')) ?></h3>
                  <ul class="u-custom-list u-file-icon u-text u-text-2">
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_amen_li1','store (with eco products)')) ?></li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_amen_li2',"children's playground with a climbing wall")) ?></li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_amen_li3','climbing tower * (8 m high)')) ?></li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_amen_li4','volleyball court')) ?></li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_amen_li5','bike hire (also for children)')) ?></li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_amen_li6','internet access')) ?></li>
                  </ul>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                <div class="u-container-layout u-container-layout-2">
                  <h3 class="u-text u-text-3"><?= h(setting('home_camp_list_heading','In the campsite, you can:')) ?></h3>
                  <ul class="u-custom-list u-text u-text-4">
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_camp_li1','hire a climbing instructor')) ?></li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_camp_li2','buy kayaking permits')) ?></li>
                    <li><div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div><?= h(setting('home_camp_li3','tandem paragliding available')) ?></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-section-4" id="block-4">
      <div class="u-container-style u-expanded-width u-group u-image u-shading u-image-1" data-image-width="1620" data-image-height="1080">
        <div class="u-container-layout u-valign-top u-container-layout-1">
          <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('home_activities_heading','Our Services')) ?></h2>
        </div>
      </div>
      <div class="u-list u-list-1">
        <div class="u-repeater u-repeater-1">
          <div class="u-align-center u-border-1 u-border-palette-2-base u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-white u-list-item-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
            <div class="u-container-layout u-similar-container u-valign-top u-container-layout-2">
              <img class="u-expanded-width u-image u-image-default u-image-2" src="new_images/32.jpg" alt="" data-image-width="900" data-image-height="600">
              <h4 class="u-hover-feature u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500"><?= h(setting('home_svc1_title','Sport Activities')) ?></h4>
              <p class="u-hover-feature u-text u-text-3"><?= h(setting('home_svc1_body','Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt')) ?></p>
              <a href="gallery.php" class="u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-hover-feature u-none u-radius-0 u-text-active-palette-2-base u-text-hover-palette-2-base u-text-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-1"><?= h(setting('home_svc1_btn','more')) ?></a>
            </div>
          </div>
          <div class="u-align-center u-border-1 u-border-palette-2-base u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-video-cover u-white u-list-item-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
            <div class="u-container-layout u-similar-container u-valign-top u-container-layout-3">
              <img class="u-expanded-width u-image u-image-default u-image-3" src="new_images/1.jpg" alt="" data-image-width="900" data-image-height="600">
              <h4 class="u-hover-feature u-text u-text-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500"><?= h(setting('home_svc2_title','Internet Access')) ?></h4>
              <p class="u-hover-feature u-text u-text-5"><?= h(setting('home_svc2_body','Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt')) ?></p>
              <a href="gallery.php" class="u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-hover-feature u-none u-radius-0 u-text-active-palette-2-base u-text-hover-palette-2-base u-text-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-2"><?= h(setting('home_svc2_btn','more')) ?></a>
            </div>
          </div>
          <div class="u-align-center u-border-1 u-border-palette-2-base u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-video-cover u-white u-list-item-3" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
            <div class="u-container-layout u-similar-container u-valign-top u-container-layout-4">
              <img class="u-expanded-width u-image u-image-default u-image-4" src="new_images/777.jpg" alt="" data-image-width="900" data-image-height="600">
              <h4 class="u-hover-feature u-text u-text-6" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500"><?= h(setting('home_svc3_title','Climbing Instructor')) ?></h4>
              <p class="u-hover-feature u-text u-text-7"><?= h(setting('home_svc3_body','Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt')) ?></p>
              <a href="gallery.php" class="u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-hover-feature u-none u-radius-0 u-text-active-palette-2-base u-text-hover-palette-2-base u-text-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-3"><?= h(setting('home_svc3_btn','more')) ?></a>
            </div>
          </div>
          <div class="u-align-center u-border-1 u-border-palette-2-base u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-video-cover u-white u-list-item-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
            <div class="u-container-layout u-similar-container u-valign-top u-container-layout-5">
              <img class="u-expanded-width u-image u-image-default u-image-5" src="new_images/dfdf.jpg" alt="" data-image-width="900" data-image-height="600">
              <h4 class="u-hover-feature u-text u-text-8" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500"><?= h(setting('home_svc4_title','Mountain Bikes')) ?></h4>
              <p class="u-hover-feature u-text u-text-9"><?= h(setting('home_svc4_body','Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt')) ?></p>
              <a href="gallery.php" class="u-border-1 u-border-active-black u-border-hover-black u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-hover-feature u-none u-radius-0 u-text-active-palette-2-base u-text-hover-palette-2-base u-text-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-4"><?= h(setting('home_svc4_btn','more')) ?></a>
            </div>
          </div>
        </div>
      </div>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
    </section>
    <section class="u-clearfix u-section-5" id="block-5">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-gutter-0 u-layout">
            <div class="u-layout-row">
              <div class="u-container-align-left u-container-style u-layout-cell u-shape-rectangle u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-top u-container-layout-1">
                  <h2 class="u-align-left u-text u-text-1"><?= h(setting('home_ourcamp_heading','Our Camping')) ?></h2>
                  <p class="u-align-left u-text u-text-2"><?= h(setting('home_ourcamp_body','Podcasting operational change management inside of workflows to establish a framework. Taking seamless key performance indicators offline to maximise the long tail. Keeping your eye on the ball while performing a deep dive on the start-up mentality to derive convergence on cross-platform integration.')) ?></p>
                  <a href="about.php" class="u-active-palette-2-light-1 u-align-left u-border-none u-btn u-btn-round u-button-style u-hover-palette-2-light-1 u-palette-2-base u-radius-50 u-text-active-white u-text-body-alt-color u-text-hover-white u-btn-1"><?= h(setting('home_ourcamp_btn','learn more')) ?></a>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
                <div class="u-container-layout u-valign-top u-container-layout-2">
                  <h4 class="u-custom-font u-text u-text-font u-text-3"><?= h(setting('home_guide_heading','National Park Service Camping Guide')) ?></h4>
                  <p class="u-text u-text-4"><?= h(setting('home_guide_body1','Podcasting operational change management inside of workflows to establish a framework. Taking seamless key performance indicators offline to maximise the long tail.')) ?></p>
                  <p class="u-text u-text-palette-2-base u-text-5"><?= h(setting('home_guide_body2','Article evident arrived express highest men did boy. Mistress sensible entirely am so. Quick can manor smart money hopes worth too.')) ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-image u-section-6" data-image-width="1620" data-image-height="1080" id="block-6">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-gutter-0 u-layout">
            <div class="u-layout-row">
              <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-size-30 u-white u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1"><?= h(setting('home_family_heading','Family Camp')) ?></h2>
                  <p class="u-text u-text-default u-text-2"><?= h(setting('home_family_body','The trekking in the enchanting mountains or rafting in the wild rivers, exploring the dense forest, canyoning in the refreshing waterfall, gliding across the highest peaks and the beautiful valley etc. are some of the adventures you can imagine.')) ?></p>
                  <a href="about.php" class="u-active-palette-2-light-1 u-border-none u-btn u-btn-round u-button-style u-hover-palette-2-light-1 u-palette-2-base u-radius-50 u-text-active-white u-text-body-alt-color u-text-hover-white u-btn-2"><?= h(setting('home_family_btn','learn more')) ?></a>
                </div>
              </div>
              <div class="u-container-style u-image u-layout-cell u-size-30 u-image-1" data-image-width="721" data-image-height="1080" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-border-20 u-border-white u-container-layout u-container-layout-2"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-white u-section-7" id="block-7">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-30 u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-size-30 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-container-style u-layout-cell u-left-cell u-similar-fill u-size-40 u-layout-cell-1">
                    <div class="u-container-layout u-valign-middle u-container-layout-1">
                      <h2 class="u-custom-font u-text u-text-1"> Sport activities</h2>
                      <ul class="u-custom-list u-text u-text-2">
                        <li style="padding-left: 8px;">
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 512 512" id="svg-838e"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg>
                          </div> Climbing tower 
                        </li>
                        <li style="padding-left: 8px;">
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 512 512" id="svg-838e"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg>
                          </div>Table tennis 
                        </li>
                        <li style="padding-left: 8px;">
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 512 512" id="svg-838e"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg>
                          </div>Volleyball 
                        </li>
                        <li style="padding-left: 8px;">
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 512 512" id="svg-838e"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg>
                          </div>Bowling 
                        </li>
                        <li style="padding-left: 8px;">
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 512 512" id="svg-838e"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg>
                          </div>Climbing wall for children 
                        </li>
                        <li style="padding-left: 8px;">
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 512 512" id="svg-838e"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg>
                          </div>Gym room
                        </li>
                      </ul>
                      <p class="u-text u-text-3" data-animation-name="customAnimationIn" data-animation-duration="1500">Images from <a href="https://www.freepik.com/photos/happy-couple" class="u-active-none u-border-1 u-border-active-palette-2-base u-border-black u-border-hover-palette-2-base u-border-no-left u-border-no-right u-border-no-top u-btn u-button-link u-button-style u-hover-none u-none u-text-body-color u-btn-1" target="_blank">Freepik</a>
                      </p>
                    </div>
                  </div>
                  <div class="u-container-style u-image u-layout-cell u-left-cell u-similar-fill u-size-20 u-image-1" data-image-width="1380" data-image-height="920" data-animation-name="customAnimationIn" data-animation-duration="1500">
                    <div class="u-container-layout"></div>
                  </div>
                </div>
              </div>
              <div class="u-size-30 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-container-style u-image u-layout-cell u-right-cell u-similar-fill u-size-20 u-image-2" data-image-width="1380" data-image-height="920" data-animation-name="customAnimationIn" data-animation-duration="1500">
                    <div class="u-container-layout"></div>
                  </div>
                  <div class="u-container-style u-layout-cell u-right-cell u-similar-fill u-size-40 u-layout-cell-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                    <div class="u-container-layout u-valign-middle u-container-layout-4">
                      <p class="u-text u-text-default u-text-4"><span style="font-weight: 700;"> Amet luctus venenatis lectus magna fringilla urna porttitor rhoncus dolor. A lacus vestibulum sed arcu non. Dolor magna eget est lorem ipsum dolor sit amet consectetur.</span>
                        <br><span class="u-text-palette-2-base">Nec feugiat nisl pretium fusce id. Justo laoreet sit amet cursus sit amet. Porta non pulvinar neque laoreet suspendisse interdum consectetur libero.</span>
                      </p>
                      <ul class="u-custom-font u-custom-list u-font-montserrat u-spacing-12 u-text u-text-5">
                        <li>
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 415.994 415.994" id="svg-2b0f"><path d="m391.645 193.946-352-192c-4.96-2.688-10.976-2.592-15.808.288-4.864 2.88-7.84 8.128-7.84 13.76v384c0 5.664 2.976 10.88 7.84 13.76 2.496 1.504 5.344 2.24 8.16 2.24 2.656 0 5.28-.672 7.648-1.984l352-192c5.152-2.752 8.352-8.16 8.352-14.016s-3.2-11.264-8.352-14.048z" fill="currentColor"></path></svg>
                          </div> Rhoncus urna neque viverra 
                        </li>
                        <li>
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 415.994 415.994" id="svg-2b0f"><path d="m391.645 193.946-352-192c-4.96-2.688-10.976-2.592-15.808.288-4.864 2.88-7.84 8.128-7.84 13.76v384c0 5.664 2.976 10.88 7.84 13.76 2.496 1.504 5.344 2.24 8.16 2.24 2.656 0 5.28-.672 7.648-1.984l352-192c5.152-2.752 8.352-8.16 8.352-14.016s-3.2-11.264-8.352-14.048z" fill="currentColor"></path></svg>
                          </div> Lobortis feugiat vivamus at augue 
                        </li>
                        <li>
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 415.994 415.994" id="svg-2b0f"><path d="m391.645 193.946-352-192c-4.96-2.688-10.976-2.592-15.808.288-4.864 2.88-7.84 8.128-7.84 13.76v384c0 5.664 2.976 10.88 7.84 13.76 2.496 1.504 5.344 2.24 8.16 2.24 2.656 0 5.28-.672 7.648-1.984l352-192c5.152-2.752 8.352-8.16 8.352-14.016s-3.2-11.264-8.352-14.048z" fill="currentColor"></path></svg>
                          </div> Eget lorem dolor sed viverra
                        </li>
                        <li>
                          <div class="u-list-icon u-text-palette-2-base">
                            <svg class="u-svg-content" viewBox="0 0 415.994 415.994" id="svg-2b0f"><path d="m391.645 193.946-352-192c-4.96-2.688-10.976-2.592-15.808.288-4.864 2.88-7.84 8.128-7.84 13.76v384c0 5.664 2.976 10.88 7.84 13.76 2.496 1.504 5.344 2.24 8.16 2.24 2.656 0 5.28-.672 7.648-1.984l352-192c5.152-2.752 8.352-8.16 8.352-14.016s-3.2-11.264-8.352-14.048z" fill="currentColor"></path></svg>
                          </div>Odio facilisis mauris sit amet massa vitae&nbsp;<br>
                        </li>
                      </ul>
                      <a href="about.php" class="u-active-palette-2-light-1 u-border-none u-btn u-btn-round u-button-style u-hover-palette-2-light-1 u-palette-2-base u-radius-50 u-text-active-white u-text-body-alt-color u-text-hover-white u-btn-2">learn more</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-image u-shading u-section-8" data-image-width="1620" data-image-height="1080" id="block-8">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1750" data-animation-delay="250">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1"><?= h(setting('home_contact_heading','Contact Us')) ?></h2>
                  <p class="u-text u-text-body-alt-color u-text-2"><?= h(setting('home_contact_body','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.')) ?></p>
                  <a href="contact.php" class="u-active-white u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2"><?= h(setting('home_contact_btn','Contact Us')) ?></a>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500">
                <div class="u-container-layout u-valign-middle u-container-layout-2">
                  <div class="u-form u-form-1">
                    <form action="https://service.nicepagesrv.com/form/v4/form-process" class="u-clearfix u-form-spacing-30 u-form-vertical u-inner-form" style="padding: 10px" source="email" name="form">
                      <div class="u-form-email u-form-group u-form-partition-factor-2">
                        <label for="email-319a" class="u-label u-text-body-alt-color u-label-1">Email</label>
                        <input type="email" placeholder="Enter a valid email address" id="email-319a" name="email" class="u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-group u-form-name u-form-partition-factor-2">
                        <label for="name-319a" class="u-label u-text-body-alt-color u-label-2">Name</label>
                        <input type="text" placeholder="Enter your Name" id="name-319a" name="name" class="u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-address u-form-group u-form-group-3">
                        <label for="address-452f" class="u-label u-text-body-alt-color u-label-3">Address</label>
                        <input type="text" placeholder="Enter your address" id="address-452f" name="address" class="u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-group u-form-message">
                        <label for="message-319a" class="u-label u-text-body-alt-color u-label-4">Message</label>
                        <textarea placeholder="Enter your message" rows="4" cols="50" id="message-319a" name="message" class="u-input u-input-rectangle" required=""></textarea>
                      </div>
                      <div class="u-align-left u-form-group u-form-submit">
                        <a href="#" class="u-active-white u-border-none u-btn u-btn-round u-btn-submit u-button-style u-hover-white u-palette-2-base u-radius-50 u-btn-3">Submit</a>
                        <input type="submit" value="submit" class="u-form-control-hidden">
                      </div>
                      <div class="u-form-send-message u-form-send-success">Thank you! Your message has been sent.</div>
                      <div class="u-form-send-error u-form-send-message">Unable to send your message. Please fix errors then try again.</div>
                      <input type="hidden" value="" name="recaptchaResponse">
                      <input type="hidden" name="formServices" value="287d277f-d596-0f54-76da-095a5b7a32ec">
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    
    
    <?php require __DIR__ . '/_footer.php'; ?>
