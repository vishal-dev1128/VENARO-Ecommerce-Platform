<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

$selectedMonth = sanitizeMonth($_GET['month'] ?? null);
[$startDate, $endDate, $daysInMonth] = monthRange($selectedMonth);

$recordsStmt = $pdo->prepare(
    'SELECT id, sale_date, quantity, rate, total, status
     FROM chapati_sales
     WHERE sale_date BETWEEN :start_date AND :end_date
     ORDER BY sale_date ASC'
);
$recordsStmt->execute([
    'start_date' => $startDate,
    'end_date' => $endDate,
]);

$records = $recordsStmt->fetchAll();
$recordsByDate = [];

foreach ($records as $record) {
    $recordsByDate[(string) $record['sale_date']] = $record;
}

$summaryStmt = $pdo->prepare(
    "SELECT
        COALESCE(SUM(quantity), 0) AS total_quantity,
        COALESCE(SUM(total), 0) AS total_earnings,
        COUNT(*) AS recorded_days,
        COALESCE(SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END), 0) AS completed_days,
        COALESCE(SUM(CASE WHEN status = 'No Order' THEN 1 ELSE 0 END), 0) AS no_order_days
     FROM chapati_sales
     WHERE sale_date BETWEEN :start_date AND :end_date"
);
$summaryStmt->execute([
    'start_date' => $startDate,
    'end_date' => $endDate,
]);

$summary = $summaryStmt->fetch();

$totalQuantity = (int) ($summary['total_quantity'] ?? 0);
$totalEarnings = (int) ($summary['total_earnings'] ?? 0);
$recordedDays = (int) ($summary['recorded_days'] ?? 0);
$completedDays = (int) ($summary['completed_days'] ?? 0);
$noOrderDays = (int) ($summary['no_order_days'] ?? 0);

$defaultDate = $selectedMonth === date('Y-m') ? date('Y-m-d') : $selectedMonth . '-01';

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
?>

<section class="panel month-filter-panel">
    <form method="get" class="month-form">
        <label for="month">Select Month</label>
        <input type="month" id="month" name="month" value="<?= h($selectedMonth); ?>">
        <button class="btn btn-primary" type="submit">Load</button>
    </form>
    <div class="month-meta">Showing data from <?= h(date('F Y', strtotime($startDate))); ?></div>
</section>

<section class="summary-grid">
    <article class="summary-card">
        <h3>Total Chapati Sold</h3>
        <p><?= h((string) $totalQuantity); ?></p>
    </article>
    <article class="summary-card">
        <h3>Total Earnings</h3>
        <p><?= h(formatInr($totalEarnings)); ?></p>
    </article>
    <article class="summary-card">
        <h3>Recorded Days</h3>
        <p><?= h((string) $recordedDays); ?></p>
    </article>
    <article class="summary-card">
        <h3>No Order Days</h3>
        <p><?= h((string) $noOrderDays); ?></p>
    </article>
</section>

<section class="panel">
    <h2>Add Daily Entry</h2>
    <form action="save_entry.php" method="post" class="entry-form">
        <input type="hidden" name="month" value="<?= h($selectedMonth); ?>">

        <div class="form-grid">
            <div>
                <label for="sale_date">Date</label>
                <input type="date" id="sale_date" name="sale_date" value="<?= h($defaultDate); ?>" required>
            </div>
            <div>
                <label for="quantityInput">Quantity</label>
                <input type="number" id="quantityInput" name="quantity" min="0" step="1" placeholder="Leave blank for no order">
            </div>
            <div>
                <label for="rateInput">Rate</label>
                <input type="text" id="rateInput" value="<?= h((string) CHAPATI_RATE); ?>" readonly>
            </div>
            <div>
                <label for="totalPreview">Total</label>
                <input type="text" id="totalPreview" value="0" readonly>
            </div>
            <div>
                <label for="statusPreview">Status</label>
                <input type="text" id="statusPreview" value="No Order" readonly>
            </div>
            <div class="form-action-wrap">
                <button type="submit" class="btn btn-primary full">Save Entry</button>
            </div>
        </div>
    </form>
    <p class="subtle">If quantity is empty, status will be saved as No Order and total as 0.</p>
</section>

<section class="panel table-panel">
    <div class="table-headline">
        <h2>Daily Report</h2>
        <p>Completed days: <?= h((string) $completedDays); ?></p>
    </div>

    <div class="table-wrap">
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                    <?php
                    $dateKey = sprintf('%s-%02d', $selectedMonth, $day);
                    $entry = $recordsByDate[$dateKey] ?? null;
                    ?>
                    <tr class="<?= $entry === null ? 'row-empty' : ($entry['status'] === 'No Order' ? 'row-no-order' : 'row-completed'); ?>">
                        <td><?= h(date('d M Y', strtotime($dateKey))); ?></td>
                        <?php if ($entry === null): ?>
                            <td class="center">-</td>
                            <td class="center"><?= h((string) CHAPATI_RATE); ?></td>
                            <td class="center">0</td>
                            <td class="center">Not Recorded</td>
                            <td class="center">-</td>
                        <?php else: ?>
                            <td class="center"><?= h($entry['quantity'] === null ? 'No Order' : (string) $entry['quantity']); ?></td>
                            <td class="center"><?= h((string) $entry['rate']); ?></td>
                            <td class="center"><?= h((string) $entry['total']); ?></td>
                            <td class="center"><?= h((string) $entry['status']); ?></td>
                            <td class="action-cell">
                                <a class="btn btn-small btn-ghost" href="edit_entry.php?id=<?= h((string) $entry['id']); ?>&month=<?= h($selectedMonth); ?>">Edit</a>
                                <form action="delete_entry.php" method="post" onsubmit="return confirm('Delete this entry?');">
                                    <input type="hidden" name="id" value="<?= h((string) $entry['id']); ?>">
                                    <input type="hidden" name="month" value="<?= h($selectedMonth); ?>">
                                    <button class="btn btn-small btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td>Monthly Total</td>
                    <td class="center"><?= h((string) $totalQuantity); ?></td>
                    <td class="center">-</td>
                    <td class="center"><?= h((string) $totalEarnings); ?></td>
                    <td class="center">Summary</td>
                    <td class="center">-</td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
