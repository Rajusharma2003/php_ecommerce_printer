<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $user_id = $_SESSION['user_id'];

    if (empty($name)) {
        $error = "Name is required";
    } else {
        // Update user profile
        $sql = "UPDATE users SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $user_id);

        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name;
            log_activity($user_id, 'update_profile', 'Profile updated');
            $success = "Profile updated successfully";
        } else {
            $error = "Failed to update profile";
        }
    }
}

// Redirect back to profile page with message
if (!empty($error)) {
    $_SESSION['error'] = $error;
} elseif (!empty($success)) {
    $_SESSION['success'] = $success;
}

header("Location: profile.php");
exit(); 