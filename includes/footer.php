<?php

declare(strict_types=1);

$settings = site_settings();
$footerServices = array_slice(get_services(true), 0, 6);
?>
</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <img src="<?= e(asset('assets/images/logo-hz.svg')); ?>" alt="Mechwize Group" style="height:42px;margin-bottom:1rem;">
            <p><?= e($settings['tagline'] ?? 'Right HVAC Solutions. Right Application. Right Execution.'); ?></p>
            <p class="address"><?= e($settings['address'] ?? ''); ?></p>
            <p class="address">
                <a href="<?= e(format_phone_href((string) ($settings['phone_primary'] ?? ''))); ?>"><?= e((string) ($settings['phone_primary'] ?? '')); ?></a>
                ·
                <a href="mailto:<?= e((string) ($settings['email_primary'] ?? '')); ?>"><?= e((string) ($settings['email_primary'] ?? '')); ?></a>
            </p>
        </div>
        <div>
            <h3 style="font-size:1.2rem;margin-bottom:0.85rem;">Services</h3>
            <div class="footer-links">
                <?php foreach ($footerServices as $service): ?>
                    <a href="<?= e(url('/services/' . $service['slug'])); ?>"><?= e($service['title']); ?></a>
                <?php endforeach; ?>
                <a href="<?= e(url('/projects')); ?>">Projects Gallery</a>
                <a href="<?= e(url('/contact')); ?>">Contact</a>
            </div>
            <p class="address" style="margin-top:1.25rem;">Design. Build. Service. Source.<br>Innovate. Integrate. Elevate.</p>
        </div>
    </div>
</footer>
<script src="<?= e(asset('assets/js/main.js')); ?>" defer></script>
</body>
</html>
