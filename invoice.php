<?php
require_once 'config.php';

// Check login
// Check login or tracking token
$is_tracking = isset($_GET['track']);
if (!is_logged_in() && !isset($_SESSION['admin_id']) && !$is_tracking) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    die("Order ID required");
}

$order_id = $_GET['id'];

// Fetch Order Details
$stmt = $pdo->prepare("
    SELECT o.*, u.full_name, u.email,
           sa.recipient_name as s_name, sa.address_line1 as s_addr, sa.city as s_city, sa.state as s_state, sa.postal_code as s_zip, sa.phone as s_phone
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.user_id
    LEFT JOIN addresses sa ON o.shipping_address_id = sa.address_id
    WHERE o.order_id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found");
}

// Security: Check if order belongs to user (if not admin) or if a valid tracking token is provided
$tracking_token = $_GET['track'] ?? '';
$is_valid_tracking = (!empty($tracking_token) && $order['order_number'] === $tracking_token);

if (!isset($_SESSION['admin_id']) && $order['user_id'] != get_current_user_id() && !$is_valid_tracking) {
    die("Unauthorized access");
}

// Fetch Order Items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?php echo $order['order_number']; ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
        }
        body {
            font-family: var(--font-sans);
            color: #1a1a1a;
            -webkit-font-smoothing: antialiased;
        }
        .material-icons {
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            font-feature-settings: 'liga';
            font-display: block;
        }
        .font-brand { 
            font-family: var(--font-serif); 
            letter-spacing: 0.05em;
        }
        
        .invoice-box { 
            padding: 60px; 
            border: 1px solid #e0e0e0; 
            background: #fff; 
            margin-top: 40px; 
            margin-bottom: 40px;
        }
        
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; margin: 0; padding: 0; }
            .container { max-width: 100% !important; width: 100% !important; padding: 0 !important; margin: 0 !important; }
            .invoice-box { 
                border: none !important; 
                box-shadow: none !important; 
                margin: 0 !important; 
                padding: 40px !important;
                width: 100% !important;
            }
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-serif);
        }

        .table th {
            font-family: var(--font-serif);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.1em;
            color: #1a1a1a;
            border-bottom: 2px solid #000;
            background: transparent !important;
        }
        
        .table td {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #eee;
        }
        
        .brand-logo {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: inline-block;
            letter-spacing: 2px;
        }
        
        .invoice-title {
            font-family: var(--font-serif);
            font-weight: 700;
            font-size: 2.4rem;
            letter-spacing: 2px;
        }
        
        .btn-dark {
            background-color: #000;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.8rem;
            padding: 12px 30px;
            box-shadow: none;
        }
        
        .btn-outline-dark {
            border-color: #000;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.8rem;
            padding: 11px 30px;
        }
        .btn-outline-dark:hover {
            background-color: #000;
            color: #fff;
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="text-center mt-5 no-print d-flex justify-content-center gap-3">
        <button onclick="window.print()" class="btn btn-dark">
            <i class="material-icons me-2" style="font-size: 18px; vertical-align: bottom;">print</i> Print Invoice
        </button>
        <a href="profile.php" class="btn btn-outline-dark">Back to Account</a>
    </div>

    <div class="invoice-box shadow-sm rounded">
        <div class="row mb-5 align-items-start">
            <div class="col-sm-6">
                <div class="font-brand brand-logo">VÉNARO</div>
                <p class="text-muted small text-uppercase letter-spacing-1">Redefining Modern Luxury</p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <h1 class="invoice-title mb-2">INVOICE</h1>
                <p class="mb-1"><strong>Invoice No:</strong> #INV-<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?></p>
                <p class="mb-1"><strong>Order No:</strong> <?php echo $order['order_number']; ?></p>
                <p class="mb-1 text-muted">Date: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                <p class="mb-0 text-muted">Est. Delivery: <?php echo date('F j, Y', strtotime($order['created_at'] . ' + 6 days')); ?></p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-sm-6 mb-4 mb-sm-0">
                <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom d-inline-block pb-1">From</h6>
                <p class="mb-1 fw-bold font-brand" style="font-size: 1.1rem;">VÉNARO</p>
                <p class="mb-1 text-muted">info@venaro.com</p>
                <p class="mb-0 text-muted">+91 98765 43210</p>
            </div>
            <div class="col-sm-6 text-sm-end">
                <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom d-inline-block pb-1">Bill To</h6>
                <p class="mb-1 fw-bold"><?php echo htmlspecialchars($order['s_name']); ?></p>
                <p class="mb-1"><?php echo htmlspecialchars($order['s_addr']); ?></p>
                <p class="mb-1"><?php echo htmlspecialchars($order['s_city'] . ', ' . $order['s_state']); ?></p>
                <p class="mb-1"><?php echo htmlspecialchars($order['s_zip']); ?></p>
                <p class="mb-0 text-muted"><?php echo htmlspecialchars($order['s_phone']); ?></p>
            </div>
        </div>

        <div class="table-responsive mb-5">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th class="ps-0" style="width: 50%;">Item Description</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end pe-0">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="ps-0">
                                <div class="fw-bold"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <?php if ($item['size']): ?>
                                    <div class="text-muted small mt-1">Size: <?php echo $item['size']; ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?php echo format_price($item['unit_price']); ?></td>
                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                            <td class="text-end pe-0"><?php echo format_price($item['subtotal']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="bg-light p-4 rounded">
                    <h6 class="text-uppercase text-muted small fw-bold mb-3">Payment Info</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Method:</span>
                        <span class="fw-medium text-capitalize"><?php echo str_replace('_', ' ', $order['payment_method']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status:</span>
                        <span class="fw-medium">
                            <?php 
                            $status_color = match($order['payment_status']) {
                                'Completed', 'Paid' => 'text-success',
                                'Pending' => 'text-warning',
                                'Failed' => 'text-danger',
                                default => 'text-dark'
                            };
                            ?>
                            <span class="<?php echo $status_color; ?>"><?php echo ucfirst($order['payment_status']); ?></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-5 offset-md-1">
                <div class="border-top pt-3">
                    <?php 
                    $tax_rate = DEFAULT_TAX_RATE;
                    $total = (float)$order['total_amount'];
                    $taxable_value = $total / (1 + ($tax_rate / 100));
                    $tax_amount = $total - $taxable_value;
                    $cgst = $tax_amount / 2;
                    $sgst = $tax_amount / 2;
                    ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Taxable Value</span>
                        <span><?php echo format_price($taxable_value); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">CGST (<?php echo $tax_rate/2; ?>%)</span>
                        <span><?php echo format_price($cgst); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">SGST (<?php echo $tax_rate/2; ?>%)</span>
                        <span><?php echo format_price($sgst); ?></span>
                    </div>
                    <?php if ($order['shipping_charge'] > 0): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span><?php echo format_price($order['shipping_charge']); ?></span>
                    </div>
                    <?php else: ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between border-top border-dark pt-3 mt-3">
                        <span class="h5 font-brand mb-0 text-uppercase fw-bold">Total (Incl. GST)</span>
                        <span class="h5 font-brand mb-0 fw-bold"><?php echo format_price($order['total_amount']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 pt-5 border-top text-center">
            <p class="font-brand h4 mb-2">Thank you for your purchase</p>
            <p class="text-muted small mb-0">If you have any questions about this invoice, please contact us.</p>
            <p class="text-muted small">© <?php echo date('Y'); ?> VÉNARO. All rights reserved.</p>
        </div>
    </div>
</div>


<script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            window.print();
        }
    };
</script>
</body>
</html>
