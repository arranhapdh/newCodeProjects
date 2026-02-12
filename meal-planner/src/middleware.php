<?php

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        flash('Please log in to continue.', 'warning');
        redirect('login');
    }
    return $user;
}

function require_admin(): array
{
    $user = require_login();
    if ($user['role'] !== 'admin') {
        flash('Access denied.', 'error');
        redirect('dashboard');
    }
    return $user;
}
