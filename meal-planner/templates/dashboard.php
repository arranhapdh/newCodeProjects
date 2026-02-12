<h2>Welcome, <?= e($user['display_name'] ?: $user['username']) ?>!</h2>

<div class="grid">
    <article>
        <header>My Meal Plan</header>
        <p>View and manage your personal weekly meal plan.</p>
        <a href="?page=calendar&week=<?= e($weekStart) ?>" role="button">Open Calendar</a>
    </article>

    <article>
        <header>AI Suggestions</header>
        <p>Let AI fill your week with healthy meals based on your dietary preferences.</p>
        <a href="?page=ai-suggest&week=<?= e($weekStart) ?>" role="button" class="secondary">AI: Fill Week</a>
    </article>
</div>

<?php if (!empty($households)): ?>
    <h3>My Households</h3>
    <div class="grid">
        <?php foreach ($households as $h): ?>
            <article>
                <header><?= e($h['name']) ?></header>
                <p>Role: <?= e(ucfirst($h['member_role'])) ?></p>
                <a href="?page=household-plan&id=<?= $h['id'] ?>&week=<?= e($weekStart) ?>" role="button" class="outline">View Plan</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<p><a href="?page=household">Manage Households</a> | <a href="?page=profile">Edit Profile & Dietary Preferences</a></p>
