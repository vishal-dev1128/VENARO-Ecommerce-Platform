<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: shop.php');
    exit();
}

$product_id = $_GET['id'];

// Fetch Product Details
$stmt = $pdo->prepare("
    SELECT p.*, c.category_name, c.slug as category_slug,
           (SELECT COUNT(*) FROM product_collections pc2 JOIN collections c2 ON pc2.collection_id = c2.collection_id WHERE pc2.product_id = p.product_id AND c2.slug = 'new-arrival') as is_new_arrival
    FROM products p
    LEFT JOIN product_categories pc ON p.product_id = pc.product_id
    LEFT JOIN categories c ON pc.category_id = c.category_id
    WHERE p.product_id = ? AND p.status = 'Active'
");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: shop.php');
    exit();
}

// Increment Views
$pdo->prepare("UPDATE products SET views = views + 1 WHERE product_id = ?")->execute([$product_id]);

// Fetch Product Images
$stmt = $pdo->prepare("SELECT image_url, is_primary FROM product_images WHERE product_id = ? ORDER BY is_primary DESC");
$stmt->execute([$product_id]);
$images = $stmt->fetchAll();

// Fetch Related Products
$stmt = $pdo->prepare("
    SELECT p.*, 
           (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = TRUE LIMIT 1) as primary_image,
           (SELECT COUNT(*) FROM product_collections pc2 JOIN collections c2 ON pc2.collection_id = c2.collection_id WHERE pc2.product_id = p.product_id AND c2.slug = 'new-arrival') as is_new_arrival
    FROM products p
    JOIN product_categories pc ON p.product_id = pc.product_id
    LEFT JOIN categories c ON pc.category_id = c.category_id
    WHERE c.category_name = ? AND p.product_id != ? AND p.status = 'Active'
    LIMIT 4
");
$stmt->execute([$product['category_name'], $product_id]);
$related_products = $stmt->fetchAll();

// Fetch Product Variants (Sizes)
$stmt = $pdo->prepare("SELECT DISTINCT size FROM product_variants WHERE product_id = ? AND size != '' ORDER BY size");
$stmt->execute([$product_id]);
$product_variants = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Default sizes if no variants found (only if no variants exist at all for this product)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM product_variants WHERE product_id = ?");
$stmt->execute([$product_id]);
$has_any_variants = $stmt->fetchColumn() > 0;

if (!$has_any_variants && empty($product_variants)) {
    $product_variants = ['XS', 'S', 'M', 'L', 'XL', '2XL'];
}

// Fetch Color Variants with images (Unique by color name)
$stmt = $pdo->prepare("
    SELECT color, MAX(color_hex) as color_hex, MAX(image) as variant_image 
    FROM product_variants 
    WHERE product_id = ? AND color != '' 
    GROUP BY color
");
$stmt->execute([$product_id]);
$product_colors = $stmt->fetchAll();

// Fetch Approved Reviews
$stmt = $pdo->prepare("
    SELECT r.*, u.full_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.user_id 
    WHERE r.product_id = ? AND r.status = 'Approved' 
    ORDER BY r.created_at DESC
");
$stmt->execute([$product_id]);
$reviews = $stmt->fetchAll();

// Calculate rating stats
$total_reviews = count($reviews);
$avg_rating = 0;
$rating_distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
if ($total_reviews > 0) {
    $sum = 0;
    foreach ($reviews as $rev) {
        $sum += $rev['rating'];
        $rating_distribution[$rev['rating']]++;
    }
    $avg_rating = round($sum / $total_reviews, 1);
}

// Check if current user already reviewed
$user_has_reviewed = false;
if (is_logged_in()) {
    $stmt = $pdo->prepare("SELECT review_id FROM reviews WHERE product_id = ? AND user_id = ?");
    $stmt->execute([$product_id, get_current_user_id()]);
    $user_has_reviewed = (bool)$stmt->fetch();
}

$page_title = $product['product_name'];
include 'includes/header.php';
?>

<div class="product-detail-page py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/shop.php">Shop</a></li>
                <?php if ($product['category_name']): ?>
                    <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo $product['category_slug']; ?>"><?php echo htmlspecialchars($product['category_name']); ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['product_name']); ?></li>
            </ol>
        </nav>

        <div class="product-gallery-container">
            <!-- Vertical Thumbnails -->
            <div class="thumbnail-vertical d-none d-md-flex">
                <?php foreach ($images as $index => $img): ?>
                    <img src="<?php echo UPLOADS_URL . '/products/' . $img['image_url']; ?>"
                        alt="Thumbnail"
                        class="<?php echo $index === 0 ? 'active' : ''; ?>"
                        onclick="changeMainImage(this, '<?php echo UPLOADS_URL . '/products/' . $img['image_url']; ?>')">
                <?php endforeach; ?>
            </div>

            <!-- Main Image Area -->
            <div class="main-image-wrapper">
                <button class="share-btn-floating" title="Share Product" onclick="shareProduct()">
                    <i class="material-icons">reply</i>
                </button>
                <?php if (!empty($images)): ?>
                    <img src="<?php echo UPLOADS_URL . '/products/' . $images[0]['image_url']; ?>"
                        alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                        id="mainImage">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center h-100 w-100 text-muted" style="min-height: 500px;">
                        No Image Available
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mobile Thumbnails -->
            <div class="thumbnail-vertical d-flex d-md-none mt-2">
                <?php foreach ($images as $index => $img): ?>
                    <img src="<?php echo UPLOADS_URL . '/products/' . $img['image_url']; ?>"
                        alt="Thumbnail"
                        class="<?php echo $index === 0 ? 'active' : ''; ?>"
                        onclick="changeMainImage(this, '<?php echo UPLOADS_URL . '/products/' . $img['image_url']; ?>')">
                <?php endforeach; ?>
            </div>

            <!-- Sticky Product Info Area -->
            <div class="sticky-info-wrapper">
                <div class="product-info-premium">
                    <h1 class="product-title-premium"><?php echo htmlspecialchars($product['product_name']); ?></h1>

                    <div class="product-price-premium">
                        <?php if ($product['sale_price']): ?>
                            <span class="price-current"><?php echo format_price($product['sale_price']); ?></span>
                            <span class="price-original"><?php echo format_price($product['regular_price']); ?></span>
                            <span class="price-discount"><?php echo calculate_discount_percentage($product['regular_price'], $product['sale_price']); ?>% OFF</span>
                        <?php else: ?>
                            <span class="price-current"><?php echo format_price($product['regular_price']); ?></span>
                        <?php endif; ?>
                    </div>

                    <form action="cart.php" method="POST" id="addToCartForm">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <div class="mb-4">
                            <label class="form-label" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #888;">Quantity</label>
                            <div class="d-flex align-items-center" style="border: 1px solid #e0e0e0; width: fit-content; border-radius: 4px;">
                                <button type="button" class="btn btn-link text-dark px-3" onclick="updateQuantity(-1)" style="text-decoration: none;">-</button>
                                <input type="text" name="quantity" id="quantityInput" value="1" class="form-control border-0 text-center p-0" style="width: 40px; height: 40px; background: transparent;" readonly>
                                <button type="button" class="btn btn-link text-dark px-3" onclick="updateQuantity(1)" style="text-decoration: none;">+</button>
                            </div>
                        </div>
                        <input type="hidden" name="size" id="selectedSize" required>

                        <?php if (!empty($product_variants)): ?>
                            <div class="size-selector-label">Select size</div>
                            <div class="size-boxes">
                                <?php foreach ($product_variants as $size): ?>
                                    <?php if (trim($size) === '') continue; ?>
                                    <div class="size-box" onclick="selectSize(this, '<?php echo htmlspecialchars($size); ?>')">
                                        <?php echo htmlspecialchars($size); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <input type="hidden" name="color" id="selectedColor" required>
                        <?php if (!empty($product_colors)): ?>
                            <div class="color-selector-label">Select Color</div>
                            <div class="color-swatches">
                                <?php foreach ($product_colors as $color): ?>
                                    <?php $c_image = !empty($color['variant_image']) ? UPLOADS_URL . '/products/' . $color['variant_image'] : ''; ?>
                                    <div class="color-swatch-wrapper" onclick="selectColor(this, '<?php echo htmlspecialchars($color['color']); ?>', '<?php echo $c_image; ?>')" title="<?php echo htmlspecialchars($color['color']); ?>">
                                        <div class="color-swatch" style="background-color: <?php echo $color['color_hex'] ?: '#000'; ?>;"></div>
                                        <span class="color-name-tooltip"><?php echo htmlspecialchars($color['color']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div id="selection-error" class="text-danger mb-3" style="display: none; font-size: 14px; font-weight: 500;"></div>
                        <div class="product-actions-premium">
                            <?php if (is_logged_in()): ?>
                                <button type="submit" class="btn-add-cart-outline" onclick="return validateSelection()">Add to cart</button>
                                <button type="button" class="btn-buy-now-solid" onclick="buyNow()">Buy now</button>
                            <?php else: ?>
                                <button type="button" class="btn-add-cart-outline" onclick="window.location.href='login.php?redirect=' + encodeURIComponent('product-detail.php?id=<?php echo $product['product_id']; ?>')">Add to cart</button>
                                <button type="button" class="btn-buy-now-solid" onclick="window.location.href='login.php?redirect=' + encodeURIComponent('product-detail.php?id=<?php echo $product['product_id']; ?>')">Buy now</button>
                            <?php endif; ?>
                        </div>
                    </form>

                    <div class="trust-badges-premium">
                        <div class="trust-badge-item">
                            <i class="material-icons">verified_user</i>
                            <span>Secure<br>Checkout</span>
                        </div>
                        <div class="trust-badge-item">
                            <i class="material-icons">workspace_premium</i>
                            <span>Satisfaction<br>Guaranteed</span>
                        </div>
                        <div class="trust-badge-item">
                            <i class="material-icons">lock</i>
                            <span>Privacy<br>Protected</span>
                        </div>
                    </div>

                    <div class="product-details-section">
                        <div class="product-details-title-premium">Product details</div>
                        <p class="product-details-content-premium">
                            <?php echo nl2br(htmlspecialchars($product['short_description'])); ?>
                        </p>
                        <?php if (!empty($product['long_description'])): ?>
                            <div class="mt-3">
                                <div class="product-details-content-premium">
                                    <?php echo nl2br(htmlspecialchars($product['long_description'])); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function changeMainImage(thumb, src) {
                const mainImg = document.getElementById('mainImage');

                // Apply animation
                mainImg.classList.remove('gallery-fade-in');
                void mainImg.offsetWidth; // Trigger reflow to restart animation

                mainImg.src = src;
                mainImg.classList.add('gallery-fade-in');

                // Update active class on thumbnails
                document.querySelectorAll('.thumbnail-vertical img').forEach(img => {
                    img.classList.remove('active');
                    if (img.src === thumb.src) img.classList.add('active');
                });
            }

            function selectSize(element, size) {
                // Update selection
                document.querySelectorAll('.size-box').forEach(box => box.classList.remove('active'));
                element.classList.add('active');
                document.getElementById('selectedSize').value = size;

                // Hide error message if it was shown
                const errorDiv = document.getElementById('selection-error');
                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }
            }

            function selectColor(element, color, imageUrl) {
                // Update selection
                document.querySelectorAll('.color-swatch-wrapper').forEach(wrapper => wrapper.classList.remove('active'));
                element.classList.add('active');
                document.getElementById('selectedColor').value = color;

                // Hide error message if it was shown
                const errorDiv = document.getElementById('selection-error');
                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }

                // Change main image if a variant image exists
                if (imageUrl) {
                    const mainImg = document.getElementById('mainImage');
                    mainImg.classList.remove('gallery-fade-in');
                    void mainImg.offsetWidth;
                    mainImg.src = imageUrl;
                    mainImg.classList.add('gallery-fade-in');

                    // Also update thumbnails selection
                    document.querySelectorAll('.thumbnail-vertical img').forEach(img => {
                        img.classList.remove('active');
                        if (img.src === imageUrl) img.classList.add('active');
                    });
                }
            }

            function validateSelection() {
                const size = document.getElementById('selectedSize').value;
                const colorInput = document.getElementById('selectedColor');
                const hasColors = document.querySelectorAll('.color-swatch-wrapper').length > 0;
                const errorDiv = document.getElementById('selection-error');

                errorDiv.style.display = 'none';
                errorDiv.textContent = '';

                if (!size) {
                    errorDiv.textContent = 'Please select a size first.';
                    errorDiv.style.display = 'block';

                    // Add a subtle shake to the size label to draw attention
                    const label = document.querySelector('.size-selector-label');
                    label.style.color = '#d9534f';
                    setTimeout(() => {
                        label.style.color = '#555';
                    }, 2000);

                    return false;
                }

                if (hasColors && !colorInput.value) {
                    errorDiv.textContent = 'Please select a color first.';
                    errorDiv.style.display = 'block';

                    const label = document.querySelector('.color-selector-label');
                    label.style.color = '#d9534f';
                    setTimeout(() => {
                        label.style.color = '#666';
                    }, 2000);

                    return false;
                }

                return true;
            }

            function shareProduct() {
                if (navigator.share) {
                    navigator.share({
                        title: '<?php echo addslashes($product['product_name']); ?>',
                        url: window.location.href
                    });
                } else {
                    // Fallback: Copy to clipboard
                    navigator.clipboard.writeText(window.location.href);
                    alert('Link copied to clipboard!');
                }
            }

            function buyNow() {
                if (validateSelection()) {
                    const form = document.getElementById('addToCartForm');
                    // Change action or add a flag to redirect to checkout after adding
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'redirect_to_checkout';
                    input.value = '1';
                    form.appendChild(input);
                    form.submit();
                }
            }

            function updateQuantity(change) {
                const input = document.getElementById('quantityInput');
                let val = parseInt(input.value);
                val += change;
                if (val < 1) val = 1;
                if (val > 10) val = 10; // Max limit
                input.value = val;
            }
        </script>

        <!-- Reviews & Ratings Section -->
        <div class="reviews-section" id="reviews">
            <h3 class="reviews-section-title">Reviews & Ratings</h3>

            <div class="reviews-layout">
                <!-- Rating Summary -->
                <div class="rating-summary-card">
                    <div class="rating-big-number"><?php echo $total_reviews > 0 ? $avg_rating : '—'; ?></div>
                    <div class="rating-big-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= floor($avg_rating)): ?>
                                <i class="material-icons">star</i>
                            <?php elseif ($i - $avg_rating < 1 && $i - $avg_rating > 0): ?>
                                <i class="material-icons">star_half</i>
                            <?php else: ?>
                                <i class="material-icons">star_border</i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="rating-total-count"><?php echo $total_reviews; ?> review<?php echo $total_reviews !== 1 ? 's' : ''; ?></div>
                    <div class="rating-breakdown">
                        <?php for ($s = 5; $s >= 1; $s--): ?>
                            <?php $pct = $total_reviews > 0 ? round(($rating_distribution[$s] / $total_reviews) * 100) : 0; ?>
                            <div class="rating-bar-row">
                                <span class="rating-bar-label"><?php echo $s; ?> <i class="material-icons" style="font-size:14px;">star</i></span>
                                <div class="rating-bar-track">
                                    <div class="rating-bar-fill" style="width: <?php echo $pct; ?>%"></div>
                                </div>
                                <span class="rating-bar-count"><?php echo $rating_distribution[$s]; ?></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="reviews-list-wrapper">
                    <?php if (empty($reviews)): ?>
                        <div class="no-reviews-msg">
                            <i class="material-icons">rate_review</i>
                            <p>No reviews yet. Be the first to review this product!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($reviews as $rev): ?>
                            <div class="review-card">
                                <div class="review-card-header">
                                    <div class="review-stars-small">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="material-icons"><?php echo $i <= $rev['rating'] ? 'star' : 'star_border'; ?></i>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if ($rev['verified_purchase']): ?>
                                        <span class="verified-badge"><i class="material-icons">verified</i> Verified Purchase</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($rev['review_title'])): ?>
                                    <div class="review-title"><?php echo htmlspecialchars($rev['review_title']); ?></div>
                                <?php endif; ?>
                                <div class="review-text"><?php echo nl2br(htmlspecialchars($rev['review_text'])); ?></div>
                                <div class="review-meta">
                                    <?php
                                    $name_parts = explode(' ', $rev['full_name']);
                                    $display_name = $name_parts[0];
                                    if (count($name_parts) > 1) $display_name .= ' ' . strtoupper(substr(end($name_parts), 0, 1)) . '.';
                                    ?>
                                    <span class="review-author"><?php echo htmlspecialchars($display_name); ?></span>
                                    <span class="review-date"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Write a Review Form -->
                    <?php if (is_logged_in() && !$user_has_reviewed): ?>
                        <div class="write-review-card" id="writeReviewCard">
                            <h5 class="write-review-title">Write a Review</h5>
                            <form id="reviewForm">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <div class="star-picker" id="starPicker">
                                    <label>Your Rating <span style="color:#e53935;">*</span></label>
                                    <div class="star-picker-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="material-icons star-pick" data-value="<?php echo $i; ?>">star_border</i>
                                        <?php endfor; ?>
                                    </div>
                                    <input type="hidden" name="rating" id="ratingInput" value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size:13px; text-transform:uppercase; letter-spacing:0.5px;">Review Title</label>
                                    <input type="text" name="review_title" class="form-control" placeholder="Sum it up in a few words" maxlength="255">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size:13px; text-transform:uppercase; letter-spacing:0.5px;">Your Review <span style="color:#e53935;">*</span></label>
                                    <textarea name="review_text" class="form-control" rows="4" placeholder="Share your experience with this product..." required></textarea>
                                </div>
                                <div id="reviewFormMsg" style="display:none;" class="mb-3"></div>
                                <button type="submit" class="btn-submit-review">Submit Review</button>
                            </form>
                        </div>
                    <?php elseif (!is_logged_in()): ?>
                        <div class="login-to-review">
                            <a href="<?php echo SITE_URL; ?>/login.php?redirect=product-detail.php?id=<?php echo $product_id; ?>">Log in</a> to write a review.
                        </div>
                    <?php elseif ($user_has_reviewed): ?>
                        <div class="already-reviewed-msg">
                            <i class="material-icons">check_circle</i> You've already reviewed this product.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            // Star Picker
            document.querySelectorAll('.star-pick').forEach(star => {
                star.addEventListener('mouseenter', function() {
                    const val = parseInt(this.dataset.value);
                    document.querySelectorAll('.star-pick').forEach((s, i) => {
                        s.textContent = (i < val) ? 'star' : 'star_border';
                    });
                });
                star.addEventListener('click', function() {
                    const val = parseInt(this.dataset.value);
                    document.getElementById('ratingInput').value = val;
                    this.closest('.star-picker-stars').dataset.selected = val;
                });
            });
            document.querySelector('.star-picker-stars')?.addEventListener('mouseleave', function() {
                const selected = parseInt(this.dataset.selected || 0);
                document.querySelectorAll('.star-pick').forEach((s, i) => {
                    s.textContent = (i < selected) ? 'star' : 'star_border';
                });
            });

            // Review Form Submit
            document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const msgDiv = document.getElementById('reviewFormMsg');
                const btn = this.querySelector('button[type=submit]');

                if (parseInt(formData.get('rating')) < 1) {
                    msgDiv.style.display = 'block';
                    msgDiv.className = 'mb-3 text-danger';
                    msgDiv.textContent = 'Please select a rating.';
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Submitting...';

                fetch('<?php echo SITE_URL; ?>/api/review-submit.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        msgDiv.style.display = 'block';
                        if (data.success) {
                            msgDiv.className = 'mb-3 alert alert-success';
                            msgDiv.textContent = data.message;
                            this.reset();
                            document.querySelectorAll('.star-pick').forEach(s => s.textContent = 'star_border');
                            document.getElementById('ratingInput').value = 0;
                            btn.style.display = 'none';
                        } else {
                            msgDiv.className = 'mb-3 text-danger';
                            msgDiv.textContent = data.message;
                            btn.disabled = false;
                            btn.textContent = 'Submit Review';
                        }
                    })
                    .catch(() => {
                        msgDiv.style.display = 'block';
                        msgDiv.className = 'mb-3 text-danger';
                        msgDiv.textContent = 'Something went wrong. Please try again.';
                        btn.disabled = false;
                        btn.textContent = 'Submit Review';
                    });
            });
        </script>

        <!-- Related Products -->
        <?php if (!empty($related_products)): ?>
            <div class="mt-5">
                <h3 class="font-brand mb-4 text-center">You May Also Like</h3>
                <div class="row g-4">
                    <?php foreach ($related_products as $rel_prod): ?>
                        <div class="col-6 col-md-3">
                            <div class="product-card h-100">
                                <div class="product-image-wrapper">
                                    <a href="product-detail.php?id=<?php echo $rel_prod['product_id']; ?>">
                                        <img src="<?php echo UPLOADS_URL . '/products/' . ($rel_prod['primary_image'] ?? 'default.jpg'); ?>"
                                            alt="<?php echo htmlspecialchars($rel_prod['product_name']); ?>"
                                            class="product-image">
                                    </a>
                                </div>
                                <div class="product-info text-center mt-3">
                                    <a href="product-detail.php?id=<?php echo $rel_prod['product_id']; ?>" class="text-decoration-none">
                                        <h6 class="product-name text-dark"><?php echo htmlspecialchars($rel_prod['product_name']); ?></h6>
                                    </a>
                                    <div class="product-price">
                                        <?php if ($rel_prod['sale_price']): ?>
                                            <span class="text-dark fw-bold"><?php echo format_price($rel_prod['sale_price']); ?></span>
                                        <?php else: ?>
                                            <span class="fw-bold"><?php echo format_price($rel_prod['regular_price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
</div>

<?php include 'includes/footer.php'; ?>