<?php

declare(strict_types=1);

$localConfig = __DIR__ . '/config.local.php';

if (is_file($localConfig)) {
    $config = require $localConfig;
    if (is_array($config)) {
        return $config;
    }
}

return [
    'database' => [
        'host' => getenv('MECHWIZE_DB_HOST') ?: '',
        'port' => (int) (getenv('MECHWIZE_DB_PORT') ?: 3306),
        'name' => getenv('MECHWIZE_DB_NAME') ?: '',
        'user' => getenv('MECHWIZE_DB_USER') ?: '',
        'password' => getenv('MECHWIZE_DB_PASS') ?: '',
        'charset' => getenv('MECHWIZE_DB_CHARSET') ?: 'utf8mb4',
    ],
    'mail' => [
        'to' => getenv('MECHWIZE_MAIL_TO') ?: 'info@mechwize.com',
        'from' => getenv('MECHWIZE_MAIL_FROM') ?: 'website@mechwize.com',
    ],
];
