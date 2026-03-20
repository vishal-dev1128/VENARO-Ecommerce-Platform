<?php

/**
 * VÉNARO Admin Panel - Login Page
 */
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

require_once '../config.php';

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = ? AND status = 'Active'");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                // Login successful
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_role'] = $admin['role'];
                $_SESSION['admin_email'] = $admin['email'];

                // Update last login
                $stmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE admin_id = ?");
                $stmt->execute([$admin['admin_id']]);

                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - VÉNARO</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --jet-black: #0a0a0a;
            --deep-black: #111111;
            --charcoal: #1a1a1a;
            --soft-white: #ffffff;
            --gray-light: #f5f5f5;
            --gray-medium: #999;
            --font-luxury: 'Playfair Display', serif;
            --font-modern: 'Inter', sans-serif;
            --font-heading: 'Montserrat', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-modern);
            background: var(--jet-black);
            min-height: 100vh;
            overflow: hidden;
        }

        /* Split Screen Layout */
        .login-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* Left Side - Brand Showcase */
        .brand-showcase {
            flex: 1;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .brand-showcase::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.02) 0%, transparent 50%);
            pointer-events: none;
        }

        .brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 60px;
            max-width: 600px;
        }

        .brand-logo-large {
            font-family: var(--font-luxury);
            font-size: 72px;
            font-weight: 800;
            letter-spacing: 16px;
            color: var(--soft-white);
            margin-bottom: 30px;
            text-transform: uppercase;
            text-shadow:
                0 2px 4px rgba(0, 0, 0, 0.1),
                0 4px 8px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 1s ease-out;
        }

        .brand-tagline {
            font-family: var(--font-modern);
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 4px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            margin-bottom: 50px;
            animation: fadeInUp 1s ease-out 0.2s backwards;
        }

        .brand-description {
            font-family: var(--font-modern);
            font-size: 16px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 300;
            animation: fadeInUp 1s ease-out 0.4s backwards;
        }

        /* Right Side - Login Form */
        .login-section {
            flex: 1;
            background: var(--soft-white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
            animation: fadeIn 1s ease-out 0.3s backwards;
        }

        .login-header {
            margin-bottom: 50px;
        }

        .login-title {
            font-family: var(--font-heading);
            font-size: 32px;
            font-weight: 700;
            color: var(--jet-black);
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-family: var(--font-modern);
            font-size: 15px;
            color: var(--gray-medium);
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        /* Alert Styling */
        .alert {
            background: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease-out;
        }

        .alert-danger {
            color: #dc3545;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.08);
            border-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .alert .material-icons {
            font-size: 22px;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 28px;
        }

        .form-label {
            font-family: var(--font-heading);
            font-size: 13px;
            font-weight: 600;
            color: var(--jet-black);
            margin-bottom: 10px;
            display: block;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            background: var(--soft-white);
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 16px 18px;
            font-family: var(--font-modern);
            font-size: 15px;
            color: var(--jet-black);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-control::placeholder {
            color: #bbb;
        }

        .form-control:focus {
            border-color: var(--jet-black);
            box-shadow: 0 0 0 4px rgba(10, 10, 10, 0.06);
        }

        .form-control:hover {
            border-color: #ccc;
        }

        /* Button Styling */
        .btn-login {
            width: 100%;
            background: var(--jet-black);
            color: var(--soft-white);
            border: none;
            border-radius: 8px;
            padding: 18px 24px;
            font-family: var(--font-heading);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 36px;
        }

        .btn-login:hover {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login .material-icons {
            font-size: 20px;
        }

        /* Additional Info */
        .login-footer {
            margin-top: 40px;
            text-align: center;
        }

        .credentials-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        .credentials-info strong {
            color: var(--jet-black);
            font-weight: 600;
        }

        .back-link {
            margin-top: 30px;
            text-align: center;
        }

        .back-link a {
            color: var(--gray-medium);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover {
            color: var(--jet-black);
        }

        .back-link .material-icons {
            font-size: 18px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
            }

            .brand-showcase {
                min-height: 40vh;
                padding: 40px 30px;
            }

            .brand-logo-large {
                font-size: 48px;
                letter-spacing: 12px;
            }

            .brand-content {
                padding: 30px;
            }

            .login-section {
                padding: 40px 30px;
            }

            .login-container {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .brand-logo-large {
                font-size: 36px;
                letter-spacing: 8px;
            }

            .brand-tagline {
                font-size: 11px;
                letter-spacing: 3px;
            }

            .brand-description {
                font-size: 14px;
            }

            .login-title {
                font-size: 26px;
            }

            .login-section {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <!-- Left Side - Brand Showcase -->
        <div class="brand-showcase">
            <div class="brand-content">
                <div class="brand-logo-large">VÉNARO</div>
                <div class="brand-tagline">Admin Control Center</div>
                <div class="brand-description">
                    Manage your luxury eCommerce platform with precision and elegance.
                    Access powerful tools to curate exceptional shopping experiences.
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-section">
            <div class="login-container">
                <div class="login-header">
                    <h1 class="login-title">Welcome Back</h1>
                    <p class="login-subtitle">Sign in to access your admin dashboard</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="material-icons">error_outline</i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="material-icons">check_circle_outline</i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            required
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            placeholder="Enter your email address"
                            autocomplete="email">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            required
                            placeholder="Enter your password"
                            autocomplete="current-password">
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="material-icons">login</i>
                        <span>Sign In</span>
                    </button>
                </form>

                <div class="login-footer">
                    <div class="credentials-info">
                        <strong>Default Credentials</strong><br>
                        Email: <strong>admin@venaro.com</strong><br>
                        Password: <strong>Admin@123</strong>
                    </div>

                    <div class="back-link">
                        <a href="<?php echo SITE_URL; ?>">
                            <i class="material-icons">arrow_back</i>
                            <span>Back to Website</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>