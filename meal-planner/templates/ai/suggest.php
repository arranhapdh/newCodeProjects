<h2><?= e($pageTitle) ?></h2>

<?php
$backUrl = $householdId
    ? '?page=household-plan&id=' . $householdId . '&week=' . e($weekStart)
    : '?page=calendar&week=' . e($weekStart);
?>

<p><a href="<?= $backUrl ?>">&larr; Back to Calendar</a></p>

<?php if (!empty($dietaryPrefs)): ?>
    <p><strong>Dietary preferences:</strong>
        <?= e(implode(', ', array_map(fn($p) => DIETARY_OPTIONS[$p] ?? $p, $dietaryPrefs))) ?>
    </p>
<?php else: ?>
    <p><em>No dietary preferences set. <a href="?page=profile">Set them in your profile</a>.</em></p>
<?php endif; ?>

<?php if ($error): ?>
    <article class="flash-error">
        <strong>Error:</strong> <?= e($error) ?>
    </article>
<?php endif; ?>

<?php if ($suggestions): ?>
    <h3>Suggested Meals</h3>
    <div class="calendar-grid-wrapper">
        <table class="calendar-table" role="grid">
            <thead>
                <tr>
                    <th></th>
                    <?php foreach (DAYS_OF_WEEK as $day => $label): ?>
                        <th><?= $label ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $previewGrid = [];
                foreach (MEAL_SLOTS as $slot) {
                    foreach (DAYS_OF_WEEK as $day => $label) {
                        $previewGrid[$slot][$day] = null;
                    }
                }
                foreach ($suggestions as $s) {
                    $previewGrid[$s['slot']][$s['day']] = $s;
                }
                ?>
                <?php foreach (MEAL_SLOTS as $slot): ?>
                    <tr>
                        <th><?= ucfirst($slot) ?></th>
                        <?php foreach (DAYS_OF_WEEK as $day => $label): ?>
                            <td class="meal-cell">
                                <?php if ($s = $previewGrid[$slot][$day]): ?>
                                    <div class="meal-card ai-generated">
                                        <strong><?= e($s['title']) ?></strong>
                                        <?php if (!empty($s['time'])): ?>
                                            <small><?= e($s['time']) ?></small>
                                        <?php endif; ?>
                                        <?php if (!empty($s['calories'])): ?>
                                            <small><?= (int)$s['calories'] ?> kcal</small>
                                        <?php endif; ?>
                                        <?php if (!empty($s['description'])): ?>
                                            <p><small><?= e($s['description']) ?></small></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="ai-actions">
        <form method="post" action="?page=ai-apply" class="inline-form">
            <?= csrf_field() ?>
            <button type="submit" class="primary">Accept & Apply</button>
        </form>
        <a href="<?= $backUrl ?>" role="button" class="outline">Reject</a>
    </div>
<?php else: ?>
    <?php if (current_page() === 'ai-modify'): ?>
        <article>
            <h3>Modify Current Plan</h3>
            <form method="post" action="?page=ai-modify&week=<?= e($weekStart) ?><?= $householdId ? '&household_id=' . $householdId : '' ?>">
                <?= csrf_field() ?>
                <label>
                    How would you like to modify your meal plan?
                    <textarea name="modification_request" rows="3" required placeholder="e.g. Make lunches lighter, add more protein, replace Thursday dinner with something vegetarian..."></textarea>
                </label>
                <button type="submit" id="btn-modify" aria-busy="false">AI: Modify Plan</button>
            </form>
        </article>
    <?php else: ?>
        <form method="post" action="?page=ai-suggest&week=<?= e($weekStart) ?><?= $householdId ? '&household_id=' . $householdId : '' ?>">
            <?= csrf_field() ?>
            <button type="submit" id="btn-suggest" aria-busy="false">Generate Week of Meals</button>
        </form>
    <?php endif; ?>
<?php endif; ?>
