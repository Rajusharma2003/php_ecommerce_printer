<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$page_title = "Shop All Products";

// Pagination settings
$records_per_page = 12; // Number of products to display per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Handle messages from other pages (e.g., add to cart)
$success_message = '';
$error_message = '';

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Get total number of active products
$count_sql = "SELECT COUNT(*) as total_products FROM products WHERE status = 'active'";
$count_result = $conn->query($count_sql);
$total_products = $count_result->fetch_assoc()['total_products'];
$total_pages = ceil($total_products / $records_per_page);

// Get all active products with category names for the current page
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        WHERE p.status = 'active' 
        ORDER BY p.created_at DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$products = $stmt->get_result();

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
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <div class="bg-light py-5">
        <div class="container">
            <h1 class="display-4">Shop All Products</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shop</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <?php if ($success_message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($products->num_rows > 0): ?>
            <div class="row g-4">
                <?php while($product = $products->fetch_assoc()): 
                    $has_discount = isset($product['discount_price']) && 
                                  $product['discount_price'] !== null && 
                                  $product['discount_price'] > 0 && 
                                  $product['discount_price'] < $product['price'];
                ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 product-card">
                            <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                                <img src="<?php echo getImageUrl($product['image_url']); ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            </a>
                            <?php if($has_discount): 
                                $discount_percentage = round((($product['price'] - $product['discount_price']) / $product['price']) * 100);
                            ?>
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    <?php echo $discount_percentage; ?>% OFF
                                </span>
                            <?php endif; ?>
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
                                    <form action="add_to_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                        <input type="hidden" name="quantity" value="1"> <!-- Default to 1 for quick add -->
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class='bx bx-cart-add'></i> Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-info text-center" role="alert">
                No products found.
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 