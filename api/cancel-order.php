<?php
require_once '../config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$order_id = isset($data['order_id']) ? intval($data['order_id']) : 0;

if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    exit();
}

try {
    // Verify order belongs to user
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT order_id, order_status FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch();

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit();
    }

    // Check if order can be cancelled
    $cancellable_statuses = ['Order Placed', 'Processing'];
    if (!in_array($order['order_status'], $cancellable_statuses)) {
        echo json_encode(['success' => false, 'message' => 'This order cannot be cancelled as it is already ' . $order['order_status']]);
        exit();
    }

    // Begin transaction
    $pdo->beginTransaction();

    // Update order status
    $updateStmt = $pdo->prepare("UPDATE orders SET order_status = 'Cancelled', updated_at = NOW() WHERE order_id = ?");
    $updateStmt->execute([$order_id]);

    // Log status change in history
    $historyStmt = $pdo->prepare("INSERT INTO order_status_history (order_id, status, notes, created_by) VALUES (?, 'Cancelled', 'Cancelled by user', ?)");
    $historyStmt->execute([$order_id, $user_id]);

    // Commit transaction
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Order Cancellation Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your request']);
}
