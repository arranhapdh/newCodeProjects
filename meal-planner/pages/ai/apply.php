<?php

$user = require_login();

if (!is_post()) {
    redirect('calendar');
}

verify_csrf();

$suggestions = $_SESSION['ai_suggestions'] ?? null;
$weekStart = $_SESSION['ai_week_start'] ?? week_start();
$householdId = $_SESSION['ai_household_id'] ?? null;

if (!$suggestions) {
    flash('No suggestions to apply.', 'error');
    redirect('calendar', ['week' => $weekStart]);
}

// Get or create plan
if ($householdId) {
    if (!household_is_member($householdId, $user['id'])) {
        flash('Access denied.', 'error');
        redirect('household');
    }
    $plan = mealplan_get_or_create_for_household($householdId, $weekStart);
} else {
    $plan = mealplan_get_or_create_for_user($user['id'], $weekStart);
}

// Clear existing meals and apply suggestions
mealplan_clear($plan['id']);

foreach ($suggestions as $meal) {
    meal_save($plan['id'], $meal['day'], $meal['slot'], [
        'title' => $meal['title'],
        'description' => $meal['description'] ?? '',
        'meal_time' => $meal['time'] ?? '12:00',
        'calories' => $meal['calories'] ?? null,
        'ai_generated' => 1,
    ]);
}

unset($_SESSION['ai_suggestions'], $_SESSION['ai_week_start'], $_SESSION['ai_household_id']);

flash('AI meal plan applied!', 'success');

if ($householdId) {
    redirect('household-plan', ['id' => $householdId, 'week' => $weekStart]);
} else {
    redirect('calendar', ['week' => $weekStart]);
}
