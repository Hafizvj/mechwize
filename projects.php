<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require __DIR__ . '/includes/partials.php';

$slug = trim((string) ($_GET['slug'] ?? ''));

$projectFallbacks = [
    'assets/images/project-01.jpg',
    'assets/images/project-02.jpg',
    'assets/images/project-03.jpg',
];

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

    $cover = !empty($project['cover_image'])
        ? (string) $project['cover_image']
        : $projectFallbacks[0];

    $seo = seo_defaults([
        'title' => $project['seo_title'] ?: ($project['title'] . ' | Mechwize Projects'),
        'description' => $project['seo_description'] ?: $project['summary'],
        'keywords' => $project['seo_keywords'] ?? '',
        'image' => $project['cover_image'] ?: $cover,
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
        <div class="container split-media">
            <div class="media-frame reveal">
                <img src="<?= e(asset($cover)); ?>" alt="<?= e($project['title']); ?>" loading="lazy" width="900" height="600">
            </div>
            <div class="reveal">
                <?= nl2p((string) $project['body']); ?>
                <?php if (!empty($project['images'])): ?>
                    <div class="card-grid two" style="margin-top:1.5rem;">
                        <?php foreach (($project['images'] ?? []) as $image): ?>
                            <div class="project-media" style="height:180px;border-radius:12px;overflow:hidden;">
                                <img src="<?= e(asset((string) $image['image_path'])); ?>" alt="<?= e((string) ($image['alt_text'] ?: $project['title'])); ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
    'image' => asset('assets/images/project-01.jpg'),
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
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
