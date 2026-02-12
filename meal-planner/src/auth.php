<?php

function register_user(string $username, string $email, string $password, string $displayName): int|false
{
    $db = get_db();
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare('INSERT INTO users (username, email, password_hash, display_name) VALUES (?, ?, ?, ?)');
    try {
        $stmt->execute([$username, $email, $hash, $displayName]);
        return (int) $db->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

function login_user(string $username, string $password): array|false
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        return $user;
    }
    return false;
}

function logout_user(): void
{
    unset($_SESSION['user_id']);
    session_destroy();
}

function current_user(): array|null
{
    static $user = null;
    static $loaded = false;

    if (!$loaded) {
        $loaded = true;
        if (!empty($_SESSION['user_id'])) {
            $db = get_db();
            $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch() ?: null;
        }
    }
    return $user;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}
