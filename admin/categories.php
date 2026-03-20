<?php
session_start();
require_once '../config.php';

// Ensure display_order column exists
try {
    $pdo->exec("ALTER TABLE categories ADD COLUMN display_order INT DEFAULT 0");
} catch (PDOException $e) {
}

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
        $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        $_SESSION['success'] = "Category deleted successfully.";
        header('Location: categories.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error deleting category: " . $e->getMessage();
    }
}

// Handle Add/Edit Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'] ?? '';
    // Gender column does not exist in DB, mapped to description if needed or ignored
    $description = $_POST['description'] ?? '';

    $category_id = $_POST['category_id'] ?? '';
    // Handle Parent ID - convert empty string to NULL
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : NULL;

    // Auto-generate slug with uniqueness check
    $base_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $category_name)));
    if (empty($base_slug)) $base_slug = 'category-' . time();
    $slug = $base_slug;
    $counter = 1;
    while (true) {
        if ($category_id) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = ? AND category_id != ?");
            $stmt->execute([$slug, $category_id]);
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        if ($stmt->fetchColumn() == 0) break;
        $slug = $base_slug . '-' . $counter;
        $counter++;
    }

    if (empty($category_name)) {
        $error = "Category Name is required.";
    } else {
        try {
            if ($category_id) {
                // Update
                $stmt = $pdo->prepare("UPDATE categories SET category_name = ?, slug = ?, description = ?, parent_id = ? WHERE category_id = ?");
                $stmt->execute([$category_name, $slug, $description, $parent_id, $category_id]);
                $_SESSION['success'] = "Category updated successfully.";
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO categories (category_name, slug, description, parent_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$category_name, $slug, $description, $parent_id]);
                $_SESSION['success'] = "Category added successfully.";
            }

            // Handle Image Upload
            if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] === UPLOAD_ERR_OK) {
                // Determine ID (either existing or new)
                $target_id = $category_id ? $category_id : $pdo->lastInsertId();

                $upload_dir = '../uploads/categories/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $file_ext = strtolower(pathinfo($_FILES['category_image']['name'], PATHINFO_EXTENSION));
                $new_filename = 'cat_' . $target_id . '_' . uniqid() . '.' . $file_ext;
                $dest_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['category_image']['tmp_name'], $dest_path)) {
                    // Fix: Column is 'image' not 'image_url'
                    $stmt = $pdo->prepare("UPDATE categories SET image = ? WHERE category_id = ?");
                    $stmt->execute([$new_filename, $target_id]);
                }
            }

            header('Location: categories.php');
            exit();
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// Fetch Categories - build tree (sorted by display_order)
$all_cats = $pdo->query("
    SELECT c.*, p.category_name as parent_name 
    FROM categories c 
    LEFT JOIN categories p ON c.parent_id = p.category_id 
    ORDER BY ISNULL(c.parent_id) DESC, COALESCE(c.display_order, 9999) ASC, c.category_name ASC
")->fetchAll();

// Build tree structure
$cat_tree = [];
$child_list = [];
foreach ($all_cats as $cat) {
    if (empty($cat['parent_id'])) {
        $cat['children'] = [];
        $cat_tree[$cat['category_id']] = $cat;
    }
}
foreach ($all_cats as $cat) {
    if (!empty($cat['parent_id']) && isset($cat_tree[$cat['parent_id']])) {
        $cat_tree[$cat['parent_id']]['children'][] = $cat;
        $child_list[] = $cat;
    }
}
// Flatten for table display
$categories = [];
foreach ($cat_tree as $parent) {
    $parent_copy = $parent;
    unset($parent_copy['children']);
    $parent_copy['_is_parent'] = true;
    $parent_copy['_child_count'] = count($parent['children']);
    $categories[] = $parent_copy;
    foreach ($parent['children'] as $child) {
        $child['_is_parent'] = false;
        $child['_child_count'] = 0;
        $categories[] = $child;
    }
}
// Also add orphan children (parent deleted)
foreach ($all_cats as $cat) {
    if (!empty($cat['parent_id']) && !isset($cat_tree[$cat['parent_id']])) {
        $cat['_is_parent'] = false;
        $cat['_child_count'] = 0;
        $categories[] = $cat;
    }
}

// Only top-level categories allowed as parent (prevent circular refs)
$parents = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY category_name")->fetchAll();

$page_title = 'Categories';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Categories</h1>
    </div>

    <div class="row">
        <!-- Add/Edit Form -->
        <div class="col-md-4">
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" id="formTitle" style="font-weight: 700; color: var(--jet-black);">Add New Category</h5>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" id="category_id" name="category_id" value="">

                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select class="form-select" id="parent_id" name="parent_id">
                                <option value="">None (Top Level)</option>
                                <?php foreach ($parents as $p): ?>
                                    <option value="<?php echo $p['category_id']; ?>"><?php echo htmlspecialchars($p['category_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category_image" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="category_image" name="category_image" accept="image/*">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn" style="padding: 10px; font-weight: 600;">Add Category</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetForm()" id="cancelBtn" style="display: none;">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div class="col-md-8">
            <div class="modern-card">
                <div class="modern-card-body">
                    <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">All Categories</h5>

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
                                    <th style="width: 32px;"></th>
                                    <th style="width: 50px;">Image</th>
                                    <th>Category</th>
                                    <th style="width: 80px;">Type</th>
                                    <th style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="material-icons" style="font-size: 48px; color: #dee2e6;">folder</i>
                                                <p class="mt-2 mb-0">No categories found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr data-id="<?php echo $cat['category_id']; ?>" style="<?php echo empty($cat['_is_parent']) && !empty($cat['parent_id']) ? 'background: #fafbfc;' : ''; ?>">
                                            <td style="cursor: grab; color: #adb5bd; text-align: center; padding-left: 8px;" class="drag-handle">
                                                <i class="material-icons" style="font-size: 18px; vertical-align: middle;">drag_indicator</i>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($cat['image'])): ?>
                                                    <img src="<?php echo UPLOADS_URL . '/categories/' . $cat['image']; ?>"
                                                        alt="Cat" class="rounded" style="width: 36px; height: 36px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 36px; height: 36px; margin: 0 auto;">
                                                        <i class="material-icons" style="font-size: 18px;"><?php echo empty($cat['parent_id']) ? 'folder' : 'subdirectory_arrow_right'; ?></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($cat['parent_id'])): ?>
                                                    <span style="color: #adb5bd; margin-right: 4px; font-size: 13px;">└─</span>
                                                    <span style="font-weight: 500; color: #495057; font-size: 12px;"><?php echo htmlspecialchars($cat['category_name']); ?></span>
                                                    <div style="padding-left: 22px;"><small style="color: #adb5bd; font-size: 10px;">in <?php echo htmlspecialchars($cat['parent_name'] ?? ''); ?></small></div>
                                                <?php else: ?>
                                                    <span style="font-weight: 700; color: #212529;"><?php echo htmlspecialchars($cat['category_name']); ?></span>
                                                    <?php if (!empty($cat['_child_count'])): ?>
                                                        <span style="margin-left: 6px; background: rgba(249,115,22,0.1); color: #ea580c; font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 10px;"><?php echo $cat['_child_count']; ?> sub</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (empty($cat['parent_id'])): ?>
                                                    <span style="background: rgba(37,99,235,0.08); color: #2563eb; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">Parent</span>
                                                <?php else: ?>
                                                    <span style="background: rgba(107,114,128,0.08); color: #6b7280; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">Sub</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-btn-group">
                                                    <button type="button" class="action-btn action-btn-primary"
                                                        onclick="editCategory(<?php echo htmlspecialchars(json_encode($cat)); ?>)" title="Edit">
                                                        <i class="material-icons" style="font-size: 16px;">edit</i>
                                                    </button>
                                                    <a href="categories.php?delete_id=<?php echo $cat['category_id']; ?>"
                                                        class="action-btn action-btn-danger" title="Delete"
                                                        onclick="venaroConfirm('Are you sure you want to delete this category?', () => window.location.href='categories.php?delete_id=<?php echo $cat['category_id']; ?>', {title: 'Delete Category', confirmText: 'Delete'})">
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
    function editCategory(cat) {
        document.getElementById('formTitle').innerText = 'Edit Category';
        document.getElementById('submitBtn').innerText = 'Update Category';
        document.getElementById('cancelBtn').style.display = 'block';

        document.getElementById('category_id').value = cat.category_id;
        document.getElementById('category_name').value = cat.category_name;
        document.getElementById('description').value = cat.description || '';
        document.getElementById('parent_id').value = cat.parent_id || '';

        // Scroll to form
        document.getElementById('formTitle').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function resetForm() {
        document.getElementById('formTitle').innerText = 'Add New Category';
        document.getElementById('submitBtn').innerText = 'Add Category';
        document.getElementById('cancelBtn').style.display = 'none';

        document.getElementById('category_id').value = '';
        document.getElementById('category_name').value = '';
        document.getElementById('description').value = '';
        document.getElementById('parent_id').value = '';
    }
</script>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    // Toast helper
    function showOrderToast(msg, isError) {
        const t = document.createElement('div');
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;background:' + (isError ? '#dc3545' : '#212529') + ';color:#fff;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:500;box-shadow:0 4px 20px rgba(0,0,0,0.18);transition:opacity 0.4s;';
        t.innerText = msg;
        document.body.appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0';
            setTimeout(() => t.remove(), 400);
        }, 2000);
    }

    const tbody = document.querySelector('.modern-table tbody');
    if (tbody) {
        Sortable.create(tbody, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                const rows = tbody.querySelectorAll('tr[data-id]');
                const order = Array.from(rows).map(r => r.dataset.id);
                fetch('category-order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            order: order
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        showOrderToast(data.success ? '✓ Order saved' : 'Error: ' + data.message, !data.success);
                    })
                    .catch(() => showOrderToast('Network error', true));
            }
        });
    }
</script>
<style>
    .drag-handle:hover {
        color: #495057 !important;
    }

    .drag-handle:active {
        cursor: grabbing !important;
    }

    .sortable-ghost {
        opacity: 0.4;
        background: #e9ecef !important;
    }
</style>

<?php include 'includes/footer.php'; ?>