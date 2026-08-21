<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/includes/partials.php';

$settings = site_settings();
$seo = seo_defaults([
    'title' => 'About Mechwize Group | HVAC & MEP Engineering UAE',
    'description' => 'Mechwize Group FZE LLC is a UAE-based engineering company specializing in HVAC, MEP, retrofit, precision cooling, technical services and procurement.',
    'canonical' => absolute_url('/about'),
    'image' => asset('assets/images/about-team.jpg'),
    'json_ld' => [organization_json_ld()],
]);

require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <?php render_breadcrumbs([
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'About', 'url' => '/about'],
        ]); ?>
        <p class="eyebrow">About Mechwize</p>
        <h1>Your trusted engineering partner for HVAC and MEP solutions.</h1>
        <p class="lead">Mechwize Group FZE LLC is a UAE-based engineering company specializing in advanced HVAC, MEP, retrofit and precision cooling solutions for commercial, industrial and mission-critical environments.</p>
    </div>
</section>
<section class="section compact">
    <div class="container split-media">
        <div class="media-frame reveal">
            <img src="<?= e(asset('assets/images/about-team.jpg')); ?>" alt="Mechwize engineering team at work" loading="lazy" width="900" height="700">
        </div>
        <div class="split-copy reveal">
            <h2>Driven by detail. Powered by purpose.</h2>
            <p>We deliver end-to-end services including equipment supply, installation, maintenance, technical procurement and engineering support with a strong focus on reliability, energy efficiency and technical excellence.</p>
            <p>Our approach is simple: not every project needs a bigger system — it needs the right solution for the right application.</p>
            <div class="feature-list" style="margin-top:1.5rem;">
                <div>
                    <strong>Vision</strong>
                    <span>To be a trusted sourcing and engineering partner for HVAC and MEP solutions across the GCC.</span>
                </div>
                <div>
                    <strong>Mission</strong>
                    <span>Deliver technically accurate, timely and value-driven HVAC design, service and procurement solutions.</span>
                </div>
                <div>
                    <strong>Coverage</strong>
                    <span><?= e((string) $settings['address']); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section muted-section">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow">Industries</p>
            <h2>Tailored engineering across sectors.</h2>
        </div>
        <div class="industry-list reveal">
            <span>Commercial &amp; residential buildings</span>
            <span>Retail &amp; hospitality</span>
            <span>Industrial facilities &amp; warehouses</span>
            <span>Healthcare &amp; institutions</span>
            <span>Data centers &amp; IT facilities</span>
        </div>
        <div class="stats-strip" style="margin-top:2.75rem;">
            <div class="stat-card reveal">
                <strong><span data-count="15">0</span>+</strong>
                <span>Years expertise</span>
            </div>
            <div class="stat-card reveal">
                <strong><span data-count="50">0</span>+</strong>
                <span>Systems serviced</span>
            </div>
            <div class="stat-card reveal">
                <strong><span data-count="5">0</span></strong>
                <span>Industries</span>
            </div>
            <div class="stat-card reveal">
                <strong>GCC</strong>
                <span>Regional coverage</span>
            </div>
        </div>
    </div>
</section>
<section class="section compact contact-section">
    <div class="container" style="text-align:center;">
        <p class="eyebrow">Work with us</p>
        <h2>Ready to discuss your next HVAC project?</h2>
        <div class="hero-actions" style="justify-content:center;">
            <a class="button primary" href="<?= e(url('/contact')); ?>">Get a quote</a>
            <a class="button secondary" href="<?= e(url('/projects')); ?>">View projects</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
