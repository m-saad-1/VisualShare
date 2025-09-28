<?php
session_start();
require_once 'api/db_connection.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle search and filtering
$search = $_GET['search'] ?? '';
$category_filter = $_GET['category'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 15;

// Handle CRUD operations
$message = '';
$message_type = '';

// Add/Edit Product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product']) || isset($_POST['edit_product'])) {
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        $price = floatval($_POST['price']);
        $old_price = !empty($_POST['old_price']) ? floatval($_POST['old_price']) : null;
        $description = trim($_POST['description']);
        $sku = trim($_POST['sku']);
        $colors = json_encode(array_map('trim', explode(',', $_POST['colors'])));
        $sizes = json_encode(array_map('trim', explode(',', $_POST['sizes'])));
        $featured = isset($_POST['featured']) ? 1 : 0;
        $new_arrival = isset($_POST['new_arrival']) ? 1 : 0;
        $image = trim($_POST['image']);

        if (isset($_POST['add_product'])) {
            // Add new product
            $stmt = $conn->prepare("INSERT INTO products (title, category, price, old_price, image, colors, sizes, description, sku, featured, new_arrival, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([$title, $category, $price, $old_price, $image, $colors, $sizes, $description, $sku, $featured, $new_arrival]);
            $message = "Product added successfully!";
            $message_type = "success";
        } else {
            // Edit existing product
            $product_id = intval($_POST['product_id']);
            $stmt = $conn->prepare("UPDATE products SET title=?, category=?, price=?, old_price=?, image=?, colors=?, sizes=?, description=?, sku=?, featured=?, new_arrival=?, updated_at=NOW() WHERE id=?");
            $stmt->execute([$title, $category, $price, $old_price, $image, $colors, $sizes, $description, $sku, $featured, $new_arrival, $product_id]);
            $message = "Product updated successfully!";
            $message_type = "success";
        }
    }

    // Delete product
    if (isset($_POST['delete_product'])) {
        $product_id = intval($_POST['product_id']);
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $message = "Product deleted successfully!";
        $message_type = "success";
    }

    // Bulk delete
    if (isset($_POST['bulk_delete'])) {
        $product_ids = $_POST['product_ids'] ?? [];
        if (!empty($product_ids)) {
            $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
            $stmt = $conn->prepare("DELETE FROM products WHERE id IN ($placeholders)");
            $stmt->execute($product_ids);
            $message = count($product_ids) . " products deleted successfully!";
            $message_type = "success";
        }
    }
}

// Build query conditions
$conditions = [];
$params = [];

if (!empty($search)) {
    $conditions[] = "(title LIKE ? OR description LIKE ? OR sku LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category_filter)) {
    $conditions[] = "category = ?";
    $params[] = $category_filter;
}

$where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM products $where_clause";
$stmt = $conn->prepare($count_query);
$stmt->execute($params);
$total_products = $stmt->fetch()['total'];
$total_pages = ceil($total_products / $per_page);

