<?php
require_once dirname(__DIR__) . '/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (is_admin_logged_in()) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    $stored_user = setting('admin_username', 'admin');
    $stored_hash = setting('admin_password', '');

    if ($user === $stored_user && password_verify($pass, $stored_hash)) {
        $_SESSION[ADMIN_SESSION_KEY] = true;
        redirect('index.php');
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | FORGE CMS</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-logo">
            <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" width="44" height="44">
                <path d="M5 10 L5 30 L15 30 L15 20 L25 20 L25 30 L35 30 L35 10 L25 10 L25 15 L15 15 L15 10 Z" fill="#E63946"/>
                <rect x="18" y="5" width="4" height="30" fill="#FF6B35" opacity="0.8"/>
                <rect x="5" y="18" width="30" height="4" fill="#FF6B35" opacity="0.8"/>
            </svg>
            <h1>FORGE CMS</h1>
            <p>Sign in to manage your website</p>
        </div>
        <?php if ($error): ?>
        <div class="alert alert-danger"><?= h($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= h($_POST['username'] ?? '') ?>" autofocus required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">Sign In</button>
        </form>
    </div>
</div>
</body>
</html>
