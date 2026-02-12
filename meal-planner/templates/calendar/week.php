<div class="week-nav">
    <a href="?page=calendar&week=<?= e(prev_week($weekStart)) ?>" role="button" class="outline">&larr; Previous</a>
    <h2><?= e($pageTitle) ?></h2>
    <a href="?page=calendar&week=<?= e(next_week($weekStart)) ?>" role="button" class="outline">Next &rarr;</a>
</div>

<div class="ai-actions">
    <a href="?page=ai-suggest&week=<?= e($weekStart) ?>" role="button" class="secondary">AI: Fill Week</a>
    <a href="?page=ai-modify&week=<?= e($weekStart) ?>" role="button" class="outline secondary">AI: Improve Plan</a>
</div>

<div class="calendar-grid-wrapper">
    <table class="calendar-table" role="grid">
        <thead>
            <tr>
                <th></th>
                <?php foreach (DAYS_OF_WEEK as $day => $label): ?>
                    <th><?= $label ?><br><small><?= format_date($dates[$day], 'j M') ?></small></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach (MEAL_SLOTS as $slot): ?>
                <tr>
                    <th><?= ucfirst($slot) ?></th>
                    <?php foreach (DAYS_OF_WEEK as $day => $label): ?>
                        <td class="meal-cell">
                            <?php if ($meal = $grid[$slot][$day]): ?>
                                <?php require APP_ROOT . '/templates/partials/meal_card.php'; ?>
                                <form method="post" action="?page=delete-meal" class="inline-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="meal_id" value="<?= $meal['id'] ?>">
                                    <input type="hidden" name="week_start" value="<?= e($weekStart) ?>">
                                    <button type="submit" class="btn-delete outline" title="Remove">&times;</button>
                                </form>
                            <?php else: ?>
                                <button class="btn-add outline" onclick="openMealModal(<?= $day ?>, '<?= $slot ?>')" title="Add meal">+</button>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Meal Modal -->
<dialog id="mealModal">
    <article>
        <header>
            <button aria-label="Close" rel="prev" onclick="closeMealModal()"></button>
            <h3>Add Meal</h3>
        </header>
        <form method="post" action="?page=save-meal">
            <?= csrf_field() ?>
            <input type="hidden" name="week_start" value="<?= e($weekStart) ?>">
            <input type="hidden" name="day" id="modal-day">
            <input type="hidden" name="slot" id="modal-slot">
            <label>
                Title
                <input type="text" name="title" required>
            </label>
            <label>
                Description
                <textarea name="description" rows="2"></textarea>
            </label>
            <div class="grid">
                <label>
                    Time
                    <input type="time" name="meal_time" value="12:00">
                </label>
                <label>
                    Calories
                    <input type="number" name="calories" min="0" placeholder="Optional">
                </label>
            </div>
            <button type="submit">Save Meal</button>
        </form>
    </article>
</dialog>
