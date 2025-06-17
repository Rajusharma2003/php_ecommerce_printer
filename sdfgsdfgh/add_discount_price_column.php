<?php
require_once 'config/database.php';

// Add discount_price column to products table
$sql = "ALTER TABLE products ADD COLUMN discount_price DECIMAL(10,2) DEFAULT NULL";

if ($conn->query($sql)) {
    echo "Successfully added discount_price column to products table";
} else {
    echo "Error adding discount_price column: " . $conn->error;
}

$conn->close();
?> 