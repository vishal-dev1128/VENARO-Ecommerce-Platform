<?php
require_once '../config.php';

header('Content-Type: application/json');

$query = $_GET['search'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT product_id, product_name, slug 
        FROM products 
        WHERE status = 'Active' 
        AND (product_name LIKE ? OR short_description LIKE ?)
        LIMIT 5
    ");

    $searchTerm = "%{$query}%";
    $stmt->execute([$searchTerm, $searchTerm]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
