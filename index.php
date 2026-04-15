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

<div style="display: flex; flex-direction: column; gap: 32px;">
    <!-- Dashboard Header -->
    <header style="margin-bottom: 8px;">
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 700; color: #0f172a; margin: 0;">Executive Dashboard</h1>
        <p style="color: #64748b; margin: 8px 0 0;"><?= date('l, d F Y'); ?> \u2014 Welcome back, <?= h(currentUserName()); ?></p>
    </header>

    <!-- Stats Grid -->
    <section style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
        <div class="card" style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="width: 48px; height: 48px; background: #eff6ff; color: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="package" size="24"></i>
                </div>
                <span style="font-size: 0.7rem; font-weight: 700; color: #10b981; background: #ecfdf5; padding: 4px 8px; border-radius: 99px;">ACTIVE</span>
            </div>
            <div>
                <h3 style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin: 0;">Total Chapati Sold</h3>
                <p style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 700; color: #0f172a; margin: 8px 0 0;"><?= h((string) $totalQuantity); ?></p>
            </div>
        </div>

        <div class="card" style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="width: 48px; height: 48px; background: #faf5ff; color: #a855f7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="users" size="24"></i>
                </div>
                <span style="font-size: 0.7rem; font-weight: 700; color: #3b82f6; background: #eff6ff; padding: 4px 8px; border-radius: 99px;">NEW</span>
            </div>
            <div>
                <h3 style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin: 0;">Total Earnings</h3>
                <p style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 700; color: #0f172a; margin: 8px 0 0;"><?= h(formatInr($totalEarnings)); ?></p>
            </div>
        </div>

        <div class="card" style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="width: 48px; height: 48px; background: #fef2f2; color: #ef4444; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="check-circle" size="24"></i>
                </div>
                <span style="font-size: 0.7rem; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 4px 8px; border-radius: 99px;">DONE</span>
            </div>
            <div>
                <h3 style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin: 0;">Recorded Days</h3>
                <p style="font-family: 'Outfit', sans-serif; font-size: 1.8rem; font-weight: 700; color: #0f172a; margin: 8px 0 0;"><?= h((string) $recordedDays); ?></p>
            </div>
        </div>
    </section>

    <!-- Main Content Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: start;">
        <!-- Daily Report Table -->
        <section class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.1rem; font-weight: 700; margin: 0;">Daily Report</h2>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <form method="get" style="display: flex; gap: 8px;">
                        <input type="month" name="month" value="<?= h($selectedMonth); ?>" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                        <button type="submit" class="btn btn-primary" style="padding: 6px 16px; background: #0f172a; color: #fff; border: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer;">Filter</button>
                    </form>
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 16px 24px; font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Date</th>
                            <th style="padding: 16px 24px; font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Quantity</th>
                            <th style="padding: 16px 24px; font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Total</th>
                            <th style="padding: 16px 24px; font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700;">Status</th>
                            <th style="padding: 16px 24px; font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                            <?php
                            $dateKey = sprintf('%s-%02d', $selectedMonth, $day);
                            $entry = $recordsByDate[$dateKey] ?? null;
                            ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 16px 24px; font-size: 0.9rem; font-weight: 600;"><?= h(date('d M Y', strtotime($dateKey))); ?></td>
                                <td style="padding: 16px 24px; font-size: 0.9rem;"><?= h($entry['quantity'] ?? '-'); ?></td>
                                <td style="padding: 16px 24px; font-size: 0.9rem; font-weight: 600;"><?= h($entry ? formatInr((int)$entry['total']) : '-'); ?></td>
                                <td style="padding: 16px 24px;">
                                    <?php if ($entry): ?>
                                        <span style="font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 99px; <?= $entry['status'] === 'Completed' ? 'background: #ecfdf5; color: #059669;' : 'background: #fef2f2; color: #dc2626;' ?>">
                                            <?= h($entry['status']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="font-size: 0.75rem; color: #94a3b8;">Not Recorded</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 16px 24px; text-align: center;">
                                    <?php if ($entry): ?>
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <a href="edit_entry.php?id=<?= h((string)$entry['id']); ?>" style="padding: 4px; color: #64748b; transition: color 0.2s;" title="Edit"><i data-lucide="edit-3" size="16"></i></a>
                                            <form action="delete_entry.php" method="post" onsubmit="return confirm('Delete this entry?');" style="display: inline;">
                                                <input type="hidden" name="id" value="<?= h((string)$entry['id']); ?>">
                                                <button type="submit" style="background: none; border: none; padding: 4px; color: #ef4444; cursor: pointer; transition: color 0.2s;" title="Delete"><i data-lucide="trash-2" size="16"></i></button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Form Sidebar (Right Column) -->
        <aside style="display: flex; flex-direction: column; gap: 24px;">
            <section class="card">
                <h2 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 20px 0;">Add Daily Entry</h2>
                <form action="save_entry.php" method="post" style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Sale Date</label>
                        <input type="date" name="sale_date" value="<?= h($defaultDate); ?>" required style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Quantity</label>
                        <input type="number" name="quantity" placeholder="Chapatis sold" style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    </div>
                    <button type="submit" style="padding: 12px; background: #0f172a; color: #fff; border: none; border-radius: 8px; font-weight: 800; cursor: pointer; transition: transform 0.2s;">
                        SAVE RECORD
                    </button>
                </form>
            </section>
            
            <section class="card" style="background: #1e293b; color: #fff; border: none;">
                <h3 style="font-size: 1rem; font-weight: 700; margin: 0 0 12px 0; color: #fff;">Need Help?</h3>
                <p style="font-size: 0.85rem; color: #94a3b8; margin: 0 0 20px 0; line-height: 1.6;">If you leave the quantity empty, the system will mark it as "No Order".</p>
                <div style="padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 12px;">
                    <i data-lucide="info" size="18" style="color: #3b82f6;"></i>
                    <span style="font-size: 0.85rem; font-weight: 600;">System Version 2.0</span>
                </div>
            </section>
        </aside>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
