<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/includes/partials.php';

$slug = trim((string) ($_GET['slug'] ?? ''));

if ($slug !== '') {
    $project = get_project_by_slug($slug);
    if (!$project) {
        http_response_code(404);
        require __DIR__ . '/404.php';
        exit;
    }

    $crumbs = [
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Projects', 'url' => '/projects'],
        ['name' => $project['title'], 'url' => '/projects/' . $project['slug']],
    ];

    $seo = seo_defaults([
        'title' => $project['seo_title'] ?: ($project['title'] . ' | Mechwize Projects'),
        'description' => $project['seo_description'] ?: $project['summary'],
        'keywords' => $project['seo_keywords'] ?? '',
        'image' => $project['cover_image'] ?: null,
        'canonical' => absolute_url('/projects/' . $project['slug']),
        'type' => 'article',
        'json_ld' => [breadcrumb_json_ld($crumbs)],
    ]);

    require __DIR__ . '/includes/header.php';
    ?>
    <section class="page-hero">
        <div class="container">
            <?php render_breadcrumbs($crumbs); ?>
            <p class="eyebrow"><?= e((string) $project['category']); ?></p>
            <h1><?= e($project['title']); ?></h1>
            <div class="meta-row">
                <span><?= e((string) $project['location']); ?></span>
                <span><?= e((string) $project['year']); ?></span>
            </div>
            <p class="lead"><?= e($project['summary']); ?></p>
        </div>
    </section>
    <section class="section compact">
        <div class="container split-grid">
            <div class="reveal">
                <div class="project-media" style="margin-bottom:1.5rem;">
                    <?php if (!empty($project['cover_image'])): ?>
                        <img src="<?= e(asset((string) $project['cover_image'])); ?>" alt="<?= e($project['title']); ?>">
                    <?php endif; ?>
                </div>
                <?= nl2p((string) $project['body']); ?>
            </div>
            <div class="reveal">
                <div class="card-grid two">
                    <?php foreach (($project['images'] ?? []) as $image): ?>
                        <div class="project-media">
                            <img src="<?= e(asset((string) $image['image_path'])); ?>" alt="<?= e((string) ($image['alt_text'] ?: $project['title'])); ?>" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
                <p style="margin-top:1.5rem;"><a class="button primary" href="<?= e(url('/contact')); ?>">Discuss a similar project</a></p>
            </div>
        </div>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

$category = trim((string) ($_GET['category'] ?? ''));
$projects = get_projects(false, $category !== '' ? $category : null);
$categories = get_project_categories();

$seo = seo_defaults([
    'title' => 'HVAC Projects Gallery UAE | Mechwize Group',
    'description' => 'Browse Mechwize HVAC projects across outdoor cooling, critical cooling, industrial facilities and energy retrofit upgrades.',
    'canonical' => absolute_url('/projects'),
]);

require __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container">
        <?php render_breadcrumbs([
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Projects', 'url' => '/projects'],
        ]); ?>
        <p class="eyebrow">Projects gallery</p>
        <h1>Engineering delivery across cooling, retrofit and critical environments.</h1>
        <div class="filter-row" style="margin-top:1.5rem;">
            <a class="<?= $category === '' ? 'is-active' : ''; ?>" href="<?= e(url('/projects')); ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a class="<?= strcasecmp($category, $cat) === 0 ? 'is-active' : ''; ?>" href="<?= e(url('/projects?category=' . urlencode($cat))); ?>"><?= e($cat); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section compact">
    <div class="container card-grid three">
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
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
