<?php
require_once 'config.php';

// Initialize Cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Require login to access cart
if (!is_logged_in()) {
    header('Location: login.php?redirect=' . urlencode('cart.php'));
    exit();
}

// Clear coupon if cart is cleared
if (empty($_SESSION['cart'])) {
    unset($_SESSION['applied_coupon']);
}

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $product_id = $_POST['product_id'];
        $size = $_POST['size'] ?? 'Standard';
        $color = $_POST['color'] ?? 'Default';
        $quantity = intval($_POST['quantity']);
        $cart_key = $product_id . '_' . $size . '_' . $color;

        // Validation
        if ($quantity > 0) {
            // Check if exists in cart
            if (isset($_SESSION['cart'][$cart_key])) {
                $_SESSION['cart'][$cart_key] += $quantity;
            } else {
                $_SESSION['cart'][$cart_key] = $quantity;
            }
            $_SESSION['success'] = "Product added to cart";
        }
    } elseif ($action === 'update') {
        $cart_key = $_POST['cart_key'];
        $quantity = intval($_POST['quantity']);

        if ($quantity > 0) {
            $_SESSION['cart'][$cart_key] = $quantity;
            $_SESSION['success'] = "Cart updated";
        } else {
            unset($_SESSION['cart'][$cart_key]);
            $_SESSION['success'] = "Item removed from cart";
        }
    } elseif ($action === 'remove') {
        $cart_key = $_POST['cart_key'];
        unset($_SESSION['cart'][$cart_key]);
        $_SESSION['success'] = "Item removed from cart";
    }

    // Redirect logic
    if (isset($_POST['redirect_to_checkout'])) {
        header('Location: checkout.php');
    } else {
        header('Location: cart.php');
    }
    exit();
}

// Handle coupon removal via GET
if (isset($_GET['remove_coupon'])) {
    unset($_SESSION['applied_coupon']);
    header('Location: cart.php');
    exit();
}

