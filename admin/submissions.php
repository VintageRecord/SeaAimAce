<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

// Delete single
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $db->prepare('DELETE FROM contact_submissions WHERE id = ?')->execute([(int)$_GET['delete']]);
    redirect('submissions.php?deleted=1');
}

// Delete all
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_all'])) {
    $db->exec('DELETE FROM contact_submissions');
    redirect('submissions.php?deleted=1');
}

$page = max(1, (int)($_GET['page'] ?? 1));
$per  = 20;
$total = $db->query('SELECT COUNT(*) FROM contact_submissions')->fetchColumn();
$pages = max(1, (int)ceil($total / $per));
$offset = ($page - 1) * $per;

$submissions = $db->prepare('SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT ? OFFSET ?');
$submissions->execute([$per, $offset]);
$submissions = $submissions->fetchAll();

// Detail view
$detail = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $stmt = $db->prepare('SELECT * FROM contact_submissions WHERE id = ?');
    $stmt->execute([(int)$_GET['view']]);
    $detail = $stmt->fetch();
}

$page_title = 'Contact Submissions';
$active_nav = 'submissions';
include '_layout.php';
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">✅ Deleted successfully.</div>
<?php endif; ?>

<?php if ($detail): ?>
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h2>Submission #<?= $detail['id'] ?></h2>
        <a href="submissions.php" class="btn btn-secondary btn-sm">← Back to list</a>
    </div>
    <div class="card-body">
        <table style="width:auto">
            <tr><th style="text-align:right;padding-right:20px;color:var(--text-muted)">Name</th><td><?= h($detail['name']) ?></td></tr>
            <tr><th style="text-align:right;padding-right:20px;color:var(--text-muted)">Email</th><td><a href="mailto:<?= h($detail['email']) ?>"><?= h($detail['email']) ?></a></td></tr>
            <tr><th style="text-align:right;padding-right:20px;color:var(--text-muted)">Phone</th><td><?= h($detail['phone'] ?: '—') ?></td></tr>
            <tr><th style="text-align:right;padding-right:20px;color:var(--text-muted)">Company</th><td><?= h($detail['company'] ?: '—') ?></td></tr>
            <tr><th style="text-align:right;padding-right:20px;color:var(--text-muted)">Interest</th><td><?= h($detail['interest'] ?: '—') ?></td></tr>
            <tr><th style="text-align:right;padding-right:20px;color:var(--text-muted)">Date</th><td><?= h($detail['created_at']) ?></td></tr>
            <tr><th style="text-align:right;padding-right:20px;color:var(--text-muted);vertical-align:top">Message</th><td style="white-space:pre-wrap;max-width:500px"><?= h($detail['message']) ?></td></tr>
        </table>
        <div style="margin-top:16px">
            <a href="submissions.php?delete=<?= $detail['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this submission?')">🗑 Delete</a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>All Submissions (<?= $total ?>)</h2>
        <?php if ($total > 0): ?>
        <form method="POST" onsubmit="return confirm('Delete ALL submissions? This cannot be undone.')">
            <button type="submit" name="delete_all" class="btn btn-danger btn-sm">🗑 Delete All</button>
        </form>
        <?php endif; ?>
    </div>
    <div class="card-body" style="padding:0">
        <?php if (empty($submissions)): ?>
        <div style="padding:24px;color:var(--text-muted);text-align:center">No submissions yet.</div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>#</th><th>Name</th><th>Email</th><th>Interest</th><th>Message</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $s): ?>
                    <tr>
                        <td style="color:var(--text-muted)"><?= $s['id'] ?></td>
                        <td><?= h($s['name']) ?></td>
                        <td><?= h($s['email']) ?></td>
                        <td style="color:var(--text-muted)"><?= h($s['interest'] ?: '—') ?></td>
                        <td class="submission-message"><?= h($s['message']) ?></td>
                        <td style="color:var(--text-muted);white-space:nowrap"><?= h(substr($s['created_at'], 0, 16)) ?></td>
                        <td class="actions-cell">
                            <a href="submissions.php?view=<?= $s['id'] ?>" class="btn btn-secondary btn-sm">View</a>
                            <a href="submissions.php?delete=<?= $s['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($pages > 1): ?>
        <div style="padding:16px;display:flex;gap:8px;justify-content:center">
            <?php for ($p = 1; $p <= $pages; $p++): ?>
            <a href="?page=<?= $p ?>" class="btn <?= $p === $page ? 'btn-primary' : 'btn-secondary' ?> btn-sm"><?= $p ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '_layout_end.php'; ?>
