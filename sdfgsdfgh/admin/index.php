<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
requireAdminLogin();

// Get admin details
$admin_id = $_SESSION['admin_id'];
$admin = getAdminDetails($admin_id);

// Get statistics
$stats = [];

// Total Orders
$sql = "SELECT COUNT(*) as total FROM orders";
$result = $conn->query($sql);
$stats['total_orders'] = $result->fetch_assoc()['total'];

// Total Products
$sql = "SELECT COUNT(*) as total FROM products";
$result = $conn->query($sql);
$stats['total_products'] = $result->fetch_assoc()['total'];

// Total Users
$sql = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
$result = $conn->query($sql);
$stats['total_users'] = $result->fetch_assoc()['total'];

// Total Revenue
$sql = "SELECT SUM(final_amount) as total FROM orders WHERE payment_status = 'paid'";
$result = $conn->query($sql);
$stats['total_revenue'] = $result->fetch_assoc()['total'] ?? 0;

// Recent Orders
$sql = "SELECT o.*, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.user_id 
        ORDER BY o.created_at DESC 
        LIMIT 5";
$recent_orders = $conn->query($sql);

// Low Stock Products
$sql = "SELECT * FROM products WHERE stock_quantity < 10 ORDER BY stock_quantity ASC LIMIT 5";
$low_stock_products = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-right: 20px;
            color: #6c757d;
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
                            <a class="nav-link active" href="index.php">
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
                <h2 class="mb-4">Dashboard</h2>

                <!-- Admin Profile Card -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="profile-card">
                            <div class="profile-header">
                                <div class="profile-avatar">
                                    <i class='bx bxs-user'></i>
                                </div>
                                <div>
                                    <h4 class="mb-1"><?php echo $admin['first_name'] . ' ' . $admin['last_name']; ?></h4>
                                    <p class="text-muted mb-0"><?php echo $admin['email']; ?></p>
                                    <small class="text-muted">Last login: <?php echo formatDate($admin['updated_at']); ?></small>
                                </div>
                            </div>
                            <a href="profile.php" class="btn btn-primary">Edit Profile</a>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary text-white">
                            <h3><?php echo $stats['total_orders']; ?></h3>
                            <p class="mb-0">Total Orders</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success text-white">
                            <h3><?php echo formatPrice($stats['total_revenue']); ?></h3>
                            <p class="mb-0">Total Revenue</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info text-white">
                            <h3><?php echo $stats['total_products']; ?></h3>
                            <p class="mb-0">Total Products</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning text-white">
                            <h3><?php echo $stats['total_users']; ?></h3>
                            <p class="mb-0">Total Users</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Recent Orders</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($order = $recent_orders->fetch_assoc()): ?>
                                            <tr>
                                                <td>#<?php echo $order['order_id']; ?></td>
                                                <td><?php echo $order['username']; ?></td>
                                                <td><?php echo formatPrice($order['final_amount']); ?></td>
                                                <td><?php echo getOrderStatusBadge($order['status']); ?></td>
                                                <td><?php echo formatDate($order['created_at']); ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Products -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Low Stock Products</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($product = $low_stock_products->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $product['name']; ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $product['stock_quantity'] < 5 ? 'danger' : 'warning'; ?>">
                                                        <?php echo $product['stock_quantity']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 