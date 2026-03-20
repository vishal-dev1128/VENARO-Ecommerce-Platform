<?php
require_once 'config.php';

$page_title = 'Home';
$meta_description = 'VÉNARO — Premium Quality Menswear. Discover our exclusive collection of luxury clothing including t-shirts, hoodies, sweatpants, and varsity jackets.';

// Fetch only top-level (parent) categories for homepage display
$stmt = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY COALESCE(display_order, 9999) ASC, category_name ASC");
$categories = $stmt->fetchAll();

// Fetch random products from all categories for New Arrivals
$stmt = $pdo->query("
    SELECT p.*, 
           (SELECT image_url FROM product_images WHERE product_id = p.product_id LIMIT 1) as primary_image
    FROM products p
    WHERE p.status = 'active'
    ORDER BY RAND()
    LIMIT 8
");
$trending_products = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden d-flex align-items-center justify-content-center" style="height: 95vh; min-height: 600px; background-color: #ffffff; width: 100%;">
    <!-- Blurred Background Text -->
    <div class="position-absolute w-100 text-center" style="top: 35%; left: 50%; transform: translate(-50%, -50%); z-index: 1; pointer-events: none; user-select: none;">
        <span style="font-family: var(--font-brand); font-size: 25vw; color: #000; opacity: 0.06; filter: blur(4px); display: block; line-height: 1;">VÉNARO</span>
    </div>

    <!-- Foreground Content -->
    <div class="hero-content text-center position-relative" style="z-index: 2;">
        <h1 class="hero-title mb-3" style="font-family: var(--font-brand); font-size: clamp(3rem, 8vw, 6rem); font-weight: 400; color: #000; letter-spacing: 4px; opacity: 0; animation: heroFadeDown 1s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards;">
            VÉNARO
        </h1>

        <p class="text-uppercase mb-5" style="font-family: 'Montserrat', sans-serif; font-size: 0.7rem; letter-spacing: 4px; color: #888; font-weight: 600; opacity: 0; animation: heroFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;">
            PREMIUM MENSWEAR
        </p>

        <div style="opacity: 0; animation: heroFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.8s forwards;">
            <a href="<?php echo SITE_URL; ?>/shop.php" style="display:inline-block; font-family: 'Montserrat', sans-serif; font-size: 0.7rem; letter-spacing: 3px; font-weight: 700; text-transform:uppercase; color:#000; border-bottom: 1px solid #000; padding-bottom: 4px; text-decoration: none; transition: opacity 0.2s;">
                SHOP NOW &nbsp;→
            </a>
        </div>
    </div>
</section>

<style>
@keyframes heroFadeDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes heroFadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<!-- Shop by Category Section -->
<?php if (!empty($categories)): ?>
    <section style="padding: 80px 0; background: #f8f8f8;">
        <div class="container-fluid px-md-5 px-3">
            <div class="text-center mb-5">
                <span style="font-family:'Montserrat',sans-serif; font-size:10px; font-weight:700; letter-spacing:4px; color:#888; text-transform:uppercase; display:block; margin-bottom:16px;">Collections</span>
                <h2 class="section-title">Shop by Category</h2>
            </div>

            <div class="row g-2 g-md-3">
                <?php foreach ($categories as $index => $category): ?>
                    <div class="col-6 col-md-3">
                        <a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo $category['slug']; ?>" class="v-cat-card">
                            <?php if (!empty($category['image'])): ?>
                                <img src="<?php echo UPLOADS_URL . '/categories/' . $category['image']; ?>"
                                    alt="<?php echo htmlspecialchars($category['category_name']); ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <div style="width:100%;height:100%;background:#1a1a1a;display:flex;align-items:center;justify-content:center;position:absolute;inset:0;">
                                    <i class="material-icons" style="font-size:40px;color:#333;">category</i>
                                </div>
                            <?php endif; ?>
                            <div class="v-cat-card__overlay">
                                <h3 class="v-cat-card__name"><?php echo htmlspecialchars($category['category_name']); ?></h3>
                                <span class="v-cat-card__cta">Shop Now</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Featured Products -->
<?php if (!empty($trending_products)): ?>
    <section style="padding: 80px 0; background: #f8f8f8;">
        <div class="container-fluid px-md-5 px-3">
            <div class="text-center mb-5">
                <span style="font-family:'Montserrat',sans-serif; font-size:10px; font-weight:700; letter-spacing:4px; color:#888; text-transform:uppercase; display:block; margin-bottom:16px;">New In</span>
                <h2 class="section-title">New Arrivals</h2>
            </div>

            <div class="row g-3 g-md-4">
                <?php foreach ($trending_products as $product): ?>
                    <div class="col-6 col-md-3">
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <a href="<?php echo SITE_URL; ?>/product-detail.php?id=<?php echo $product['product_id']; ?>">
                                    <img src="<?php echo UPLOADS_URL . '/products/' . ($product['primary_image'] ?? 'default.jpg'); ?>"
                                        alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                        class="product-image"
                                        loading="lazy">
                                </a>
                                <?php if (is_logged_in()): ?>
                                    <button class="wishlist-btn" data-product-id="<?php echo $product['product_id']; ?>" title="Add to wishlist">
                                        <i class="material-icons">favorite_border</i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <a href="<?php echo SITE_URL; ?>/product-detail.php?id=<?php echo $product['product_id']; ?>" class="text-decoration-none">
                                    <h5 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                                </a>
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

            <div class="text-center mt-5">
                <a href="<?php echo SITE_URL; ?>/shop.php" style="display:inline-block; font-family: 'Montserrat', sans-serif; font-size: 0.7rem; letter-spacing: 3px; font-weight: 700; text-transform:uppercase; color:#000; border: 1px solid #000; padding: 14px 48px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.background='#000';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='#000'">
                    VIEW ALL PRODUCTS
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Brand Values -->
<section style="padding: 80px 0; background: #111;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <i class="material-icons mb-3" style="font-size: 36px; color: #fff;">verified</i>
                <h5 style="font-family: var(--font-brand); font-size: 18px; color: #fff; margin-bottom: 12px;">Premium Quality</h5>
                <p style="color: rgba(255,255,255,0.5); font-size: 13px; line-height: 1.8; max-width: 220px; margin: 0 auto;">100% Supima cotton and sustainable materials for unmatched comfort.</p>
            </div>
            <div class="col-md-4 text-center">
                <i class="material-icons mb-3" style="font-size: 36px; color: #fff;">straighten</i>
                <h5 style="font-family: var(--font-brand); font-size: 18px; color: #fff; margin-bottom: 12px;">Perfect Fit</h5>
                <p style="color: rgba(255,255,255,0.5); font-size: 13px; line-height: 1.8; max-width: 220px; margin: 0 auto;">Multiple fit options with size guides to find your perfect silhouette.</p>
            </div>
            <div class="col-md-4 text-center">
                <i class="material-icons mb-3" style="font-size: 36px; color: #fff;">eco</i>
                <h5 style="font-family: var(--font-brand); font-size: 18px; color: #fff; margin-bottom: 12px;">Sustainably Made</h5>
                <p style="color: rgba(255,255,255,0.5); font-size: 13px; line-height: 1.8; max-width: 220px; margin: 0 auto;">Responsibly made with eco-friendly practices and ethical manufacturing.</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section style="padding: 100px 0 60px; background: #fff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="section-title mb-3">Stay in the Loop</h2>
                <p style="color: #888; font-size: 14px; margin-bottom: 32px;">Subscribe for exclusive offers, new arrivals and style guides.</p>
                <form action="<?php echo SITE_URL; ?>/api/newsletter-subscribe.php" method="POST" id="newsletterForm">
                    <div class="d-flex gap-0" style="border: 1px solid #ddd;">
                        <input type="email" name="email" class="form-control rounded-0 border-0" placeholder="Enter your email address" required
                            style="font-size: 13px; padding: 14px 18px;">
                        <button type="submit" class="btn btn-dark rounded-0 px-4"
                            style="font-family: 'Montserrat', sans-serif; font-size: 10px; letter-spacing: 2px; font-weight: 700; white-space: nowrap;">
                            SUBSCRIBE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Grand Brand Showcase -->
<section style="padding: 80px 0 120px; background: #fff; overflow: hidden; border-top: 1px solid #f1f1f1;">
    <div class="container-fluid px-md-5 px-4">
        <!-- Top Row -->
        <div class="row align-items-center mb-5 pb-4">
            <div class="col-6">
                <span style="font-family: 'Montserrat', sans-serif; font-size: clamp(10px, 1.5vw, 13px); font-weight: 700; letter-spacing: 4px; color: #888; text-transform: uppercase;">
                    Experience Elegance
                </span>
            </div>
            <div class="col-6 text-end">
                <div class="d-flex justify-content-end gap-md-5 gap-3">
                    <a href="<?php echo SITE_URL; ?>/shop.php" style="text-decoration: none; color: #111; font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Collections</a>
                    <a href="<?php echo SITE_URL; ?>/about.php" style="text-decoration: none; color: #111; font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Craftsmanship</a>
                    <a href="<?php echo SITE_URL; ?>/contact.php" style="text-decoration: none; color: #111; font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Support</a>
                </div>
            </div>
        </div>
        
        <!-- Central Typography -->
        <div class="text-center py-4 my-lg-5">
            <h1 class="grand-title" style="font-family: 'Montserrat', sans-serif; font-size: 21vw; font-weight: 900; color: #000; line-height: 0.8; letter-spacing: -0.06em; margin: 0; user-select: none; transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
                VÉNARO
            </h1>
        </div>
        
        <!-- Bottom Row -->
        <div class="row align-items-center mt-5 pt-5">
            <div class="col-md-4 mb-4 mb-md-0">
                <span class="brand-logo" style="font-size: 20px; letter-spacing: 10px;">VÉNARO</span>
            </div>
            <div class="col-md-8 text-md-end">
                <div class="d-flex justify-content-md-end flex-wrap gap-md-4 gap-3">
                    <a href="https://instagram.com/venaro_apparel/" target="_blank" style="text-decoration: none; color: #888; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Instagram</a>
                    <a href="#" style="text-decoration: none; color: #888; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Privacy</a>
                    <a href="#" style="text-decoration: none; color: #888; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Terms</a>
                    <a href="#" style="text-decoration: none; color: #888; font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; border: 1px solid #eee; padding: 4px 12px;">Global / EN</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .grand-title:hover {
        transform: scale(1.02);
    }
    @media (max-width: 768px) {
        .grand-title {
            font-size: 24vw !important;
            letter-spacing: -0.04em !important;
        }
    }
</style>

<!-- Category Card CSS — Premium editorial style -->
<style>
    .v-cat-card {
        position: relative;
        display: block;
        overflow: hidden;
        text-decoration: none;
        aspect-ratio: 3/4;
        background: #111;
    }

    .v-cat-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .v-cat-card:hover img {
        transform: scale(1.09);
    }

    .v-cat-card__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.6) 0%, transparent 40%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 24px 18px;
        transition: background 0.5s ease;
    }

    .v-cat-card:hover .v-cat-card__overlay {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 50%);
    }

    .v-cat-card__name {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 600;
        color: #fff;
        margin: 0 0 12px;
        letter-spacing: 0.03em;
        transform: translateY(6px);
        transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .v-cat-card:hover .v-cat-card__name {
        transform: translateY(0);
    }

    .v-cat-card__cta {
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2.5px;
        color: #fff;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transform: translateY(8px);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.05s;
    }

    .v-cat-card__cta::after {
        content: '';
        display: block;
        width: 28px;
        height: 1px;
        background: #fff;
        transition: width 0.35s ease;
    }

    .v-cat-card:hover .v-cat-card__cta {
        opacity: 1;
        transform: translateY(0);
    }

    .v-cat-card:hover .v-cat-card__cta::after {
        width: 46px;
    }
</style>

<?php include 'includes/footer.php'; ?>