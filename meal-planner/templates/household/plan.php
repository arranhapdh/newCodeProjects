<div class="week-nav">
    <a href="?page=household-plan&id=<?= $householdId ?>&week=<?= e(prev_week($weekStart)) ?>" role="button" class="outline">&larr; Previous</a>
    <h2><?= e($pageTitle) ?></h2>
    <a href="?page=household-plan&id=<?= $householdId ?>&week=<?= e(next_week($weekStart)) ?>" role="button" class="outline">Next &rarr;</a>
</div>

<div class="ai-actions">
    <a href="?page=ai-suggest&week=<?= e($weekStart) ?>&household_id=<?= $householdId ?>" role="button" class="secondary">AI: Fill Week</a>
    <a href="?page=ai-modify&week=<?= e($weekStart) ?>&household_id=<?= $householdId ?>" role="button" class="outline secondary">AI: Improve Plan</a>
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
                            <?php else: ?>
                                <button class="btn-add outline" onclick="openHouseholdMealModal(<?= $day ?>, '<?= $slot ?>')" title="Add meal">+</button>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Meal Modal for Household -->
<dialog id="householdMealModal">
    <article>
        <header>
            <button aria-label="Close" rel="prev" onclick="closeHouseholdMealModal()"></button>
            <h3>Add Meal</h3>
        </header>
        <form method="post" action="?page=household-save">
            <?= csrf_field() ?>
            <input type="hidden" name="household_id" value="<?= $householdId ?>">
            <input type="hidden" name="week_start" value="<?= e($weekStart) ?>">
            <input type="hidden" name="day" id="hh-modal-day">
            <input type="hidden" name="slot" id="hh-modal-slot">
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
