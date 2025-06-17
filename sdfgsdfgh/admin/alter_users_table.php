<?php
require_once '../includes/db.php';

// Add status column to users table
$sql = "ALTER TABLE users ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER role";

if ($conn->query($sql)) {
    echo "Status column added successfully to users table.";
} else {
    echo "Error adding status column: " . $conn->error;
}

// Close the connection
$conn->close();
?> 