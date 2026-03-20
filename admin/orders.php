<?php
session_start();
require_once '../config.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch Orders
$query = "
    SELECT o.*, u.full_name, u.email 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.user_id 
    ORDER BY o.created_at DESC
";
$orders = $pdo->query($query)->fetchAll();

$page_title = 'Orders';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Orders</h1>
    </div>

    <div class="modern-card">
        <div class="modern-card-body">
            <div class="table-responsive">
                <table class="modern-table table" width="100%">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="material-icons" style="font-size: 48px; color: #dee2e6;">shopping_bag</i>
                                        <p class="mt-2 mb-0">No orders found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><span style="font-weight: 600; color: #212529;"><?php echo htmlspecialchars($order['order_number']); ?></span></td>
                                    <td>
                                        <?php if ($order['full_name']): ?>
                                            <div style="font-weight: 500; color: #212529;"><?php echo htmlspecialchars($order['full_name']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                                        <?php else: ?>
                                            <div class="text-muted">Guest</div>
                                            <small class="text-muted"><?php echo htmlspecialchars($order['guest_email']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td><span style="font-weight: 600; color: #212529;"><?php echo format_price($order['total_amount']); ?></span></td>
                                    <td>
                                        <?php
                                        $badge_class = [
                                            'Paid' => 'success',
                                            'Pending' => 'warning',
                                            'Failed' => 'danger',
                                            'Refunded' => 'info'
                                        ];
                                        $class = $badge_class[$order['payment_status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $class; ?>"><?php echo $order['payment_status']; ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = [
                                            'Order Placed' => 'primary',
                                            'Processing' => 'info',
                                            'Shipped' => 'warning',
                                            'Delivered' => 'success',
                                            'Cancelled' => 'danger',
                                            'Returned' => 'secondary'
                                        ];
                                        $class = $status_class[$order['order_status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $class; ?>"><?php echo $order['order_status']; ?></span>
                                    </td>
                                    <td>
                                        <a href="order-detail.php?id=<?php echo $order['order_id']; ?>"
                                            class="action-btn action-btn-primary" title="View Details">
                                            <i class="material-icons" style="font-size: 16px;">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>