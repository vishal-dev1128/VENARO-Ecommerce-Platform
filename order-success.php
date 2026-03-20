<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: shop.php');
    exit();
}

$order_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found");
}

// Fetch Order Items for a richer look
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

$page_title = 'Order Confirmed';
include 'includes/header.php';
?>

<style>
    .order-success-hero {
        padding: 80px 0 40px;
        background: #fff;
    }
    
    .success-checkmark {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
        display: block;
    }
    
    .checkmark-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #1a1a1a;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    
    .checkmark-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
    }
    
    @keyframes stroke {
        100% { stroke-dashoffset: 0; }
    }
    
    .thank-you-title {
        font-family: var(--font-brand);
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: 2px;
        color: #1a1a1a;
    }
    
    .confirmation-msg {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 50px;
    }
    
    .order-summary-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 0;
        padding: 40px;
        text-align: left;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    
    .summary-header {
        border-bottom: 2px solid #1a1a1a;
        padding-bottom: 15px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    
    .summary-title {
        font-family: var(--font-brand);
        text-transform: uppercase;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 2px;
        margin: 0;
    }
    
    .order-meta-info {
        font-size: 0.85rem;
        color: #888;
        margin: 0;
    }
    
    .order-item-list {
        list-style: none;
        padding: 0;
        margin-bottom: 30px;
    }
    
    .order-item-entry {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f8f8f8;
    }
    
    .item-name {
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .item-extras {
        font-size: 0.8rem;
        color: #999;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
    
    .total-label {
        font-family: var(--font-brand);
        text-transform: uppercase;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .total-value {
        font-weight: 700;
        font-size: 1.3rem;
    }
    
    .whats-next {
        text-align: left;
        margin-top: 60px;
    }
    
    .whats-next-title {
        font-family: var(--font-brand);
        font-size: 1.5rem;
        margin-bottom: 25px;
        border-left: 4px solid #1a1a1a;
        padding-left: 20px;
    }
    
    .step-item {
        margin-bottom: 25px;
        display: flex;
        gap: 20px;
    }
    
    .step-number {
        font-family: var(--font-brand);
        font-size: 1.5rem;
        color: #eee;
        font-weight: 700;
    }
    
    .step-text h6 {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .step-text p {
        font-size: 0.95rem;
        color: #777;
    }
    
    .action-buttons {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 1px solid #eee;
        display: flex;
        gap: 20px;
        justify-content: center;
    }
    
    .btn-premium-dark {
        background: #1a1a1a;
        color: #fff;
        padding: 18px 45px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 2px;
        border-radius: 0;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    
    .btn-premium-dark:hover {
        background: #333;
        color: #fff;
        transform: translateY(-2px);
    }
    
    .btn-premium-outline {
        background: transparent;
        color: #1a1a1a;
        padding: 18px 45px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 2px;
        border: 2px solid #1a1a1a;
        border-radius: 0;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    
    .btn-premium-outline:hover {
        background: #1a1a1a;
        color: #fff;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .thank-you-title { font-size: 2.5rem; }
        .action-buttons { flex-direction: column; }
    }
</style>

<div class="order-success-hero text-center">
    <div class="container" style="max-width: 800px;">
        <!-- Premium Checkmark Animation -->
        <svg class="success-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
        </svg>

        <h1 class="thank-you-title">Thank You.</h1>
        <p class="confirmation-msg">Your sequence of luxury has been initiated. We've received your order and are preparing it with meticulous care.</p>

        <!-- Rich Order Summary -->
        <div class="order-summary-card">
            <div class="summary-header">
                <h5 class="summary-title">Order Details</h5>
                <p class="order-meta-info">No. <?php echo htmlspecialchars($order['order_number']); ?> — <?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
            </div>

            <ul class="order-item-list">
                <?php foreach ($items as $item): ?>
                <li class="order-item-entry">
                    <div>
                        <span class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></span>
                        <div class="item-extras">
                            <?php if($item['size']): ?>Size: <?php echo htmlspecialchars($item['size']); ?> | <?php endif; ?>
                            Qty: <?php echo $item['quantity']; ?>
                        </div>
                    </div>
                    <span class="item-price fw-bold"><?php echo format_price($item['subtotal']); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="total-row">
                <span class="total-label">Grand Total</span>
                <span class="total-value"><?php echo format_price($order['total_amount']); ?></span>
            </div>
            <div class="text-muted small mt-1 text-end">Inclusive of GST & Free Shipping</div>
        </div>

        <!-- What's Next Section -->
        <div class="whats-next">
            <h4 class="whats-next-title">What Happens Next?</h4>
            
            <div class="step-item">
                <div class="step-number">01</div>
                <div class="step-text">
                    <h6>Order Processing</h6>
                    <p>Our artisans are confirming your selection and preparing your items for quality inspection.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">02</div>
                <div class="step-text">
                    <h6>Shipping Update</h6>
                    <p>You'll receive a notification as soon as your order leaves our workshop with a tracking identity.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">03</div>
                <div class="step-text">
                    <h6>Delivery</h6>
                    <?php 
                        $delivery_date = date('M d, Y', strtotime($order['created_at'] . ' + 6 days'));
                    ?>
                    <p>Your VÉNARO apparel will arrive within 5-6 business days (Estimated Delivery: <strong><?php echo $delivery_date; ?></strong>), packaged for the ultimate unboxing experience.</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="shop.php" class="btn btn-premium-dark">Continue Shopping</a>
            <div class="dropdown">
                <button class="btn btn-premium-outline dropdown-toggle" type="button" data-mdb-toggle="dropdown" aria-expanded="false">
                    Order Options
                </button>
                <ul class="dropdown-menu border-0 shadow-lg">
                    <li><a class="dropdown-item" href="invoice.php?id=<?php echo $order_id; ?>" target="_blank"><i class="material-icons me-2" style="font-size: 18px;">description</i> View Invoice</a></li>
                    <li><a class="dropdown-item" href="invoice.php?id=<?php echo $order_id; ?>&print=true" target="_blank"><i class="material-icons me-2" style="font-size: 18px;">print</i> Print Receipt</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

