<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$order = $data['order'] ?? [];

if (empty($order)) {
    echo json_encode(['success' => false, 'message' => 'No order data']);
    exit();
}

try {
    // Ensure display_order column exists
    $pdo->exec("ALTER TABLE categories ADD COLUMN IF NOT EXISTS display_order INT DEFAULT 0");

    $stmt = $pdo->prepare("UPDATE categories SET display_order = ? WHERE category_id = ?");
    foreach ($order as $position => $categoryId) {
        $stmt->execute([$position, $categoryId]);
    }
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