// Get products with pagination
$offset = ($page - 1) * $per_page;
$query = "SELECT * FROM products $where_clause ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories for filter dropdown
$stmt = $conn->prepare("SELECT DISTINCT category FROM products ORDER BY category");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get product for editing
$edit_product = null;
if (isset($_GET['edit'])) {
    $product_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $edit_product = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Admin Products</title>
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
            .filters-section { margin-bottom: 20px; }
            .filters-form { grid-template-columns: 1fr; gap: 12px; }
            .bulk-actions { flex-direction: column; align-items: stretch; gap: 10px; }
            .bulk-actions label { margin: 0; }
            .bulk-actions .btn { align-self: flex-start; }
        }

        @media (max-width: 768px) {
            .admin-main { padding: 15px; }
            .admin-header h1 { font-size: 1.3rem; margin-left: 40px; }
            .admin-header { padding: 15px; margin-bottom: 20px; }
            .filters-section { padding: 15px; }
            .filters-form { gap: 10px; }
            .form-group input, .form-group select { padding: 10px; font-size: 16px; }
            .bulk-actions { padding: 15px; margin-bottom: 15px; }
            .admin-table { font-size: 0.85rem; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .admin-table th, .admin-table td { padding: 8px 6px; white-space: nowrap; }
            .admin-table th { font-size: 0.8rem; }
            .product-image { width: 50px; height: 50px; }
            .featured-badge, .new-badge { font-size: 0.7rem; padding: 1px 4px; }
            .status-badge { font-size: 0.75rem; padding: 3px 6px; }
            .btn { padding: 6px 12px; font-size: 0.85rem; min-width: 70px; }
            .hamburger-menu { top: 10px; left: 10px; padding: 8px; font-size: 16px; }
            .pagination { gap: 5px; }
            .pagination a, .pagination span { padding: 6px 10px; font-size: 0.85rem; }
        }

        @media (max-width: 480px) {
            .admin-main { padding: 10px; }
            .admin-header h1 { font-size: 1.2rem; margin-left: 35px; }
            .admin-header { padding: 10px; }
            .filters-section { padding: 12px; }
            .bulk-actions { padding: 12px; }
            .admin-table { font-size: 0.8rem; }
            .admin-table th, .admin-table td { padding: 6px 4px; }
            .product-image { width: 40px; height: 40px; }
            .btn { padding: 5px 10px; font-size: 0.8rem; }
            .status-badge { font-size: 0.7rem; padding: 2px 4px; }
            .pagination { flex-wrap: wrap; justify-content: center; }
            .pagination a, .pagination span { padding: 5px 8px; font-size: 0.8rem; }
            .admin-table th:nth-child(3), .admin-table td:nth-child(3) { max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .admin-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .admin-table th, .admin-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        .admin-table th { background: #f8f9fa; font-weight: 600; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
        .alert-error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #d4a762; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn:hover { opacity: 0.9; }
        .filters-section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .filters-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .product-image { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: white; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: black; }
        .pagination { display: flex; justify-content: center; align-items: center; margin-top: 20px; gap: 10px; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; }
        .pagination a:hover, .pagination .current { background: #d4a762; color: white; border-color: #d4a762; }
        .bulk-actions { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; display: flex; gap: 15px; align-items: center; }
        .checkbox-column { width: 40px; }
        .featured-badge { background: #ffc107; color: #000; padding: 2px 6px; border-radius: 3px; font-size: 0.8rem; }
        .new-badge { background: #28a745; color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.8rem; }
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
                <h1>Product Management</h1>
                <div class="admin-actions">
                    <a href="admin-product-edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
                    <button class="btn btn-secondary" id="refreshBtn"><i class="fas fa-sync"></i> Refresh</button>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Filters Section -->
            <div class="filters-section">
                <h3>Search & Filter Products</h3>
                <form method="GET" class="filters-form">
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Product name, description, SKU">
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $category_filter == $category ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="admin-products.php" class="btn btn-secondary" style="margin-left: 10px;">Clear</a>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <form method="POST" id="bulkForm">


                <table class="admin-table">
                    <thead>
                        <tr>

                            <th>Image</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>SKU</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>

                                <td>
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product" class="product-image">
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($product['title']); ?></strong>
                                    <?php if ($product['featured']): ?>
                                        <span class="featured-badge">Featured</span>
                                    <?php endif; ?>
                                    <?php if ($product['new_arrival']): ?>
                                        <span class="new-badge">New</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                <td>
                                    $<?php echo number_format($product['price'], 2); ?>
                                    <?php if ($product['old_price']): ?>
                                        <br><small style="color: #999; text-decoration: line-through;">$<?php echo number_format($product['old_price'], 2); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $product['price'] > 0 ? 'active' : 'inactive'; ?>">
                                        <?php echo $product['price'] > 0 ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="admin-product-edit.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">Edit</a>
                                    <form method="POST" style="display: inline-block; margin-left: 5px;">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" name="delete_product" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category_filter); ?>">&laquo; Previous</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category_filter); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category_filter); ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px; color: #666;">
                Showing <?php echo ($offset + 1) . ' - ' . min($offset + $per_page, $total_products); ?> of <?php echo $total_products; ?> products
            </div>
        </main>
    </div>


    <script>
        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });



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

    </script>
</body>
</html>