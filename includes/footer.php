<?php

declare(strict_types=1);

$settings = site_settings();
$footerServices = array_slice(get_services(true), 0, 6);
$logoSrc = asset('assets/images/logo-mechwize.png');
?>
</main>
<footer class="site-footer">
    <div class="footer-glow" aria-hidden="true"></div>
    <div class="container footer-grid">
        <div class="footer-brand">
            <img src="<?= e($logoSrc); ?>" alt="Mechwize Group" width="200" height="52">
            <p class="footer-tagline"><?= e($settings['tagline'] ?? 'Right HVAC Solutions. Right Application. Right Execution.'); ?></p>
            <p class="footer-motto">Innovate. Integrate. Elevate.</p>
            <p class="address"><?= e($settings['address'] ?? ''); ?></p>
        </div>
        <div>
            <h3 class="footer-heading">Services</h3>
            <div class="footer-links">
                <?php foreach ($footerServices as $service): ?>
                    <a href="<?= e(url('/services/' . $service['slug'])); ?>"><?= e($service['title']); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div>
            <h3 class="footer-heading">Explore</h3>
            <div class="footer-links">
                <a href="<?= e(url('/about')); ?>">About</a>
                <a href="<?= e(url('/projects')); ?>">Projects</a>
                <a href="<?= e(url('/clients')); ?>">Clients</a>
                <a href="<?= e(url('/contact')); ?>">Contact</a>
            </div>
        </div>
        <div>
            <h3 class="footer-heading">Get in touch</h3>
            <div class="footer-links">
                <a href="<?= e(format_phone_href((string) ($settings['phone_primary'] ?? ''))); ?>"><?= e((string) ($settings['phone_primary'] ?? '')); ?></a>
                <a href="mailto:<?= e((string) ($settings['email_primary'] ?? '')); ?>"><?= e((string) ($settings['email_primary'] ?? '')); ?></a>
                <a href="<?= e(whatsapp_href((string) ($settings['whatsapp'] ?? ''))); ?>" target="_blank" rel="noopener">WhatsApp</a>
            </div>
            <p class="address" style="margin-top:1rem;"><?= e((string) ($settings['working_hours'] ?? '')); ?></p>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>&copy; <?= date('Y'); ?> Mechwize Group. All rights reserved.</p>
        <p>Design. Build. Service. Source.</p>
    </div>
</footer>
<script src="<?= e(asset('assets/js/main.js')); ?>" defer></script>
</body>
</html>
