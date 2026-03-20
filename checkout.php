<?php
require_once 'config.php';

// Redirect if not logged in
if (!is_logged_in()) {
    redirect('login.php?redirect=' . urlencode('checkout.php'));
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

$page_title = 'Checkout';
include 'includes/header.php';

// Calculate totals
$subtotal = 0;
$cart_products = [];
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cart_key => $qty) {
        $parts = explode('_', $cart_key);
        $pid = $parts[0];
        $size = $parts[1] ?? 'Standard';

        $stmt = $pdo->prepare("SELECT product_id, product_name, sale_price, regular_price FROM products WHERE product_id = ?");
        $stmt->execute([$pid]);
        $p = $stmt->fetch();

        if ($p) {
            $price = (!empty($p['sale_price']) && $p['sale_price'] > 0) ? $p['sale_price'] : $p['regular_price'];
            $subtotal += $price * $qty;
            $cart_products[] = [
                'name'  => $p['product_name'],
                'size'  => $size,
                'qty'   => $qty,
                'total' => $price * $qty
            ];
        }
    }
}

// Apply coupon from session
$applied_coupon  = $_SESSION['applied_coupon'] ?? null;
$discount_amount = 0;
$grand_total     = $subtotal;
if ($applied_coupon) {
    if ($applied_coupon['discount_type'] === 'Percentage') {
        $discount_amount = round($subtotal * ($applied_coupon['discount_value'] / 100), 2);
    } elseif ($applied_coupon['discount_type'] === 'Flat') {
        $discount_amount = min(floatval($applied_coupon['discount_value']), $subtotal);
    }
    $grand_total = max(0, $subtotal - $discount_amount);
}
?>

<div class="container my-5">
    <h1 class="font-brand mb-4 text-center">Checkout</h1>

    <form action="place_order.php" method="POST" id="checkoutForm">
        <div class="row">
            <!-- Shipping Details -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!is_logged_in()): ?>
                            <div class="alert alert-info py-2">
                                <small>Already have an account? <a href="login.php?redirect=checkout.php">Log in</a> for faster checkout.</small>
                            </div>
                        <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required
                                    value="<?php echo is_logged_in() ? explode(' ', $_SESSION['user_name'])[0] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required
                                    value="<?php echo is_logged_in() ? explode(' ', $_SESSION['user_name'])[1] ?? '' : ''; ?>">
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="<?php echo $_SESSION['user_email'] ?? ''; ?>">
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Street Address *</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="123 Main St" required>
                            </div>

                            <div class="col-md-5">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>

                            <div class="col-md-4">
                                <label for="state" class="form-label">State *</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>

                            <div class="col-md-3">
                                <label for="zip" class="form-label">Zip Code *</label>
                                <input type="text" class="form-control" id="zip" name="zip" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                            <label class="form-check-label fw-bold" for="cod">
                                Cash on Delivery (COD)
                            </label>
                            <div class="text-muted small mt-1">Pay with cash upon delivery.</div>
                        </div>
                        <div class="form-check text-muted">
                            <input class="form-check-input" type="radio" name="payment_method" id="card" value="Card" disabled>
                            <label class="form-check-label" for="card">
                                Credit/Debit Card (Coming Soon)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4 font-brand">Your Order</h5>

                        <div class="mb-4">
                            <?php foreach ($cart_products as $item): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></div>
                                        <small class="text-muted">Size: <?php echo htmlspecialchars($item['size']); ?> | x<?php echo $item['qty']; ?></small>
                                    </div>
                                    <span class="fw-bold"><?php echo format_price($item['total']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold"><?php echo format_price($subtotal); ?></span>
                        </div>

                        <?php if ($applied_coupon): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success" style="font-size:13px;">
                                    <i class="material-icons" style="font-size:14px;vertical-align:middle;">local_offer</i>
                                    <?php echo htmlspecialchars($applied_coupon['coupon_code']); ?>
                                </span>
                                <span class="text-success fw-bold">-<?php echo format_price($discount_amount); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success">Free</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted" style="font-size: 0.85rem;">Est. Delivery</span>
                            <span class="text-muted text-end" style="font-size: 0.85rem;">
                                <?php echo date('M d, Y', strtotime('+6 days')); ?><br>
                                <small>(5-6 Business Days)</small>
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">Grand Total</span>
                            <span class="h5 fw-bold"><?php echo format_price($grand_total); ?></span>
                        </div>

                        <?php if ($applied_coupon): ?>
                            <input type="hidden" name="coupon_code" value="<?php echo htmlspecialchars($applied_coupon['coupon_code']); ?>">
                        <?php endif; ?>

                        <button type="submit" class="btn btn-dark btn-lg w-100 py-3">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>