<?php
require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php?redirect=profile.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch full user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get stats
$stmt_orders = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt_orders->execute([$user_id]);
$total_orders = $stmt_orders->fetchColumn();

$stmt_wishlist = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
$stmt_wishlist->execute([$user_id]);
$total_wishlist = $stmt_wishlist->fetchColumn();

$stmt_delivered = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND order_status = 'Delivered'");
$stmt_delivered->execute([$user_id]);
$delivered_orders = $stmt_delivered->fetchColumn();

$stmt_pending = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND order_status IN ('Order Placed','Processing','Shipped')");
$stmt_pending->execute([$user_id]);
$pending_orders = $stmt_pending->fetchColumn();

// Get user initials for avatar
$name_parts = explode(' ', $user['full_name']);
$initials = strtoupper(substr($name_parts[0], 0, 1) . (isset($name_parts[1]) ? substr($name_parts[1], 0, 1) : ''));

$page_title = 'My Profile';
include 'includes/header.php';
?>

<link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/account-dashboard.css">

<div class="vn-dash-wrapper">

    <!-- ═══ SIDEBAR ═══ -->
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
            <li><a href="profile.php" class="active"><i class="material-icons">dashboard</i> Dashboard</a></li>
        </ul>

        <div class="vn-sidebar-label">Shopping</div>
        <ul class="vn-sidebar-nav">
            <li><a href="orders.php"><i class="material-icons">shopping_bag</i> My Orders</a></li>
            <li><a href="wishlist.php"><i class="material-icons">favorite_border</i> Wishlist</a></li>
        </ul>

        <div class="vn-sidebar-label">Account</div>
        <ul class="vn-sidebar-nav">
            <li><a href="profile.php" class="active"><i class="material-icons">person_outline</i> My Profile</a></li>
        </ul>

        <div class="vn-sidebar-footer">
            <a href="logout.php"><i class="material-icons">logout</i> Logout</a>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div class="vn-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ═══ MAIN ═══ -->
    <main class="vn-dash-main">

        <!-- Top Header -->
        <div class="vn-dash-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <button class="vn-mobile-toggle" onclick="toggleSidebar()"><i class="material-icons">menu</i></button>
                <div class="vn-dash-header-left">
                    <h1>My Profile</h1>
                    <p>Dashboard · My Profile</p>
                </div>
            </div>
            <div class="vn-dash-header-right">
                <a href="index.php" class="vn-header-icon" title="Go to Store"><i class="material-icons">storefront</i></a>
                <a href="wishlist.php" class="vn-header-icon" title="Wishlist">
                    <i class="material-icons">favorite_border</i>
                    <?php if ($total_wishlist > 0): ?><span class="vn-header-badge"></span><?php endif; ?>
                </a>
                <div class="vn-header-user">
                    <div class="vn-header-user-avatar">
                        <?php if (!empty($user['profile_photo'])): ?>
                            <img src="<?php echo UPLOADS_URL . '/profiles/' . $user['profile_photo']; ?>" alt="">
                        <?php else: ?>
                            <?php echo $initials; ?>
                        <?php endif; ?>
                    </div>
                    <span class="vn-header-user-name"><?php echo htmlspecialchars($user['full_name']); ?></span>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="vn-dash-content">

            <div class="vn-dash-title-row">
                <div>
                    <h2 class="vn-dash-page-title">Welcome back, <?php echo htmlspecialchars(explode(' ', $user['full_name'])[0]); ?></h2>
                    <p class="vn-dash-page-subtitle"><?php echo date('l, d F Y'); ?> · Manage your account settings</p>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="vn-stats-grid">
                <div class="vn-stat-card">
                    <div class="vn-stat-icon blue"><i class="material-icons">shopping_bag</i></div>
                    <div class="vn-stat-info">
                        <div class="vn-stat-value"><?php echo $total_orders; ?></div>
                        <div class="vn-stat-label">Total Orders</div>
                    </div>
                </div>
                <div class="vn-stat-card">
                    <div class="vn-stat-icon green"><i class="material-icons">check_circle</i></div>
                    <div class="vn-stat-info">
                        <div class="vn-stat-value"><?php echo $delivered_orders; ?></div>
                        <div class="vn-stat-label">Delivered</div>
                    </div>
                    <span class="vn-stat-tag green">✓ Done</span>
                </div>
                <div class="vn-stat-card">
                    <div class="vn-stat-icon yellow"><i class="material-icons">local_shipping</i></div>
                    <div class="vn-stat-info">
                        <div class="vn-stat-value"><?php echo $pending_orders; ?></div>
                        <div class="vn-stat-label">In Progress</div>
                    </div>
                    <?php if ($pending_orders > 0): ?>
                        <span class="vn-stat-tag yellow">Active</span>
                    <?php endif; ?>
                </div>
                <div class="vn-stat-card">
                    <div class="vn-stat-icon pink"><i class="material-icons">favorite</i></div>
                    <div class="vn-stat-info">
                        <div class="vn-stat-value"><?php echo $total_wishlist; ?></div>
                        <div class="vn-stat-label">Wishlist Items</div>
                    </div>
                </div>
            </div>

            <!-- Alert -->
            <div id="profileAlert" style="display: none;"></div>

            <!-- Profile Form + Security side by side -->
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="vn-card">
                        <div class="vn-card-header">
                            <div class="vn-card-header-left">
                                <i class="material-icons">person</i>
                                <h3 class="vn-card-title">Personal Information</h3>
                            </div>
                        </div>
                        <div class="vn-card-body">
                            <form id="profileForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="vn-form-group">
                                            <label class="vn-form-label">Full Name</label>
                                            <input type="text" class="vn-form-input" name="full_name"
                                                   value="<?php echo htmlspecialchars($user['full_name']); ?>" placeholder="Enter full name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="vn-form-group">
                                            <label class="vn-form-label">Email Address</label>
                                            <input type="email" class="vn-form-input"
                                                   value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="vn-form-group">
                                            <label class="vn-form-label">Phone Number</label>
                                            <input type="tel" class="vn-form-input" name="phone"
                                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="+91 XXXXX XXXXX">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="vn-form-group">
                                            <label class="vn-form-label">Date of Birth</label>
                                            <input type="date" class="vn-form-input" name="date_of_birth"
                                                   value="<?php echo htmlspecialchars($user['date_of_birth'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="vn-form-group">
                                            <label class="vn-form-label">Gender</label>
                                            <select class="vn-form-input" name="gender">
                                                <option value="">Select gender</option>
                                                <option value="Male" <?php echo ($user['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                                <option value="Female" <?php echo ($user['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                                <option value="Other" <?php echo ($user['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                                <option value="Prefer not to say" <?php echo ($user['gender'] === 'Prefer not to say') ? 'selected' : ''; ?>>Prefer not to say</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="vn-form-group">
                                            <label class="vn-form-label">Member Since</label>
                                            <input type="text" class="vn-form-input"
                                                   value="<?php echo date('d M Y', strtotime($user['created_at'])); ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="vn-btn vn-btn-primary" id="saveProfileBtn">
                                    <i class="material-icons" style="font-size:16px;">save</i> Save Changes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="vn-card">
                        <div class="vn-card-header">
                            <div class="vn-card-header-left">
                                <i class="material-icons" style="color: var(--accent-red);">lock</i>
                                <h3 class="vn-card-title">Change Password</h3>
                            </div>
                        </div>
                        <div class="vn-card-body">
                            <form id="passwordForm">
                                <div class="vn-form-group">
                                    <label class="vn-form-label">Current Password</label>
                                    <input type="password" class="vn-form-input" name="current_password"
                                           placeholder="Enter current password" autocomplete="off">
                                </div>
                                <div class="vn-form-group">
                                    <label class="vn-form-label">New Password</label>
                                    <input type="password" class="vn-form-input" name="new_password" id="newPassword"
                                           placeholder="Enter new password" autocomplete="off">
                                </div>
                                <div class="vn-form-group">
                                    <label class="vn-form-label">Confirm Password</label>
                                    <input type="password" class="vn-form-input" name="confirm_password" id="confirmPassword"
                                           placeholder="Confirm new password" autocomplete="off">
                                </div>
                                <button type="submit" class="vn-btn vn-btn-dark" id="changePasswordBtn">
                                    <i class="material-icons" style="font-size:16px;">vpn_key</i> Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
// Mobile sidebar toggle
function toggleSidebar() {
    document.getElementById('dashSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

document.addEventListener('DOMContentLoaded', function() {
    function showAlert(message, type) {
        const alertDiv = document.getElementById('profileAlert');
        alertDiv.className = type === 'success' ? 'vn-alert vn-alert-success' : 'vn-alert vn-alert-error';
        alertDiv.innerHTML = '<i class="material-icons" style="font-size:18px;">' + (type === 'success' ? 'check_circle' : 'error') + '</i> ' + message;
        alertDiv.style.display = 'flex';
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => { alertDiv.style.display = 'none'; }, 5000);
    }

    // Profile Update
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveProfileBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="material-icons" style="font-size:16px;">hourglass_empty</i> Saving...';

        fetch('api/update-profile.php', { method: 'POST', body: new FormData(this) })
        .then(res => res.json())
        .then(data => {
            showAlert(data.message || (data.success ? 'Profile updated!' : 'Update failed.'), data.success ? 'success' : 'error');
            if (data.success && data.name) {
                document.querySelectorAll('.vn-sidebar-user-info h4, .vn-header-user-name').forEach(el => el.textContent = data.name);
            }
        })
        .catch(() => showAlert('An unexpected error occurred.', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="material-icons" style="font-size:16px;">save</i> Save Changes'; });
    });

    // Password Change
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmPassword').value;

        if (newPass !== confirmPass) { showAlert('Passwords do not match.', 'error'); return; }
        if (newPass.length < 6) { showAlert('Password must be at least 6 characters.', 'error'); return; }

        const btn = document.getElementById('changePasswordBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="material-icons" style="font-size:16px;">hourglass_empty</i> Updating...';

        fetch('api/change-password.php', { method: 'POST', body: new FormData(this) })
        .then(res => res.json())
        .then(data => {
            showAlert(data.message || (data.success ? 'Password updated!' : 'Update failed.'), data.success ? 'success' : 'error');
            if (data.success) this.reset();
        })
        .catch(() => showAlert('An unexpected error occurred.', 'error'))
        .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="material-icons" style="font-size:16px;">vpn_key</i> Update Password'; });
    });
});
</script>

<?php include 'includes/footer.php'; ?>