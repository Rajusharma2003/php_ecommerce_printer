<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
requireAdminLogin();

$admin_id = $_SESSION['admin_id'];
$success_message = '';
$error_message = '';

// Get admin details
$admin = getAdminDetails($admin_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate current password
    if (!empty($current_password)) {
        if (!password_verify($current_password, $admin['password'])) {
            $error_message = "Current password is incorrect";
        } else {
            // Update profile
            $sql = "UPDATE users SET 
                    first_name = ?, 
                    last_name = ?, 
                    email = ?";
            $params = [$first_name, $last_name, $email];
            $types = "sss";

            // If new password is provided
            if (!empty($new_password)) {
                if ($new_password === $confirm_password) {
                    if (strlen($new_password) >= 8) {
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $sql .= ", password = ?";
                        $params[] = $hashed_password;
                        $types .= "s";
                    } else {
                        $error_message = "New password must be at least 8 characters long";
                    }
                } else {
                    $error_message = "New passwords do not match";
                }
            }

            if (empty($error_message)) {
                $sql .= " WHERE user_id = ?";
                $params[] = $admin_id;
                $types .= "i";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);

                if ($stmt->execute()) {
                    $success_message = "Profile updated successfully";
                    // Refresh admin details
                    $admin = getAdminDetails($admin_id);
                } else {
                    $error_message = "Error updating profile";
                }
            }
        }
    } else {
        $error_message = "Please enter current password to make changes";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h4>Admin Panel</h4>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class='bx bxs-dashboard'></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">
                                <i class='bx bxs-box'></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="categories.php">
                                <i class='bx bxs-category'></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">
                                <i class='bx bxs-cart'></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="users.php">
                                <i class='bx bxs-user'></i> Users
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class='bx bxs-user-circle'></i> Profile
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class='bx bxs-log-out'></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <h2 class="mb-4">Admin Profile</h2>

                <?php if($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <?php if($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" value="<?php echo $admin['username']; ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo $admin['email']; ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="<?php echo $admin['first_name']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="<?php echo $admin['last_name']; ?>" required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Change Password</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="new_password" minlength="8">
                                    <small class="text-muted">Leave blank to keep current password</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_password" minlength="8">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 