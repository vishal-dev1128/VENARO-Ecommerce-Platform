<?php
require_once '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$code     = strtoupper(trim($_POST['coupon_code'] ?? ''));
$subtotal = floatval($_POST['subtotal'] ?? 0);

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a coupon code.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT * FROM coupons
        WHERE coupon_code = ?
          AND status = 'Active'
          AND (expiry_date IS NULL OR expiry_date >= CURDATE())
        LIMIT 1
    ");
    $stmt->execute([$code]);
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$coupon) {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired coupon code.']);
        exit;
    }

    // Calculate discount
    $discount = 0;
    if ($coupon['discount_type'] === 'Percentage') {
        $discount = round($subtotal * ($coupon['discount_value'] / 100), 2);
    } elseif ($coupon['discount_type'] === 'Flat') {
        $discount = min(floatval($coupon['discount_value']), $subtotal);
    } elseif ($coupon['discount_type'] === 'Free Shipping') {
        $discount = 0; // Shipping is already free; acknowledge it
    }

    echo json_encode([
        'success'        => true,
        'message'        => 'Coupon applied successfully!',
        'discount_type'  => $coupon['discount_type'],
        'discount_value' => $coupon['discount_value'],
        'discount_amount' => $discount,
        'coupon_code'    => $coupon['coupon_code'],
        'new_total'      => max(0, $subtotal - $discount),
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again.']);
}
