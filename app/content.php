<?php

declare(strict_types=1);

function default_site_settings(): array
{
    return [
        'site_name' => 'Mechwize Group',
        'tagline' => 'Right HVAC Solutions. Right Application. Right Execution.',
        'site_url' => 'https://mechwize.com',
        'phone_primary' => '+971 54 736 6228',
        'phone_secondary' => '+971 50 450 7318',
        'whatsapp' => '+971 54 736 6228',
        'email_primary' => 'info@mechwize.com',
        'email_sales' => 'sales@mechwize.com',
        'address' => 'PO Box 73111, Business Centre, Dubai | Abu Dhabi | Sharjah - UAE',
        'map_url' => 'https://maps.google.com/?q=Dubai+UAE',
        'working_hours' => 'Sunday – Thursday, 9:00 AM – 6:00 PM',
        'social_linkedin' => '',
        'social_instagram' => '',
        'social_facebook' => '',
        'default_meta_title' => 'Mechwize Group | HVAC Design, Technical Services & Procurement UAE',
        'default_meta_description' => 'Mechwize Group delivers HVAC design, turnkey solutions, technical services, retrofit, procurement and trading for commercial, industrial and critical cooling applications across the UAE and GCC.',
        'default_og_image' => 'assets/images/logo-hz.svg',
        'google_site_verification' => '',
    ];
}

function site_settings(): array
{
    static $settings = null;
    if (is_array($settings)) {
        return $settings;
    }

    $defaults = default_site_settings();
    $pdo = db_try();

    if (!$pdo) {
        $settings = $defaults;
        return $settings;
    }

    try {
        $rows = $pdo->query('SELECT setting_key, setting_value FROM site_settings')->fetchAll();
        $mapped = [];
        foreach ($rows as $row) {
            $mapped[$row['setting_key']] = $row['setting_value'];
        }
        $settings = array_merge($defaults, $mapped);
    } catch (Throwable $exception) {
        error_log('site_settings failed: ' . $exception->getMessage());
        $settings = $defaults;
    }

    return $settings;
}

function update_site_settings(array $data): void
{
    $pdo = database();
    $statement = $pdo->prepare(
        'INSERT INTO site_settings (setting_key, setting_value)
         VALUES (:setting_key, :setting_value)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );

    foreach ($data as $key => $value) {
        $statement->execute([
            'setting_key' => $key,
            'setting_value' => (string) $value,
        ]);
    }
}

