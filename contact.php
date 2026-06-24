<?php
require_once __DIR__ . '/config.php';
$current_page = 'contact';
$db = get_db();

$success = false;
$error   = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $company = trim($_POST['company'] ?? '');
    $interest= trim($_POST['interest']?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name && $email) {
        $stmt = $db->prepare('INSERT INTO contact_submissions (name,email,phone,company,interest,message) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$name,$email,$phone,$company,$interest,$message]);
        $success = true;
    } else {
        $error = 'Please fill in your name and email address.';
    }
}
?>
<!DOCTYPE html>
<html style="font-size:16px;" lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <title><?= h(setting('contact_meta_title','Contact Us')) ?> | <?= h(setting('site_name','CampForge')) ?></title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Contact.css" media="screen">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?display=swap&family=Playfair+Display:wght@400;700&family=Lato:wght@400;700&family=Montserrat:wght@400;600;700">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
  <style>
  .contact-form-wrap { max-width: 640px; margin: 0 auto; padding: 40px 24px; }
  .contact-form-wrap h2 { margin-bottom: 24px; }
  .cf-row { margin-bottom: 16px; }
  .cf-row label { display: block; font-size: .85rem; color: #555; margin-bottom: 6px; }
  .cf-row input, .cf-row textarea, .cf-row select { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: .95rem; font-family: inherit; box-sizing: border-box; }
  .cf-row textarea { resize: vertical; min-height: 100px; }
  .cf-submit { background: #478ac9; color: #fff; border: none; padding: 12px 32px; border-radius: 50px; font-size: 1rem; font-weight: 600; cursor: pointer; }
  .cf-submit:hover { background: #3a7ab9; }
  .cf-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 6px; padding: 14px 18px; margin-bottom: 20px; }
  .cf-error  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px; padding: 14px 18px; margin-bottom: 20px; }
  </style>
</head>
<body data-path-to-root="./" class="u-body u-clearfix u-xl-mode" data-lang="en">

<?php require __DIR__ . '/_nav.php'; ?>

<section class="u-align-center u-clearfix u-image u-shading u-section-1" id="sec-hero">
  <div class="u-clearfix u-sheet u-sheet-1">
    <h1 class="u-text u-text-default u-text-1" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('contact_hero_heading','Plan Your Camping Trip')) ?></h1>
    <p class="u-large-text u-text u-text-variant u-text-2" data-animation-name="customAnimationIn" data-animation-duration="1500"><?= h(setting('contact_hero_sub','We\'d love to help you plan the perfect outdoor adventure.')) ?></p>
  </div>
</section>

<section class="u-clearfix u-section-2" id="sec-form">
  <div class="contact-form-wrap">
    <h2><?= h(setting('contact_form_heading','Get in Touch')) ?></h2>
    <?php if ($success): ?>
    <div class="cf-success"><?= h(setting('contact_success_msg','Thank you! We\'ll be in touch soon.')) ?></div>
    <?php elseif ($error): ?>
    <div class="cf-error"><?= h($error) ?></div>
    <?php endif; ?>
    <?php if (!$success): ?>
    <form method="POST" action="contact.php">
      <div class="cf-row">
        <label>Full Name *</label>
        <input type="text" name="name" value="<?= h($_POST['name'] ?? '') ?>" required placeholder="Jane Smith">
      </div>
      <div class="cf-row">
        <label>Email Address *</label>
        <input type="email" name="email" value="<?= h($_POST['email'] ?? '') ?>" required placeholder="jane@example.com">
      </div>
      <div class="cf-row">
        <label>Phone Number</label>
        <input type="tel" name="phone" value="<?= h($_POST['phone'] ?? '') ?>" placeholder="+1 (555) 000-0000">
      </div>
      <div class="cf-row">
        <label>Company / Group Name</label>
        <input type="text" name="company" value="<?= h($_POST['company'] ?? '') ?>" placeholder="Optional">
      </div>
      <div class="cf-row">
        <label>I'm interested in</label>
        <select name="interest">
          <option value="">-- Select --</option>
          <option value="Trekking"<?= (($_POST['interest']??'')==='Trekking')?' selected':'' ?>>Trekking</option>
          <option value="Camping"<?= (($_POST['interest']??'')==='Camping')?' selected':'' ?>>Camping</option>
          <option value="Beach Tents"<?= (($_POST['interest']??'')==='Beach Tents')?' selected':'' ?>>Beach Tents</option>
          <option value="Group Booking"<?= (($_POST['interest']??'')==='Group Booking')?' selected':'' ?>>Group Booking</option>
          <option value="Other"<?= (($_POST['interest']??'')==='Other')?' selected':'' ?>>Other</option>
        </select>
      </div>
      <div class="cf-row">
        <label>Message</label>
        <textarea name="message" placeholder="Tell us about your camping plans..."><?= h($_POST['message'] ?? '') ?></textarea>
      </div>
      <button type="submit" class="cf-submit"><?= h(setting('contact_submit_btn','Send Message')) ?></button>
    </form>
    <?php endif; ?>
  </div>
</section>

<section class="u-clearfix u-palette-2-base u-section-3" id="sec-info">
  <div class="u-clearfix u-sheet u-sheet-1">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:32px;padding:40px 0;color:#fff;text-align:center;">
      <div>
        <h4 style="margin-bottom:8px;"><?= h(setting('contact_info1_title','Address')) ?></h4>
        <p><?= nl2br(h(setting('contact_info1_text','123 National Park Road\nWilderness, CA 90210'))) ?></p>
      </div>
      <div>
        <h4 style="margin-bottom:8px;"><?= h(setting('contact_info2_title','Phone')) ?></h4>
        <p><?= h(setting('contact_info2_text','+1 (555) 000-0000')) ?></p>
      </div>
      <div>
        <h4 style="margin-bottom:8px;"><?= h(setting('contact_info3_title','Email')) ?></h4>
        <p><?= h(setting('contact_info3_text','hello@campforge.com')) ?></p>
      </div>
      <div>
        <h4 style="margin-bottom:8px;"><?= h(setting('contact_info4_title','Hours')) ?></h4>
        <p><?= nl2br(h(setting('contact_info4_text','Mon–Fri: 8am – 6pm\nWeekends: 9am – 5pm'))) ?></p>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/_footer.php'; ?>
