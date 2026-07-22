<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();
require __DIR__ . '/theme-bundles.php';

$db = get_db();

$rows = $db->query("SELECT * FROM pages WHERE theme_group != '' ORDER BY theme_group, id")->fetchAll();

$groups = [];
foreach ($rows as $r) {
    $groups[$r['theme_group']][] = $r;
}

$nav_urls = $db->query('SELECT url FROM nav_links')->fetchAll(PDO::FETCH_COLUMN);

$page_title  = 'Themed Pages';
$active_nav  = 'themed-pages';
$show_preview = false;
include '_layout.php';
?>

<?php if (isset($_GET['applied']) && is_string($_GET['applied'])):
    $applied_name = $THEME_BUNDLES[$_GET['applied']]['name'] ?? $_GET['applied'];
?>
<div class="alert alert-success">"<?= h($applied_name) ?>" theme applied — new pages were created as drafts below. Edit each one, then publish and add it to your nav if you want it public.</div>
<?php endif; ?>
<?php if (isset($_GET['nav_added'])): ?>
<div class="alert alert-success">Added to Navigation. <a href="navigation.php" style="text-decoration:underline">Manage nav links</a></div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
<div class="alert alert-success">Page deleted.</div>
<?php endif; ?>

<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h2>Themed Pages</h2>
        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('theme-modal').style.display='flex'">+ Add Theme</button>
    </div>
    <div class="card-body">
        <p style="color:var(--text-muted);font-size:.85rem;margin:0">
            Apply a bundled theme to instantly create a set of starter pages (edited with the same page editor as any other page).
            Your site-wide Navigation &amp; Footer settings stay exactly as configured — themed pages just need to be added as nav links once you're happy with them.
        </p>
    </div>
</div>

<?php if (empty($groups)): ?>
<div class="card">
    <div class="card-body" style="text-align:center;padding:40px;color:var(--text-muted)">
        No themed pages yet. Click <strong>+ Add Theme</strong> to generate a starter set.
    </div>
</div>
<?php else: foreach ($groups as $group_id => $pages):
    $bundle = $THEME_BUNDLES[$group_id] ?? null;
?>
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h2><?= $bundle ? h($bundle['icon'] . ' ' . $bundle['name']) : h($group_id) ?></h2>
    </div>
    <div class="card-body" style="padding:0">
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
                    <?php foreach ($pages as $p):
                        $_in_nav = in_array('preview.php?slug=' . $p['slug'], $nav_urls, true);
                    ?>
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
                            <?php if ($_in_nav): ?>
                            <span class="btn btn-secondary btn-sm" style="opacity:.6;cursor:default" title="Already in the nav menu">In Nav ✓</span>
                            <?php else: ?>
                            <a href="nav-quick-add.php?page_id=<?= $p['id'] ?>" class="btn btn-secondary btn-sm">+ Add to Nav</a>
                            <?php endif; ?>
                            <a href="pages.php?delete=<?= $p['id'] ?>&from=themed" class="btn btn-danger btn-sm" onclick="return confirm('Delete page \'<?= h(addslashes($p['title'])) ?>\'?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endforeach; endif; ?>

<!-- Theme picker modal -->
<div id="theme-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.75);align-items:center;justify-content:center;backdrop-filter:blur(3px)">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;width:760px;max-width:95vw;max-height:88vh;display:flex;flex-direction:column;overflow:hidden">
        <div style="padding:20px 24px 14px;border-bottom:1px solid var(--border)">
            <h2 style="font-size:1.05rem;font-weight:700;color:#fff;margin:0">Choose a Theme</h2>
            <p style="font-size:.8rem;color:var(--text-muted);margin:4px 0 0">Each theme creates a set of new draft pages you can edit and publish.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:14px;padding:20px 24px;overflow-y:auto">
            <?php foreach ($THEME_BUNDLES as $id => $bundle): ?>
            <div style="background:var(--surface2);border:2px solid var(--border);border-radius:8px;padding:18px;cursor:pointer;transition:border-color .15s"
                 onmouseover="this.style.borderColor='var(--accent,#E63946)'" onmouseout="this.style.borderColor='var(--border)'"
                 onclick="applyTheme('<?= h($id) ?>', this)">
                <div style="font-size:1.8rem;margin-bottom:8px"><?= h($bundle['icon']) ?></div>
                <strong style="display:block;font-size:.95rem;color:#fff;margin-bottom:4px"><?= h($bundle['name']) ?></strong>
                <span style="display:block;font-size:.78rem;color:var(--text-muted);line-height:1.5;margin-bottom:10px"><?= h($bundle['desc']) ?></span>
                <span style="font-size:.72rem;color:var(--text-muted)"><?= count($bundle['pages']) ?> pages: <?= h(implode(', ', array_column($bundle['pages'], 'title'))) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="padding:14px 24px;border-top:1px solid var(--border);display:flex;justify-content:flex-end">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('theme-modal').style.display='none'">Cancel</button>
        </div>
    </div>
</div>

<script>
async function applyTheme(bundleId, cardEl) {
    if (!confirm('Create new starter pages for this theme?')) return;
    cardEl.style.opacity = '.5';
    cardEl.style.pointerEvents = 'none';
    try {
        const fd = new FormData();
        fd.append('bundle', bundleId);
        const res = await fetch('theme-apply.php', { method: 'POST', body: fd, headers: { 'X-Ajax': '1' } });
        const json = await res.json();
        if (json.success) {
            window.location.href = 'themed-pages.php?applied=' + encodeURIComponent(bundleId);
        } else {
            alert(json.error || 'Failed to apply theme');
            cardEl.style.opacity = '';
            cardEl.style.pointerEvents = '';
        }
    } catch (e) {
        alert('Network error');
        cardEl.style.opacity = '';
        cardEl.style.pointerEvents = '';
    }
}
</script>

<?php include '_layout_end.php'; ?>
