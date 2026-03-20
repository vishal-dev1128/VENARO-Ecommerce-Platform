<?php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Sanitize inputs
$full_name = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$date_of_birth = trim($_POST['date_of_birth'] ?? '');
$gender = trim($_POST['gender'] ?? '');

// Validate
if (empty($full_name)) {
    echo json_encode(['success' => false, 'message' => 'Full name is required.']);
    exit();
}

if (strlen($full_name) > 100) {
    echo json_encode(['success' => false, 'message' => 'Full name must be less than 100 characters.']);
    exit();
}

// Validate phone if provided
if (!empty($phone) && !preg_match('/^[\+\d\s\-\(\)]{7,20}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid phone number.']);
    exit();
}

// Validate gender
$valid_genders = ['Male', 'Female', 'Other', 'Prefer not to say', ''];
if (!in_array($gender, $valid_genders)) {
    echo json_encode(['success' => false, 'message' => 'Invalid gender selection.']);
    exit();
}

// Validate date of birth
if (!empty($date_of_birth)) {
    $dob = DateTime::createFromFormat('Y-m-d', $date_of_birth);
    if (!$dob || $dob->format('Y-m-d') !== $date_of_birth) {
        echo json_encode(['success' => false, 'message' => 'Invalid date of birth.']);
        exit();
    }
}

try {
    $stmt = $pdo->prepare("
        UPDATE users SET 
            full_name = ?, 
            phone = ?, 
            date_of_birth = ?, 
            gender = ?,
            updated_at = NOW()
        WHERE user_id = ?
    ");
    $stmt->execute([
        $full_name,
        !empty($phone) ? $phone : null,
        !empty($date_of_birth) ? $date_of_birth : null,
        !empty($gender) ? $gender : null,
        $user_id
    ]);

    // Update session
    $_SESSION['user_name'] = $full_name;

    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully.',
        'name' => htmlspecialchars($full_name)
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
