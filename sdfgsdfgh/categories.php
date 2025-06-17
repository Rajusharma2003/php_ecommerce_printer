<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get all active categories
$sql = "SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC";
$categories = $conn->query($sql);

$pageTitle = "All Categories";
include 'includes/header.php';

// Reset the result set pointer after header.php might have used it
if ($categories) {
    $categories->data_seek(0);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Categories - ShopNow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
  
    <div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">All Product Categories</h2>
    <div class="row g-4 justify-content-center">
        <?php
        if ($categories && $categories->num_rows > 0) {
            while($category = $categories->fetch_assoc()) {
                ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="category.php?id=<?php echo $category['category_id']; ?>" class="text-decoration-none">
                        <div class="card h-100 category-card shadow-sm">
                            <?php if($category['image_url']): ?>
                                <img src="<?php echo getImageUrl($category['image_url']); ?>" 
                                     class="card-img-top category-image" 
                                     alt="<?php echo htmlspecialchars($category['name']); ?>"
                                     onerror="this.src='assets/images/placeholder.jpg'">
                            <?php else: ?>
                                <div class="category-image-placeholder d-flex align-items-center justify-content-center h-100 bg-light">
                                    <i class='bx bx-category' style="font-size: 4rem; color: #6c757d;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <h5 class="card-title text-dark mb-0"><?php echo htmlspecialchars($category['name']); ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12 text-center"><p class="text-muted">No categories found.</p></div>';
        }
        ?>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

<?php include 'includes/footer.php'; ?> 