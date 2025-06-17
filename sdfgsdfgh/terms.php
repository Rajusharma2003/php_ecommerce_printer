<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get page title
$page_title = "Terms & Conditions";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <div class="bg-light py-5">
        <div class="container">
            <h1 class="display-4">Terms & Conditions</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Terms & Conditions</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Terms Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="mb-4">1. Introduction</h2>
                            <p>Welcome to <?php echo SITE_NAME; ?>. These terms and conditions outline the rules and regulations for the use of our website.</p>
                            <p>By accessing this website, we assume you accept these terms and conditions in full. Do not continue to use <?php echo SITE_NAME; ?> if you do not accept all of the terms and conditions stated on this page.</p>

                            <h2 class="mb-4 mt-5">2. Intellectual Property Rights</h2>
                            <p>Unless otherwise stated, <?php echo SITE_NAME; ?> and/or its licensors own the intellectual property rights for all material on this website. All intellectual property rights are reserved.</p>

                            <h2 class="mb-4 mt-5">3. User Account</h2>
                            <p>If you create an account on the website, you are responsible for maintaining the security of your account and you are fully responsible for all activities that occur under the account.</p>

                            <h2 class="mb-4 mt-5">4. Product Information</h2>
                            <p>We strive to display as accurately as possible the colors and images of our products. We cannot guarantee that your computer monitor's display of any color will be accurate.</p>

                            <h2 class="mb-4 mt-5">5. Pricing and Payment</h2>
                            <p>All prices are in USD and are subject to change without notice. We reserve the right to modify or discontinue any product without notice at any time.</p>

                            <h2 class="mb-4 mt-5">6. Shipping and Delivery</h2>
                            <p>Shipping times may vary depending on the shipping method selected and the destination. We are not responsible for any delays in delivery due to circumstances beyond our control.</p>

                            <h2 class="mb-4 mt-5">7. Returns and Refunds</h2>
                            <p>We accept returns within 30 days of delivery. Items must be unused and in the same condition that you received them. To complete your return, we require a receipt or proof of purchase.</p>

                            <h2 class="mb-4 mt-5">8. Privacy Policy</h2>
                            <p>Your use of this website is also governed by our Privacy Policy. Please review our Privacy Policy, which also governs the site and informs users of our data collection practices.</p>

                            <h2 class="mb-4 mt-5">9. Limitation of Liability</h2>
                            <p>In no event shall <?php echo SITE_NAME; ?>, nor any of its officers, directors, and employees, be liable to you for anything arising out of or in any way connected with your use of this website.</p>

                            <h2 class="mb-4 mt-5">10. Changes to Terms</h2>
                            <p>We reserve the right to modify these terms at any time. We do so by posting modified terms on this website. Your continued use of the website means you accept any changes.</p>

                            <h2 class="mb-4 mt-5">11. Contact Information</h2>
                            <p>Questions about the Terms and Conditions should be sent to us at info@<?php echo strtolower(SITE_NAME); ?>.com</p>

                            <div class="mt-5">
                                <p class="text-muted">Last updated: <?php echo date('F d, Y'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 