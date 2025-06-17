<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// SQL to create activity_logs table
$sql = "CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

// Execute the query
if ($conn->query($sql)) {
    echo "Activity logs table created successfully";
} else {
    echo "Error creating activity logs table: " . $conn->error;
}

// Close the connection
$conn->close();
?> 