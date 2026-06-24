<?php
require_once __DIR__ . '/config.php';
$current_page = 'about';
$db = get_db();
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="description" content="<?= h(setting('about_meta_desc', 'About us - Find yourself outside')) ?>">
  <title><?= h(setting('about_meta_title', 'About Us')) ?> | <?= h(setting('site_name', 'CampForge')) ?></title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="About.css" media="screen">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&family=Montserrat:wght@400;600;700">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
</head>
<body data-path-to-root="./" class="u-body u-clearfix u-xl-mode" data-lang="en">

<?php require __DIR__ . '/_nav.php'; ?>

<section class="u-align-center u-clearfix u-image u-shading u-section-1" id="sec-hero">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h1 class="u-align-center u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('about_hero_heading', 'Find yourself outside')) ?></h1>
    <p class="u-align-center u-large-text u-text u-text-body-alt-color u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('about_hero_sub', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
    <div class="u-clearfix u-expanded-width-xs u-layout-wrap u-layout-wrap-1">
      <div class="u-layout"><div class="u-layout-row">
        <div class="u-container-style u-layout-cell u-left-cell u-size-30" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
          <div class="u-container-layout u-container-layout-1">
            <a href="gallery.php" class="u-active-white u-border-2 u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-1"><?= h(setting('about_hero_btn1', 'View More')) ?></a>
          </div>
        </div>
        <div class="u-container-style u-layout-cell u-right-cell u-size-30" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
          <div class="u-container-layout u-container-layout-2">
            <a href="contact.php" class="u-active-palette-2-base u-border-2 u-border-white u-btn u-btn-round u-button-style u-hover-palette-2-base u-radius-50 u-text-active-black u-text-hover-black u-white u-btn-2"><?= h(setting('about_hero_btn2', 'Contact Us')) ?></a>
          </div>
        </div>
      </div></div>
    </div>
  </div>
</section>

<section class="u-clearfix u-section-2" id="sec-mission">
  <div class="u-clearfix u-sheet u-sheet-1">
    <div class="u-clearfix u-layout-wrap">
      <div class="u-layout"><div class="u-layout-row">
        <div class="u-container-style u-layout-cell u-size-30" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
          <div class="u-container-layout u-container-layout-1">
            <h4 class="u-text u-text-default u-text-1"><?= h(setting('about_mission_heading', 'Our mission')) ?></h4>
            <p class="u-text u-text-2"><?= h(setting('about_mission_sub', 'Get more people outside')) ?></p>
            <p class="u-text u-text-3"><?= h(setting('about_mission_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.')) ?></p>
          </div>
        </div>
        <div class="u-container-style u-image u-layout-cell u-size-30 u-image-1" data-image-width="690" data-image-height="726">
          <div class="u-container-layout u-container-layout-2"></div>
        </div>
      </div></div>
    </div>
  </div>
</section>

<section class="u-clearfix u-section-3" id="sec-activities">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-align-center u-text u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('about_activities_heading', 'Activities')) ?></h2>
    <div class="u-expanded-width u-list u-list-1">
      <div class="u-repeater u-repeater-1">
        <?php
        $activities = [
            ['title' => setting('about_act1_title','Adventures'),           'text' => setting('about_act1_text','Experience thrilling outdoor adventures.')],
            ['title' => setting('about_act2_title','Hiking'),               'text' => setting('about_act2_text','Explore trails through stunning landscapes.')],
            ['title' => setting('about_act3_title','Bicycling'),            'text' => setting('about_act3_text','Ride scenic routes through nature.')],
            ['title' => setting('about_act4_title','Camping'),              'text' => setting('about_act4_text','Sleep under the stars in comfort.')],
            ['title' => setting('about_act5_title','Recreation Activities'),'text' => setting('about_act5_text','Fun for the whole family.')],
            ['title' => setting('about_act6_title','Equestrian Services'),  'text' => setting('about_act6_text','Horse riding for all skill levels.')],
        ];
        foreach ($activities as $i => $act): $n = $i + 1; ?>
        <div class="u-container-style u-list-item u-repeater-item u-list-item-<?= $n ?>" data-animation-name="customAnimationIn" data-animation-duration="1500">
          <div class="u-container-layout u-similar-container u-container-layout-<?= $n ?>">
            <h5 class="u-custom-font u-text u-text-font u-text-<?= $n ?>"><?= h($act['title']) ?></h5>
            <p class="u-text u-text-<?= $n + 6 ?>"><?= h($act['text']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="u-clearfix u-palette-2-base u-section-4" id="sec-getaways">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-text u-text-body-alt-color u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('about_getaway_heading', 'Best Getaways')) ?></h2>
    <div class="u-expanded-width u-list u-list-1">
      <div class="u-repeater u-repeater-1">
        <?php
        $getaways = [
            ['img' => 'new_images/1244.jpg', 'title' => setting('about_gtw1_title','Best RV camping')],
            ['img' => 'new_images/8.jpg',    'title' => setting('about_gtw2_title','Lake camping')],
            ['img' => 'new_images/7894.jpg', 'title' => setting('about_gtw3_title','Beach stays')],
            ['img' => 'new_images/53.jpg',   'title' => setting('about_gtw4_title','Sequoia')],
        ];
        foreach ($getaways as $i => $gtw): $n = $i + 1; ?>
        <div class="u-container-style u-image u-list-item u-repeater-item u-list-item-<?= $n ?>" data-animation-width="560" data-animation-height="373" style="background-image:url('<?= h($gtw['img']) ?>');background-size:cover;background-position:center;" data-animation-name="customAnimationIn" data-animation-duration="1500">
          <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-<?= $n ?>">
            <h4 class="u-text u-text-body-alt-color u-text-<?= $n ?>"><?= h($gtw['title']) ?></h4>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
