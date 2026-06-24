<?php
require_once __DIR__ . '/config.php';
$current_page = 'team';
$db = get_db();
$members = $db->query('SELECT * FROM team_members ORDER BY sort_order ASC')->fetchAll();
if (empty($members)) {
    $members = [
        ['name'=>'Ann Brown',     'role'=>'Camp Director',   'bio'=>'', 'image'=>'new_images/01.png', 'fb_url'=>'#','tw_url'=>'#','ig_url'=>'#'],
        ['name'=>'David Villegas','role'=>'Lead Guide',      'bio'=>'', 'image'=>'new_images/02.png', 'fb_url'=>'#','tw_url'=>'#','ig_url'=>'#'],
        ['name'=>'Clayton Lane',  'role'=>'Safety Officer',  'bio'=>'', 'image'=>'new_images/03.png', 'fb_url'=>'#','tw_url'=>'#','ig_url'=>'#'],
        ['name'=>'Robert Fifield','role'=>'Activities Coach','bio'=>'', 'image'=>'new_images/04.png', 'fb_url'=>'#','tw_url'=>'#','ig_url'=>'#'],
        ['name'=>'Dan Spinello',  'role'=>'Chef',            'bio'=>'', 'image'=>'new_images/05.png', 'fb_url'=>'#','tw_url'=>'#','ig_url'=>'#'],
        ['name'=>'Dwight Atkins', 'role'=>'Site Manager',   'bio'=>'', 'image'=>'new_images/06.png', 'fb_url'=>'#','tw_url'=>'#','ig_url'=>'#'],
    ];
}
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <title><?= h(setting('team_meta_title','Our Team')) ?> | <?= h(setting('site_name','CampForge')) ?></title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Team.css" media="screen">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&family=Montserrat:wght@400;600;700">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
</head>
<body data-path-to-root="./" class="u-body u-clearfix u-xl-mode" data-lang="en">

<?php require __DIR__ . '/_nav.php'; ?>

<section class="u-align-center u-clearfix u-image u-shading u-section-1" id="sec-hero">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h1 class="u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('team_hero_heading','Our team is looking forward')) ?></h1>
    <p class="u-text u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('team_hero_sub','We are a passionate team dedicated to delivering unforgettable camping experiences.')) ?></p>
  </div>
</section>