function seeded_services(): array
{
    return [
        [
            'id' => 1,
            'title' => 'Design, Build & Turnkey Solutions',
            'slug' => 'design-build-turnkey',
            'category' => 'Turnkey',
            'summary' => 'Complete HVAC solutions from design to execution and commissioning for commercial, industrial and critical environments.',
            'body' => "Mechwize Group delivers end-to-end HVAC turnkey projects across the UAE and GCC.\n\nFrom application engineering and equipment selection to supply, installation, testing, commissioning and handover, we match the right solution to the right application.",
            'hero_image' => null,
            'seo_title' => 'HVAC Design Build & Turnkey Solutions Dubai | Mechwize Group',
            'seo_description' => 'Turnkey HVAC design, supply, installation and commissioning for warehouses, factories, outdoor cooling and critical facilities in the UAE.',
            'seo_keywords' => 'HVAC turnkey Dubai, HVAC design UAE, industrial cooling',
            'og_image' => null,
            'sort_order' => 1,
            'is_featured' => 1,
            'is_active' => 1,
            'features' => ['System selection and application engineering', 'Supply, installation and commissioning', 'Outdoor, warehouse and industrial cooling', 'Performance verification and handover'],
        ],
        [
            'id' => 2,
            'title' => 'Outdoor Cooling Solutions',
            'slug' => 'outdoor-cooling',
            'category' => 'Turnkey',
            'summary' => 'Evaporative and indirect evaporative cooling systems engineered for high-ambient outdoor applications.',
            'body' => "Outdoor cooling projects demand application-specific engineering for UAE climate conditions.\n\nMechwize designs and delivers evaporative and indirect evaporative cooling systems for outdoor and semi-outdoor spaces with a focus on comfort, energy efficiency and reliable operation.",
            'hero_image' => null,
            'seo_title' => 'Outdoor Evaporative Cooling Systems UAE | Mechwize',
            'seo_description' => 'Outdoor and high-ambient evaporative cooling solutions designed, supplied and installed by Mechwize Group across the UAE.',
            'seo_keywords' => 'outdoor cooling UAE, evaporative cooling Dubai, indirect evaporative cooling',
            'og_image' => null,
            'sort_order' => 2,
            'is_featured' => 1,
            'is_active' => 1,
            'features' => ['Evaporative cooling systems', 'Indirect evaporative solutions', 'High-ambient application engineering', 'Installation and commissioning'],
        ],
        [
            'id' => 3,
            'title' => 'Warehouse, Factory & Industrial Cooling',
            'slug' => 'industrial-cooling',
            'category' => 'Turnkey',
            'summary' => 'Application-based cooling and ventilation for large warehouses, factories, workshops and production areas.',
            'body' => "Large industrial spaces need cooling strategies based on process loads, occupancy, airflow and operational constraints.\n\nMechwize delivers warehouse, factory and industrial cooling solutions that balance comfort, productivity and energy performance.",
            'hero_image' => null,
            'seo_title' => 'Warehouse & Factory Cooling Solutions UAE | Mechwize',
            'seo_description' => 'Industrial HVAC and ventilation solutions for warehouses, factories and production facilities across Dubai and the UAE.',
            'seo_keywords' => 'warehouse cooling Dubai, factory cooling UAE, industrial ventilation',
            'og_image' => null,
            'sort_order' => 3,
            'is_featured' => 1,
            'is_active' => 1,
            'features' => ['Warehouse cooling design', 'Factory and workshop ventilation', 'Airflow optimization', 'Turnkey installation support'],
        ],
        [
            'id' => 4,
            'title' => 'Server Room & Critical Cooling',
            'slug' => 'critical-cooling',
            'category' => 'Critical',
            'summary' => 'Precision and reliable cooling solutions for server rooms, data centers and mission-critical environments.',
            'body' => "Critical environments require precision cooling, redundancy awareness and dependable servicing.\n\nMechwize supports server room and CCU applications with design, supply, installation, maintenance and technical selection for continuous operation.",
            'hero_image' => null,
            'seo_title' => 'Server Room & Precision Cooling UAE | Mechwize Group',
            'seo_description' => 'Critical cooling and precision CCU solutions for server rooms and mission-critical facilities in the UAE.',
            'seo_keywords' => 'server room cooling Dubai, precision cooling UAE, CCU installation',
            'og_image' => null,
            'sort_order' => 4,
            'is_featured' => 1,
            'is_active' => 1,
            'features' => ['Precision cooling units (CCU)', 'Server room installation and servicing', 'Reliability-focused maintenance', 'Equipment selection support'],
        ],
        [
            'id' => 5,
            'title' => 'Chiller Specialist Services',
            'slug' => 'chiller-services',
            'category' => 'Technical Services',
            'summary' => 'Preventive maintenance, troubleshooting, diagnostics, refurbishment, overhauling and compressor-related services.',
            'body' => "Mechwize provides specialist chiller services for air-cooled and water-cooled systems.\n\nOur technical team supports diagnostics, corrective works, compressor services, coil cleaning, refrigerant circuit work and emergency breakdown response.",
            'hero_image' => null,
            'seo_title' => 'Chiller Maintenance & Repair Services UAE | Mechwize',
            'seo_description' => 'Chiller troubleshooting, refurbishment, compressor services and preventive maintenance across Dubai and the UAE.',
            'seo_keywords' => 'chiller services Dubai, chiller repair UAE, compressor overhaul',
            'og_image' => null,
            'sort_order' => 5,
            'is_featured' => 1,
            'is_active' => 1,
            'features' => ['Troubleshooting and diagnostics', 'Compressor repair and overhauling', 'Preventive maintenance programs', 'Emergency breakdown response'],
        ],
        [
            'id' => 6,
            'title' => 'Chilled Water & DX Systems',
            'slug' => 'chilled-water-dx',
            'category' => 'Technical Services',
            'summary' => 'Installation, repair, maintenance and troubleshooting for chilled water and DX air conditioning systems.',
            'body' => "From chilled water pumps and piping to DX package and ducted systems, Mechwize supports installation, repair and ongoing maintenance.\n\nWe help facilities keep systems efficient, reliable and ready for UAE operating conditions.",
            'hero_image' => null,
            'seo_title' => 'Chilled Water & DX System Services UAE | Mechwize',
            'seo_description' => 'Installation, maintenance and troubleshooting for chilled water and DX HVAC systems in commercial and industrial facilities.',
            'seo_keywords' => 'chilled water systems Dubai, DX AC service UAE',
            'og_image' => null,
            'sort_order' => 6,
            'is_featured' => 0,
            'is_active' => 1,
            'features' => ['Chilled water system services', 'Pump and piping support', 'DX installation and repair', 'System troubleshooting'],
        ],
        [
            'id' => 7,
            'title' => 'Airside Equipment Services',
            'slug' => 'airside-services',
            'category' => 'Technical Services',
            'summary' => 'AHU, FAHU, FCU and HRW inspection, servicing, repair and performance rectification.',
            'body' => "Airside equipment performance impacts comfort, indoor air quality and energy use.\n\nMechwize services AHUs, FAHUs, FCUs and heat recovery wheels with inspection, repair, filter works, coil rectification and restoration of design performance.",
            'hero_image' => null,
            'seo_title' => 'AHU FAHU FCU & HRW Servicing UAE | Mechwize',
            'seo_description' => 'Airside HVAC services including AHU, FAHU, FCU and heat recovery wheel inspection, repair and performance restoration.',
            'seo_keywords' => 'AHU service Dubai, FAHU maintenance UAE, HRW repair',
            'og_image' => null,
            'sort_order' => 7,
            'is_featured' => 0,
            'is_active' => 1,
            'features' => ['AHU and FAHU servicing', 'FCU inspection and repair', 'HRW motor and belt services', 'Coil and filter rectification'],
        ],
        [
            'id' => 8,
            'title' => 'Retrofit & Energy Upgrades',
            'slug' => 'retrofit-energy-upgrades',
            'category' => 'Retrofit',
            'summary' => 'Energy and performance upgrades including EC fan retrofit, HRW replacement and equipment modernization.',
            'body' => "Not every facility needs a larger system — many need the right upgrade.\n\nMechwize delivers retrofit projects that improve efficiency, reduce operating cost and modernize aging HVAC assets with EC fans, HRW replacements and equipment upgrades.",
            'hero_image' => null,
            'seo_title' => 'HVAC Retrofit & EC Fan Upgrades UAE | Mechwize',
            'seo_description' => 'Energy-efficiency HVAC retrofits including EC fan upgrades, HRW replacement and equipment modernization in the UAE.',
            'seo_keywords' => 'EC fan retrofit Dubai, HVAC retrofit UAE, energy upgrade',
            'og_image' => null,
            'sort_order' => 8,
            'is_featured' => 1,
            'is_active' => 1,
            'features' => ['EC fan and motor retrofit', 'HRW replacement and repair', 'Equipment modernization', 'Energy-performance upgrades'],
        ],
        [
            'id' => 9,
            'title' => 'Procurement & Trading',
            'slug' => 'procurement-trading',
            'category' => 'Procurement',
            'summary' => 'Direct sourcing and supply of HVAC equipment, components and spare parts from approved manufacturers.',
            'body' => "Mechwize provides a single point of contact for HVAC procurement and trading.\n\nWe support technical selection, OEM coordination, documentation and supply of airside equipment, chillers, DX units, fans, motors, HRWs and spare parts.",
            'hero_image' => null,
            'seo_title' => 'HVAC Equipment Procurement & Trading UAE | Mechwize',
            'seo_description' => 'Source AHU, FAHU, FCU, chillers, DX units, EC fans, HRW and HVAC spare parts through Mechwize Group procurement.',
            'seo_keywords' => 'HVAC procurement Dubai, HVAC spare parts UAE, OEM sourcing',
            'og_image' => null,
            'sort_order' => 9,
            'is_featured' => 1,
            'is_active' => 1,
            'features' => ['FCU, AHU, FAHU and airside equipment', 'Chillers, DX and precision cooling units', 'Fans, EC fans, motors and HRW', 'Controls, spare parts and accessories'],
        ],
    ];
}

