<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get page title
$page_title = "Frequently Asked Questions";
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
            <h1 class="display-4">Frequently Asked Questions</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- FAQ Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <!-- Ordering -->
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How do I place an order?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>To place an order:</p>
                                    <ol>
                                        <li>Browse our products and add items to your cart</li>
                                        <li>Click on the cart icon to review your items</li>
                                        <li>Click "Proceed to Checkout"</li>
                                        <li>Enter your shipping and billing information</li>
                                        <li>Review your order and click "Place Order"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What payment methods do you accept?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>We accept the following payment methods:</p>
                                    <ul>
                                        <li>Credit/Debit Cards (Visa, MasterCard, American Express)</li>
                                        <li>PayPal</li>
                                        <li>Apple Pay</li>
                                        <li>Google Pay</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping -->
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What are your shipping options?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>We offer the following shipping options:</p>
                                    <ul>
                                        <li>Standard Shipping (3-5 business days)</li>
                                        <li>Express Shipping (1-2 business days)</li>
                                        <li>Free Shipping on orders over $50</li>
                                    </ul>
                                    <p>International shipping is available to select countries.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Returns -->
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    What is your return policy?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Our return policy:</p>
                                    <ul>
                                        <li>30-day return window from delivery date</li>
                                        <li>Items must be unused and in original packaging</li>
                                        <li>Return shipping is free for defective items</li>
                                        <li>Refunds are processed within 5-7 business days</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Account -->
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    How do I create an account?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>To create an account:</p>
                                    <ol>
                                        <li>Click on the "Account" icon in the top right</li>
                                        <li>Select "Create Account"</li>
                                        <li>Fill in your details (name, email, password)</li>
                                        <li>Click "Create Account"</li>
                                        <li>Verify your email address</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    How do I know if a product is in stock?
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Product availability is shown on each product page:</p>
                                    <ul>
                                        <li>"In Stock" - Available for immediate shipping</li>
                                        <li>"Low Stock" - Limited quantity available</li>
                                        <li>"Out of Stock" - Currently unavailable</li>
                                        <li>"Back in Stock Soon" - Expected restock date shown</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Support -->
                    <div class="text-center mt-5">
                        <h3>Still have questions?</h3>
                        <p class="mb-4">Our customer support team is here to help!</p>
                        <a href="contact.php" class="btn btn-primary">Contact Us</a>
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