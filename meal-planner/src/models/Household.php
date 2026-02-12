<?php

function household_create(string $name, int $userId): int
{
    $db = get_db();
    $inviteCode = strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    $db->prepare('INSERT INTO households (name, invite_code, created_by) VALUES (?, ?, ?)')
       ->execute([$name, $inviteCode, $userId]);
    $householdId = (int) $db->lastInsertId();

    $db->prepare("INSERT INTO household_members (household_id, user_id, role) VALUES (?, ?, 'owner')")
       ->execute([$householdId, $userId]);

    return $householdId;
}

function household_find(int $id): array|false
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM households WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function household_find_by_invite(string $code): array|false
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM households WHERE invite_code = ?');
    $stmt->execute([strtoupper($code)]);
    return $stmt->fetch();
}

function household_get_for_user(int $userId): array
{
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT h.*, hm.role AS member_role
         FROM households h
         JOIN household_members hm ON h.id = hm.household_id
         WHERE hm.user_id = ?
         ORDER BY h.name'
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function household_get_members(int $householdId): array
{
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT u.id, u.username, u.display_name, hm.role
         FROM users u
         JOIN household_members hm ON u.id = hm.user_id
         WHERE hm.household_id = ?
         ORDER BY hm.role DESC, u.username'
    );
    $stmt->execute([$householdId]);
    return $stmt->fetchAll();
}

function household_is_member(int $householdId, int $userId): bool
{
    $db = get_db();
    $stmt = $db->prepare('SELECT 1 FROM household_members WHERE household_id = ? AND user_id = ?');
    $stmt->execute([$householdId, $userId]);
    return (bool) $stmt->fetch();
}

function household_join(int $householdId, int $userId): bool
{
    $db = get_db();
    try {
        $db->prepare("INSERT INTO household_members (household_id, user_id, role) VALUES (?, ?, 'member')")
           ->execute([$householdId, $userId]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function household_leave(int $householdId, int $userId): void
{
    $db = get_db();
    $db->prepare('DELETE FROM household_members WHERE household_id = ? AND user_id = ?')
       ->execute([$householdId, $userId]);
}

function household_remove_member(int $householdId, int $userId): void
{
    household_leave($householdId, $userId);
}

function household_aggregated_dietary_prefs(int $householdId): array
{
    $db = get_db();
    $stmt = $db->prepare(
        'SELECT DISTINCT dp.preference
         FROM dietary_preferences dp
         JOIN household_members hm ON dp.user_id = hm.user_id
         WHERE hm.household_id = ?
         ORDER BY dp.preference'
    );
    $stmt->execute([$householdId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
