<?php
header('Content-Type: application/json');
require_once '../config.php';

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$product_id = intval($_POST['product_id'] ?? 0);

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Product ID required']);
    exit;
}

$user_id = get_current_user_id();

try {
    // Check if already in wishlist
    $stmt = $pdo->prepare("SELECT wishlist_id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Remove from wishlist
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE wishlist_id = ?");
        $stmt->execute([$existing['wishlist_id']]);
        $action = 'removed';
    } else {
        // Add to wishlist
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $product_id]);
        $action = 'added';
    }
    
    // Get wishlist count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $wishlist_count = $stmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'action' => $action,
        'wishlist_count' => $wishlist_count
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
