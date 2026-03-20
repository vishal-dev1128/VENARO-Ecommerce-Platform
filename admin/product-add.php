<?php
session_start();
require_once '../config.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $sku = 'VN-' . strtoupper(uniqid()); // Auto-generate SKU

    // Generate Slug
    $slug = strtolower(trim($product_name));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
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


            // Ensure Slug is unique
            $original_slug = $slug;
            $count = 1;
            while (true) {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
                $stmt->execute([$slug]);
                if ($stmt->fetchColumn() == 0) break;
                $slug = $original_slug . '-' . $count;
                $count++;
            }

            // Insert Product
            $sql = "INSERT INTO products (product_name, sku, slug, regular_price, sale_price, stock_quantity, status, short_description, long_description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $product_name,
                $sku,
                $slug,
                $regular_price,
                $sale_price,
                $stock_quantity,
                $status,
                $short_description,
                $long_description
            ]);

            $product_id = $pdo->lastInsertId();

            // Handle Multiple Image Uploads
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
                            // Set the first image as primary, others as secondary
                            $is_primary = ($i === 0) ? 1 : 0;

                            $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url, is_primary, display_order) VALUES (?, ?, ?, ?)");
                            $stmt->execute([$product_id, $new_filename, $is_primary, $i]);
                        }
                    }
                }
            }

            // Save Product Categories
            if (!empty($selected_categories)) {
                $pdo->prepare("DELETE FROM product_categories WHERE product_id = ?")->execute([$product_id]);
                $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
                foreach ($selected_categories as $cat_id) {
                    $stmt->execute([$product_id, $cat_id]);
                }
            }


            // Save Product Variants
            if (isset($_POST['v_size'])) {
                $stmt = $pdo->prepare("INSERT INTO product_variants (product_id, sku, size, color, color_hex, image, stock_quantity, price_adjustment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($_POST['v_size'] as $index => $v_size) {
                    $v_color = $_POST['v_color'][$index] ?? '';
                    $v_color_hex = $_POST['v_color_hex'][$index] ?? '';
                    $v_image = $_POST['v_image'][$index] ?? '';
                    $v_stock = $_POST['v_stock'][$index] ?? 0;
                    $v_price = $_POST['v_price'][$index] ?? 0;
                    $v_sku_manual = $_POST['v_sku'][$index] ?? '';

                    // Skip if both size and color are empty
                    if (empty($v_size) && empty($v_color)) continue;

                    $v_sku = !empty($v_sku_manual) ? $v_sku_manual : $sku . '-' . ($index + 1);
                    $stmt->execute([$product_id, $v_sku, $v_size, $v_color, $v_color_hex, $v_image, $v_stock, $v_price]);
                }
            }



            $_SESSION['success'] = "Product added successfully!";
            header('Location: products.php');
            exit();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Fetch Categories with hierarchy for Organization panel
$raw_cats = $pdo->query("SELECT * FROM categories ORDER BY ISNULL(parent_id) DESC, category_name ASC")->fetchAll();
$cat_org_tree = [];
foreach ($raw_cats as $c) {
    if (empty($c['parent_id'])) {
        $c['children'] = [];
        $cat_org_tree[$c['category_id']] = $c;
    }
}
foreach ($raw_cats as $c) {
    if (!empty($c['parent_id']) && isset($cat_org_tree[$c['parent_id']])) {
        $cat_org_tree[$c['parent_id']]['children'][] = $c;
    }
}



$page_title = 'Add New Product';
include 'includes/header.php';
?>

