<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if ($product_id > 0) {
        if (isset($_SESSION['cart']) && array_key_exists($product_id, $_SESSION['cart'])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success_message'] = "Product removed from cart.";
        } else {
            $_SESSION['error_message'] = "Product not found in cart.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid product ID.";
    }
}

header("Location: cart.php");
exit();
?> 