<?php

session_start();

require dirname(__DIR__) . '/config/app.php';
require APP_ROOT . '/config/database.php';
require APP_ROOT . '/config/ai.php';
require APP_ROOT . '/src/helpers.php';
require APP_ROOT . '/src/auth.php';
require APP_ROOT . '/src/middleware.php';
require APP_ROOT . '/src/models/User.php';
require APP_ROOT . '/src/models/Meal.php';
require APP_ROOT . '/src/models/MealPlan.php';
require APP_ROOT . '/src/models/Household.php';
require APP_ROOT . '/src/services/ClaudeService.php';
require APP_ROOT . '/src/services/MealSuggester.php';

$page = current_page();

$routes = [
    'dashboard'       => 'pages/dashboard.php',
    'login'           => 'pages/auth/login.php',
    'register'        => 'pages/auth/register.php',
    'logout'          => 'pages/auth/logout.php',
    'calendar'        => 'pages/calendar/week.php',
    'save-meal'       => 'pages/calendar/save_meal.php',
    'delete-meal'     => 'pages/calendar/delete_meal.php',
    'profile'         => 'pages/profile/edit.php',
    'household'       => 'pages/household/manage.php',
    'household-plan'  => 'pages/household/plan.php',
    'household-save'  => 'pages/household/save_meal.php',
    'admin-users'     => 'pages/admin/users.php',
    'ai-suggest'      => 'pages/ai/suggest.php',
    'ai-apply'        => 'pages/ai/apply.php',
    'ai-modify'       => 'pages/ai/modify.php',
];

$file = $routes[$page] ?? null;

if ($file && file_exists(APP_ROOT . '/' . $file)) {
    require APP_ROOT . '/' . $file;
} else {
    http_response_code(404);
    echo '<h1>Page not found</h1>';
    echo '<p><a href="?page=dashboard">Go to Dashboard</a></p>';
}
