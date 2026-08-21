<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/includes/partials.php';

$settings = site_settings();
$flash = consume_flash();

$seo = seo_defaults([
    'title' => 'Contact Mechwize Group | HVAC Enquiry UAE',
    'description' => 'Contact Mechwize Group for HVAC design, technical services, retrofit and procurement support across Dubai, Abu Dhabi and Sharjah.',
    'canonical' => absolute_url('/contact'),
    'json_ld' => [organization_json_ld()],
]);

require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <?php render_breadcrumbs([
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Contact', 'url' => '/contact'],
        ]); ?>
        <p class="eyebrow">Get in touch</p>
        <h1>Discuss your HVAC project, service or procurement need.</h1>
    </div>
</section>
<section class="section compact">
    <div class="container contact-grid">
        <div class="reveal">
            <div class="contact-cards">
                <a href="<?= e(format_phone_href((string) $settings['phone_primary'])); ?>">
                    <strong>Phone</strong>
                    <span><?= e((string) $settings['phone_primary']); ?></span>
                </a>
                <a href="<?= e(format_phone_href((string) $settings['phone_secondary'])); ?>">
                    <strong>Alternate</strong>
                    <span><?= e((string) $settings['phone_secondary']); ?></span>
                </a>
                <a href="mailto:<?= e((string) $settings['email_primary']); ?>">
                    <strong>Email</strong>
                    <span><?= e((string) $settings['email_primary']); ?></span>
                </a>
                <a href="mailto:<?= e((string) $settings['email_sales']); ?>">
                    <strong>Sales</strong>
                    <span><?= e((string) $settings['email_sales']); ?></span>
                </a>
                <a href="<?= e(whatsapp_href((string) $settings['whatsapp'])); ?>" target="_blank" rel="noopener">
                    <strong>WhatsApp</strong>
                    <span><?= e((string) $settings['whatsapp']); ?></span>
                </a>
                <a href="<?= e((string) $settings['map_url']); ?>" target="_blank" rel="noopener">
                    <strong>Location</strong>
                    <span>Dubai | Abu Dhabi | Sharjah</span>
                </a>
            </div>
            <p class="address"><?= e((string) $settings['address']); ?></p>
            <p class="address"><?= e((string) $settings['working_hours']); ?></p>
        </div>
        <div class="reveal">
            <?php render_enquiry_form(is_array($flash) ? $flash : null); ?>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
