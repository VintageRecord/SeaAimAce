<?php
// Outputs a <style> block overriding background images from DB settings.
// Include inside <head> on every public page, after $current_page is set.
// Each slot: [setting_key, css_selector, overlay_rgba|'']
$_bg_slots = [
    // Home
    'home' => [
        ['bg_home_hero',    '.u-section-1',            'rgba(0,0,0,0.5)'],
        ['bg_home_sec2',    '.u-section-2 .u-image-1', ''],
        ['bg_home_sec4',    '.u-section-4 .u-image-1', 'rgba(0,0,0,0.4)'],
        ['bg_home_sec6',    '.u-section-6',            ''],
        ['bg_home_contact', '.u-section-8',            'rgba(0,0,0,0.45)'],
    ],
    'about' => [
        ['bg_about_hero',   '.u-section-1', 'rgba(0,0,0,0.45)'],
        ['bg_about_contact','.u-section-7', 'rgba(0,0,0,0.45)'],
    ],
    'gallery' => [
        ['bg_gallery_hero', '.u-section-1', 'rgba(0,0,0,0.5)'],
        ['bg_gallery_sec6', '.u-section-6', 'rgba(0,0,0,0.45)'],
    ],
    'team' => [
        ['bg_team_hero',    '.u-section-1 .u-image-1', ''],
    ],
    'faq' => [
        ['bg_faq_hero',     '.u-section-1', 'rgba(0,0,0,0.3)'],
    ],
    'contact' => [
        ['bg_contact_hero', '.u-section-1', 'rgba(0,0,0,0.3)'],
        ['bg_contact_sec4', '.u-section-4', 'rgba(0,0,0,0.45)'],
    ],
];

$_page_key  = $current_page ?? '';
$_overrides = $_bg_slots[$_page_key] ?? [];
$_css_lines = [];

foreach ($_overrides as [$key, $selector, $overlay]) {
    $val = setting($key);
    if ($val === '') continue;
    // Prepend uploads/ prefix if not already a full path
    $url = (strpos($val, '/') === false ? 'uploads/' : '') . $val;
    if ($overlay) {
        $_css_lines[] = "{$selector} { background-image: linear-gradient(0deg,{$overlay},{$overlay}),url('" . addslashes($url) . "'); }";
    } else {
        $_css_lines[] = "{$selector} { background-image: url('" . addslashes($url) . "'); }";
    }
}

if (!empty($_css_lines)):
?>
<style>
<?= implode("\n", $_css_lines) ?>
</style>
<?php endif; ?>