function get_services(bool $featuredOnly = false, bool $activeOnly = true): array
{
    $pdo = db_try();
    if (!$pdo) {
        $services = seeded_services();
        if ($featuredOnly) {
            $services = array_values(array_filter($services, static fn ($s) => !empty($s['is_featured'])));
        }
        return $services;
    }

    try {
        $sql = 'SELECT * FROM services';
        $where = [];
        if ($activeOnly) {
            $where[] = 'is_active = 1';
        }
        if ($featuredOnly) {
            $where[] = 'is_featured = 1';
        }
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY sort_order ASC, id ASC';
        $services = $pdo->query($sql)->fetchAll();

        foreach ($services as &$service) {
            $service['features'] = get_service_features((int) $service['id']);
        }

        return $services;
    } catch (Throwable $exception) {
        error_log('get_services failed: ' . $exception->getMessage());
        return seeded_services();
    }
}

function get_service_by_slug(string $slug): ?array
{
    $pdo = db_try();
    if (!$pdo) {
        foreach (seeded_services() as $service) {
            if ($service['slug'] === $slug) {
                return $service;
            }
        }
        return null;
    }

    try {
        $statement = $pdo->prepare('SELECT * FROM services WHERE slug = :slug AND is_active = 1 LIMIT 1');
        $statement->execute(['slug' => $slug]);
        $service = $statement->fetch();
        if (!$service) {
            return null;
        }
        $service['features'] = get_service_features((int) $service['id']);
        return $service;
    } catch (Throwable $exception) {
        error_log('get_service_by_slug failed: ' . $exception->getMessage());
        return null;
    }
}

