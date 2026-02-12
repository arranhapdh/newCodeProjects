<?php

$user = require_login();

// Handle create
if (is_post() && ($_POST['action'] ?? '') === 'create') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    if ($name) {
        household_create($name, $user['id']);
        flash('Household created.', 'success');
    } else {
        flash('Household name is required.', 'error');
    }
    redirect('household');
}

// Handle join
if (is_post() && ($_POST['action'] ?? '') === 'join') {
    verify_csrf();
    $code = trim($_POST['invite_code'] ?? '');
    $household = $code ? household_find_by_invite($code) : false;
    if ($household) {
        if (household_join($household['id'], $user['id'])) {
            flash('Joined household: ' . $household['name'], 'success');
        } else {
            flash('You are already a member.', 'error');
        }
    } else {
        flash('Invalid invite code.', 'error');
    }
    redirect('household');
}

// Handle leave
if (is_post() && ($_POST['action'] ?? '') === 'leave') {
    verify_csrf();
    $householdId = (int) ($_POST['household_id'] ?? 0);
    if ($householdId) {
        household_leave($householdId, $user['id']);
        flash('Left household.', 'success');
    }
    redirect('household');
}

// Handle remove member
if (is_post() && ($_POST['action'] ?? '') === 'remove_member') {
    verify_csrf();
    $householdId = (int) ($_POST['household_id'] ?? 0);
    $memberId = (int) ($_POST['member_id'] ?? 0);
    $household = $householdId ? household_find($householdId) : false;
    if ($household && $household['created_by'] == $user['id'] && $memberId != $user['id']) {
        household_remove_member($householdId, $memberId);
        flash('Member removed.', 'success');
    }
    redirect('household');
}

$households = household_get_for_user($user['id']);
$householdMembers = [];
foreach ($households as $h) {
    $householdMembers[$h['id']] = household_get_members($h['id']);
}

$pageTitle = 'Households';
ob_start();
require APP_ROOT . '/templates/household/manage.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
