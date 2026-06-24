<?php
require_once __DIR__ . '/config.php';

$db = get_db();

// Create tables
$db->exec("
CREATE TABLE IF NOT EXISTS settings (
    key TEXT PRIMARY KEY,
    value TEXT NOT NULL DEFAULT ''
);

CREATE TABLE IF NOT EXISTS features (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    icon TEXT NOT NULL DEFAULT '',
    title TEXT NOT NULL DEFAULT '',
    description TEXT NOT NULL DEFAULT '',
    stat1_num TEXT NOT NULL DEFAULT '',
    stat1_label TEXT NOT NULL DEFAULT '',
    stat2_num TEXT NOT NULL DEFAULT '',
    stat2_label TEXT NOT NULL DEFAULT '',
    stat3_num TEXT NOT NULL DEFAULT '',
    stat3_label TEXT NOT NULL DEFAULT '',
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS spaces (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL DEFAULT '',
    description TEXT NOT NULL DEFAULT '',
    tag1 TEXT NOT NULL DEFAULT '',
    tag2 TEXT NOT NULL DEFAULT '',
    tag3 TEXT NOT NULL DEFAULT '',
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS pricing_plans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL DEFAULT '',
    price_monthly INTEGER NOT NULL DEFAULT 0,
    price_yearly INTEGER NOT NULL DEFAULT 0,
    features TEXT NOT NULL DEFAULT '[]',
    is_featured INTEGER NOT NULL DEFAULT 0,
    button_text TEXT NOT NULL DEFAULT 'Get Started',
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS amenities (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    icon TEXT NOT NULL DEFAULT '',
    title TEXT NOT NULL DEFAULT '',
    description TEXT NOT NULL DEFAULT '',
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS contact_submissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL DEFAULT '',
    email TEXT NOT NULL DEFAULT '',
    phone TEXT NOT NULL DEFAULT '',
    company TEXT NOT NULL DEFAULT '',
    interest TEXT NOT NULL DEFAULT '',
    message TEXT NOT NULL DEFAULT '',
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL DEFAULT '',
    slug TEXT NOT NULL UNIQUE,
    meta_title TEXT NOT NULL DEFAULT '',
    meta_description TEXT NOT NULL DEFAULT '',
    status TEXT NOT NULL DEFAULT 'draft',
    html_content TEXT NOT NULL DEFAULT '',
    editor_json TEXT NOT NULL DEFAULT '{}',
    updated_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    filename TEXT NOT NULL,
    original_name TEXT NOT NULL DEFAULT '',
    mime_type TEXT NOT NULL DEFAULT '',
    file_size INTEGER NOT NULL DEFAULT 0,
    uploaded_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS nav_links (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    label TEXT NOT NULL DEFAULT '',
    url TEXT NOT NULL DEFAULT '',
    sort_order INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS footer_columns (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    heading TEXT NOT NULL DEFAULT '',
    links TEXT NOT NULL DEFAULT '[]',
    sort_order INTEGER NOT NULL DEFAULT 0
);
");

// Seed default settings
$defaults = [
    'site_title'          => 'FORGE - Industrial Coworking Space',
    'admin_username'      => 'admin',
    'admin_password'      => password_hash('forge2024', PASSWORD_DEFAULT),

    // Nav
    'nav_logo_text'       => 'FORGE',
    'nav_book_btn_text'   => 'Book Tour',
    'nav_book_btn_href'   => '#contact',

    // Hero
    'hero_heading_line1'  => 'Where Ideas',
    'hero_heading_line2'  => 'Forge Reality',
    'hero_subtitle'       => 'Industrial coworking spaces designed for creators, innovators, and disruptors.',
    'hero_btn1_text'      => 'Start Free Trial',
    'hero_btn1_href'      => '#pricing',
    'hero_btn2_text'      => 'Virtual Tour',
    'hero_btn2_href'      => '#contact',

    // Features section header
    'features_heading'    => 'Built Different',
    'features_subtext'    => 'Raw spaces. Real community. Relentless innovation.',

    // Spaces section header
    'spaces_heading'      => 'Choose Your Arena',
    'spaces_subtext'      => 'From hot desks to private studios, find your perfect workspace.',

    // Pricing section header
    'pricing_heading'     => 'Membership Plans',
    'pricing_subtext'     => 'No hidden fees. No contracts. Just pure workspace.',

    // Pricing enterprise block
    'pricing_enterprise_heading'  => 'Enterprise & Custom Plans',
    'pricing_enterprise_text1'    => 'Scale your workspace with our tailored enterprise solutions. Perfect for growing teams who need flexibility, privacy, and premium amenities. We create custom packages that match your unique requirements.',
    'pricing_enterprise_text2'    => 'Includes: Priority support, custom branding, dedicated account manager, flexible terms, and enterprise-grade security.',
    'pricing_enterprise_feat1_num'   => '10-100+',
    'pricing_enterprise_feat1_label' => 'Team Size',
    'pricing_enterprise_feat2_num'   => 'Custom',
    'pricing_enterprise_feat2_label' => 'Build-Outs',
    'pricing_enterprise_feat3_num'   => 'Dedicated',
    'pricing_enterprise_feat3_label' => 'Floor Options',
    'pricing_enterprise_btn_text' => 'Get Custom Quote',
    'pricing_enterprise_btn_href' => '#contact',

    // Amenities section header
    'amenities_heading'   => 'Everything You Need',
    'amenities_subtext'   => 'Industrial strength amenities for serious work.',

    // Contact section
    'contact_heading'     => 'Get In Touch',
    'contact_subtext'     => 'Ready to join our community? Let\'s talk.',
    'contact_visit_heading' => 'Visit Our Space',
    'contact_visit_text'  => 'Experience the energy of our industrial workspace. Located in the heart of the creative district, we\'re more than just desks and Wi-Fi.',
    'contact_address'     => '123 Industrial Way, Creative District, NY 10013',
    'contact_phone'       => '1-800-FORGE-IT',
    'contact_email'       => 'hello@forge.work',
    'contact_hours'       => 'Mon-Fri 9AM-6PM (Members 24/7)',
    'contact_offer_title' => 'LIMITED TIME OFFER',
    'contact_offer_text'  => 'Schedule a tour and get your first week FREE!',
    'contact_form_heading'=> 'Start Your Journey',
    'contact_form_subtext'=> 'Tell us about your workspace needs and we\'ll craft the perfect solution.',

    // CTA
    'cta_heading'         => 'Ready to Forge Your Future?',
    'cta_subtext'         => 'Join 500+ creators, entrepreneurs, and innovators who call FORGE home.',
    'cta_btn1_text'       => 'Start Free Week',
    'cta_btn1_href'       => '#pricing',
    'cta_btn2_text'       => 'Schedule Tour',
    'cta_btn2_href'       => '#contact',

    // Footer
    'footer_copyright'    => '© 2026 FORGE Coworking. Built to last.',

    // Nav style
    'nav_bg_color'        => '',
    'nav_text_color'      => '',

    // Footer style
    'footer_bg_color'     => '',
    'footer_text_color'   => '',
];

$stmt = $db->prepare('INSERT OR IGNORE INTO settings (key, value) VALUES (?, ?)');
foreach ($defaults as $k => $v) {
    $stmt->execute([$k, $v]);
}

// Seed features
$featureCount = $db->query('SELECT COUNT(*) FROM features')->fetchColumn();
if ($featureCount == 0) {
    $features = [
        ['24/7', 'Always Open', 'Round-the-clock access for members. Work on your schedule, not ours.', '365', 'Days', '24', 'Hours', '∞', 'Access'],
        ['1GB', 'Fiber Internet', 'Lightning-fast internet that keeps up with your ambitions.', '1000', 'Mbps', '99.9%', 'Uptime', '5ms', 'Latency'],
        ['∞', 'Unlimited Coffee', 'Premium coffee and craft beverages to fuel your productivity.', '12', 'Varieties', '∞', 'Refills', '0', 'Extra Cost'],
        ['12', 'Meeting Rooms', 'State-of-the-art conference spaces equipped with the latest tech.', '12', 'Rooms', '4-20', 'Capacity', '4K', 'Displays'],
        ['500+', 'Community', 'Connect with entrepreneurs, freelancers, and innovators.', '500+', 'Members', '50+', 'Events/yr', '100+', 'Collabs'],
        ['P', 'Free Parking', 'Complimentary parking for all members and their guests.', '200', 'Spots', '24/7', 'Security', 'FREE', 'Always'],
    ];
    $fstmt = $db->prepare('INSERT INTO features (icon,title,description,stat1_num,stat1_label,stat2_num,stat2_label,stat3_num,stat3_label,sort_order) VALUES (?,?,?,?,?,?,?,?,?,?)');
    foreach ($features as $i => $f) {
        $fstmt->execute([...$f, $i]);
    }
}

// Seed spaces
$spacesCount = $db->query('SELECT COUNT(*) FROM spaces')->fetchColumn();
if ($spacesCount == 0) {
    $spaces = [
        ['Hot Desks', 'Flexible seating in our open industrial floor', 'Flexible', 'Community', '$160/mo'],
        ['Dedicated Desks', 'Your personal workspace with storage', 'Fixed Desk', 'Storage', '$250/mo'],
        ['Private Offices', 'Lockable offices for teams of 2-10', 'Private', 'Secure', 'From $400/mo'],
        ['Conference Rooms', 'Professional meeting spaces for 4-20 people', 'Hourly', 'A/V Ready', '$50/hr'],
        ['Creative Studios', 'Specialized spaces for content creation', 'Equipment', 'Sound-proof', '$350/mo'],
        ['Event Space', 'Large venue for workshops and gatherings', '150+ Capacity', 'Stage', 'Custom Quote'],
    ];
    $sstmt = $db->prepare('INSERT INTO spaces (title,description,tag1,tag2,tag3,sort_order) VALUES (?,?,?,?,?,?)');
    foreach ($spaces as $i => $s) {
        $sstmt->execute([...$s, $i]);
    }
}

// Seed pricing
$pricingCount = $db->query('SELECT COUNT(*) FROM pricing_plans')->fetchColumn();
if ($pricingCount == 0) {
    $plans = [
        ['Starter', 160, 1632, json_encode(['Hot desk access','High-speed internet','Meeting room credits (5 hrs)','Member events','Coffee & tea']), 0, 'Get Started'],
        ['Professional', 250, 2550, json_encode(['Dedicated desk','24/7 access','Meeting room credits (15 hrs)','Storage locker','Guest passes (5/month)','Printing credits']), 1, 'Get Started'],
        ['Team', 400, 4080, json_encode(['Private office (2-4 people)','24/7 access','Unlimited meeting rooms','Company signage','Dedicated phone line','Premium support']), 0, 'Contact Sales'],
    ];
    $pstmt = $db->prepare('INSERT INTO pricing_plans (name,price_monthly,price_yearly,features,is_featured,button_text,sort_order) VALUES (?,?,?,?,?,?,?)');
    foreach ($plans as $i => $p) {
        $pstmt->execute([...$p, $i]);
    }
}

// Seed amenities
$amenitiesCount = $db->query('SELECT COUNT(*) FROM amenities')->fetchColumn();
if ($amenitiesCount == 0) {
    $amenities = [
        ['☕', 'Craft Coffee Bar', 'Barista-quality coffee and specialty drinks'],
        ['🏃', 'Wellness Room', 'Meditation and wellness space'],
        ['📞', 'Phone Booths', 'Private spaces for calls'],
        ['🎮', 'Game Lounge', 'Unwind with games and entertainment'],
        ['🚿', 'Showers', 'Full shower facilities with lockers'],
        ['📦', 'Mail Service', 'Package handling and mail service'],
        ['🖨️', 'Print Center', 'High-speed printing and scanning'],
        ['🎤', 'Event Space', 'Host workshops and networking events'],
    ];
    $astmt = $db->prepare('INSERT INTO amenities (icon,title,description,sort_order) VALUES (?,?,?,?)');
    foreach ($amenities as $i => $a) {
        $astmt->execute([...$a, $i]);
    }
}

// Seed nav_links
$navCount = $db->query('SELECT COUNT(*) FROM nav_links')->fetchColumn();
if ($navCount == 0) {
    $navLinks = [['Spaces','#spaces'],['Pricing','#pricing'],['Amenities','#amenities'],['Contact','#contact']];
    $nlstmt = $db->prepare('INSERT INTO nav_links (label,url,sort_order) VALUES (?,?,?)');
    foreach ($navLinks as $i => $nl) $nlstmt->execute([...$nl, $i]);
}

// Seed footer_columns
$footerColCount = $db->query('SELECT COUNT(*) FROM footer_columns')->fetchColumn();
if ($footerColCount == 0) {
    $cols = [
        ['Locations', json_encode([['Downtown - Main St','#'],['Eastside - Tech Hub','#'],['Westside - Creative District','#'],['Coming Soon: Airport','#']])],
        ['Company',   json_encode([['About Us','#'],['Careers','#'],['Press','#'],['Partnerships','#']])],
        ['Resources', json_encode([['Blog','#'],['Events','#'],['Member Portal','#'],['FAQ','#']])],
        ['Contact',   json_encode([['hello@forge.work','#'],['1-800-FORGE-IT','#'],['Live Chat','#'],['Support','#']])],
    ];
    $fcstmt = $db->prepare('INSERT INTO footer_columns (heading,links,sort_order) VALUES (?,?,?)');
    foreach ($cols as $i => $c) $fcstmt->execute([...$c, $i]);
}

// Create uploads directory
$uploadsDir = __DIR__ . '/uploads';
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
file_put_contents($uploadsDir . '/.htaccess', "Options -Indexes\n");

echo "<h2 style='font-family:sans-serif'>FORGE CMS installed successfully!</h2>";
echo "<p style='font-family:sans-serif'>Default admin credentials: <strong>admin</strong> / <strong>forge2024</strong><br>";
echo "Please <a href='admin/login.php'>log in</a> and change the password immediately.</p>";
echo "<p style='font-family:sans-serif'><a href='index.php'>View website &rarr;</a></p>";
echo "<p style='font-family:sans-serif;color:red'><strong>Security:</strong> Delete or restrict access to install.php after setup.</p>";
