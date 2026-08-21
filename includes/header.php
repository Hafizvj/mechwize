<?php

declare(strict_types=1);

$settings = site_settings();
$seo = seo_defaults($seo ?? []);
$bodyClass = $bodyClass ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php render_seo_head($seo); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('assets/css/styles.css')); ?>">
    <link rel="icon" href="<?= e(asset('assets/images/logo-hz.svg')); ?>">
</head>
<body class="<?= e($bodyClass); ?>">
<header class="site-header" data-header>
    <div class="container header-inner">
        <a class="brand" href="<?= e(url('/')); ?>" aria-label="Mechwize Group home">
            <img src="<?= e(asset('assets/images/logo-hz.svg')); ?>" alt="Mechwize Group — Innovate. Integrate. Elevate.">
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
