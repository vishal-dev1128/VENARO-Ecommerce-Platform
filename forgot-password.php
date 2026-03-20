<?php
require_once 'config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    
    if (empty($email)) {
        $error = 'Please enter your email address.';
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT user_id, full_name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // In a real app, send email here.
            // For now, we simulate success.
            $message = 'If an account exists with this email, you will receive password reset instructions shortly.';
        } else {
            // Same message for security (don't reveal user existence)
            $message = 'If an account exists with this email, you will receive password reset instructions shortly.';
        }
    }
}
?>
<?php
// Include Header
include 'includes/header.php';
?>

<div class="auth-minimal-page">
    <div class="auth-minimal-wrapper">
        <h2 class="auth-title-minimal">Reset Password</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger rounded-0 border-0 mb-5 small bg-opacity-10 bg-danger text-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="alert alert-success rounded-0 border-0 mb-5 small bg-opacity-10 bg-success text-success text-center">
                <?php echo $message; ?>
            </div>
            <div class="text-center">
                <a href="login.php" class="btn-minimal-black">Return to Login</a>
            </div>
        <?php else: ?>

            <form method="POST" action="">
                <div class="form-minimal-group">
                    <input type="email" class="form-minimal-control" id="email" name="email" placeholder="Email Address" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-minimal-black">
                        SEND RESET LINK
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="login.php" class="auth-create-account">Back to Login</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>
</body>
</html>
