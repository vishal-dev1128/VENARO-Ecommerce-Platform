<?php
require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php?redirect=orders.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user
$stmt_user = $pdo->prepare("SELECT full_name, email, profile_photo FROM users WHERE user_id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch();

// Stats
$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt_total->execute([$user_id]);
$total_orders = $stmt_total->fetchColumn();

$stmt_wishlist = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
$stmt_wishlist->execute([$user_id]);
$total_wishlist = $stmt_wishlist->fetchColumn();

// Fetch Orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

$name_parts = explode(' ', $user['full_name']);
$initials = strtoupper(substr($name_parts[0], 0, 1) . (isset($name_parts[1]) ? substr($name_parts[1], 0, 1) : ''));

$page_title = 'My Orders';
include 'includes/header.php';
?>

<link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/account-dashboard.css">

<div class="vn-dash-wrapper">

    <!-- SIDEBAR -->
    <aside class="vn-sidebar" id="dashSidebar">
        <div class="vn-sidebar-brand">
            <h2>VÉNARO</h2>
            <span>My Account</span>
        </div>
        <div class="vn-sidebar-user">
            <div class="vn-sidebar-avatar">
                <?php if (!empty($user['profile_photo'])): ?>
                    <img src="<?php echo UPLOADS_URL . '/profiles/' . $user['profile_photo']; ?>" alt="">
                <?php else: ?>
                    <?php echo $initials; ?>
                <?php endif; ?>
            </div>
            <div class="vn-sidebar-user-info">
                <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
                <span>Active</span>
            </div>
        </div>
        <div class="vn-sidebar-label">Main</div>
        <ul class="vn-sidebar-nav">
            <li><a href="profile.php"><i class="material-icons">dashboard</i> Dashboard</a></li>
        </ul>
        <div class="vn-sidebar-label">Shopping</div>
        <ul class="vn-sidebar-nav">
            <li><a href="orders.php" class="active"><i class="material-icons">shopping_bag</i> My Orders</a></li>
            <li><a href="wishlist.php"><i class="material-icons">favorite_border</i> Wishlist</a></li>
        </ul>
        <div class="vn-sidebar-label">Account</div>
        <ul class="vn-sidebar-nav">
            <li><a href="profile.php"><i class="material-icons">person_outline</i> My Profile</a></li>
        </ul>
        <div class="vn-sidebar-footer">
            <a href="logout.php"><i class="material-icons">logout</i> Logout</a>
        </div>
    </aside>

    <div class="vn-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- MAIN -->
    <main class="vn-dash-main">
        <div class="vn-dash-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <button class="vn-mobile-toggle" onclick="toggleSidebar()"><i class="material-icons">menu</i></button>
                <div class="vn-dash-header-left">
                    <h1>My Orders</h1>
                    <p>Dashboard · My Orders</p>
                </div>
            </div>
            <div class="vn-dash-header-right">
                <a href="index.php" class="vn-header-icon" title="Store"><i class="material-icons">storefront</i></a>
                <div class="vn-header-user">
                    <div class="vn-header-user-avatar"><?php echo $initials; ?></div>
                    <span class="vn-header-user-name"><?php echo htmlspecialchars($user['full_name']); ?></span>
                </div>
            </div>
        </div>

        <div class="vn-dash-content">
            <div class="vn-dash-title-row">
                <div>
                    <h2 class="vn-dash-page-title">Order History</h2>
                    <p class="vn-dash-page-subtitle"><?php echo $total_orders; ?> total orders</p>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="vn-alert vn-alert-success"><i class="material-icons" style="font-size:18px;">check_circle</i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="vn-alert vn-alert-error"><i class="material-icons" style="font-size:18px;">error</i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <?php if (empty($orders)): ?>
                <div class="vn-empty-state">
                    <i class="material-icons">shopping_bag</i>
                    <h3>No orders yet</h3>
                    <p>Your order history will appear here once you make a purchase.</p>
                    <a href="shop.php" class="vn-btn vn-btn-primary">Explore Collection</a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="vn-order-card">
                        <div class="vn-order-header">
                            <div class="vn-order-meta">
                                <span class="vn-order-id">ORD-<?php echo htmlspecialchars($order['order_number']); ?></span>
                                <span class="vn-order-date"><?php echo date('F d, Y', strtotime($order['created_at'])); ?></span>
                                <?php
                                $badge_map = [
                                    'Order Placed' => 'vn-badge-placed',
                                    'Processing'   => 'vn-badge-processing',
                                    'Shipped'      => 'vn-badge-shipped',
                                    'Delivered'    => 'vn-badge-delivered',
                                    'Cancelled'    => 'vn-badge-cancelled'
                                ];
                                $badge_cls = $badge_map[$order['order_status']] ?? 'vn-badge-processing';
                                ?>
                                <span class="vn-badge <?php echo $badge_cls; ?>"><?php echo htmlspecialchars($order['order_status']); ?></span>
                            </div>
                            <div class="vn-order-right">
                                <span class="vn-order-total"><?php echo format_price($order['total_amount']); ?></span>
                            </div>
                        </div>
                        <div class="vn-order-body">
                            <?php
                            $stmt_items = $pdo->prepare("
                                SELECT oi.*, 
                                       (SELECT image_url FROM product_images WHERE product_id = oi.product_id AND is_primary = TRUE LIMIT 1) as image
                                FROM order_items oi WHERE oi.order_id = ?
                            ");
                            $stmt_items->execute([$order['order_id']]);
                            $items = $stmt_items->fetchAll();
                            ?>
                            <?php foreach ($items as $item): ?>
                                <div class="vn-order-item">
                                    <div class="vn-order-item-img">
                                        <img src="<?php echo UPLOADS_URL . '/products/' . ($item['image'] ?? 'default.jpg'); ?>"
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    </div>
                                    <div class="vn-order-item-details">
                                        <div class="vn-order-item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                        <div class="vn-order-item-meta">
                                            <?php if (!empty($item['size'])): ?><span>Size: <?php echo htmlspecialchars($item['size']); ?></span><?php endif; ?>
                                            <span>Qty: <?php echo $item['quantity']; ?></span>
                                        </div>
                                        <?php if ($order['order_status'] === 'Delivered' && !empty($item['product_id'])): ?>
                                            <a href="product-detail.php?id=<?php echo $item['product_id']; ?>#reviews" class="vn-review-link">✎ Write a Review</a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="vn-order-item-price"><?php echo format_price($item['subtotal']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="vn-order-footer">
                            <div class="vn-invoice-dropdown">
                                <button class="vn-invoice-toggle" onclick="toggleInvoice(this)">
                                    <i class="material-icons" style="font-size:14px;vertical-align:middle;">receipt</i> Invoice ▾
                                </button>
                                <div class="vn-invoice-menu">
                                    <a href="invoice.php?id=<?php echo $order['order_id']; ?>" target="_blank"><i class="material-icons" style="font-size:16px;">visibility</i> View</a>
                                    <a href="invoice.php?id=<?php echo $order['order_id']; ?>&print=true" target="_blank"><i class="material-icons" style="font-size:16px;">print</i> Print</a>
                                </div>
                            </div>
                            <?php if (in_array($order['order_status'], ['Order Placed', 'Processing'])): ?>
                                <button class="vn-btn vn-btn-danger cancel-order-btn" data-order-id="<?php echo $order['order_id']; ?>">Cancel</button>
                            <?php endif; ?>
                            <a href="track-order.php?order_id=<?php echo $order['order_number']; ?>" class="vn-btn vn-btn-primary">
                                <i class="material-icons" style="font-size:16px;">local_shipping</i> Track
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleSidebar() {
    document.getElementById('dashSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

function toggleInvoice(btn) {
    const menu = btn.nextElementSibling;
    document.querySelectorAll('.vn-invoice-menu.show').forEach(m => { if (m !== menu) m.classList.remove('show'); });
    menu.classList.toggle('show');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.vn-invoice-dropdown')) {
        document.querySelectorAll('.vn-invoice-menu.show').forEach(m => m.classList.remove('show'));
    }
});

document.querySelectorAll('.cancel-order-btn').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.getAttribute('data-order-id');
        Swal.fire({
            title: 'Cancel Order?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Cancel It',
            cancelButtonText: 'Keep Order'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                fetch('api/cancel-order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_id: orderId })
                }).then(r => r.json()).then(data => {
                    if (data.success) Swal.fire('Cancelled', 'Order cancelled successfully.', 'success').then(() => location.reload());
                    else Swal.fire('Failed', data.message || 'Unable to cancel.', 'error');
                }).catch(() => Swal.fire('Error', 'Connection error.', 'error'));
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>