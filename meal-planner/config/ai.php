<?php

define('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages');
define('CLAUDE_MODEL', 'claude-sonnet-4-5-20250929');
define('CLAUDE_MAX_TOKENS', 4096);

function get_claude_api_key(): string
{
    $key = getenv('CLAUDE_API_KEY');
    if (!$key) {
        throw new RuntimeException('CLAUDE_API_KEY environment variable is not set');
    }
    return $key;
}
