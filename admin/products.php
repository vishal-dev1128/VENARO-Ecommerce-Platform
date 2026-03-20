<?php
session_start();
require_once '../config.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Handle Duplicate Request
if (isset($_GET['duplicate_id'])) {
    try {
        $pdo->beginTransaction();

        $original_id = $_GET['duplicate_id'];

        // 1. Fetch original product
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$original_id]);
        $product = $stmt->fetch();

        if ($product) {
            // Prepare new product data
            $new_name = $product['product_name'] . ' (Copy)';
            $new_sku = 'VN-' . strtoupper(uniqid());

            // Generate unique slug
            $new_slug = strtolower(trim($new_name));
            $new_slug = preg_replace('/[^a-z0-9-]+/', '-', $new_slug);
            $new_slug = preg_replace('/-+/', '-', $new_slug);
            $new_slug = trim($new_slug, '-');

            $original_slug = $new_slug;
            $count = 1;
            while (true) {
                $check_slug = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
                $check_slug->execute([$new_slug]);
                if ($check_slug->fetchColumn() == 0) break;
                $new_slug = $original_slug . '-' . $count;
                $count++;
            }

            // 2. Insert new product
            $sql = "INSERT INTO products (product_name, sku, slug, short_description, long_description, regular_price, sale_price, sale_start_date, sale_end_date, tax_rate, weight, length, width, height, fabric_composition, gsm_weight, care_instructions, track_inventory, stock_quantity, low_stock_threshold, allow_backorders, stock_status, status, featured) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $new_name,
                $new_sku,
                $new_slug,
                $product['short_description'],
                $product['long_description'],
                $product['regular_price'],
                $product['sale_price'],
                $product['sale_start_date'],
                $product['sale_end_date'],
                $product['tax_rate'],
                $product['weight'],
                $product['length'],
                $product['width'],
                $product['height'],
                $product['fabric_composition'],
                $product['gsm_weight'],
                $product['care_instructions'],
                $product['track_inventory'],
                $product['stock_quantity'],
                $product['low_stock_threshold'],
                $product['allow_backorders'],
                $product['stock_status'],
                $product['featured']
            ]);

            $new_product_id = $pdo->lastInsertId();

            // 3. Copy Categories
            $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) SELECT ?, category_id FROM product_categories WHERE product_id = ?");
            $stmt->execute([$new_product_id, $original_id]);



            // 5. Copy Images
            $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url, alt_text, display_order, is_primary) SELECT ?, image_url, alt_text, display_order, is_primary FROM product_images WHERE product_id = ?");
            $stmt->execute([$new_product_id, $original_id]);

            // 6. Copy Variants
            $stmt = $pdo->prepare("SELECT * FROM product_variants WHERE product_id = ?");
            $stmt->execute([$original_id]);
            $variants = $stmt->fetchAll();

            if ($variants) {
                $v_stmt = $pdo->prepare("INSERT INTO product_variants (product_id, sku, size, color, color_hex, price_adjustment, stock_quantity, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($variants as $index => $v) {
                    $new_v_sku = $new_sku . '-' . ($index + 1);
                    $v_stmt->execute([
                        $new_product_id,
                        $new_v_sku,
                        $v['size'],
                        $v['color'],
                        $v['color_hex'],
                        $v['price_adjustment'],
                        $v['stock_quantity'],
                        $v['image'],
                        $v['status']
                    ]);
                }
            }

            // 7. Copy Reviews
            $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, order_id, rating, review_title, review_text, verified_purchase, helpful_count, status) SELECT ?, user_id, order_id, rating, review_title, review_text, verified_purchase, helpful_count, status FROM reviews WHERE product_id = ?");
            $stmt->execute([$new_product_id, $original_id]);

            $pdo->commit();
            $_SESSION['success'] = "Product and reviews duplicated successfully.";
        } else {
            $_SESSION['error'] = "Product not found.";
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error duplicating product: " . $e->getMessage();
    }
    header('Location: products.php');
    exit();
}

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        $_SESSION['success'] = "Product deleted successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting product: " . $e->getMessage();
    }
    header('Location: products.php');
    exit();
}

