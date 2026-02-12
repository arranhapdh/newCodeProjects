<?php

function mealplan_get_or_create_for_user(int $userId, string $weekStart): array
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM meal_plans WHERE user_id = ? AND week_start = ?');
    $stmt->execute([$userId, $weekStart]);
    $plan = $stmt->fetch();

    if (!$plan) {
        $db->prepare('INSERT INTO meal_plans (user_id, week_start) VALUES (?, ?)')->execute([$userId, $weekStart]);
        $plan = ['id' => (int) $db->lastInsertId(), 'user_id' => $userId, 'household_id' => null, 'week_start' => $weekStart];
    }
    return $plan;
}

function mealplan_get_or_create_for_household(int $householdId, string $weekStart): array
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM meal_plans WHERE household_id = ? AND week_start = ?');
    $stmt->execute([$householdId, $weekStart]);
    $plan = $stmt->fetch();

    if (!$plan) {
        $db->prepare('INSERT INTO meal_plans (household_id, week_start) VALUES (?, ?)')->execute([$householdId, $weekStart]);
        $plan = ['id' => (int) $db->lastInsertId(), 'user_id' => null, 'household_id' => $householdId, 'week_start' => $weekStart];
    }
    return $plan;
}

function mealplan_get_week_grid(int $planId): array
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM meals WHERE meal_plan_id = ? ORDER BY day_of_week, slot');
    $stmt->execute([$planId]);
    $meals = $stmt->fetchAll();

    $grid = [];
    foreach (MEAL_SLOTS as $slot) {
        foreach (DAYS_OF_WEEK as $day => $label) {
            $grid[$slot][$day] = null;
        }
    }

    foreach ($meals as $meal) {
        $grid[$meal['slot']][$meal['day_of_week']] = $meal;
    }

    return $grid;
}

function mealplan_get_all_meals(int $planId): array
{
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM meals WHERE meal_plan_id = ? ORDER BY day_of_week, slot');
    $stmt->execute([$planId]);
    return $stmt->fetchAll();
}

function mealplan_clear(int $planId): void
{
    get_db()->prepare('DELETE FROM meals WHERE meal_plan_id = ?')->execute([$planId]);
}
