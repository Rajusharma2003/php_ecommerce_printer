<?php
ob_start();
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get category ID from URL
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get category details
$sql = "SELECT * FROM categories WHERE category_id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    header("Location: categories.php");
    exit();
}

// Get products in this category
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.category_id 
        WHERE p.category_id = ? AND p.status = 'active' 
        ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$products = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - ShopNow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .category-banner {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('<?php echo getImageUrl($category['image_url']); ?>');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>


    <!-- Category Banner -->
    <?php if ($category): ?>
    <div class="category-banner">
        <div class="container text-center">
            <h1 class="display-4 mb-3"><?php echo htmlspecialchars($category['name']); ?></h1>
            <?php if($category['description']): ?>
                <p class="lead"><?php echo htmlspecialchars($category['description']); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
        <div class="container text-center">
            <h1 class="display-4 mb-3">Category Not Found</h1>
            <p class="lead">The category you are looking for does not exist or is inactive.</p>
        </div>
    <?php endif; ?>

    <!-- Products Grid -->
    <div class="container mb-5">
        <div class="row g-4">
            <?php if($products->num_rows > 0): ?>
                <?php while($product = $products->fetch_assoc()): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 product-card">
                            <a href="product_details.php?id=<?php echo $product['product_id']; ?>">
                                <img src="<?php echo getImageUrl($product['image_url']); ?>" 
                                     class="card-img-top product-image" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0"><?php echo formatPrice($product['price']); ?></span>
                                    <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="lead text-muted">No products found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>