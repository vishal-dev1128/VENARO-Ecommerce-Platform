<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$success = '';
$error = '';

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        foreach ($_POST['settings'] as $key => $value) {
            // Check if exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
            $stmt->execute([$key]);

            if ($stmt->fetchColumn() > 0) {
                // Update
                $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?")->execute([$value, $key]);
            } else {
                // Insert
                $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)")->execute([$key, $value]);
            }
        }

        $pdo->commit();
        $success = "Settings updated successfully.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error updating settings: " . $e->getMessage();
    }
}

// Fetch Settings
$settings_raw = $pdo->query("SELECT * FROM settings")->fetchAll(PDO::FETCH_ASSOC);
$settings = [];
foreach ($settings_raw as $s) {
    $settings[$s['setting_key']] = $s['setting_value'];
}

$page_title = 'Site Settings';
include 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center admin-page-header">
        <h1 class="admin-page-title">Site Configuration</h1>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <!-- General Settings -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="modern-card-body">
                        <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">General Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Site Name</label>
                            <input type="text" class="form-control" name="settings[site_name]"
                                value="<?php echo htmlspecialchars($settings['site_name'] ?? 'VÉNARO'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tagline</label>
                            <input type="text" class="form-control" name="settings[site_tagline]"
                                value="<?php echo htmlspecialchars($settings['site_tagline'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-control" name="settings[contact_email]"
                                value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" name="settings[contact_phone]"
                                value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- E-Commerce Settings -->
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="modern-card-body">
                        <h5 class="mb-4" style="font-weight: 700; color: var(--jet-black);">Store Configuration</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency Symbol</label>
                                <input type="text" class="form-control" name="settings[currency_symbol]"
                                    value="<?php echo htmlspecialchars($settings['currency_symbol'] ?? 'Rs. '); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency Code</label>
                                <input type="text" class="form-control" name="settings[currency]"
                                    value="<?php echo htmlspecialchars($settings['currency'] ?? 'INR'); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" class="form-control" name="settings[tax_rate]" step="0.01"
                                value="<?php echo htmlspecialchars($settings['tax_rate'] ?? '18.00'); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Free Shipping Threshold</label>
                            <input type="number" class="form-control" name="settings[free_shipping_threshold]" step="1"
                                value="<?php echo htmlspecialchars($settings['free_shipping_threshold'] ?? '999'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary px-5" style="font-weight: 600; padding: 12px 24px;">Save Configuration</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>