<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
$db = get_db();
$cms_page_key   = 'live-edit-team.php';
$cms_page_title = 'Our Team';
$cms_page_css   = '../Team.css';
$team_members = $db->query('SELECT * FROM team_members ORDER BY sort_order')->fetchAll();
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
            <div class="u-align-left u-container-align-left u-container-style u-image u-layout-cell u-left-cell u-size-31-lg u-size-33-xl u-size-60-md u-size-60-sm u-size-60-xs u-image-1" src="" data-image-width="1650" data-image-height="1100" data-bg-key="team_s1_bg1" style="background-image:url('../<?= h(setting('img_src_team_s1_bg1','new_images/3570.jpg')) ?>')">
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
                 data-bg-key="team_member_<?= (int)$m['id'] ?>"
                 style="background-image:linear-gradient(0deg,rgba(0,0,0,.4),rgba(0,0,0,.4)),url('../<?= h($m['image']) ?>')">
              <div class="u-container-layout u-similar-container u-container-layout-1">
                <h3 class="u-custom-font u-font-montserrat u-text u-text-body-alt-color u-text-3" data-editable data-type="team_member" data-id="<?= (int)$m['id'] ?>" data-field="name"><?= h($m['name']) ?></h3>
                <p class="u-text u-text-body-alt-color u-text-4" data-editable data-type="team_member" data-id="<?= (int)$m['id'] ?>" data-field="bio"><?= h($m['bio']) ?></p>
                <div class="u-social-icons u-spacing-10 u-social-icons-1">
                  <a class="u-social-url" target="_blank" href="<?= h($m['fb_url']) ?>"><span class="u-icon u-icon-circle u-social-facebook u-social-icon u-text-white u-icon-1"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xlink:href="#svg-ad72"></use></svg><svg x="0px" y="0px" viewBox="0 0 112 112" id="svg-ad72" class="u-svg-content"><path d="M75.5,28.8H65.4c-1.5,0-4,0.9-4,4.3v9.4h13.9l-1.5,15.8H61.4v45.1H42.8V58.3h-8.8V42.4h8.8V32.2 c0-7.4,3.4-18.8,18.8-18.8h13.8v15.4H75.5z"></path></svg></span>
                  </a>
                  <a class="u-social-url" target="_blank" href="<?= h($m['tw_url']) ?>"><span class="u-icon u-icon-circle u-social-icon u-social-twitter u-text-white u-icon-2"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xlink:href="#svg-f916"></use></svg><svg x="0px" y="0px" viewBox="0 0 112 112" id="svg-f916" class="u-svg-content"><path d="M92.2,38.2c0,0.8,0,1.6,0,2.3c0,24.3-18.6,52.4-52.6,52.4c-10.6,0.1-20.2-2.9-28.5-8.2 c1.4,0.2,2.9,0.2,4.4,0.2c8.7,0,16.7-2.9,23-7.9c-8.1-0.2-14.9-5.5-17.3-12.8c1.1,0.2,2.4,0.2,3.4,0.2c1.6,0,3.3-0.2,4.8-0.7 c-8.4-1.6-14.9-9.2-14.9-18c0-0.2,0-0.2,0-0.2c2.5,1.4,5.4,2.2,8.4,2.3c-5-3.3-8.3-8.9-8.3-15.4c0-3.4,1-6.5,2.5-9.2 c9.1,11.1,22.7,18.5,38,19.2c-0.2-1.4-0.4-2.8-0.4-4.3c0.1-10,8.3-18.2,18.5-18.2c5.4,0,10.1,2.2,13.5,5.7c4.3-0.8,8.1-2.3,11.7-4.5 c-1.4,4.3-4.3,7.9-8.1,10.1c3.7-0.4,7.3-1.4,10.6-2.9C98.9,32.3,95.7,35.5,92.2,38.2z"></path></svg></span>
                  </a>
                  <a class="u-social-url" target="_blank" href="<?= h($m['ig_url']) ?>"><span class="u-icon u-icon-circle u-social-icon u-social-instagram u-text-white u-icon-3"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 112 112" style=""><use xlink:href="#svg-a0a0"></use></svg><svg x="0px" y="0px" viewBox="0 0 112 112" id="svg-a0a0" class="u-svg-content"><path d="M55.9,32.9c-12.8,0-23.2,10.4-23.2,23.2s10.4,23.2,23.2,23.2s23.2-10.4,23.2-23.2S68.7,32.9,55.9,32.9z M55.9,69.4c-7.4,0-13.3-6-13.3-13.3c-0.1-7.4,6-13.3,13.3-13.3s13.3,6,13.3,13.3C69.3,63.5,63.3,69.4,55.9,69.4z"></path><path d="M79.7,26.8c-3,0-5.4,2.5-5.4,5.4s2.5,5.4,5.4,5.4c3,0,5.4-2.5,5.4-5.4S82.7,26.8,79.7,26.8z"></path><path d="M78.2,11H33.5C21,11,10.8,21.3,10.8,33.7v44.7c0,12.6,10.2,22.8,22.7,22.8h44.7c12.6,0,22.7-10.2,22.7-22.7 V33.7C100.8,21.1,90.6,11,78.2,11z M91,78.4c0,7.1-5.8,12.8-12.8,12.8H33.5c-7.1,0-12.8-5.8-12.8-12.8V33.7 c0-7.1,5.8-12.8,12.8-12.8h44.7c7.1,0,12.8,5.8,12.8,12.8V78.4z"></path></svg></span>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          </div>
        </div>
        <?php else: ?>
        <p style="text-align:center;color:#888;padding:40px">No team members yet. Add them in <a href="site-team.php" style="color:#E63946">Dashboard → Our Team</a>.</p>
        <?php endif; ?>
      </div>
    </section>
    <section class="u-clearfix u-image u-shading u-section-3" data-image-width="1620" data-image-height="1080" id="block-3" data-bg-key="team_s3_bg" style="background-image:linear-gradient(0deg,rgba(0,0,0,.45),rgba(0,0,0,.45)),url('../<?= h(setting('img_src_team_s3_bg','new_images/gfgfggfggg-min.jpg')) ?>')">
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
$_foot_base = '../';
require_once dirname(__DIR__) . '/_footer.php';
?>
    <script src="../jquery.js" defer></script>
    <script src="../nicepage.js" defer></script>

<?php include '_live_edit_js.php'; ?>
</body>
</html>
