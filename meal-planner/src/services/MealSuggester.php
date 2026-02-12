<?php

function meal_suggester_system_prompt(): string
{
    return <<<PROMPT
You are a helpful meal planning assistant. You suggest healthy, balanced meals that respect dietary restrictions.

IMPORTANT RULES:
- All meals must be scheduled between 6:00 AM and 6:00 PM
- Breakfast: typically 7:00-9:00 AM
- Lunch: typically 12:00-1:00 PM
- Dinner: typically 5:00-6:00 PM
- Provide realistic calorie estimates
- Respect ALL dietary restrictions listed

You MUST respond with ONLY valid JSON, no other text. The JSON must be an array of meal objects with these exact fields:
- "day": integer 1-7 (1=Monday, 7=Sunday)
- "slot": string, one of "breakfast", "lunch", "dinner"
- "time": string in "HH:MM" format (24-hour)
- "title": string, the meal name
- "description": string, brief description of the meal
- "calories": integer, estimated calories
PROMPT;
}

function meal_suggester_suggest_week(array $dietaryPrefs, int $userId): array
{
    $prefsText = empty($dietaryPrefs) ? 'No specific dietary restrictions.' : 'Dietary restrictions: ' . implode(', ', array_map(fn($p) => DIETARY_OPTIONS[$p] ?? $p, $dietaryPrefs));

    $userMessage = <<<MSG
Please suggest a complete week of healthy meals (breakfast, lunch, and dinner for Monday through Sunday = 21 meals total).

{$prefsText}

Respond with ONLY a JSON array of 21 meal objects.
MSG;

    $response = claude_send_message(meal_suggester_system_prompt(), $userMessage, $userId);
    return meal_suggester_parse_response($response['text']);
}

function meal_suggester_modify_plan(array $currentMeals, array $dietaryPrefs, string $userRequest, int $userId): array
{
    $prefsText = empty($dietaryPrefs) ? 'No specific dietary restrictions.' : 'Dietary restrictions: ' . implode(', ', array_map(fn($p) => DIETARY_OPTIONS[$p] ?? $p, $dietaryPrefs));

    $mealsJson = json_encode(array_map(fn($m) => [
        'day' => $m['day_of_week'],
        'slot' => $m['slot'],
        'time' => $m['meal_time'],
        'title' => $m['title'],
        'description' => $m['description'],
        'calories' => $m['calories'],
    ], $currentMeals), JSON_PRETTY_PRINT);

    $userMessage = <<<MSG
Here is the current meal plan:
{$mealsJson}

{$prefsText}

User's modification request: {$userRequest}

Please provide the COMPLETE modified meal plan as a JSON array (include ALL meals, not just changed ones). Keep unchanged meals the same.
MSG;

    $response = claude_send_message(meal_suggester_system_prompt(), $userMessage, $userId);
    return meal_suggester_parse_response($response['text']);
}

function meal_suggester_parse_response(string $text): array
{
    // Extract JSON from response (handle markdown code blocks)
    if (preg_match('/\[[\s\S]*\]/', $text, $matches)) {
        $json = $matches[0];
    } else {
        throw new RuntimeException('Could not parse AI response as JSON');
    }

    $meals = json_decode($json, true);
    if (!is_array($meals)) {
        throw new RuntimeException('Invalid JSON in AI response');
    }

    // Validate and normalize
    $valid = [];
    foreach ($meals as $meal) {
        if (empty($meal['day']) || empty($meal['slot']) || empty($meal['title'])) {
            continue;
        }
        $day = (int) $meal['day'];
        $slot = $meal['slot'];
        if ($day < 1 || $day > 7 || !in_array($slot, MEAL_SLOTS)) {
            continue;
        }
        $valid[] = [
            'day' => $day,
            'slot' => $slot,
            'time' => $meal['time'] ?? '12:00',
            'title' => $meal['title'],
            'description' => $meal['description'] ?? '',
            'calories' => (int) ($meal['calories'] ?? 0),
        ];
    }

    return $valid;
}
