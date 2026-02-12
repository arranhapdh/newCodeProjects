<?php

function claude_send_message(string $systemPrompt, string $userMessage, int $userId): array
{
    $apiKey = get_claude_api_key();

    $payload = [
        'model' => CLAUDE_MODEL,
        'max_tokens' => CLAUDE_MAX_TOKENS,
        'system' => $systemPrompt,
        'messages' => [
            ['role' => 'user', 'content' => $userMessage],
        ],
    ];

    $ch = curl_init(CLAUDE_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01',
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        throw new RuntimeException('Claude API request failed: ' . $error);
    }

    $data = json_decode($response, true);

    if ($httpCode !== 200) {
        $errMsg = $data['error']['message'] ?? 'Unknown error';
        throw new RuntimeException('Claude API error (' . $httpCode . '): ' . $errMsg);
    }

    // Log the request
    $db = get_db();
    $db->prepare(
        'INSERT INTO ai_requests (user_id, request_type, prompt_summary, tokens_in, tokens_out) VALUES (?, ?, ?, ?, ?)'
    )->execute([
        $userId,
        'meal_suggestion',
        mb_substr($userMessage, 0, 200),
        $data['usage']['input_tokens'] ?? 0,
        $data['usage']['output_tokens'] ?? 0,
    ]);

    $text = $data['content'][0]['text'] ?? '';
    return ['text' => $text, 'usage' => $data['usage'] ?? []];
}
