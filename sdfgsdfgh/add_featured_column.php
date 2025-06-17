<?php
require_once 'includes/db.php';

// Add is_featured column to products table
$sql = "ALTER TABLE products ADD COLUMN is_featured TINYINT(1) DEFAULT 0";

if ($conn->query($sql)) {
    echo "Successfully added is_featured column to products table";
    
    // Update some products to be featured
    $update_sql = "UPDATE products SET is_featured = 1 WHERE product_id IN (1, 2, 3, 4, 5, 6, 7, 8)";
    if ($conn->query($update_sql)) {
        echo "<br>Successfully updated some products as featured";
    } else {
        echo "<br>Error updating featured products: " . $conn->error;
    }
} else {
    echo "Error adding is_featured column: " . $conn->error;
}

$conn->close();
?> 