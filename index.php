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

require __DIR__ . '/includes/header.php';
?>

<section class="hero section">
    <div class="container hero-grid">
        <div class="hero-copy reveal">
            <p class="eyebrow">Dubai-based HVAC · MEP · Cooling</p>
            <h1>Right HVAC Solutions. Right Application. Right Execution.</h1>
            <p class="hero-lede">Mechwize Group specializes in HVAC design, turnkey solutions, technical services, retrofit, procurement and trading for commercial, industrial, critical cooling and infrastructure applications.</p>
            <div class="hero-actions">
                <a class="button primary" href="<?= e(url('/contact')); ?>">Discuss Your Requirement</a>
                <a class="button secondary" href="<?= e(format_phone_href((string) $settings['phone_primary'])); ?>">Call <?= e((string) $settings['phone_primary']); ?></a>
            </div>
            <div class="trust-row" aria-label="Company highlights">
                <span><strong>15+</strong> years team expertise</span>
                <span><strong>50+</strong> major systems serviced</span>
                <span><strong>5</strong> industries served</span>
            </div>
        </div>
        <div class="hero-panel reveal" aria-label="Mechwize service summary">
            <div class="panel-card">
                <span class="status-dot"></span>
                <p>Engineering-led HVAC support from design to post-project maintenance across the UAE and GCC.</p>
            </div>
            <div class="hero-metric">
                <strong>100%</strong>
                <span>Project completion focus with testing, commissioning and handover.</span>
            </div>
            <ul class="check-list">
                <li>Outdoor, warehouse and industrial cooling</li>
                <li>Server room and precision cooling</li>
                <li>Chiller, DX and chilled water services</li>
                <li>OEM sourcing, logistics and warranty support</li>
            </ul>
        </div>
    </div>
</section>

<section class="section compact" id="solutions">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow">What we do</p>
            <h2>Three business units. One engineering partner.</h2>
            <p>Design and turnkey delivery, technical services, and procurement — matched to the right application.</p>
        </div>
        <div class="card-grid three">
            <article class="service-card reveal">
                <span class="card-label">Design / Supply / Install</span>
                <h3>Design, Build & Turnkey</h3>
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
                <h3>Procurement & Trading</h3>
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

<section class="section dark-section">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow">Featured services</p>
            <h2>Specialized HVAC solutions for demanding applications.</h2>
            <p>Explore detailed service pages for turnkey delivery, technical specialty work and procurement support.</p>
        </div>
        <div class="card-grid three">
            <?php foreach (array_slice($services, 0, 6) as $service): ?>
                <a class="service-card reveal" href="<?= e(url('/services/' . $service['slug'])); ?>">
                    <span class="card-label"><?= e((string) $service['category']); ?></span>
                    <h3><?= e($service['title']); ?></h3>
                    <p><?= e($service['summary']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
        <p style="margin-top:1.75rem;"><a class="button secondary" href="<?= e(url('/services')); ?>">View all services</a></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow">Projects</p>
            <h2>Selected HVAC delivery and technical upgrade work.</h2>
        </div>
        <div class="card-grid three">
            <?php foreach ($projects as $project): ?>
                <a class="project-card reveal" href="<?= e(url('/projects/' . $project['slug'])); ?>">
                    <div class="project-media">
                        <?php if (!empty($project['cover_image'])): ?>
                            <img src="<?= e(asset((string) $project['cover_image'])); ?>" alt="<?= e($project['title']); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                    <div class="meta-row">
                        <span><?= e((string) $project['category']); ?></span>
                        <span><?= e((string) $project['location']); ?></span>
                        <span><?= e((string) $project['year']); ?></span>
                    </div>
                    <h3><?= e($project['title']); ?></h3>
                    <p><?= e($project['summary']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
        <p style="margin-top:1.75rem;"><a class="button secondary" href="<?= e(url('/projects')); ?>">Browse projects gallery</a></p>
    </div>
</section>

<section class="section muted-section">
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
