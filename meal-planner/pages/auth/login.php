<?php

if (is_logged_in()) {
    redirect('dashboard');
}

if (is_post()) {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password && login_user($username, $password)) {
        flash('Welcome back!', 'success');
        redirect('dashboard');
    } else {
        flash('Invalid username or password.', 'error');
    }
}

$pageTitle = 'Login';
ob_start();
require APP_ROOT . '/templates/auth/login.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
