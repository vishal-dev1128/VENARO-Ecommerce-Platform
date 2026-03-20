<?php

/**
 * VÉNARO Admin Panel - Contact Messages
 */
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Mark message as read
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$_GET['read']]);
    header('Location: messages.php');
    exit();
}

// Mark all as read
if (isset($_GET['read_all'])) {
    $pdo->exec("UPDATE contact_messages SET is_read = 1");
    header('Location: messages.php');
    exit();
}

// Delete message
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$_GET['delete']]);
    header('Location: messages.php');
    exit();
}

// Filter: unread only
$filter = $_GET['filter'] ?? 'all';
$where  = ($filter === 'unread') ? "WHERE is_read = 0" : "";

$messages = $pdo->query("SELECT * FROM contact_messages $where ORDER BY created_at DESC")->fetchAll();
$unread_count = (int) $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();

$page_title = 'Messages';
include 'includes/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Contact Messages</h1>
            <p class="admin-page-subtitle">Customer inquiries submitted via the contact form.</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <?php if ($unread_count > 0): ?>
                <a href="messages.php?read_all=1" class="btn btn-outline-dark btn-sm">
                    <i class="material-icons" style="font-size:16px;">done_all</i>
                    Mark All Read
                </a>
            <?php endif; ?>
            <a href="messages.php?filter=<?= $filter === 'unread' ? 'all' : 'unread' ?>" class="btn btn-outline-dark btn-sm">
                <i class="material-icons" style="font-size:16px;"><?= $filter === 'unread' ? 'inbox' : 'mark_email_unread' ?></i>
                <?= $filter === 'unread' ? 'Show All' : "Unread ($unread_count)" ?>
            </a>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="modern-card">
        <div class="modern-card-body p-0">
            <?php if (empty($messages)): ?>
                <div class="text-center py-5">
                    <i class="material-icons text-muted" style="font-size: 64px;">mark_email_read</i>
                    <p class="text-muted mt-3 mb-0">No messages found.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th style="width:30px;"></th>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th style="width:120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                                <?php $unread = !$msg['is_read']; ?>
                                <tr style="<?= $unread ? 'background:#fffbf5;' : '' ?>">
                                    <td>
                                        <?php if ($unread): ?>
                                            <span title="Unread" style="display:inline-block;width:9px;height:9px;border-radius:50%;background:#1a73e8;"></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-weight:<?= $unread ? 700 : 500 ?>;font-size:14px;color:#212121;">
                                            <?= htmlspecialchars($msg['name']) ?>
                                        </div>
                                        <div style="font-size:12px;color:#999;">
                                            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" style="color:#999;text-decoration:none;">
                                                <?= htmlspecialchars($msg['email']) ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background:#f4f4f4;color:#555;font-weight:500;font-size:12px;padding:5px 10px;border-radius:4px;">
                                            <?= htmlspecialchars($msg['subject']) ?>
                                        </span>
                                    </td>
                                    <td style="max-width:360px;">
                                        <p style="font-size:13px;color:#555;margin:0;line-height:1.5;
                                            display:-webkit-box;line-clamp:2;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                        </p>
                                    </td>
                                    <td style="font-size:13px;color:#888;white-space:nowrap;">
                                        <?= date('M d, Y', strtotime($msg['created_at'])) ?><br>
                                        <span style="font-size:11px;"><?= date('h:i A', strtotime($msg['created_at'])) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <!-- View / Reply -->
                                            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>?subject=Re: <?= urlencode($msg['subject']) ?>"
                                                class="btn btn-outline-dark btn-sm" title="Reply via email"
                                                onclick="markRead(<?= $msg['id'] ?>)">
                                                <i class="material-icons" style="font-size:16px;">reply</i>
                                            </a>
                                            <?php if ($unread): ?>
                                                <a href="messages.php?read=<?= $msg['id'] ?>" class="btn btn-outline-dark btn-sm" title="Mark as read">
                                                    <i class="material-icons" style="font-size:16px;">done</i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="messages.php?delete=<?= $msg['id'] ?>"
                                                class="btn btn-sm" style="border:1px solid #e74c3c;color:#e74c3c;background:transparent;"
                                                title="Delete"
                                                onclick="return confirm('Delete this message?')">
                                                <i class="material-icons" style="font-size:16px;">delete_outline</i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Full message row (expandable) -->
                                <tr style="background:#fafafa;border-top:none;">
                                    <td colspan="6" style="padding:0 16px 14px 48px;font-size:13px;color:#444;line-height:1.7;border-top:none;">
                                        <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function markRead(id) {
        fetch('messages.php?read=' + id);
    }
</script>

<?php include 'includes/footer.php'; ?>