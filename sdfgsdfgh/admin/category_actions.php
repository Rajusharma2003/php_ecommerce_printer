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
        header("Location: categories.php");
        exit();
    }
    
    if ($action === 'delete') {
        $category_id = (int)$_POST['category_id'];
        
        // Check if category has subcategories
        $check_sql = "SELECT COUNT(*) as count FROM categories WHERE parent_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $category_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $count = $result->fetch_assoc()['count'];
        
        if ($count > 0) {
            $_SESSION['error_message'] = "Cannot delete category: It has subcategories. Please delete or reassign subcategories first.";
            header("Location: categories.php");
            exit();
        }
        
        // Get category image before deleting
        $image_sql = "SELECT image_url FROM categories WHERE category_id = ?";
        $image_stmt = $conn->prepare($image_sql);
        $image_stmt->bind_param("i", $category_id);
        $image_stmt->execute();
        $image_result = $image_stmt->get_result();
        $category = $image_result->fetch_assoc();
        
        // Delete category
        $delete_sql = "DELETE FROM categories WHERE category_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $category_id);
        
        if ($delete_stmt->execute()) {
            // Delete category image if exists
            if ($category && $category['image_url']) {
                deleteImage($category['image_url']);
            }
            $_SESSION['success_message'] = "Category deleted successfully";
        } else {
            $_SESSION['error_message'] = "Error deleting category: " . $conn->error;
        }
        header("Location: categories.php");
        exit();
    }
    
    // For add and edit actions, validate required fields
    if ($action === 'add' || $action === 'edit') {
        $name = sanitize($_POST['name']);
        $description = sanitize($_POST['description']);
        $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        $status = isset($_POST['status']) && in_array($_POST['status'], ['active', 'inactive']) ? $_POST['status'] : 'active';
        
        // Validate required fields
        if (empty($name)) {
            $_SESSION['error_message'] = "Please fill in all required fields";
            header("Location: categories.php");
            exit();
        }
        
        if ($action === 'add') {
            // Handle image upload
            $image_url = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image_url = uploadImage($_FILES['image']);
                if ($image_url === false) {
                    $_SESSION['error_message'] = "Error uploading image. Please try again.";
                    header("Location: categories.php");
                    exit();
                }
            }
            
            // Insert new category
            $sql = "INSERT INTO categories (name, description, parent_id, status, image_url) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiss", $name, $description, $parent_id, $status, $image_url);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Category added successfully";
            } else {
                $_SESSION['error_message'] = "Error adding category: " . $conn->error;
            }
            header("Location: categories.php");
            exit();
        }
        elseif ($action === 'edit') {
            $category_id = (int)$_POST['category_id'];
            
            // Handle image upload
            $image_url = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Get old image URL
                $old_image_sql = "SELECT image_url FROM categories WHERE category_id = ?";
                $old_image_stmt = $conn->prepare($old_image_sql);
                $old_image_stmt->bind_param("i", $category_id);
                $old_image_stmt->execute();
                $old_image = $old_image_stmt->get_result()->fetch_assoc();
                
                // Upload new image
                $image_url = uploadImage($_FILES['image']);
                if ($image_url === false) {
                    $_SESSION['error_message'] = "Error uploading image. Please try again.";
                    header("Location: categories.php");
                    exit();
                }
                
                // Delete old image if exists
                if ($old_image && $old_image['image_url']) {
                    deleteImage($old_image['image_url']);
                }
            }
            
            // Update category
            if ($image_url) {
                $sql = "UPDATE categories SET name = ?, description = ?, parent_id = ?, status = ?, image_url = ? WHERE category_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssissi", $name, $description, $parent_id, $status, $image_url, $category_id);
            } else {
                $sql = "UPDATE categories SET name = ?, description = ?, parent_id = ?, status = ? WHERE category_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssisi", $name, $description, $parent_id, $status, $category_id);
            }
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Category updated successfully";
            } else {
                $_SESSION['error_message'] = "Error updating category: " . $conn->error;
            }
            header("Location: categories.php");
            exit();
        }
    }
}
?> 