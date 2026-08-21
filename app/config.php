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
        'host' => getenv('localhost') ?: '',
        'port' => (int) (getenv('44798') ?: 3306),
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
