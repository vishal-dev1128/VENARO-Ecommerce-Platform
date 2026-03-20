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

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM collections WHERE collection_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        $_SESSION['success'] = "Collection deleted successfully.";
        header('Location: collections.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error deleting collection: " . $e->getMessage();
    }
}

// Handle Add/Edit Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $collection_name = $_POST['collection_name'] ?? '';
    // Auto-generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $collection_name)));
    if (empty($slug)) $slug = 'collection-' . time();

    $status = $_POST['status'] ?? 'Active';
    $display_order = $_POST['display_order'] ?? 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $collection_id = $_POST['collection_id'] ?? '';

    if (empty($collection_name)) {
        $error = "Collection Name is required.";
    } else {
        try {
            if ($collection_id) {
                // Update
                $stmt = $pdo->prepare("UPDATE collections SET collection_name = ?, slug = ?, status = ?, display_order = ?, is_featured = ? WHERE collection_id = ?");
                $stmt->execute([$collection_name, $slug, $status, $display_order, $is_featured, $collection_id]);
                $_SESSION['success'] = "Collection updated successfully.";
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO collections (collection_name, slug, status, display_order, is_featured) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$collection_name, $slug, $status, $display_order, $is_featured]);
                $_SESSION['success'] = "Collection added successfully.";
            }

            // Handle Image Upload
            if (isset($_FILES['collection_image']) && $_FILES['collection_image']['error'] === UPLOAD_ERR_OK) {
                // Determine ID (either existing or new)
                $target_id = $collection_id ? $collection_id : $pdo->lastInsertId();

                $upload_dir = '../uploads/collections/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $file_ext = strtolower(pathinfo($_FILES['collection_image']['name'], PATHINFO_EXTENSION));
                $new_filename = 'col_' . $target_id . '_' . uniqid() . '.' . $file_ext;
                $dest_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['collection_image']['tmp_name'], $dest_path)) {
                    // Fix: Column is 'image' not 'image_url'
                    $stmt = $pdo->prepare("UPDATE collections SET image = ? WHERE collection_id = ?");
                    $stmt->execute([$new_filename, $target_id]);
                }
            }

            header('Location: collections.php');
            exit();
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// Fetch Collections
$collections = $pdo->query("SELECT * FROM collections ORDER BY created_at DESC")->fetchAll();

$page_title = 'Collections';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Collections</h1>
    </div>

    <div class="row">
        <!-- Add/Edit Form -->
        <div class="col-md-4">
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" id="formTitle" style="font-weight: 700; color: var(--jet-black);">Add New Collection</h5>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" id="collection_id" name="collection_id" value="">

                        <div class="mb-3">
                            <label for="collection_name" class="form-label">Collection Name *</label>
                            <input type="text" class="form-control" id="collection_name" name="collection_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control" id="display_order" name="display_order" value="0">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                            <label class="form-check-label" for="is_featured">Featured on Homepage</label>
                        </div>

                        <div class="mb-3">
                            <label for="collection_image" class="form-label">Collection Image</label>
                            <input type="file" class="form-control" id="collection_image" name="collection_image" accept="image/*">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn" style="padding: 10px; font-weight: 600;">Add Collection</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()" id="cancelBtn" style="display: none;">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Collections List -->
        <div class="col-md-8">
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">All Collections</h5>

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
                                    <th style="width: 70px;">Image</th>
                                    <th>Collection Name</th>
                                    <th>Status</th>
                                    <th>Order</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($collections)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="material-icons" style="font-size: 48px; color: #dee2e6;">collections</i>
                                                <p class="mt-2 mb-0">No collections found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($collections as $col): ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if (!empty($col['image'])): ?>
                                                    <img src="<?php echo UPLOADS_URL . '/collections/' . $col['image']; ?>"
                                                        alt="Col" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 40px; height: 40px;">
                                                        <i class="material-icons" style="font-size: 20px;">collections</i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span style="font-weight: 600; color: #212529;"><?php echo htmlspecialchars($col['collection_name']); ?></span>
                                                <?php if ($col['is_featured']): ?>
                                                    <span class="badge bg-warning text-dark ms-2" style="font-size: 10px; vertical-align: middle;">FEATURED</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $col['status'] == 'Active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo htmlspecialchars($col['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo $col['display_order']; ?></td>
                                            <td>
                                                <div class="action-btn-group">
                                                    <button type="button" class="action-btn action-btn-primary"
                                                        onclick="editCollection(<?php echo htmlspecialchars(json_encode($col)); ?>)" title="Edit">
                                                        <i class="material-icons" style="font-size: 16px;">edit</i>
                                                    </button>
                                                    <a href="collections.php?delete_id=<?php echo $col['collection_id']; ?>"
                                                        class="action-btn action-btn-danger" title="Delete"
                                                        onclick="venaroConfirm('Are you sure you want to delete this collection?', () => window.location.href='collections.php?delete_id=<?php echo $col['collection_id']; ?>', {title: 'Delete Collection', confirmText: 'Delete'})">
                                                        <i class="material-icons" style="font-size: 16px;">delete</i>
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
    function editCollection(col) {
        document.getElementById('formTitle').innerText = 'Edit Collection';
        document.getElementById('submitBtn').innerText = 'Update Collection';
        document.getElementById('cancelBtn').style.display = 'block';

        document.getElementById('collection_id').value = col.collection_id;
        document.getElementById('collection_name').value = col.collection_name;
        document.getElementById('status').value = col.status;
        document.getElementById('display_order').value = col.display_order;
        document.getElementById('is_featured').checked = (col.is_featured == 1);

        // Scroll to form
        document.getElementById('formTitle').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function resetForm() {
        document.getElementById('formTitle').innerText = 'Add New Collection';
        document.getElementById('submitBtn').innerText = 'Add Collection';
        document.getElementById('cancelBtn').style.display = 'none';

        document.getElementById('collection_id').value = '';
        document.getElementById('collection_name').value = '';
        document.getElementById('status').value = 'Active';
        document.getElementById('display_order').value = '0';
        document.getElementById('is_featured').checked = false;
    }
</script>

<?php include 'includes/footer.php'; ?>