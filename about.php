<?php
require_once 'config.php';

$page_title = 'About Us';
$meta_description = 'VÉNARO — Premium Quality Menswear. Our mission is to redefine luxury with sustainable materials and ethical manufacturing.';
include 'includes/header.php';
?>

<style>
/* ── About Page — Editorial Luxury ── */
.about-hero {
    padding: 120px 0 80px;
    background: #fff;
    border-bottom: 1px solid #eee;
}

.about-eyebrow {
    font-family: 'Montserrat', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #888;
    display: block;
    margin-bottom: 28px;
}

.about-hero-heading {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(32px, 5vw, 64px);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.01em;
    text-transform: uppercase;
    color: #111;
    margin-bottom: 32px;
}

.about-hero-text {
    font-family: 'Inter', sans-serif;
    font-size: 16px;
    line-height: 1.9;
    color: #555;
    max-width: 420px;
}

.about-hero-img {
    width: 100%;
    height: 520px;
    object-fit: cover;
    display: block;
}

/* Values Section */
.about-values-section {
    padding: 100px 0;
    background: #f8f8f8;
}

.about-value-item {
    padding: 48px 40px;
    border-left: 1px solid #e5e5e5;
    background: #fff;
    transition: all 0.3s ease;
}

.about-value-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.06);
}

.about-value-number {
    font-family: 'Montserrat', sans-serif;
    font-size: 48px;
    font-weight: 400;
    color: #eee;
    line-height: 1;
    margin-bottom: 20px;
    letter-spacing: -0.02em;
}

.about-value-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px;
    font-weight: 400;
    color: #111;
    margin-bottom: 16px;
    letter-spacing: -0.01em;
}

.about-value-text {
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #777;
    line-height: 1.8;
}

/* Process Section */
.about-process-section {
    padding: 100px 0;
    background: #fff;
}

.about-process-img {
    width: 100%;
    height: 480px;
    object-fit: cover;
    display: block;
}

.about-process-content {
    padding: 0 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
}

.about-process-heading {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(28px, 4vw, 44px);
    font-weight: 400;
    color: #111;
    line-height: 1.15;
    margin-bottom: 28px;
    letter-spacing: -0.02em;
}

.about-process-text {
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #666;
    line-height: 1.9;
    margin-bottom: 20px;
}

/* Strip Banner */
.about-strip {
    padding: 80px 0;
    background: #111;
    text-align: center;
}

.about-strip-text {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(24px, 4vw, 48px);
    font-weight: 400;
    color: #fff;
    letter-spacing: -0.02em;
    line-height: 1.2;
    font-style: italic;
}

@media (max-width: 991px) {
    .about-hero { padding: 80px 0 60px; }
    .about-hero-img { height: 320px; margin-top: 40px; }
    .about-process-content { padding: 40px 20px 0; }
    .about-process-img { height: 320px; }
    .about-value-item { border-left: none; border-top: 1px solid #e5e5e5; }
}
</style>

<!-- Hero -->
<section class="about-hero">
    <div class="container-fluid px-md-5 px-4">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="about-eyebrow">Our Story</span>
                <h1 class="about-hero-heading">Redefining<br>Modern <em style="font-style:italic;">Luxury</em></h1>
                <p class="about-hero-text">VÉNARO is more than a clothing brand — it's a statement of elegance, quality, and timeless style. Founded in 2026, we bridge the gap between high-end fashion and everyday comfort.</p>
            </div>
            <div class="col-lg-6">
                <img src="<?php echo ASSETS_URL; ?>/images/about-production.jpg" alt="About VÉNARO" class="about-hero-img">
            </div>
        </div>
    </div>
</section>

<!-- Brand Strip -->
<section class="about-strip">
    <div class="container">
        <p class="about-strip-text">"True luxury lies in the details —<br>the fabric, the fit, and the finish."</p>
    </div>
</section>

<!-- Values -->
<section class="about-values-section">
    <div class="container-fluid px-md-5 px-4">
        <div class="text-center mb-5">
            <span class="about-eyebrow">Our Principles</span>
            <h2 style="font-family:'Montserrat',sans-serif; text-transform: uppercase; font-weight:800; font-size:clamp(24px,3.5vw,36px); letter-spacing: -0.01em; color:#111;">What We Stand For</h2>
        </div>
        <div class="row g-0">
            <div class="col-md-4">
                <div class="about-value-item h-100">
                    <div class="about-value-number">01</div>
                    <h3 class="about-value-title">Premium Quality</h3>
                    <p class="about-value-text">We source only the finest materials — 100% Supima Cotton and sustainable textiles — ensuring our garments stand the test of time and wear.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-value-item h-100">
                    <div class="about-value-number">02</div>
                    <h3 class="about-value-title">Minimalist Design</h3>
                    <p class="about-value-text">Our designs are clean, sophisticated, and versatile. Each piece is crafted to complement the modern gentleman's wardrobe without excess.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-value-item h-100">
                    <div class="about-value-number">03</div>
                    <h3 class="about-value-title">Sustainable Future</h3>
                    <p class="about-value-text">We are committed to ethical manufacturing and responsible sourcing. Fashion should be beautiful — and it should be responsible.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process -->
<section class="about-process-section">
    <div class="container-fluid px-md-5 px-4">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <div class="about-process-content">
                    <span class="about-eyebrow">Craftsmanship</span>
                    <h2 class="about-process-heading">The Art of<br>Production</h2>
                    <h5 style="font-family: var(--font-brand); font-size: 18px; color: #000; font-weight: 700; margin-bottom: 12px;">The VÉNARO Process</h5>
                    <p style="color: #666; font-size: 13px; line-height: 1.8;">Our garments are created through a meticulous process of design and refinement. From the initial sketch to the final stitch, we ensure each VÉNARO piece meets our high standards.</p>
                    <p class="about-process-text">Every piece of VÉNARO clothing passes through a rigorous quality control process. From the initial sketch to the final stitch, our team of expert artisans ensures perfection at every stage.</p>
                    <p class="about-process-text">We work closely with manufacturing partners to maintain high standards of craftsmanship while ensuring fair labor practices. When you wear VÉNARO, you wear a product of passion and integrity.</p>
                    <a href="<?php echo SITE_URL; ?>/shop.php" style="display:inline-block; margin-top:16px; font-family:'Montserrat',sans-serif; font-size:11px; font-weight:700; letter-spacing:3px; text-transform:uppercase; color:#111; border-bottom: 1px solid #111; padding-bottom:4px; text-decoration:none; transition:all 0.2s;">
                        Explore Collection
                    </a>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <img src="<?php echo ASSETS_URL; ?>/images/about-hero.jpg" alt="Production Process" class="about-process-img">
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>