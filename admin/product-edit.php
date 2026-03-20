<?php
session_start();
require_once '../config.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = $_GET['id'];
$error = '';
$success = '';

// Fetch Product Data
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        header('Location: products.php');
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching product: " . $e->getMessage());
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'] ?? '';

    // Generate Slug
    $slug = strtolower(trim($product_name));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');

    // Ensure Slug is unique (excluding current product)
    $original_slug = $slug;
    $count = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ? AND product_id != ?");
        $stmt->execute([$slug, $product_id]);
        if ($stmt->fetchColumn() == 0) break;
        $slug = $original_slug . '-' . $count;
        $count++;
    }
    $regular_price = $_POST['regular_price'] ?? 0;
    $sale_price = !empty($_POST['sale_price']) ? $_POST['sale_price'] : null;
    $stock_quantity = $_POST['stock_quantity'] ?? 0;
    $status = $_POST['status'] ?? 'Active';
    $short_description = $_POST['short_description'] ?? '';
    $long_description = $_POST['long_description'] ?? '';
    $selected_categories = $_POST['categories'] ?? [];
    $selected_collections = $_POST['collections'] ?? [];

    // Validation
    if (empty($product_name) || empty($regular_price)) {
        $error = "Name and Regular Price are required.";
    } else {
        try {


            // Update Product
            $sql = "UPDATE products SET 
                    product_name = ?, 
                    slug = ?, 
                    regular_price = ?, 
                    sale_price = ?, 
                    stock_quantity = ?, 
                    status = ?, 
                    short_description = ?, 
                    long_description = ? 
                    WHERE product_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $product_name,
                $slug,
                $regular_price,
                $sale_price,
                $stock_quantity,
                $status,
                $short_description,
                $long_description,
                $product_id
            ]);

            // Handle New Multiple Image Uploads
            if (isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
                $upload_dir = '../uploads/products/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $total_files = count($_FILES['product_images']['name']);

                for ($i = 0; $i < $total_files; $i++) {
                    if ($_FILES['product_images']['error'][$i] === UPLOAD_ERR_OK) {
                        $file_ext = strtolower(pathinfo($_FILES['product_images']['name'][$i], PATHINFO_EXTENSION));
                        $new_filename = 'prod_' . $product_id . '_' . uniqid() . '_' . $i . '.' . $file_ext;
                        $dest_path = $upload_dir . $new_filename;

                        if (move_uploaded_file($_FILES['product_images']['tmp_name'][$i], $dest_path)) {
                            // Check if there are existing primary images
                            $check_primary = $pdo->prepare("SELECT COUNT(*) FROM product_images WHERE product_id = ? AND is_primary = TRUE");
                            $check_primary->execute([$product_id]);
                            $has_primary = $check_primary->fetchColumn() > 0;

                            $is_primary = $has_primary ? 0 : 1;

                            $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url, is_primary) VALUES (?, ?, ?)");
                            $stmt->execute([$product_id, $new_filename, $is_primary]);
                        }
                    }
                }
            }

            // Handle Image Actions (Delete or Set Primary)
            if (isset($_POST['image_action'])) {
                $action = $_POST['image_action'];
                $img_id = (int)$_POST['image_id'];

                if ($action === 'delete') {
                    // Get image URL to delete file
                    $stmt = $pdo->prepare("SELECT image_url, is_primary FROM product_images WHERE image_id = ? AND product_id = ?");
                    $stmt->execute([$img_id, $product_id]);
                    $img_data = $stmt->fetch();

                    if ($img_data) {
                        $file_path = '../uploads/products/' . $img_data['image_url'];
                        if (file_exists($file_path)) unlink($file_path);

                        $pdo->prepare("DELETE FROM product_images WHERE image_id = ?")->execute([$img_id]);

                        // If deleted was primary, set another one as primary
                        if ($img_data['is_primary']) {
                            $pdo->prepare("UPDATE product_images SET is_primary = TRUE WHERE product_id = ? LIMIT 1")->execute([$product_id]);
                        }
                    }
                } elseif ($action === 'set_primary') {
                    $pdo->prepare("UPDATE product_images SET is_primary = FALSE WHERE product_id = ?")->execute([$product_id]);
                    $pdo->prepare("UPDATE product_images SET is_primary = TRUE WHERE image_id = ? AND product_id = ?")->execute([$img_id, $product_id]);
                }
            }

            // Update Product Categories
            $pdo->prepare("DELETE FROM product_categories WHERE product_id = ?")->execute([$product_id]);
            if (!empty($selected_categories)) {
                $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
                foreach ($selected_categories as $cat_id) {
                    $stmt->execute([$product_id, $cat_id]);
                }
            }


            // Update Product Variants
            $pdo->prepare("DELETE FROM product_variants WHERE product_id = ?")->execute([$product_id]);
            if (isset($_POST['v_size'])) {
                $stmt = $pdo->prepare("INSERT INTO product_variants (product_id, sku, size, color, color_hex, image, stock_quantity, price_adjustment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($_POST['v_size'] as $index => $v_size) {
                    $v_color = $_POST['v_color'][$index] ?? '';
                    $v_color_hex = $_POST['v_color_hex'][$index] ?? '';
                    $v_image = $_POST['v_image'][$index] ?? '';
                    $v_stock = $_POST['v_stock'][$index] ?? 0;
                    $v_price = $_POST['v_price'][$index] ?? 0;
                    $v_sku_manual = $_POST['v_sku'][$index] ?? '';

                    if (empty($v_size) && empty($v_color)) continue;

                    $v_sku = !empty($v_sku_manual) ? $v_sku_manual : $product['sku'] . '-' . ($index + 1);
                    $stmt->execute([$product_id, $v_sku, $v_size, $v_color, $v_color_hex, $v_image, $v_stock, $v_price]);
                }
            }



            $_SESSION['success'] = "Product updated successfully!";
            // Redirect to refresh data
            header("Location: product-edit.php?id=$product_id");
            exit();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Fetch Categories with hierarchy for Organization panel
