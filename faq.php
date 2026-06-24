<?php
require_once __DIR__ . '/config.php';
$current_page = 'faq';
$db = get_db();
$faqs = $db->query('SELECT * FROM faq_items ORDER BY sort_order ASC')->fetchAll();
if (empty($faqs)) {
    $faqs = [
        ['question'=>'What should I bring to camp?','answer'=>'Pack essentials: tent, sleeping bag, warm clothing, rain gear, first aid kit, flashlight, water bottles, food, and sunscreen. Check our packing list for a full guide.'],
        ['question'=>'Are pets allowed on site?','answer'=>'Yes, well-behaved pets on a leash are welcome in designated areas. Please clean up after your pet and keep them under control at all times.'],
        ['question'=>'What activities are available?','answer'=>'We offer trekking, rock climbing, mountain biking, beach camping, volleyball, and guided nature tours. See our activities page for full details.'],
        ['question'=>'Can I make a group booking?','answer'=>'Absolutely! We accommodate groups of all sizes. Contact us for special group rates and to reserve exclusive areas of the campsite.'],
    ];
}
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <title><?= h(setting('faq_meta_title','FAQ')) ?> | <?= h(setting('site_name','CampForge')) ?></title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="FAQ-Page.css" media="screen">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&family=Montserrat:wght@400;600;700">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
</head>
<body data-path-to-root="./" class="u-body u-clearfix u-xl-mode" data-lang="en">

<?php require __DIR__ . '/_nav.php'; ?>

<section class="u-align-center u-clearfix u-image u-shading u-section-1" id="sec-hero">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h1 class="u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('faq_hero_heading','Frequently Asked Questions')) ?></h1>
    <p class="u-large-text u-text u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('faq_hero_sub','Find answers to common questions about our campsite below.')) ?></p>
  </div>
</section>

<section class="u-clearfix u-section-2" id="sec-faqs">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-text u-text-1"><?= h(setting('faq_section_heading','Common Questions')) ?></h2>
    <div class="u-accordion u-spacing-13 u-accordion-1">
      <?php foreach ($faqs as $i => $faq): $n = $i + 1; ?>
      <div class="u-accordion-item u-accordion-item-<?= $n ?>">
        <a class="u-accordion-link u-active-palette-1-base u-button-style u-custom-left-right-menu-spacing u-custom-padding-bottom u-custom-top-bottom-menu-spacing u-grey-10 u-hover-palette-1-base u-text-active-white u-text-hover-white u-accordion-link-<?= $n ?>" id="link-accordion-<?= $n ?>" aria-controls="accordion-<?= $n ?>">
          <span class="u-accordion-link-text"><?= h($faq['question']) ?></span>
          <span class="u-accordion-link-icon u-icon u-text-white u-icon-1">
            <svg class="u-svg-link" viewBox="0 0 444.819 444.819"><use xlink:href="#svg-plus"></use></svg>
            <svg class="u-svg-content" id="svg-plus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 444.819 444.819"><path d="M434.252 208.708H236.11V10.566C236.11 4.729 231.381 0 225.545 0h-6.271c-5.836 0-10.565 4.729-10.565 10.566v198.142H10.566C4.729 208.708 0 213.437 0 219.273v6.271c0 5.836 4.729 10.565 10.566 10.565h198.143v198.143c0 5.836 4.729 10.566 10.565 10.566h6.271c5.836 0 10.565-4.73 10.565-10.566V236.109h198.142c5.836 0 10.565-4.729 10.565-10.565v-6.271c0-5.836-4.729-10.565-10.565-10.565z" fill="currentColor"/></svg>
          </span>
        </a>
        <div class="u-accordion-pane u-container-style u-accordion-pane-<?= $n ?>" id="accordion-<?= $n ?>" aria-labelledby="link-accordion-<?= $n ?>">
          <div class="u-container-layout u-container-layout-<?= $n ?>">
            <p class="u-text u-text-<?= $n ?>"><?= h($faq['answer']) ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="u-clearfix u-palette-2-base u-section-3" id="sec-cta">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h2 class="u-text u-text-body-alt-color u-text-1"><?= h(setting('faq_cta_heading','Still have questions?')) ?></h2>
    <p class="u-text u-text-body-alt-color u-text-2"><?= h(setting('faq_cta_text','Our team is happy to help you plan the perfect camping trip.')) ?></p>
    <a href="contact.php" class="u-border-2 u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-body-alt-color u-text-hover-black u-btn-1"><?= h(setting('faq_cta_btn','Contact Us')) ?></a>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
