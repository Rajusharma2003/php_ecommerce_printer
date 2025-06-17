<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$page_title = "Shopping Cart";

// Handle messages from add_to_cart.php or other cart actions
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

// Calculate cart total
$cart_total = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item_id => $item) {
        $cart_total += ($item['price'] * $item['quantity']);
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
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <div class="bg-light py-5">
        <div class="container">
            <h1 class="display-4">Shopping Cart</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
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

        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php
                                    // Fetch product image_url from database for robust display
                                    $product_image_url = '';
                                    $stmt_img = $conn->prepare("SELECT image_url FROM products WHERE product_id = ?");
                                    $stmt_img->bind_param("i", $product_id);
                                    $stmt_img->execute();
                                    $result_img = $stmt_img->get_result();
                                    if ($img_data = $result_img->fetch_assoc()) {
                                        $product_image_url = $img_data['image_url'];
                                    }
                                    $stmt_img->close();
                                    ?>
                                    <img src="<?php echo getImageUrl($product_image_url); ?>" class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">ID: <?php echo htmlspecialchars($item['product_id']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo formatPrice($item['price']); ?></td>
                            <td>
                                <form action="update_cart.php" method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['product_id']); ?>">
                                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                </form>
                            </td>
                            <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                            <td>
                                <form action="remove_from_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['product_id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class='bx bx-trash'></i> Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Cart Summary</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total:
                                    <span class="fw-bold display-6">$<?php echo htmlspecialchars(number_format($cart_total, 2)); ?></span>
                                </li>
                            </ul>
                            <div class="d-grid mt-3">
                                <a href="checkout.php" class="btn btn-primary btn-lg">Proceed to Checkout</a>
                            </div>
                            <div class="d-grid mt-2">
                                <a href="shop.php" class="btn btn-outline-secondary">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-info text-center" role="alert">
                Your cart is empty. <a href="shop.php">Start shopping now!</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 