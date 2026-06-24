<?php
require_once __DIR__ . '/config.php';

$db = get_db();

// Handle contact form submission
$form_success = false;
$form_error   = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name     = trim($_POST['name']    ?? '');
    $email    = trim($_POST['email']   ?? '');
    $phone    = trim($_POST['phone']   ?? '');
    $company  = trim($_POST['company'] ?? '');
    $interest = trim($_POST['interest']?? '');
    $message  = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '') {
        $form_error = 'Name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $form_error = 'Please enter a valid email address.';
    } else {
        $stmt = $db->prepare('INSERT INTO contact_submissions (name,email,phone,company,interest,message) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$name, $email, $phone, $company, $interest, $message]);
        $form_success = true;
    }
}

$features = $db->query('SELECT * FROM features ORDER BY sort_order')->fetchAll();
$spaces   = $db->query('SELECT * FROM spaces ORDER BY sort_order')->fetchAll();
$plans    = $db->query('SELECT * FROM pricing_plans ORDER BY sort_order')->fetchAll();
$amenities= $db->query('SELECT * FROM amenities ORDER BY sort_order')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(setting('site_title', 'FORGE - Industrial Coworking Space')) ?></title>
    <link rel="stylesheet" href="tooplate-forge-style.css">
</head>
<body id="top">
    <div class="loading-bar"></div>

    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="#top" class="logo">
                <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 10 L5 30 L15 30 L15 20 L25 20 L25 30 L35 30 L35 10 L25 10 L25 15 L15 15 L15 10 Z" fill="#E63946"/>
                    <rect x="18" y="5" width="4" height="30" fill="#FF6B35" opacity="0.8"/>
                    <rect x="5" y="18" width="30" height="4" fill="#FF6B35" opacity="0.8"/>
                </svg>
                <?= h(setting('nav_logo_text', 'FORGE')) ?>
            </a>
            <ul class="nav-links">
                <li><a href="#spaces" class="nav-link">Spaces</a></li>
                <li><a href="#pricing" class="nav-link">Pricing</a></li>
                <li><a href="#amenities" class="nav-link">Amenities</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
            <a href="<?= h(setting('nav_book_btn_href', '#contact')) ?>" class="book-tour-btn"><?= h(setting('nav_book_btn_text', 'Book Tour')) ?></a>
            <div class="menu-toggle">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-backgrounds">
            <div class="hero-bg"></div>
            <div class="hero-bg"></div>
            <div class="hero-bg"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1><?= h(setting('hero_heading_line1')) ?><br><span><?= h(setting('hero_heading_line2')) ?></span></h1>
                <p class="hero-subtitle"><?= h(setting('hero_subtitle')) ?></p>
                <div class="hero-buttons">
                    <a href="<?= h(setting('hero_btn1_href', '#pricing')) ?>" class="btn-primary"><?= h(setting('hero_btn1_text', 'Start Free Trial')) ?></a>
                    <a href="<?= h(setting('hero_btn2_href', '#contact')) ?>" class="btn-secondary"><?= h(setting('hero_btn2_text', 'Virtual Tour')) ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2><?= h(setting('features_heading', 'Built Different')) ?></h2>
                <p><?= h(setting('features_subtext')) ?></p>
            </div>
            <div class="features-grid">
                <?php foreach ($features as $f): ?>
                <div class="feature-card">
                    <div class="feature-icon <?= $f['icon'] === '24/7' ? 'icon-24-7' : '' ?>"><?= h($f['icon']) ?></div>
                    <h3><?= h($f['title']) ?></h3>
                    <p><?= h($f['description']) ?></p>
                    <div class="feature-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?= h($f['stat1_num']) ?></span>
                            <span class="stat-label"><?= h($f['stat1_label']) ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?= h($f['stat2_num']) ?></span>
                            <span class="stat-label"><?= h($f['stat2_label']) ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?= h($f['stat3_num']) ?></span>
                            <span class="stat-label"><?= h($f['stat3_label']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Spaces Section -->
    <section class="spaces" id="spaces">
        <div class="container">
            <div class="section-header">
                <h2><?= h(setting('spaces_heading', 'Choose Your Arena')) ?></h2>
                <p><?= h(setting('spaces_subtext')) ?></p>
            </div>
            <div class="spaces-grid">
                <?php foreach ($spaces as $s): ?>
                <div class="space-card">
                    <div class="space-thumbnail"></div>
                    <div class="space-info">
                        <h3><?= h($s['title']) ?></h3>
                        <p><?= h($s['description']) ?></p>
                        <div class="space-features">
                            <?php if ($s['tag1']): ?><span class="space-feature-tag"><?= h($s['tag1']) ?></span><?php endif; ?>
                            <?php if ($s['tag2']): ?><span class="space-feature-tag"><?= h($s['tag2']) ?></span><?php endif; ?>
                            <?php if ($s['tag3']): ?><span class="space-feature-tag"><?= h($s['tag3']) ?></span><?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-header">
                <h2><?= h(setting('pricing_heading', 'Membership Plans')) ?></h2>
                <p><?= h(setting('pricing_subtext')) ?></p>
            </div>
            <div class="pricing-toggle">
                <span class="pricing-toggle-label active" id="monthly-label">Monthly</span>
                <div class="toggle-switch" id="pricing-toggle"></div>
                <span class="pricing-toggle-label" id="yearly-label">Yearly<span class="pricing-badge">Save 20%</span></span>
            </div>
            <div class="pricing-grid">
                <?php foreach ($plans as $plan): ?>
                <div class="pricing-card <?= $plan['is_featured'] ? 'featured' : '' ?>">
                    <h3 class="plan-name"><?= h($plan['name']) ?></h3>
                    <div class="plan-price">$<span class="price-amount" data-monthly="<?= (int)$plan['price_monthly'] ?>" data-yearly="<?= (int)$plan['price_yearly'] ?>"><?= (int)$plan['price_monthly'] ?></span><span class="price-period">/month</span></div>
                    <ul class="plan-features">
                        <?php foreach (json_decode($plan['features'], true) as $feat): ?>
                        <li><?= h($feat) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button class="btn-primary" style="width:100%;text-align:center"><?= h($plan['button_text']) ?></button>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="special-pricing">
                <h3><?= h(setting('pricing_enterprise_heading')) ?></h3>
                <p><?= h(setting('pricing_enterprise_text1')) ?></p>
                <div class="special-features">
                    <div class="special-feature">
                        <span class="special-feature-number"><?= h(setting('pricing_enterprise_feat1_num')) ?></span>
                        <span class="special-feature-label"><?= h(setting('pricing_enterprise_feat1_label')) ?></span>
                    </div>
                    <div class="special-feature">
                        <span class="special-feature-number"><?= h(setting('pricing_enterprise_feat2_num')) ?></span>
                        <span class="special-feature-label"><?= h(setting('pricing_enterprise_feat2_label')) ?></span>
                    </div>
                    <div class="special-feature">
                        <span class="special-feature-number"><?= h(setting('pricing_enterprise_feat3_num')) ?></span>
                        <span class="special-feature-label"><?= h(setting('pricing_enterprise_feat3_label')) ?></span>
                    </div>
                </div>
                <p><?= h(setting('pricing_enterprise_text2')) ?></p>
                <a href="<?= h(setting('pricing_enterprise_btn_href', '#contact')) ?>" class="btn-primary"><?= h(setting('pricing_enterprise_btn_text', 'Get Custom Quote')) ?></a>
            </div>
        </div>
    </section>

    <!-- Amenities Section -->
    <section class="amenities" id="amenities">
        <div class="container">
            <div class="section-header">
                <h2><?= h(setting('amenities_heading', 'Everything You Need')) ?></h2>
                <p><?= h(setting('amenities_subtext')) ?></p>
            </div>
            <div class="amenities-grid">
                <?php foreach ($amenities as $a): ?>
                <div class="amenity-item">
                    <div class="amenity-icon"><?= h($a['icon']) ?></div>
                    <h3><?= h($a['title']) ?></h3>
                    <p><?= h($a['description']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header">
                <h2><?= h(setting('contact_heading', 'Get In Touch')) ?></h2>
                <p><?= h(setting('contact_subtext')) ?></p>
            </div>
            <div class="contact-grid">
                <div class="contact-left">
                    <div class="contact-info">
                        <h3><?= h(setting('contact_visit_heading')) ?></h3>
                        <p><?= h(setting('contact_visit_text')) ?></p>
                        <ul class="contact-details">
                            <li><strong>Address</strong><span><?= h(setting('contact_address')) ?></span></li>
                            <li><strong>Phone</strong><span><?= h(setting('contact_phone')) ?></span></li>
                            <li><strong>Email</strong><span><?= h(setting('contact_email')) ?></span></li>
                            <li><strong>Hours</strong><span><?= h(setting('contact_hours')) ?></span></li>
                        </ul>
                        <div class="contact-offer">
                            <strong><?= h(setting('contact_offer_title')) ?></strong>
                            <p><?= h(setting('contact_offer_text')) ?></p>
                        </div>
                    </div>
                    <div class="contact-map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.3652287879687!2d-74.00594368459395!3d40.71005597933047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a316636d887%3A0xcc93b1a9f6b0d5c8!2sIndustrial%20District%2C%20New%20York%2C%20NY%2010013!5e0!3m2!1sen!2sus!4v1647890123456!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        <div class="map-overlay">
                            <div class="map-content">
                                <div class="map-icon">📍</div>
                                <h4>Interactive Map</h4>
                                <p>Hover to explore</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-form-wrapper">
                    <?php if ($form_success): ?>
                    <div style="background:#1a1a1a;border:1px solid #E63946;border-radius:8px;padding:40px;text-align:center;color:#fff;">
                        <div style="font-size:3rem;margin-bottom:16px">✅</div>
                        <h3 style="color:#E63946;margin-bottom:8px">Message Sent!</h3>
                        <p>Thanks! We'll be in touch shortly.</p>
                        <a href="#contact" class="btn-primary" style="display:inline-block;margin-top:20px">Send Another</a>
                    </div>
                    <?php else: ?>
                    <form class="contact-form" method="POST" action="#contact">
                        <div class="form-header">
                            <h3><?= h(setting('contact_form_heading', 'Start Your Journey')) ?></h3>
                            <p><?= h(setting('contact_form_subtext')) ?></p>
                        </div>
                        <?php if ($form_error): ?>
                        <div style="background:#E63946;color:#fff;padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:.9rem"><?= h($form_error) ?></div>
                        <?php endif; ?>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" placeholder="John Doe" value="<?= h($_POST['name'] ?? '') ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="john@company.com" value="<?= h($_POST['email'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="+1 (555) 123-4567" value="<?= h($_POST['phone'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="company">Company Name</label>
                                <input type="text" id="company" name="company" placeholder="Your Company" value="<?= h($_POST['company'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group full-width">
                            <label for="interest">Workspace Type</label>
                            <select id="interest" name="interest">
                                <option value="">Select your ideal workspace</option>
                                <option>Hot Desk - Flexible Daily</option>
                                <option>Dedicated Desk - Your Own Space</option>
                                <option>Private Office - 2-4 People</option>
                                <option>Team Suite - 5-10 People</option>
                                <option>Enterprise Floor - 10+ People</option>
                                <option>Event Space - One-time Booking</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label for="message">Tell Us More</label>
                            <textarea id="message" name="message" placeholder="Share your vision, team size, preferred start date, or any special requirements..."><?= h($_POST['message'] ?? '') ?></textarea>
                        </div>
                        <input type="hidden" name="contact_submit" value="1">
                        <button type="submit" class="form-submit"><span>Send Message</span></button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2><?= h(setting('cta_heading')) ?></h2>
                <p><?= h(setting('cta_subtext')) ?></p>
                <div class="cta-buttons">
                    <a href="<?= h(setting('cta_btn1_href', '#pricing')) ?>" class="btn-primary"><?= h(setting('cta_btn1_text', 'Start Free Week')) ?></a>
                    <a href="<?= h(setting('cta_btn2_href', '#contact')) ?>" class="btn-secondary"><?= h(setting('cta_btn2_text', 'Schedule Tour')) ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Locations</h4>
                    <ul>
                        <li><a href="#">Downtown - Main St</a></li>
                        <li><a href="#">Eastside - Tech Hub</a></li>
                        <li><a href="#">Westside - Creative District</a></li>
                        <li><a href="#">Coming Soon: Airport</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Partnerships</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Events</a></li>
                        <li><a href="#">Member Portal</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="mailto:<?= h(setting('contact_email')) ?>"><?= h(setting('contact_email')) ?></a></li>
                        <li><a href="tel:<?= h(setting('contact_phone')) ?>"><?= h(setting('contact_phone')) ?></a></li>
                        <li><a href="#">Live Chat</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-credits">
                    <p><?= h(setting('footer_copyright', '© 2026 FORGE Coworking. Built to last.')) ?></p>
                    <p>Design: <a rel="nofollow" href="https://www.tooplate.com" target="_blank">Tooplate</a></p>
                </div>
                <div class="social-links">
                    <a href="#">f</a>
                    <a href="#">tw</a>
                    <a href="#">in</a>
                    <a href="#">ig</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="tooplate-forge-script.js"></script>
</body>
</html>
