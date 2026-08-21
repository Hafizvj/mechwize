<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/includes/partials.php';

$settings = site_settings();
$services = get_services(true);
$projects = get_projects(true);
$clients = get_clients(true);

$seo = seo_defaults([
    'title' => $settings['default_meta_title'],
    'description' => $settings['default_meta_description'],
    'canonical' => absolute_url('/'),
    'image' => asset('assets/images/hero-hvac.jpg'),
    'json_ld' => [
        organization_json_ld(),
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Mechwize Group',
            'url' => absolute_url('/'),
        ],
    ],
]);

$serviceFallbacks = [
    'assets/images/service-chiller.jpg',
    'assets/images/service-precision.jpg',
    'assets/images/service-warehouse.jpg',
    'assets/images/service-rooftop.jpg',
    'assets/images/unit-turnkey.jpg',
    'assets/images/unit-services.jpg',
];

$projectFallbacks = [
    'assets/images/project-01.jpg',
    'assets/images/project-02.jpg',
    'assets/images/project-03.jpg',
];

require __DIR__ . '/includes/header.php';
?>

<section class="hero-cinematic">
    <div class="hero-media" aria-hidden="true">
        <img src="<?= e(asset('assets/images/hero-hvac.jpg')); ?>" alt="" width="1800" height="1000" fetchpriority="high">
    </div>
    <div class="hero-overlay" aria-hidden="true"></div>
    <div class="container">
        <div class="reveal">
            <img class="hero-brand-mark" src="<?= e(asset('assets/images/logo-mechwize.png')); ?>" alt="Mechwize Group" width="220" height="56">
            <p class="eyebrow">Dubai · HVAC · MEP · Cooling</p>
            <h1>Right HVAC Solutions. Right Application. Right Execution.</h1>
            <p class="hero-lede">Engineering-led design, turnkey delivery, technical services and procurement for commercial, industrial and critical cooling across the UAE and GCC.</p>
            <div class="hero-actions">
                <a class="button primary" href="<?= e(url('/contact')); ?>">Discuss Your Requirement</a>
                <a class="button secondary" href="<?= e(format_phone_href((string) $settings['phone_primary'])); ?>">Call <?= e((string) $settings['phone_primary']); ?></a>
            </div>
        </div>
        <div class="unit-tiles">
            <a class="unit-tile reveal" href="<?= e(url('/services')); ?>">
                <img src="<?= e(asset('assets/images/unit-turnkey.jpg')); ?>" alt="" loading="lazy" width="176" height="144">
                <div>
                    <strong>Design &amp; Turnkey</strong>
                    <span>Design · Supply · Install · Commission</span>
                </div>
            </a>
            <a class="unit-tile reveal" href="<?= e(url('/services')); ?>">
                <img src="<?= e(asset('assets/images/unit-services.jpg')); ?>" alt="" loading="lazy" width="176" height="144">
                <div>
                    <strong>Technical Services</strong>
                    <span>Maintain · Repair · Retrofit · Upgrade</span>
                </div>
            </a>
            <a class="unit-tile reveal" href="<?= e(url('/services')); ?>">
                <img src="<?= e(asset('assets/images/unit-procurement.jpg')); ?>" alt="" loading="lazy" width="176" height="144">
                <div>
                    <strong>Procurement</strong>
                    <span>Source · Supply · Trade · Logistics</span>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="section" id="about-preview">
    <div class="container split-media">
        <div class="media-frame reveal">
            <img src="<?= e(asset('assets/images/about-team.jpg')); ?>" alt="Mechwize engineering and commissioning" loading="lazy" width="900" height="700">
        </div>
        <div class="split-copy reveal">
            <p class="eyebrow">About Mechwize</p>
            <h2>Engineering partners for demanding cooling applications.</h2>
            <p>Mechwize Group specializes in HVAC design, turnkey solutions, technical services, retrofit, procurement and trading — matched to the right application, not a one-size system.</p>
            <ul class="check-list">
                <li>Outdoor, warehouse and industrial cooling</li>
                <li>Server room and precision cooling</li>
                <li>Chiller, DX and chilled water services</li>
                <li>OEM sourcing, logistics and warranty support</li>
            </ul>
            <div class="hero-actions">
                <a class="button secondary" href="<?= e(url('/about')); ?>">Our story</a>
                <a class="button primary" href="<?= e(url('/services')); ?>">Explore services</a>
            </div>
        </div>
    </div>
</section>

<section class="section compact muted-section">
    <div class="container">
        <div class="stats-strip">
            <div class="stat-card reveal">
                <strong><span data-count="15">0</span>+</strong>
                <span>Years team expertise</span>
            </div>
            <div class="stat-card reveal">
                <strong><span data-count="50">0</span>+</strong>
                <span>Major systems serviced</span>
            </div>
            <div class="stat-card reveal">
                <strong><span data-count="5">0</span></strong>
                <span>Industries served</span>
            </div>
            <div class="stat-card reveal">
                <strong><span data-count="100">0</span>%</strong>
                <span>Completion focus</span>
            </div>
        </div>
    </div>
