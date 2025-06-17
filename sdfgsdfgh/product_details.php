<?php
session_start();
require_once 'includes/config.php'; // Include config.php for SITE_NAME
include 'includes/db.php';
include 'includes/functions.php';

$page_title = "Product Details"; // Default title

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Fetch product details from database
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $page_title = htmlspecialchars($product['name']) . " Details"; // Set title to product name
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>

    <?php
    if ($product_id > 0 && $product) {
        // Product found, display details
        ?>
        <!-- Page Header -->
        <div class="bg-light py-5">
            <div class="container">
                <h1 class="display-4"><?php echo htmlspecialchars($product['name']); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container product-details py-5">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo getImageUrl($product['image_url']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="col-md-6">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="price">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <!-- Add to cart form -->
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="<?php echo htmlspecialchars($product['stock_quantity']); ?>" style="width: 100px;">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class='bx bx-cart-add'></i> Add to Cart</button>
                    </form>
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success mt-3" role="alert">
                            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        // Product not found or invalid ID
        ?>
        <!-- Page Header for Product Not Found / Invalid ID -->
        <div class="bg-light py-5">
            <div class="container">
                <h1 class="display-4"><?php echo ($product_id > 0) ? "Product Not Found" : "Invalid Product ID"; ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo ($product_id > 0) ? "Product Not Found" : "Invalid Product ID"; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class='container py-5'><p><?php echo ($product_id > 0) ? "The product you are looking for does not exist." : "The provided product ID is invalid."; ?></p></div>
        <?php
    }
    ?>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 































































