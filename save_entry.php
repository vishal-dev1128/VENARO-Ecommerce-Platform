<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$month = sanitizeMonth($_POST['month'] ?? null);
$saleDate = trim((string) ($_POST['sale_date'] ?? ''));

if (!isValidDate($saleDate)) {
    setFlash('error', 'Invalid date. Please select a valid date.');
    redirect('index.php?month=' . urlencode($month));
}

$saleData = buildSaleData($_POST['quantity'] ?? null);

if ($saleData['valid'] !== true) {
    setFlash('error', (string) $saleData['error']);
    redirect('index.php?month=' . urlencode($month));
}

$stmt = $pdo->prepare(
    'INSERT INTO chapati_sales (sale_date, quantity, rate, total, status)
     VALUES (:sale_date, :quantity, :rate, :total, :status)
     ON DUPLICATE KEY UPDATE
        quantity = VALUES(quantity),
        rate = VALUES(rate),
        total = VALUES(total),
        status = VALUES(status),
        updated_at = CURRENT_TIMESTAMP'
);

$stmt->bindValue(':sale_date', $saleDate);

if ($saleData['quantity'] === null) {
    $stmt->bindValue(':quantity', null, PDO::PARAM_NULL);
} else {
    $stmt->bindValue(':quantity', (int) $saleData['quantity'], PDO::PARAM_INT);
}

$stmt->bindValue(':rate', CHAPATI_RATE, PDO::PARAM_INT);
$stmt->bindValue(':total', (int) $saleData['total'], PDO::PARAM_INT);
$stmt->bindValue(':status', (string) $saleData['status']);
$stmt->execute();

setFlash('success', 'Entry saved successfully.');
redirect('index.php?month=' . urlencode(monthFromDate($saleDate)));
