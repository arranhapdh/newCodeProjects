<?php

$user = require_login();
$dietaryPrefs = user_get_dietary_prefs($user['id']);

if (is_post()) {
    verify_csrf();
    $displayName = trim($_POST['display_name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';

    if (empty($errors)) {
        user_update($user['id'], [
            'display_name' => $displayName ?: $user['username'],
            'email' => $email,
        ]);

        $selectedPrefs = $_POST['dietary'] ?? [];
        user_set_dietary_prefs($user['id'], $selectedPrefs);
        $dietaryPrefs = $selectedPrefs;

        flash('Profile updated.', 'success');
        redirect('profile');
    } else {
        foreach ($errors as $err) flash($err, 'error');
    }
}

$pageTitle = 'Edit Profile';
ob_start();
require APP_ROOT . '/templates/profile/edit.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
