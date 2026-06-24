<?php
require_once __DIR__ . '/config.php';
$current_page = 'gallery';
$db = get_db();
$items = $db->query('SELECT * FROM gallery_items ORDER BY sort_order ASC')->fetchAll();
if (empty($items)) {
    $default_imgs = ['new_images/3.jpg','new_images/37.jpg','new_images/fd.jpg','new_images/lifestyle-people-living-e.jpg','new_images/t5.jpg','new_images/r6.jpg','new_images/bnnnb.jpg','new_images/breathtaking-scenery-snowy-rocks-cloudy-sky-dolomiten-italy_181624-12706.webp','new_images/cvcvcv-min.jpg'];
    foreach ($default_imgs as $img) {
        $items[] = ['image' => $img, 'caption' => ''];
    }
}
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <title><?= h(setting('gallery_meta_title','Gallery')) ?> | <?= h(setting('site_name','CampForge')) ?></title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Gallery.css" media="screen">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&family=Montserrat:wght@400;600;700">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
</head>
<body data-path-to-root="./" class="u-body u-clearfix u-xl-mode" data-lang="en">

<?php require __DIR__ . '/_nav.php'; ?>

<section class="u-align-center u-clearfix u-image u-shading u-section-1" id="sec-hero">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h1 class="u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('gallery_hero_heading','Where Can I Camp?')) ?></h1>
    <p class="u-large-text u-text u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('gallery_hero_sub','Explore our stunning collection of camping destinations.')) ?></p>
  </div>
</section>

<section class="u-clearfix u-section-2" id="sec-gallery">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-align-center u-text u-text-1"><?= h(setting('gallery_section_heading','Our Photo Gallery')) ?></h2>
    <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-gallery u-layout-grid u-lightbox u-show-text-none u-gallery-1">
      <div class="u-gallery-inner u-gallery-inner-1">
        <?php foreach ($items as $i => $item): ?>
        <div class="u-effect-hover-zoom u-gallery-item u-gallery-item-<?= $i+1 ?>">
          <div class="u-back-slide">
            <img class="u-back-image u-expanded" src="<?= h($item['image']) ?>" alt="<?= h($item['caption']) ?>">
          </div>
          <?php if ($item['caption']): ?>
          <div class="u-over-slide u-shading u-over-slide-<?= $i+1 ?>">
            <p class="u-gallery-caption u-over-slide-text"><?= h($item['caption']) ?></p>
          </div>
          <?php else: ?>
          <div class="u-over-slide u-shading"></div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="u-clearfix u-palette-2-base u-section-3" id="sec-cta">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-text u-text-body-alt-color u-text-1"><?= h(setting('gallery_cta_heading','Ready to explore?')) ?></h2>
    <p class="u-text u-text-body-alt-color u-text-2"><?= h(setting('gallery_cta_text','Book your camping adventure today and create memories that last a lifetime.')) ?></p>
    <a href="contact.php" class="u-border-2 u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-body-alt-color u-text-hover-black u-btn-1"><?= h(setting('gallery_cta_btn','Book Now')) ?></a>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
