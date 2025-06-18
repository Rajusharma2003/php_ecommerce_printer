<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get featured products (using latest products instead)
$sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 8";
$featured_products = $conn->query($sql);

// Get categories
$sql = "SELECT * FROM categories LIMIT 6";
$categories = $conn->query($sql);

// Get latest products
$sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 4";
$latest_products = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern E-Commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
        }
        
        .carousel-item {
            height: 500px;
        }
        
        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
        
        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .carousel-caption {
        bottom: 10.25rem;
         }
        
        .category-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            transition: transform 0.3s;
            height: 200px;
            background: #f8f9fa;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .category-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e9ecef;
        }
        
        .category-placeholder i {
            font-size: 48px;
            color: #6c757d;
        }
        
        .category-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            padding: 20px;
            color: white;
        }
        
        .category-overlay h4 {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .product-card {
            border: none;
            transition: transform 0.3s;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-card img {
            height: 200px;
            object-fit: cover;
        }
        
        .features-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }
        
        .feature-box {
            text-align: center;
            padding: 30px;
        }
        
        .feature-box i {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .newsletter-section {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0;
        }
        .navbar {
            padding: 1rem 0;
        }
        .navbar-brand img {
            max-height: 40px;
            width: auto;
        }
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            color: #333 !important;
        }
        .nav-link:hover {
            color: #0d6efd !important;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .dropdown-item {
            padding: 0.5rem 1.5rem;
        }
        .dropdown-item i {
            margin-right: 0.5rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
        }
        .btn i {
            margin-right: 0.25rem;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        @media (max-width: 991.98px) {
            .navbar-collapse {
                padding: 1rem 0;
            }
            .d-flex {
                flex-direction: column;
                gap: 0.5rem !important;
            }
            .btn {
                width: 100%;
            }
        }
        .product-spotlight-card {
            position: relative;
            overflow: hidden;
        }

        .product-spotlight-card img {
            width: 100%;
            height: 300px; /* Adjust as needed */
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .product-spotlight-card:hover img {
            transform: scale(1.05);
        }

        .product-spotlight-card .card-img-overlay {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            padding: 1.5rem;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .product-spotlight-card:hover .card-img-overlay {
            opacity: 1;
        }

        .product-spotlight-card .card-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .product-spotlight-card .card-text {
            font-size: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://www.printersindia.in/wp-content/uploads/2020/02/banner5-1.jpg" class="d-block w-100" alt="Shop Now" style="height: 90vh; object-fit: contain;">
                <div class="carousel-caption d-none">
                    <h1>Welcome to ShopNow</h1>
                    <p>Discover amazing products at great prices</p>
                    <a href="shop.php" class="btn btn-primary btn-lg">Shop Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://www.printersindia.in/wp-content/uploads/2020/02/banner3-1-1-1.png" class="d-block w-100" alt="Special Offers" style="height: 90vh; object-fit: contain;">
                <div class="carousel-caption d-none">
                    <h1>Special Offers</h1>
                    <p>Check out our latest deals and discounts</p>
                    <a href="shop.php" class="btn btn-primary btn-lg">View Offers</a>
                </div>
            </div>

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Shop by Category Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Shop by Category</h2>
            <div class="owl-carousel owl-theme">
                <?php
                // Get all active categories
                $sql = "SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC";
                $categories = $conn->query($sql);
                
                if ($categories && $categories->num_rows > 0) {
                    while($category = $categories->fetch_assoc()) {
                        ?>
                        <div class="item">
                            <a href="category.php?id=<?php echo $category['category_id']; ?>" class="text-decoration-none">
                                <div class="card h-100 category-card">
                                    <?php if($category['image_url']): ?>
                                        <img src="<?php echo getImageUrl($category['image_url']); ?>" 
                                             class="card-img-top category-image" 
                                             alt="<?php echo htmlspecialchars($category['name']); ?>"
                                             onerror="this.src='assets/images/placeholder.jpg'">
                                    <?php else: ?>
                                        <div class="category-image-placeholder">
                                            <i class='bx bx-category'></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-dark"><?php echo htmlspecialchars($category['name']); ?></h5>
                                        <?php if($category['description']): ?>
                                            <p class="card-text text-muted"><?php echo htmlspecialchars($category['description']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12 text-center"><p class="text-muted">No categories found</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

     <!-- Our Story Section -->
     <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="https://img.freepik.com/premium-photo/laser-printer_101266-638.jpg?ga=GA1.1.60748560.1748862681&semt=ais_hybrid&w=740" alt="Our Store" class="img-fluid rounded shadow">
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

    <!-- New Promotional Banner Section -->
    <section class="py-5 bg-dark text-white text-center" style="background-image: url('https://prmprinterparts.com/cdn/shop/files/maxify-gx4050-lifestyle-front-tray-4x3_0af8e9cf0aba47eba8cb1164fffaaa33.jpg?v=1712235002&width=1500'); background-size: cover; background-position: center; filter: brightness(0.7);">
        <div class="container py-5">
            <h2 class="display-4 mb-3">Summer Collection Sale!</h2>
            <p class="lead mb-4">Discover the latest trends and save up to 50% on selected items.</p>
            <a href="shop.php" class="btn btn-light btn-lg">Shop Now</a>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Us</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class='bx bxs-truck text-primary' style="font-size: 48px;"></i>
                        </div>
                        <h4>Easy Shipping</h4>
                        <p class="text-muted">Easy shipping on all orders</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class='bx bx-refund text-primary' style="font-size: 48px;"></i>
                        </div>
                        <h4>Easy Returns</h4>
                        <p class="text-muted">30 days return policy</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class='bx bx-lock-alt text-primary' style="font-size: 48px;"></i>
                        </div>
                        <h4>Secure Payment</h4>
                        <p class="text-muted">100% secure payment</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class='bx bx-support text-primary' style="font-size: 48px;"></i>
                        </div>
                        <h4>24/7 Support</h4>
                        <p class="text-muted">Dedicated support team</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Brands Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Our Brands</h2>
            <div class="row g-4 align-items-center justify-content-center">
                <div class="col-6 col-md-2">
                    <img src="assets/images/brands/brand1.png" alt="Brand 1" class="img-fluid">
                </div>
                <div class="col-6 col-md-2">
                    <img src="assets/images/brands/brand2.png" alt="Brand 2" class="img-fluid">
                </div>
                <div class="col-6 col-md-2">
                    <img src="assets/images/brands/brand3.png" alt="Brand 3" class="img-fluid">
                </div>
                <div class="col-6 col-md-2">
                    <img src="assets/images/brands/brand4.png" alt="Brand 4" class="img-fluid">
                </div>
                <div class="col-6 col-md-2">
                    <img src="assets/images/brands/brand5.png" alt="Brand 5" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Products Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Latest Products</h2>
            <div class="row g-4">
                <?php
                // Get latest products
                $sql = "SELECT p.*, c.name as category_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.category_id 
                        WHERE p.status = 'active' 
                        ORDER BY p.created_at DESC 
                        LIMIT 4";
                $latest_products = $conn->query($sql);
                
                if ($latest_products && $latest_products->num_rows > 0) {
                    while($product = $latest_products->fetch_assoc()) {
                        $has_discount = isset($product['discount_price']) && 
                                      $product['discount_price'] !== null && 
                                      $product['discount_price'] > 0 && 
                                      $product['discount_price'] < $product['price'];
                        ?>
                        <div class="col-6 col-md-3">
                            <div class="card h-100 product-card">
                                <div class="position-relative">
                                    <?php if($product['image_url']): ?>
                                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                                            <img src="<?php echo getImageUrl($product['image_url']); ?>" 
                                                 class="card-img-top product-image" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        </a>
                                    <?php else: ?>
                                        <div class="product-image-placeholder">
                                            <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                                                <i class='bx bx-image'></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($has_discount): 
                                        $discount_percentage = round((($product['price'] - $product['discount_price']) / $product['price']) * 100);
                                    ?>
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                            <?php echo $discount_percentage; ?>% OFF
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php if($has_discount): ?>
                                                <span class="text-decoration-line-through text-muted"><?php echo formatPrice($product['price']); ?></span>
                                                <span class="text-danger fw-bold"><?php echo formatPrice($product['discount_price']); ?></span>
                                            <?php else: ?>
                                                <span class="fw-bold"><?php echo formatPrice($product['price']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-sm">
                                            <i class='bx bx-cart-add'></i> Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="shop.php" class="btn btn-outline-primary">View All Products</a>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Featured Products</h2>
            <div class="row g-4">
                <?php
                // Get featured products
                $sql = "SELECT p.*, c.name as category_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.category_id 
                        WHERE p.status = 'active' 
                        ORDER BY p.created_at DESC 
                        LIMIT 8";
                $featured_products = $conn->query($sql);
                
                if ($featured_products && $featured_products->num_rows > 0) {
                    while($product = $featured_products->fetch_assoc()) {
                        ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 product-card">
                                <div class="position-relative">
                                    <?php if($product['image_url']): ?>
                                        <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                                            <img src="<?php echo getImageUrl($product['image_url']); ?>" 
                                                 class="card-img-top product-image" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        </a>
                                    <?php else: ?>
                                        <div class="product-image-placeholder">
                                            <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                                                <i class='bx bx-image'></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <?php 
                                    // Calculate discount percentage if discount price exists and is less than regular price
                                    $has_discount = isset($product['discount_price']) && 
                                                  $product['discount_price'] !== null && 
                                                  $product['discount_price'] > 0 && 
                                                  $product['discount_price'] < $product['price'];
                                    
                                    if($has_discount): 
                                        $discount_percentage = round((($product['price'] - $product['discount_price']) / $product['price']) * 100);
                                    ?>
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                            <?php echo $discount_percentage; ?>% OFF
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php if($has_discount): ?>
                                                <span class="text-decoration-line-through text-muted"><?php echo formatPrice($product['price']); ?></span>
                                                <span class="text-danger fw-bold"><?php echo formatPrice($product['discount_price']); ?></span>
                                            <?php else: ?>
                                                <span class="fw-bold"><?php echo formatPrice($product['price']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary btn-sm">
                                            <i class='bx bx-cart-add'></i> Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12 text-center"><p class="text-muted">No featured products found</p></div>';
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="shop.php" class="btn btn-outline-primary">View All Products</a>
            </div>
        </div>
    </section>

    <!-- New Modern Products Spotlight Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-5">Our Latest Collection</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 ">
                        <img src="https://img.freepik.com/free-photo/woman-work-office-using-printer_23-2149456927.jpg?ga=GA1.1.60748560.1748862681&semt=ais_hybrid&w=740" class="card-img-top" alt="Product 1">
                        <div class="card-img-overlay d-flex flex-column justify-content-end text-white p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <h3 class="card-title">Elegant Dresses</h3>
                            <p class="card-text">Discover our new line of elegant dresses for every occasion.</p>
                            <a href="shop.php" class="btn btn-light btn-sm">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row g-4 h-100">
                        <div class="col-md-12">
                            <div class="card border-0 shadow-sm h-100 ">
                                <img src="https://img.freepik.com/premium-photo/bussiness-man-hand-press-button-panel-printer-printer-scanner-laser-office-copy-machine-supplies-start-concept_34936-1822.jpg?ga=GA1.1.60748560.1748862681&semt=ais_hybrid&w=740" class="card-img-top" alt="Product 2">
                                <div class="card-img-overlay d-flex flex-column justify-content-end text-white p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                    <h3 class="card-title">Casual Wear</h3>
                                    <p class="card-text">Comfortable and stylish options for your everyday look.</p>
                                    <a href="shop.php" class="btn btn-light btn-sm">Explore More</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card border-0 shadow-sm h-100 ">
                                <img src="https://img.freepik.com/premium-photo/close-up-hand-technician-open-cover-photocopier_101448-2245.jpg?ga=GA1.1.60748560.1748862681&semt=ais_hybrid&w=740" class="card-img-top" alt="Product 3">
                                <div class="card-img-overlay d-flex flex-column justify-content-end text-white p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                    <h3 class="card-title">Accessories</h3>
                                    <p class="card-text">Complete your outfit with our exquisite range of accessories.</p>
                                    <a href="shop.php" class="btn btn-light btn-sm">View Collection</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- New Value Proposition Section -->
     <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="p-4 border rounded shadow-sm h-100">
                        <i class='bx bx-package display-4 text-primary mb-3'></i>
                        <h3>Fast & Reliable Delivery</h3>
                        <p class="text-muted">Experience swift and secure shipping directly to your doorstep.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 border rounded shadow-sm h-100">
                        <i class='bx bx-badge-check display-4 text-success mb-3'></i>
                        <h3>Quality Guaranteed</h3>
                        <p class="text-muted">We source only the finest products to ensure your satisfaction.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 border rounded shadow-sm h-100">
                        <i class='bx bx-dollar-circle display-4 text-info mb-3'></i>
                        <h3>Best Prices</h3>
                        <p class="text-muted">Enjoy competitive pricing without compromising on quality.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Testimonials Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">What Our Customers Say</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                            </div>
                            <p class="card-text">“Fast delivery and great quality parts. My printer is working like new again!”</p>
                            <div class="mt-3">
                                <h6 class="mb-0">Ravi</h6>
                                <small class="text-muted">Regular Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                            </div>
                            <p class="card-text">“Affordable prices and excellent customer service. Highly recommended!”</p>
                            <div class="mt-3">
                                <h6 class="mb-0">Neha</h6>
                                <small class="text-muted">Loyal Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                                <i class='bx bxs-star text-warning'></i>
                            </div>
                            <p class="card-text">“Found the exact part I needed. Easy to order and install. Very satisfied.”</p>
                            <div class="mt-3">
                                <h6 class="mb-0">Amit</h6>
                                <small class="text-muted">Happy Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Call to Action Section -->
    <section class="py-5" style="background: linear-gradient(to right, #6a11cb, #2575fc); color: white;">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Find Your Perfect Item?</h2>
            <p class="lead mb-5">We provide reliable and affordable printer spare parts to keep your machines running smoothly.</p>
            <a href="shop.php" class="btn btn-light btn-lg rounded-pill">Shop All Products</a>
        </div>
    </section>

   <!-- Footer -->
   <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".owl-carousel").owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                dots: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 4
                    }
                }
            });
        });
    </script>
</body>
</html> 