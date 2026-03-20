<?php

/**
 * VÉNARO eCommerce Platform
 * Configuration File
 * Version: 1.0
 */

// Error Reporting (Development mode)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Enable PHP Output Compression
if (!ob_start("ob_gzhandler")) ob_start();

// Database Configuration
// Prevent direct access
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], basename(__FILE__)) !== false) {
    die('Forbidden');
}

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'venaro_db');

// Error Reporting (Set to 0 for production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Hide errors from users
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Site Configuration
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$site_url = $protocol . '://' . $host . '/new-venaro';
define('SITE_URL', $site_url);
define('SITE_NAME', 'VÉNARO');
define('SITE_TAGLINE', 'Redefining Modern Fashion');

// Directory Paths
define('ROOT_PATH', dirname(__FILE__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// URL Paths
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', SITE_URL . '/uploads');

// Upload Directories
define('PRODUCT_IMAGES_PATH', UPLOADS_PATH . '/products');
define('PROFILE_IMAGES_PATH', UPLOADS_PATH . '/profiles');
define('CATEGORY_IMAGES_PATH', UPLOADS_PATH . '/categories');

// Session Configuration (must be set before session_start)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 in production with HTTPS
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.gc_maxlifetime', 1800); // 30 minutes
    session_start();
}

// Security Configuration
define('BCRYPT_COST', 12);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes in seconds

// Pagination
define('PRODUCTS_PER_PAGE', 24);
define('ORDERS_PER_PAGE', 50);

// Business Rules
define('FREE_SHIPPING_THRESHOLD', 999);
define('DEFAULT_TAX_RATE', 12);
define('COD_MAX_AMOUNT', 5000);
define('COD_CHARGE', 50);

// Email Configuration (SMTP)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_FROM_EMAIL', 'noreply@venaro.com');
define('SMTP_FROM_NAME', 'VÉNARO');

// Payment Gateway Configuration
define('PAYMENT_GATEWAY', 'razorpay'); // razorpay, stripe, paypal
define('RAZORPAY_KEY_ID', 'your_razorpay_key_id');
define('RAZORPAY_KEY_SECRET', 'your_razorpay_key_secret');
define('RAZORPAY_TEST_MODE', true);

// Image Upload Settings
define('MAX_IMAGE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('IMAGE_QUALITY', 85);

// Currency
define('CURRENCY', 'INR');
define('CURRENCY_SYMBOL', 'Rs. ');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Database Connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true, // Performance: Enable persistent connection pooling
        ]
    );
} catch (PDOException $e) {
    // Better error message
    $error_msg = "<h2>Database Connection Error</h2>";
    $error_msg .= "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";

    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        $error_msg .= "<h3>Database Not Found</h3>";
        $error_msg .= "<p>The database '<strong>" . DB_NAME . "</strong>' does not exist.</p>";
        $error_msg .= "<h4>To fix this:</h4>";
        $error_msg .= "<ol>";
        $error_msg .= "<li>Open phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
        $error_msg .= "<li>Click the 'Import' tab</li>";
        $error_msg .= "<li>Choose file: <code>c:\\xampp\\htdocs\\new-venaro\\database\\venaro_schema.sql</code></li>";
        $error_msg .= "<li>Click 'Go' to import</li>";
        $error_msg .= "<li>Refresh this page</li>";
        $error_msg .= "</ol>";
        $error_msg .= "<p><a href='db-test.php'>Or run the database diagnostic tool</a></p>";
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        $error_msg .= "<h3>Access Denied</h3>";
        $error_msg .= "<p>Cannot connect to MySQL with the provided credentials.</p>";
        $error_msg .= "<p>Check your database credentials in <code>config.php</code>:</p>";
        $error_msg .= "<ul>";
        $error_msg .= "<li>DB_HOST: " . DB_HOST . "</li>";
        $error_msg .= "<li>DB_USER: " . DB_USER . "</li>";
        $error_msg .= "<li>DB_NAME: " . DB_NAME . "</li>";
        $error_msg .= "</ul>";
    }

    die($error_msg);
}

// Helper Functions
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Global Contact Settings
define('CONTACT_EMAIL', 'info@venaro.com');
define('CONTACT_PHONE', '+91 98765 43210');
define('INSTAGRAM_URL', 'https://www.instagram.com/venaro_apparel/');

function generate_csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function redirect($url)
{
    header("Location: " . $url);
    exit();
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function is_admin_logged_in()
{
    return isset($_SESSION['admin_id']);
}

function get_current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

function get_current_admin_id()
{
    return $_SESSION['admin_id'] ?? null;
}

function format_price($amount)
{
    return CURRENCY_SYMBOL . number_format($amount, 2);
}

function calculate_discount_percentage($regular_price, $sale_price)
{
    if ($regular_price <= 0) return 0;
    return round((($regular_price - $sale_price) / $regular_price) * 100);
}

function generate_order_number()
{
    return 'VEN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

function generate_ticket_number()
{
    return 'TKT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

function get_session_id()
{
    if (!isset($_SESSION['guest_session_id'])) {
        $_SESSION['guest_session_id'] = session_id();
    }
    return $_SESSION['guest_session_id'];
}

// Auto-load classes (simple autoloader)
spl_autoload_register(function ($class) {
    $file = INCLUDES_PATH . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
