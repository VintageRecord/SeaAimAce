<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();
$features_count    = $db->query('SELECT COUNT(*) FROM features')->fetchColumn();
$spaces_count      = $db->query('SELECT COUNT(*) FROM spaces')->fetchColumn();
$plans_count       = $db->query('SELECT COUNT(*) FROM pricing_plans')->fetchColumn();
$amenities_count   = $db->query('SELECT COUNT(*) FROM amenities')->fetchColumn();
$submissions_count = $db->query('SELECT COUNT(*) FROM contact_submissions')->fetchColumn();
$new_submissions   = $db->query("SELECT COUNT(*) FROM contact_submissions WHERE created_at >= datetime('now','-7 days')")->fetchColumn();
$recent            = $db->query('SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT 5')->fetchAll();

$page_title  = 'Dashboard';
$active_nav  = 'dashboard';
$show_preview = false;
include '_layout.php';
?>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">Features</div><div class="stat-value stat-accent"><?= $features_count ?></div></div>
    <div class="stat-card"><div class="stat-label">Spaces</div><div class="stat-value stat-accent"><?= $spaces_count ?></div></div>
    <div class="stat-card"><div class="stat-label">Pricing Plans</div><div class="stat-value stat-accent"><?= $plans_count ?></div></div>
    <div class="stat-card"><div class="stat-label">Amenities</div><div class="stat-value stat-accent"><?= $amenities_count ?></div></div>
    <div class="stat-card"><div class="stat-label">Total Submissions</div><div class="stat-value"><?= $submissions_count ?></div></div>
    <div class="stat-card"><div class="stat-label">New (7 days)</div><div class="stat-value stat-accent"><?= $new_submissions ?></div></div>
</div>

<?php if ($recent): ?>
<div class="card">
    <div class="card-header">
        <h2>Recent Submissions</h2>
        <a href="submissions.php" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Interest</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($recent as $r): ?>
                    <tr>
                        <td><?= h($r['name']) ?></td>
                        <td><?= h($r['email']) ?></td>
                        <td><?= h($r['interest'] ?: '—') ?></td>
                        <td style="color:var(--text-muted)"><?= h(substr($r['created_at'], 0, 16)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">No contact submissions yet.</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h2>Quick Links</h2></div>
    <div class="card-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="general.php"   class="btn btn-secondary">General &amp; Hero</a>
        <a href="features.php"  class="btn btn-secondary">Features</a>
        <a href="spaces.php"    class="btn btn-secondary">Spaces</a>
        <a href="pricing.php"   class="btn btn-secondary">Pricing</a>
        <a href="amenities.php" class="btn btn-secondary">Amenities</a>
        <a href="contact.php"   class="btn btn-secondary">Contact Info</a>
        <a href="footer.php"    class="btn btn-secondary">CTA &amp; Footer</a>
    </div>
</div>

<?php include '_layout_end.php'; ?>
