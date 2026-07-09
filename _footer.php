<?php
$footer_copyright = setting('footer_copyright', '') ?: setting('footer_text', '© ' . date('Y') . ' CampForge. All rights reserved.');
$footer_bg        = setting('footer_bg_color', '#1a1a1a');
$footer_text_col  = setting('footer_text_color', '#aaaaaa');
$site_name        = setting('site_name', 'CampForge');
$nav_logo_text    = setting('nav_logo_text', '');
$site_logo        = setting('site_logo', 'new_images/-.png');
$footer_template  = setting('footer_template', 'default');
$_foot_base       = $_nav_base ?? '';

$footer_columns = get_db()->query('SELECT * FROM footer_columns ORDER BY sort_order')->fetchAll();
$fnav           = get_db()->query('SELECT * FROM nav_links ORDER BY sort_order LIMIT 6')->fetchAll();

// Build inline style strings
$bg_style   = 'background:' . ($footer_bg ?: '#1a1a1a') . ';';
$text_style = 'color:' . ($footer_text_col ?: '#aaaaaa') . ';';
$link_style = 'color:' . ($footer_text_col ?: '#aaaaaa') . ';text-decoration:none;transition:opacity .15s;';

$_footer_variants = ['default', 'minimal'];
if (!in_array($footer_template, $_footer_variants, true)) $footer_template = 'default';
require __DIR__ . '/footer-templates/' . $footer_template . '.php';
?>
<script class="u-script" type="text/javascript" src="<?= h($_foot_base) ?>jquery.js" defer=""></script>
<script class="u-script" type="text/javascript" src="<?= h($_foot_base) ?>nicepage.js" defer=""></script>
</body>
</html>
