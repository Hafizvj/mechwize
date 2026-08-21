<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

header('Content-Type: application/xml; charset=UTF-8');

$urls = [
    ['loc' => absolute_url('/'), 'priority' => '1.0'],
    ['loc' => absolute_url('/about'), 'priority' => '0.8'],
    ['loc' => absolute_url('/services'), 'priority' => '0.9'],
    ['loc' => absolute_url('/projects'), 'priority' => '0.8'],
    ['loc' => absolute_url('/clients'), 'priority' => '0.7'],
    ['loc' => absolute_url('/contact'), 'priority' => '0.8'],
];

foreach (get_services() as $service) {
    $urls[] = [
        'loc' => absolute_url('/services/' . $service['slug']),
        'priority' => '0.85',
    ];
}

foreach (get_projects() as $project) {
    $urls[] = [
        'loc' => absolute_url('/projects/' . $project['slug']),
        'priority' => '0.75',
    ];
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $item): ?>
  <url>
    <loc><?= e($item['loc']); ?></loc>
    <changefreq>weekly</changefreq>
    <priority><?= e($item['priority']); ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
