<?php

$user = require_login();

if (!is_post()) {
    redirect('calendar');
}

verify_csrf();

$mealId = (int) ($_POST['meal_id'] ?? 0);
$weekStart = $_POST['week_start'] ?? week_start();
$plan = mealplan_get_or_create_for_user($user['id'], $weekStart);

if ($mealId && meal_delete($mealId, $plan['id'])) {
    flash('Meal removed.', 'success');
} else {
    flash('Could not remove meal.', 'error');
}

redirect('calendar', ['week' => $weekStart]);
