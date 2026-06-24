<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$db = get_db();
$UPLOADS_DIR = dirname(__DIR__) . '/uploads';
$UPLOADS_URL = '../uploads/';
$MAX_SIZE    = 10 * 1024 * 1024; // 10MB
$ALLOWED     = ['image/jpeg','image/png','image/gif','image/webp','image/svg+xml'];
$ALLOWED_EXT = ['jpg','jpeg','png','gif','webp','svg'];

$upload_error   = '';
$upload_success = '';

// Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $db->prepare('SELECT filename FROM media WHERE id = ?');
    $stmt->execute([(int)$_GET['delete']]);
    $row = $stmt->fetch();
    if ($row) {
        $path = $UPLOADS_DIR . '/' . $row['filename'];
        if (file_exists($path)) unlink($path);
        $db->prepare('DELETE FROM media WHERE id = ?')->execute([(int)$_GET['delete']]);
    }

    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    redirect('media.php?deleted=1');
}

// Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $upload_error = 'Upload failed (error code ' . $file['error'] . ')';
    } elseif ($file['size'] > $MAX_SIZE) {
        $upload_error = 'File exceeds 10MB limit.';
    } elseif (!in_array($file['type'], $ALLOWED)) {
        $upload_error = 'Invalid file type. Allowed: JPG, PNG, GIF, WEBP, SVG.';
    } else {
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $ALLOWED_EXT)) {
            $upload_error = 'Invalid file extension.';
        } else {
            $filename = uniqid('media_', true) . '.' . $ext;
            $dest     = $UPLOADS_DIR . '/' . $filename;
            if (!is_dir($UPLOADS_DIR)) mkdir($UPLOADS_DIR, 0755, true);

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $stmt = $db->prepare('INSERT INTO media (filename, original_name, mime_type, file_size) VALUES (?,?,?,?)');
                $stmt->execute([$filename, basename($file['name']), $file['type'], $file['size']]);
                $upload_success = 'Uploaded successfully.';
            } else {
                $upload_error = 'Failed to move uploaded file. Check folder permissions.';
            }
        }
    }

    if (!empty($_SERVER['HTTP_X_AJAX'])) {
        header('Content-Type: application/json');
        if ($upload_error) echo json_encode(['success' => false, 'error' => $upload_error]);
        else echo json_encode(['success' => true, 'url' => $UPLOADS_URL . $filename, 'filename' => $filename]);
        exit;
    }
}

$media = $db->query('SELECT * FROM media ORDER BY uploaded_at DESC')->fetchAll();
$total_media = count($media);

$page_title  = 'Media Library';
$active_nav  = 'media';
$show_preview = false;
include '_layout.php';
?>

<?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">File deleted.</div><?php endif; ?>
<?php if ($upload_error): ?><div class="alert alert-danger"><?= h($upload_error) ?></div><?php endif; ?>
<?php if ($upload_success): ?><div class="alert alert-success"><?= h($upload_success) ?></div><?php endif; ?>

<div class="card" style="margin-bottom:20px">
    <div class="card-header"><h2>Upload File</h2></div>
    <div class="card-body">
        <form id="upload-form" method="POST" enctype="multipart/form-data">
            <div style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
                <div class="form-group" style="margin:0">
                    <label>Select Image (JPG, PNG, GIF, WEBP, SVG — max 10MB)</label>
                    <input type="file" name="file" id="file-input" accept="image/*" required style="background:var(--surface2);border:1px solid var(--border);color:var(--text);padding:7px 11px;border-radius:5px;font-size:.85rem;cursor:pointer">
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
        <div id="upload-progress" style="display:none;margin-top:10px">
            <div style="height:3px;background:var(--border);border-radius:2px;overflow:hidden">
                <div id="progress-bar" style="height:100%;background:var(--accent);width:0;transition:width .3s"></div>
            </div>
        </div>
        <div id="upload-status" style="margin-top:8px;font-size:.82rem"></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Files (<?= $total_media ?>)</h2>
    </div>
    <div class="card-body">
        <?php if (empty($media)): ?>
        <div style="text-align:center;color:var(--text-muted);padding:24px">No files uploaded yet.</div>
        <?php else: ?>
        <div id="media-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px">
            <?php foreach ($media as $m):
                $url  = $UPLOADS_URL . h($m['filename']);
                $size = $m['file_size'] < 1024*1024 ? round($m['file_size']/1024) . ' KB' : round($m['file_size']/(1024*1024),1) . ' MB';
            ?>
            <div class="media-item" data-id="<?= $m['id'] ?>" data-url="uploads/<?= h($m['filename']) ?>" style="background:var(--surface2);border:1px solid var(--border);border-radius:7px;overflow:hidden">
                <div style="height:110px;display:flex;align-items:center;justify-content:center;background:#0d0d0d;overflow:hidden">
                    <img src="<?= $url ?>" alt="<?= h($m['original_name']) ?>" style="max-width:100%;max-height:110px;object-fit:contain">
                </div>
                <div style="padding:8px 10px">
                    <div style="font-size:.75rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:2px" title="<?= h($m['original_name']) ?>"><?= h($m['original_name']) ?></div>
                    <div style="font-size:.68rem;color:var(--text-muted);margin-bottom:8px"><?= $size ?></div>
                    <div style="display:flex;gap:6px">
                        <button class="btn btn-secondary btn-sm" onclick="copyUrl('<?= h('uploads/' . $m['filename']) ?>')" style="flex:1;justify-content:center">Copy URL</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteMedia(<?= $m['id'] ?>, this)">Del</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// AJAX upload with progress
