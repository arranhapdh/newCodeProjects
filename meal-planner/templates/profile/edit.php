<article>
    <h2>Edit Profile</h2>
    <form method="post" action="?page=profile">
        <?= csrf_field() ?>
        <label>
            Username
            <input type="text" value="<?= e($user['username']) ?>" disabled>
        </label>
        <label>
            Display Name
            <input type="text" name="display_name" value="<?= e($user['display_name']) ?>">
        </label>
        <label>
            Email
            <input type="email" name="email" value="<?= e($user['email']) ?>" required>
        </label>

        <fieldset>
            <legend>Dietary Preferences</legend>
            <?php foreach (DIETARY_OPTIONS as $key => $label): ?>
                <label>
                    <input type="checkbox" name="dietary[]" value="<?= e($key) ?>"
                        <?= in_array($key, $dietaryPrefs) ? 'checked' : '' ?>>
                    <?= e($label) ?>
                </label>
            <?php endforeach; ?>
        </fieldset>

        <button type="submit">Save Changes</button>
    </form>
</article>
