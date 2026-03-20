<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin'; ?> - VÉNARO Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Admin CSS -->
    <link href="assets/css/admin-custom.css" rel="stylesheet">

    <style>
        /* Fallback for sidebar if CSS fails to load */
        .sidebar {
            min-width: 200px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand-wrapper">
                <img src="assets/img/admin-logo.png" alt="VÉNARO Logo" class="sidebar-logo-img" style="height: 28px; width: auto; border-radius: 6px;">
                <div class="sidebar-title">VÉNARO</div>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="nav-group-title">MAIN</div>
            <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="material-icons">home</i>
                <span>Dashboard</span>
            </a>

            <div class="nav-group-title">PRODUCT & STOCK</div>
            <a href="products.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' || basename($_SERVER['PHP_SELF']) == 'product-add.php' || basename($_SERVER['PHP_SELF']) == 'product-edit.php' ? 'active' : ''; ?>">
                <i class="material-icons">inventory_2</i>
                <span>Products</span>
            </a>
            <a href="categories.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
                <i class="material-icons">category</i>
                <span>Categories</span>
            </a>


            <div class="nav-group-title">SALES & ORDERS</div>
            <a href="orders.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' || basename($_SERVER['PHP_SELF']) == 'order-detail.php' ? 'active' : ''; ?>">
                <i class="material-icons">shopping_cart</i>
                <span>Orders</span>
            </a>
            <a href="coupons.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'coupons.php' ? 'active' : ''; ?>">
                <i class="material-icons">local_offer</i>
                <span>Coupons</span>
            </a>

            <div class="nav-group-title">PEOPLE</div>
            <a href="customers.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>">
                <i class="material-icons">people</i>
                <span>Customers</span>
            </a>
            <a href="reviews.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'active' : ''; ?>">
                <i class="material-icons">rate_review</i>
                <span>Reviews</span>
            </a>
            <?php
            $msg_unread = (int) $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
            ?>
            <a href="messages.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>" style="position:relative;">
                <i class="material-icons">mail_outline</i>
                <span>Messages</span>
                <?php if ($msg_unread > 0): ?>
                    <span style="margin-left:auto;background:#1a73e8;color:#fff;font-size:10px;font-weight:700;border-radius:10px;padding:1px 7px;"><?php echo $msg_unread; ?></span>
                <?php endif; ?>
            </a>

            <div class="nav-group-title">SETTINGS</div>
            <a href="settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="material-icons">settings</i>
                <span>Settings</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <i class="material-icons" style="font-size: 20px; color: #666; cursor: pointer; margin-right: 16px;">menu</i>
                <div style="display: flex; flex-direction: column;">
                    <h1 class="top-bar-title" style="font-size: 15px; font-weight: 700; margin-bottom: 2px;"><?php echo $page_title ?? 'Dashboard'; ?></h1>
                    <span style="font-size: 11px; color: #666;">Dashboard</span>
                </div>
            </div>
            <div class="top-bar-right">
                
                <!-- Dark Mode Toggle (Visual Only for now) -->
                <button class="icon-btn" title="Toggle Dark Mode" style="width: 36px; height: 36px; border-radius: 50%; background: #f8f9fa;">
                    <i class="material-icons" style="font-size: 18px;">dark_mode</i>
                </button>

                <!-- Website Link -->
                <a href="<?php echo SITE_URL; ?>" target="_blank" class="icon-btn" title="View Store" style="width: 36px; height: 36px; border-radius: 50%; background: #f8f9fa; text-decoration: none; color: inherit;">
                    <i class="material-icons" style="font-size: 18px;">open_in_new</i>
                </a>

                <!-- Notifications (Linked to Orders) -->
                <?php
                // Dynamic Notification Count (Pending Orders)
                $notif_stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'Order Placed'");
                $notif_count = $notif_stmt->fetchColumn();
                ?>
                <a href="orders.php" class="icon-btn" title="Pending Orders" style="width: 36px; height: 36px; border-radius: 50%; background: #f8f9fa;">
                    <i class="material-icons" style="font-size: 18px;">notifications_none</i>
                    <?php if ($notif_count > 0): ?>
                        <span class="badge" style="background: #ef4444; width: 8px; height: 8px; min-width: 8px; border-radius: 50%; padding: 0; right: 8px; top: 8px; box-shadow: none;"></span>
                    <?php endif; ?>
                </a>

                <!-- Admin Profile (Linked to Settings) -->
                <a href="settings.php" class="admin-profile" title="Admin Settings" style="text-decoration: none; color: inherit; margin-left: 8px;">
                    <div style="display: flex; align-items: center; gap: 10px; padding: 4px 12px 4px 4px; border-radius: 20px; background: #f8f9fa;">
                        <div class="admin-avatar" style="width: 32px; height: 32px; font-size: 14px; background: #1a73e8;">
                            <?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'S', 0, 1)); ?>
                        </div>
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-size: 13px; font-weight: 600; color: #212121;"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'System Administrator'); ?></span>
                            <span style="font-size: 11px; color: #999;">Super admin</span>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- Page Content -->