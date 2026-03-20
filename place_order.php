<?php
require_once 'config.php';

// Redirect if not logged in
if (!is_logged_in()) {
    redirect('login.php?redirect=' . urlencode('checkout.php'));
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: shop.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit();
}

// 1. Schema Fix (Ensure addresses.user_id allows NULL for guests)
try {
    $pdo->exec("ALTER TABLE addresses MODIFY user_id INT NULL");
} catch (PDOException $e) {
    // Silently skip if already modified or permission denied
}

try {
    $pdo->beginTransaction();

    // 2. User Logic (Guest or Logged In)
    $user_id = $_SESSION['user_id'] ?? null;
    $email = $_POST['email'];
    $guest_email = $user_id ? null : $email;
    $payment_method = $_POST['payment_method'] ?? 'COD';

    // 3. Create Address Record
    $stmt_addr = $pdo->prepare("INSERT INTO addresses (
        user_id, recipient_name,
        address_line1, city, state, postal_code, country, 
        address_type, phone
    ) VALUES (?, ?, ?, ?, ?, ?, 'India', 'Shipping', 'N/A')");

    $recipient_name = trim(($_POST['first_name'] ?? '') . ' ' . ($_POST['last_name'] ?? ''));

    $stmt_addr->execute([
        $user_id,
        $recipient_name,
        $_POST['address'],
        $_POST['city'],
        $_POST['state'],
        $_POST['zip']
    ]);
    $shipping_address_id = $pdo->lastInsertId();

    // 4. Create Order
    $stmt = $pdo->prepare("INSERT INTO orders (
        order_number, user_id, guest_email, 
        total_amount, subtotal, tax_amount, shipping_charge, 
        payment_method, payment_status, order_status, shipping_address_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Order Placed', ?)");

    $order_number = 'ORD-' . strtoupper(uniqid());

    // Recalculate Total
    $subtotal = 0;

    foreach ($_SESSION['cart'] as $cart_key => $qty) {
        $parts = explode('_', $cart_key);
        $pid = $parts[0];

        $cart_query = $pdo->prepare("SELECT product_id, sale_price, regular_price FROM products WHERE product_id = ?");
        $cart_query->execute([$pid]);
        $p = $cart_query->fetch(PDO::FETCH_ASSOC);

        if ($p) {
            $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['regular_price'];
            $subtotal += (float)$price * (int)$qty;
        }
    }

    $shipping_charge = 0; // Free shipping as per checkout.php
    $tax_amount = $subtotal - ($subtotal / (1 + (DEFAULT_TAX_RATE / 100)));

    // Apply coupon discount
    $coupon_discount = 0;
    $applied_coupon  = $_SESSION['applied_coupon'] ?? null;
    $coupon_code_used = $_POST['coupon_code'] ?? ($applied_coupon['coupon_code'] ?? null);
    if ($applied_coupon) {
        if ($applied_coupon['discount_type'] === 'Percentage') {
            $coupon_discount = round($subtotal * ($applied_coupon['discount_value'] / 100), 2);
        } elseif ($applied_coupon['discount_type'] === 'Flat') {
            $coupon_discount = min((float)$applied_coupon['discount_value'], $subtotal);
        }
    }
    $total = max(0, $subtotal + $shipping_charge - $coupon_discount);

    $stmt->execute([
        $order_number,
        $user_id,
        $guest_email,
        $total,
        $subtotal,
        $tax_amount,
        $shipping_charge,
        $payment_method,
        $shipping_address_id
    ]);

    $order_id = $pdo->lastInsertId();

    // 5. Insert Order Items
    $stmt_item = $pdo->prepare("INSERT INTO order_items (
        order_id, product_id, product_name, size,
        quantity, unit_price, subtotal
    ) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($_SESSION['cart'] as $cart_key => $qty) {
        $parts = explode('_', $cart_key);
        $pid = $parts[0];
        $size = $parts[1] ?? 'Standard';

        $item_query = $pdo->prepare("SELECT product_name, sale_price, regular_price FROM products WHERE product_id = ?");
        $item_query->execute([$pid]);
        $p = $item_query->fetch(PDO::FETCH_ASSOC);

        if ($p) {
            $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['regular_price'];
            $line_total = (float)$price * (int)$qty;

            $stmt_item->execute([
                $order_id,
                $pid,
                $p['product_name'],
                $size,
                $qty,
                $price,
                $line_total
            ]);
        }
    }

    $pdo->commit();

    // Clear Cart & Coupon
    unset($_SESSION['cart']);
    unset($_SESSION['applied_coupon']);
    $_SESSION['last_order_id'] = $order_id;

    // Redirect to Success
    header('Location: order-success.php?id=' . $order_id);
    exit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Order failed: " . $e->getMessage());
}
