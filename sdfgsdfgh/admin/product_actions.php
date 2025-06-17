<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
requireAdminLogin();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Invalid request";
        header("Location: products.php");
        exit();
    }
    
    // Common fields
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = (float)$_POST['price'];
    $stock_quantity = (int)$_POST['stock_quantity'];
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $status = sanitize($_POST['status']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Validate required fields
    if (empty($name) || $price <= 0 || $stock_quantity < 0) {
        $_SESSION['error_message'] = "Please fill in all required fields correctly";
        header("Location: products.php");
        exit();
    }
    
    if ($action === 'add') {
        // Handle image upload
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_url = uploadImage($_FILES['image']);
            if (!$image_url) {
                $_SESSION['error'] = "Error uploading image";
                header("Location: products.php");
                exit();
            }
        }
        
        // Insert new product
        $sql = "INSERT INTO products (name, description, price, stock_quantity, category_id, image_url, status, is_featured) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddissi", $name, $description, $price, $stock_quantity, $category_id, $image_url, $status, $is_featured);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product added successfully";
        } else {
            $_SESSION['error_message'] = "Error adding product";
        }
        header("Location: products.php");
        exit();
    }
    elseif ($action === 'edit') {
        $product_id = (int)$_POST['product_id'];
        
        // Get current product data
        $current_sql = "SELECT image_url FROM products WHERE product_id = ?";
        $current_stmt = $conn->prepare($current_sql);
        $current_stmt->bind_param("i", $product_id);
        $current_stmt->execute();
        $current_result = $current_stmt->get_result();
        $current_product = $current_result->fetch_assoc();
        
        // Handle image upload
        $image_url = $current_product['image_url'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $new_image_url = uploadImage($_FILES['image']);
            if ($new_image_url) {
                // Delete old image
                if ($image_url) {
                    deleteImage($image_url);
                }
                $image_url = $new_image_url;
            }
        }
        
        // Update product
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, category_id = ?, image_url = ?, status = ?, is_featured = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddissii", $name, $description, $price, $stock_quantity, $category_id, $image_url, $status, $is_featured, $product_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Product updated successfully";
        } else {
            $_SESSION['error_message'] = "Error updating product";
        }
        header("Location: products.php");
        exit();
    }
}
?>