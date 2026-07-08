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
        <div class="u-clearfix u-expanded-width-sm u-expanded-width-xs u-layout-custom-sm u-layout-custom-xs u-layout-wrap u-layout-wrap-1">
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
        <p class="u-align-center u-text u-text-body-alt-color u-text-default u-text-3" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">Image from&nbsp;<a href="https://www.freepik.com" class="u-active-none u-border-1 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-border-white u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-hover-none u-none u-radius-0 u-text-body-alt-color u-top-left-radius-0 u-top-right-radius-0 u-btn-3" target="_blank">Freepik</a>
        </p>
      </div>
    </section>
    <section class="u-clearfix u-container-align-center u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h4 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="about_mission_heading"><?= h(setting('about_mission_heading','Our mission')) ?></h4>
        <h2 class="u-align-center u-text u-text-default u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="about_mission_subheading"><?= h(setting('about_mission_subheading','Get more people outside')) ?></h2>
        <p class="u-align-center u-text u-text-3" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250" data-editable data-type="setting" data-key="about_mission_body"><?= h(setting('about_mission_body','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.')) ?></p>
        <h2 class="u-align-center u-text u-text-font u-text-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-editable data-type="setting" data-key="about_mission_quote"><?= h(setting('about_mission_quote','Move with purposeful urgency.')) ?></h2>
        <p class="u-align-center u-text u-text-5" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-editable data-type="setting" data-key="about_mission_quote_body"><?= h(setting('about_mission_quote_body','We take action with the urgency that our mission deserves. We focus on learning faster so that we can invest in the most important things. We achieve more with less by creating systems at the right scale (sometimes with duct tape.) We are intentional about when we move fast and when we are more considered.')) ?></p>
        <a href="#" class="u-active-palette-2-base u-border-2 u-border-active-white u-border-hover-white u-border-palette-2-base u-btn u-btn-round u-button-style u-hover-palette-2-base u-none u-radius-50 u-text-active-white u-text-hover-white u-btn-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="750">view More</a>
      </div>
    </section>
    <section class="u-clearfix u-container-align-center u-palette-2-base u-section-3" id="block-3">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250" data-editable data-type="setting" data-key="about_s3_heading"><?= h(setting('about_s3_heading','Camping near hot springs')) ?></h2>
        <div class="u-clearfix u-expanded-width u-gutter-32 u-layout-wrap u-layout-wrap-1" data-animation-name="customAnimationIn" data-animation-duration="1750" data-animation-delay="500">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-size-30 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-size-40">
                    <div class="u-layout-row">
                      <div class="u-container-style u-image u-layout-cell u-left-cell u-size-60 u-image-1" src="" data-image-width="1380" data-image-height="920" data-bg-key="about_s3_bg1" style="background-image:url('../<?= h(setting('img_src_about_s3_bg1','new_images/3.jpg')) ?>')">
                        <div class="u-container-layout u-valign-middle u-container-layout-1"></div>
                      </div>
                    </div>
                  </div>
                  <div class="u-size-20">
                    <div class="u-layout-row">
                      <div class="u-container-style u-image u-layout-cell u-left-cell u-size-30 u-image-2" src="" data-image-width="803" data-image-height="803" data-bg-key="about_s3_bg2" style="background-image:url('../<?= h(setting('img_src_about_s3_bg2','new_images/37.jpg')) ?>')">
                        <div class="u-container-layout u-valign-middle u-container-layout-2"></div>
                      </div>
                      <div class="u-align-left u-black u-container-align-left u-container-style u-layout-cell u-size-30 u-layout-cell-3">
                        <div class="u-container-layout u-valign-middle u-container-layout-3">
                          <h3 class="u-align-left u-text u-text-2" data-editable data-type="setting" data-key="about_s3_tent_heading"><?= h(setting('about_s3_tent_heading','Tent camping')) ?></h3>
                          <p class="u-align-left u-text u-text-3" data-editable data-type="setting" data-key="about_s3_tent_body"><?= h(setting('about_s3_tent_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
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
                          <h3 class="u-align-left u-text u-text-4" data-editable data-type="setting" data-key="about_s3_lake_heading"><?= h(setting('about_s3_lake_heading','Lake camping')) ?></h3>
                          <p class="u-align-left u-text u-text-5" data-editable data-type="setting" data-key="about_s3_lake_body"><?= h(setting('about_s3_lake_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                        </div>
                      </div>
                      <div class="u-container-style u-image u-layout-cell u-right-cell u-size-30 u-image-3" src="" data-image-width="732" data-image-height="754" data-bg-key="about_s3_bg3" style="background-image:url('../<?= h(setting('img_src_about_s3_bg3','new_images/32.jpg')) ?>')">
                        <div class="u-container-layout u-valign-middle u-container-layout-5"></div>
                      </div>
                    </div>
                  </div>
                  <div class="u-size-40">
                    <div class="u-layout-row">
                      <div class="u-container-style u-image u-layout-cell u-right-cell u-size-60 u-image-4" src="" data-image-width="1380" data-image-height="920" data-bg-key="about_s3_bg4" style="background-image:url('../<?= h(setting('img_src_about_s3_bg4','new_images/4.jpg')) ?>')">
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
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="0" data-editable data-type="setting" data-key="about_s4_heading"><?= h(setting('about_s4_heading','Unique camping')) ?></h2>
        <p class="u-align-center u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-editable data-type="setting" data-key="about_s4_body"><?= h(setting('about_s4_body','Sample text. Click to select the text box. Click again or double click to start editing the text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.')) ?></p>
        <div class="data-layout-selected u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-gutter-0 u-layout">
            <div class="u-layout-row">
              <div class="u-size-20 u-size-30-md">
                <div class="u-layout-col">
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-left-cell u-size-20 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-1">
                      <h3 class="u-text u-text-default u-text-3" data-editable data-type="setting" data-key="about_s4_adventures_heading"><?= h(setting('about_s4_adventures_heading','Adventures')) ?></h3>
                      <p class="u-text u-text-4" data-editable data-type="setting" data-key="about_s4_adventures_body"><?= h(setting('about_s4_adventures_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                    </div>
                  </div>
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-left-cell u-size-20 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-2">
                      <h3 class="u-text u-text-default u-text-5" data-editable data-type="setting" data-key="about_s4_hiking_heading"><?= h(setting('about_s4_hiking_heading','Hiking')) ?></h3>
                      <p class="u-text u-text-6" data-editable data-type="setting" data-key="about_s4_hiking_body"><?= h(setting('about_s4_hiking_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                    </div>
                  </div>
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-left-cell u-size-20 u-layout-cell-3" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-3">
                      <h3 class="u-text u-text-default u-text-7" data-editable data-type="setting" data-key="about_s4_bicycling_heading"><?= h(setting('about_s4_bicycling_heading','Bicycling')) ?></h3>
                      <p class="u-text u-text-8" data-editable data-type="setting" data-key="about_s4_bicycling_body"><?= h(setting('about_s4_bicycling_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="u-size-20 u-size-30-md">
                <div class="u-layout-row">
                  <div class="u-align-left u-container-align-left u-container-style u-image u-layout-cell u-size-60 u-image-1" src="" data-image-width="800" data-image-height="1200" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-bg-key="about_s4_bg1" style="background-image:url('../<?= h(setting('img_src_about_s4_bg1','new_images/14.jpg')) ?>')">
                    <div class="u-container-layout u-valign-top u-container-layout-4" src=""></div>
                  </div>
                </div>
              </div>
              <div class="u-size-20 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-right-cell u-size-20 u-layout-cell-5" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-5">
                      <h3 class="u-text u-text-default u-text-9" data-editable data-type="setting" data-key="about_s4_camping_heading"><?= h(setting('about_s4_camping_heading','Camping')) ?></h3>
                      <p class="u-text u-text-10" data-editable data-type="setting" data-key="about_s4_camping_body"><?= h(setting('about_s4_camping_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                    </div>
                  </div>
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-right-cell u-size-20 u-layout-cell-6" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-6">
                      <h3 class="u-text u-text-default u-text-11" data-editable data-type="setting" data-key="about_s4_recreation_heading"><?= h(setting('about_s4_recreation_heading','Recreation Activities')) ?></h3>
                      <p class="u-text u-text-12" data-editable data-type="setting" data-key="about_s4_recreation_body"><?= h(setting('about_s4_recreation_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                    </div>
                  </div>
                  <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-right-cell u-size-20 u-layout-cell-7" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="250">
                    <div class="u-container-layout u-valign-top u-container-layout-7">
                      <h3 class="u-text u-text-default u-text-13" data-editable data-type="setting" data-key="about_s4_equestrian_heading"><?= h(setting('about_s4_equestrian_heading','Equestrian Services')) ?></h3>
                      <p class="u-text u-text-14" data-editable data-type="setting" data-key="about_s4_equestrian_body"><?= h(setting('about_s4_equestrian_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
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
        <div class="u-clearfix u-expanded-width u-gutter-32 u-layout-wrap u-layout-wrap-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500">
          <div class="u-gutter-0 u-layout">
            <div class="u-layout-row">
              <div class="u-size-30 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-container-style u-image u-layout-cell u-size-60 u-image-1" data-image-width="800" data-image-height="1200" data-bg-key="about_s5_bg1" style="background-image:url('../<?= h(setting('img_src_about_s5_bg1','new_images/56.jpg')) ?>')">
                    <div class="u-container-layout u-valign-middle u-container-layout-1"></div>
                  </div>
                </div>
              </div>
              <div class="u-size-30 u-size-60-md">
                <div class="u-layout-col">
                  <div class="u-size-40">
                    <div class="u-layout-row">
                      <div class="u-container-style u-image u-layout-cell u-size-60 u-image-2" data-image-width="740" data-image-height="1110" data-bg-key="about_s5_bg2" style="background-image:url('../<?= h(setting('img_src_about_s5_bg2','new_images/1244.jpg')) ?>')">
                        <div class="u-container-layout u-valign-middle u-container-layout-2"></div>
                      </div>
                    </div>
                  </div>
                  <div class="u-size-20">
                    <div class="u-layout-row">
                      <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-palette-2-base u-size-30 u-layout-cell-3">
                        <div class="u-container-layout u-valign-middle u-container-layout-3">
                          <h3 class="u-align-left u-text u-text-2" data-editable data-type="setting" data-key="about_s5_people_heading"><?= h(setting('about_s5_people_heading','People')) ?></h3>
                          <p class="u-align-left u-text u-text-3" data-editable data-type="setting" data-key="about_s5_people_body"><?= h(setting('about_s5_people_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                        </div>
                      </div>
                      <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-size-30 u-white u-layout-cell-4">
                        <div class="u-container-layout u-valign-middle u-container-layout-4">
                          <h3 class="u-align-left u-text u-text-4" data-editable data-type="setting" data-key="about_s5_values_heading"><?= h(setting('about_s5_values_heading','Our Values')) ?></h3>
                          <p class="u-align-left u-text u-text-5" data-editable data-type="setting" data-key="about_s5_values_body"><?= h(setting('about_s5_values_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <p class="u-align-center u-text u-text-body-alt-color u-text-default u-text-6" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">Images from <a href="https://www.freepik.com/" class="u-border-1 u-border-active-palette-1-base u-border-hover-palette-1-base u-border-no-left u-border-no-right u-border-no-top u-border-white u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-none u-radius-0 u-text-body-alt-color u-top-left-radius-0 u-top-right-radius-0 u-btn-1" target="_blank">Freepik</a>
        </p>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-white u-section-6" id="block-6">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="0" data-editable data-type="setting" data-key="about_s6_heading"><?= h(setting('about_s6_heading','Find your next getaway')) ?></h2>
        <p class="u-align-center u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-editable data-type="setting" data-key="about_s6_body"><?= h(setting('about_s6_body','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-align-center u-container-align-center u-container-align-center-lg u-container-align-center-md u-container-align-center-sm u-container-align-center-xs u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-video-cover u-list-item-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-1">
                <img class="u-expanded-width u-image u-image-default u-image-1" src="<?= h('../' . setting('img_src_about_s6_img1', 'new_images/1244.jpg')) ?>" alt="" data-image-width="800" data-image-height="533" data-img-key="about_s6_img1">
                <h4 class="u-align-center u-text u-text-3" data-editable data-type="setting" data-key="about_s6_item1_heading"><?= h(setting('about_s6_item1_heading','Best RV camping')) ?></h4>
                <p class="u-align-center u-text u-text-4" data-editable data-type="setting" data-key="about_s6_item1_body"><?= h(setting('about_s6_item1_body','Sample text. Click to select the Text Element.')) ?></p>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-list-item-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-2">
                <img class="u-expanded-width u-image u-image-default u-image-2" src="<?= h('../' . setting('img_src_about_s6_img2', 'new_images/8.jpg')) ?>" alt="" data-image-width="800" data-image-height="533" data-img-key="about_s6_img2">
                <h4 class="u-align-center u-text u-text-5" data-editable data-type="setting" data-key="about_s6_item2_heading"><?= h(setting('about_s6_item2_heading','Lake camping')) ?></h4>
                <p class="u-align-center u-text u-text-6" data-editable data-type="setting" data-key="about_s6_item2_body"><?= h(setting('about_s6_item2_body','Sample text. Click to select the Text Element.')) ?></p>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-align-center-lg u-container-align-center-md u-container-align-center-sm u-container-align-center-xs u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-video-cover u-list-item-3" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-3">
                <img class="u-expanded-width u-image u-image-default u-image-3" src="<?= h('../' . setting('img_src_about_s6_img3', 'new_images/7894.jpg')) ?>" alt="" data-image-width="626" data-image-height="533" data-img-key="about_s6_img3">
                <h4 class="u-align-center u-text u-text-7" data-editable data-type="setting" data-key="about_s6_item3_heading"><?= h(setting('about_s6_item3_heading','Beach stays')) ?></h4>
                <p class="u-align-center u-text u-text-8" data-editable data-type="setting" data-key="about_s6_item3_body"><?= h(setting('about_s6_item3_body','Sample text. Click to select the Text Element.')) ?></p>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-video-cover u-list-item-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-top u-container-layout-4">
                <img class="u-expanded-width u-image u-image-default u-image-4" src="<?= h('../' . setting('img_src_about_s6_img4', 'new_images/53.jpg')) ?>" alt="" data-image-width="800" data-image-height="533" data-img-key="about_s6_img4">
                <h4 class="u-align-center u-text u-text-9" data-editable data-type="setting" data-key="about_s6_item4_heading"><?= h(setting('about_s6_item4_heading','Sequoia')) ?></h4>
                <p class="u-align-center u-text u-text-10" data-editable data-type="setting" data-key="about_s6_item4_body"><?= h(setting('about_s6_item4_body','Sample text. Click to select the Text Element.')) ?></p>
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
                  <p class="u-align-left u-text u-text-3">Images from <a href="https://www.freepik.com/photos/man-with-dog" class="u-border-1 u-border-no-left u-border-no-right u-border-no-top u-border-white u-btn u-button-link u-button-style u-none u-text-body-alt-color u-btn-1">Freepik</a>
                  </p>
                  <a href="../contact.php" class="u-active-white u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2" data-editable data-type="setting" data-key="about_contact_btn"><?= h(setting('about_contact_btn','Contact Us')) ?></a>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
                <div class="u-container-layout u-valign-middle u-container-layout-2">
                  <div class="u-form u-form-1">
                    <form action="https://service.nicepagesrv.com/form/v4/form-process" class="u-clearfix u-form-spacing-30 u-form-vertical u-inner-form" style="padding: 10px" source="email" name="form">
                      <div class="u-form-email u-form-group u-form-partition-factor-2">
                        <label for="email-319a" class="u-label u-text-body-alt-color u-label-1">Email</label>
                        <input type="email" placeholder="Enter a valid email address" id="email-319a" name="email" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-group u-form-name u-form-partition-factor-2">
                        <label for="name-319a" class="u-label u-text-body-alt-color u-label-2">Name</label>
                        <input type="text" placeholder="Enter your Name" id="name-319a" name="name" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-address u-form-group u-form-group-3">
                        <label for="address-452f" class="u-label u-text-body-alt-color u-label-3">Address</label>
                        <input type="text" placeholder="Enter your address" id="address-452f" name="address" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required="">
                      </div>
                      <div class="u-form-group u-form-message">
                        <label for="message-319a" class="u-label u-text-body-alt-color u-label-4">Message</label>
                        <textarea placeholder="Enter your message" rows="4" cols="50" id="message-319a" name="message" class="u-border-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-input u-input-rectangle" required=""></textarea>
                      </div>
                      <div class="u-align-left u-form-group u-form-submit">
                        <a href="#" class="u-active-white u-border-none u-btn u-btn-round u-btn-submit u-button-style u-hover-white u-palette-2-base u-radius-50 u-btn-3">Submit</a>
                        <input type="submit" value="submit" class="u-form-control-hidden">
                      </div>
                      <div class="u-form-send-message u-form-send-success">Thank you! Your message has been sent.</div>
                      <div class="u-form-send-error u-form-send-message">Unable to send your message. Please fix errors then try again.</div>
                      <input type="hidden" value="" name="recaptchaResponse">
                      <input type="hidden" name="formServices" value="">
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
