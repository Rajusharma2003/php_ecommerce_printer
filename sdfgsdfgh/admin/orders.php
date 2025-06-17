<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
requireAdminLogin();

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$success_message = '';
$error_message = '';

// Display messages
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = sanitize($_POST['status']);
    
    $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        $success_message = "Order status updated successfully";
    } else {
        $error_message = "Error updating order status";
    }
}

// Pagination
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Status filter
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';

// Get total records
$count_sql = "SELECT COUNT(*) as count FROM orders";
if ($status_filter) {
    $count_sql .= " WHERE status = ?";
}
$stmt = $conn->prepare($count_sql);
if ($status_filter) {
    $stmt->bind_param("s", $status_filter);
}
$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['count'];
$total_pages = ceil($total_records / $records_per_page);

// Get orders
$sql = "SELECT o.*, u.username, u.email 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.user_id";
if ($status_filter) {
    $sql .= " WHERE o.status = ?";
}
$sql .= " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
if ($status_filter) {
    $stmt->bind_param("sii", $status_filter, $records_per_page, $offset);
} else {
    $stmt->bind_param("ii", $records_per_page, $offset);
}
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management</title>
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
        .order-status {
            font-size: 0.875rem;
            padding: 0.35em 0.65em;
            font-weight: 500;
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
                            <a class="nav-link active" href="orders.php">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Orders Management</h2>
                    <div class="btn-group">
                        <a href="?status=" class="btn btn-outline-secondary <?php echo !$status_filter ? 'active' : ''; ?>">All</a>
                        <a href="?status=pending" class="btn btn-outline-warning <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">Pending</a>
                        <a href="?status=processing" class="btn btn-outline-info <?php echo $status_filter === 'processing' ? 'active' : ''; ?>">Processing</a>
                        <a href="?status=shipped" class="btn btn-outline-primary <?php echo $status_filter === 'shipped' ? 'active' : ''; ?>">Shipped</a>
                        <a href="?status=delivered" class="btn btn-outline-success <?php echo $status_filter === 'delivered' ? 'active' : ''; ?>">Delivered</a>
                        <a href="?status=cancelled" class="btn btn-outline-danger <?php echo $status_filter === 'cancelled' ? 'active' : ''; ?>">Cancelled</a>
                    </div>
                </div>

                <?php if($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <?php if($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Orders Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($order = $orders->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?php echo $order['order_id']; ?></td>
                                        <td>
                                            <?php echo $order['username']; ?><br>
                                            <small class="text-muted"><?php echo $order['email']; ?></small>
                                        </td>
                                        <td><?php echo formatPrice($order['total_amount']); ?></td>
                                        <td>
                                            <span class="badge order-status bg-<?php 
                                                echo match($order['status']) {
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'shipped' => 'primary',
                                                    'delivered' => 'success',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewOrderModal<?php echo $order['order_id']; ?>">
                                                <i class='bx bx-show'></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#updateStatusModal<?php echo $order['order_id']; ?>">
                                                <i class='bx bx-refresh'></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- View Order Modal -->
                                    <div class="modal fade" id="viewOrderModal<?php echo $order['order_id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Order #<?php echo $order['order_id']; ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <h6>Customer Information</h6>
                                                            <p>
                                                                Name: <?php echo $order['username']; ?><br>
                                                                Email: <?php echo $order['email']; ?><br>
                                                                Phone: <?php echo $order['phone']; ?>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Shipping Address</h6>
                                                            <p>
                                                                <?php echo $order['shipping_address']; ?><br>
                                                                <?php echo $order['shipping_city']; ?>, <?php echo $order['shipping_state']; ?><br>
                                                                <?php echo $order['shipping_zip']; ?><br>
                                                                <?php echo $order['shipping_country']; ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <h6>Order Items</h6>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Product</th>
                                                                <th>Price</th>
                                                                <th>Quantity</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $items_sql = "SELECT oi.*, p.name as product_name 
                                                                        FROM order_items oi 
                                                                        LEFT JOIN products p ON oi.product_id = p.product_id 
                                                                        WHERE oi.order_id = ?";
                                                            $items_stmt = $conn->prepare($items_sql);
                                                            $items_stmt->bind_param("i", $order['order_id']);
                                                            $items_stmt->execute();
                                                            $items = $items_stmt->get_result();
                                                            while($item = $items->fetch_assoc()):
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $item['product_name']; ?></td>
                                                                <td><?php echo formatPrice($item['price']); ?></td>
                                                                <td><?php echo $item['quantity']; ?></td>
                                                                <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                                                            </tr>
                                                            <?php endwhile; ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                                <td><strong><?php echo formatPrice($order['total_amount']); ?></strong></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Update Status Modal -->
                                    <div class="modal fade" id="updateStatusModal<?php echo $order['order_id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Order Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="orders.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select class="form-select" name="status" required>
                                                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                                <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                                <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                                <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                                <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if($total_pages > 1): ?>
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>">Previous</a>
                                </li>
                                <?php endif; ?>

                                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>

                                <?php if($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $status_filter ? '&status='.$status_filter : ''; ?>">Next</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 