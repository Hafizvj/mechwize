-- Mechwize Group schema for Hostmaria / remote MySQL only
-- Import this file into your cloud MySQL database after creating the DB user.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(120) NOT NULL PRIMARY KEY,
    setting_value TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    category VARCHAR(120) NOT NULL DEFAULT '',
    summary TEXT NOT NULL,
    body MEDIUMTEXT NOT NULL,
    hero_image VARCHAR(255) NULL,
    seo_title VARCHAR(255) NULL,
    seo_description VARCHAR(320) NULL,
    seo_keywords VARCHAR(255) NULL,
    og_image VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS service_features (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id INT UNSIGNED NOT NULL,
    feature_text VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_service_features_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    location VARCHAR(160) NULL,
    year VARCHAR(20) NULL,
    category VARCHAR(120) NOT NULL DEFAULT '',
    summary TEXT NOT NULL,
    body MEDIUMTEXT NOT NULL,
    cover_image VARCHAR(255) NULL,
    seo_title VARCHAR(255) NULL,
    seo_description VARCHAR(320) NULL,
    seo_keywords VARCHAR(255) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id INT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_project_images_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS clients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    logo_path VARCHAR(255) NULL,
    logo_alt VARCHAR(255) NULL,
    industry VARCHAR(120) NULL,
    website VARCHAR(255) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS enquiries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    company VARCHAR(160) NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(60) NULL,
    service_interest VARCHAR(190) NULL,
    project_location VARCHAR(160) NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(64) NULL,
    user_agent VARCHAR(255) NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'new',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO admins (name, email, password_hash) VALUES
('Mechwize Admin', 'admin@mechwize.com', '$2y$10$8cUcRpMy9ce7r.M3II8uMOl9eJAv9SL6hVHGb54HA5VFJ0iBqlaX6')
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'Mechwize Group'),
('tagline', 'Right HVAC Solutions. Right Application. Right Execution.'),
('site_url', 'https://mechwize.com'),
('phone_primary', '+971 54 736 6228'),
('phone_secondary', '+971 50 450 7318'),
('whatsapp', '+971 54 736 6228'),
('email_primary', 'info@mechwize.com'),
('email_sales', 'sales@mechwize.com'),
('address', 'PO Box 73111, Business Centre, Dubai | Abu Dhabi | Sharjah - UAE'),
('map_url', 'https://maps.google.com/?q=Dubai+UAE'),
('working_hours', 'Sunday – Thursday, 9:00 AM – 6:00 PM'),
('social_linkedin', ''),
('social_instagram', ''),
('social_facebook', ''),
('default_meta_title', 'Mechwize Group | HVAC Design, Technical Services & Procurement UAE'),
('default_meta_description', 'Mechwize Group delivers HVAC design, turnkey solutions, technical services, retrofit, procurement and trading for commercial, industrial and critical cooling applications across the UAE and GCC.'),
('default_og_image', 'assets/images/logo-hz.svg'),
('google_site_verification', '')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO services (id, title, slug, category, summary, body, seo_title, seo_description, seo_keywords, sort_order, is_featured, is_active) VALUES
(1, 'Design, Build & Turnkey Solutions', 'design-build-turnkey', 'Turnkey', 'Complete HVAC solutions from design to execution and commissioning for commercial, industrial and critical environments.', 'Mechwize Group delivers end-to-end HVAC turnkey projects across the UAE and GCC.\n\nFrom application engineering and equipment selection to supply, installation, testing, commissioning and handover, we match the right solution to the right application.', 'HVAC Design Build & Turnkey Solutions Dubai | Mechwize Group', 'Turnkey HVAC design, supply, installation and commissioning for warehouses, factories, outdoor cooling and critical facilities in the UAE.', 'HVAC turnkey Dubai, HVAC design UAE, industrial cooling', 1, 1, 1),
(2, 'Outdoor Cooling Solutions', 'outdoor-cooling', 'Turnkey', 'Evaporative and indirect evaporative cooling systems engineered for high-ambient outdoor applications.', 'Outdoor cooling projects demand application-specific engineering for UAE climate conditions.\n\nMechwize designs and delivers evaporative and indirect evaporative cooling systems for outdoor and semi-outdoor spaces with a focus on comfort, energy efficiency and reliable operation.', 'Outdoor Evaporative Cooling Systems UAE | Mechwize', 'Outdoor and high-ambient evaporative cooling solutions designed, supplied and installed by Mechwize Group across the UAE.', 'outdoor cooling UAE, evaporative cooling Dubai, indirect evaporative cooling', 2, 1, 1),
(3, 'Warehouse, Factory & Industrial Cooling', 'industrial-cooling', 'Turnkey', 'Application-based cooling and ventilation for large warehouses, factories, workshops and production areas.', 'Large industrial spaces need cooling strategies based on process loads, occupancy, airflow and operational constraints.\n\nMechwize delivers warehouse, factory and industrial cooling solutions that balance comfort, productivity and energy performance.', 'Warehouse & Factory Cooling Solutions UAE | Mechwize', 'Industrial HVAC and ventilation solutions for warehouses, factories and production facilities across Dubai and the UAE.', 'warehouse cooling Dubai, factory cooling UAE, industrial ventilation', 3, 1, 1),
(4, 'Server Room & Critical Cooling', 'critical-cooling', 'Critical', 'Precision and reliable cooling solutions for server rooms, data centers and mission-critical environments.', 'Critical environments require precision cooling, redundancy awareness and dependable servicing.\n\nMechwize supports server room and CCU applications with design, supply, installation, maintenance and technical selection for continuous operation.', 'Server Room & Precision Cooling UAE | Mechwize Group', 'Critical cooling and precision CCU solutions for server rooms and mission-critical facilities in the UAE.', 'server room cooling Dubai, precision cooling UAE, CCU installation', 4, 1, 1),
(5, 'Chiller Specialist Services', 'chiller-services', 'Technical Services', 'Preventive maintenance, troubleshooting, diagnostics, refurbishment, overhauling and compressor-related services.', 'Mechwize provides specialist chiller services for air-cooled and water-cooled systems.\n\nOur technical team supports diagnostics, corrective works, compressor services, coil cleaning, refrigerant circuit work and emergency breakdown response.', 'Chiller Maintenance & Repair Services UAE | Mechwize', 'Chiller troubleshooting, refurbishment, compressor services and preventive maintenance across Dubai and the UAE.', 'chiller services Dubai, chiller repair UAE, compressor overhaul', 5, 1, 1),
(6, 'Chilled Water & DX Systems', 'chilled-water-dx', 'Technical Services', 'Installation, repair, maintenance and troubleshooting for chilled water and DX air conditioning systems.', 'From chilled water pumps and piping to DX package and ducted systems, Mechwize supports installation, repair and ongoing maintenance.\n\nWe help facilities keep systems efficient, reliable and ready for UAE operating conditions.', 'Chilled Water & DX System Services UAE | Mechwize', 'Installation, maintenance and troubleshooting for chilled water and DX HVAC systems in commercial and industrial facilities.', 'chilled water systems Dubai, DX AC service UAE', 6, 0, 1),
(7, 'Airside Equipment Services', 'airside-services', 'Technical Services', 'AHU, FAHU, FCU and HRW inspection, servicing, repair and performance rectification.', 'Airside equipment performance impacts comfort, indoor air quality and energy use.\n\nMechwize services AHUs, FAHUs, FCUs and heat recovery wheels with inspection, repair, filter works, coil rectification and restoration of design performance.', 'AHU FAHU FCU & HRW Servicing UAE | Mechwize', 'Airside HVAC services including AHU, FAHU, FCU and heat recovery wheel inspection, repair and performance restoration.', 'AHU service Dubai, FAHU maintenance UAE, HRW repair', 7, 0, 1),
(8, 'Retrofit & Energy Upgrades', 'retrofit-energy-upgrades', 'Retrofit', 'Energy and performance upgrades including EC fan retrofit, HRW replacement and equipment modernization.', 'Not every facility needs a larger system — many need the right upgrade.\n\nMechwize delivers retrofit projects that improve efficiency, reduce operating cost and modernize aging HVAC assets with EC fans, HRW replacements and equipment upgrades.', 'HVAC Retrofit & EC Fan Upgrades UAE | Mechwize', 'Energy-efficiency HVAC retrofits including EC fan upgrades, HRW replacement and equipment modernization in the UAE.', 'EC fan retrofit Dubai, HVAC retrofit UAE, energy upgrade', 8, 1, 1),
(9, 'Procurement & Trading', 'procurement-trading', 'Procurement', 'Direct sourcing and supply of HVAC equipment, components and spare parts from approved manufacturers.', 'Mechwize provides a single point of contact for HVAC procurement and trading.\n\nWe support technical selection, OEM coordination, documentation and supply of airside equipment, chillers, DX units, fans, motors, HRWs and spare parts.', 'HVAC Equipment Procurement & Trading UAE | Mechwize', 'Source AHU, FAHU, FCU, chillers, DX units, EC fans, HRW and HVAC spare parts through Mechwize Group procurement.', 'HVAC procurement Dubai, HVAC spare parts UAE, OEM sourcing', 9, 1, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

DELETE FROM service_features;
INSERT INTO service_features (service_id, feature_text, sort_order) VALUES
(1, 'System selection and application engineering', 1),
(1, 'Supply, installation and commissioning', 2),
(1, 'Outdoor, warehouse and industrial cooling', 3),
(1, 'Performance verification and handover', 4),
(2, 'Evaporative cooling systems', 1),
(2, 'Indirect evaporative solutions', 2),
(2, 'High-ambient application engineering', 3),
(2, 'Installation and commissioning', 4),
(3, 'Warehouse cooling design', 1),
(3, 'Factory and workshop ventilation', 2),
(3, 'Airflow optimization', 3),
(3, 'Turnkey installation support', 4),
(4, 'Precision cooling units (CCU)', 1),
(4, 'Server room installation and servicing', 2),
(4, 'Reliability-focused maintenance', 3),
(4, 'Equipment selection support', 4),
(5, 'Troubleshooting and diagnostics', 1),
(5, 'Compressor repair and overhauling', 2),
(5, 'Preventive maintenance programs', 3),
(5, 'Emergency breakdown response', 4),
(6, 'Chilled water system services', 1),
(6, 'Pump and piping support', 2),
(6, 'DX installation and repair', 3),
(6, 'System troubleshooting', 4),
(7, 'AHU and FAHU servicing', 1),
(7, 'FCU inspection and repair', 2),
(7, 'HRW motor and belt services', 3),
(7, 'Coil and filter rectification', 4),
(8, 'EC fan and motor retrofit', 1),
(8, 'HRW replacement and repair', 2),
(8, 'Equipment modernization', 3),
(8, 'Energy-performance upgrades', 4),
(9, 'FCU, AHU, FAHU and airside equipment', 1),
(9, 'Chillers, DX and precision cooling units', 2),
(9, 'Fans, EC fans, motors and HRW', 3),
(9, 'Controls, spare parts and accessories', 4);

INSERT INTO projects (id, title, slug, location, year, category, summary, body, seo_title, seo_description, seo_keywords, is_featured, is_published) VALUES
(1, 'Warehouse Evaporative Cooling Upgrade', 'warehouse-evaporative-cooling', 'Dubai, UAE', '2025', 'Outdoor Cooling', 'Application-based outdoor and warehouse cooling solution designed for high-ambient operation.', 'Mechwize delivered design support, equipment selection and execution planning for a warehouse cooling upgrade focused on comfort and efficiency.', 'Warehouse Evaporative Cooling Project Dubai | Mechwize', 'Case snapshot of a warehouse evaporative cooling upgrade delivered by Mechwize Group in Dubai.', 'warehouse cooling project Dubai', 1, 1),
(2, 'Server Room Precision Cooling Support', 'server-room-precision-cooling', 'Abu Dhabi, UAE', '2025', 'Critical Cooling', 'Precision cooling selection and technical services for a mission-critical server room environment.', 'The project included CCU technical selection support, installation coordination and reliability-focused maintenance planning.', 'Server Room Cooling Project UAE | Mechwize', 'Critical cooling project support for server room precision systems in the UAE.', 'server room cooling project', 1, 1),
(3, 'EC Fan Retrofit for Airside Efficiency', 'ec-fan-retrofit', 'Sharjah, UAE', '2024', 'Retrofit', 'High-efficiency EC fan retrofit to reduce energy consumption and restore airflow performance.', 'Conventional fan systems were upgraded with EC fan technology to improve efficiency and long-term reliability.', 'EC Fan Retrofit Project UAE | Mechwize', 'Energy-efficiency EC fan retrofit project delivered by Mechwize Group technical services.', 'EC fan retrofit project UAE', 1, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO clients (id, name, logo_path, logo_alt, industry, website, is_featured, is_published, sort_order) VALUES
(1, 'Commercial Facility Partner', NULL, 'Commercial Facility Partner', 'Commercial', '', 1, 1, 1),
(2, 'Industrial Operations Client', NULL, 'Industrial Operations Client', 'Industrial', '', 1, 1, 2),
(3, 'Critical Infrastructure Client', NULL, 'Critical Infrastructure Client', 'Data Centers', '', 1, 1, 3),
(4, 'Hospitality Portfolio Client', NULL, 'Hospitality Portfolio Client', 'Hospitality', '', 0, 1, 4)
ON DUPLICATE KEY UPDATE name = VALUES(name);
