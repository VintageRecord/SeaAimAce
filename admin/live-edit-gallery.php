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
    <section class="u-clearfix u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-list-item-1" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-1"><span class="u-align-center u-file-icon u-icon u-text-white u-icon-1"><img src="../new_images/2325148-28c38e53.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-1" data-editable data-type="setting" data-key="gallery_s2_item1"><?= h(setting('gallery_s2_item1','Trekking')) ?></h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-list-item-2" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-2"><span class="u-align-center u-file-icon u-icon u-text-white u-icon-2"><img src="../new_images/7401471-4294aa1a.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-2" data-editable data-type="setting" data-key="gallery_s2_item2"><?= h(setting('gallery_s2_item2','Camping')) ?></h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-list-item-3" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-3"><span class="u-align-center u-file-icon u-icon u-text-white u-icon-3"><img src="../new_images/931077-6ca510ad.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-3" data-editable data-type="setting" data-key="gallery_s2_item3"><?= h(setting('gallery_s2_item3','Beach Tents')) ?></h4>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-palette-2-base u-repeater-item u-shape-rectangle u-list-item-4" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500">
              <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-4"><span class="u-align-center u-file-icon u-icon u-text-white u-icon-4"><img src="../new_images/2560416-11b1db70.png" alt=""></span>
                <h4 class="u-align-center u-custom-font u-text u-text-font u-text-4" data-editable data-type="setting" data-key="gallery_s2_item4"><?= h(setting('gallery_s2_item4','News &amp; Events')) ?></h4>
              </div>
            </div>
          </div>
        </div>
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
                <img class="u-back-image u-expanded" src="<?= h('../' . setting('img_src_gallery_s3_img1', 'new_images/3.jpg')) ?>" alt="" data-img-key="gallery_s3_img1">
              </div>
              <div class="u-over-slide u-shading u-over-slide-1">
                <h3 class="u-gallery-heading" data-editable data-type="setting" data-key="gallery_s3_item1_heading"><?= h(setting('gallery_s3_item1_heading','Sample Headline')) ?></h3>
                <p class="u-gallery-text" data-editable data-type="setting" data-key="gallery_s3_item1_text"><?= h(setting('gallery_s3_item1_text','sample text')) ?></p>
              </div>
            </div>
            <div class="u-effect-fade u-effect-hover-zoom u-gallery-item">
              <div class="u-back-slide" data-image-width="1480" data-image-height="833">
                <img class="u-back-image u-expanded" src="<?= h('../' . setting('img_src_gallery_s3_img2', 'new_images/37.jpg')) ?>" alt="" data-img-key="gallery_s3_img2">
              </div>
              <div class="u-over-slide u-shading u-over-slide-2">
                <h3 class="u-gallery-heading" data-editable data-type="setting" data-key="gallery_s3_item2_heading"><?= h(setting('gallery_s3_item2_heading','Sample Headline')) ?></h3>
                <p class="u-gallery-text" data-editable data-type="setting" data-key="gallery_s3_item2_text"><?= h(setting('gallery_s3_item2_text','sample text')) ?></p>
              </div>
            </div>
            <div class="u-effect-fade u-effect-hover-zoom u-gallery-item">
              <div class="u-back-slide" data-image-width="740" data-image-height="833">
                <img class="u-back-image u-expanded" src="<?= h('../' . setting('img_src_gallery_s3_img3', 'new_images/fd.jpg')) ?>" alt="" data-img-key="gallery_s3_img3">
              </div>
              <div class="u-over-slide u-shading u-over-slide-3">
                <h3 class="u-gallery-heading" data-editable data-type="setting" data-key="gallery_s3_item3_heading"><?= h(setting('gallery_s3_item3_heading','Sample Headline')) ?></h3>
                <p class="u-gallery-text" data-editable data-type="setting" data-key="gallery_s3_item3_text"><?= h(setting('gallery_s3_item3_text','sample text')) ?></p>
              </div>
            </div>
            <div class="u-effect-fade u-effect-hover-zoom u-gallery-item">
              <div class="u-back-slide" data-image-width="800" data-image-height="800">
                <img class="u-back-image u-expanded" src="<?= h('../' . setting('img_src_gallery_s3_img4', 'new_images/lifestyle-people-living-e.jpg')) ?>" data-img-key="gallery_s3_img4">
              </div>
              <div class="u-over-slide u-shading u-over-slide-4"></div>
            </div>
            <div class="u-effect-fade u-effect-hover-zoom u-gallery-item">
              <div class="u-back-slide" data-image-width="1380" data-image-height="920">
                <img class="u-back-image u-expanded" src="<?= h('../' . setting('img_src_gallery_s3_img5', 'new_images/t5.jpg')) ?>" data-img-key="gallery_s3_img5">
              </div>
              <div class="u-over-slide u-shading u-over-slide-5"></div>
            </div>
            <div class="u-effect-fade u-effect-hover-zoom u-gallery-item">
              <div class="u-back-slide" data-image-width="800" data-image-height="1200">
                <img class="u-back-image u-expanded" src="<?= h('../' . setting('img_src_gallery_s3_img6', 'new_images/r6.jpg')) ?>" data-img-key="gallery_s3_img6">
              </div>
              <div class="u-over-slide u-shading u-over-slide-6"></div>
            </div>
          </div>
        </div>
        <p class="u-align-center u-text u-text-3">Images from <a href="https://www.freepik.com/" class="u-border-1 u-border-active-palette-3-base u-border-black u-border-hover-palette-3-base u-border-no-left u-border-no-right u-border-no-top u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-none u-radius-0 u-text-body-color u-top-left-radius-0 u-top-right-radius-0 u-btn-1" target="_blank">Freepik</a>
        </p>
        <a href="#" class="u-align-center u-border-2 u-border-palette-2-base u-btn u-btn-round u-button-style u-palette-2-base u-radius-50 u-btn-2">more photos</a>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-palette-2-base u-section-4" id="block-4">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-editable data-type="setting" data-key="gallery_sec4_heading"><?= h(setting('gallery_sec4_heading','Find your next getaway')) ?></h2>
        <p class="u-align-center u-text u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250" data-editable data-type="setting" data-key="gallery_sec4_body"><?= h(setting('gallery_sec4_body','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-align-center u-container-align-center u-container-style u-image u-list-item u-repeater-item u-shading u-shape-rectangle u-image-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500" data-image-width="800" data-image-height="533" data-bg-key="gallery_s4_bg1" style="background-image:url('../<?= h(setting('img_src_gallery_s4_bg1','new_images/1244.jpg')) ?>')">
              <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-1">
                <div class="u-black u-container-align-center u-container-style u-expanded-width u-group u-opacity u-opacity-50 u-group-1">
                  <div class="u-container-layout u-valign-middle u-container-layout-2">
                    <h4 class="u-align-center u-text u-text-3" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item1_heading"><?= h(setting('gallery_s4_item1_heading','Best RV camping')) ?></h4>
                    <p class="u-align-center u-text u-text-4" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item1_body"><?= h(setting('gallery_s4_item1_body','Sample text. Click to select the Text Element.')) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-image u-list-item u-repeater-item u-shading u-shape-rectangle u-image-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500" data-image-width="800" data-image-height="533" data-bg-key="gallery_s4_bg2" style="background-image:url('../<?= h(setting('img_src_gallery_s4_bg2','new_images/8.jpg')) ?>')">
              <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-3">
                <div class="u-black u-container-style u-expanded-width u-group u-opacity u-opacity-50 u-group-2">
                  <div class="u-container-layout u-valign-middle u-container-layout-4">
                    <h4 class="u-align-center u-text u-text-5" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item2_heading"><?= h(setting('gallery_s4_item2_heading','Lake camping')) ?></h4>
                    <p class="u-align-center u-text u-text-6" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item2_body"><?= h(setting('gallery_s4_item2_body','Sample text. Click to select the Text Element.')) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-image u-list-item u-repeater-item u-shading u-shape-rectangle u-image-3" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500" data-image-width="626" data-image-height="533" data-bg-key="gallery_s4_bg3" style="background-image:url('../<?= h(setting('img_src_gallery_s4_bg3','new_images/7894.jpg')) ?>')">
              <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-5">
                <div class="u-black u-container-align-center u-container-style u-expanded-width u-group u-opacity u-opacity-50 u-group-3">
                  <div class="u-container-layout u-valign-middle u-container-layout-6">
                    <h4 class="u-align-center u-text u-text-7" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item3_heading"><?= h(setting('gallery_s4_item3_heading','Beach stays')) ?></h4>
                    <p class="u-align-center u-text u-text-8" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item3_body"><?= h(setting('gallery_s4_item3_body','Sample text. Click to select the Text Element.')) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-image u-list-item u-repeater-item u-shading u-shape-rectangle u-image-4" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500" data-image-width="740" data-image-height="925" data-bg-key="gallery_s4_bg4" style="background-image:url('../<?= h(setting('img_src_gallery_s4_bg4','new_images/53.jpg')) ?>')">
              <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-7">
                <div class="u-black u-container-style u-expanded-width u-group u-opacity u-opacity-50 u-group-4">
                  <div class="u-container-layout u-valign-middle u-container-layout-8">
                    <h4 class="u-align-center u-text u-text-9" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item4_heading"><?= h(setting('gallery_s4_item4_heading','Backyard Camping')) ?></h4>
                    <p class="u-align-center u-text u-text-10" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item4_body"><?= h(setting('gallery_s4_item4_body','Sample text. Click to select the Text Element.')) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-image u-list-item u-repeater-item u-shading u-shape-rectangle u-image-5" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500" data-image-width="740" data-image-height="925" data-bg-key="gallery_s4_bg5" style="background-image:url('../<?= h(setting('img_src_gallery_s4_bg5','new_images/45677.jpg')) ?>')">
              <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-9">
                <div class="u-black u-container-style u-expanded-width u-group u-opacity u-opacity-50 u-group-5">
                  <div class="u-container-layout u-valign-middle u-container-layout-10">
                    <h4 class="u-align-center u-text u-text-11" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item5_heading"><?= h(setting('gallery_s4_item5_heading','car camping')) ?></h4>
                    <p class="u-align-center u-text u-text-12" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item5_body"><?= h(setting('gallery_s4_item5_body','Sample text. Click to select the Text Element.')) ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-image u-list-item u-repeater-item u-shading u-shape-rectangle u-image-6" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500" data-image-width="740" data-image-height="925" data-bg-key="gallery_s4_bg6" style="background-image:url('../<?= h(setting('img_src_gallery_s4_bg6','new_images/3570.jpg')) ?>')">
              <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-11">
                <div class="u-black u-container-style u-expanded-width u-group u-opacity u-opacity-50 u-group-6">
                  <div class="u-container-layout u-valign-middle u-container-layout-12">
                    <h4 class="u-align-center u-text u-text-13" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item6_heading"><?= h(setting('gallery_s4_item6_heading','Wilderness Camping')) ?></h4>
                    <p class="u-align-center u-text u-text-14" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500" data-editable data-type="setting" data-key="gallery_s4_item6_body"><?= h(setting('gallery_s4_item6_body','Sample text. Click to select the Text Element.')) ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <p class="u-align-center u-text u-text-default u-text-15" data-animation-name="customAnimationIn" data-animation-duration="1250" data-animation-delay="500">Image from <a href="https://www.freepik.com" class="u-border-1 u-border-active-palette-2-light-2 u-border-hover-palette-2-light-2 u-border-no-left u-border-no-right u-border-no-top u-border-white u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-none u-radius-0 u-text-body-alt-color u-top-left-radius-0 u-top-right-radius-0 u-btn-1" target="_blank">Freepik</a>
        </p>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-section-5" id="block-5">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-text u-text-default u-text-1" data-editable data-type="setting" data-key="gallery_s5_heading"><?= h(setting('gallery_s5_heading','Where to go now')) ?></h2>
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item">
              <div class="u-container-layout u-similar-container u-container-layout-1">
                <img alt="" class="u-expanded-width u-image u-image-default u-image-1" data-image-width="700" data-image-height="652" src="<?= h('../' . setting('img_src_gallery_s5_img1', 'new_images/young-rural-travellers-picnic3.jpg')) ?>" data-img-key="gallery_s5_img1">
                <div class="u-align-center u-container-align-center u-container-style u-group u-palette-2-base u-group-1">
                  <div class="u-container-layout u-valign-middle u-container-layout-2">
                    <h4 class="u-align-center u-text u-text-default u-text-2" data-editable data-type="setting" data-key="gallery_s5_item1_heading"><?= h(setting('gallery_s5_item1_heading','Hidden gems')) ?></h4>
                    <p class="u-align-center u-text u-text-3" data-editable data-type="setting" data-key="gallery_s5_item1_sub"><?= h(setting('gallery_s5_item1_sub','Sites on the rise')) ?></p>
                  </div>
                </div>
                <p class="u-align-center u-text u-text-4" data-editable data-type="setting" data-key="gallery_s5_item1_body"><?= h(setting('gallery_s5_item1_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                <a href="" class="u-align-center u-border-1 u-border-active-palette-2-base u-border-black u-border-hover-palette-2-base u-border-no-left u-border-no-right u-border-no-top u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-none u-radius-0 u-text-body-color u-text-hover-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-1">learn more</a>
              </div>
            </div>
            <div class="u-align-center u-container-align-center-sm u-container-align-center-xl u-container-align-center-xs u-container-style u-list-item u-repeater-item">
              <div class="u-container-layout u-similar-container u-container-layout-3">
                <img alt="" class="u-expanded-width u-image u-image-default u-image-2" data-image-width="700" data-image-height="652" src="<?= h('../' . setting('img_src_gallery_s5_img2', 'new_images/689.jpg')) ?>" data-img-key="gallery_s5_img2">
                <div class="u-align-center u-container-align-center u-container-style u-group u-palette-2-base u-group-2">
                  <div class="u-container-layout u-valign-middle u-container-layout-4">
                    <h4 class="u-align-center u-text u-text-default u-text-5" data-editable data-type="setting" data-key="gallery_s5_item2_heading"><?= h(setting('gallery_s5_item2_heading','Cottage stays')) ?></h4>
                    <p class="u-align-center u-text u-text-6" data-editable data-type="setting" data-key="gallery_s5_item2_sub"><?= h(setting('gallery_s5_item2_sub','Our top picks')) ?></p>
                  </div>
                </div>
                <p class="u-align-center u-text u-text-7" data-editable data-type="setting" data-key="gallery_s5_item2_body"><?= h(setting('gallery_s5_item2_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                <a href="" class="u-align-center u-border-1 u-border-active-palette-2-base u-border-black u-border-hover-palette-2-base u-border-no-left u-border-no-right u-border-no-top u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-none u-radius-0 u-text-body-color u-text-hover-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-2">learn more</a>
              </div>
            </div>
            <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item">
              <div class="u-container-layout u-similar-container u-container-layout-5">
                <img alt="" class="u-expanded-width u-image u-image-default u-image-3" data-image-width="700" data-image-height="652" src="<?= h('../' . setting('img_src_gallery_s5_img3', 'new_images/young-rural-travellers-picnic3t.jpg')) ?>" data-img-key="gallery_s5_img3">
                <div class="u-align-center u-container-align-center u-container-style u-group u-palette-2-base u-group-3">
                  <div class="u-container-layout u-valign-middle u-container-layout-6">
                    <h4 class="u-align-center u-text u-text-default u-text-8" data-editable data-type="setting" data-key="gallery_s5_item3_heading"><?= h(setting('gallery_s5_item3_heading','Glamping')) ?></h4>
                    <p class="u-align-center u-text u-text-9" data-editable data-type="setting" data-key="gallery_s5_item3_sub"><?= h(setting('gallery_s5_item3_sub','Exercitation ullamco')) ?></p>
                  </div>
                </div>
                <p class="u-align-center u-text u-text-10" data-editable data-type="setting" data-key="gallery_s5_item3_body"><?= h(setting('gallery_s5_item3_body','Sample text. Click to select the text box. Click again or double click to start editing the text.')) ?></p>
                <a href="" class="u-align-center u-border-1 u-border-active-palette-2-base u-border-black u-border-hover-palette-2-base u-border-no-left u-border-no-right u-border-no-top u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-style u-none u-radius-0 u-text-body-color u-text-hover-palette-2-base u-top-left-radius-0 u-top-right-radius-0 u-btn-3">learn more</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-clearfix u-image u-shading u-section-6" data-image-width="1620" data-image-height="1080" id="block-6">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1" data-editable data-type="setting" data-key="gallery_s6_heading"><?= h(setting('gallery_s6_heading','Contact Us')) ?></h2>
                  <p class="u-text u-text-body-alt-color u-text-2" data-editable data-type="setting" data-key="gallery_s6_body"><?= h(setting('gallery_s6_body','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.')) ?></p>
                  <a href="../contact.php" class="u-active-white u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2" data-editable data-type="setting" data-key="gallery_s6_btn"><?= h(setting('gallery_s6_btn','Contact Us')) ?></a>
                </div>
              </div>
              <div class="u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2">
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