function get_service_features(int $serviceId): array
{
    $pdo = db_try();
    if (!$pdo) {
        foreach (seeded_services() as $service) {
            if ((int) $service['id'] === $serviceId) {
                return $service['features'] ?? [];
            }
        }
        return [];
    }

    $statement = $pdo->prepare('SELECT feature_text FROM service_features WHERE service_id = :id ORDER BY sort_order ASC, id ASC');
    $statement->execute(['id' => $serviceId]);
    return array_column($statement->fetchAll(), 'feature_text');
}

function seeded_projects(): array
{
    return [
        [
            'id' => 1,
            'title' => 'Warehouse Evaporative Cooling Upgrade',
            'slug' => 'warehouse-evaporative-cooling',
            'location' => 'Dubai, UAE',
            'year' => '2025',
            'category' => 'Outdoor Cooling',
            'summary' => 'Application-based outdoor and warehouse cooling solution designed for high-ambient operation.',
            'body' => 'Mechwize delivered design support, equipment selection and execution planning for a warehouse cooling upgrade focused on comfort and efficiency.',
            'cover_image' => null,
            'seo_title' => 'Warehouse Evaporative Cooling Project Dubai | Mechwize',
            'seo_description' => 'Case snapshot of a warehouse evaporative cooling upgrade delivered by Mechwize Group in Dubai.',
            'seo_keywords' => 'warehouse cooling project Dubai',
            'is_featured' => 1,
            'is_published' => 1,
            'images' => [],
        ],
        [
            'id' => 2,
            'title' => 'Server Room Precision Cooling Support',
            'slug' => 'server-room-precision-cooling',
            'location' => 'Abu Dhabi, UAE',
            'year' => '2025',
            'category' => 'Critical Cooling',
            'summary' => 'Precision cooling selection and technical services for a mission-critical server room environment.',
            'body' => 'The project included CCU technical selection support, installation coordination and reliability-focused maintenance planning.',
            'cover_image' => null,
            'seo_title' => 'Server Room Cooling Project UAE | Mechwize',
            'seo_description' => 'Critical cooling project support for server room precision systems in the UAE.',
            'seo_keywords' => 'server room cooling project',
            'is_featured' => 1,
            'is_published' => 1,
            'images' => [],
        ],
        [
            'id' => 3,
            'title' => 'EC Fan Retrofit for Airside Efficiency',
            'slug' => 'ec-fan-retrofit',
            'location' => 'Sharjah, UAE',
            'year' => '2024',
            'category' => 'Retrofit',
            'summary' => 'High-efficiency EC fan retrofit to reduce energy consumption and restore airflow performance.',
            'body' => 'Conventional fan systems were upgraded with EC fan technology to improve efficiency and long-term reliability.',
            'cover_image' => null,
            'seo_title' => 'EC Fan Retrofit Project UAE | Mechwize',
            'seo_description' => 'Energy-efficiency EC fan retrofit project delivered by Mechwize Group technical services.',
            'seo_keywords' => 'EC fan retrofit project UAE',
            'is_featured' => 1,
            'is_published' => 1,
            'images' => [],
        ],
    ];
}

