<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

$entryId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$selectedMonth = sanitizeMonth($_GET['month'] ?? null);

if ($entryId === false || $entryId === null) {
    setFlash('error', 'Invalid entry selected.');
    redirect('index.php?month=' . urlencode($selectedMonth));
}

$fetchStmt = $pdo->prepare('SELECT id, sale_date, quantity, rate, total, status FROM chapati_sales WHERE id = :id LIMIT 1');
$fetchStmt->execute(['id' => $entryId]);
$entry = $fetchStmt->fetch();

if ($entry === false) {
    setFlash('error', 'Entry not found.');
    redirect('index.php?month=' . urlencode($selectedMonth));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saleDate = trim((string) ($_POST['sale_date'] ?? ''));
    $saleData = buildSaleData($_POST['quantity'] ?? null);

    if (!isValidDate($saleDate)) {
        $errors[] = 'Invalid date. Please select a valid date.';
    }

    if ($saleData['valid'] !== true) {
        $errors[] = (string) $saleData['error'];
    }

    if ($errors === []) {
        $updateStmt = $pdo->prepare(
            'UPDATE chapati_sales
             SET sale_date = :sale_date,
                 quantity = :quantity,
                 rate = :rate,
                 total = :total,
                 status = :status,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );

        $updateStmt->bindValue(':sale_date', $saleDate);

        if ($saleData['quantity'] === null) {
            $updateStmt->bindValue(':quantity', null, PDO::PARAM_NULL);
        } else {
            $updateStmt->bindValue(':quantity', (int) $saleData['quantity'], PDO::PARAM_INT);
        }

        $updateStmt->bindValue(':rate', CHAPATI_RATE, PDO::PARAM_INT);
        $updateStmt->bindValue(':total', (int) $saleData['total'], PDO::PARAM_INT);
        $updateStmt->bindValue(':status', (string) $saleData['status']);
        $updateStmt->bindValue(':id', (int) $entryId, PDO::PARAM_INT);

        try {
            $updateStmt->execute();
            setFlash('success', 'Entry updated successfully.');
            redirect('index.php?month=' . urlencode(monthFromDate($saleDate)));
        } catch (PDOException $exception) {
            if ((string) $exception->getCode() === '23000') {
                $errors[] = 'An entry for this date already exists.';
            } else {
                $errors[] = 'Unable to update entry right now.';
            }
        }
    }

    if ($errors === []) {
        $entry['sale_date'] = $saleDate;
        $entry['quantity'] = $saleData['quantity'];
        $entry['rate'] = CHAPATI_RATE;
        $entry['total'] = $saleData['total'];
        $entry['status'] = $saleData['status'];
    }
}

$quantityValue = $entry['quantity'] === null ? '' : (string) $entry['quantity'];
$pageTitle = 'Edit Entry';
require_once __DIR__ . '/includes/header.php';
?>

<section class="panel">
    <div class="table-headline">
        <h2>Edit Entry</h2>
        <a class="btn btn-ghost" href="index.php?month=<?= h($selectedMonth); ?>">Back to Dashboard</a>
    </div>

    <?php if ($errors !== []): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?= h($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="entry-form">
        <div class="form-grid">
            <div>
                <label for="sale_date">Date</label>
                <input type="date" id="sale_date" name="sale_date" value="<?= h((string) $entry['sale_date']); ?>" required>
            </div>
            <div>
                <label for="quantityInput">Quantity</label>
                <input type="number" id="quantityInput" name="quantity" min="0" step="1" value="<?= h($quantityValue); ?>" placeholder="Leave blank for no order">
            </div>
            <div>
                <label for="rateInput">Rate</label>
                <input type="text" id="rateInput" value="<?= h((string) CHAPATI_RATE); ?>" readonly>
            </div>
            <div>
                <label for="totalPreview">Total</label>
                <input type="text" id="totalPreview" value="<?= h((string) $entry['total']); ?>" readonly>
            </div>
            <div>
                <label for="statusPreview">Status</label>
                <input type="text" id="statusPreview" value="<?= h((string) $entry['status']); ?>" readonly>
            </div>
            <div class="form-action-wrap">
                <button type="submit" class="btn btn-primary full">Update Entry</button>
            </div>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
