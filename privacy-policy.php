<?php
require_once 'config.php';
$page_title = 'Privacy Policy';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-center mb-5" style="font-family: var(--font-brand); font-weight: 700;">Privacy Policy</h1>
            
            <div class="text-muted custom-content" style="line-height: 1.8;">
                <p class="mb-4">Last Updated: <?php echo date('F Y'); ?></p>

                <h4 class="text-dark mt-5 mb-3" style="font-family: var(--font-brand);">1. Introduction</h4>
                <p>Welcome to VÉNARO. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.</p>

                <h4 class="text-dark mt-5 mb-3" style="font-family: var(--font-brand);">2. Data We Collect</h4>
                <p>We may collect, use, store and transfer different kinds of personal data about you which we have grouped together follows:</p>
                <ul>
                    <li><strong>Identity Data:</strong> includes first name, last name, username or similar identifier.</li>
                    <li><strong>Contact Data:</strong> includes billing address, delivery address, email address and telephone numbers.</li>
                    <li><strong>Financial Data:</strong> includes payment card details (processed securely by our third-party payment providers).</li>
                    <li><strong>Transaction Data:</strong> includes details about payments to and from you and other details of products you have purchased from us.</li>
                </ul>

                <h4 class="text-dark mt-5 mb-3" style="font-family: var(--font-brand);">3. How We Use Your Data</h4>
                <p>We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:</p>
                <ul>
                    <li>Where we need to perform the contract we are about to enter into or have entered into with you.</li>
                    <li>Where it is necessary for our legitimate interests (or those of a third party) and your interests and fundamental rights do not override those interests.</li>
                    <li>Where we need to comply with a legal or regulatory obligation.</li>
                </ul>

                <h4 class="text-dark mt-5 mb-3" style="font-family: var(--font-brand);">4. Data Security</h4>
                <p>We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed. In addition, we limit access to your personal data to those employees, agents, contractors and other third parties who have a business need to know.</p>

                <h4 class="text-dark mt-5 mb-3" style="font-family: var(--font-brand);">5. Contact Us</h4>
                <p>If you have any questions about this privacy policy or our privacy practices, please contact us at: <a href="mailto:privacy@venaro.com" class="text-dark">privacy@venaro.com</a>.</p>
            </div>
            
            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-outline-dark rounded-0 px-4">RETURN TO HOME</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
