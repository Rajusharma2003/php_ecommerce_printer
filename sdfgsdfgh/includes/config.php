<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce_dbc');

// Site configuration
define('SITE_NAME', 'Your Store Name');
define('SITE_URL', 'http://localhost/sdfgsdfgh');
define('ADMIN_EMAIL', 'admin@yourstore.com');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Set default timezone
date_default_timezone_set('UTC');

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Function to verify CSRF token
function verify_csrf_token() {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }
}

// Function to generate CSRF token input
function csrf_token_input() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Function to redirect
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Function to get current page URL
function get_current_page() {
    return basename($_SERVER['PHP_SELF']);
}

// Function to format price
function format_price($price) {
    return '$' . number_format($price, 2);
}

// Function to get image URL
function get_image_url($image_path) {
    if (empty($image_path)) {
        return SITE_URL . '/assets/images/placeholder.jpg';
    }
    return SITE_URL . '/' . $image_path;
}

// Function to get category URL
function get_category_url($category_id, $category_name) {
    $slug = strtolower(str_replace(' ', '-', $category_name));
    return SITE_URL . '/category.php?id=' . $category_id . '&slug=' . $slug;
}

// Function to get product URL
function get_product_url($product_id, $product_name) {
    $slug = strtolower(str_replace(' ', '-', $product_name));
    return SITE_URL . '/product.php?id=' . $product_id . '&slug=' . $slug;
}

// Function to check if product is in stock
function is_in_stock($quantity) {
    return $quantity > 0;
}

// Function to get stock status
function get_stock_status($quantity) {
    if ($quantity > 10) {
        return 'In Stock';
    } elseif ($quantity > 0) {
        return 'Low Stock';
    } else {
        return 'Out of Stock';
    }
}

// Function to calculate discount percentage
function calculate_discount_percentage($original_price, $discount_price) {
    if ($original_price <= 0 || $discount_price >= $original_price) {
        return 0;
    }
    return round((($original_price - $discount_price) / $original_price) * 100);
}

// Function to format date
function format_date($date) {
    return date('F d, Y', strtotime($date));
}

// Function to truncate text
function truncate_text($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

// Function to generate pagination
function generate_pagination($total_items, $items_per_page, $current_page, $url_pattern) {
    $total_pages = ceil($total_items / $items_per_page);
    if ($total_pages <= 1) {
        return '';
    }

    $pagination = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // Previous button
    if ($current_page > 1) {
        $pagination .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $current_page - 1) . '">Previous</a></li>';
    }
   
    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            $pagination .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $i) . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($current_page < $total_pages) {
        $pagination .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $current_page + 1) . '">Next</a></li>';
    }

    $pagination .= '</ul></nav>';
    return $pagination;
}
?> 