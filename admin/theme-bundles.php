<?php
// Predefined multi-page "themes" that can be applied from the Themed Pages
// admin section. Each bundle creates several new rows in the `pages` table,
// pre-filled with GrapesJS-editable HTML so the admin can customize them
// afterwards in pages-edit.php. Site-wide nav/footer/settings are untouched —
// these are just starter content for extra pages.
$THEME_BUNDLES = [

    'adventure-camp' => [
        'name'  => 'Adventure Camp',
        'desc'  => 'Rustic, earthy starter pages: Trail Guide, Events & Booking, Camp Store.',
        'icon'  => '⛺',
        'pages' => [
            [
                'title' => 'Trail Guide',
                'slug'  => 'trail-guide',
                'html'  => '<section style="background:#2f3b28;color:#fff;padding:100px 40px;text-align:center;">
  <h1 style="font-size:2.8rem;margin-bottom:16px;font-family:Georgia,serif;">Trail Guide</h1>
  <p style="font-size:1.1rem;color:#cdd6c4;max-width:620px;margin:0 auto;">Explore our trail network — from easy loops to full-day treks. Pick your difficulty and hit the path.</p>
</section>
<section style="padding:80px 40px;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:32px;">
  <div style="border:1px solid #ddd;border-radius:10px;padding:28px;"><div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#5a7a4a;font-weight:700;margin-bottom:8px;">Easy</div><h3 style="margin-bottom:10px;">Lakeside Loop</h3><p style="color:#666;font-size:.9rem;line-height:1.7;">2.4 miles, flat terrain, perfect for families and first-time campers.</p></div>
  <div style="border:1px solid #ddd;border-radius:10px;padding:28px;"><div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#b8860b;font-weight:700;margin-bottom:8px;">Moderate</div><h3 style="margin-bottom:10px;">Ridge Overlook</h3><p style="color:#666;font-size:.9rem;line-height:1.7;">5.1 miles, steady climb, rewarded with panoramic valley views.</p></div>
  <div style="border:1px solid #ddd;border-radius:10px;padding:28px;"><div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.08em;color:#a33;font-weight:700;margin-bottom:8px;">Hard</div><h3 style="margin-bottom:10px;">Summit Trek</h3><p style="color:#666;font-size:.9rem;line-height:1.7;">9.8 miles, full-day hike, experienced hikers only. Guide recommended.</p></div>
</section>
<section style="background:#f4f1ea;padding:60px 40px;text-align:center;">
  <h2 style="font-size:1.6rem;margin-bottom:16px;">Need a guide?</h2>
  <p style="color:#666;margin-bottom:24px;">Our rangers lead guided hikes every weekend.</p>
  <a href="contact.php" style="background:#2f3b28;color:#fff;padding:12px 30px;border-radius:6px;font-weight:700;text-decoration:none;">Book a Guided Hike</a>
</section>',
                'css' => '',
            ],
            [
                'title' => 'Events & Booking',
                'slug'  => 'events',
                'html'  => '<section style="background:#2f3b28;color:#fff;padding:100px 40px;text-align:center;">
  <h1 style="font-size:2.8rem;margin-bottom:16px;font-family:Georgia,serif;">Events &amp; Booking</h1>
  <p style="font-size:1.1rem;color:#cdd6c4;max-width:600px;margin:0 auto;">Campfire nights, guided hikes, and seasonal gatherings — see what is coming up.</p>
</section>
<section style="padding:80px 40px;max-width:900px;margin:0 auto;">
  <div style="display:flex;gap:20px;align-items:center;border-bottom:1px solid #eee;padding:24px 0;">
    <div style="background:#2f3b28;color:#fff;border-radius:8px;padding:10px 16px;text-align:center;flex-shrink:0;"><div style="font-size:1.3rem;font-weight:700;">14</div><div style="font-size:.7rem;text-transform:uppercase;">Sept</div></div>
    <div><h3 style="margin-bottom:6px;">Campfire Storytelling Night</h3><p style="color:#666;font-size:.9rem;">Marshmallows, music, and tales around the fire pit. 7 PM at the main lodge.</p></div>
  </div>
  <div style="display:flex;gap:20px;align-items:center;border-bottom:1px solid #eee;padding:24px 0;">
    <div style="background:#2f3b28;color:#fff;border-radius:8px;padding:10px 16px;text-align:center;flex-shrink:0;"><div style="font-size:1.3rem;font-weight:700;">21</div><div style="font-size:.7rem;text-transform:uppercase;">Sept</div></div>
    <div><h3 style="margin-bottom:6px;">Sunrise Ridge Hike</h3><p style="color:#666;font-size:.9rem;">Guided moderate hike departing 6 AM sharp. Coffee provided.</p></div>
  </div>
  <div style="display:flex;gap:20px;align-items:center;padding:24px 0;">
    <div style="background:#2f3b28;color:#fff;border-radius:8px;padding:10px 16px;text-align:center;flex-shrink:0;"><div style="font-size:1.3rem;font-weight:700;">05</div><div style="font-size:.7rem;text-transform:uppercase;">Oct</div></div>
    <div><h3 style="margin-bottom:6px;">Family Fun Weekend</h3><p style="color:#666;font-size:.9rem;">Games, crafts, and a group cookout for all ages.</p></div>
  </div>
</section>
<section style="background:#f4f1ea;padding:60px 40px;text-align:center;">
  <h2 style="font-size:1.6rem;margin-bottom:16px;">Reserve your spot</h2>
  <a href="contact.php" style="background:#2f3b28;color:#fff;padding:12px 30px;border-radius:6px;font-weight:700;text-decoration:none;">Contact Us to Book</a>
</section>',
                'css' => '',
            ],
            [
                'title' => 'Camp Store',
                'slug'  => 'camp-store',
                'html'  => '<section style="background:#2f3b28;color:#fff;padding:100px 40px;text-align:center;">
  <h1 style="font-size:2.8rem;margin-bottom:16px;font-family:Georgia,serif;">Camp Store</h1>
  <p style="font-size:1.1rem;color:#cdd6c4;max-width:600px;margin:0 auto;">Forgot your gear? We have got you covered — eco-friendly essentials for every trip.</p>
</section>
<section style="padding:80px 40px;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:28px;">
  <div style="border:1px solid #eee;border-radius:10px;overflow:hidden;"><div style="height:160px;background:#e8e4d8;display:flex;align-items:center;justify-content:center;font-size:2rem;">🎒</div><div style="padding:18px;"><h3 style="margin-bottom:6px;font-size:1rem;">Trail Backpack</h3><p style="color:#666;font-size:.85rem;">$64.00</p></div></div>
  <div style="border:1px solid #eee;border-radius:10px;overflow:hidden;"><div style="height:160px;background:#e8e4d8;display:flex;align-items:center;justify-content:center;font-size:2rem;">🔦</div><div style="padding:18px;"><h3 style="margin-bottom:6px;font-size:1rem;">Rechargeable Lantern</h3><p style="color:#666;font-size:.85rem;">$28.00</p></div></div>
  <div style="border:1px solid #eee;border-radius:10px;overflow:hidden;"><div style="height:160px;background:#e8e4d8;display:flex;align-items:center;justify-content:center;font-size:2rem;">🧭</div><div style="padding:18px;"><h3 style="margin-bottom:6px;font-size:1rem;">Field Compass</h3><p style="color:#666;font-size:.85rem;">$19.00</p></div></div>
</section>
<section style="background:#f4f1ea;padding:60px 40px;text-align:center;">
  <h2 style="font-size:1.6rem;margin-bottom:16px;">Visit the store on-site</h2>
  <p style="color:#666;">Open daily 8 AM – 8 PM near the main entrance.</p>
</section>',
                'css' => '',
            ],
        ],
    ],

    'modern-minimal' => [
        'name'  => 'Modern Minimal',
        'desc'  => 'Clean, whitespace-heavy starter pages: Our Impact, Blog, Careers.',
        'icon'  => '◻',
        'pages' => [
            [
                'title' => 'Our Impact',
                'slug'  => 'our-impact',
                'html'  => '<section style="padding:110px 40px;text-align:center;max-width:760px;margin:0 auto;">
  <h1 style="font-size:2.6rem;font-weight:700;margin-bottom:18px;letter-spacing:-.02em;">Our Impact</h1>
  <p style="font-size:1.1rem;color:#666;line-height:1.7;">Every stay supports trail conservation and local wildlife programs. Here is what that has added up to.</p>
</section>
<section style="padding:0 40px 100px;max-width:1000px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:40px;text-align:center;">
  <div><div style="font-size:2.6rem;font-weight:700;">12,400</div><div style="color:#888;font-size:.85rem;text-transform:uppercase;letter-spacing:.08em;margin-top:6px;">Trees Planted</div></div>
  <div><div style="font-size:2.6rem;font-weight:700;">340 mi</div><div style="color:#888;font-size:.85rem;text-transform:uppercase;letter-spacing:.08em;margin-top:6px;">Trails Maintained</div></div>
  <div><div style="font-size:2.6rem;font-weight:700;">8,900</div><div style="color:#888;font-size:.85rem;text-transform:uppercase;letter-spacing:.08em;margin-top:6px;">Guests Educated</div></div>
</section>
<section style="background:#fafafa;padding:80px 40px;text-align:center;">
  <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:14px;">Want to help?</h2>
  <a href="contact.php" style="color:#111;font-weight:600;text-decoration:underline;">Get in touch with our conservation team →</a>
</section>',
                'css' => '',
            ],
            [
                'title' => 'Blog',
                'slug'  => 'blog',
                'html'  => '<section style="padding:100px 40px 60px;text-align:center;">
  <h1 style="font-size:2.6rem;font-weight:700;letter-spacing:-.02em;margin-bottom:12px;">From the Blog</h1>
  <p style="color:#666;">Stories, guides, and updates from the trail.</p>
</section>
<section style="padding:0 40px 100px;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:32px;">
  <article><div style="height:180px;background:#eee;border-radius:10px;margin-bottom:14px;"></div><span style="font-size:.72rem;color:#999;text-transform:uppercase;letter-spacing:.08em;">Guide</span><h3 style="margin:8px 0;font-size:1.05rem;">Packing for a Weekend Trip</h3><p style="color:#666;font-size:.88rem;line-height:1.6;">Everything you need, nothing you do not.</p></article>
  <article><div style="height:180px;background:#eee;border-radius:10px;margin-bottom:14px;"></div><span style="font-size:.72rem;color:#999;text-transform:uppercase;letter-spacing:.08em;">Story</span><h3 style="margin:8px 0;font-size:1.05rem;">A Ranger&rsquo;s Morning</h3><p style="color:#666;font-size:.88rem;line-height:1.6;">Behind the scenes with our trail crew.</p></article>
  <article><div style="height:180px;background:#eee;border-radius:10px;margin-bottom:14px;"></div><span style="font-size:.72rem;color:#999;text-transform:uppercase;letter-spacing:.08em;">Update</span><h3 style="margin:8px 0;font-size:1.05rem;">New Trail Opening This Fall</h3><p style="color:#666;font-size:.88rem;line-height:1.6;">A sneak peek at our newest loop.</p></article>
</section>',
                'css' => '',
            ],
            [
                'title' => 'Careers',
                'slug'  => 'careers',
                'html'  => '<section style="padding:100px 40px 60px;text-align:center;">
  <h1 style="font-size:2.6rem;font-weight:700;letter-spacing:-.02em;margin-bottom:12px;">Careers</h1>
  <p style="color:#666;max-width:600px;margin:0 auto;">Join a team that spends its days outdoors. We are always looking for people who love this place as much as we do.</p>
</section>
<section style="padding:0 40px 100px;max-width:800px;margin:0 auto;">
  <div style="border-bottom:1px solid #eee;padding:22px 0;display:flex;justify-content:space-between;align-items:center;"><div><h3 style="font-size:1rem;margin-bottom:4px;">Trail Ranger</h3><span style="color:#888;font-size:.82rem;">Full-time · On-site</span></div><a href="contact.php" style="color:#111;font-weight:600;text-decoration:underline;font-size:.85rem;">Apply →</a></div>
  <div style="border-bottom:1px solid #eee;padding:22px 0;display:flex;justify-content:space-between;align-items:center;"><div><h3 style="font-size:1rem;margin-bottom:4px;">Camp Store Associate</h3><span style="color:#888;font-size:.82rem;">Seasonal · On-site</span></div><a href="contact.php" style="color:#111;font-weight:600;text-decoration:underline;font-size:.85rem;">Apply →</a></div>
  <div style="padding:22px 0;display:flex;justify-content:space-between;align-items:center;"><div><h3 style="font-size:1rem;margin-bottom:4px;">Marketing Coordinator</h3><span style="color:#888;font-size:.82rem;">Full-time · Remote</span></div><a href="contact.php" style="color:#111;font-weight:600;text-decoration:underline;font-size:.85rem;">Apply →</a></div>
</section>',
                'css' => '',
            ],
        ],
    ],

    'family-fun' => [
        'name'  => 'Family Fun',
        'desc'  => 'Bright, playful starter pages: Kids Zone, Guest Reviews, Group Bookings.',
        'icon'  => '🎈',
        'pages' => [
            [
                'title' => 'Kids Zone',
                'slug'  => 'kids-zone',
                'html'  => '<section style="background:#ffb703;color:#3a2a00;padding:90px 40px;text-align:center;">
  <h1 style="font-size:2.8rem;margin-bottom:16px;font-family:Verdana,sans-serif;">Kids Zone</h1>
  <p style="font-size:1.1rem;max-width:600px;margin:0 auto;">Playgrounds, crafts, and games to keep the little campers happy all day long.</p>
</section>
<section style="padding:70px 40px;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:28px;text-align:center;">
  <div style="background:#fff3cd;border-radius:20px;padding:28px;"><div style="font-size:2.4rem;margin-bottom:10px;">🛝</div><h3 style="margin-bottom:8px;">Playground</h3><p style="color:#665;font-size:.88rem;">Open sunrise to sunset, right by the main lodge.</p></div>
  <div style="background:#d8f3dc;border-radius:20px;padding:28px;"><div style="font-size:2.4rem;margin-bottom:10px;">🎨</div><h3 style="margin-bottom:8px;">Craft Corner</h3><p style="color:#665;font-size:.88rem;">Daily 2 PM sessions — painting, friendship bracelets, and more.</p></div>
  <div style="background:#cde7f0;border-radius:20px;padding:28px;"><div style="font-size:2.4rem;margin-bottom:10px;">🔦</div><h3 style="margin-bottom:8px;">Night Games</h3><p style="color:#665;font-size:.88rem;">Flashlight tag and scavenger hunts every Friday evening.</p></div>
</section>
<section style="background:#fff8e6;padding:60px 40px;text-align:center;">
  <h2 style="font-size:1.6rem;margin-bottom:16px;">Bring the whole family</h2>
  <a href="contact.php" style="background:#ffb703;color:#3a2a00;padding:12px 30px;border-radius:50px;font-weight:700;text-decoration:none;">Plan Your Trip</a>
</section>',
                'css' => '',
            ],
            [
                'title' => 'Guest Reviews',
                'slug'  => 'reviews',
                'html'  => '<section style="background:#ffb703;color:#3a2a00;padding:90px 40px;text-align:center;">
  <h1 style="font-size:2.8rem;margin-bottom:16px;font-family:Verdana,sans-serif;">What Guests Say</h1>
  <p style="font-size:1.1rem;max-width:600px;margin:0 auto;">Real stories from families who made memories with us.</p>
</section>
<section style="padding:70px 40px;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:28px;">
  <div style="background:#fff3cd;border-radius:20px;padding:28px;"><div style="color:#e8a400;margin-bottom:10px;">★★★★★</div><p style="color:#554;font-size:.92rem;line-height:1.7;margin-bottom:12px;">"The kids didn&rsquo;t want to leave! Best family trip we&rsquo;ve had in years."</p><strong style="font-size:.85rem;">— The Martinez Family</strong></div>
  <div style="background:#d8f3dc;border-radius:20px;padding:28px;"><div style="color:#e8a400;margin-bottom:10px;">★★★★★</div><p style="color:#554;font-size:.92rem;line-height:1.7;margin-bottom:12px;">"Clean, friendly, and the staff went above and beyond for us."</p><strong style="font-size:.85rem;">— Priya S.</strong></div>
  <div style="background:#cde7f0;border-radius:20px;padding:28px;"><div style="color:#e8a400;margin-bottom:10px;">★★★★★</div><p style="color:#554;font-size:.92rem;line-height:1.7;margin-bottom:12px;">"Beautiful grounds and the trail guide made our hike unforgettable."</p><strong style="font-size:.85rem;">— Tom R.</strong></div>
</section>',
                'css' => '',
            ],
            [
                'title' => 'Group Bookings',
                'slug'  => 'group-bookings',
                'html'  => '<section style="background:#ffb703;color:#3a2a00;padding:90px 40px;text-align:center;">
  <h1 style="font-size:2.8rem;margin-bottom:16px;font-family:Verdana,sans-serif;">Group Bookings</h1>
  <p style="font-size:1.1rem;max-width:600px;margin:0 auto;">Planning a reunion, scout troop, or company retreat? We make group stays easy.</p>
</section>
<section style="padding:70px 40px;max-width:800px;margin:0 auto;">
  <div style="background:#fff3cd;border-radius:20px;padding:28px;margin-bottom:20px;"><h3 style="margin-bottom:8px;">10-24 Guests</h3><p style="color:#665;font-size:.9rem;">Reserve a cluster of sites together, plus access to our group fire pit.</p></div>
  <div style="background:#d8f3dc;border-radius:20px;padding:28px;margin-bottom:20px;"><h3 style="margin-bottom:8px;">25-60 Guests</h3><p style="color:#665;font-size:.9rem;">Includes the main pavilion for shared meals and a dedicated coordinator.</p></div>
  <div style="background:#cde7f0;border-radius:20px;padding:28px;"><h3 style="margin-bottom:8px;">60+ Guests</h3><p style="color:#665;font-size:.9rem;">Full-site buyouts available for retreats and large celebrations.</p></div>
</section>
<section style="background:#fff8e6;padding:60px 40px;text-align:center;">
  <a href="contact.php" style="background:#ffb703;color:#3a2a00;padding:12px 30px;border-radius:50px;font-weight:700;text-decoration:none;">Request a Group Quote</a>
</section>',
                'css' => '',
            ],
        ],
    ],

    'bold-adventure' => [
        'name'  => 'Bold Adventure',
        'desc'  => 'Dark, high-contrast starter pages: Gear Rental, Trail Conditions, Safety Guidelines.',
        'icon'  => '🏔️',
        'pages' => [
            [
                'title' => 'Gear Rental',
                'slug'  => 'gear-rental',
                'html'  => '<section style="background:#0d1117;color:#fff;padding:100px 40px;text-align:center;">
  <h1 style="font-size:2.9rem;margin-bottom:16px;font-weight:800;letter-spacing:-.02em;">Gear Rental</h1>
  <p style="font-size:1.1rem;color:#9aa;max-width:600px;margin:0 auto;">Show up empty-handed. Rent everything you need, right on site.</p>
</section>
<section style="background:#0d1117;padding:0 40px 90px;max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:2px;background-color:#222;">
  <div style="background:#161b22;padding:32px;color:#fff;"><h3 style="margin-bottom:8px;font-size:1.05rem;">Tents &amp; Sleeping</h3><p style="color:#8b95a1;font-size:.88rem;">4-person tents, sleeping bags rated to 20°F, camp pads.</p></div>
  <div style="background:#161b22;padding:32px;color:#fff;"><h3 style="margin-bottom:8px;font-size:1.05rem;">Climbing</h3><p style="color:#8b95a1;font-size:.88rem;">Harnesses, helmets, and ropes inspected before every rental.</p></div>
  <div style="background:#161b22;padding:32px;color:#fff;"><h3 style="margin-bottom:8px;font-size:1.05rem;">Water Sports</h3><p style="color:#8b95a1;font-size:.88rem;">Kayaks, paddleboards, and life vests for all sizes.</p></div>
</section>
<section style="background:#161b22;padding:60px 40px;text-align:center;">
  <a href="contact.php" style="background:#fff;color:#0d1117;padding:12px 30px;border-radius:4px;font-weight:700;text-decoration:none;">Reserve Gear</a>
</section>',
                'css' => '',
            ],
            [
                'title' => 'Trail Conditions',
                'slug'  => 'trail-conditions',
                'html'  => '<section style="background:#0d1117;color:#fff;padding:100px 40px;text-align:center;">
  <h1 style="font-size:2.9rem;margin-bottom:16px;font-weight:800;letter-spacing:-.02em;">Trail &amp; Weather Conditions</h1>
  <p style="font-size:1.1rem;color:#9aa;max-width:600px;margin:0 auto;">Updated daily by our ranger team before you head out.</p>
</section>
<section style="background:#0d1117;padding:0 40px 90px;max-width:900px;margin:0 auto;">
  <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #222;padding:20px 0;color:#fff;"><span>Lakeside Loop</span><span style="background:#1f6f3c;color:#fff;padding:4px 12px;border-radius:4px;font-size:.78rem;font-weight:700;">OPEN</span></div>
  <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #222;padding:20px 0;color:#fff;"><span>Ridge Overlook</span><span style="background:#8a6d1f;color:#fff;padding:4px 12px;border-radius:4px;font-size:.78rem;font-weight:700;">CAUTION — MUDDY</span></div>
  <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 0;color:#fff;"><span>Summit Trek</span><span style="background:#7a2020;color:#fff;padding:4px 12px;border-radius:4px;font-size:.78rem;font-weight:700;">CLOSED</span></div>
</section>',
                'css' => '',
            ],
            [
                'title' => 'Safety Guidelines',
                'slug'  => 'safety',
                'html'  => '<section style="background:#0d1117;color:#fff;padding:100px 40px;text-align:center;">
  <h1 style="font-size:2.9rem;margin-bottom:16px;font-weight:800;letter-spacing:-.02em;">Safety Guidelines</h1>
  <p style="font-size:1.1rem;color:#9aa;max-width:600px;margin:0 auto;">Read before you head into the backcountry.</p>
</section>
<section style="background:#0d1117;padding:0 40px 90px;max-width:800px;margin:0 auto;">
  <div style="display:flex;gap:18px;padding:20px 0;border-bottom:1px solid #222;color:#fff;"><strong style="color:#fff;opacity:.4;font-size:1.3rem;">01</strong><div><h3 style="margin-bottom:6px;font-size:1rem;">Check in at the ranger station</h3><p style="color:#8b95a1;font-size:.88rem;">Before any hike beyond the Lakeside Loop.</p></div></div>
  <div style="display:flex;gap:18px;padding:20px 0;border-bottom:1px solid #222;color:#fff;"><strong style="color:#fff;opacity:.4;font-size:1.3rem;">02</strong><div><h3 style="margin-bottom:6px;font-size:1rem;">Carry 2L of water minimum</h3><p style="color:#8b95a1;font-size:.88rem;">More on hot days or longer treks.</p></div></div>
  <div style="display:flex;gap:18px;padding:20px 0;color:#fff;"><strong style="color:#fff;opacity:.4;font-size:1.3rem;">03</strong><div><h3 style="margin-bottom:6px;font-size:1rem;">Never hike alone past dusk</h3><p style="color:#8b95a1;font-size:.88rem;">Trails close to solo hikers after sunset.</p></div></div>
</section>',
                'css' => '',
            ],
        ],
    ],
];
