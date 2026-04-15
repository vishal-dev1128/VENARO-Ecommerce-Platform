<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';

function ensureSessionStarted(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}

function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function setFlash(string $type, string $message): void
{
    ensureSessionStarted();
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function getFlash(): ?array
{
    ensureSessionStarted();

    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function isLoggedIn(): bool
{
    ensureSessionStarted();
    return isset($_SESSION['user_id']);
}

function currentUserName(): string
{
    ensureSessionStarted();
    return (string) ($_SESSION['username'] ?? 'User');
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        setFlash('error', 'Please login to continue.');
        redirect('login.php');
    }
}

function sanitizeMonth(?string $month): string
{
    if ($month !== null && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month) === 1) {
        return $month;
    }

    return date('Y-m');
}

function monthRange(string $month): array
{
    $start = DateTimeImmutable::createFromFormat('Y-m-d', $month . '-01');

    if ($start === false) {
        $start = new DateTimeImmutable('first day of this month');
    }

    $end = $start->modify('last day of this month');

    return [
        $start->format('Y-m-d'),
        $end->format('Y-m-d'),
        (int) $end->format('j'),
    ];
}

function isValidDate(string $date): bool
{
    $parsed = DateTimeImmutable::createFromFormat('Y-m-d', $date);

    return $parsed !== false && $parsed->format('Y-m-d') === $date;
}

function buildSaleData(?string $quantityInput): array
{
    $trimmed = trim((string) $quantityInput);

    if ($trimmed === '') {
        return [
            'valid' => true,
            'quantity' => null,
            'status' => 'No Order',
            'total' => 0,
            'error' => '',
        ];
    }

    if (!ctype_digit($trimmed)) {
        return [
            'valid' => false,
            'quantity' => null,
            'status' => 'No Order',
            'total' => 0,
            'error' => 'Quantity must be a non-negative whole number.',
        ];
    }

    $quantity = (int) $trimmed;

    return [
        'valid' => true,
        'quantity' => $quantity,
        'status' => 'Completed',
        'total' => $quantity * CHAPATI_RATE,
        'error' => '',
    ];
}

function monthFromDate(string $date): string
{
    return substr($date, 0, 7);
}

function formatInr(int $amount): string
{
    return 'INR ' . number_format($amount);
}
