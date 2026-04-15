<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$entryId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$month = sanitizeMonth($_POST['month'] ?? null);

if ($entryId === false || $entryId === null) {
    setFlash('error', 'Invalid entry id.');
    redirect('index.php?month=' . urlencode($month));
}

$deleteStmt = $pdo->prepare('DELETE FROM chapati_sales WHERE id = :id');
$deleteStmt->execute(['id' => $entryId]);

if ($deleteStmt->rowCount() > 0) {
    setFlash('success', 'Entry deleted successfully.');
} else {
    setFlash('error', 'Entry not found or already removed.');
}

redirect('index.php?month=' . urlencode($month));
