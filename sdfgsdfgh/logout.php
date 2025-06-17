<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Log the logout activity
    log_activity($user_id, 'logout', 'User logged out');
    
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy the session
    session_destroy();
}

// Redirect to login page
header("Location: login.php");
exit(); 