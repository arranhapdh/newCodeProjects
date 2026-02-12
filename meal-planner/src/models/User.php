<?php

function user_find(int $id): array|false
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function user_find_by_username(string $username): array|false
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function user_all(): array
{
    return get_db()->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
}

function user_update(int $id, array $data): void
{
    $db = get_db();
    $fields = [];
    $values = [];
    foreach ($data as $col => $val) {
        $fields[] = "$col = ?";
        $values[] = $val;
    }
    $values[] = $id;
    $db->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($values);
}

function user_delete(int $id): void
{
    $db = get_db();
    $db->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
}

function user_get_dietary_prefs(int $userId): array
{
    $db = get_db();
    $stmt = $db->prepare('SELECT preference FROM dietary_preferences WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function user_set_dietary_prefs(int $userId, array $prefs): void
{
    $db = get_db();
    $db->prepare('DELETE FROM dietary_preferences WHERE user_id = ?')->execute([$userId]);
    $stmt = $db->prepare('INSERT INTO dietary_preferences (user_id, preference) VALUES (?, ?)');
    foreach ($prefs as $pref) {
        if (array_key_exists($pref, DIETARY_OPTIONS)) {
            $stmt->execute([$userId, $pref]);
        }
    }
}

function user_create_admin(string $username, string $email, string $password, string $displayName): int|false
{
    $db = get_db();
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, display_name, role) VALUES (?, ?, ?, ?, 'admin')");
    try {
        $stmt->execute([$username, $email, $hash, $displayName]);
        return (int) $db->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}
