<?php
require_once 'config.php';

$success = '';
$error = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? 'General Inquiry');
    $message = trim($_POST['message'] ?? '');

    // Simple validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO contact_messages (name, email, subject, message)
                VALUES (:name, :email, :subject, :message)
            ");
            $stmt->execute([
                ':name'    => htmlspecialchars($name),
                ':email'   => htmlspecialchars($email),
                ':subject' => htmlspecialchars($subject),
                ':message' => htmlspecialchars($message),
            ]);
            $success = 'Thank you for your message. We will be in touch shortly.';
        } catch (PDOException $e) {
            $error = 'Something went wrong. Please try again later.';
        }
    }
}

$page_title = 'Contact Us';
include 'includes/header.php';
?>

<style>
/* ── Contact Page — Luxury Underline Style ── */
.contact-page-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 100px 40px 120px;
}

.contact-eyebrow {
    font-family: 'Montserrat', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: #888;
    display: block;
    margin-bottom: 24px;
}

.contact-heading {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(36px, 5vw, 64px);
    font-weight: 400;
    color: #111;
    line-height: 1.1;
    letter-spacing: -0.02em;
    margin-bottom: 20px;
}

.contact-subtext {
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #666;
    line-height: 1.8;
    max-width: 360px;
    margin-bottom: 60px;
}

.contact-info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-bottom: 36px;
}

.contact-info-label {
    font-family: 'Montserrat', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #888;
}

.contact-info-value {
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #111;
    text-decoration: none;
    transition: color 0.2s;
}

.contact-info-value:hover {
    color: #000;
}

.contact-social-row {
    display: flex;
    gap: 16px;
    margin-top: 8px;
}

.contact-social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border: 1px solid #ddd;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.contact-social-icon:hover {
    background: #000;
    border-color: #000;
    color: #fff;
}

.contact-divider {
    width: 1px;
    background: #eee;
    margin: 0 60px;
}

/* Underline Form */
.contact-form-wrap {
    flex: 1;
}

.contact-form-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
    font-weight: 400;
    color: #111;
    margin-bottom: 48px;
    letter-spacing: -0.01em;
}

.form-underline-group {
    position: relative;
    margin-bottom: 40px;
}

.form-underline-label {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #888;
    margin-bottom: 10px;
}

.form-underline-input {
    width: 100%;
    border: none;
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #111;
    background: transparent;
    outline: none;
    border-radius: 0;
    transition: border-color 0.3s ease;
}

.form-underline-input:focus {
    border-bottom-color: #000;
}

.form-underline-input::placeholder {
    color: #bbb;
    font-size: 14px;
}

.form-underline-select {
    width: 100%;
    border: none;
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #111;
    background: transparent;
    outline: none;
    border-radius: 0;
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.form-underline-select:focus {
    border-bottom-color: #000;
}

.form-underline-textarea {
    width: 100%;
    border: none;
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #111;
    background: transparent;
    outline: none;
    border-radius: 0;
    resize: none;
    transition: border-color 0.3s ease;
}

.form-underline-textarea:focus {
    border-bottom-color: #000;
}

.btn-contact-luxury {
    background: #000;
    color: #fff;
    border: 1px solid #000;
    padding: 16px 48px;
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.4s ease;
    display: inline-block;
    position: relative;
    overflow: hidden;
}

.btn-contact-luxury:hover {
    background: transparent;
    color: #000;
    transform: translateY(-2px);
}

.contact-success {
    background: #f8f8f8;
    border-left: 2px solid #000;
    padding: 16px 20px;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    color: #111;
    margin-bottom: 32px;
    letter-spacing: 0.3px;
}

.contact-error {
    background: #fff8f8;
    border-left: 2px solid #888;
    padding: 16px 20px;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    color: #555;
    margin-bottom: 32px;
    letter-spacing: 0.3px;
}

@media (max-width: 991px) {
    .contact-page-wrap { padding: 60px 24px 80px; }
    .contact-layout { flex-direction: column; }
    .contact-divider { display: none; }
    .contact-subtext { max-width: 100%; }
}

@media (max-width: 576px) {
    .contact-form-row { flex-direction: column; }
}
</style>

<div class="contact-page-wrap">

    <!-- Section Header -->
    <div style="text-align: center; margin-bottom: 80px; border-bottom: 1px solid #eee; padding-bottom: 60px;">
        <span class="contact-eyebrow">Get in Touch</span>
        <h1 class="contact-heading">We'd Love to Hear<br><em style="font-style:italic;">From You</em></h1>
        <p style="font-family:'Inter',sans-serif; font-size:15px; color:#888; max-width:480px; margin:0 auto; line-height:1.8;">
            Whether it's about your order, our products, or just a hello — we're here for you.
        </p>
    </div>

    <!-- Layout -->
    <div class="d-flex contact-layout" style="align-items: flex-start; gap: 80px;">

        <!-- Left: Contact Info -->
        <div style="min-width: 260px; flex-shrink: 0;">
            <h2 style="font-family:'Montserrat',sans-serif; text-transform: uppercase; letter-spacing: 2px; font-size:22px; font-weight:700; color:#111; margin-bottom:40px;">Contact Information</h2>

            <div class="contact-info-item">
                <span class="contact-info-label">Email</span>
                <a href="mailto:info@venaro.com" class="contact-info-value">info@venaro.com</a>
            </div>

            <div class="contact-info-item">
                <span class="contact-info-label">Phone</span>
                <a href="tel:+919876543210" class="contact-info-value">+91 98765 43210</a>
                <span style="font-family:'Inter',sans-serif; font-size:12px; color:#aaa; margin-top:4px;">Mon – Sat, 10am – 8pm IST</span>
            </div>

            <div class="contact-info-item" style="margin-top: 48px;">
                <span class="contact-info-label">Follow Us</span>
                <div class="contact-social-row" style="margin-top: 12px;">
                    <a href="https://www.facebook.com/profile.php?id=61582406730314" target="_blank" class="contact-social-icon" title="Facebook">
                        <i class="material-icons" style="font-size:18px;">facebook</i>
                    </a>
                    <a href="https://www.instagram.com/venaro_apparel/" target="_blank" class="contact-social-icon" title="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.844.047 1.097.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/></svg>
                    </a>
                    <a href="mailto:info@venaro.com" class="contact-social-icon" title="Email">
                        <i class="material-icons" style="font-size:18px;">alternate_email</i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="contact-divider d-none d-lg-block"></div>

        <!-- Right: Form -->
        <div class="contact-form-wrap flex-grow-1">
            <h2 class="contact-form-title">Send Us a Message</h2>

            <?php if ($success): ?>
                <div class="contact-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="contact-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="d-flex gap-5 contact-form-row">
                    <div class="form-underline-group flex-grow-1">
                        <label class="form-underline-label">Your Name *</label>
                        <input type="text" class="form-underline-input" name="name" placeholder="John Doe" required>
                    </div>
                    <div class="form-underline-group flex-grow-1">
                        <label class="form-underline-label">Email Address *</label>
                        <input type="email" class="form-underline-input" name="email" placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="form-underline-group">
                    <label class="form-underline-label">Subject</label>
                    <select class="form-underline-select" name="subject">
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Order Status">Order Status</option>
                        <option value="Returns & Exchanges">Returns &amp; Exchanges</option>
                        <option value="Product Information">Product Information</option>
                    </select>
                </div>

                <div class="form-underline-group">
                    <label class="form-underline-label">Message *</label>
                    <textarea class="form-underline-textarea" name="message" rows="5" placeholder="Tell us how we can help you..." required></textarea>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn-contact-luxury">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>