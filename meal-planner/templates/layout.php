<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php require APP_ROOT . '/templates/partials/nav.php'; ?>

    <main class="container">
        <?php require APP_ROOT . '/templates/partials/flash.php'; ?>
        <?= $content ?>
    </main>

    <footer class="container">
        <small>&copy; <?= date('Y') ?> <?= APP_NAME ?></small>
    </footer>

    <script src="assets/js/app.js"></script>
</body>
</html>
