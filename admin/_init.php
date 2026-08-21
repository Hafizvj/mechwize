<?php

declare(strict_types=1);

function admin_nav_items(): array
{
    return [
        'index.php' => 'Dashboard',
        'services.php' => 'Services',
        'projects.php' => 'Projects',
        'clients.php' => 'Clients',
        'settings.php' => 'Contact & SEO Settings',
        'enquiries.php' => 'Enquiries',
    ];
}

function admin_header(string $title): void
{
    $user = auth_user();
    $current = basename((string) ($_SERVER['SCRIPT_NAME'] ?? 'index.php'));
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= e($title); ?> | Mechwize Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('assets/css/styles.css')); ?>">
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a href="<?= e(url('/admin/')); ?>" style="display:block;margin-bottom:1.25rem;">
            <img src="<?= e(asset('assets/images/logo-hz.svg')); ?>" alt="Mechwize" style="height:36px;">
        </a>
        <?php foreach (admin_nav_items() as $file => $label): ?>
            <a class="<?= $current === $file ? 'is-active' : ''; ?>" href="<?= e(url('/admin/' . $file)); ?>"><?= e($label); ?></a>
        <?php endforeach; ?>
        <a href="<?= e(url('/')); ?>" target="_blank" rel="noopener">View website</a>
        <a href="<?= e(url('/admin/logout.php')); ?>">Logout</a>
    </aside>
    <div class="admin-main">
        <div class="admin-top">
            <div>
                <p class="eyebrow" style="margin-bottom:0.35rem;">Admin</p>
                <h1 style="font-size:2rem;margin:0;"><?= e($title); ?></h1>
            </div>
            <div class="address">Signed in as <?= e((string) ($user['email'] ?? '')); ?></div>
        </div>
        <?= render_flash(consume_flash()); ?>
    <?php
}

function admin_footer(): void
{
    ?>
    </div>
</div>
<script src="<?= e(asset('assets/js/main.js')); ?>" defer></script>
</body>
</html>
    <?php
}

function require_post_csrf(): void
{
    if (!is_post() || !verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Invalid request. Please try again.');
        redirect(url('/admin/'));
    }
}
