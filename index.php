<?php
require_once __DIR__ . '/config.php';
$db = get_db();

// Handle contact form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contact_form'])) {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name && $email) {
        $stmt = $db->prepare('INSERT INTO contact_submissions (name,email,phone,message) VALUES (?,?,?,?)');
        $stmt->execute([$name, $email, $phone, $message]);
        $form_success = true;
    } else {
        $form_error = 'Please fill in your name and email.';
    }
}

$hero_heading = setting('home_hero_heading', 'Best Camping in the National Park');
$hero_sub     = setting('home_hero_sub', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');
$hero_btn1    = setting('home_hero_btn1', 'Our Story');
$hero_btn1url = setting('home_hero_btn1url', 'about.php');
$hero_btn2    = setting('home_hero_btn2', 'Contact Us');
$hero_btn2url = setting('home_hero_btn2url', 'contact.php');
$sec2_heading = setting('home_sec2_heading', '10 Amazing Camping Tours');
$sec2_text    = setting('home_sec2_text', 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.');
$current_page = 'home';

$services = $db->query('SELECT * FROM home_services ORDER BY sort_order ASC')->fetchAll();
if (empty($services)) {
    $services = [
        ['icon_img' => 'new_images/2325148-28c38e53.png', 'title' => 'Trekking'],
        ['icon_img' => 'new_images/7401471-4294aa1a.png', 'title' => 'Camping'],
        ['icon_img' => 'new_images/931077-6ca510ad.png',  'title' => 'Beach Tents'],
        ['icon_img' => 'new_images/2560416-11b1db70.png', 'title' => 'News & Events'],
    ];
}

$activities = $db->query('SELECT * FROM home_activities ORDER BY sort_order ASC')->fetchAll();
if (empty($activities)) {
    $activities = [
        ['image' => 'new_images/32.jpg',   'title' => 'Sport activities',    'description' => 'Various outdoor sport activities for all ages.'],
        ['image' => 'new_images/1.jpg',    'title' => 'Internet Access',     'description' => 'Stay connected with high-speed Wi-Fi on site.'],
        ['image' => 'new_images/777.jpg',  'title' => 'Climbing Instructor', 'description' => 'Professional instructors for safe climbing.'],
        ['image' => 'new_images/dfdf.jpg', 'title' => 'Mountain Bikes',      'description' => 'Bike rentals available for all skill levels.'],
    ];
}
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="description" content="<?= h(setting('home_meta_desc', 'Best Camping in the National Park')) ?>">
  <title><?= h(setting('home_meta_title', 'Home')) ?> | <?= h(setting('site_name', 'CampForge')) ?></title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="index.css" media="screen">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&family=Montserrat:wght@400;600;700">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
</head>
<body data-path-to-root="./" class="u-body u-clearfix u-xl-mode" data-lang="en">

<?php require __DIR__ . '/_nav.php'; ?>

<section class="skrollable u-align-center u-clearfix u-container-align-center u-image u-shading u-section-1" src="" data-image-width="1622" data-image-height="1080" id="block-1">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h1 class="u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h($hero_heading) ?></h1>
    <p class="u-large-text u-text u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h($hero_sub) ?></p>
    <div class="u-clearfix u-expanded-width-xs u-layout-wrap u-layout-wrap-1">
      <div class="u-layout"><div class="u-layout-row">
        <div class="u-align-right-lg u-align-right-md u-align-right-xl u-align-center-sm u-align-center-xs u-container-align-right u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
          <div class="u-container-layout u-valign-middle-xs u-valign-top-lg u-container-layout-1">
            <a href="<?= h($hero_btn1url) ?>" class="u-align-right u-border-2 u-border-palette-2-base u-btn u-btn-round u-button-style u-palette-2-base u-radius-50 u-btn-1"><?= h($hero_btn1) ?></a>
          </div>
        </div>
        <div class="u-align-left-lg u-align-left-md u-align-left-xl u-align-center-sm u-align-center-xs u-container-align-left u-container-style u-layout-cell u-right-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
          <div class="u-container-layout u-valign-top u-container-layout-2">
            <a href="<?= h($hero_btn2url) ?>" class="u-active-white u-align-left u-border-2 u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2"><?= h($hero_btn2) ?></a>
          </div>
        </div>
      </div></div>
    </div>
    <div class="u-expanded-width u-list u-list-1">
      <div class="u-repeater u-repeater-1">
        <?php foreach ($services as $i => $svc): $n = $i + 1; ?>
        <div class="u-align-center u-container-align-center u-container-style u-list-item u-repeater-item u-shape-rectangle u-list-item-<?= $n ?>" data-animation-name="customAnimationIn" data-animation-duration="1500">
          <div class="u-container-layout u-similar-container u-container-layout-<?= $n + 2 ?>">
            <span class="u-file-icon u-icon u-text-white u-icon-<?= $n ?>"><img src="<?= h($svc['icon_img']) ?>" alt=""></span>
            <h4 class="u-align-center u-custom-font u-text u-text-font u-text-<?= $n + 2 ?>"><?= h($svc['title']) ?></h4>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="u-clearfix u-section-2" id="block-2">
  <div class="u-clearfix u-sheet u-valign-middle-lg u-valign-middle-md u-valign-middle-sm u-valign-middle-xl u-sheet-1">
    <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
      <div class="u-layout"><div class="u-layout-row">
        <div class="u-size-35-lg u-size-35-xl u-size-60-md u-size-60-sm u-size-60-xs">
          <div class="u-layout-col">
            <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-valign-middle u-container-layout-1">
                <h2 class="u-text u-text-1"><?= h($sec2_heading) ?></h2>
                <p class="u-text u-text-2"><?= h($sec2_text) ?></p>
                <a href="gallery.php" class="u-active-palette-2-light-1 u-border-none u-btn u-btn-round u-button-style u-hover-palette-2-light-1 u-palette-2-base u-radius-50 u-text-active-white u-text-body-alt-color u-text-hover-white u-btn-2">See Gallery</a>
              </div>
            </div>
            <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
              <div class="u-container-layout u-valign-top u-container-layout-2">
                <div class="u-expanded-width-md u-expanded-width-sm u-expanded-width-xs u-gallery u-layout-grid u-lightbox u-no-transition u-show-text-none u-gallery-1">
                  <div class="u-gallery-inner u-gallery-inner-1">
                    <?php foreach (['new_images/bnnnb.jpg','new_images/nbbnbnnnnnnnnn.jpg','new_images/b4f5b21c-2998-57d4-57d9-d0089b671caa.jpg','new_images/breathtaking-scenery-snowy-rocks-cloudy-sky-dolomiten-italy_181624-12706.webp','new_images/cvcvcv-min.jpg','new_images/d3e5609c-0bf4-4df0-853d-5cced0ca48e1.jpeg'] as $gi): ?>
                    <div class="u-effect-hover-zoom u-gallery-item">
                      <div class="u-back-slide"><img class="u-back-image u-expanded" src="<?= h($gi) ?>"></div>
                      <div class="u-over-slide u-shading"></div>
                    </div>
                    <?php endforeach; ?>
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
      </div></div>
    </div>
  </div>
</section>

<section class="u-clearfix u-palette-2-base u-section-3" id="block-3">
  <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
    <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
      <div class="u-layout"><div class="u-layout-row">
        <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-1" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250">
          <div class="u-container-layout u-container-layout-1">
            <h3 class="u-text u-text-1"><?= h(setting('home_amenities_heading', 'Available to campsite guests:')) ?></h3>
            <ul class="u-custom-list u-file-icon u-text u-text-2">
              <?php foreach (explode("\n", setting('home_amenities_list', "store (with eco products)\nchildren's playground\nclimbing tower\nvolleyball court\nbike hire\nmountain bike hire\npétanque court\ntable tennis")) as $item): $item = trim($item); if (!$item) continue; ?>
              <li>
                <div class="u-list-icon u-text-palette-2-light-2"><svg class="u-svg-content" viewBox="0 0 512 512"><path d="m433.1 67.1-231.8 231.9c-6.2 6.2-16.4 6.2-22.6 0l-99.8-99.8-78.9 78.8 150.5 150.5c10.5 10.5 24.6 16.3 39.4 16.3 14.8 0 29-5.9 39.4-16.3l282.7-282.5z" fill="currentColor"></path></svg></div>
                <?= h($item) ?>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <div class="u-container-style u-layout-cell u-size-30 u-layout-cell-2" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="500">
          <div class="u-container-layout u-container-layout-2">
            <h3 class="u-text u-text-3"><?= h(setting('home_family_heading', 'Family Camp')) ?></h3>
            <p class="u-text u-text-4"><?= h(setting('home_family_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
            <a href="contact.php" class="u-btn u-btn-round u-button-style u-none u-radius-50 u-btn-3"><?= h(setting('home_family_btn', 'Book Now')) ?></a>
          </div>
        </div>
      </div></div>
    </div>
  </div>
</section>

<section class="u-clearfix u-section-4" id="block-4">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-align-center u-text u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('home_activities_heading', 'Our Camping')) ?></h2>
    <div class="u-expanded-width u-list u-list-1">
      <div class="u-repeater u-repeater-1">
        <?php foreach ($activities as $i => $act): $n = $i + 1; ?>
        <div class="u-container-align-center-lg u-container-align-center-md u-container-style u-image u-list-item u-repeater-item u-list-item-<?= $n ?>" data-image-width="400" data-image-height="267" style="background-image:url('<?= h($act['image']) ?>');background-size:cover;background-position:center;">
          <div class="u-container-layout u-similar-container u-valign-bottom u-container-layout-<?= $n ?>">
            <div class="u-border-2 u-border-palette-2-base u-expanded-width u-line u-line-horizontal u-line-1"></div>
            <h3 class="u-text u-text-<?= $n ?>"><?= h($act['title']) ?></h3>
            <p class="u-text u-text-<?= $n + 4 ?>"><?= h($act['description']) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="u-clearfix u-section-5" id="block-5">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-text u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('home_sport_heading', 'Sport activities')) ?></h2>
    <p class="u-text u-text-2"><?= h(setting('home_sport_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')) ?></p>
    <a href="contact.php" class="u-btn u-btn-round u-button-style u-palette-2-base u-radius-50 u-btn-1"><?= h(setting('home_sport_btn', 'Contact Us')) ?></a>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
