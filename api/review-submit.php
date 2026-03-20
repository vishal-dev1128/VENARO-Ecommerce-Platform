<?php
require_once '../config.php';

header('Content-Type: application/json');

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

// Must be logged in
if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Please log in to submit a review.']);
    exit();
}

$user_id = get_current_user_id();
$product_id = intval($_POST['product_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$review_title = trim($_POST['review_title'] ?? '');
$review_text = trim($_POST['review_text'] ?? '');

// Validate inputs
if (!$product_id || $rating < 1 || $rating > 5 || empty($review_text)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields (rating and review text).']);
    exit();
}

// Check product exists
$stmt = $pdo->prepare("SELECT product_id FROM products WHERE product_id = ? AND status = 'Active'");
$stmt->execute([$product_id]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit();
}

// Check duplicate review
$stmt = $pdo->prepare("SELECT review_id FROM reviews WHERE product_id = ? AND user_id = ?");
$stmt->execute([$product_id, $user_id]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'You have already reviewed this product.']);
    exit();
}

// Check if user has a delivered order containing this product (verified purchase)
$stmt = $pdo->prepare("
    SELECT o.order_id 
    FROM orders o 
    JOIN order_items oi ON o.order_id = oi.order_id 
    WHERE o.user_id = ? AND oi.product_id = ? AND o.order_status = 'Delivered'
    LIMIT 1
");
$stmt->execute([$user_id, $product_id]);
$order = $stmt->fetch();
$verified_purchase = $order ? true : false;
$order_id = $order ? $order['order_id'] : null;

// Insert review
$stmt = $pdo->prepare("
    INSERT INTO reviews (product_id, user_id, order_id, rating, review_title, review_text, verified_purchase, status, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())
");
$stmt->execute([
    $product_id,
    $user_id,
    $order_id,
    $rating,
    $review_title,
    $review_text,
    $verified_purchase ? 1 : 0
]);

echo json_encode([
    'success' => true, 
    'message' => 'Thank you! Your review has been submitted and is pending approval.'
]);
