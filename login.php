<?php
require_once 'config.php';

// Redirect if already logged in
if (is_logged_in()) {
    redirect(SITE_URL . '/profile.php');
}

// Store the previous page URL in session if not a POST request and not already set
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_SESSION['redirect_after_login'])) {
    if (isset($_GET['redirect'])) {
        $_SESSION['redirect_after_login'] = $_GET['redirect'];
    } elseif (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'login.php') === false && strpos($_SERVER['HTTP_REFERER'], 'register.php') === false && strpos($_SERVER['HTTP_REFERER'], 'forgot-password.php') === false) {
        $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'];
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        // Check for failed login attempts
        $lockout_key = 'login_attempts_' . md5($email);
        $attempts = $_SESSION[$lockout_key] ?? 0;
        $lockout_time_key = 'lockout_time_' . md5($email);
        $lockout_time = $_SESSION[$lockout_time_key] ?? 0;

        if ($attempts >= MAX_LOGIN_ATTEMPTS && time() < $lockout_time) {
            $remaining = ceil(($lockout_time - time()) / 60);
            $error = "Account locked due to too many failed attempts. Please try again in {$remaining} minutes.";
        } else {
            // Reset attempts if lockout period has passed
            if (time() >= $lockout_time) {
                $_SESSION[$lockout_key] = 0;
            }

            // Fetch user
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'Active'");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Successful login
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];

                // Reset failed attempts
                unset($_SESSION[$lockout_key]);
                unset($_SESSION[$lockout_time_key]);

                // Update last login
                $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                $stmt->execute([$user['user_id']]);

                // Merge guest cart with user cart
                $session_id = get_session_id();
                $stmt = $pdo->prepare("
                    UPDATE cart SET user_id = ?, session_id = NULL 
                    WHERE session_id = ?
                ");
                $stmt->execute([$user['user_id'], $session_id]);

                // Set remember me cookie
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
                }

                // Redirect to intended page or profile
                $redirect_url = $_SESSION['redirect_after_login'] ?? SITE_URL . '/profile.php';
                unset($_SESSION['redirect_after_login']);
                redirect($redirect_url);
            } else {
                // Failed login
                $_SESSION[$lockout_key] = ($attempts + 1);
                if ($_SESSION[$lockout_key] >= MAX_LOGIN_ATTEMPTS) {
                    $_SESSION[$lockout_time_key] = time() + LOGIN_LOCKOUT_TIME;
                }
                $error = 'Invalid email or password.';
            }
        }
    }
}

