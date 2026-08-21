<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/includes/partials.php';

$settings = site_settings();
$seo = seo_defaults([
    'title' => 'About Mechwize Group | HVAC & MEP Engineering UAE',
    'description' => 'Mechwize Group FZE LLC is a UAE-based engineering company specializing in HVAC, MEP, retrofit, precision cooling, technical services and procurement.',
    'canonical' => absolute_url('/about'),
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
    <div class="container split-grid">
        <div class="reveal">
            <h2>Driven by detail. Powered by purpose.</h2>
            <p>We deliver end-to-end services including equipment supply, installation, maintenance, technical procurement and engineering support with a strong focus on reliability, energy efficiency and technical excellence.</p>
            <p>Our approach is simple: not every project needs a bigger system — it needs the right solution for the right application.</p>
        </div>
        <div class="feature-list reveal">
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
</section>
<section class="section muted-section">
    <div class="container">
        <div class="section-heading">
            <p class="eyebrow">Industries</p>
            <h2>Tailored engineering across sectors.</h2>
        </div>
        <div class="industry-list">
            <span>Commercial & residential buildings</span>
            <span>Retail & hospitality</span>
            <span>Industrial facilities & warehouses</span>
            <span>Healthcare & institutions</span>
            <span>Data centers & IT facilities</span>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
