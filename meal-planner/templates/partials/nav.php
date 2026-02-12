<?php $user = current_user(); ?>
<nav class="container-fluid">
    <ul>
        <li><strong><a href="?page=dashboard"><?= APP_NAME ?></a></strong></li>
    </ul>
    <ul>
        <?php if ($user): ?>
            <li><a href="?page=calendar">My Calendar</a></li>
            <li><a href="?page=household">Household</a></li>
            <li><a href="?page=profile">Profile</a></li>
            <?php if ($user['role'] === 'admin'): ?>
                <li><a href="?page=admin-users">Admin</a></li>
            <?php endif; ?>
            <li><a href="?page=logout">Logout (<?= e($user['username']) ?>)</a></li>
        <?php else: ?>
            <li><a href="?page=login">Login</a></li>
            <li><a href="?page=register">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
