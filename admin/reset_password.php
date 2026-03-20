<?php
require_once '../config.php';

try {
    $email = 'admin@venaro.com';
    $new_password = '123456';
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Check if user exists first
    $stmt = $pdo->prepare("SELECT admin_id FROM admin_users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        // Update password
        $update = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE email = ?");
        $update->execute([$password_hash, $email]);
        echo "Password for '$email' has been reset successfully to '$new_password'.\n";
    } else {
        // Create user if missing (failsafe)
        $insert = $pdo->prepare("INSERT INTO admin_users (full_name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)");
        $insert->execute(['Super Admin', $email, $password_hash, 'Super Admin', 'Active']);
        echo "User '$email' was missing and has been created with password '$new_password'.\n";
    }
    
    // Verify
    $verify = $pdo->prepare("SELECT password_hash FROM admin_users WHERE email = ?");
    $verify->execute([$email]);
    $user = $verify->fetch();
    
    if (password_verify($new_password, $user['password_hash'])) {
        echo "VERIFICATION SUCCESS: Login should now work.\n";
    } else {
        echo "VERIFICATION FAILED: Something went wrong.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
