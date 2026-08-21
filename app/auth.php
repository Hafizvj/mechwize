<?php

declare(strict_types=1);

function auth_user(): ?array
{
    if (empty($_SESSION['admin_id'])) {
        return null;
    }

    static $user = null;
    if (is_array($user)) {
        return $user;
    }

    $pdo = db_try();
    if (!$pdo) {
        return null;
    }

    $statement = $pdo->prepare('SELECT id, name, email FROM admins WHERE id = :id LIMIT 1');
    $statement->execute(['id' => (int) $_SESSION['admin_id']]);
    $row = $statement->fetch();

    $user = $row ?: null;
    return $user;
}

function require_admin(): void
{
    if (!auth_user()) {
        set_flash('error', 'Please sign in to continue.');
        redirect(url('admin/login.php'));
    }
}

function attempt_login(string $email, string $password): bool
{
    $pdo = database();
    $statement = $pdo->prepare('SELECT id, password_hash FROM admins WHERE email = :email LIMIT 1');
    $statement->execute(['email' => $email]);
    $admin = $statement->fetch();

    if (!$admin || !password_verify($password, (string) $admin['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];
    return true;
}

function logout_admin(): void
{
    unset($_SESSION['admin_id']);
    session_regenerate_id(true);
}