<div class="container-fluid py-4 admin-content-wrapper">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Add Product</h1>
        <a href="products.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <i class="material-icons" style="font-size: 18px;">arrow_back</i> Back to List
        </a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" id="addProductForm">
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
                            <a class="quick-nav-item" href="#section_seo">Search engine listing</a>
                            <a class="quick-nav-item" href="#section_status">Status</a>
                            <a class="quick-nav-item" href="#section_organization">Organization</a>
                        </nav>
                        <div class="mt-3">
                            <button type="submit" form="addProductForm" class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 8px; font-size: 13px; font-weight: 600; padding: 10px;">
                                <i class="material-icons" style="font-size: 18px;">save</i> Save Product
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
                            <label for="product_name" class="form-label">Title</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="e.g. Linen Blend Shirt" required>
                        </div>
                        <div class="mb-3">
                            <label for="long_description" class="form-label">Description</label>
                            <textarea class="form-control" id="long_description" name="long_description" rows="6" placeholder="Product details..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Media -->
                <div class="modern-card" id="section_media">
                    <div class="modern-card-body">
                        <h6 class="mb-4 font-weight-bold text-dark">Media</h6>
                        <div class="mb-3">
                            <label class="form-label">Product Images</label>
                            <input type="file" class="form-control" id="product_images" name="product_images[]" accept="image/*" multiple>
                            <small class="text-muted d-block mt-1">First image will be the primary thumbnail. You can upload multiple images.</small>
                        </div>
                    </div>
                </div>

                <!-- 3. Pricing -->
                <div class="modern-card" id="section_pricing">
                    <div class="modern-card-body">
                        <h6 class="mb-4 font-weight-bold text-dark">Pricing</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regular_price" class="form-label">Price (<?php echo CURRENCY_SYMBOL; ?>) <small class="text-muted">(Incl. 12% GST)</small></label>
                                <input type="number" class="form-control" id="regular_price" name="regular_price" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sale_price" class="form-label">Discounted Price (<?php echo CURRENCY_SYMBOL; ?>) <small class="text-muted">(Incl. 12% GST)</small></label>
                                <input type="number" class="form-control" id="sale_price" name="sale_price" step="0.01" min="0" placeholder="0.00">
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
                                <input type="text" class="form-control" id="sku" value="Auto-generated" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock_quantity" class="form-label">Total Quantity</label>
                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="100" min="0">
                                <small class="text-muted">Global stock if no variants are used.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Variants (Modern Dukaan Style) -->
                <div class="modern-card" id="section_variants">
                    <div class="modern-card-body">
                        <h6 class="mb-1 font-weight-bold text-dark">Variants</h6>
                        <p class="text-muted mb-4" style="font-size: 13px;">Customize variants for size, color, and more to cater to all your customers’ preferences.</p>

                        <div id="variant_options_display" class="mb-4" style="display: none;">
                            <!-- Placeholder for pills like 'size: XS, S, M' -->
                        </div>

                        <div id="variant_action_buttons" class="d-flex gap-3 mb-4" style="display: none;">
                            <button type="button" class="btn-modern-outline" data-bs-toggle="modal" data-bs-target="#addVariantsModal">
                                Edit or add variants
                            </button>
                            <button type="button" class="btn-modern-outline primary" onclick="addVariantRow()">
                                <i class="material-icons" style="font-size: 18px;">add</i> Add Variant
                            </button>
                        </div>

                        <div id="variant_table_container" style="display: none;">
                            <div class="variant-modern-container">
                                <div class="variant-modern-header">
                                    <div></div>
                                    <div style="padding-left: 4px;">Variant</div>
                                    <div class="text-center">Price</div>
                                    <div class="text-center">Discounted price</div>
                                    <div class="text-center">SKU ID</div>
                                    <div></div>
                                </div>
                                <div id="variant_rows_container">
                                    <!-- Dynamic rows will be injected here -->
                                </div>
                            </div>
                        </div>

                        <div id="variant_empty_state" class="text-center py-4">
                            <button type="button" class="btn-modern-outline" data-bs-toggle="modal" data-bs-target="#addVariantsModal">
                                Add variants
                            </button>
                        </div>

                        <small class="text-muted d-block mt-3">Note: Variants will override the global stock quantity.</small>
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
                                                <input type="text" class="form-control option-name" placeholder="E.g. Style, Material">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Option values <span class="text-danger">*</span></label>
                                                <div class="tags-input-container">
                                                    <div class="tags-list d-flex flex-wrap gap-2"></div>
                                                    <input type="text" class="tags-input" placeholder="Separate values with commas or press enter">
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

                    // Auto-fill logic: Pre-populate with common size values
                    function autoFillVariantOptions() {
                        const firstOptionRow = document.querySelector('.option-row');
                        if (firstOptionRow) {
                            // Set option name to "size"
                            const optionNameInput = firstOptionRow.querySelector('.option-name');
                            if (optionNameInput && !optionNameInput.value) {
                                optionNameInput.value = 'size';
                            }

                            // Add common size values
                            const tagsList = firstOptionRow.querySelector('.tags-list');
                            const tagsInput = firstOptionRow.querySelector('.tags-input');

                            if (tagsList && tagsList.children.length === 0) {
                                const commonSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                                commonSizes.forEach(size => {
                                    addTag(size, tagsList, tagsInput);
                                });
                            }
                        }
                    }

                    // Trigger auto-fill when modal opens
                    const addVariantsModal = document.getElementById('addVariantsModal');
                    addVariantsModal.addEventListener('shown.bs.modal', function() {
                        autoFillVariantOptions();
                    });

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
                                <input type="text" class="form-control option-name" placeholder="E.g. Style, Material">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Option values <span class="text-danger">*</span></label>
                                <div class="tags-input-container">
                                    <div class="tags-list d-flex flex-wrap gap-2"></div>
                                    <input type="text" class="tags-input" placeholder="Separate values with commas or press enter">
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
                        document.getElementById('variant_table_container').style.display = 'block';
                        document.getElementById('variant_empty_state').style.display = 'none';
                        document.getElementById('variant_action_buttons').style.display = 'flex';
                    }

                    function removeVariantRow(btn) {
                        btn.closest('.variant-modern-row').remove();
                        const container = document.getElementById('variant_rows_container');
                        if (container.children.length === 0) {
                            document.getElementById('variant_table_container').style.display = 'none';
                            document.getElementById('variant_action_buttons').style.display = 'none';
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
                        document.getElementById('variant_table_container').style.display = 'block';
                        document.getElementById('variant_action_buttons').style.display = 'flex';
                        document.getElementById('variant_empty_state').style.display = 'none';

                        const modalEl = document.getElementById('addVariantsModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();
                    }
                </script>
                </script>

                <!-- 5. Search Engine Listing Preview -->
                <div class="card shadow-sm border-0 mb-4" id="section_seo">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-dark">Search engine listing</h6>
                    </div>
                    <div class="card-body">
                        <div class="search-listing-preview p-3 bg-light rounded">
                            <div class="preview-store-name text-muted small mb-1">My Store</div>
                            <div class="preview-url text-muted small mb-2" id="preview_url">
                                <?php echo SITE_URL; ?>/products/product-slug
                            </div>
                            <div class="preview-title mb-2">
                                <a href="#" class="preview-title-link text-decoration-none" id="preview_title">Product Title</a>
                            </div>
                            <div class="preview-description text-muted small mb-2" id="preview_description">
                                Product description will appear here. Add a description to see the preview update in real-time.
                            </div>
                            <div class="preview-price text-dark" id="preview_price">₹0.00 INR</div>
                        </div>
                    </div>
                </div>

                <!-- 6. Status -->
                <div class="card shadow-sm border-0 mb-4" id="section_status">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-dark">Status</h6>
                    </div>
                    <div class="card-body">
                        <select class="form-select" id="status" name="status">
                            <option value="Active">Active</option>
                            <option value="Draft">Draft</option>
                            <option value="Archived">Archived</option>
                        </select>
                        <small class="text-muted d-block mt-2">
                            This product will be hidden from all sales channels if set to Draft.
                        </small>
                    </div>
                </div>

                <!-- 7. Organization -->
                <div class="card shadow-sm border-0 mb-4" id="section_organization">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-dark">Organization</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            <label class="form-label fw-bold small text-uppercase">Categories &amp; Subcategories</label>
                            <div class="border rounded p-3" style="max-height: 260px; overflow-y: auto;">
                                <?php foreach ($cat_org_tree as $parent_cat): ?>
                                    <!-- Parent -->
                                    <div class="mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                value="<?php echo $parent_cat['category_id']; ?>"
                                                id="cat_<?php echo $parent_cat['category_id']; ?>">
                                            <label class="form-check-label fw-semibold" for="cat_<?php echo $parent_cat['category_id']; ?>" style="font-size: 12px; color: #212529;">
                                                <?php echo htmlspecialchars($parent_cat['category_name']); ?>
                                            </label>
                                        </div>
                                        <!-- Subcategories -->
                                        <?php if (!empty($parent_cat['children'])): ?>
                                            <div style="padding-left: 20px;">
                                                <?php foreach ($parent_cat['children'] as $child): ?>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox" name="categories[]"
                                                            value="<?php echo $child['category_id']; ?>"
                                                            id="cat_<?php echo $child['category_id']; ?>">
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
            </div>
        </div>

        <script>
            // Real-time Search Engine Listing Preview
            function slugify(text) {
                return text.toString().toLowerCase()
                    .replace(/\s+/g, '-') // Replace spaces with -
                    .replace(/[^\w\-]+/g, '') // Remove all non-word chars
                    .replace(/\-\-+/g, '-') // Replace multiple - with single -
                    .replace(/^-+/, '') // Trim - from start of text
                    .replace(/-+$/, ''); // Trim - from end of text
            }

            function truncateText(text, maxLength) {
                if (text.length <= maxLength) return text;
                return text.substr(0, maxLength) + '...';
            }

            function updatePreview() {
                // Update title
                const titleInput = document.getElementById('product_name');
                const previewTitle = document.getElementById('preview_title');
                const title = titleInput.value.trim() || 'Product Title';
                previewTitle.textContent = title;

                // Update URL slug
                const previewUrl = document.getElementById('preview_url');
                const slug = titleInput.value ? slugify(titleInput.value) : 'product-slug';
                previewUrl.textContent = '<?php echo SITE_URL; ?>/products/' + slug;

                // Update description
                const descInput = document.getElementById('long_description');
                const previewDesc = document.getElementById('preview_description');
                const description = descInput.value.trim() || 'Product description will appear here. Add a description to see the preview update in real-time.';
                previewDesc.textContent = truncateText(description, 160);

                // Update price
                const priceInput = document.getElementById('regular_price');
                const salePriceInput = document.getElementById('sale_price');
                const previewPrice = document.getElementById('preview_price');

                const regularPrice = parseFloat(priceInput.value) || 0;
                const salePrice = parseFloat(salePriceInput.value) || 0;

                const displayPrice = salePrice > 0 ? salePrice : regularPrice;
                previewPrice.textContent = '₹' + displayPrice.toFixed(2) + ' INR';
            }

            // Attach event listeners
            document.addEventListener('DOMContentLoaded', function() {
                const productNameInput = document.getElementById('product_name');
                const descriptionInput = document.getElementById('long_description');
                const regularPriceInput = document.getElementById('regular_price');
                const salePriceInput = document.getElementById('sale_price');

                if (productNameInput) productNameInput.addEventListener('input', updatePreview);
                if (descriptionInput) descriptionInput.addEventListener('input', updatePreview);
                if (regularPriceInput) regularPriceInput.addEventListener('input', updatePreview);
                if (salePriceInput) salePriceInput.addEventListener('input', updatePreview);

                // Initial update
                updatePreview();
            });
        </script>


    </form>
</div>

<?php include 'includes/footer.php'; ?>