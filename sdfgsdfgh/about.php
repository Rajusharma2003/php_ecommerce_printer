<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get page title
$page_title = "About Us";
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
            <h1 class="display-4">About Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About Us</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Our Story Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="assets/images/about/store.jpg" alt="Our Store" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Our Story</h2>
                    <p class="lead">Welcome to ShopNow, your one-stop destination for quality products and exceptional shopping experience.</p>
                    <p>Founded in 2024, we started with a simple mission: to provide our customers with the best products at competitive prices while ensuring outstanding customer service.</p>
                    <p>Over the years, we have grown from a small local store to a trusted online retailer, serving customers nationwide. Our commitment to quality, authenticity, and customer satisfaction remains at the heart of everything we do.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Our Values</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class='bx bxs-badge-check display-4 text-primary mb-3'></i>
                            <h4>Quality First</h4>
                            <p class="card-text">We carefully select each product to ensure it meets our high standards of quality and reliability.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class='bx bxs-user-voice display-4 text-primary mb-3'></i>
                            <h4>Customer Focus</h4>
                            <p class="card-text">Your satisfaction is our priority. We're always here to help and listen to your feedback.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class='bx bxs-bulb display-4 text-primary mb-3'></i>
                            <h4>Innovation</h4>
                            <p class="card-text">We continuously improve our services and embrace new technologies to better serve you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Team</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="assets/images/team/team1.jpg" class="card-img-top" alt="Team Member">
                        <div class="card-body text-center">
                            <h5 class="card-title">John Doe</h5>
                            <p class="text-muted">Founder & CEO</p>
                            <div class="social-links">
                                <a href="#" class="text-dark me-2"><i class='bx bxl-facebook'></i></a>
                                <a href="#" class="text-dark me-2"><i class='bx bxl-twitter'></i></a>
                                <a href="#" class="text-dark"><i class='bx bxl-linkedin'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="assets/images/team/team2.jpg" class="card-img-top" alt="Team Member">
                        <div class="card-body text-center">
                            <h5 class="card-title">Jane Smith</h5>
                            <p class="text-muted">Operations Manager</p>
                            <div class="social-links">
                                <a href="#" class="text-dark me-2"><i class='bx bxl-facebook'></i></a>
                                <a href="#" class="text-dark me-2"><i class='bx bxl-twitter'></i></a>
                                <a href="#" class="text-dark"><i class='bx bxl-linkedin'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="assets/images/team/team3.jpg" class="card-img-top" alt="Team Member">
                        <div class="card-body text-center">
                            <h5 class="card-title">Mike Johnson</h5>
                            <p class="text-muted">Customer Service Lead</p>
                            <div class="social-links">
                                <a href="#" class="text-dark me-2"><i class='bx bxl-facebook'></i></a>
                                <a href="#" class="text-dark me-2"><i class='bx bxl-twitter'></i></a>
                                <a href="#" class="text-dark"><i class='bx bxl-linkedin'></i></a>
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