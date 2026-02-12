<?php

$user = require_admin();

// Handle add user
if (is_post() && ($_POST['action'] ?? '') === 'add') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if ($username && $email && strlen($password) >= 6) {
        if ($role === 'admin') {
            $result = user_create_admin($username, $email, $password, $username);
        } else {
            $result = register_user($username, $email, $password, $username);
        }
        if ($result) {
            flash('User created.', 'success');
        } else {
            flash('Could not create user. Username or email may be taken.', 'error');
        }
    } else {
        flash('Please fill in all fields (password min 6 chars).', 'error');
    }
    redirect('admin-users');
}

// Handle delete user
if (is_post() && ($_POST['action'] ?? '') === 'delete') {
    verify_csrf();
    $deleteId = (int) ($_POST['user_id'] ?? 0);
    if ($deleteId && $deleteId !== $user['id']) {
        user_delete($deleteId);
        flash('User removed.', 'success');
    } else {
        flash('Cannot delete your own account.', 'error');
    }
    redirect('admin-users');
}

$users = user_all();

$pageTitle = 'User Management';
ob_start();
require APP_ROOT . '/templates/admin/users.php';
$content = ob_get_clean();
require APP_ROOT . '/templates/layout.php';
