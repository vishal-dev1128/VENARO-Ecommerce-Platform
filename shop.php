<?php
require_once 'config.php';

$page_title = 'Shop';

// Get filter parameters
$category_slug = $_GET['category'] ?? '';
$collection_slug = $_GET['collection'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'popularity';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$sizes = $_GET['sizes'] ?? [];
$colors = $_GET['colors'] ?? [];
$page = max(1, intval($_GET['page'] ?? 1));

// Build query
$where = ["p.status = 'Active'"];
$params = [];

if ($category_slug) {
    $where[] = "c.slug = ?";
    $params[] = $category_slug;
}

if ($collection_slug) {
    $where[] = "col.slug = ?";
    $params[] = $collection_slug;
}

if ($search) {
    $where[] = "(p.product_name LIKE ? OR p.short_description LIKE ? OR p.long_description LIKE ?)";
    $search_term = "%{$search}%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

if ($min_price) {
    $where[] = "COALESCE(p.sale_price, p.regular_price) >= ?";
    $params[] = $min_price;
}

if ($max_price) {
    $where[] = "COALESCE(p.sale_price, p.regular_price) <= ?";
    $params[] = $max_price;
}

$where_clause = implode(' AND ', $where);

// Sorting
$order_by = match ($sort) {
    'price-asc' => 'COALESCE(p.sale_price, p.regular_price) ASC',
    'price-desc' => 'COALESCE(p.sale_price, p.regular_price) DESC',
    'newest' => 'p.created_at DESC',
    'name-asc' => 'p.product_name ASC',
    'name-desc' => 'p.product_name DESC',
    default => 'p.views DESC, p.created_at DESC'
};

// Count total products
$count_sql = "
    SELECT COUNT(DISTINCT p.product_id)
    FROM products p
    LEFT JOIN product_categories pc ON p.product_id = pc.product_id
    LEFT JOIN categories c ON pc.category_id = c.category_id
    LEFT JOIN product_collections pcol ON p.product_id = pcol.product_id
    LEFT JOIN collections col ON pcol.collection_id = col.collection_id
    WHERE {$where_clause}
";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_products = $stmt->fetchColumn();

// Calculate pagination
$total_pages = ceil($total_products / PRODUCTS_PER_PAGE);
$offset = ($page - 1) * PRODUCTS_PER_PAGE;

// Fetch products
$sql = "
    SELECT DISTINCT p.*,
           (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = TRUE LIMIT 1) as primary_image,
           (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = FALSE LIMIT 1) as secondary_image,
           (SELECT COUNT(*) FROM product_collections pc2 JOIN collections c2 ON pc2.collection_id = c2.collection_id WHERE pc2.product_id = p.product_id AND c2.slug = 'new-arrival') as is_new_arrival,
           (SELECT ROUND(AVG(rating),1) FROM reviews WHERE product_id = p.product_id AND status = 'Approved') as avg_rating,
           (SELECT COUNT(*) FROM reviews WHERE product_id = p.product_id AND status = 'Approved') as review_count
    FROM products p
    LEFT JOIN product_categories pc ON p.product_id = pc.product_id
    LEFT JOIN categories c ON pc.category_id = c.category_id
    LEFT JOIN product_collections pcol ON p.product_id = pcol.product_id
    LEFT JOIN collections col ON pcol.collection_id = col.collection_id
    WHERE {$where_clause}
    ORDER BY {$order_by}
    LIMIT ? OFFSET ?
";
$params[] = PRODUCTS_PER_PAGE;
$params[] = $offset;
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch categories with hierarchy for filter sidebar
$all_filter_cats = $pdo->query("SELECT * FROM categories WHERE status = 'Active' ORDER BY ISNULL(parent_id) DESC, COALESCE(display_order, 9999) ASC, category_name ASC")->fetchAll();
$filter_cat_tree = [];
foreach ($all_filter_cats as $c) {
    if (empty($c['parent_id'])) {
        $c['children'] = [];
        $filter_cat_tree[$c['category_id']] = $c;
    }
}
foreach ($all_filter_cats as $c) {
    if (!empty($c['parent_id']) && isset($filter_cat_tree[$c['parent_id']])) {
        $filter_cat_tree[$c['parent_id']]['children'][] = $c;
    }
}



include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="filter-card shadow-sm mb-4">
                <div class="filter-header">
                    <h5>Filters</h5>
                    <a href="<?php echo SITE_URL; ?>/shop.php" class="clear-all-link">Clear all</a>
                </div>

                <div class="filter-group-body">
                    <!-- Categories -->
                    <div class="mb-4">
                        <h6 class="filter-section-title">CATEGORIES</h6>

                        <?php foreach ($filter_cat_tree as $parent_cat): ?>
                            <!-- Parent Category -->
                            <div class="mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category"
                                        id="cat_<?php echo $parent_cat['category_id']; ?>"
                                        value="<?php echo $parent_cat['slug']; ?>"
                                        <?php echo $category_slug === $parent_cat['slug'] ? 'checked' : ''; ?>
                                        onchange="window.location.href='<?php echo SITE_URL; ?>/shop.php?category=<?php echo $parent_cat['slug']; ?>'">
                                    <label class="form-check-label" for="cat_<?php echo $parent_cat['category_id']; ?>" style="font-weight: 600; font-size: 12px; color: #212529;">
                                        <?php echo htmlspecialchars($parent_cat['category_name']); ?>
                                        <?php if (!empty($parent_cat['children'])): ?>
                                            <span style="color: #adb5bd; font-size: 10px; font-weight: 400;">(<?php echo count($parent_cat['children']); ?>)</span>
                                        <?php endif; ?>
                                    </label>
                                </div>

                                <!-- Subcategories -->
                                <?php if (!empty($parent_cat['children'])): ?>
                                    <div style="padding-left: 16px; margin-top: 2px;">
                                        <?php foreach ($parent_cat['children'] as $child_cat): ?>
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="radio" name="category"
                                                    id="cat_<?php echo $child_cat['category_id']; ?>"
                                                    value="<?php echo $child_cat['slug']; ?>"
                                                    <?php echo $category_slug === $child_cat['slug'] ? 'checked' : ''; ?>
                                                    onchange="window.location.href='<?php echo SITE_URL; ?>/shop.php?category=<?php echo $child_cat['slug']; ?>'">
                                                <label class="form-check-label" for="cat_<?php echo $child_cat['category_id']; ?>" style="font-size: 11px; color: #6c757d;">
                                                    └ <?php echo htmlspecialchars($child_cat['category_name']); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>



                    <!-- Price Range -->
                    <div class="mb-4">
                        <h6 class="filter-section-title">PRICE RANGE</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" placeholder="Min"
                                    id="minPrice" value="<?php echo $min_price; ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" placeholder="Max"
                                    id="maxPrice" value="<?php echo $max_price; ?>">
                            </div>
                        </div>
                        <button class="btn w-100 mt-3" onclick="applyPriceFilter()" style="background:#000; color:#fff; border-radius:0; padding:10px; font-family:'Montserrat',sans-serif; font-size:10px; letter-spacing:2px; font-weight:700; text-transform:uppercase; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">Apply Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Sort Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom: 1px solid #eee; padding-bottom: 16px;">
                <div>
                    <span style="font-family:'Montserrat',sans-serif; font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#888;">
                        Showing <?php echo $offset + 1; ?>–<?php echo min($offset + PRODUCTS_PER_PAGE, $total_products); ?> of <?php echo $total_products; ?> items
                    </span>
                </div>
                <div>
                    <select class="form-select" onchange="window.location.href=this.value" style="font-family:'Montserrat',sans-serif; font-size:11px; letter-spacing:1px; text-transform:uppercase; border:1px solid #ddd; border-radius:0; padding:8px 32px 8px 12px; background-color:#fff; color:#333;">
                        <option value="?sort=popularity<?php echo $category_slug ? '&category=' . $category_slug : ''; ?><?php echo $collection_slug ? '&collection=' . $collection_slug : ''; ?>" <?php echo $sort === 'popularity' ? 'selected' : ''; ?>>Popularity</option>
                        <option value="?sort=newest<?php echo $category_slug ? '&category=' . $category_slug : ''; ?><?php echo $collection_slug ? '&collection=' . $collection_slug : ''; ?>" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                        <option value="?sort=price-asc<?php echo $category_slug ? '&category=' . $category_slug : ''; ?><?php echo $collection_slug ? '&collection=' . $collection_slug : ''; ?>" <?php echo $sort === 'price-asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="?sort=price-desc<?php echo $category_slug ? '&category=' . $category_slug : ''; ?><?php echo $collection_slug ? '&collection=' . $collection_slug : ''; ?>" <?php echo $sort === 'price-desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="?sort=name-asc<?php echo $category_slug ? '&category=' . $category_slug : ''; ?><?php echo $collection_slug ? '&collection=' . $collection_slug : ''; ?>" <?php echo $sort === 'name-asc' ? 'selected' : ''; ?>>Name: A-Z</option>
                    </select>
                </div>
            </div>

            <!-- Products -->
            <?php if (empty($products)): ?>
                <div class="empty-state-premium py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="material-icons">shopping_bag</i>
                    </div>
                    <h3 class="empty-state-title mb-3">No Products Found</h3>
                    <p class="empty-state-text text-muted mb-4">We couldn't find any products matching your selection.<br>Try adjusting your filters or search terms.</p>
                    <a href="<?php echo SITE_URL; ?>/shop.php" class="btn btn-premium px-5">View All Products</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col-6 col-md-4">
                            <div class="product-card">
                                <div class="product-image-wrapper">
                                    <a href="<?php echo SITE_URL; ?>/product-detail.php?id=<?php echo $product['product_id']; ?>">
                                        <img src="<?php echo UPLOADS_URL . '/products/' . ($product['primary_image'] ?? 'default.jpg'); ?>"
                                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                            class="product-image"
                                            loading="lazy">
                                    </a>

                                    <?php if (is_logged_in()): ?>
                                        <button class="wishlist-btn" data-product-id="<?php echo $product['product_id']; ?>">
                                            <i class="material-icons">favorite_border</i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <a href="<?php echo SITE_URL; ?>/product-detail.php?id=<?php echo $product['product_id']; ?>" class="text-decoration-none">
                                        <h5 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                    </a>
                                    <?php if (!empty($product['review_count']) && $product['review_count'] > 0): ?>
                                        <div class="product-card-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= floor($product['avg_rating'])): ?>
                                                    <i class="material-icons">star</i>
                                                <?php elseif ($i - $product['avg_rating'] < 1 && $i - $product['avg_rating'] > 0): ?>
                                                    <i class="material-icons">star_half</i>
                                                <?php else: ?>
                                                    <i class="material-icons">star_border</i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <span class="product-card-rating-count">(<?php echo $product['review_count']; ?>)</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="product-price">
                                        <?php if ($product['sale_price']): ?>
                                            <span class="price-current"><?php echo format_price($product['sale_price']); ?></span>
                                            <span class="product-price-original"><?php echo format_price($product['regular_price']); ?></span>
                                        <?php else: ?>
                                            <span class="price-current"><?php echo format_price($product['regular_price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $category_slug ? '&category=' . $category_slug : ''; ?><?php echo $collection_slug ? '&collection=' . $collection_slug : ''; ?><?php echo $sort ? '&sort=' . $sort : ''; ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $category_slug ? '&category=' . $category_slug : ''; ?><?php echo $collection_slug ? '&collection=' . $collection_slug : ''; ?><?php echo $sort ? '&sort=' . $sort : ''; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $category_slug ? '&category=' . $category_slug : ''; ?><?php echo $collection_slug ? '&collection=' . $collection_slug : ''; ?><?php echo $sort ? '&sort=' . $sort : ''; ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function applyPriceFilter() {
        const minPrice = document.getElementById('minPrice').value;
        const maxPrice = document.getElementById('maxPrice').value;
        let url = '<?php echo SITE_URL; ?>/shop.php?';

        <?php if ($category_slug): ?>
            url += 'category=<?php echo $category_slug; ?>&';
        <?php endif; ?>

        <?php if ($collection_slug): ?>
            url += 'collection=<?php echo $collection_slug; ?>&';
        <?php endif; ?>

        if (minPrice) url += 'min_price=' + minPrice + '&';
        if (maxPrice) url += 'max_price=' + maxPrice + '&';

        window.location.href = url;
    }
</script>

<?php include 'includes/footer.php'; ?>