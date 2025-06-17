<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    if ($product_id > 0 && $quantity >= 0) {
        // Fetch product stock quantity from database
        $stmt = $conn->prepare("SELECT stock_quantity FROM products WHERE product_id = ? AND status = 'active'");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            if ($quantity == 0) {
                // Remove item if quantity is 0
                if (isset($_SESSION['cart']) && array_key_exists($product_id, $_SESSION['cart'])) {
                    unset($_SESSION['cart'][$product_id]);
                    $_SESSION['success_message'] = "Product removed from cart.";
                }
            } elseif ($quantity > $product['stock_quantity']) {
                $_SESSION['error_message'] = "Cannot update. Quantity exceeds available stock of " . $product['stock_quantity'] . ".";
            } else {
                // Update quantity
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                    $_SESSION['success_message'] = "Cart updated successfully.";
                } else {
                    $_SESSION['error_message'] = "Product not found in cart.";
                }
            }
        } else {
            $_SESSION['error_message'] = "Product not found or is inactive.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid product ID or quantity.";
    }
}

header("Location: cart.php");
exit();
?> 