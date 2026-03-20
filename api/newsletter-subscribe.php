<?php
header('Content-Type: application/json');
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Valid email required']);
    exit;
}

try {
    // Check if already subscribed
    $stmt = $pdo->prepare("SELECT subscriber_id, status FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        if ($existing['status'] === 'Active') {
            echo json_encode(['success' => false, 'message' => 'Email already subscribed']);
            exit;
        } else {
            // Reactivate subscription
            $stmt = $pdo->prepare("UPDATE newsletter_subscribers SET status = 'Active', subscribed_at = NOW() WHERE subscriber_id = ?");
            $stmt->execute([$existing['subscriber_id']]);
        }
    } else {
        // New subscription
        $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email, status, source, subscribed_at) VALUES (?, 'Active', 'Website', NOW())");
        $stmt->execute([$email]);
    }
    
    // Generate discount code (10% off)
    $discount_code = 'WELCOME10';
    
    // TODO: Send welcome email with discount code
    
    echo json_encode([
        'success' => true,
        'message' => 'Successfully subscribed!',
        'discount_code' => $discount_code
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Subscription failed']);
}
