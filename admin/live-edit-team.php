<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$db = get_db();
$cms_page_key   = 'live-edit-team.php';
$cms_page_title = 'Our Team';
$cms_page_css   = '../Team.css';
$team_members = $db->query('SELECT * FROM team_members ORDER BY sort_order')->fetchAll();
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

    <section class="u-align-center u-clearfix u-container-align-center u-valign-middle u-white u-section-1" id="block-1">
      <div class="u-clearfix u-gutter-0 u-layout-wrap u-layout-wrap-1">
        <div class="u-layout">
          <div class="u-layout-row">
            <div class="u-align-left u-container-align-left u-container-style u-image u-layout-cell u-left-cell u-size-31-lg u-size-33-xl u-size-60-md u-size-60-sm u-size-60-xs u-image-1" src="" data-image-width="1650" data-image-height="1100" style="background-image:url('../<?= h(setting('img_bg_team_s1_bg1','new_images/default.jpg')) ?>')">
              <div class="u-container-layout u-container-layout-1"></div>
            </div>
            <div class="u-align-left u-container-align-left u-container-style u-layout-cell u-right-cell u-shape-rectangle u-size-27-xl u-size-29-lg u-size-60-md u-size-60-sm u-size-60-xs u-white u-layout-cell-2">
              <div class="u-container-layout u-valign-middle u-container-layout-2">
                <h1 class="u-align-left u-font-titillium-Web u-text u-text-1" data-editable data-type="setting" data-key="team_hero_heading"><?= h(setting('team_hero_heading','Our team is looking forward')) ?></h1>
                <p class="u-align-left u-text u-text-2" data-editable data-type="setting" data-key="team_hero_body"><?= h(setting('team_hero_body','Sample text. Lorem ipsum dolor sit amet, consectetur adipiscing elit nullam nunc justo sagittis suscipit ultrices.')) ?></p>
                <p class="u-align-left u-text u-text-3">Image from <a href="https://www.freepik.com/photos/woman" class="u-border-1 u-border-active-palette-2-base u-border-hover-palette-3-base u-border-no-left u-border-no-right u-border-no-top u-border-palette-2-base u-bottom-left-radius-0 u-bottom-right-radius-0 u-btn u-button-link u-button-style u-none u-radius-0 u-text-body-color u-top-left-radius-0 u-top-right-radius-0 u-btn-1">Freepik</a>
                </p>
                <a href="#" class="u-align-left u-border-2 u-border-palette-2-base u-btn u-btn-round u-button-style u-palette-2-base u-radius-50 u-btn-2"> Our team</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-container-align-center u-grey-5 u-section-2" id="block-2">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <h2 class="u-align-center u-custom-font u-font-montserrat u-text u-text-default u-text-1" data-editable data-type="setting" data-key="team_section_heading"><?= h(setting('team_section_heading','Our Team')) ?></h2>
        <p class="u-align-center u-text u-text-default u-text-2">Images from&nbsp;<a href="https://freepik.com" class="u-active-none u-border-1 u-border-active-palette-2-base u-border-grey-75 u-border-hover-palette-2-base u-border-no-left u-border-no-right u-border-no-top u-btn u-button-link u-button-style u-hover-none u-none u-text-body-color u-btn-1">Freepik</a>
        </p>
        <?php if (!empty($team_members)): ?>
        <div class="u-expanded-width u-list u-list-1">
          <div class="u-repeater u-repeater-1">
          <?php foreach ($team_members as $m): ?>
            <div class="u-align-left u-container-align-left u-container-style u-image u-list-item u-repeater-item u-shading u-shape-rectangle" data-animation-name="customAnimationIn" data-animation-duration="1500" data-animation-delay="250"
                 <?= $m['image'] ? 'style="background-image:url(\'../' . h($m['image']) . '\')"' : '' ?>>
              <div class="u-container-layout u-similar-container u-container-layout-1">
                <h3 class="u-custom-font u-font-montserrat u-text u-text-body-alt-color u-text-3"><?= h($m['name']) ?></h3>
                <p class="u-text u-text-body-alt-color u-text-4"><?= h($m['role']) ?></p>
                <p class="u-text u-text-body-alt-color" style="font-size:.85em"><?= h($m['bio']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
        <?php else: ?>
        <p style="text-align:center;color:#888;padding:40px">No team members yet. Add them in <a href="team.php" style="color:#E63946">Dashboard → Our Team</a>.</p>
        <?php endif; ?>
      </div>
    </section>
    <section class="u-clearfix u-image u-shading u-section-3" data-image-width="1620" data-image-height="1080" id="block-3">
      <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
        <div class="u-clearfix u-expanded-width u-gutter-0 u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-container-style u-layout-cell u-left-cell u-size-30 u-layout-cell-1">
                <div class="u-container-layout u-valign-middle u-container-layout-1">
                  <h2 class="u-text u-text-1" data-editable data-type="setting" data-key="team_s3_heading"><?= h(setting('team_s3_heading','Contact Us')) ?></h2>
                  <p class="u-text u-text-body-alt-color u-text-2" data-editable data-type="setting" data-key="team_s3_body"><?= h(setting('team_s3_body','Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.')) ?></p>
                  <p class="u-align-left u-text u-text-3">Images from <a href="https://www.freepik.com/photos/man-with-dog" class="u-border-1 u-border-no-left u-border-no-right u-border-no-top u-border-white u-btn u-button-link u-button-style u-none u-text-body-alt-color u-btn-1">Freepik</a>
                  </p>
                  <a href="../contact.php" class="u-active-white u-border-2 u-border-active-white u-border-hover-white u-border-white u-btn u-btn-round u-button-style u-hover-white u-none u-radius-50 u-text-active-black u-text-hover-black u-btn-2" data-editable data-type="setting" data-key="team_s3_btn"><?= h(setting('team_s3_btn','Contact Us')) ?></a>
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
