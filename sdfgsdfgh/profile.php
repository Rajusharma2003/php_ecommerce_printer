<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "No user found.";
    }

    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

// Get user's orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Display success/error messages
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success'], $_SESSION['error']);

// Close the database connection after all operations are done
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h1>User Profile</h1>
    <?php if (isset($user)): ?>
        <p>Name: <?php echo isset($user['name']) ? htmlspecialchars($user['name']) : 'N/A'; ?></p>
        <p>Email: <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'N/A'; ?></p>
        <!-- Add more fields as necessary -->
    <?php else: ?>
        <p>No user data available.</p>
    <?php endif; ?>
</body>
</html> 