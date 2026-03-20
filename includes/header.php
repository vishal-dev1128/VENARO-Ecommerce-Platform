<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME . ' - ' . SITE_TAGLINE; ?></title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo isset($meta_description) ? $meta_description : 'VÉNARO - Ultra Premium Fashion eCommerce Platform. Discover premium quality t-shirts, hoodies, and apparel made with Supima cotton.'; ?>">
    <meta name="keywords" content="premium fashion, t-shirts, hoodies, supima cotton, VÉNARO">
    <meta name="author" content="VÉNARO">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title : SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo isset($meta_description) ? $meta_description : 'Ultra Premium Fashion eCommerce Platform'; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo ASSETS_URL; ?>/images/favicon.ico">

    <!-- Google Fonts Preconnect (Performance Optimization) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Montserrat:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Material Design Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css?v=<?php echo time(); ?>">

    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler border-0" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav">
                <i class="material-icons">menu</i>
            </button>

            <!-- Logo -->
            <a class="navbar-brand d-flex flex-column" href="<?php echo SITE_URL; ?>">
                <span class="brand-logo">VÉNARO</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/shop.php">Shop</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-mdb-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            // Fetch all categories
                            $nav_cats = $pdo->query("SELECT * FROM categories ORDER BY COALESCE(display_order, 9999) ASC, category_name ASC")->fetchAll();

                            // Organize into hierarchy
                            $cat_tree = [];
                            foreach ($nav_cats as $c) {
                                if (empty($c['parent_id'])) {
                                    $cat_tree[$c['category_id']] = $c;
                                    $cat_tree[$c['category_id']]['children'] = [];
                                }
                            }
                            foreach ($nav_cats as $c) {
                                if (!empty($c['parent_id']) && isset($cat_tree[$c['parent_id']])) {
                                    $cat_tree[$c['parent_id']]['children'][] = $c;
                                }
                            }

                            foreach ($cat_tree as $parent_cat):
                            ?>
                                <?php if (empty($parent_cat['children'])): ?>
                                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo htmlspecialchars($parent_cat['slug']); ?>"><?php echo htmlspecialchars($parent_cat['category_name']); ?></a></li>
                                <?php else: ?>
                                    <li class="dropdown-submenu">
                                        <a class="dropdown-item dropdown-toggle" href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo htmlspecialchars($parent_cat['slug']); ?>">
                                            <?php echo htmlspecialchars($parent_cat['category_name']); ?>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php foreach ($parent_cat['children'] as $child_cat): ?>
                                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo htmlspecialchars($child_cat['slug']); ?>"><?php echo htmlspecialchars($child_cat['category_name']); ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/contact.php">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Right Side Icons -->
            <div class="d-flex align-items-center">

                <!-- Social Icons (top bar) -->
                <div class="d-none d-lg-flex align-items-center me-1">
                    <a href="https://www.facebook.com/profile.php?id=61582406730314" target="_blank"
                        class="btn btn-link text-dark p-1" title="Facebook" style="opacity:0.7;transition:opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7">
                        <i class="material-icons" style="font-size:20px;">facebook</i>
                    </a>
                    <a href="https://www.instagram.com/venaro_apparel/" target="_blank"
                        class="btn btn-link text-dark p-1" title="Instagram" style="opacity:0.7;transition:opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" viewBox="0 0 16 16" style="margin-bottom:1px;">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.844.047 1.097.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                        </svg>
                    </a>
                    <a href="mailto:info@venaro.com"
                        class="btn btn-link text-dark p-1" title="Email Us" style="opacity:0.7;transition:opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7">
                        <i class="material-icons" style="font-size:20px;">alternate_email</i>
                    </a>
                    <div style="width:1px;height:18px;background:#ddd;margin:0 8px;"></div>
                </div>

                <!-- Search Icon -->
                <button class="btn btn-link text-dark p-2" data-mdb-toggle="modal" data-mdb-target="#searchModal">
                    <i class="material-icons">search</i>
                </button>

                <!-- Wishlist -->
                <?php if (is_logged_in()): ?>
                    <a href="<?php echo SITE_URL; ?>/wishlist.php" class="btn btn-link text-dark p-2 position-relative">
                        <i class="material-icons wishlist-icon">favorite_border</i>
                        <?php
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
                        $stmt->execute([get_current_user_id()]);
                        $wishlist_count = $stmt->fetchColumn();
                        ?>
                        <span id="wishlist-badge" class="badge rounded-pill badge-notification bg-danger" style="<?php echo ($wishlist_count > 0) ? '' : 'display: none;'; ?>">
                            <?php echo $wishlist_count; ?>
                        </span>
                    </a>
                <?php endif; ?>

                <!-- Cart -->
                <a href="<?php echo SITE_URL; ?>/cart.php" class="btn btn-link text-dark p-2 position-relative">
                    <i class="material-icons cart-icon">shopping_bag</i>
                    <?php
                    if (is_logged_in()) {
                        $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
                        $stmt->execute([get_current_user_id()]);
                    } else {
                        $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE session_id = ?");
                        $stmt->execute([get_session_id()]);
                    }
                    $cart_count = $stmt->fetchColumn() ?? 0;
                    ?>
                    <span id="cart-badge" class="badge rounded-pill badge-notification bg-danger" style="<?php echo ($cart_count > 0) ? '' : 'display: none;'; ?>">
                        <?php echo $cart_count; ?>
                    </span>
                </a>

                <!-- User Account -->
                <?php if (is_logged_in()): ?>
                    <div class="dropdown">
                        <button class="btn btn-link text-dark p-2 dropdown-toggle" type="button" id="userDropdown" data-mdb-toggle="dropdown" aria-expanded="false" style="text-decoration:none;">
                            <i class="material-icons">person</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 260px; border: 1px solid #e8e8e8; border-radius: 0; box-shadow: 0 12px 40px rgba(0,0,0,0.10); overflow: hidden; margin-top: 12px;">

                            <!-- User Info Header -->
                            <div style="padding: 20px; display: flex; align-items: center; gap: 14px; border-bottom: 1px solid #eeeeee; background: #f8f8f8;">
                                <div style="width: 40px; height: 40px; border-radius: 0; background: #000; display: flex; align-items: center; justify-content: center; font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 700; color: #fff; flex-shrink: 0; letter-spacing: 1px;">
                                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                                </div>
                                <div>
                                    <div style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: #111; margin-bottom: 2px;">
                                        <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>
                                    </div>
                                    <div style="font-family: 'Montserrat', sans-serif; font-size: 10px; color: #888; letter-spacing: 1px; text-transform: uppercase;">
                                        <?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Links -->
                            <div style="padding: 8px;">
                                <a href="<?php echo SITE_URL; ?>/profile.php" class="vn-dropdown-link">
                                    <i class="material-icons" style="font-size: 16px; color: #333;">dashboard</i> Dashboard
                                </a>
                                <a href="<?php echo SITE_URL; ?>/orders.php" class="vn-dropdown-link">
                                    <i class="material-icons" style="font-size: 16px; color: #333;">shopping_bag</i> My Orders
                                </a>
                                <a href="<?php echo SITE_URL; ?>/wishlist.php" class="vn-dropdown-link">
                                    <i class="material-icons" style="font-size: 16px; color: #333;">favorite_border</i> Wishlist
                                </a>
                            </div>

                            <!-- Logout -->
                            <div style="padding: 8px; border-top: 1px solid #eeeeee;">
                                <a href="<?php echo SITE_URL; ?>/logout.php" class="vn-dropdown-link" style="color: #555;">
                                    <i class="material-icons" style="font-size: 16px; color: #555;">logout</i> Logout
                                </a>
                            </div>
                        </div>

                        <style>
                        .vn-dropdown-link {
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            padding: 10px 14px;
                            text-decoration: none;
                            color: #111;
                            font-family: 'Montserrat', sans-serif;
                            font-size: 11px;
                            font-weight: 600;
                            letter-spacing: 1px;
                            text-transform: uppercase;
                            border-radius: 0;
                            transition: background 0.2s ease;
                        }
                        .vn-dropdown-link:hover {
                            background: #f5f5f5;
                            color: #000;
                        }
                        </style>
                    </div>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/login.php" class="btn btn-link text-dark p-2">
                        <i class="material-icons">person_outline</i>
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </nav>

    <style>
        /* Multi-level Dropdown Support */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }

        /* Show submenu on hover */
        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }
    </style>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <form action="<?php echo SITE_URL; ?>/shop.php" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" name="search" placeholder="Search for products..." required>
                            <button class="btn btn-dark" type="submit">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                        <div id="searchSuggestions" class="list-group mt-2" style="display:none; position: absolute; width: 100%; z-index: 1000; top: 100%;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchModal = document.getElementById('searchModal');
            if (!searchModal) return;

            searchModal.addEventListener('shown.mdb.modal', function() {
                const searchInput = searchModal.querySelector('input[name="search"]');
                const suggestionsBox = searchModal.querySelector('#searchSuggestions');
                if (!searchInput || !suggestionsBox) return;

                searchInput.focus();

                searchInput.addEventListener('input', function() {
                    const query = this.value;
                    if (query.length < 2) {
                        suggestionsBox.style.display = 'none';
                        return;
                    }

                    fetch('<?php echo SITE_URL; ?>/api/search-suggestions.php?search=' + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const a = document.createElement('a');
                                    a.href = '<?php echo SITE_URL; ?>/product-detail.php?id=' + item.product_id;
                                    a.className = 'list-group-item list-group-item-action';
                                    a.textContent = item.product_name;
                                    suggestionsBox.appendChild(a);
                                });
                                suggestionsBox.style.display = 'block';
                            } else {
                                suggestionsBox.style.display = 'none';
                            }
                        })
                        .catch(() => {
                            suggestionsBox.style.display = 'none';
                        });
                });

                // Hide suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                        suggestionsBox.style.display = 'none';
                    }
                });
            });

            // Clear suggestions when modal closes
            searchModal.addEventListener('hidden.mdb.modal', function() {
                const suggestionsBox = searchModal.querySelector('#searchSuggestions');
                if (suggestionsBox) suggestionsBox.style.display = 'none';
            });
        });
    </script>