</section>

<section class="section dark-section" id="solutions">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow">What we do</p>
            <h2>Three business units. One engineering partner.</h2>
            <p>Design and turnkey delivery, technical services, and procurement — matched to the right application.</p>
        </div>
        <div class="card-grid three">
            <article class="service-card reveal">
                <span class="card-label">Design / Supply / Install</span>
                <h3>Design, Build &amp; Turnkey</h3>
                <p>Complete HVAC delivery from application engineering to commissioning and handover.</p>
                <ul>
                    <li>Outdoor cooling</li>
                    <li>Warehouse and factory cooling</li>
                    <li>Critical cooling</li>
                </ul>
            </article>
            <article class="service-card reveal">
                <span class="card-label">Maintain / Repair / Upgrade</span>
                <h3>HVAC Technical Services</h3>
                <p>Installation, maintenance, repair, refurbishment and retrofit for DX and chilled water systems.</p>
                <ul>
                    <li>Chiller specialist services</li>
                    <li>Airside equipment</li>
                    <li>EC fan retrofit</li>
                </ul>
            </article>
            <article class="service-card reveal">
                <span class="card-label">Source / Supply / Trade</span>
                <h3>Procurement &amp; Trading</h3>
                <p>Direct sourcing of HVAC equipment, spare parts and replacement components from trusted manufacturers.</p>
                <ul>
                    <li>AHU / FAHU / FCU</li>
                    <li>Chillers and DX units</li>
                    <li>Fans, motors and HRW</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow">Featured services</p>
            <h2>Specialized HVAC solutions for demanding applications.</h2>
            <p>Explore detailed service pages for turnkey delivery, technical specialty work and procurement support.</p>
        </div>
        <div class="card-grid three">
            <?php foreach (array_slice($services, 0, 6) as $index => $service): ?>
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
        <p style="margin-top:1.75rem;"><a class="button secondary" href="<?= e(url('/services')); ?>">View all services</a></p>
    </div>
</section>

<section class="section muted-section">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow">Projects</p>
            <h2>Selected HVAC delivery and technical upgrade work.</h2>
        </div>
        <div class="card-grid three">
            <?php foreach (array_values($projects) as $index => $project): ?>
                <?php
                $img = !empty($project['cover_image'])
                    ? (string) $project['cover_image']
                    : $projectFallbacks[$index % count($projectFallbacks)];
                $num = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                ?>
                <a class="project-card reveal" href="<?= e(url('/projects/' . $project['slug'])); ?>">
                    <div class="project-media">
                        <img src="<?= e(asset($img)); ?>" alt="<?= e($project['title']); ?>" loading="lazy" width="640" height="400">
                        <span class="project-number"><?= e($num); ?></span>
                    </div>
                    <div class="card-body">
                        <div class="meta-row">
                            <span><?= e((string) $project['category']); ?></span>
                            <span><?= e((string) $project['location']); ?></span>
                            <span><?= e((string) $project['year']); ?></span>
                        </div>
                        <h3><?= e($project['title']); ?></h3>
                        <p><?= e($project['summary']); ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <p style="margin-top:1.75rem;"><a class="button secondary" href="<?= e(url('/projects')); ?>">Browse projects gallery</a></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow">Clients</p>
            <h2>Trusted across commercial, industrial and critical facilities.</h2>
        </div>
        <div class="clients-grid">
            <?php foreach ($clients as $client): ?>
                <div class="client-card reveal">
                    <?php if (!empty($client['logo_path'])): ?>
                        <img src="<?= e(asset((string) $client['logo_path'])); ?>" alt="<?= e((string) ($client['logo_alt'] ?: $client['name'])); ?>" loading="lazy">
                    <?php endif; ?>
                    <strong><?= e($client['name']); ?></strong>
                    <span class="address"><?= e((string) $client['industry']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <p style="margin-top:1.75rem;"><a class="button secondary" href="<?= e(url('/clients')); ?>">View clients</a></p>
    </div>
</section>

<section class="section contact-section">
    <div class="container contact-grid">
        <div class="reveal">
            <p class="eyebrow">Next step</p>
            <h2>Tell us about your HVAC requirement.</h2>
            <p class="lead">Share your project, service or procurement need and the Mechwize team will respond with the next technical step.</p>
            <div class="contact-cards">
                <a href="<?= e(format_phone_href((string) $settings['phone_primary'])); ?>">
                    <strong>Phone</strong>
                    <span><?= e((string) $settings['phone_primary']); ?></span>
                </a>
                <a href="<?= e(whatsapp_href((string) $settings['whatsapp'])); ?>" target="_blank" rel="noopener">
                    <strong>WhatsApp</strong>
                    <span>Start a conversation</span>
                </a>
            </div>
        </div>
        <div class="reveal">
            <?php render_enquiry_form(); ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
