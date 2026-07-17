<?php
// Shared navigation — included by every front-end page
// $current_page: 'home'|'about'|'contact'|'faq'|'gallery'|'team'|'landing'
// $_nav_base: optional prefix for asset/page URLs (e.g. '../' when included from admin/)
$current_page ??= '';
$_nav_base ??= '';
$site_logo      = setting('site_logo', 'new_images/-.png');
$site_name      = setting('site_name', 'CampForge');
$nav_logo_text  = setting('nav_logo_text', '');
$nav_btn_text   = setting('nav_book_btn_text', 'Book a Tour');
$nav_btn_href   = setting('nav_book_btn_href', 'contact.php');
$nav_bg         = setting('nav_bg_color', '');
$nav_text       = setting('nav_text_color', '');
$nav_accent     = setting('nav_accent_color', '');
$nav_template   = setting('nav_template', 'default');

// Build inline style for nav
$nav_style = '';
if ($nav_bg)   $nav_style .= "background:{$nav_bg}!important;";
if ($nav_text) $nav_style .= "color:{$nav_text};";

// Nav links from DB; fallback to defaults
$db = get_db();
$nav_rows = $db->query('SELECT * FROM nav_links ORDER BY sort_order ASC')->fetchAll();
if (empty($nav_rows)) {
    $nav_rows = [
        ['label' => 'Home',     'url' => './'],
        ['label' => 'About Us', 'url' => 'about.php'],
        ['label' => 'Gallery',  'url' => 'gallery.php'],
        ['label' => 'Our Team', 'url' => 'team.php'],
        ['label' => 'FAQ',      'url' => 'faq.php'],
        ['label' => 'Contact',  'url' => 'contact.php'],
    ];
}

// Determine active link by matching $current_page to known page slugs
$page_slug_map = [
    'home'    => './',
    'about'   => 'about.php',
    'gallery' => 'gallery.php',
    'team'    => 'team.php',
    'faq'     => 'faq.php',
    'contact' => 'contact.php',
];
$active_url = $page_slug_map[$current_page] ?? '';
$_nav_variants = ['default', 'centered', 'boxed', 'split'];
if (!in_array($nav_template, $_nav_variants, true)) $nav_template = 'default';
?>
<?php require __DIR__ . '/nav-templates/' . $nav_template . '.php'; ?>
