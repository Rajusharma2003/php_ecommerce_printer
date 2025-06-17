<?php
// Sanitize input data
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

// Check if user is logged in as admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Redirect if not logged in
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Get admin details
function getAdminDetails($admin_id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE user_id = ? AND role = 'admin' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Format date
function formatDate($date) {
    return date('M d, Y H:i', strtotime($date));
}

// Format price
function formatPrice($price) {
    return '₹' . number_format($price, 2);
}

// Generate random string
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Get the full URL for an image
 * @param string $image_path The image path
 * @return string The full URL to the image
 */
function getImageUrl($image_path) {
    if (empty($image_path)) {
        return 'assets/images/placeholder.jpg';
    }
    
    // If it's already a full URL or data URL, return as is
    if (strpos($image_path, 'http://') === 0 || strpos($image_path, 'https://') === 0 || strpos($image_path, 'data:image') === 0) {
        return $image_path;
    }
    
    // Check if we're in admin section
    $is_admin = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;
    
    // If it's a relative path starting with 'uploads/', return as is
    if (strpos($image_path, 'uploads/') === 0) {
        return $is_admin ? '../' . $image_path : $image_path;
    }
    
    // Otherwise, prepend the uploads directory
    return $is_admin ? '../uploads/' . $image_path : 'uploads/' . $image_path;
}

/**
 * Upload an image file
 * @param array $file The $_FILES array element for the image
 * @return string|false The filename if successful, false otherwise
 */
function uploadImage($file) {
    $upload_dir = __DIR__ . '/../uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . basename($file['name']);
    $target_path = $upload_dir . $filename;
    
    // Check if file is an actual image
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return false;
    }
    
    // Check file size (5MB max)
    if ($file['size'] > 5000000) {
        return false;
    }
    
    // Allow certain file formats
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_type = strtolower(pathinfo($target_path, PATHINFO_EXTENSION));
    if (!in_array($file_type, $allowed_types)) {
        return false;
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return $filename;
    }
    
    return false;
}

/**
 * Delete an image file
 * @param string $filename The image filename
 * @return bool True if successful, false otherwise
 */
function deleteImage($filename) {
    if (empty($filename)) {
        return false;
    }
    
    $file_path = __DIR__ . '/../uploads/' . $filename;
    
    if (file_exists($file_path)) {
        return unlink($file_path);
    }
    
    return false;
}

// Get pagination
function getPagination($total_records, $records_per_page, $current_page) {
    $total_pages = ceil($total_records / $records_per_page);
    $pagination = [];
    
    if ($total_pages > 1) {
        $pagination['current_page'] = $current_page;
        $pagination['total_pages'] = $total_pages;
        $pagination['has_previous'] = ($current_page > 1);
        $pagination['has_next'] = ($current_page < $total_pages);
        $pagination['previous_page'] = $current_page - 1;
        $pagination['next_page'] = $current_page + 1;
    }
    
    return $pagination;
}

// Get order status badge
function getOrderStatusBadge($status) {
    $badges = [
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger'
    ];
    
    $color = isset($badges[$status]) ? $badges[$status] : 'secondary';
    return '<span class="badge bg-' . $color . '">' . ucfirst($status) . '</span>';
}

// Get payment status badge
function getPaymentStatusBadge($status) {
    $badges = [
        'pending' => 'warning',
        'paid' => 'success',
        'failed' => 'danger'
    ];
    
    $color = isset($badges[$status]) ? $badges[$status] : 'secondary';
    return '<span class="badge bg-' . $color . '">' . ucfirst($status) . '</span>';
}

function getStatusBadge($status) {
    $badges = [
        'active' => 'success',
        'inactive' => 'danger',
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger',
        'approved' => 'success',
        'rejected' => 'danger',
        'paid' => 'success',
        'failed' => 'danger'
    ];
    
    $color = isset($badges[$status]) ? $badges[$status] : 'secondary';
    $label = ucfirst($status);
    
    return '<span class="badge bg-' . $color . '">' . $label . '</span>';
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

// Function to generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Function to verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Function to get all categories
function get_categories() {
    global $conn;
    $sql = "SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC";
    $result = $conn->query($sql);
    $categories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    return $categories;
}

// Function to get category by ID
function get_category($id) {
    global $conn;
    $sql = "SELECT * FROM categories WHERE id = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to get products by category
function get_products_by_category($category_id, $limit = 12) {
    global $conn;
    $sql = "SELECT * FROM products WHERE category_id = ? AND status = 'active' LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $category_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Function to get featured products
function get_featured_products($limit = 8) {
    global $conn;
    $sql = "SELECT * FROM products WHERE is_featured = 1 AND status = 'active' LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Function to get latest products
function get_latest_products($limit = 8) {
    global $conn;
    $sql = "SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Function to get product by ID
function get_product($id) {
    global $conn;
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ? AND p.status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to handle image upload
function handle_image_upload($file, $target_dir) {
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ["error" => "File is not an image."];
    }

    // Check file size (5MB max)
    if ($file["size"] > 5000000) {
        return ["error" => "File is too large."];
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        return ["error" => "Only JPG, JPEG, PNG & GIF files are allowed."];
    }

    // Generate unique filename
    $filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["success" => true, "filename" => $filename];
    } else {
        return ["error" => "Failed to upload file."];
    }
}

// Function to delete image
function delete_image($image_path) {
    if (file_exists($image_path)) {
        unlink($image_path);
        return true;
    }
    return false;
}

// Function to generate random string
function generate_random_string($length = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

// Function to send email
function send_email($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . SITE_NAME . ' <' . ADMIN_EMAIL . '>' . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Function to log activity
function log_activity($user_id, $action, $details = '') {
    global $conn;
    try {
        $sql = "INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $action, $details);
        return $stmt->execute();
    } catch (Exception $e) {
        // First, check and create users table if it doesn't exist
        if (strpos($e->getMessage(), "Table 'ecommerce_dbc.users' doesn't exist") !== false) {
            $create_users_sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                // Add other columns as needed, e.g., username, email, etc.
                username VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB";
            $conn->query($create_users_sql);
        }
        // Now create activity_logs as before
        if (strpos($e->getMessage(), "Table 'ecommerce_dbc.activity_logs' doesn't exist") !== false) {
            $create_table_sql = "CREATE TABLE IF NOT EXISTS activity_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                action VARCHAR(50) NOT NULL,
                details TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
            ) ENGINE=InnoDB";
            if ($conn->query($create_table_sql)) {
                // Try inserting the log again
                $sql = "INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss", $user_id, $action, $details);
                return $stmt->execute();
            }
        }
        return false;
    }
}
?> 