  <!-- Footer -->
  <footer class="bg-while text-dark py-5">
        <div class="container ">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 ">
                    <h5>About Us</h5>
                    <p class="text-muted">Your trusted online shopping destination for quality products and excellent service.</p>
                    <div class="social-links">
                        <a href="#" class="text-dark me-2"><i class='bx bxl-facebook'></i></a>
                        <a href="#" class="text-dark me-2"><i class='bx bxl-twitter'></i></a>
                        <a href="#" class="text-dark me-2"><i class='bx bxl-instagram'></i></a>
                        <a href="#" class="text-dark"><i class='bx bxl-linkedin'></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-muted text-decoration-none">About Us</a></li>
                        <li><a href="contact.php" class="text-muted text-decoration-none">Contact Us</a></li>
                        <li><a href="faq.php" class="text-muted text-decoration-none">FAQ</a></li>
                        <li><a href="terms.php" class="text-muted text-decoration-none">Terms & Conditions</a></li>
                        <li><a href="privacy.php" class="text-muted text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Customer Service</h5>
                    <ul class="list-unstyled">
                        <li><a href="shipping.php" class="text-muted text-decoration-none">Shipping Policy</a></li>
                        <li><a href="returns.php" class="text-muted text-decoration-none">Returns & Refunds</a></li>
                        <li><a href="track-order.php" class="text-muted text-decoration-none">Track Order</a></li>
                        <li><a href="size-guide.php" class="text-muted text-decoration-none">Size Guide</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled text-muted">
                        <li><i class='bx bxs-map'></i> 123 Shopping Street, City, Country</li>
                        <li><i class='bx bxs-phone'></i> +1 234 567 890</li>
                        <li><i class='bx bxs-envelope'></i> info@shopnow.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> ShopNow. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="assets/images/payment-methods.png" alt="Payment Methods" height="30">
                </div>
            </div>
        </div>
    </footer>

    <style>
        /* Existing styles */
        
        /* Product Card Styles */
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .product-image-placeholder {
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 48px;
        }
        
        /* Newsletter Section Styles */
        .newsletter-section {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }
        
        /* Footer Styles */
        .social-links a {
            font-size: 24px;
            transition: opacity 0.2s;
        }
        .social-links a:hover {
            opacity: 0.8;
        }
        footer .list-unstyled li {
            margin-bottom: 10px;
        }
        footer .list-unstyled a {
            transition: color 0.2s;
        }
        footer .list-unstyled a:hover {
            color: #fff !important;
        }
    </style>