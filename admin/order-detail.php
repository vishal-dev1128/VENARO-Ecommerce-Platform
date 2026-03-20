<?php
session_start();
require_once '../config.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit();
}

$order_id = $_GET['id'];
$success = '';
$error = '';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['order_status'];
    $payment_status = $_POST['payment_status'];

    try {
        $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, payment_status = ?, updated_at = NOW() WHERE order_id = ?");
        $stmt->execute([$new_status, $payment_status, $order_id]);
        $success = "Order status updated successfully.";
    } catch (PDOException $e) {
        $error = "Update failed: " . $e->getMessage();
    }
}

// Fetch Order Details
try {
    // Order Info with Address Data
    $stmt = $pdo->prepare("
        SELECT o.*, u.full_name, u.email, u.phone,
               sa.address_line1 as shipping_address_line1,
               sa.address_line2 as shipping_address_line2,
               sa.city as shipping_city,
               sa.state as shipping_state,
               sa.postal_code as shipping_postal_code,
               sa.country as shipping_country,
               ba.address_line1 as billing_address_line1,
               ba.address_line2 as billing_address_line2,
               ba.city as billing_city,
               ba.state as billing_state,
               ba.postal_code as billing_postal_code,
               ba.country as billing_country
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.user_id
        LEFT JOIN addresses sa ON o.shipping_address_id = sa.address_id
        LEFT JOIN addresses ba ON o.billing_address_id = ba.address_id
        WHERE o.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    if (!$order) {
        header('Location: orders.php');
        exit();
    }

    // Order Items
    $stmt = $pdo->prepare("
        SELECT oi.*, p.product_name, p.sku,
               (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = TRUE LIMIT 1) as product_image
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $items = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

$page_title = 'Order #' . $order['order_number'];
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Order Details: <?php echo htmlspecialchars($order['order_number']); ?></h1>
        <a href="orders.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="material-icons" style="font-size: 18px;">arrow_back</i> Back to Orders
        </a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">Order Items</h5>
                    <div class="table-responsive">
                        <table class="modern-table table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($item['product_image']): ?>
                                                    <img src="<?php echo UPLOADS_URL . '/products/' . $item['product_image']; ?>"
                                                        class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0" style="font-weight: 600;"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                                    <small class="text-muted">SKU: <?php echo htmlspecialchars($item['sku']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo format_price($item['unit_price'] ?? 0); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><span style="font-weight: 600;"><?php echo format_price(($item['unit_price'] ?? 0) * $item['quantity']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end py-2">Subtotal</td>
                                    <td class="py-2"><?php echo format_price($order['subtotal']); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end py-2">Tax</td>
                                    <td class="py-2"><?php echo format_price($order['tax_amount'] ?? 0); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end py-2">Shipping</td>
                                    <td class="py-2"><?php echo format_price($order['shipping_charge'] ?? 0); ?></td>
                                </tr>
                                <tr style="border-top: 2px solid #eee;">
                                    <td colspan="3" class="text-end py-3 pt-4"><strong class="h5">Grand Total</strong></td>
                                    <td class="py-3 pt-4"><strong class="h5 text-primary"><?php echo format_price($order['total_amount']); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">Shipping & Billing</h5>
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h6 class="text-muted small text-uppercase fw-bold mb-3">Shipping Address</h6>
                            <p class="mb-0" style="color: #444; line-height: 1.6;">
                                <?php echo htmlspecialchars($order['shipping_address_line1'] ?? 'N/A'); ?><br>
                                <?php if (!empty($order['shipping_address_line2'])) echo htmlspecialchars($order['shipping_address_line2']) . '<br>'; ?>
                                <?php echo htmlspecialchars($order['shipping_city'] ?? ''); ?>, <?php echo htmlspecialchars($order['shipping_state'] ?? ''); ?> <?php echo htmlspecialchars($order['shipping_postal_code'] ?? ''); ?><br>
                                <strong><?php echo htmlspecialchars($order['shipping_country'] ?? 'India'); ?></strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small text-uppercase fw-bold mb-3">Billing Address</h6>
                            <?php if (!empty($order['billing_address_line1'])): ?>
                                <p class="mb-0" style="color: #444; line-height: 1.6;">
                                    <?php echo htmlspecialchars($order['billing_address_line1']); ?><br>
                                    <?php if (!empty($order['billing_address_line2'])) echo htmlspecialchars($order['billing_address_line2']) . '<br>'; ?>
                                    <?php echo htmlspecialchars($order['billing_city'] ?? ''); ?>, <?php echo htmlspecialchars($order['billing_state'] ?? ''); ?> <?php echo htmlspecialchars($order['billing_postal_code'] ?? ''); ?><br>
                                    <strong><?php echo htmlspecialchars($order['billing_country'] ?? 'India'); ?></strong>
                                </p>
                            <?php else: ?>
                                <p class="text-muted italic">Same as shipping address</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Status & Actions -->
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">Order Status</h5>
                    <form method="POST">
                        <input type="hidden" name="update_status" value="1">

                        <div class="mb-3">
                            <label class="form-label">Order Status</label>
                            <select class="form-select" name="order_status">
                                <?php
                                $statuses = ['Order Placed', 'Processing', 'Shipped', 'Delivered', 'Cancelled', 'Returned'];
                                foreach ($statuses as $status) {
                                    $selected = ($status == $order['order_status']) ? 'selected' : '';
                                    echo "<option value='$status' $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Payment Status</label>
                            <select class="form-select" name="payment_status">
                                <?php
                                $p_statuses = ['Pending', 'Paid', 'Failed', 'Refunded'];
                                foreach ($p_statuses as $status) {
                                    $selected = ($status == $order['payment_status']) ? 'selected' : '';
                                    echo "<option value='$status' $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">Update Status</button>
                    </form>

                    <hr class="my-4">

                    <div class="customer-info-box">
                        <h6 class="text-muted small text-uppercase fw-bold mb-3">Customer Information</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="material-icons text-muted">person</i>
                            </div>
                            <div>
                                <?php if (!empty($order['full_name'])): ?>
                                    <div style="font-weight: 600; color: #212529;"><?php echo htmlspecialchars($order['full_name']); ?></div>
                                    <div class="small"><a href="mailto:<?php echo htmlspecialchars($order['email'] ?? ''); ?>" class="text-decoration-none"><?php echo htmlspecialchars($order['email'] ?? ''); ?></a></div>
                                <?php else: ?>
                                    <div style="font-weight: 600; color: #212529;">Guest Checkout</div>
                                    <div class="small"><a href="mailto:<?php echo htmlspecialchars($order['guest_email'] ?? ''); ?>" class="text-decoration-none"><?php echo htmlspecialchars($order['guest_email'] ?? ''); ?></a></div>
                                <?php endif; ?>
                                <div class="small text-muted mt-1"><?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>