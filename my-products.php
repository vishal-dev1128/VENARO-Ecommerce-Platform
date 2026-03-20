<?php
require_once 'config.php';

// Check login
if (!is_logged_in()) {
    header('Location: login.php?redirect=my-products.php');
    exit();
}

$user_id = get_current_user_id();

// Fetch Unique Purchased Products
$stmt = $pdo->prepare("
    SELECT DISTINCT p.product_id, p.product_name, p.regular_price, p.sale_price,
           (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = TRUE LIMIT 1) as image
    FROM products p
    JOIN order_items oi ON p.product_id = oi.product_id
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.user_id = ? AND o.order_status = 'Delivered'
");
$stmt->execute([$user_id]);
$purchased_products = $stmt->fetchAll();

$page_title = 'My Purchased Products';
include 'includes/header.php';
?>

<div class="container my-5">
    <h1 class="font-brand mb-4 text-center">My Products</h1>
    <p class="text-center text-muted mb-5">A collection of premium apparel you've previously purchased and received.</p>

    <?php if (empty($purchased_products)): ?>
        <div class="text-center py-5">
            <i class="material-icons mb-3 text-muted" style="font-size: 64px;">inventory_2</i>
            <h3>No products found</h3>
            <p class="text-muted mb-4">Once your orders are delivered, the products will appear here.</p>
            <a href="shop.php" class="btn btn-premium px-5">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($purchased_products as $item): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="product-card h-100 border rounded p-3 shadow-sm">
                        <div class="product-image-wrapper mb-3">
                            <a href="product-detail.php?id=<?php echo $item['product_id']; ?>">
                                <img src="<?php echo UPLOADS_URL . '/products/' . ($item['image'] ?? 'default.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                     class="img-fluid rounded" style="width: 100%; height: 200px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="product-info text-center">
                            <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                            <a href="product-detail.php?id=<?php echo $item['product_id']; ?>" class="btn btn-outline-dark btn-sm w-100">View Product</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
