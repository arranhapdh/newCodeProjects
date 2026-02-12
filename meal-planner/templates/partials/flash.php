<?php foreach (get_flashes() as $flash): ?>
    <article class="flash-<?= e($flash['type']) ?>">
        <?= e($flash['message']) ?>
    </article>
<?php endforeach; ?>
