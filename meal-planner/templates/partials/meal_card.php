<div class="meal-card <?= !empty($meal['ai_generated']) ? 'ai-generated' : '' ?>">
    <strong><?= e($meal['title']) ?></strong>
    <?php if (!empty($meal['meal_time'])): ?>
        <small><?= e($meal['meal_time']) ?></small>
    <?php endif; ?>
    <?php if (!empty($meal['calories'])): ?>
        <small><?= (int)$meal['calories'] ?> kcal</small>
    <?php endif; ?>
    <?php if (!empty($meal['description'])): ?>
        <p><small><?= e($meal['description']) ?></small></p>
    <?php endif; ?>
</div>
