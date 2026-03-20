<?php
require_once 'config.php';
$page_title = 'Shipping & Returns';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-center mb-5" style="font-family: var(--font-brand); font-weight: 700;">Shipping & Returns</h1>
            
            <div class="mb-5">
                <h3 class="mb-3" style="font-family: var(--font-brand);">Shipping Policy</h3>
                <p class="text-muted mb-3">
                    We offer complimentary standard shipping on all orders over $150. For orders under $150, a flat rate shipping fee of $15 applies.
                </p>
                <h5 class="fw-bold fs-6 mt-4">Processing Time</h5>
                <p class="text-muted">
                    Please allow 1-2 business days for your order to be processed and packed at our warehouse. Orders placed on weekends or holidays will be processed on the next business day.
                </p>
                 <h5 class="fw-bold fs-6 mt-4">Delivery Estimates</h5>
                <ul class="text-muted small mt-2">
                    <li>Standard Shipping: 5-7 business days</li>
                    <li>Express Shipping: 2-3 business days</li>
                    <li>International: 7-14 business days</li>
                </ul>
            </div>
            
            <hr class="my-5">
            
            <div class="mb-5">
                <h3 class="mb-3" style="font-family: var(--font-brand);">Return Policy</h3>
                <p class="text-muted mb-3">
                    We want you to be completely satisfied with your VÉNARO purchase. If for any reason you are not, we strictly accept returns of unworn, unwashed, and undamaged items with original tags within <strong>14 days of delivery</strong>.
                </p>
                <p class="text-muted">
                    To initiate a return, please visit our <a href="contact.php" class="text-dark text-decoration-underline">Contact</a> page and select "Returns & Exchanges" as the subject.
                </p>
            </div>
            
             <hr class="my-5">
             
             <div class="bg-light p-4 text-center">
                 <h5 class="mb-3" style="font-family: var(--font-brand);">Need Help?</h5>
                 <p class="text-muted small mb-3">Our customer support team is available Monday through Friday, 9am to 6pm IST.</p>
                 <a href="contact.php" class="btn btn-outline-dark rounded-0 px-4">CONTACT US</a>
             </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
