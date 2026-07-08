<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

// Pull settings
$site_logo     = setting('site_logo',         'new_images/-.png');
$site_name     = setting('site_name',         'CampForge');
$nav_logo_text = setting('nav_logo_text',     '');
$nav_btn_text  = setting('nav_book_btn_text', 'Book a Tour');
$nav_btn_href  = setting('nav_book_btn_href', 'contact.php');
$nav_bg        = setting('nav_bg_color',      '#ffffff');
$nav_text      = setting('nav_text_color',    '#333333');

$db       = get_db();
$nav_rows = $db->query('SELECT * FROM nav_links ORDER BY sort_order ASC')->fetchAll();
if (empty($nav_rows)) {
    $nav_rows = [
        ['label'=>'Home',    'url'=>'./'],
        ['label'=>'About Us','url'=>'about.php'],
        ['label'=>'Gallery', 'url'=>'gallery.php'],
        ['label'=>'Our Team','url'=>'team.php'],
        ['label'=>'FAQ',     'url'=>'faq.php'],
        ['label'=>'Contact', 'url'=>'contact.php'],
    ];
}

$bg   = $nav_bg   ?: '#ffffff';
$text = $nav_text ?: '#333333';

$links_html = '';
foreach ($nav_rows as $nl) {
    $links_html .= '<a href="' . h($nl['url']) . '" style="color:' . h($text) . ';text-decoration:none;font-size:.9rem;font-weight:500;padding:6px 10px;white-space:nowrap">' . h($nl['label']) . '</a>';
}

$btn_html = $nav_btn_text
    ? '<a href="' . h($nav_btn_href) . '" style="margin-left:12px;padding:8px 20px;background:transparent;border:2px solid currentColor;border-radius:50px;color:' . h($text) . ';font-size:.82rem;font-weight:600;text-decoration:none;white-space:nowrap">' . h($nav_btn_text) . '</a>'
    : '';

$logo_img = '<img src="../' . h($site_logo) . '" alt="' . h($site_name) . '" style="height:48px;width:auto;object-fit:contain">';
$logo_text = $nav_logo_text ? '<span style="font-size:1.1rem;font-weight:700;color:' . h($text) . ';margin-left:8px">' . h($nav_logo_text) . '</span>' : '';

$nav_html = '<header style="background:' . h($bg) . ';padding:0 32px;display:flex;align-items:center;justify-content:space-between;height:72px;box-shadow:0 1px 4px rgba(0,0,0,.08);position:relative;z-index:100">'
    . '<a href="./" style="display:flex;align-items:center;text-decoration:none;flex-shrink:0">' . $logo_img . $logo_text . '</a>'
    . '<nav style="display:flex;align-items:center;gap:4px;flex-wrap:wrap">' . $links_html . $btn_html . '</nav>'
    . '</header>';

// Footer — pull rendered HTML, strip script/body/html tags
$_nav_base = '../';
ob_start();
include dirname(__DIR__) . '/_footer.php';
$footer_raw = ob_get_clean();
$footer_html = preg_replace('/<script[^>]*>.*?<\/script>/s', '', $footer_raw);
$footer_html = preg_replace('/<\/body>\s*<\/html>/s', '', $footer_html);

header('Content-Type: application/json');
echo json_encode([
    'nav'    => $nav_html,
    'footer' => trim($footer_html),
]);
