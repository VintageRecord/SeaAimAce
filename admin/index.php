<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();
$total_pages       = $db->query("SELECT COUNT(*) FROM pages")->fetchColumn();
$published_pages   = $db->query("SELECT COUNT(*) FROM pages WHERE status='published'")->fetchColumn();
$draft_pages       = $db->query("SELECT COUNT(*) FROM pages WHERE status='draft'")->fetchColumn();
$total_media       = $db->query("SELECT COUNT(*) FROM media")->fetchColumn();
$submissions_count = $db->query('SELECT COUNT(*) FROM contact_submissions')->fetchColumn();
$new_submissions   = $db->query("SELECT COUNT(*) FROM contact_submissions WHERE created_at >= datetime('now','-7 days')")->fetchColumn();
$recent            = $db->query('SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT 5')->fetchAll();
$recent_pages      = $db->query('SELECT * FROM pages ORDER BY updated_at DESC LIMIT 5')->fetchAll();

$page_title  = 'Dashboard';
$active_nav  = 'dashboard';
$show_preview = false;
include '_layout.php';
?>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">Total Pages</div><div class="stat-value stat-accent"><?= $total_pages ?></div></div>
    <div class="stat-card"><div class="stat-label">Published</div><div class="stat-value stat-accent"><?= $published_pages ?></div></div>
    <div class="stat-card"><div class="stat-label">Drafts</div><div class="stat-value"><?= $draft_pages ?></div></div>
    <div class="stat-card"><div class="stat-label">Media Files</div><div class="stat-value"><?= $total_media ?></div></div>
    <div class="stat-card"><div class="stat-label">Submissions</div><div class="stat-value"><?= $submissions_count ?></div></div>
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

<?php if ($recent_pages): ?>
<div class="card" style="margin-bottom:18px">
    <div class="card-header">
        <h2>Recent Pages</h2>
        <a href="pages.php" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div class="card-body" style="padding:0">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Updated</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($recent_pages as $rp): ?>
                    <tr>
                        <td><?= h($rp['title']) ?></td>
                        <td style="font-family:monospace;font-size:.78rem;color:var(--text-muted)"><?= h($rp['slug']) ?></td>
                        <td><span style="font-size:.72rem;font-weight:600;color:<?= $rp['status']==='published'?'#2ecc71':'#888' ?>"><?= ucfirst($rp['status']) ?></span></td>
                        <td style="color:var(--text-muted)"><?= h(substr($rp['updated_at'],0,16)) ?></td>
                        <td><a href="pages-edit.php?id=<?= $rp['id'] ?>" class="btn btn-secondary btn-sm">Edit</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h2>Quick Links</h2></div>
    <div class="card-body" style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="pages.php"      class="btn btn-secondary">Pages</a>
        <a href="media.php"      class="btn btn-secondary">Media Library</a>
        <a href="navigation.php" class="btn btn-secondary">Navigation &amp; Footer</a>
        <a href="general.php"    class="btn btn-secondary">General &amp; Hero</a>
        <a href="features.php"   class="btn btn-secondary">Features</a>
        <a href="spaces.php"     class="btn btn-secondary">Spaces</a>
        <a href="pricing.php"    class="btn btn-secondary">Pricing</a>
        <a href="amenities.php"  class="btn btn-secondary">Amenities</a>
        <a href="contact.php"    class="btn btn-secondary">Contact Info</a>
    </div>
</div>

<?php include '_layout_end.php'; ?>
