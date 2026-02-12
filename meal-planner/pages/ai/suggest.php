<?php

$user = require_login();

$weekStart = week_start($_GET['week'] ?? null);
$householdId = !empty($_GET['household_id']) ? (int) $_GET['household_id'] : null;
$context = $householdId ? 'household' : 'personal';

// Verify household access
if ($householdId && !household_is_member($householdId, $user['id'])) {
    flash('Access denied.', 'error');
    redirect('household');
}

// Get dietary prefs
if ($householdId) {
    $dietaryPrefs = household_aggregated_dietary_prefs($householdId);
} else {
    $dietaryPrefs = user_get_dietary_prefs($user['id']);
}

$suggestions = null;
$error = null;

if (is_post()) {
    verify_csrf();
    try {
        $suggestions = meal_suggester_suggest_week($dietaryPrefs, $user['id']);
        $_SESSION['ai_suggestions'] = $suggestions;
        $_SESSION['ai_week_start'] = $weekStart;
        $_SESSION['ai_household_id'] = $householdId;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$pageTitle = 'AI Meal Suggestions';
ob_start();
require APP_ROOT . '/templates/ai/suggest.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
