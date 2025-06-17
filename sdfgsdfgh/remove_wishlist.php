<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Remove item from wishlist
    $sql = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        log_activity($user_id, 'remove_wishlist', "Product ID: $product_id removed from wishlist");
        $_SESSION['success'] = "Item removed from wishlist";
    } else {
        $_SESSION['error'] = "Failed to remove item from wishlist";
    }
}

header("Location: profile.php#wishlist");
exit(); 