<?php

function redirect(string $page, array $params = []): void
{
    $url = '?page=' . urlencode($page);
    foreach ($params as $k => $v) {
        $url .= '&' . urlencode($k) . '=' . urlencode($v);
    }
    header('Location: ' . $url);
    exit;
}

function flash(string $message, string $type = 'info'): void
{
    $_SESSION['flash'][] = ['message' => $message, 'type' => $type];
}

function get_flashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        die('Invalid CSRF token. Please go back and try again.');
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function week_start(string $date = null): string
{
    $dt = new DateTime($date ?? 'now');
    $dow = (int) $dt->format('N');
    if ($dow > 1) {
        $dt->modify('-' . ($dow - 1) . ' days');
    }
    return $dt->format('Y-m-d');
}

function week_dates(string $weekStart): array
{
    $dates = [];
    $dt = new DateTime($weekStart);
    for ($i = 0; $i < 7; $i++) {
        $dates[$i + 1] = $dt->format('Y-m-d');
        $dt->modify('+1 day');
    }
    return $dates;
}

function prev_week(string $weekStart): string
{
    return (new DateTime($weekStart))->modify('-7 days')->format('Y-m-d');
}

function next_week(string $weekStart): string
{
    return (new DateTime($weekStart))->modify('+7 days')->format('Y-m-d');
}

function format_date(string $date, string $format = 'D j M'): string
{
    return (new DateTime($date))->format($format);
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function current_page(): string
{
    return $_GET['page'] ?? 'dashboard';
}
