<?php

function get_db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dbPath = APP_ROOT . '/database/meal_planner.db';
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA journal_mode = WAL');
        $pdo->exec('PRAGMA foreign_keys = ON');
    }
    return $pdo;
}
