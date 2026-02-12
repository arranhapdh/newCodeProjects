<?php

function meal_save(int $planId, int $day, string $slot, array $data): int
{
    $db = get_db();

    // Upsert: delete existing then insert
    $db->prepare('DELETE FROM meals WHERE meal_plan_id = ? AND day_of_week = ? AND slot = ?')
       ->execute([$planId, $day, $slot]);

    $stmt = $db->prepare(
        'INSERT INTO meals (meal_plan_id, day_of_week, slot, meal_time, title, description, calories, ai_generated)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $planId,
        $day,
        $slot,
        $data['meal_time'] ?? '12:00',
        $data['title'],
        $data['description'] ?? '',
        $data['calories'] ?? null,
        $data['ai_generated'] ?? 0,
    ]);

    return (int) $db->lastInsertId();
}

function meal_delete(int $mealId, int $planId): bool
{
    $db = get_db();
    $stmt = $db->prepare('DELETE FROM meals WHERE id = ? AND meal_plan_id = ?');
    $stmt->execute([$mealId, $planId]);
    return $stmt->rowCount() > 0;
}

function meal_find(int $id): array|false
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM meals WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}
