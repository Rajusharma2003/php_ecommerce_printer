  <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo.png" alt="ShopNow Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            <?php
                            global $conn; // Ensure $conn is accessible
                            // Get all active categories
                            $sql = "SELECT * FROM categories ORDER BY name ASC";
                            $categories = $conn->query($sql);
                            
                            if ($categories && $categories->num_rows > 0) {
                                while($category = $categories->fetch_assoc()) {
                                    echo '<li><a class="dropdown-item" href="category.php?id=' . $category['category_id'] . '">' . 
                                         htmlspecialchars($category['name']) . '</a></li>';
                                }
                            } else {
                                echo '<li><span class="dropdown-item text-muted">No categories found</span></li>';
                            }
                            ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="categories.php">View All Categories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <form class="d-flex me-2" action="search.php" method="GET">
                        <div class="input-group">
                            <input type="search" name="q" class="form-control" placeholder="Search products..." aria-label="Search">
                            <select name="category_id" class="form-select d-none d-md-block" style="max-width: 150px;">
                                <option value="">All Categories</option>
                                <?php
                                // Get all active categories for the search filter
                                global $conn; // Ensure $conn is accessible here as well
                                $search_categories_sql = "SELECT category_id, name FROM categories WHERE status = 'active' ORDER BY name ASC";
                                $search_categories_result = $conn->query($search_categories_sql);
                                
                                if ($search_categories_result && $search_categories_result->num_rows > 0) {
                                    while($search_category = $search_categories_result->fetch_assoc()) {
                                        // Keep selected category if it was part of the previous search
                                        $selected = (isset($_GET['category_id']) && $_GET['category_id'] == $search_category['category_id']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($search_category['category_id']) . '" ' . $selected . '>' . 
                                             htmlspecialchars($search_category['name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <button class="btn btn-outline-primary" type="submit">
                                <i class='bx bx-search'></i>
                            </button>
                        </div>
                    </form>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="cart.php" class="btn btn-outline-primary position-relative">
                            <i class='bx bxs-cart'></i> Cart
                            <?php
                            // Get cart count
                            $cart_count = 0;
                            if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                $cart_count = count($_SESSION['cart']);
                            }
                            if($cart_count > 0):
                            ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cart_count; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bxs-user'></i> My Account
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php"><i class='bx bxs-user-detail'></i> Profile</a></li>
                                <li><a class="dropdown-item" href="orders.php"><i class='bx bxs-package'></i> Orders</a></li>
                                <!-- <li><a class="dropdown-item" href="wishlist.php"><i class='bx bxs-heart'></i> Wishlist</a></li> -->
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class='bx bxs-log-out'></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary">
                            <i class='bx bxs-log-in'></i> Login
                        </a>
                        <a href="register.php" class="btn btn-primary">
                            <i class='bx bxs-user-plus'></i> Register
                        </a>
                        <a href="cart.php" class="btn btn-outline-primary position-relative">
                            <i class='bx bxs-cart'></i> Cart
                            <?php
                            // Get cart count
                            $cart_count = 0;
                            if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                $cart_count = count($_SESSION['cart']);
                            }
                            if($cart_count > 0):
                            ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cart_count; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>