<h2>Households</h2>

<div class="grid">
    <article>
        <header>Create Household</header>
        <form method="post" action="?page=household">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">
            <label>
                Household Name
                <input type="text" name="name" required>
            </label>
            <button type="submit">Create</button>
        </form>
    </article>

    <article>
        <header>Join Household</header>
        <form method="post" action="?page=household">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="join">
            <label>
                Invite Code
                <input type="text" name="invite_code" required placeholder="e.g. A1B2C3D4" maxlength="8">
            </label>
            <button type="submit">Join</button>
        </form>
    </article>
</div>

<?php if (empty($households)): ?>
    <p>You are not a member of any households yet.</p>
<?php else: ?>
    <?php foreach ($households as $h): ?>
        <article>
            <header>
                <strong><?= e($h['name']) ?></strong>
                <small>Invite code: <code><?= e($h['invite_code']) ?></code></small>
            </header>

            <h4>Members</h4>
            <table>
                <thead>
                    <tr><th>Name</th><th>Role</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($householdMembers[$h['id']] as $member): ?>
                        <tr>
                            <td><?= e($member['display_name'] ?: $member['username']) ?></td>
                            <td><?= e(ucfirst($member['role'])) ?></td>
                            <td>
                                <?php if ($member['id'] != $user['id'] && $h['created_by'] == $user['id']): ?>
                                    <form method="post" action="?page=household" class="inline-form">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="remove_member">
                                        <input type="hidden" name="household_id" value="<?= $h['id'] ?>">
                                        <input type="hidden" name="member_id" value="<?= $member['id'] ?>">
                                        <button type="submit" class="outline btn-small">Remove</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <footer>
                <a href="?page=household-plan&id=<?= $h['id'] ?>" role="button" class="outline">View Meal Plan</a>
                <?php if ($h['created_by'] != $user['id']): ?>
                    <form method="post" action="?page=household" class="inline-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="leave">
                        <input type="hidden" name="household_id" value="<?= $h['id'] ?>">
                        <button type="submit" class="outline" onclick="return confirm('Leave this household?')">Leave</button>
                    </form>
                <?php endif; ?>
            </footer>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
