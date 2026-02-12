<?php

$user = require_login();

if (!is_post()) {
    redirect('calendar');
}

verify_csrf();

$weekStart = week_start($_POST['week_start'] ?? null);
$day = (int) ($_POST['day'] ?? 0);
$slot = $_POST['slot'] ?? '';

if ($day < 1 || $day > 7 || !in_array($slot, MEAL_SLOTS)) {
    flash('Invalid meal slot.', 'error');
    redirect('calendar', ['week' => $weekStart]);
}

$title = trim($_POST['title'] ?? '');
if (empty($title)) {
    flash('Meal title is required.', 'error');
    redirect('calendar', ['week' => $weekStart]);
}

$plan = mealplan_get_or_create_for_user($user['id'], $weekStart);

meal_save($plan['id'], $day, $slot, [
    'title' => $title,
    'description' => trim($_POST['description'] ?? ''),
    'meal_time' => $_POST['meal_time'] ?? '12:00',
    'calories' => !empty($_POST['calories']) ? (int) $_POST['calories'] : null,
    'ai_generated' => 0,
]);

flash('Meal saved.', 'success');
redirect('calendar', ['week' => $weekStart]);