$raw_cats_edit = $pdo->query("SELECT * FROM categories ORDER BY ISNULL(parent_id) DESC, category_name ASC")->fetchAll();
$cat_org_tree = [];
foreach ($raw_cats_edit as $c) {
    if (empty($c['parent_id'])) {
        $c['children'] = [];
        $cat_org_tree[$c['category_id']] = $c;
    }
}
foreach ($raw_cats_edit as $c) {
    if (!empty($c['parent_id']) && isset($cat_org_tree[$c['parent_id']])) {
        $cat_org_tree[$c['parent_id']]['children'][] = $c;
    }
}

// Fetch currently selected categories
$stmt = $pdo->prepare("SELECT category_id FROM product_categories WHERE product_id = ?");
$stmt->execute([$product_id]);
$current_categories = $stmt->fetchAll(PDO::FETCH_COLUMN);



$page_title = 'Edit Product';
include 'includes/header.php';
?>

<div class="container-fluid py-4 admin-content-wrapper">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Edit Product</h1>
        <a href="products.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="material-icons" style="font-size: 18px;">arrow_back</i> Back to List
        </a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" id="editProductForm">
        <div class="row">
            <!-- Left Column: Sidebar (Quick Navigation) -->
            <div class="col-lg-3">
                <!-- Quick Navigation -->
                <div class="card quick-nav-card sticky-top" style="top: 90px; z-index: 10;">
                    <div class="card-body">
                        <h6 class="quick-nav-title">Quick navigation</h6>
                        <nav class="nav flex-column">
                            <a class="quick-nav-item" href="#section_general">Product Information</a>
                            <a class="quick-nav-item" href="#section_media">Product Media</a>
                            <a class="quick-nav-item" href="#section_pricing">Pricing</a>
                            <a class="quick-nav-item" href="#section_inventory">Inventory</a>
                            <a class="quick-nav-item" href="#section_status">Status</a>
                            <a class="quick-nav-item" href="#section_organization">Organization</a>
                        </nav>
                        <div class="mt-3">
                            <button type="submit" form="editProductForm" class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 8px; font-size: 13px; font-weight: 600; padding: 10px;">
                                <i class="material-icons" style="font-size: 18px;">save</i> Update Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Main Content -->
            <div class="col-lg-9">
                <!-- 1. General Information -->
                <div class="modern-card" id="section_general">
                    <div class="modern-card-body">
                        <h6 class="mb-4 font-weight-bold text-dark">General Information</h6>
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="long_description" class="form-label">Description</label>
                            <textarea class="form-control" id="long_description" name="long_description" rows="6"><?php echo htmlspecialchars($product['long_description']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Media -->
                <div class="modern-card" id="section_media">
                    <div class="modern-card-body">
                        <h6 class="mb-4 font-weight-bold text-dark">Media Gallery</h6>
                        <?php
                        // Fetch all images
                        $stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, image_id ASC");
                        $stmt->execute([$product_id]);
                        $product_images = $stmt->fetchAll();
                        ?>

                        <div class="row g-3 mb-4">
                            <?php if (empty($product_images)): ?>
                                <div class="col-12 text-center py-4 bg-light rounded text-muted">
                                    No images uploaded yet.
                                </div>
                            <?php else: ?>
                                <?php foreach ($product_images as $img): ?>
                                    <div class="col-md-3 col-6">
                                        <div class="position-relative border rounded p-2 text-center <?php echo $img['is_primary'] ? 'border-primary' : ''; ?>">
                                            <img src="<?php echo UPLOADS_URL . '/products/' . $img['image_url']; ?>" alt="Gallery Image" class="img-fluid rounded mb-2" style="height: 120px; width: 100%; object-fit: contain;">

                                            <div class="d-flex justify-content-center gap-1">
                                                <?php if (!$img['is_primary']): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-primary p-1" onclick="handleImgAction('set_primary', <?php echo $img['image_id']; ?>)" title="Set as Primary">
                                                        <i class="material-icons" style="font-size: 16px;">star</i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="badge bg-primary position-absolute top-0 start-0 m-2">Primary</span>
                                                <?php endif; ?>

                                                <button type="button" class="btn btn-sm btn-outline-danger p-1" onclick="handleImgAction('delete', <?php echo $img['image_id']; ?>)" title="Delete Image">
                                                    <i class="material-icons" style="font-size: 16px;">delete</i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Hidden fields for gallery actions -->
                        <input type="hidden" name="image_action" id="image_action">
                        <input type="hidden" name="image_id" id="image_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Add More Images</label>
                            <input type="file" class="form-control" id="product_images" name="product_images[]" accept="image/*" multiple>
                            <small class="text-muted d-block mt-1">Select multiple files to upload additional images.</small>
                        </div>
                    </div>
                </div>

                <script>
                    function handleImgAction(action, id) {
                        document.getElementById('image_action').value = action;
                        document.getElementById('image_id').value = id;
                        document.getElementById('editProductForm').submit();
                    }
                </script>

                <!-- 3. Pricing -->
                <div class="modern-card" id="section_pricing">
                    <div class="modern-card-body">
                        <h6 class="mb-4 font-weight-bold text-dark">Pricing</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regular_price" class="form-label">Price (<?php echo CURRENCY_SYMBOL; ?>) <small class="text-muted">(Incl. 12% GST)</small></label>
                                <input type="number" class="form-control" id="regular_price" name="regular_price" step="0.01" min="0"
                                    value="<?php echo $product['regular_price']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sale_price" class="form-label">Discounted Price (<?php echo CURRENCY_SYMBOL; ?>) <small class="text-muted">(Incl. 12% GST)</small></label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" step="0.01" min="0"
                                    value="<?php echo $product['sale_price']; ?>">
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="tax_included" disabled checked>
                            <label class="form-check-label text-muted" for="tax_included">
                                Charge tax on this product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 4. Inventory -->
                <div class="modern-card" id="section_inventory">
                    <div class="modern-card-body">
                        <h6 class="mb-4 font-weight-bold text-dark">Inventory</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sku" class="form-label">SKU (Stock Keeping Unit)</label>
                                <input type="text" class="form-control" id="sku" value="<?php echo htmlspecialchars($product['sku']); ?>" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock_quantity" class="form-label">Total Quantity</label>
                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0"
                                    value="<?php echo $product['stock_quantity']; ?>">
                                <small class="text-muted">Global stock if no variants are used.</small>
                            </div>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="track_inventory" name="track_inventory" value="1"
                                <?php echo $product['track_inventory'] ?? 1 ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="track_inventory">
                                Track inventory for this product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 5. Variants (Dukaan Style) -->
                <div class="modern-card" id="section_variants">
                    <div class="modern-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="m-0 font-weight-bold text-dark">Variants</h6>
                        </div>
                        <p class="text-muted mb-4" style="font-size: 13px;">Customize variants for size, color, and more to cater to all your customers’ preferences.</p>

                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM product_variants WHERE product_id = ? ORDER BY variant_id ASC");
                        $stmt->execute([$product_id]);
                        $variants = $stmt->fetchAll();
                        ?>

                        <div id="variant_options_display" class="mb-4" style="display: none;">
                            <!-- Option pills would go here if we tracked them -->
                        </div>

                        <div class="d-flex gap-3 mb-4">
                            <button type="button" class="btn-modern-outline" data-bs-toggle="modal" data-bs-target="#addVariantsModal">
                                Edit or add variants
                            </button>
                            <button type="button" class="btn-modern-outline primary" onclick="addVariantRow()">
                                <i class="material-icons" style="font-size: 18px;">add</i> Add Variant
                            </button>
                        </div>

                        <div id="variant_table_container" <?php echo empty($variants) ? 'style="display: none;"' : ''; ?>>
                            <div class="variant-modern-container">
                                <div class="variant-modern-header">
                                    <div></div>
                                    <div style="padding-left: 4px;">Variant</div>
                                    <div class="text-center">
                                        Price
                                        <i class="material-icons text-muted ms-1" style="font-size: 14px; cursor: pointer;" onclick="applyToAll('price')" title="Apply first price to all">arrow_downward</i>
                                    </div>
                                    <div class="text-center">
                                        Discounted price
                                        <i class="material-icons text-muted ms-1" style="font-size: 14px; cursor: pointer;" onclick="applyToAll('discount')" title="Apply first discount to all">arrow_downward</i>
                                    </div>
                                    <div class="text-center">SKU ID</div>
                                    <div></div>
                                </div>
                                <div id="variant_rows_container">
                                    <?php foreach ($variants as $index => $v): ?>
                                        <div class="variant-modern-row">
                                            <div class="variant-thumb-placeholder">
                                                <i class="material-icons">add_a_photo</i>
                                            </div>
                                            <div class="variant-info">
                                                <div class="variant-name"><?php echo htmlspecialchars($v['size'] . ($v['color'] ? ' / ' . $v['color'] : '')); ?></div>
                                                <div class="variant-status">In stock</div>
                                                <input type="hidden" name="v_size[]" value="<?php echo htmlspecialchars($v['size']); ?>">
                                                <input type="hidden" name="v_color[]" value="<?php echo htmlspecialchars($v['color']); ?>">
                                                <input type="hidden" name="v_color_hex[]" value="<?php echo htmlspecialchars($v['color_hex']); ?>">
                                            </div>
                                            <div class="input-with-icon">
                                                <span class="input-icon-prefix">₹</span>
                                                <input type="number" class="form-control" value="<?php echo $product['regular_price']; ?>" step="0.01">
                                            </div>
                                            <div class="input-with-icon">
                                                <span class="input-icon-prefix">₹</span>
                                                <input type="number" name="v_price[]" class="form-control" value="<?php echo $v['price_adjustment']; ?>" step="0.01">
                                            </div>
                                            <div class="input-with-icon">
                                                <input type="text" name="v_sku[]" class="form-control" value="<?php echo htmlspecialchars($v['sku']); ?>" placeholder="Eg. 100000">
                                                <input type="hidden" name="v_stock[]" value="<?php echo $v['stock_quantity']; ?>">
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-link text-danger p-0" onclick="removeVariantRow(this)">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div id="variant_empty_state" class="text-center py-5" <?php echo !empty($variants) ? 'style="display: none;"' : ''; ?>>
                            <i class="material-icons text-muted" style="font-size: 48px;">inventory_2</i>
                            <p class="text-muted mt-2">No variants added yet. Click 'Edit or add variants' to get started.</p>
                        </div>
                    </div>
                </div>

                <!-- Add Variants Modal -->
                <div class="modal fade" id="addVariantsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add variants</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="options_container">
                                    <!-- Option Row 1 (Default) -->
                                    <div class="option-row" data-option-id="1">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Option name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control option-name" placeholder="e.g. size" value="size">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Option values <span class="text-danger">*</span></label>
                                                <div class="tags-input-container">
                                                    <div class="tags-list d-flex flex-wrap gap-2"></div>
                                                    <input type="text" class="tags-input" placeholder="Enter values...">
                                                </div>
                                            </div>
                                        </div>
                                        <i class="material-icons btn-remove-option" onclick="removeOptionRow(this)">delete</i>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-link text-primary p-0 mt-2 d-flex align-items-center" onclick="addOptionRow()">
                                    <i class="material-icons me-1" style="font-size: 18px;">add</i> Add another option
                                </button>
                            </div>
                            <div class="modal-footer border-0 d-flex justify-content-between align-items-center">
                                <div class="modal-footer-info">
                                    <i class="material-icons text-muted" style="font-size: 18px;">info_outline</i>
                                    You can add prices, images, quantity, etc after this step.
                                </div>
                                <button type="button" class="btn btn-modal-primary" onclick="generateVariants()">Add variants</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Tag Input Logic
                    function initTagInput(container) {
                        const input = container.querySelector('.tags-input');
                        const tagsList = container.querySelector('.tags-list');

                        input.addEventListener('keydown', function(e) {
                            if (e.key === 'Enter' || e.key === ',') {
                                e.preventDefault();
                                const val = this.value.trim();
                                if (val) addTag(val, tagsList, this);
                            } else if (e.key === 'Backspace' && !this.value) {
                                const lastTag = tagsList.lastElementChild;
                                if (lastTag) lastTag.remove();
                            }
                        });

                        // Click container to focus input
                        container.addEventListener('click', () => input.focus());
                    }

                    function addTag(val, tagsList, input) {
                        // Check for duplicates
                        const existing = Array.from(tagsList.querySelectorAll('.tag-badge')).map(t => t.innerText.replace('close', '').trim());
                        if (existing.includes(val)) {
                            input.value = '';
                            return;
                        }

                        const tag = document.createElement('div');
                        tag.className = 'tag-badge';
                        tag.innerHTML = `${val} <i class="material-icons tag-remove" onclick="this.parentElement.remove()">close</i>`;
                        tagsList.appendChild(tag);
                        input.value = '';
                    }

                    // Initialize first option row
                    document.querySelectorAll('.tags-input-container').forEach(initTagInput);

                    function addOptionRow() {
                        const container = document.getElementById('options_container');
                        const rows = container.querySelectorAll('.option-row');
                        if (rows.length >= 3) {
                            venaroAlert('Maximum 3 options allowed.', 'warning');
                            return;
                        }

                        const nextId = rows.length + 1;
                        const newRow = document.createElement('div');
                        newRow.className = 'option-row';
                        newRow.dataset.optionId = nextId;
                        newRow.innerHTML = `
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Option name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control option-name" placeholder="e.g. color">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Option values <span class="text-danger">*</span></label>
                                <div class="tags-input-container">
                                    <div class="tags-list d-flex flex-wrap gap-2"></div>
                                    <input type="text" class="tags-input" placeholder="Enter values...">
                                </div>
                            </div>
                        </div>
                        <i class="material-icons btn-remove-option" onclick="removeOptionRow(this)">delete</i>
                    `;
                        container.appendChild(newRow);
                        initTagInput(newRow.querySelector('.tags-input-container'));
                    }

                    function removeOptionRow(btn) {
                        const rows = document.querySelectorAll('.option-row');
                        if (rows.length > 1) {
                            btn.closest('.option-row').remove();
                        } else {
                            venaroAlert('At least one option is required.', 'warning');
                        }
                    }

                    function addVariantRow() {
                        const container = document.getElementById('variant_rows_container');
                        const salePrice = document.getElementById('sale_price').value;
                        const regularPrice = document.getElementById('regular_price').value;

                        const row = document.createElement('div');
                        row.className = 'variant-modern-row';
                        row.innerHTML = `
                        <div class="variant-thumb-placeholder">
                            <i class="material-icons">add_a_photo</i>
                        </div>
                        <div class="variant-info">
                            <div class="variant-name">New Variant</div>
                            <div class="variant-status">In stock</div>
                            <div class="mt-1 d-flex gap-2">
                                <select name="v_size[]" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="">Size</option>
                                    <option value="XS">XS</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="2XL">2XL</option>
                                    <option value="3XL">3XL</option>
                                </select>
                                <input type="text" name="v_color[]" class="form-control form-control-sm" placeholder="Color" style="width: 80px;">
                                <input type="color" name="v_color_hex[]" class="form-control form-control-color form-control-sm" value="#000000" style="width: 30px; height: 30px; padding: 2px;">
                            </div>
                        </div>
                        <div class="input-with-icon">
                            <span class="input-icon-prefix">₹</span>
                            <input type="number" class="form-control" value="${regularPrice || '0'}" step="0.01">
                        </div>
                        <div class="input-with-icon">
                            <span class="input-icon-prefix">₹</span>
                            <input type="number" name="v_price[]" class="form-control" value="${salePrice || '0'}" step="0.01">
                        </div>
                        <div class="input-with-icon">
                            <input type="text" name="v_sku[]" class="form-control" placeholder="Eg. 100000">
                            <input type="hidden" name="v_stock[]" value="100">
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-link text-danger p-0" onclick="removeVariantRow(this)">
                                <i class="material-icons">delete</i>
                            </button>
                        </div>
                    `;
                        container.appendChild(row);

                        document.getElementById('variant_table_container').style.display = 'block';
                        document.getElementById('variant_empty_state').style.display = 'none';
                    }

                    function removeVariantRow(btn) {
                        btn.closest('.variant-modern-row').remove();
                        const container = document.getElementById('variant_rows_container');
                        if (container.children.length === 0) {
                            document.getElementById('variant_table_container').style.display = 'none';
                            document.getElementById('variant_empty_state').style.display = 'block';
                        }
                    }

                    function generateVariants() {
                        const optionRows = document.querySelectorAll('.option-row');
                        const options = [];

                        optionRows.forEach(row => {
                            const name = row.querySelector('.option-name').value.trim();
                            const values = Array.from(row.querySelectorAll('.tag-badge')).map(t => t.innerText.replace('close', '').trim());
                            if (name && values.length > 0) {
                                options.push({
                                    name,
                                    values
                                });
                            }
                        });

                        if (options.length === 0) {
                            venaroAlert('Please add at least one option with values.', 'warning');
                            return;
                        }

                        // Display pills
                        const optionsDisplay = document.getElementById('variant_options_display');
                        optionsDisplay.innerHTML = '';
                        options.forEach(opt => {
                            const optGroup = document.createElement('div');
                            optGroup.className = 'mb-2';
                            optGroup.innerHTML = `<div class="text-muted mb-2 text-capitalize" style="font-size: 13px;">${opt.name}</div>`;
                            const pillContainer = document.createElement('div');
                            pillContainer.className = 'variant-option-pills';
                            opt.values.forEach(val => {
                                const pill = document.createElement('span');
                                pill.className = 'variant-pill';
                                pill.innerText = val;
                                pillContainer.appendChild(pill);
                            });
                            optGroup.appendChild(pillContainer);
                            optionsDisplay.appendChild(optGroup);
                        });
                        optionsDisplay.style.display = 'block';

                        // Generate combinations
                        const combinations = options.reduce((a, b) => {
                            const result = [];
                            a.forEach(a_val => {
                                b.values.forEach(b_val => {
                                    result.push([...a_val, {
                                        name: b.name,
                                        value: b_val
                                    }]);
                                });
                            });
                            return result;
                        }, [
                            []
                        ]);

                        const container = document.getElementById('variant_rows_container');
                        container.innerHTML = '';

                        const salePrice = document.getElementById('sale_price').value;
                        const regularPrice = document.getElementById('regular_price').value;

                        const colorMap = {
                            'black': '#000000',
                            'white': '#FFFFFF',
                            'red': '#FF0000',
                            'blue': '#0000FF',
                            'green': '#008000',
                            'yellow': '#FFFF00',
                            'orange': '#FFA500',
                            'purple': '#800080',
                            'pink': '#FFC0CB',
                            'grey': '#808080',
                            'gray': '#808080',
                            'brown': '#A52A2A',
                            'navy': '#000080',
                            'navy blue': '#000080',
                            'royal blue': '#4169E1',
                            'sky blue': '#87CEEB',
                            'gold': '#FFD700',
                            'silver': '#C0C0C0',
                            'beige': '#F5F5DC',
                            'maroon': '#800000',
                            'olive': '#808000',
                            'teal': '#008080',
                            'cyan': '#00FFFF',
                            'magenta': '#FF00FF',
                            'charcoal': '#36454F',
                            'cream': '#FFFDD0',
                            'tan': '#D2B48C',
                            'khaki': '#F0E68C',
                            'coral': '#FF7F50',
                            'turquoise': '#40E0D0',
                            'lavender': '#E6E6FA'
                        };

                        combinations.forEach((combo, index) => {
                            const variantText = combo.map(c => c.value).join(' / ');
                            const size = combo.find(c => c.name.toLowerCase() === 'size')?.value || '';
                            const color = combo.find(c => c.name.toLowerCase().includes('color'))?.value || '';

                            let defaultHex = '#000000';
                            const colorLower = color.toLowerCase().trim();
                            if (colorMap[colorLower]) {
                                defaultHex = colorMap[colorLower];
                            }

                            const row = document.createElement('div');
                            row.className = 'variant-modern-row';
                            row.innerHTML = `
                            <div class="variant-thumb-placeholder">
                                <i class="material-icons">add_a_photo</i>
                            </div>
                            <div class="variant-info">
                                <div class="variant-name">${variantText}</div>
                                <div class="variant-status">In stock</div>
                                <input type="hidden" name="v_size[]" value="${size}">
                                <input type="hidden" name="v_color[]" value="${color}">
                                <input type="hidden" name="v_color_hex[]" value="${defaultHex}">
                            </div>
                            <div class="input-with-icon">
                                <span class="input-icon-prefix">₹</span>
                                <input type="number" class="form-control" value="${regularPrice || '0'}" step="0.01">
                            </div>
                            <div class="input-with-icon">
                                <span class="input-icon-prefix">₹</span>
                                <input type="number" name="v_price[]" class="form-control" value="${salePrice || '0'}" step="0.01">
                            </div>
                            <div class="input-with-icon">
                                <input type="text" name="v_sku[]" class="form-control" placeholder="Eg. 100000">
                                <input type="hidden" name="v_stock[]" value="100">
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-link text-danger p-0" onclick="removeVariantRow(this)">
                                    <i class="material-icons">delete</i>
                                </button>
                            </div>
                        `;
                            container.appendChild(row);
                        });

                        document.getElementById('variant_table_container').style.display = 'block';
                        document.getElementById('variant_empty_state').style.display = 'none';

                        const modalEl = document.getElementById('addVariantsModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();
                    }

                    function applyToAll(type) {
                        const rows = document.querySelectorAll('.variant-modern-row');
                        if (rows.length < 2) return;

                        let sourceValue = '';
                        const firstRow = rows[0];

                        if (type === 'price') {
                            // 3rd div is price container (index 2)
                            // The input is inside .input-with-icon
                            const input = firstRow.querySelectorAll('.input-with-icon input')[0];
                            if (input) sourceValue = input.value;
                        } else if (type === 'discount') {
                            // 4th div is discount container (index 3)
                            const input = firstRow.querySelectorAll('.input-with-icon input')[1]; // In the same row, but next input-with-icon? No, looking at HTML structure.
                            // Let's re-verify structure.
                            // Row HTML:
                            // div.variant-thumb-placeholder
                            // div.variant-info
                            // div.input-with-icon (Regular Price) -> input[type=number] (index 0 of inputs in this div? No, inputs are distributed)

                            // Let's select by attribute to be safer if possible, but they don't have unique classes.
                            // Structure:
                            // .variant-modern-row > .input-with-icon:nth-child(3) > input (Regular)
                            // .variant-modern-row > .input-with-icon:nth-child(4) > input (Discount)

                            const regularInput = firstRow.querySelector('.input-with-icon:nth-child(3) input');
                            const discountInput = firstRow.querySelector('.input-with-icon:nth-child(4) input');

                            if (type === 'price' && regularInput) sourceValue = regularInput.value;
                            if (type === 'discount' && discountInput) sourceValue = discountInput.value;
                        }

                        if (sourceValue === '') return;

                        rows.forEach((row, index) => {
                            if (index === 0) return; // Skip first row

                            if (type === 'price') {
                                const input = row.querySelector('.input-with-icon:nth-child(3) input');
                                if (input) input.value = sourceValue;
                            } else if (type === 'discount') {
                                const input = row.querySelector('.input-with-icon:nth-child(4) input');
                                if (input) input.value = sourceValue;
                            }
                        });

                        // Show toast/alert
                        const toast = document.createElement('div');
                        toast.className = 'position-fixed bottom-0 end-0 p-3';
                        toast.style.zIndex = '11';
                        toast.innerHTML = `
                        <div class="toast show align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    Applied ${type === 'price' ? 'price' : 'discount'} to all variants.
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    `;
                        document.body.appendChild(toast);
                        setTimeout(() => toast.remove(), 3000);
                    }
                </script>

                <!-- 5. Status -->
                <div class="modern-card" id="section_status">
                    <div class="modern-card-body">
                        <h6 class="mb-3 font-weight-bold text-dark">Status</h6>
                        <select class="form-select" id="status" name="status">
                            <option value="Active" <?php echo $product['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                            <option value="Draft" <?php echo $product['status'] == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="Archived" <?php echo $product['status'] == 'Archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                        <small class="text-muted d-block mt-2">
                            This product will be hidden from all sales channels if set to Draft.
                        </small>
                    </div>
                </div>

                <!-- 6. Organization -->
                <div class="modern-card" id="section_organization">
                    <div class="modern-card-body">
                        <h6 class="mb-4 font-weight-bold text-dark">Organization</h6>
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-uppercase">Categories &amp; Subcategories</label>
                            <div class="border rounded p-3" style="max-height: 260px; overflow-y: auto;">
                                <?php foreach ($cat_org_tree as $parent_cat): ?>
                                    <div class="mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                value="<?php echo $parent_cat['category_id']; ?>"
                                                id="cat_<?php echo $parent_cat['category_id']; ?>"
                                                <?php echo in_array($parent_cat['category_id'], $current_categories) ? 'checked' : ''; ?>>
                                            <label class="form-check-label fw-semibold" for="cat_<?php echo $parent_cat['category_id']; ?>" style="font-size: 12px; color: #212529;">
                                                <?php echo htmlspecialchars($parent_cat['category_name']); ?>
                                            </label>
                                        </div>
                                        <?php if (!empty($parent_cat['children'])): ?>
                                            <div style="padding-left: 20px;">
                                                <?php foreach ($parent_cat['children'] as $child): ?>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox" name="categories[]"
                                                            value="<?php echo $child['category_id']; ?>"
                                                            id="cat_<?php echo $child['category_id']; ?>"
                                                            <?php echo in_array($child['category_id'], $current_categories) ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="cat_<?php echo $child['category_id']; ?>" style="font-size: 11px; color: #6c757d;">
                                                            └ <?php echo htmlspecialchars($child['category_name']); ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="window.location.href='products.php?delete_id=<?php echo $product_id; ?>'">
                        <i class="material-icons" style="vertical-align: middle; font-size: 16px;">delete</i> Delete Product
                    </button>
                </div>
            </div>
        </div>


    </form>
</div>

<?php include 'includes/footer.php'; ?>