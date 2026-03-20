<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $pdo->prepare("UPDATE reviews SET status = 'Approved' WHERE review_id = ?")->execute([$id]);
        $_SESSION['success'] = "Review approved.";
    } elseif ($action == 'reject') {
        $pdo->prepare("UPDATE reviews SET status = 'Rejected' WHERE review_id = ?")->execute([$id]);
        $_SESSION['success'] = "Review rejected.";
    } elseif ($action == 'delete') {
        $pdo->prepare("DELETE FROM reviews WHERE review_id = ?")->execute([$id]);
        $_SESSION['success'] = "Review deleted.";
    }
    header('Location: reviews.php');
    exit();
}

// Fetch Reviews
$sql = "
    SELECT r.*, p.product_name, u.full_name 
    FROM reviews r 
    JOIN products p ON r.product_id = p.product_id 
    JOIN users u ON r.user_id = u.user_id 
    ORDER BY r.created_at DESC
";
$reviews = $pdo->query($sql)->fetchAll();

$page_title = 'Product Reviews';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Reviews & Ratings</h1>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="modern-card">
        <div class="modern-card-body">
            <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">All Reviews</h5>
            <div class="table-responsive">
                <table class="modern-table table" width="100%">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>User</th>
                            <th>Rating</th>
                            <th style="width: 40%">Review</th>
                            <th>Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reviews)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="material-icons" style="font-size: 48px; color: #dee2e6;">star_border</i>
                                        <p class="mt-2 mb-0">No reviews found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reviews as $r): ?>
                                <tr>
                                    <td><span style="font-weight: 600; color: #212529;"><?php echo htmlspecialchars($r['product_name']); ?></span></td>
                                    <td><?php echo htmlspecialchars($r['full_name']); ?></td>
                                    <td>
                                        <div class="d-flex text-warning">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="material-icons" style="font-size:16px;">
                                                    <?php echo $i <= $r['rating'] ? 'star' : 'star_border'; ?>
                                                </i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #212529; margin-bottom: 4px;"><?php echo htmlspecialchars($r['review_title']); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($r['review_text']); ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $badge = match ($r['status']) {
                                            'Approved' => 'success',
                                            'Rejected' => 'danger',
                                            default => 'warning'
                                        };
                                        ?>
                                        <span class="badge bg-<?php echo $badge; ?>"><?php echo $r['status']; ?></span>
                                    </td>
                                    <td>
                                        <div class="action-btn-group">
                                            <?php if ($r['status'] == 'Pending'): ?>
                                                <a href="reviews.php?action=approve&id=<?php echo $r['review_id']; ?>"
                                                    class="action-btn text-success border-success" title="Approve">
                                                    <i class="material-icons" style="font-size:16px;">check</i>
                                                </a>
                                                <a href="reviews.php?action=reject&id=<?php echo $r['review_id']; ?>"
                                                    class="action-btn text-warning border-warning" title="Reject">
                                                    <i class="material-icons" style="font-size:16px;">close</i>
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="action-btn action-btn-danger" title="Delete"
                                                onclick="venaroConfirm('Are you sure you want to delete this review?', () => window.location.href='reviews.php?action=delete&id=<?php echo $r['review_id']; ?>', {title: 'Delete Review', confirmText: 'Delete'})">
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