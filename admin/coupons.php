<?php
session_start();
require_once '../config.php';

// Check authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

// Handle Delete
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM coupons WHERE coupon_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        $_SESSION['success'] = "Coupon deleted successfully.";
        header('Location: coupons.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper($_POST['coupon_code']);
    $type = $_POST['discount_type'];
    $value = $_POST['discount_value'];
    $expiry = $_POST['expiry_date'];
    $status = $_POST['status'];

    // Check if code exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM coupons WHERE coupon_code = ?");
    $stmt->execute([$code]);
    if ($stmt->fetchColumn() > 0 && !isset($_POST['coupon_id'])) {
        $error = "Coupon code already exists!";
    } else {
        try {
            if (isset($_POST['coupon_id']) && !empty($_POST['coupon_id'])) {
                // Update
                $stmt = $pdo->prepare("UPDATE coupons SET coupon_code=?, discount_type=?, discount_value=?, expiry_date=?, status=? WHERE coupon_id=?");
                $stmt->execute([$code, $type, $value, $expiry, $status, $_POST['coupon_id']]);
                $_SESSION['success'] = "Coupon updated successfully.";
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO coupons (coupon_code, discount_type, discount_value, expiry_date, status) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$code, $type, $value, $expiry, $status]);
                $_SESSION['success'] = "Coupon created successfully.";
            }
            header('Location: coupons.php');
            exit();
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// Fetch Coupons
$coupons = $pdo->query("SELECT * FROM coupons ORDER BY created_at DESC")->fetchAll();

$page_title = 'Coupons';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Coupons</h1>
    </div>

    <div class="row">
        <!-- Coupon Form -->
        <div class="col-md-4">
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" id="formTitle" style="font-weight: 700; color: var(--jet-black);">Add New Coupon</h5>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="coupon_id" id="coupon_id" value="">

                        <div class="mb-3">
                            <label class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" name="coupon_code" id="coupon_code" required style="text-transform: uppercase; font-weight: 600; letter-spacing: 1px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount Type</label>
                            <select class="form-select" name="discount_type" id="discount_type">
                                <option value="Percentage">Percentage (%)</option>
                                <option value="Flat">Fixed Amount (<?php echo CURRENCY_SYMBOL; ?>)</option>
                                <option value="Free Shipping">Free Shipping</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="number" class="form-control" name="discount_value" id="discount_value" step="0.01" value="0">
                            <small class="text-muted">Enter 0 for Free Shipping</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="expiry_date" id="expiry_date" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="Active">Active</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn" style="padding: 10px; font-weight: 600;">Save Coupon</button>
                            <button type="button" class="btn btn-outline-secondary" id="cancelBtn" onclick="resetForm()" style="display:none;">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Coupons List -->
        <div class="col-md-8">
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">Active Coupons</h5>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="modern-table table" width="100%">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Expiry</th>
                                    <th>Status</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($coupons)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="material-icons" style="font-size: 48px; color: #dee2e6;">local_offer</i>
                                                <p class="mt-2 mb-0">No coupons found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($coupons as $c): ?>
                                        <tr>
                                            <td><span class="badge bg-light text-dark border" style="font-size: 13px; letter-spacing: 1px;"><?php echo htmlspecialchars($c['coupon_code']); ?></span></td>
                                            <td>
                                                <span style="font-weight: 600; color: #212529;">
                                                    <?php
                                                    if ($c['discount_type'] == 'Percentage') echo isset($c['discount_value']) ? $c['discount_value'] . '%' : '0%';
                                                    elseif ($c['discount_type'] == 'Flat') echo CURRENCY_SYMBOL . (isset($c['discount_value']) ? $c['discount_value'] : '0');
                                                    else echo 'Free Shipping';
                                                    ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($c['expiry_date'])); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $c['status'] == 'Active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo $c['status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-btn-group">
                                                    <button class="action-btn action-btn-primary" onclick="editCoupon(<?php echo htmlspecialchars(json_encode($c)); ?>)" title="Edit">
                                                        <i class="material-icons" style="font-size:16px;">edit</i>
                                                    </button>
                                                    <button type="button" class="action-btn action-btn-danger" title="Delete"
                                                        onclick="venaroConfirm('Are you sure you want to delete this coupon?', () => window.location.href='coupons.php?delete_id=<?php echo $c['coupon_id']; ?>', {title: 'Delete Coupon', confirmText: 'Delete'})">
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
    </div>
</div>

<script>
    function editCoupon(data) {
        document.getElementById('formTitle').innerText = 'Edit Coupon';
        document.getElementById('submitBtn').innerText = 'Update Coupon';
        document.getElementById('cancelBtn').style.display = 'block';

        document.getElementById('coupon_id').value = data.coupon_id;
        document.getElementById('coupon_code').value = data.coupon_code;
        document.getElementById('discount_type').value = data.discount_type;
        document.getElementById('discount_value').value = data.discount_value;
        document.getElementById('expiry_date').value = data.expiry_date;
        document.getElementById('status').value = data.status;

        // Scroll to form
        document.getElementById('formTitle').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function resetForm() {
        document.getElementById('formTitle').innerText = 'Add New Coupon';
        document.getElementById('submitBtn').innerText = 'Save Coupon';
        document.getElementById('cancelBtn').style.display = 'none';

        document.getElementById('coupon_id').value = '';
        document.getElementById('coupon_code').value = '';
        document.getElementById('discount_type').value = 'Percentage';
        document.getElementById('discount_value').value = '0';
        document.getElementById('expiry_date').value = '';
        document.getElementById('status').value = 'Active';
    }
</script>

<?php include 'includes/footer.php'; ?>