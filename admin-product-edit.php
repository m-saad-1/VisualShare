<?php
session_start();
require_once 'api/db_connection.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$is_new = $product_id == 0;

// Get product data if editing
$product = null;
if (!$is_new) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: admin-products.php");
        exit();
    }
}

// Handle form submission
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $old_price = !empty($_POST['old_price']) ? floatval($_POST['old_price']) : null;
    $image = trim($_POST['image']);
    $colors = json_encode(array_map('trim', explode(',', $_POST['colors'])));
    $sizes = json_encode(array_map('trim', explode(',', $_POST['sizes'])));
    $rating = floatval($_POST['rating']);
    $reviews = intval($_POST['reviews']);
    $badge = trim($_POST['badge']);
    $featured = isset($_POST['featured']) ? 1 : 0;
    $new_arrival = isset($_POST['new_arrival']) ? 1 : 0;
    $sku = trim($_POST['sku']);
    $description = trim($_POST['description']);
    $features = json_encode(array_map('trim', explode("\n", $_POST['features'])));
    $color_codes = json_encode(array_map('trim', explode(',', $_POST['color_codes'])));

    try {
        if ($is_new) {
            // Insert new product
            $stmt = $conn->prepare("INSERT INTO products (title, category, price, old_price, image, colors, sizes, rating, reviews, badge, featured, new_arrival, sku, description, features, color_codes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $category, $price, $old_price, $image, $colors, $sizes, $rating, $reviews, $badge, $featured, $new_arrival, $sku, $description, $features, $color_codes]);
            $message = "Product created successfully!";
            $product_id = $conn->lastInsertId();
        } else {
            // Update existing product
            $stmt = $conn->prepare("UPDATE products SET title = ?, category = ?, price = ?, old_price = ?, image = ?, colors = ?, sizes = ?, rating = ?, reviews = ?, badge = ?, featured = ?, new_arrival = ?, sku = ?, description = ?, features = ?, color_codes = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$title, $category, $price, $old_price, $image, $colors, $sizes, $rating, $reviews, $badge, $featured, $new_arrival, $sku, $description, $features, $color_codes, $product_id]);
            $message = "Product updated successfully!";
        }
        $message_type = "success";

        // Refresh product data
        if (!$is_new) {
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

// Set default values for new product
if ($is_new) {
    $product = [
        'title' => '',
        'category' => '',
        'price' => '',
        'old_price' => '',
        'image' => '',
        'colors' => '["black", "white"]',
        'sizes' => '["S", "M", "L", "XL"]',
        'rating' => 0,
        'reviews' => 0,
        'badge' => '',
        'featured' => 0,
        'new_arrival' => 0,
        'sku' => '',
        'description' => '',
        'features' => '["Feature 1", "Feature 2"]',
        'color_codes' => '{"black": "#000000", "white": "#ffffff"}'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - <?php echo $is_new ? 'Add' : 'Edit'; ?> Product</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .admin-sidebar h2 { color: white; margin-bottom: 30px; text-align: center; }
        .admin-sidebar ul { list-style: none; padding: 0; }
        .admin-sidebar li { margin-bottom: 10px; }
        .admin-sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 10px; border-radius: 4px; transition: background 0.3s; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: #34495e; }
        .admin-main {
            flex: 1;
            padding: 20px;
            background: #f5f5f5;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        .hamburger-menu {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }
        .hamburger-menu:hover { background: #34495e; }
        /* Tablet and Mobile Responsive Design */
        @media (max-width: 1200px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .hamburger-menu { display: block; }
            .admin-header h1 { margin-left: 50px; font-size: 1.5rem; }
            .admin-header { flex-direction: column; align-items: flex-start; gap: 15px; }
            .admin-actions { align-self: flex-end; }
            .form-container { padding: 25px; }
            .form-row { grid-template-columns: 1fr; gap: 15px; }
        }

        @media (max-width: 768px) {
            .admin-main { padding: 15px; }
            .admin-header h1 { font-size: 1.3rem; margin-left: 40px; }
            .admin-header { padding: 15px; margin-bottom: 20px; }
            .form-container { padding: 20px; }
            .form-row { gap: 12px; }
            .form-group input, .form-group select, .form-group textarea { padding: 10px; font-size: 16px; }
            .checkbox-group { flex-direction: column; align-items: flex-start; gap: 8px; }
            .checkbox-group input { margin: 0; }
            .hamburger-menu { top: 10px; left: 10px; padding: 8px; font-size: 16px; }
            .btn { padding: 10px 20px; font-size: 0.9rem; }
            .image-preview img { max-width: 150px; }
        }

        @media (max-width: 480px) {
            .admin-main { padding: 10px; }
            .admin-header h1 { font-size: 1.2rem; margin-left: 35px; }
            .admin-header { padding: 10px; }
            .form-container { padding: 15px; }
            .form-row { gap: 10px; }
            .form-group input, .form-group select, .form-group textarea { padding: 8px; font-size: 14px; }
            .form-group label { font-size: 0.9rem; margin-bottom: 6px; }
            .checkbox-group { gap: 6px; }
            .checkbox-group label { font-size: 0.9rem; }
            .hamburger-menu { top: 8px; left: 8px; padding: 6px; font-size: 14px; }
            .btn { padding: 8px 16px; font-size: 0.85rem; }
            .image-preview img { max-width: 120px; }
            .modal-content { width: 95%; max-width: 400px; padding: 15px; }
            .modal-content h2 { font-size: 1.2rem; }
        }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #d4a762; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
        .alert-error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; }
        .checkbox-group input { width: auto; margin: 0; }
    </style>
</head>
<body>
    <button class="hamburger-menu" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="admin-container">
        <aside class="admin-sidebar" id="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="admin-orders.php"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                <li><a href="admin-products.php" class="active"><i class="fas fa-tshirt"></i> Products</a></li>
                <li><a href="admin-users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1><?php echo $is_new ? 'Add New Product' : 'Edit Product'; ?></h1>
                <div class="admin-actions">
                    <a href="admin-products.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Products</a>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Product Title *</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="shirts" <?php echo $product['category'] == 'shirts' ? 'selected' : ''; ?>>Shirts</option>
                                <option value="jeans" <?php echo $product['category'] == 'jeans' ? 'selected' : ''; ?>>Jeans</option>
                                <option value="jackets" <?php echo $product['category'] == 'jackets' ? 'selected' : ''; ?>>Jackets</option>
                                <option value="dresses" <?php echo $product['category'] == 'dresses' ? 'selected' : ''; ?>>Dresses</option>
                                <option value="accessories" <?php echo $product['category'] == 'accessories' ? 'selected' : ''; ?>>Accessories</option>
                                <option value="shoes" <?php echo $product['category'] == 'shoes' ? 'selected' : ''; ?>>Shoes</option>
                                <option value="sweaters" <?php echo $product['category'] == 'sweaters' ? 'selected' : ''; ?>>Sweaters</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price *</label>
                            <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="old_price">Old Price (Optional)</label>
                            <input type="number" id="old_price" name="old_price" step="0.01" value="<?php echo $product['old_price']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image">Image URL *</label>
                        <input type="url" id="image" name="image" value="<?php echo htmlspecialchars($product['image']); ?>" required>
                        <div id="image-preview" style="margin-top: 10px; max-width: 200px; <?php echo empty($product['image']) ? 'display: none;' : ''; ?>">
                            <img id="preview-img" src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Preview" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="colors">Colors (comma-separated)</label>
                            <input type="text" id="colors" name="colors" value="<?php echo htmlspecialchars(str_replace(['[', ']', '"'], '', $product['colors'])); ?>" placeholder="black, white, blue">
                        </div>
                        <div class="form-group">
                            <label for="sizes">Sizes (comma-separated)</label>
                            <input type="text" id="sizes" name="sizes" value="<?php echo htmlspecialchars(str_replace(['[', ']', '"'], '', $product['sizes'])); ?>" placeholder="S, M, L, XL">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" value="<?php echo $product['rating']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="reviews">Reviews Count</label>
                            <input type="number" id="reviews" name="reviews" min="0" value="<?php echo $product['reviews']; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="badge">Badge</label>
                            <select id="badge" name="badge">
                                <option value="">No Badge</option>
                                <option value="sale" <?php echo $product['badge'] == 'sale' ? 'selected' : ''; ?>>Sale</option>
                                <option value="new" <?php echo $product['badge'] == 'new' ? 'selected' : ''; ?>>New</option>
                                <option value="best-seller" <?php echo $product['badge'] == 'best-seller' ? 'selected' : ''; ?>>Best Seller</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($product['sku']); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Product Status</label>
                            <select id="status" name="status">
                                <option value="active" <?php echo ($product['price'] > 0) ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($product['price'] <= 0) ? 'selected' : ''; ?>>Inactive</option>
                                <option value="draft">Draft</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="discontinued">Discontinued</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Product Flags</label>
                            <div style="display: flex; gap: 15px; margin-top: 8px;">
                                <label style="display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" id="featured" name="featured" value="1" <?php echo $product['featured'] ? 'checked' : ''; ?>>
                                    Featured
                                </label>
                                <label style="display: flex; align-items: center; gap: 5px;">
                                    <input type="checkbox" id="new_arrival" name="new_arrival" value="1" <?php echo $product['new_arrival'] ? 'checked' : ''; ?>>
                                    New Arrival
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="features">Features (one per line)</label>
                        <textarea id="features" name="features"><?php echo htmlspecialchars(str_replace(['[', ']', '"'], '', $product['features'])); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="color_codes">Color Codes (JSON format)</label>
                        <textarea id="color_codes" name="color_codes"><?php echo htmlspecialchars($product['color_codes']); ?></textarea>
                    </div>

                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 16px;">
                            <i class="fas fa-save"></i> <?php echo $is_new ? 'Create Product' : 'Update Product'; ?>
                        </button>
                        <a href="admin-products.php" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.querySelector('.hamburger-menu');

            if (window.innerWidth <= 1200) {
                if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Image preview functionality
        document.getElementById('image').addEventListener('input', function() {
            const imageUrl = this.value.trim();
            const previewDiv = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            if (imageUrl) {
                previewImg.src = imageUrl;
                previewImg.onerror = function() {
                    previewDiv.style.display = 'none';
                    alert('Invalid image URL. Please enter a valid image URL.');
                };
                previewImg.onload = function() {
                    previewDiv.style.display = 'block';
                };
            } else {
                previewDiv.style.display = 'none';
            }
        });

        // Handle status changes
        document.getElementById('status').addEventListener('change', function() {
            const status = this.value;
            const priceInput = document.getElementById('price');

            if (status === 'inactive') {
                if (priceInput.value === '' || parseFloat(priceInput.value) > 0) {
                    priceInput.value = '0';
                }
            }
        });
    </script>
</body>
</html>