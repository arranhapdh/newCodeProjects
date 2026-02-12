<h2>User Management</h2>

<article>
    <header>Add User</header>
    <form method="post" action="?page=admin-users">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="add">
        <div class="grid">
            <label>
                Username
                <input type="text" name="username" required>
            </label>
            <label>
                Email
                <input type="email" name="email" required>
            </label>
            <label>
                Password
                <input type="password" name="password" required minlength="6">
            </label>
            <label>
                Role
                <select name="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </label>
        </div>
        <button type="submit">Add User</button>
    </form>
</article>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Display Name</th>
            <th>Role</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= e($u['username']) ?></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['display_name']) ?></td>
                <td><?= e(ucfirst($u['role'])) ?></td>
                <td><?= e($u['created_at']) ?></td>
                <td>
                    <?php if ($u['id'] !== $user['id']): ?>
                        <form method="post" action="?page=admin-users" class="inline-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button type="submit" class="outline" onclick="return confirm('Delete this user?')">Delete</button>
                        </form>
                    <?php else: ?>
                        <em>You</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