// Fetch Cart Products
$cart_items = [];
$subtotal = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cart_key => $qty) {
        $parts = explode('_', $cart_key);
        $pid = $parts[0];
        $size = $parts[1] ?? 'Standard';
        $color = $parts[2] ?? '';

        $stmt = $pdo->prepare("
            SELECT p.*, 
                   (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = TRUE LIMIT 1) as image
            FROM products p 
            WHERE p.product_id = ?
        ");
        $stmt->execute([$pid]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($p) {
            $price = (!empty($p['sale_price']) && $p['sale_price'] > 0) ? $p['sale_price'] : $p['regular_price'];
            $total = $price * $qty;

            $p['cart_key'] = $cart_key;
            $p['cart_qty'] = $qty;
            $p['cart_size'] = $size;
            $p['cart_color'] = $color;
            $p['cart_total'] = $total;
            $p['price_used'] = $price;

            $cart_items[] = $p;
            $subtotal += $total;
        }
    }
}

$page_title = 'Shopping Cart';
include 'includes/header.php';
?>

<div class="container my-5">
    <h1 class="font-brand mb-4 text-center">Your Shopping Bag</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="material-icons" style="font-size: 64px; color: #ddd;">shopping_bag</i>
            </div>
            <h3>Your bags are empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
            <a href="shop.php" class="btn btn-premium px-5">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <a href="product-detail.php?id=<?php echo $item['product_id']; ?>">
                                                        <img src="<?php echo UPLOADS_URL . '/products/' . ($item['image'] ?? 'default.jpg'); ?>"
                                                            alt="Product" class="rounded"
                                                            style="width: 70px; height: 70px; object-fit: cover;">
                                                    </a>
                                                    <div class="ms-3">
                                                        <h6 class="mb-1">
                                                            <a href="product-detail.php?id=<?php echo $item['product_id']; ?>" class="text-dark text-decoration-none">
                                                                <?php echo htmlspecialchars($item['product_name']); ?>
                                                            </a>
                                                        </h6>
                                                        <div class="small d-flex gap-3">
                                                            <div><span class="text-muted">Size:</span> <span class="fw-bold"><?php echo htmlspecialchars($item['cart_size']); ?></span></div>
                                                            <?php if (!empty($item['cart_color']) && $item['cart_color'] !== 'Default'): ?>
                                                                <div><span class="text-muted">Color:</span> <span class="fw-bold"><?php echo htmlspecialchars($item['cart_color']); ?></span></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <small class="text-muted">SKU: <?php echo htmlspecialchars($item['sku']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo format_price($item['price_used']); ?></td>
                                            <td style="width: 120px;">
                                                <form action="cart.php" method="POST">
                                                    <input type="hidden" name="action" value="update">
                                                    <input type="hidden" name="cart_key" value="<?php echo $item['cart_key']; ?>">
                                                    <input type="number" name="quantity" class="form-control form-control-sm"
                                                        value="<?php echo $item['cart_qty']; ?>" min="1" max="10"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="fw-bold"><?php echo format_price($item['cart_total']); ?></td>
                                            <td class="text-end pe-4">
                                                <form action="cart.php" method="POST">
                                                    <input type="hidden" name="action" value="remove">
                                                    <input type="hidden" name="cart_key" value="<?php echo $item['cart_key']; ?>">
                                                    <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent p-0">
                                                        <i class="material-icons">close</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4 font-brand">Order Summary</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Product Price</span>
                            <span class="fw-bold"><?php echo format_price($subtotal); ?></span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span class="fw-bold text-success">Free</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted" style="font-size: 0.85rem;">Est. Delivery</span>
                            <span class="text-muted text-end" style="font-size: 0.85rem;">
                                <?php echo date('M d, Y', strtotime('+6 days')); ?><br>
                                <small>(5-6 Business Days)</small>
                            </span>
                        </div>

                        <?php
                        // Restore coupon from session
                        $applied_coupon    = $_SESSION['applied_coupon'] ?? null;
                        $discount_amount   = 0;
                        $final_total       = $subtotal;
                        if ($applied_coupon) {
                            if ($applied_coupon['discount_type'] === 'Percentage') {
                                $discount_amount = round($subtotal * ($applied_coupon['discount_value'] / 100), 2);
                            } elseif ($applied_coupon['discount_type'] === 'Flat') {
                                $discount_amount = min(floatval($applied_coupon['discount_value']), $subtotal);
                            }
                            $final_total = max(0, $subtotal - $discount_amount);
                        }
                        ?>

                        <!-- Coupon Discount Line -->
                        <div id="couponDiscountRow" class="d-flex justify-content-between mb-2" style="<?php echo $applied_coupon ? '' : 'display:none!important;'; ?>">
                            <span class="text-success" style="font-size:13px;">
                                <i class="material-icons" style="font-size:14px;vertical-align:middle;">local_offer</i>
                                Coupon: <strong><?php echo htmlspecialchars($applied_coupon['coupon_code'] ?? ''); ?></strong>
                            </span>
                            <span class="text-success fw-bold">-<?php echo format_price($discount_amount); ?></span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5 fw-bold">Total</span>
                            <span class="h5 fw-bold" id="cartFinalTotal"><?php echo format_price($final_total); ?></span>
                        </div>

                        <!-- Coupon Input -->
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" id="couponInput"
                                    class="form-control form-control-sm"
                                    placeholder="Enter coupon code"
                                    value="<?php echo htmlspecialchars($applied_coupon['coupon_code'] ?? ''); ?>"
                                    style="text-transform:uppercase;letter-spacing:1px;font-size:12px;border-color:#ced4da;">
                                <button class="btn btn-dark btn-sm" type="button" id="applyCouponBtn"
                                    onclick="applyCoupon()" style="font-size:12px;letter-spacing:1px;">
                                    Apply
                                </button>
                            </div>
                            <div id="couponMessage" class="mt-2" style="font-size:12px;"></div>
                            <?php if ($applied_coupon): ?>
                                <div class="mt-1">
                                    <a href="?remove_coupon=1" class="text-muted" style="font-size:11px;">
                                        <i class="material-icons" style="font-size:11px;vertical-align:middle;">close</i> Remove coupon
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <a href="checkout.php" class="btn btn-dark btn-lg">Proceed to Checkout</a>
                        </div>

                        <div class="mt-3 text-center">
                            <a href="shop.php" class="text-muted text-decoration-none small">
                                <i class="material-icons" style="font-size: 14px; vertical-align: middle;">arrow_back</i>
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <p class="small text-muted mb-0">
                        <i class="material-icons" style="font-size: 16px; vertical-align: middle;">lock</i>
                        Secure Checkout
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script>
    function applyCoupon() {
        const code = document.getElementById('couponInput').value.trim().toUpperCase();
        const msgDiv = document.getElementById('couponMessage');
        const btn = document.getElementById('applyCouponBtn');

        if (!code) {
            msgDiv.style.color = '#e53935';
            msgDiv.textContent = 'Please enter a coupon code.';
            return;
        }

        // Read current subtotal from DOM (safe approach: use PHP-injected value)
        const subtotal = <?php echo $subtotal; ?>;

        btn.disabled = true;
        btn.textContent = '...';
        msgDiv.textContent = '';

        const formData = new FormData();
        formData.append('coupon_code', code);
        formData.append('subtotal', subtotal);

        fetch('<?php echo SITE_URL; ?>/api/coupon-apply.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Apply';
                if (data.success) {
                    msgDiv.style.color = '#2e7d32';
                    msgDiv.textContent = data.message;

                    // Store coupon in session via AJAX — reload page to apply properly
                    const storeForm = new FormData();
                    storeForm.append('action', 'apply_coupon');
                    storeForm.append('coupon_code', data.coupon_code);
                    storeForm.append('discount_type', data.discount_type);
                    storeForm.append('discount_value', data.discount_value);

                    fetch('<?php echo SITE_URL; ?>/api/coupon-store.php', {
                        method: 'POST',
                        body: storeForm
                    }).then(() => location.reload());
                } else {
                    msgDiv.style.color = '#e53935';
                    msgDiv.textContent = data.message;
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Apply';
                msgDiv.style.color = '#e53935';
                msgDiv.textContent = 'An error occurred. Try again.';
            });
    }
</script>