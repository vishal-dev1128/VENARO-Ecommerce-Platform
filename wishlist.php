<?php
require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php?redirect=wishlist.php');
    exit();
}

$user_id = get_current_user_id();

// Handle Actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $product_id = $_GET['product_id'] ?? null;
    if ($action === 'remove' && $product_id) {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $_SESSION['success'] = "Removed from wishlist";
    }
    header('Location: wishlist.php');
    exit();
}

// Fetch user
$stmt_user = $pdo->prepare("SELECT full_name, email, profile_photo FROM users WHERE user_id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch();

$stmt_wishlist_count = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
$stmt_wishlist_count->execute([$user_id]);
$total_wishlist = $stmt_wishlist_count->fetchColumn();

// Fetch Wishlist Items
$stmt = $pdo->prepare("
    SELECT w.*, p.product_name, p.regular_price, p.sale_price,
           (SELECT image_url FROM product_images WHERE product_id = p.product_id AND is_primary = TRUE LIMIT 1) as image
    FROM wishlist w
    JOIN products p ON w.product_id = p.product_id
    WHERE w.user_id = ?
");
$stmt->execute([$user_id]);
$wishlist_items = $stmt->fetchAll();

$name_parts = explode(' ', $user['full_name']);
$initials = strtoupper(substr($name_parts[0], 0, 1) . (isset($name_parts[1]) ? substr($name_parts[1], 0, 1) : ''));

$page_title = 'My Wishlist';
include 'includes/header.php';
?>

<link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/account-dashboard.css">

<div class="vn-dash-wrapper">

    <!-- SIDEBAR -->
    <aside class="vn-sidebar" id="dashSidebar">
        <div class="vn-sidebar-brand">
            <h2>VÉNARO</h2>
            <span>My Account</span>
        </div>
        <div class="vn-sidebar-user">
            <div class="vn-sidebar-avatar">
                <?php if (!empty($user['profile_photo'])): ?>
                    <img src="<?php echo UPLOADS_URL . '/profiles/' . $user['profile_photo']; ?>" alt="">
                <?php else: ?>
                    <?php echo $initials; ?>
                <?php endif; ?>
            </div>
            <div class="vn-sidebar-user-info">
                <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
                <span>Active</span>
            </div>
        </div>
        <div class="vn-sidebar-label">Main</div>
        <ul class="vn-sidebar-nav">
            <li><a href="profile.php"><i class="material-icons">dashboard</i> Dashboard</a></li>
        </ul>
        <div class="vn-sidebar-label">Shopping</div>
        <ul class="vn-sidebar-nav">
            <li><a href="orders.php"><i class="material-icons">shopping_bag</i> My Orders</a></li>
            <li><a href="wishlist.php" class="active"><i class="material-icons">favorite_border</i> Wishlist</a></li>
        </ul>
        <div class="vn-sidebar-label">Account</div>
        <ul class="vn-sidebar-nav">
            <li><a href="profile.php"><i class="material-icons">person_outline</i> My Profile</a></li>
        </ul>
        <div class="vn-sidebar-footer">
            <a href="logout.php"><i class="material-icons">logout</i> Logout</a>
        </div>
    </aside>

    <div class="vn-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- MAIN -->
    <main class="vn-dash-main">
        <div class="vn-dash-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <button class="vn-mobile-toggle" onclick="toggleSidebar()"><i class="material-icons">menu</i></button>
                <div class="vn-dash-header-left">
                    <h1>Wishlist</h1>
                    <p>Dashboard · Wishlist</p>
                </div>
            </div>
            <div class="vn-dash-header-right">
                <a href="index.php" class="vn-header-icon" title="Store"><i class="material-icons">storefront</i></a>
                <div class="vn-header-user">
                    <div class="vn-header-user-avatar"><?php echo $initials; ?></div>
                    <span class="vn-header-user-name"><?php echo htmlspecialchars($user['full_name']); ?></span>
                </div>
            </div>
        </div>

        <div class="vn-dash-content">
            <div class="vn-dash-title-row">
                <div>
                    <h2 class="vn-dash-page-title">My Wishlist</h2>
                    <p class="vn-dash-page-subtitle"><?php echo $total_wishlist; ?> saved items</p>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="vn-alert vn-alert-success"><i class="material-icons" style="font-size:18px;">check_circle</i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (empty($wishlist_items)): ?>
                <div class="vn-empty-state">
                    <i class="material-icons">favorite_border</i>
                    <h3>Your wishlist is empty</h3>
                    <p>Save the pieces you love — they'll be waiting for you.</p>
                    <a href="shop.php" class="vn-btn vn-btn-primary">Explore Collection</a>
                </div>
            <?php else: ?>
                <div class="vn-wishlist-grid">
                    <?php foreach ($wishlist_items as $item): ?>
                        <div class="vn-wishlist-card">
                            <div class="vn-wishlist-img">
                                <a href="product-detail.php?id=<?php echo $item['product_id']; ?>">
                                    <img src="<?php echo UPLOADS_URL . '/products/' . ($item['image'] ?? 'default.jpg'); ?>"
                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                </a>
                                <a href="wishlist.php?action=remove&product_id=<?php echo $item['product_id']; ?>"
                                   class="vn-wishlist-heart" title="Remove">
                                    <i class="material-icons">favorite</i>
                                </a>
                                <div class="vn-wishlist-overlay">
                                    <form action="cart.php" method="POST" style="width:100%;">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="vn-add-to-cart">Add to Bag</button>
                                    </form>
                                </div>
                            </div>
                            <div class="vn-wishlist-info">
                                <div class="vn-wishlist-name">
                                    <a href="product-detail.php?id=<?php echo $item['product_id']; ?>">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                    </a>
                                </div>
                                <div class="vn-wishlist-price">
                                    <?php if ($item['sale_price']): ?>
                                        <?php echo format_price($item['sale_price']); ?>
                                        <span class="vn-wishlist-price-old"><?php echo format_price($item['regular_price']); ?></span>
                                    <?php else: ?>
                                        <?php echo format_price($item['regular_price']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('dashSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
</script>

<?php include 'includes/footer.php'; ?>