<?php

declare(strict_types=1);

function database_configured(): bool
{
    $config = require __DIR__ . '/config.php';
    $database = $config['database'] ?? [];

    foreach (['host', 'name', 'user'] as $key) {
        if (empty($database[$key])) {
            return false;
        }
    }

    return true;
}

function database(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if (!database_configured()) {
        throw new RuntimeException('Remote MySQL is not configured. Set Hostmaria/cloud database credentials before using dynamic features.');
    }

    $config = require __DIR__ . '/config.php';
    $database = $config['database'];
    $charset = $database['charset'] ?? 'utf8mb4';
    $port = (int) ($database['port'] ?? 3306);

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $database['host'],
        $port,
        $database['name'],
        $charset
    );

    $pdo = new PDO($dsn, $database['user'], (string) ($database['password'] ?? ''), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function db_try(): ?PDO
{
    try {
        return database();
    } catch (Throwable $exception) {
        error_log('Mechwize DB unavailable: ' . $exception->getMessage());
        return null;
    }
}
