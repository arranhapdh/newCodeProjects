<?php

$user = require_login();

$weekStart = week_start($_GET['week'] ?? $_POST['week_start'] ?? null);
$householdId = !empty($_GET['household_id'] ?? $_POST['household_id'] ?? null) ? (int) ($_GET['household_id'] ?? $_POST['household_id']) : null;

// Verify access
if ($householdId && !household_is_member($householdId, $user['id'])) {
    flash('Access denied.', 'error');
    redirect('household');
}

// Get current plan
if ($householdId) {
    $plan = mealplan_get_or_create_for_household($householdId, $weekStart);
    $dietaryPrefs = household_aggregated_dietary_prefs($householdId);
} else {
    $plan = mealplan_get_or_create_for_user($user['id'], $weekStart);
    $dietaryPrefs = user_get_dietary_prefs($user['id']);
}

$currentMeals = mealplan_get_all_meals($plan['id']);
$suggestions = null;
$error = null;

if (is_post()) {
    verify_csrf();
    $request = trim($_POST['modification_request'] ?? '');

    if (empty($request)) {
        flash('Please describe how you want to modify the plan.', 'error');
    } elseif (empty($currentMeals)) {
        flash('No meals to modify. Add some meals first or use AI: Fill Week.', 'error');
    } else {
        try {
            $suggestions = meal_suggester_modify_plan($currentMeals, $dietaryPrefs, $request, $user['id']);
            $_SESSION['ai_suggestions'] = $suggestions;
            $_SESSION['ai_week_start'] = $weekStart;
            $_SESSION['ai_household_id'] = $householdId;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$pageTitle = 'AI: Improve Plan';
ob_start();
require APP_ROOT . '/templates/ai/suggest.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
