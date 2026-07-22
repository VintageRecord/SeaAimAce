<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();

// Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $del_id   = (int)$_GET['delete'];
    $del_stmt = $db->prepare('SELECT slug FROM pages WHERE id = ?');
    $del_stmt->execute([$del_id]);
    $del_slug = $del_stmt->fetchColumn();

    $db->prepare('DELETE FROM pages WHERE id = ?')->execute([$del_id]);
    if ($del_slug) {
        $db->prepare('DELETE FROM nav_links WHERE url = ?')->execute(['preview.php?slug=' . $del_slug]);
    }

    $back = ($_GET['from'] ?? '') === 'themed' ? 'themed-pages.php?deleted=1' : 'pages.php?deleted=1';
    redirect($back);
}

$search = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

$where  = [];
$params = [];
if ($search !== '') {
    $where[]  = '(title LIKE ? OR slug LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}
if (in_array($status, ['published', 'draft'])) {
    $where[]  = 'status = ?';
    $params[] = $status;
}

$sql   = 'SELECT * FROM pages' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY updated_at DESC';
$stmt  = $db->prepare($sql);
$stmt->execute($params);
$pages = $stmt->fetchAll();

$total_pages     = $db->query("SELECT COUNT(*) FROM pages")->fetchColumn();
$published_count = $db->query("SELECT COUNT(*) FROM pages WHERE status='published'")->fetchColumn();
$draft_count     = $db->query("SELECT COUNT(*) FROM pages WHERE status='draft'")->fetchColumn();

$page_title  = 'Pages';
$active_nav  = 'pages';
$show_preview = false;
include '_layout.php';
?>

<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">Page deleted.</div>
<?php endif; ?>
<?php if (isset($_GET['saved'])): ?>
<div class="alert alert-success">Page saved.</div>
<?php endif; ?>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
    <div class="stat-card"><div class="stat-label">Total Pages</div><div class="stat-value"><?= $total_pages ?></div></div>
    <div class="stat-card"><div class="stat-label">Published</div><div class="stat-value stat-accent"><?= $published_count ?></div></div>
    <div class="stat-card"><div class="stat-label">Drafts</div><div class="stat-value"><?= $draft_count ?></div></div>
</div>

<div class="card">
    <div class="card-header">
        <h2>All Pages</h2>
        <a href="pages-edit.php" class="btn btn-primary btn-sm">+ New Page</a>
    </div>
    <div class="card-body" style="padding:14px 18px;border-bottom:1px solid var(--border)">
        <form method="GET" style="display:flex;gap:10px;align-items:center">
            <input type="text" name="q" value="<?= h($search) ?>" placeholder="Search by title or slug..." style="flex:1;background:var(--surface2);border:1px solid var(--border);color:var(--text);padding:7px 11px;border-radius:5px;font-size:.85rem;font-family:inherit">
            <select name="status" style="background:var(--surface2);border:1px solid var(--border);color:var(--text);padding:7px 11px;border-radius:5px;font-size:.85rem;font-family:inherit">
                <option value="">All status</option>
                <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="draft"     <?= $status === 'draft'     ? 'selected' : '' ?>>Draft</option>
            </select>
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            <?php if ($search || $status): ?>
            <a href="pages.php" class="btn btn-secondary btn-sm">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="card-body" style="padding:0">
        <?php if (empty($pages)): ?>
        <div style="padding:24px;text-align:center;color:var(--text-muted)">No pages found. <a href="pages-edit.php">Create one.</a></div>
        <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $p): ?>
                    <tr>
                        <td><strong><?= h($p['title']) ?></strong></td>
                        <td style="font-family:monospace;font-size:.8rem;color:var(--text-muted)"><?= h($p['slug']) ?></td>
                        <td>
                            <?php if ($p['status'] === 'published'): ?>
                            <span style="display:inline-block;padding:2px 8px;border-radius:20px;font-size:.72rem;font-weight:600;background:#0a2a18;border:1px solid #1a5a38;color:#2ecc71">Published</span>
                            <?php else: ?>
                            <span style="display:inline-block;padding:2px 8px;border-radius:20px;font-size:.72rem;font-weight:600;background:#1e1e1e;border:1px solid #2a2a2a;color:#888">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td style="color:var(--text-muted)"><?= h(substr($p['updated_at'], 0, 16)) ?></td>
                        <td style="white-space:nowrap">
                            <a href="pages-edit.php?id=<?= $p['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="../preview.php?slug=<?= h($p['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm">Preview</a>
                            <a href="pages.php?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete page \'<?= h(addslashes($p['title'])) ?>\'?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '_layout_end.php'; ?>
