<?php

if (is_logged_in()) {
    redirect('dashboard');
}

if (is_post()) {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $displayName = trim($_POST['display_name'] ?? '') ?: $username;

    $errors = [];
    if (strlen($username) < 3) $errors[] = 'Username must be at least 3 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';

    if (empty($errors)) {
        $userId = register_user($username, $email, $password, $displayName);
        if ($userId) {
            login_user($username, $password);
            flash('Account created! Welcome to ' . APP_NAME . '.', 'success');
            redirect('dashboard');
        } else {
            flash('Username or email already taken.', 'error');
        }
    } else {
        foreach ($errors as $err) flash($err, 'error');
    }
}

$pageTitle = 'Register';
ob_start();
require APP_ROOT . '/templates/auth/register.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
