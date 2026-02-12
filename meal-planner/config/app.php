<?php

define('APP_NAME', 'Meal Planner');
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}
define('TIMEZONE', 'Europe/London');

date_default_timezone_set(TIMEZONE);

define('DIETARY_OPTIONS', [
    'vegetarian'  => 'Vegetarian',
    'vegan'       => 'Vegan',
    'gluten_free' => 'Gluten Free',
    'dairy_free'  => 'Dairy Free',
    'nut_allergy' => 'Nut Allergy',
    'halal'       => 'Halal',
    'kosher'      => 'Kosher',
    'low_carb'    => 'Low Carb',
    'low_sodium'  => 'Low Sodium',
    'pescatarian' => 'Pescatarian',
]);

define('MEAL_SLOTS', ['breakfast', 'lunch', 'dinner']);
define('DAYS_OF_WEEK', [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun']);
