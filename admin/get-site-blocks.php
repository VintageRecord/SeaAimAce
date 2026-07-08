<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$_nav_base    = '../';
$current_page = '';

ob_start();
include dirname(__DIR__) . '/_nav.php';
$nav_html = ob_get_clean();

// Extract the embedded <style class="menu-style"> so we can return it separately
// (GrapesJS mishandles <style> tags inside block HTML, causing visual glitches)
$nav_style = '';
$nav_html = preg_replace_callback('/<style[^>]*class="menu-style"[^>]*>(.*?)<\/style>/s', function($m) use (&$nav_style) {
    $nav_style = $m[1];
    return '';
}, $nav_html);

ob_start();
include dirname(__DIR__) . '/_footer.php';
$footer_raw = ob_get_clean();
// Strip the closing </body></html> and script tags _footer adds
$footer_html = preg_replace('/<script[^>]*>.*?<\/script>\s*(<\/body>\s*<\/html>)?/s', '', $footer_raw);

header('Content-Type: application/json');
echo json_encode([
    'nav'       => trim($nav_html),
    'nav_style' => trim($nav_style),
    'footer'    => trim($footer_html),
]);