function get_projects(bool $featuredOnly = false, ?string $category = null): array
{
    $pdo = db_try();
    if (!$pdo) {
        $projects = seeded_projects();
        if ($featuredOnly) {
            $projects = array_values(array_filter($projects, static fn ($p) => !empty($p['is_featured'])));
        }
        if ($category) {
            $projects = array_values(array_filter($projects, static fn ($p) => strcasecmp((string) $p['category'], $category) === 0));
        }
        return $projects;
    }

    try {
        $sql = 'SELECT * FROM projects WHERE is_published = 1';
        $params = [];
        if ($featuredOnly) {
            $sql .= ' AND is_featured = 1';
        }
        if ($category) {
            $sql .= ' AND category = :category';
            $params['category'] = $category;
        }
        $sql .= ' ORDER BY year DESC, id DESC';
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        $projects = $statement->fetchAll();

        foreach ($projects as &$project) {
            $project['images'] = get_project_images((int) $project['id']);
        }

        return $projects;
    } catch (Throwable $exception) {
        error_log('get_projects failed: ' . $exception->getMessage());
        return seeded_projects();
    }
}

function get_project_by_slug(string $slug): ?array
{
    $pdo = db_try();
    if (!$pdo) {
        foreach (seeded_projects() as $project) {
            if ($project['slug'] === $slug) {
                return $project;
            }
        }
        return null;
    }

    $statement = $pdo->prepare('SELECT * FROM projects WHERE slug = :slug AND is_published = 1 LIMIT 1');
    $statement->execute(['slug' => $slug]);
    $project = $statement->fetch();
    if (!$project) {
        return null;
    }
    $project['images'] = get_project_images((int) $project['id']);
    return $project;
}

function get_project_images(int $projectId): array
{
    $pdo = db_try();
    if (!$pdo) {
        return [];
    }

    $statement = $pdo->prepare('SELECT * FROM project_images WHERE project_id = :id ORDER BY sort_order ASC, id ASC');
    $statement->execute(['id' => $projectId]);
    return $statement->fetchAll();
}

function get_project_categories(): array
{
    $projects = get_projects();
    $categories = [];
    foreach ($projects as $project) {
        $cat = trim((string) ($project['category'] ?? ''));
        if ($cat !== '') {
            $categories[$cat] = $cat;
        }
    }
    return array_values($categories);
}

function seeded_clients(): array
{
    return [
        ['id' => 1, 'name' => 'Commercial Facility Partner', 'logo_path' => null, 'logo_alt' => 'Commercial Facility Partner', 'industry' => 'Commercial', 'website' => '', 'is_featured' => 1, 'is_published' => 1, 'sort_order' => 1],
        ['id' => 2, 'name' => 'Industrial Operations Client', 'logo_path' => null, 'logo_alt' => 'Industrial Operations Client', 'industry' => 'Industrial', 'website' => '', 'is_featured' => 1, 'is_published' => 1, 'sort_order' => 2],
        ['id' => 3, 'name' => 'Critical Infrastructure Client', 'logo_path' => null, 'logo_alt' => 'Critical Infrastructure Client', 'industry' => 'Data Centers', 'website' => '', 'is_featured' => 1, 'is_published' => 1, 'sort_order' => 3],
        ['id' => 4, 'name' => 'Hospitality Portfolio Client', 'logo_path' => null, 'logo_alt' => 'Hospitality Portfolio Client', 'industry' => 'Hospitality', 'website' => '', 'is_featured' => 0, 'is_published' => 1, 'sort_order' => 4],
    ];
}

function get_clients(bool $featuredOnly = false): array
{
    $pdo = db_try();
    if (!$pdo) {
        $clients = seeded_clients();
        if ($featuredOnly) {
            $clients = array_values(array_filter($clients, static fn ($c) => !empty($c['is_featured'])));
        }
        return $clients;
    }

    try {
        $sql = 'SELECT * FROM clients WHERE is_published = 1';
        if ($featuredOnly) {
            $sql .= ' AND is_featured = 1';
        }
        $sql .= ' ORDER BY sort_order ASC, id ASC';
        return $pdo->query($sql)->fetchAll();
    } catch (Throwable $exception) {
        error_log('get_clients failed: ' . $exception->getMessage());
        return seeded_clients();
    }
}

function service_interest_options(): array
{
    $options = [];
    foreach (get_services() as $service) {
        $options[] = $service['title'];
    }
    $options[] = 'General HVAC Enquiry';
    return array_values(array_unique($options));
}