document.getElementById('upload-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const file = document.getElementById('file-input').files[0];
    if (!file) return;

    const fd = new FormData();
    fd.append('file', file);

    const prog  = document.getElementById('upload-progress');
    const bar   = document.getElementById('progress-bar');
    const stat  = document.getElementById('upload-status');
    prog.style.display = 'block';
    stat.textContent = 'Uploading...';
    stat.style.color = '';

    const xhr = new XMLHttpRequest();
    xhr.upload.addEventListener('progress', e => {
        if (e.lengthComputable) bar.style.width = (e.loaded / e.total * 100) + '%';
    });
    xhr.addEventListener('load', () => {
        try {
            const json = JSON.parse(xhr.responseText);
            if (json.success) {
                stat.textContent = 'Upload successful!';
                stat.style.color = 'var(--success)';
                bar.style.width = '100%';
                addMediaItem(json.url, file.name, file.size);
                document.getElementById('file-input').value = '';
                setTimeout(() => { prog.style.display='none'; bar.style.width='0'; }, 1500);
            } else {
                stat.textContent = json.error || 'Upload failed';
                stat.style.color = 'var(--accent)';
            }
        } catch(err) {
            stat.textContent = 'Unexpected response';
            stat.style.color = 'var(--accent)';
        }
    });
    xhr.open('POST', 'media.php');
    xhr.setRequestHeader('X-Ajax', '1');
    xhr.send(fd);
});

function addMediaItem(url, name, size) {
    const grid = document.getElementById('media-grid');
    if (!grid) { location.reload(); return; }
    const sizeStr = size < 1048576 ? Math.round(size/1024) + ' KB' : (size/(1024*1024)).toFixed(1) + ' MB';
    const div = document.createElement('div');
    div.className = 'media-item';
    div.style.cssText = 'background:var(--surface2);border:1px solid var(--border);border-radius:7px;overflow:hidden';
    div.innerHTML = `
        <div style="height:110px;display:flex;align-items:center;justify-content:center;background:#0d0d0d;overflow:hidden">
            <img src="../${url}" style="max-width:100%;max-height:110px;object-fit:contain">
        </div>
        <div style="padding:8px 10px">
            <div style="font-size:.75rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:2px">${name}</div>
            <div style="font-size:.68rem;color:var(--text-muted);margin-bottom:8px">${sizeStr}</div>
            <div style="display:flex;gap:6px">
                <button class="btn btn-secondary btn-sm" onclick="copyUrl('${url}')" style="flex:1;justify-content:center">Copy URL</button>
                <button class="btn btn-danger btn-sm" disabled>Del</button>
            </div>
        </div>`;
    grid.prepend(div);
}

function copyUrl(url) {
    navigator.clipboard.writeText(window.location.origin + '/' + url).then(() => {
        const orig = event.target.textContent;
        event.target.textContent = 'Copied!';
        setTimeout(() => event.target.textContent = orig, 1500);
    }).catch(() => {
        prompt('Copy this URL:', window.location.origin + '/' + url);
    });
}

function deleteMedia(id, btn) {
    if (!confirm('Delete this file?')) return;
    fetch('media.php?delete=' + id, { headers: {'X-Ajax':'1'} })
        .then(r => r.json())
        .then(json => {
            if (json.success) btn.closest('.media-item').remove();
        });
}
</script>

<?php include '_layout_end.php'; ?>
