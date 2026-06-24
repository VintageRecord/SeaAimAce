<?php
require_once dirname(__DIR__) . '/config.php';
require_admin_login();

$saved = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current  = $_POST['current_password']  ?? '';
    $new1     = $_POST['new_password']      ?? '';
    $new2     = $_POST['confirm_password']  ?? '';
    $username = trim($_POST['username']     ?? '');

    $stored_hash = setting('admin_password', '');
    if (!password_verify($current, $stored_hash)) {
        $error = 'Current password is incorrect.';
    } elseif ($new1 !== '' && strlen($new1) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($new1 !== $new2) {
        $error = 'New passwords do not match.';
    } else {
        if ($username !== '') save_setting('admin_username', $username);
        if ($new1 !== '') save_setting('admin_password', password_hash($new1, PASSWORD_DEFAULT));
        $saved = true;
    }
}

$page_title = 'Change Password';
$active_nav = 'account';
include '_layout.php';
?>

<?php if ($saved): ?>
<div class="alert alert-success">✅ Account updated successfully.</div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-danger"><?= h($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width:480px">
    <div class="card-header"><h2>Update Credentials</h2></div>
    <div class="card-body">
        <form method="POST">
            <div class="form-group">
                <label>New Username (leave blank to keep current)</label>
                <input type="text" name="username" placeholder="<?= h(setting('admin_username', 'admin')) ?>">
            </div>
            <div class="form-group">
                <label>Current Password <span style="color:var(--accent)">*</span></label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>New Password (leave blank to keep current)</label>
                <input type="password" name="new_password" minlength="8">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password">
            </div>
            <button type="submit" class="btn btn-primary">💾 Update Account</button>
        </form>
    </div>
</div>

<?php include '_layout_end.php'; ?>
