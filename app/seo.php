<?php

declare(strict_types=1);

function seo_defaults(array $overrides = []): array
{
    $settings = site_settings();

    $defaults = [
        'title' => $settings['default_meta_title'] ?? 'Mechwize Group | HVAC Design, Technical Services & Procurement',
        'description' => $settings['default_meta_description'] ?? 'Mechwize Group provides HVAC design, turnkey solutions, technical services, retrofit, procurement and trading across the UAE and GCC.',
        'keywords' => 'HVAC Dubai, HVAC UAE, chiller services, critical cooling, evaporative cooling, Mechwize Group',
        'image' => $settings['default_og_image'] ?? asset('assets/images/logo-mechwize.png'),
        'url' => absolute_url(current_path()),
        'type' => 'website',
        'robots' => 'index,follow',
        'canonical' => absolute_url(current_path()),
        'json_ld' => [],
    ];

    return array_merge($defaults, array_filter($overrides, static fn ($value) => $value !== null && $value !== ''));
}

function organization_json_ld(): array
{
    $settings = site_settings();

    return [
        '@context' => 'https://schema.org',
        '@type' => ['Organization', 'LocalBusiness'],
        'name' => 'Mechwize Group',
        'url' => absolute_url('/'),
        'logo' => absolute_url(asset('assets/images/logo-mechwize.png')),
        'email' => $settings['email_primary'] ?? 'info@mechwize.com',
        'telephone' => $settings['phone_primary'] ?? '+971 54 736 6228',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $settings['address'] ?? 'PO Box 73111, Business Centre',
            'addressLocality' => 'Dubai',
            'addressRegion' => 'Dubai',
            'addressCountry' => 'AE',
        ],
        'areaServed' => ['AE', 'GCC'],
        'sameAs' => array_values(array_filter([
            $settings['social_linkedin'] ?? null,
            $settings['social_instagram'] ?? null,
            $settings['social_facebook'] ?? null,
        ])),
    ];
}

function breadcrumb_json_ld(array $crumbs): array
{
    $items = [];
    foreach ($crumbs as $index => $crumb) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $crumb['name'],
            'item' => absolute_url($crumb['url']),
        ];
    }

    return [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
}

function service_json_ld(array $service): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service['title'],
        'description' => $service['seo_description'] ?: $service['summary'],
        'provider' => [
            '@type' => 'Organization',
            'name' => 'Mechwize Group',
            'url' => absolute_url('/'),
        ],
        'areaServed' => 'United Arab Emirates',
        'url' => absolute_url('/services/' . $service['slug']),
    ];
}

function render_seo_head(array $seo): void
{
    $settings = site_settings();
    $title = (string) $seo['title'];
    $description = (string) $seo['description'];
    $image = absolute_url((string) $seo['image']);
    $canonical = (string) ($seo['canonical'] ?? $seo['url']);
    $robots = (string) ($seo['robots'] ?? 'index,follow');
    $keywords = (string) ($seo['keywords'] ?? '');
    $type = (string) ($seo['type'] ?? 'website');

    echo '<title>' . e($title) . '</title>' . "\n";
    echo '<meta name="description" content="' . e($description) . '">' . "\n";
    if ($keywords !== '') {
        echo '<meta name="keywords" content="' . e($keywords) . '">' . "\n";
    }
    echo '<meta name="robots" content="' . e($robots) . '">' . "\n";
    echo '<link rel="canonical" href="' . e($canonical) . '">' . "\n";
    echo '<meta property="og:site_name" content="Mechwize Group">' . "\n";
    echo '<meta property="og:type" content="' . e($type) . '">' . "\n";
    echo '<meta property="og:title" content="' . e($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . e($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . e($canonical) . '">' . "\n";
    echo '<meta property="og:image" content="' . e($image) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . e($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . e($description) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . e($image) . '">' . "\n";

    if (!empty($settings['google_site_verification'])) {
        echo '<meta name="google-site-verification" content="' . e((string) $settings['google_site_verification']) . '">' . "\n";
    }

    foreach (($seo['json_ld'] ?? []) as $block) {
        echo '<script type="application/ld+json">' . json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