<section class="u-clearfix u-section-2" id="sec-team">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-align-center u-text u-text-1"><?= h(setting('team_section_heading','Meet The Team')) ?></h2>
    <div class="u-expanded-width u-list u-list-1">
      <div class="u-repeater u-repeater-1">
        <?php foreach ($members as $i => $m): $n = $i + 1; ?>
        <div class="u-align-center u-container-style u-list-item u-repeater-item u-list-item-<?= $n ?>" data-animation-name="customAnimationIn" data-animation-duration="1500">
          <div class="u-container-layout u-similar-container u-container-layout-<?= $n ?>">
            <div class="u-image u-image-circle u-image-<?= $n ?>" style="background-image:url('<?= h($m['image']) ?>');width:160px;height:160px;border-radius:50%;margin:0 auto 16px;background-size:cover;background-position:center;"></div>
            <h4 class="u-custom-font u-text u-text-font u-text-<?= $n ?>"><?= h($m['name']) ?></h4>
            <p class="u-text u-text-grey-50 u-text-<?= $n + 6 ?>"><?= h($m['role']) ?></p>
            <?php if ($m['bio']): ?><p class="u-text u-text-<?= $n + 12 ?>"><?= h($m['bio']) ?></p><?php endif; ?>
            <div class="u-social-icons u-spacing-10 u-social-icons-1" style="display:flex;gap:10px;justify-content:center;margin-top:12px;">
              <?php if ($m['fb_url'] && $m['fb_url'] !== '#'): ?><a class="u-social-url" title="Facebook" href="<?= h($m['fb_url']) ?>" target="_blank"><span class="u-icon u-social-facebook u-text-white u-icon-1"><svg class="u-svg-link" viewBox="0 0 112.196 112.196"><use xlink:href="#svg-9ec0"></use></svg><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 112.196 112.196" style="enable-background:new 0 0 112.196 112.196;" xml:space="preserve" id="svg-9ec0"><circle style="fill:#3B5998;" cx="56.098" cy="56.098" r="56.098"/><path style="fill:#FFFFFF;" d="M70.201 58.294h-10.01v36.672H45.025V58.294h-7.213V45.406h7.213v-8.34c0-5.964 2.833-15.303 15.301-15.303l11.234.047v12.51h-8.151c-1.337 0-3.217.668-3.217 3.513v7.585h11.334L70.201 58.294z"/></svg></span></a><?php endif; ?>
              <?php if ($m['tw_url'] && $m['tw_url'] !== '#'): ?><a class="u-social-url" title="Twitter" href="<?= h($m['tw_url']) ?>" target="_blank"><span class="u-icon u-social-twitter u-text-white u-icon-2"><svg viewBox="0 0 112.197 112.197" style="width:32px;height:32px;"><circle style="fill:#55acee;" cx="56.099" cy="56.099" r="56.098"/><path style="fill:#fff;" d="M90.461 40.316a26.083 26.083 0 0 1-7.519 2.06 13.127 13.127 0 0 0 5.756-7.244 26.174 26.174 0 0 1-8.312 3.176 13.088 13.088 0 0 0-22.283 11.933 37.122 37.122 0 0 1-26.965-13.676 13.088 13.088 0 0 0 4.047 17.46 13.048 13.048 0 0 1-5.923-1.636c-.002.055-.002.11-.002.162a13.09 13.09 0 0 0 10.492 12.83 13.13 13.13 0 0 1-5.911.225 13.094 13.094 0 0 0 12.22 9.081 26.228 26.228 0 0 1-19.33 5.407 37.03 37.03 0 0 0 20.067 5.882c24.083 0 37.251-19.949 37.251-37.249 0-.566-.014-1.134-.039-1.694a26.597 26.597 0 0 0 6.521-6.757z"/></svg></span></a><?php endif; ?>
              <?php if ($m['ig_url'] && $m['ig_url'] !== '#'): ?><a class="u-social-url" title="Instagram" href="<?= h($m['ig_url']) ?>" target="_blank"><span class="u-icon u-social-instagram u-text-white u-icon-3"><svg viewBox="0 0 112.197 112.197" style="width:32px;height:32px;"><circle cx="56.099" cy="56.099" r="56.098" fill="#e1306c"/><path fill="#fff" d="M56.099 40.068c8.556 0 9.568.033 12.942.187 3.124.143 4.821.665 5.951 1.104 1.496.581 2.562 1.275 3.682 2.395 1.12 1.12 1.815 2.187 2.395 3.682.44 1.13.961 2.827 1.104 5.951.154 3.374.186 4.386.186 12.942s-.032 9.568-.186 12.942c-.143 3.124-.665 4.821-1.104 5.951-.581 1.496-1.275 2.562-2.395 3.682-1.12 1.12-2.187 1.815-3.682 2.395-1.13.44-2.827.961-5.951 1.104-3.374.154-4.386.186-12.942.186s-9.568-.032-12.942-.186c-3.124-.143-4.821-.665-5.951-1.104-1.496-.581-2.562-1.275-3.682-2.395-1.12-1.12-1.815-2.187-2.395-3.682-.44-1.13-.961-2.827-1.104-5.951-.154-3.374-.186-4.386-.186-12.942s.032-9.568.186-12.942c.143-3.124.665-4.821 1.104-5.951.581-1.496 1.275-2.562 2.395-3.682 1.12-1.12 2.187-1.815 3.682-2.395 1.13-.44 2.827-.961 5.951-1.104 3.374-.154 4.386-.187 12.942-.187zm0-5.772c-8.704 0-9.796.037-13.21.193-3.407.156-5.733.697-7.765 1.489-2.104.817-3.886 1.911-5.664 3.689s-2.872 3.561-3.689 5.665c-.792 2.032-1.333 4.358-1.489 7.765-.156 3.414-.193 4.505-.193 13.21s.037 9.796.193 13.21c.156 3.407.697 5.733 1.489 7.765.817 2.104 1.911 3.886 3.689 5.664s3.561 2.872 5.665 3.689c2.032.792 4.358 1.333 7.765 1.489 3.414.156 4.505.193 13.21.193s9.796-.037 13.21-.193c3.407-.156 5.733-.697 7.765-1.489 2.104-.817 3.886-1.911 5.664-3.689s2.872-3.561 3.689-5.665c.792-2.032 1.333-4.358 1.489-7.765.156-3.414.193-4.505.193-13.21s-.037-9.796-.193-13.21c-.156-3.407-.697-5.733-1.489-7.765-.817-2.104-1.911-3.886-3.689-5.664s-3.561-2.872-5.665-3.689c-2.032-.792-4.358-1.333-7.765-1.489-3.414-.156-4.505-.193-13.21-.193zm0 14.347c-9.082 0-16.445 7.362-16.445 16.445s7.363 16.445 16.445 16.445 16.445-7.363 16.445-16.445-7.363-16.445-16.445-16.445zm0 27.117c-5.889 0-10.672-4.783-10.672-10.672s4.783-10.672 10.672-10.672 10.672 4.783 10.672 10.672-4.783 10.672-10.672 10.672zm17.098-30.912c-2.123 0-3.843 1.721-3.843 3.843s1.72 3.843 3.843 3.843 3.843-1.721 3.843-3.843-1.72-3.843-3.843-3.843z"/></svg></span></a><?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
