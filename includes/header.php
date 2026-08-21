<?php

declare(strict_types=1);

$settings = site_settings();
$seo = seo_defaults($seo ?? []);
$bodyClass = $bodyClass ?? '';
$logoSrc = asset('assets/images/logo-mechwize.png');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php render_seo_head($seo); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('assets/css/styles.css')); ?>">
    <link rel="icon" href="<?= e($logoSrc); ?>" type="image/png">
</head>
<body class="<?= e($bodyClass); ?>">
<div class="topbar">
    <div class="container topbar-inner">
        <div class="topbar-left">
            <a href="mailto:<?= e((string) ($settings['email_primary'] ?? '')); ?>"><?= e((string) ($settings['email_primary'] ?? '')); ?></a>
            <span class="topbar-sep" aria-hidden="true"></span>
            <a href="<?= e(format_phone_href((string) ($settings['phone_primary'] ?? ''))); ?>"><?= e((string) ($settings['phone_primary'] ?? '')); ?></a>
        </div>
        <div class="topbar-right">
            <span><?= e((string) ($settings['working_hours'] ?? 'Mon – Sat · UAE')); ?></span>
            <a class="topbar-whatsapp" href="<?= e(whatsapp_href((string) ($settings['whatsapp'] ?? ''))); ?>" target="_blank" rel="noopener">WhatsApp</a>
        </div>
    </div>
</div>
<header class="site-header" data-header>
    <div class="container header-inner">
        <a class="brand" href="<?= e(url('/')); ?>" aria-label="Mechwize Group home">
            <img src="<?= e($logoSrc); ?>" alt="Mechwize Group — Innovate. Integrate. Elevate." width="220" height="56">
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
            <span></span>
            <span></span>
            <span></span>
            <span class="sr-only">Menu</span>
        </button>
        <nav class="site-nav" id="site-nav" data-nav>
            <a class="<?= e(active_nav('/services')); ?>" href="<?= e(url('/services')); ?>">Services</a>
            <a class="<?= e(active_nav('/projects')); ?>" href="<?= e(url('/projects')); ?>">Projects</a>
            <a class="<?= e(active_nav('/clients')); ?>" href="<?= e(url('/clients')); ?>">Clients</a>
            <a class="<?= e(active_nav('/about')); ?>" href="<?= e(url('/about')); ?>">About</a>
            <a class="nav-cta <?= e(active_nav('/contact')); ?>" href="<?= e(url('/contact')); ?>">Get a Quote</a>
        </nav>
    </div>
</header>
<main>
