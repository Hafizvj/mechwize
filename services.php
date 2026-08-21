<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/includes/partials.php';

$slug = trim((string) ($_GET['slug'] ?? ''));

$serviceFallbacks = [
    'assets/images/service-chiller.jpg',
    'assets/images/service-precision.jpg',
    'assets/images/service-warehouse.jpg',
    'assets/images/service-rooftop.jpg',
    'assets/images/unit-turnkey.jpg',
    'assets/images/unit-services.jpg',
];

if ($slug !== '') {
    $service = get_service_by_slug($slug);
    if (!$service) {
        http_response_code(404);
        require __DIR__ . '/404.php';
        exit;
    }

    $crumbs = [
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Services', 'url' => '/services'],
        ['name' => $service['title'], 'url' => '/services/' . $service['slug']],
    ];

    $detailImage = $service['hero_image'] ?: $serviceFallbacks[0];

    $seo = seo_defaults([
        'title' => $service['seo_title'] ?: ($service['title'] . ' | Mechwize Group'),
        'description' => $service['seo_description'] ?: $service['summary'],
        'keywords' => $service['seo_keywords'] ?? '',
        'image' => $service['og_image'] ?: ($service['hero_image'] ?: $detailImage),
        'canonical' => absolute_url('/services/' . $service['slug']),
        'type' => 'article',
        'json_ld' => [
            breadcrumb_json_ld($crumbs),
            service_json_ld($service),
        ],
    ]);

    $related = array_values(array_filter(
        get_services(),
        static fn ($item) => $item['slug'] !== $service['slug']
    ));
    $related = array_slice($related, 0, 3);

    require __DIR__ . '/includes/header.php';
    ?>
    <section class="page-hero">
        <div class="container">
            <?php render_breadcrumbs($crumbs); ?>
            <p class="eyebrow"><?= e((string) $service['category']); ?></p>
            <h1><?= e($service['title']); ?></h1>
            <p class="lead"><?= e($service['summary']); ?></p>
        </div>
    </section>
    <section class="section compact">
        <div class="container split-media">
            <div class="media-frame reveal">
                <img src="<?= e(asset((string) $detailImage)); ?>" alt="<?= e($service['title']); ?>" loading="lazy" width="900" height="600">
            </div>
            <div class="reveal">
                <?= nl2p((string) $service['body']); ?>
                <div class="feature-list" style="margin-top:1.5rem;">
                    <?php foreach (($service['features'] ?? []) as $feature): ?>
                        <div>
                            <strong>Capability</strong>
                            <span><?= e((string) $feature); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p style="margin-top:1.5rem;"><a class="button primary" href="<?= e(url('/contact')); ?>">Request this service</a></p>
            </div>
        </div>
    </section>
    <?php if ($related): ?>
    <section class="section muted-section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow">Related</p>
                <h2>More HVAC capabilities</h2>
            </div>
            <div class="card-grid three">
                <?php foreach ($related as $ri => $item): ?>
                    <?php $img = !empty($item['hero_image']) ? (string) $item['hero_image'] : $serviceFallbacks[($ri + 1) % count($serviceFallbacks)]; ?>
                    <a class="service-card has-media reveal" href="<?= e(url('/services/' . $item['slug'])); ?>">
                        <div class="card-media">
                            <img src="<?= e(asset($img)); ?>" alt="" loading="lazy" width="640" height="360">
                        </div>
                        <div class="card-body">
                            <span class="card-label"><?= e((string) $item['category']); ?></span>
                            <h3><?= e($item['title']); ?></h3>
                            <p><?= e($item['summary']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$services = get_services();
$seo = seo_defaults([
    'title' => 'HVAC Services Dubai & UAE | Mechwize Group',
    'description' => 'Explore Mechwize HVAC services including turnkey cooling, chiller services, retrofit, critical cooling and procurement across the UAE.',
    'canonical' => absolute_url('/services'),
    'image' => asset('assets/images/service-chiller.jpg'),
]);

require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <?php render_breadcrumbs([
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Services', 'url' => '/services'],
        ]); ?>
        <p class="eyebrow">Detailed service pages</p>
        <h1>Specialized HVAC services from design to maintenance.</h1>
        <p class="lead">Application-based cooling, technical specialty services, retrofit upgrades and procurement support.</p>
    </div>
</section>
<section class="section compact">
    <div class="container card-grid three">
        <?php foreach (array_values($services) as $index => $service): ?>
            <?php
            $img = !empty($service['hero_image'])
                ? (string) $service['hero_image']
                : $serviceFallbacks[$index % count($serviceFallbacks)];
            ?>
            <a class="service-card has-media reveal" href="<?= e(url('/services/' . $service['slug'])); ?>">
                <div class="card-media">
                    <img src="<?= e(asset($img)); ?>" alt="" loading="lazy" width="640" height="360">
                </div>
                <div class="card-body">
                    <span class="card-label"><?= e((string) $service['category']); ?></span>
                    <h3><?= e($service['title']); ?></h3>
                    <p><?= e($service['summary']); ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
