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

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    
    // Get product image before deleting
    $sql = "SELECT image_url FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    // Delete product
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        // Delete product image if exists
        if ($product && $product['image_url']) {
            deleteImage($product['image_url']);
        }
        $success_message = "Product deleted successfully";
    } else {
        $error_message = "Error deleting product";
    }
}

// Get all categories for dropdown
$categories = $conn->query("SELECT category_id, name FROM categories WHERE status = 'active' ORDER BY name");

// Pagination
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Get total records
$count_sql = "SELECT COUNT(*) as count FROM products";
$total_records = $conn->query($count_sql)->fetch_assoc()['count'];
$total_pages = ceil($total_records / $records_per_page);

// Get products with category names
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        ORDER BY p.created_at DESC 
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$products = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
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
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
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
                            <a class="nav-link active" href="products.php">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Products Management</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class='bx bx-plus'></i> Add New Product
                    </button>
                </div>

                <?php if($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <?php if($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Products Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($product = $products->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <?php if($product['image_url']): ?>
                                                <img src="<?php echo getImageUrl($product['image_url']); ?>" 
                                                     class="product-image" 
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                     onerror="this.src='../assets/images/placeholder.jpg'">
                                            <?php else: ?>
                                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class='bx bxs-image text-muted'></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $product['name']; ?></td>
                                        <td><?php echo $product['category_name'] ?? 'Uncategorized'; ?></td>
                                        <td><?php echo formatPrice($product['price']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $product['stock_quantity'] < 10 ? 'danger' : 'success'; ?>">
                                                <?php echo $product['stock_quantity']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editProductModal<?php echo $product['product_id']; ?>">
                                                <i class='bx bx-edit'></i>
                                            </button>
                                            <a href="?delete=<?php echo $product['product_id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class='bx bx-trash'></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Product Modal -->
                                    <div class="modal fade" id="editProductModal<?php echo $product['product_id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Product</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="product_actions.php" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                        
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Product Name</label>
                                                                <input type="text" class="form-control" name="name" value="<?php echo $product['name']; ?>" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Category</label>
                                                                <select class="form-select" name="category_id">
                                                                    <option value="">Select Category</option>
                                                                    <?php 
                                                                    $categories->data_seek(0);
                                                                    while($category = $categories->fetch_assoc()): 
                                                                    ?>
                                                                    <option value="<?php echo $category['category_id']; ?>" 
                                                                            <?php echo $category['category_id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                                                        <?php echo $category['name']; ?>
                                                                    </option>
                                                                    <?php endwhile; ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Price</label>
                                                                <input type="number" class="form-control" name="price" value="<?php echo $product['price']; ?>" step="0.01" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Stock Quantity</label>
                                                                <input type="number" class="form-control" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>" required>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control" name="description" rows="3"><?php echo $product['description']; ?></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Product Image</label>
                                                            <?php if($product['image_url']): ?>
                                                                <div class="mb-2">
                                                                    <img src="<?php echo getImageUrl($product['image_url']); ?>" class="img-thumbnail" style="max-width: 200px;">
                                                                </div>
                                                            <?php endif; ?>
                                                            <input type="file" class="form-control" name="image">
                                                            <small class="text-muted">Leave empty to keep current image</small>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select class="form-select" id="status" name="status" required>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                                                <label class="form-check-label" for="is_featured">
                                                                    Featured Product
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
                                    <a class="page-link" href="?page=<?php echo $page-1; ?>">Previous</a>
                                </li>
                                <?php endif; ?>

                                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>

                                <?php if($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a>
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

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="product_actions.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category_id">
                                    <option value="">Select Category</option>
                                    <?php 
                                    $categories->data_seek(0);
                                    while($category = $categories->fetch_assoc()): 
                                    ?>
                                    <option value="<?php echo $category['category_id']; ?>">
                                        <?php echo $category['name']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Price</label>
                                <input type="number" class="form-control" name="price" step="0.01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stock_quantity" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="image">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                <label class="form-check-label" for="is_featured">
                                    Featured Product
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 