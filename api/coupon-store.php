<?php
require_once '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'apply_coupon') {
    $_SESSION['applied_coupon'] = [
        'coupon_code'    => strtoupper(trim($_POST['coupon_code'] ?? '')),
        'discount_type'  => $_POST['discount_type'] ?? '',
        'discount_value' => floatval($_POST['discount_value'] ?? 0),
    ];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
