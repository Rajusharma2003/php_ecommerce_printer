<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
requireAdminLogin();

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Invalid request method";
    header("Location: users.php");
    exit();
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error_message'] = "Invalid request";
    header("Location: users.php");
    exit();
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        // Validate required fields
        if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['error_message'] = "All fields are required";
            header("Location: users.php");
            exit();
        }

        // Sanitize inputs
        $username = sanitize($_POST['username']);
        $email = sanitize($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = sanitize($_POST['role']);

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Invalid email format";
            header("Location: users.php");
            exit();
        }

        // Check if username or email already exists
        $check_sql = "SELECT COUNT(*) as count FROM users WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $count = $result->fetch_assoc()['count'];

        if ($count > 0) {
            $_SESSION['error_message'] = "Username or email already exists";
            header("Location: users.php");
            exit();
        }

        // Insert new user
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "User added successfully";
        } else {
            $_SESSION['error_message'] = "Error adding user";
        }
        break;

    case 'edit':
        // Validate required fields
        if (empty($_POST['user_id']) || empty($_POST['username']) || empty($_POST['email'])) {
            $_SESSION['error_message'] = "Required fields are missing";
            header("Location: users.php");
            exit();
        }

        // Sanitize inputs
        $user_id = (int)$_POST['user_id'];
        $username = sanitize($_POST['username']);
        $email = sanitize($_POST['email']);
        $role = sanitize($_POST['role']);

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Invalid email format";
            header("Location: users.php");
            exit();
        }

        // Check if username or email already exists for other users
        $check_sql = "SELECT COUNT(*) as count FROM users WHERE (username = ? OR email = ?) AND user_id != ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ssi", $username, $email, $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $count = $result->fetch_assoc()['count'];

        if ($count > 0) {
            $_SESSION['error_message'] = "Username or email already exists";
            header("Location: users.php");
            exit();
        }

        // Update user
        if (!empty($_POST['password'])) {
            // Update with new password
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $username, $email, $password, $role, $user_id);
        } else {
            // Update without changing password
            $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $email, $role, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "User updated successfully";
        } else {
            $_SESSION['error_message'] = "Error updating user";
        }
        break;

    default:
        $_SESSION['error_message'] = "Invalid action";
        break;
}

header("Location: users.php");
exit(); 