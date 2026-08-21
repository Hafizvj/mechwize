<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/includes/partials.php';

$clients = get_clients();
$seo = seo_defaults([
    'title' => 'Clients | Mechwize Group HVAC UAE',
    'description' => 'Mechwize Group supports commercial, industrial, hospitality and critical infrastructure clients across the UAE.',
    'canonical' => absolute_url('/clients'),
]);

require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <?php render_breadcrumbs([
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Clients', 'url' => '/clients'],
        ]); ?>
        <p class="eyebrow">Clients</p>
        <h1>Trusted by facilities that need reliable HVAC performance.</h1>
        <p class="lead">Update logos and featured clients anytime from the admin panel.</p>
    </div>
</section>
<section class="section compact">
    <div class="container clients-grid">
        <?php foreach ($clients as $client): ?>
            <div class="client-card reveal">
                <?php if (!empty($client['logo_path'])): ?>
                    <img src="<?= e(asset((string) $client['logo_path'])); ?>" alt="<?= e((string) ($client['logo_alt'] ?: $client['name'])); ?>" loading="lazy">
                <?php endif; ?>
                <strong><?= e($client['name']); ?></strong>
                <span class="address"><?= e((string) $client['industry']); ?></span>
                <?php if (!empty($client['website'])): ?>
                    <a href="<?= e((string) $client['website']); ?>" target="_blank" rel="noopener">Visit website</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<section class="section compact contact-section">
    <div class="container" style="text-align:center;">
        <p class="eyebrow">Partner with Mechwize</p>
        <h2>Need HVAC support for your facility?</h2>
        <div class="hero-actions" style="justify-content:center;">
            <a class="button primary" href="<?= e(url('/contact')); ?>">Request a quote</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
