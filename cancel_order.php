<?php
require_once 'config.php';

// Check login
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch order and verify ownership
$stmt = $pdo->prepare("SELECT order_id, order_status FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    $_SESSION['error'] = "Order not found or unauthorized.";
    header('Location: orders.php');
    exit();
}

// Check if order is cancellable
if ($order['order_status'] !== 'Order Placed') {
    $_SESSION['error'] = "Only orders that have not been processed can be cancelled.";
    header('Location: orders.php');
    exit();
}

// Perform cancellation
try {
    $pdo->beginTransaction();

    // Update order status
    $stmt = $pdo->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ?");
    $stmt->execute([$order_id]);

    // Optional: Return stock if needed (depending on business logic)
    // For now, satisfy the user request of "cancel order"
    
    $pdo->commit();
    $_SESSION['success'] = "Order #{$order_id} has been successfully cancelled.";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "An error occurred while cancelling your order.";
}

header('Location: orders.php');
exit();
