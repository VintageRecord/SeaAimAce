<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$_nav_base    = '../';
$current_page = '';

ob_start();
include dirname(__DIR__) . '/_nav.php';
$nav_html = ob_get_clean();

ob_start();
// _footer.php closes </body></html> — capture everything before those tags
include dirname(__DIR__) . '/_footer.php';
$footer_raw = ob_get_clean();
// Strip the closing </body></html> and the two script tags _footer adds (not needed in block)
$footer_html = preg_replace('/<script[^>]*>.*?<\/script>\s*<\/body>\s*<\/html>/s', '', $footer_raw);

header('Content-Type: application/json');
echo json_encode([
    'nav'    => $nav_html,
    'footer' => $footer_html,
]);
