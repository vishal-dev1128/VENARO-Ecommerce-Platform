<?php
session_start();
require_once '../config.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$success = '';
$error   = '';

// Handle Delete
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    try {
        // Prevent deleting yourself (if admin has a user account)
        $pdo->prepare("DELETE FROM reviews WHERE user_id = ?")->execute([$del_id]);
        $pdo->prepare("DELETE FROM wishlist WHERE user_id = ?")->execute([$del_id]);
        $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$del_id]);
        $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$del_id]);
        $success = "Customer deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting customer: " . $e->getMessage();
    }
}

// Handle Block / Unblock
if (isset($_GET['block_id'])) {
    $block_id = intval($_GET['block_id']);
    $pdo->prepare("UPDATE users SET status = 'Blocked' WHERE user_id = ?")->execute([$block_id]);
    $success = "Customer blocked.";
}

if (isset($_GET['unblock_id'])) {
    $unblock_id = intval($_GET['unblock_id']);
    $pdo->prepare("UPDATE users SET status = 'Active' WHERE user_id = ?")->execute([$unblock_id]);
    $success = "Customer unblocked.";
}

// Search / Filter
$search_query = trim($_GET['q'] ?? '');
$where = '1=1';
$params = [];
if ($search_query) {
    $where = "(full_name LIKE ? OR email LIKE ?)";
    $params = ["%$search_query%", "%$search_query%"];
}

// Fetch Customers
$stmt = $pdo->prepare("SELECT * FROM users WHERE $where ORDER BY created_at DESC");
$stmt->execute($params);
$customers = $stmt->fetchAll();

$page_title = 'Customers';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Customers</h1>
        <span class="text-muted" style="font-size:13px;"><?php echo count($customers); ?> total</span>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Search Bar -->
    <div class="modern-card mb-3">
        <div class="modern-card-body" style="padding: 14px 20px;">
            <form method="GET" class="d-flex gap-2 align-items-center">
                <div class="input-group" style="max-width: 380px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="material-icons" style="font-size:18px; color:#999;">search</i>
                    </span>
                    <input type="text" name="q" class="form-control border-start-0"
                        placeholder="Search by name or email..."
                        value="<?php echo htmlspecialchars($search_query); ?>"
                        style="font-size:13px;">
                </div>
                <button type="submit" class="btn btn-dark btn-sm" style="padding: 8px 18px; font-size:12px; letter-spacing:1px;">Search</button>
                <?php if ($search_query): ?>
                    <a href="customers.php" class="btn btn-outline-secondary btn-sm" style="font-size:12px;">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="modern-card">
        <div class="modern-card-body">
            <div class="table-responsive">
                <table class="modern-table table" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="material-icons" style="font-size: 48px; color: #dee2e6;">people</i>
                                        <p class="mt-2 mb-0">No customers found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td style="font-size:12px; color:#999;">#<?php echo $customer['user_id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; flex-shrink:0;">
                                                <i class="material-icons" style="font-size: 18px; color: #6c757d;">person</i>
                                            </div>
                                            <span style="font-weight: 600; color: #212529;"><?php echo htmlspecialchars($customer['full_name']); ?></span>
                                        </div>
                                    </td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($customer['email']); ?>" style="color: #0d6efd; text-decoration: none;"><?php echo htmlspecialchars($customer['email']); ?></a></td>
                                    <td>
                                        <?php if ($customer['status'] === 'Active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Blocked</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size:13px; color:#666;"><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                                    <td>
                                        <div class="action-btn-group">
                                            <!-- Block / Unblock -->
                                            <?php if ($customer['status'] === 'Active'): ?>
                                                <button class="action-btn" title="Block Customer"
                                                    onclick="venaroConfirm('Block this customer? They will not be able to log in.', () => window.location.href='customers.php?block_id=<?php echo $customer['user_id']; ?>', {title: 'Block Customer', confirmText: 'Block'})"
                                                    style="color:#f39c12;">
                                                    <i class="material-icons" style="font-size:16px;">block</i>
                                                </button>
                                            <?php else: ?>
                                                <button class="action-btn" title="Unblock Customer"
                                                    onclick="window.location.href='customers.php?unblock_id=<?php echo $customer['user_id']; ?>'"
                                                    style="color:#22c55e;">
                                                    <i class="material-icons" style="font-size:16px;">check_circle</i>
                                                </button>
                                            <?php endif; ?>

                                            <!-- Delete -->
                                            <button class="action-btn action-btn-danger" title="Delete Customer"
                                                onclick="venaroConfirm('Delete this customer permanently? All their reviews and wishlist data will also be removed.', () => window.location.href='customers.php?delete_id=<?php echo $customer['user_id']; ?>', {title: 'Delete Customer', confirmText: 'Delete'})">
                                                <i class="material-icons" style="font-size:16px;">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>