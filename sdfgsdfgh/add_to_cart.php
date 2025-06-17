<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($product_id > 0 && $quantity > 0) {
        // Fetch product details to ensure it's a valid product and get its price
        $stmt = $conn->prepare("SELECT product_id, name, price, stock_quantity, image_url FROM products WHERE product_id = ? AND status = 'active'");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            // Initialize cart if not exists
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Add or update product in cart
            if (isset($_SESSION['cart'][$product_id])) {
                // Check if adding more would exceed stock
                if (($_SESSION['cart'][$product_id]['quantity'] + $quantity) > $product['stock_quantity']) {
                    $_SESSION['error_message'] = "Cannot add more. Exceeds available stock.";
                } else {
                    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                    $_SESSION['success_message'] = "Product quantity updated in cart!";
                }
            } else {
                // Check if adding exceeds stock (for new item)
                if ($quantity > $product['stock_quantity']) {
                    $_SESSION['error_message'] = "Cannot add. Exceeds available stock.";
                } else {
                    $_SESSION['cart'][$product_id] = [
                        'product_id' => $product['product_id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity,
                        'image_url' => $product['image_url']
                    ];
                    $_SESSION['success_message'] = "Product added to cart!";
                }
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Product not found or is inactive.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid product ID or quantity.";
    }
}

// Redirect back to the product details page or wherever appropriate
header("Location: product_details.php?id=" . $product_id); // Redirect back to the same product page
exit();
?> 