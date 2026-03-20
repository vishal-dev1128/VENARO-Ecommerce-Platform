<?php
header('Content-Type: application/json');
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$product_id = intval($_POST['product_id'] ?? 0);
$variant_id = intval($_POST['variant_id'] ?? 0) ?: null;
$quantity = max(1, intval($_POST['quantity'] ?? 1));

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Product ID required']);
    exit;
}

// Check if product exists and is active
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ? AND status = 'Active'");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Check stock
if ($product['track_inventory']) {
    if ($variant_id) {
        $stmt = $pdo->prepare("SELECT stock_quantity FROM product_variants WHERE variant_id = ? AND status = 'Active'");
        $stmt->execute([$variant_id]);
        $variant = $stmt->fetch();
        if (!$variant || $variant['stock_quantity'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
            exit;
        }
    } else {
        if ($product['stock_quantity'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
            exit;
        }
    }
}

try {
    if (is_logged_in()) {
        $user_id = get_current_user_id();
        
        // Check if item already in cart
        $stmt = $pdo->prepare("
            SELECT cart_id, quantity FROM cart 
            WHERE user_id = ? AND product_id = ? AND (variant_id = ? OR (variant_id IS NULL AND ? IS NULL))
        ");
        $stmt->execute([$user_id, $product_id, $variant_id, $variant_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update quantity
            $new_quantity = $existing['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE cart_id = ?");
            $stmt->execute([$new_quantity, $existing['cart_id']]);
        } else {
            // Insert new item
            $stmt = $pdo->prepare("
                INSERT INTO cart (user_id, product_id, variant_id, quantity, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $product_id, $variant_id, $quantity]);
        }
    } else {
        $session_id = get_session_id();
        
        // Check if item already in cart
        $stmt = $pdo->prepare("
            SELECT cart_id, quantity FROM cart 
            WHERE session_id = ? AND product_id = ? AND (variant_id = ? OR (variant_id IS NULL AND ? IS NULL))
        ");
        $stmt->execute([$session_id, $product_id, $variant_id, $variant_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update quantity
            $new_quantity = $existing['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE cart_id = ?");
            $stmt->execute([$new_quantity, $existing['cart_id']]);
        } else {
            // Insert new item
            $stmt = $pdo->prepare("
                INSERT INTO cart (session_id, product_id, variant_id, quantity, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$session_id, $product_id, $variant_id, $quantity]);
        }
    }
    
    // Get cart count
    if (is_logged_in()) {
        $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt->execute([get_current_user_id()]);
    } else {
        $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE session_id = ?");
        $stmt->execute([get_session_id()]);
    }
    $cart_count = $stmt->fetchColumn() ?? 0;
    
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart',
        'cart_count' => $cart_count
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
