<?php

$user = require_login();
$householdId = (int) ($_GET['id'] ?? 0);

if (!$householdId || !household_is_member($householdId, $user['id'])) {
    flash('Access denied.', 'error');
    redirect('household');
}

$household = household_find($householdId);
$weekStart = week_start($_GET['week'] ?? null);
$plan = mealplan_get_or_create_for_household($householdId, $weekStart);
$grid = mealplan_get_week_grid($plan['id']);
$dates = week_dates($weekStart);

$pageTitle = e($household['name']) . ' - Week of ' . format_date($weekStart, 'j M Y');
ob_start();
require APP_ROOT . '/templates/household/plan.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
