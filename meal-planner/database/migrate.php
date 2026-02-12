<?php
/**
 * CLI script: creates/resets the SQLite database from schema.sql
 * Usage: php database/migrate.php [--seed]
 */

define('APP_ROOT', dirname(__DIR__));
require APP_ROOT . '/config/app.php';
require APP_ROOT . '/config/database.php';

$dbPath = APP_ROOT . '/database/meal_planner.db';

echo "Meal Planner Database Migration\n";
echo "================================\n";

$db = get_db();

// Run schema
$schema = file_get_contents(APP_ROOT . '/database/schema.sql');
$db->exec($schema);
echo "Schema applied successfully.\n";

// Seed if requested
if (in_array('--seed', $argv ?? [])) {
    $seed = file_get_contents(APP_ROOT . '/database/seed.sql');
    $db->exec($seed);
    echo "Seed data inserted. Default admin: admin / admin123\n";
}

echo "Database ready at: {$dbPath}\n";
