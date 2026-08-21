<?php

declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/includes/partials.php';

http_response_code(404);

$seo = seo_defaults([
    'title' => 'Page Not Found | Mechwize Group',
    'description' => 'The requested Mechwize Group page could not be found.',
    'robots' => 'noindex,follow',
    'canonical' => absolute_url('/404'),
]);

require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">404</p>
        <h1>This page is not available.</h1>
        <p class="lead">The link may be outdated, or the page may have moved. Explore our services, projects or contact the Mechwize team.</p>
        <div class="hero-actions">
            <a class="button primary" href="<?= e(url('/')); ?>">Back to home</a>
            <a class="button secondary" href="<?= e(url('/services')); ?>">Browse services</a>
            <a class="button secondary" href="<?= e(url('/contact')); ?>">Contact us</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
