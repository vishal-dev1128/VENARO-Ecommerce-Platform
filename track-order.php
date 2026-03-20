<?php
require_once 'config.php';

$order_status = null;
$error = null;
$order_id_param = $_GET['order_id'] ?? '';

if (!empty($order_id_param)) {
    $order_id_param = sanitize_input($order_id_param);
    try {
        // 1. Search by order_number or internal order_id
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? OR order_id = ?");
        $stmt->execute([$order_id_param, $order_id_param]);
        $order = $stmt->fetch();

        if (!$order) {
            // 2. Try matching without prefixes (like ORD- or VEN-)
            $clean_id = preg_replace('/^(ORD|VEN)-/i', '', $order_id_param);
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number LIKE ? OR order_id = ?");
            $stmt->execute(["%$clean_id%", $clean_id]);
            $order = $stmt->fetch();
        }

        if ($order) {
            $order_status = $order;
        } else {
            $error = "Order #$order_id_param not found. Please verify your Order ID / Number.";
        }
    } catch (PDOException $e) {
        $error = "System error. Please try again later.";
    }
}

$page_title = 'Track Order';
include 'includes/header.php';
?>

<div class="container my-5" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="text-center mb-5">
                <h1 class="display-5 mb-2" style="font-family: var(--font-brand); font-weight: 700; letter-spacing: 2px;">TRACK ORDER</h1>
                <p class="text-muted text-uppercase small" style="letter-spacing: 1px;">Enter your Order ID to see real-time updates</p>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="background: #fcfcfc; border-radius: 0;">
                <div class="card-body p-4 p-md-5">
                    <form method="GET" action="track-order.php">
                        <div class="mb-4">
                            <label for="order_id" class="form-label text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 2px;">Order ID</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg rounded-0 border-dark" id="order_id" name="order_id" placeholder="e.g. ORD-123456789" value="<?php echo htmlspecialchars($order_id_param); ?>" required>
                                <button type="submit" class="btn btn-dark rounded-0 px-4">TRACK</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($order_status): ?>
                <div class="card border-0 shadow-lg mt-5" style="border-radius: 0; background: #fff;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1" style="font-family: var(--font-brand); font-weight: 700;">Order Details</h5>
                                <p class="text-muted small mb-0">#<?php echo htmlspecialchars($order_status['order_number']); ?></p>
                            </div>
                            <div class="text-end">
                                <?php
                                $status_badge = match ($order_status['order_status']) {
                                    'Delivered' => 'success',
                                    'Cancelled' => 'danger',
                                    'Shipped' => 'info',
                                    'Processing' => 'primary',
                                    'Order Placed' => 'dark',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $status_badge; ?> rounded-0 px-3 py-2 text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem;"><?php echo htmlspecialchars($order_status['order_status']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Order Date</p>
                                <p class="mb-0 fw-medium"><?php echo date('F d, Y', strtotime($order_status['created_at'])); ?></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Est. Delivery</p>
                                <?php $est_delivery = date('F d, Y', strtotime($order_status['created_at'] . ' + 6 days')); ?>
                                <p class="mb-0 fw-medium"><?php echo $est_delivery; ?> <small class="text-muted">(5-6 days)</small></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Total Amount</p>
                                <p class="mb-0 fw-bold h5 font-brand"><?php echo format_price($order_status['total_amount']); ?></p>
                            </div>

                            <?php if (!empty($order_status['tracking_number'])): ?>
                                <div class="col-12 mt-4 pt-3 border-top">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 bg-light p-3 border border-dark">
                                            <i class="material-icons" style="font-size: 24px;">local_shipping</i>
                                        </div>
                                        <div class="ms-3">
                                            <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Tracking Information</p>
                                            <p class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($order_status['tracking_number']); ?> (<?php echo htmlspecialchars($order_status['carrier'] ?? 'Standard Shipping'); ?>)</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-12 mt-4 pt-4 border-top text-center">
                                <a href="invoice.php?id=<?php echo $order_status['order_id']; ?>&track=<?php echo urlencode($order_status['order_number']); ?>" class="btn btn-outline-dark rounded-0 px-4 text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.8rem;">
                                    View Detailed Invoice <i class="material-icons" style="font-size: 16px; margin-left: 5px; vertical-align: middle;">open_in_new</i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Simple Roadmap/Progress UI -->
                <div class="mt-4 px-2">
                    <div class="d-flex justify-content-between position-relative mb-4" style="z-index: 1;">
                        <div class="progress position-absolute w-100" style="height: 2px; top: 15px; left: 0; z-index: -1;">
                            <?php
                            $progress = match ($order_status['order_status']) {
                                'Order Placed' => 10,
                                'Processing' => 40,
                                'Shipped' => 70,
                                'Delivered' => 100,
                                'Cancelled' => 0,
                                default => 10
                            };
                            ?>
                            <div class="progress-bar bg-dark" style="width: <?php echo $progress; ?>%"></div>
                        </div>
                        <div class="text-center">
                            <div class="rounded-circle bg-dark text-white mx-auto d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="material-icons" style="font-size: 16px;">receipt</i>
                            </div>
                            <span class="small d-block mt-1 fw-bold">Placed</span>
                        </div>
                        <div class="text-center">
                            <div class="rounded-circle <?php echo in_array($order_status['order_status'], ['Processing', 'Shipped', 'Delivered']) ? 'bg-dark text-white' : 'bg-white border text-muted'; ?> mx-auto d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="material-icons" style="font-size: 16px;">inventory_2</i>
                            </div>
                            <span class="small d-block mt-1 <?php echo in_array($order_status['order_status'], ['Processing', 'Shipped', 'Delivered']) ? 'fw-bold' : 'text-muted'; ?>">Processing</span>
                        </div>
                        <div class="text-center">
                            <div class="rounded-circle <?php echo in_array($order_status['order_status'], ['Shipped', 'Delivered']) ? 'bg-dark text-white' : 'bg-white border text-muted'; ?> mx-auto d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="material-icons" style="font-size: 16px;">local_shipping</i>
                            </div>
                            <span class="small d-block mt-1 <?php echo in_array($order_status['order_status'], ['Shipped', 'Delivered']) ? 'fw-bold' : 'text-muted'; ?>">Shipped</span>
                        </div>
                        <div class="text-center">
                            <div class="rounded-circle <?php echo ($order_status['order_status'] == 'Delivered') ? 'bg-dark text-white' : 'bg-white border text-muted'; ?> mx-auto d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="material-icons" style="font-size: 16px;">check_circle</i>
                            </div>
                            <span class="small d-block mt-1 <?php echo ($order_status['order_status'] == 'Delivered') ? 'fw-bold' : 'text-muted'; ?>">Delivered</span>
                        </div>
                    </div>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger mt-4 text-center rounded-0 border-0 shadow-sm" style="border-left: 4px solid #dc3545 !important;">
                    <i class="material-icons me-2" style="vertical-align: middle;">error_outline</i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>