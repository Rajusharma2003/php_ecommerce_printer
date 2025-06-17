<?php
require_once '../includes/db.php';

// Admin credentials
$admin_username = 'admin';
$admin_password = 'admin123'; // This will be hashed
$admin_email = 'admin@example.com';
$admin_firstname = 'Admin';
$admin_lastname = 'User';

// Check if admin already exists
$sql = "SELECT user_id FROM users WHERE username = ? OR email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $admin_username, $admin_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Admin user already exists!";
    exit();
}

// Hash the password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Insert admin user
$sql = "INSERT INTO users (username, email, password, first_name, last_name, role) 
        VALUES (?, ?, ?, ?, ?, 'admin')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $admin_username, $admin_email, $hashed_password, $admin_firstname, $admin_lastname);

if ($stmt->execute()) {
    echo "Admin user created successfully!<br>";
    echo "Username: " . $admin_username . "<br>";
    echo "Password: " . $admin_password . "<br>";
    echo "<br>Please delete this file after creating the admin user for security reasons.";
} else {
    echo "Error creating admin user: " . $conn->error;
}
?> 