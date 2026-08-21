<?php

declare(strict_types=1);

function uploads_root(): string
{
    return dirname(__DIR__) . '/uploads';
}

function ensure_upload_dirs(): void
{
    foreach (['projects', 'clients', 'services'] as $folder) {
        $path = uploads_root() . '/' . $folder;
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}

function store_upload(array $file, string $folder, string $prefix = 'file'): ?string
{
    ensure_upload_dirs();

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed. Please try again.');
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('Image must be 5MB or smaller.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file((string) $file['tmp_name']) ?: '';
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
        'image/svg+xml' => 'svg',
    ];

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Only JPG, PNG, WEBP, GIF, or SVG images are allowed.');
    }

    $name = $prefix . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $relative = 'uploads/' . trim($folder, '/') . '/' . $name;
    $destination = dirname(__DIR__) . '/' . $relative;

    if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
        throw new RuntimeException('Could not save uploaded file.');
    }

    return $relative;
}

function delete_upload(?string $relativePath): void
{
    if (!$relativePath) {
        return;
    }

    $full = dirname(__DIR__) . '/' . ltrim($relativePath, '/');
    $root = realpath(uploads_root());
    $real = realpath($full);

    if ($root && $real && str_starts_with($real, $root) && is_file($real)) {
        unlink($real);
    }
}
