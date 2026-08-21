<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';
require __DIR__ . '/_init.php';

if (auth_user()) {
    redirect(url('/admin/'));
}

$error = null;

if (is_post()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid session. Please try again.';
    } else {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        try {
            if (attempt_login($email, $password)) {
                redirect(url('/admin/'));
            }
            $error = 'Invalid email or password.';
        } catch (Throwable $exception) {
            error_log('Admin login failed: ' . $exception->getMessage());
            $error = 'Remote MySQL is not configured or unreachable. Configure Hostmaria credentials first.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Admin Login | Mechwize Group</title>
    <link rel="stylesheet" href="<?= e(asset('assets/css/styles.css')); ?>">
</head>
<body class="admin-body">
<div class="login-wrap">
    <form class="contact-form login-card" method="post">
        <img src="<?= e(asset('assets/images/logo-hz.svg')); ?>" alt="Mechwize Group" style="height:42px;margin-bottom:0.5rem;">
        <h1 style="font-size:2rem;">Admin Login</h1>
        <p class="form-note">Manage services, projects, clients and contact details.</p>
        <?php if ($error): ?>
            <div class="flash error"><?= e($error); ?></div>
        <?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <div class="form-row">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required value="admin@mechwize.com">
        </div>
        <div class="form-row">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>
        <button class="button primary full" type="submit">Sign in</button>
    </form>
</div>
</body>
</html>
