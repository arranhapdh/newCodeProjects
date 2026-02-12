<?php

$user = require_login();
$households = household_get_for_user($user['id']);
$weekStart = week_start();

$pageTitle = 'Dashboard';
ob_start();
require APP_ROOT . '/templates/dashboard.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