// Fetch Products with Categories and Image
$stmt = $pdo->query("
    SELECT p.*, 
           (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = TRUE LIMIT 1) as primary_image,
           (SELECT COUNT(*) FROM product_variants WHERE product_id = p.product_id) as variant_count,
           GROUP_CONCAT(DISTINCT c.category_name SEPARATOR ', ') as categories
    FROM products p
    LEFT JOIN product_categories pc ON p.product_id = pc.product_id
    LEFT JOIN categories c ON pc.category_id = c.category_id
    GROUP BY p.product_id
    ORDER BY p.created_at DESC
");
$products = $stmt->fetchAll();

$page_title = 'Products';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Products</h1>
        <a href="product-add.php" class="btn btn-primary d-flex align-items-center gap-2" style="padding: 10px 20px; font-weight: 600;">
            <i class="material-icons" style="font-size: 20px;">add</i>
            Add New Product
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="modern-card">
        <div class="modern-card-body">
            <div class="table-responsive">
                <table class="modern-table table" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Product Name</th>
                            <th>Category</th>

                            <th>Price</th>
                            <th>Status</th>
                            <th>Stock</th>
                            <th>Variants</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="material-icons" style="font-size: 48px; color: #dee2e6;">inventory_2</i>
                                        <p class="mt-2 mb-0">No products found. Click "Add New Product" to start.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if ($product['primary_image']): ?>
                                            <img src="<?php echo UPLOADS_URL . '/products/' . $product['primary_image']; ?>"
                                                alt="Product" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center text-muted border rounded"
                                                style="width: 50px; height: 50px;">
                                                <i class="material-icons" style="font-size: 20px;">image</i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #212529;">
                                            <?php echo htmlspecialchars($product['product_name']); ?>
                                        </div>
                                        <small class="text-muted">ID: #<?php echo $product['product_id']; ?></small>
                                    </td>
                                    <td>
                                        <?php if ($product['categories']): ?>
                                            <span class="text-muted small"><?php echo htmlspecialchars($product['categories']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">Uncategorized</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($product['sale_price']): ?>
                                            <div style="font-weight: 600; color: #212529;"><?php echo format_price($product['sale_price']); ?></div>
                                            <small class="text-muted text-decoration-line-through"><?php echo format_price($product['regular_price']); ?></small>
                                        <?php else: ?>
                                            <span style="font-weight: 600; color: #212529;"><?php echo format_price($product['regular_price']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = match ($product['status']) {
                                            'Active' => 'success',
                                            'Draft' => 'secondary',
                                            'Archived' => 'warning',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?php echo $status_class; ?>">
                                            <?php echo $product['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($product['track_inventory']): ?>
                                            <?php if ($product['stock_quantity'] <= $product['low_stock_threshold']): ?>
                                                <span class="text-danger fw-bold"><?php echo $product['stock_quantity']; ?></span>
                                            <?php else: ?>
                                                <?php echo $product['stock_quantity']; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">&infin;</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info rounded-pill"><?php echo $product['variant_count']; ?></span>
                                    </td>
                                    <td>
                                        <div class="action-btn-group">
                                            <a href="product-edit.php?id=<?php echo $product['product_id']; ?>"
                                                class="action-btn action-btn-primary" title="Edit">
                                                <i class="material-icons" style="font-size: 16px;">edit</i>
                                            </a>
                                            <a href="products.php?duplicate_id=<?php echo $product['product_id']; ?>"
                                                class="action-btn" title="Duplicate">
                                                <i class="material-icons" style="font-size: 16px;">content_copy</i>
                                            </a>
                                            <button type="button" class="action-btn action-btn-danger" title="Delete"
                                                onclick="venaroConfirm('Are you sure you want to delete this product?', () => window.location.href='products.php?delete_id=<?php echo $product['product_id']; ?>', {title: 'Delete Product', confirmText: 'Delete'})">
                                                <i class="material-icons" style="font-size: 16px;">delete</i>
                                            </button>
                                        </div>
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