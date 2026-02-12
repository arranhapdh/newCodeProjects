<?php

$user = require_login();
$weekStart = week_start($_GET['week'] ?? null);
$plan = mealplan_get_or_create_for_user($user['id'], $weekStart);
$grid = mealplan_get_week_grid($plan['id']);
$dates = week_dates($weekStart);

$pageTitle = 'Week of ' . format_date($weekStart, 'j M Y');
ob_start();
require APP_ROOT . '/templates/calendar/week.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