$page_title = 'Login - VÉNARO';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Main Site CSS for footer -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">

    <style>
        :root {
            --jet-black: #0a0a0a;
            --deep-black: #111111;
            --charcoal: #1a1a1a;
            --soft-white: #ffffff;
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
            background: #f5f5f5;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Split Screen Layout */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Left Side - Brand Showcase */
        .brand-side {
            flex: 1;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            overflow: hidden;
        }

        .brand-side::before {
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
            max-width: 500px;
        }

        .brand-logo-auth {
            font-family: var(--font-luxury);
            font-size: 64px;
            font-weight: 800;
            letter-spacing: 14px;
            color: var(--soft-white);
            margin-bottom: 30px;
            text-transform: uppercase;
            animation: fadeInUp 0.8s ease-out;
        }

        .brand-tagline-auth {
            font-family: var(--font-modern);
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 3px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .brand-description-auth {
            font-family: var(--font-modern);
            font-size: 15px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 300;
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        /* Right Side - Form */
        .form-side {
            flex: 1;
            background: var(--soft-white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.8s ease-out 0.3s backwards;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-title {
            font-family: var(--font-heading);
            font-size: 28px;
            font-weight: 700;
            color: var(--jet-black);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            font-family: var(--font-modern);
            font-size: 14px;
            color: #666;
            font-weight: 400;
        }

        .form-subtitle a {
            color: var(--jet-black);
            text-decoration: underline;
            font-weight: 500;
        }

        /* Alert */
        .alert-custom {
            background: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 24px;
            color: #dc3545;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        /* Form Styling */
        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-label-custom {
            display: none;
        }

        .form-control-custom {
            width: 100%;
            background: var(--soft-white);
            border: 0;
            border-bottom: 2px solid #e0e0e0;
            border-radius: 0;
            padding: 14px 0;
            font-family: var(--font-modern);
            font-size: 15px;
            color: var(--jet-black);
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control-custom::placeholder {
            color: #999;
        }

        .form-control-custom:focus {
            border-bottom-color: var(--jet-black);
        }

        /* Button */
        .btn-auth {
            width: 100%;
            background: var(--jet-black);
            color: var(--soft-white);
            border: none;
            border-radius: 8px;
            padding: 16px 24px;
            font-family: var(--font-heading);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .btn-auth:hover {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Links */
        .auth-link {
            color: #666;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .auth-link:hover {
            color: var(--jet-black);
            text-decoration: underline;
        }

        .auth-footer {
            margin-top: 30px;
            text-align: center;
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

        /* Responsive */
        @media (max-width: 992px) {
            .auth-wrapper {
                flex-direction: column;
            }

            .brand-side {
                min-height: 35vh;
                padding: 40px 30px;
            }

            .brand-logo-auth {
                font-size: 42px;
                letter-spacing: 10px;
            }

            .form-side {
                padding: 40px 30px;
            }
        }

        @media (max-width: 576px) {
            .brand-logo-auth {
                font-size: 32px;
                letter-spacing: 8px;
            }

            .brand-tagline-auth {
                font-size: 11px;
                letter-spacing: 2px;
            }

            .form-side {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <!-- Left Side - Brand -->
        <div class="brand-side">
            <div class="brand-content">
                <div class="brand-logo-auth">VÉNARO</div>
                <div class="brand-tagline-auth">Premium Fashion</div>
                <div class="brand-description-auth">
                    Experience luxury redefined. Discover exceptional quality,
                    timeless design, and unparalleled craftsmanship in every piece.
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="form-side">
            <div class="form-container">
                <div class="form-header">
                    <h1 class="form-title">Welcome Back</h1>
                    <p class="form-subtitle">
                        Don't have an account? <a href="register.php">Create a new account now</a>
                    </p>
                </div>

                <?php if ($error): ?>
                    <div class="alert-custom">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group-custom">
                        <label for="email" class="form-label-custom">Email</label>
                        <input
                            type="email"
                            class="form-control-custom"
                            id="email"
                            name="email"
                            placeholder="Email address"
                            required
                            value="<?php echo htmlspecialchars($email ?? ''); ?>"
                            autocomplete="email">
                    </div>

                    <div class="form-group-custom">
                        <label for="password" class="form-label-custom">Password</label>
                        <input
                            type="password"
                            class="form-control-custom"
                            id="password"
                            name="password"
                            placeholder="Password"
                            required
                            autocomplete="off">
                    </div>

                    <div class="text-center mt-3">
                        <a href="forgot-password.php" class="auth-link">Forgot password? Click here</a>
                    </div>

                    <button type="submit" class="btn-auth">
                        Login Now
                    </button>

                    <div class="auth-footer">
                        <a href="<?php echo SITE_URL; ?>" class="auth-link">
                            <i class="material-icons" style="vertical-align: middle; font-size: 16px;">arrow_back</i>
                            Back to Website
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php
// Minimal footer with social links
echo '<footer style="background-color: #0a0a0a; padding: 32px 0; margin-top: 0;">';
echo '<div class="container">';
echo '<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">';
echo '<p style="color: rgba(255,255,255,0.4); font-size: 12px; margin: 0; font-family: Montserrat, sans-serif;">&copy; ' . date('Y') . ' V&Eacute;NARO. All rights reserved.</p>';
echo '<div class="d-flex gap-3">';
echo '<a href="https://www.facebook.com/profile.php?id=61582406730314" target="_blank" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border:1px solid rgba(255,255,255,0.2);border-radius:50%;color:rgba(255,255,255,0.6);text-decoration:none;transition:all 0.3s;" onmouseover="this.style.borderColor=\'#fff\';this.style.color=\'#fff\'" onmouseout="this.style.borderColor=\'rgba(255,255,255,0.2)\';this.style.color=\'rgba(255,255,255,0.6)\'">';
echo '<i class="material-icons" style="font-size:18px;">facebook</i></a>';
echo '<a href="https://www.instagram.com/venaro_apparel/" target="_blank" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border:1px solid rgba(255,255,255,0.2);border-radius:50%;color:rgba(255,255,255,0.6);text-decoration:none;transition:all 0.3s;" onmouseover="this.style.borderColor=\'#fff\';this.style.color=\'#fff\'" onmouseout="this.style.borderColor=\'rgba(255,255,255,0.2)\';this.style.color=\'rgba(255,255,255,0.6)\'">';
echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.844.047 1.097.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/></svg>';
echo '</a>';
echo '</div></div></div></footer>';
?>

</html>