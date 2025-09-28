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
$status_filter = $_GET['status'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;

// Build query conditions
$conditions = [];
$params = [];

if (!empty($search)) {
    $conditions[] = "(o.order_number LIKE ? OR u.name LIKE ? OR u.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($status_filter)) {
    $conditions[] = "o.status = ?";
    $params[] = $status_filter;
}

if (!empty($date_from)) {
    $conditions[] = "o.order_date >= ?";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $conditions[] = "o.order_date <= ?";
    $params[] = $date_to . ' 23:59:59';
}

$where_clause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM orders o JOIN users u ON o.user_id = u.id $where_clause";
$stmt = $conn->prepare($count_query);
$stmt->execute($params);
$total_orders = $stmt->fetch()['total'];
$total_pages = ceil($total_orders / $per_page);

// Get orders with pagination
$offset = ($page - 1) * $per_page;
$query = "SELECT o.*, u.name as customer_name, u.email as customer_email
          FROM orders o
          JOIN users u ON o.user_id = u.id
          $where_clause
          ORDER BY
            CASE
                WHEN o.status = 'pending' THEN 1
                WHEN o.status = 'processing' THEN 2
                WHEN o.status = 'shipped' THEN 3
                WHEN o.status = 'delivered' THEN 4
                WHEN o.status = 'cancelled' THEN 5
            END,
            o.order_date DESC
          LIMIT $per_page OFFSET $offset";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();

// Process status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Update order status
    $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    // Redirect to refresh page
    header("Location: admin-orders.php?page=$page&updated=true");
    exit();
}

// Process order deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    // Delete order items first
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->execute([$order_id]);

    // Then delete order
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);

    // Redirect to refresh page
    header("Location: admin-orders.php?page=$page&deleted=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Admin Orders</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

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

        .admin-sidebar.hidden {
            transform: translateX(-100%);
        }

        .admin-main {
            flex: 1;
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

        .hamburger-menu:hover {
            background: #34495e;
        }

        /* Tablet and Mobile Responsive Design */
        @media (max-width: 1200px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .hamburger-menu {
                display: block;
            }

            .admin-header h1 {
                margin-left: 50px;
                font-size: 1.5rem;
            }

            .admin-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .admin-actions {
                align-self: flex-end;
            }

            .filters-section {
                margin-bottom: 20px;
            }

            .filters-form {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }


        @media (max-width: 768px) {
            .admin-main {
                padding: 15px;
            }

            .admin-header h1 {
                font-size: 1.3rem;
                margin-left: 40px;
            }

            .admin-header {
                padding: 15px;
                margin-bottom: 20px;
            }

            .filters-section {
                padding: 15px;
            }

            .filters-form {
                gap: 10px;
            }

            .form-group input,
            .form-group select {
                padding: 10px;
                font-size: 16px; /* Prevents zoom on iOS */
            }

            .admin-table {
                font-size: 0.85rem;
            }

            .status-badge {
                font-size: 0.75rem;
                padding: 3px 6px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
                min-width: 80px;
            }

            .hamburger-menu {
                top: 10px;
                left: 10px;
                padding: 8px;
                font-size: 16px;
            }

            .pagination {
                gap: 5px;
            }

            .pagination a,
            .pagination span {
                padding: 6px 10px;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .admin-main {
                padding: 10px;
            }

            .admin-header h1 {
                font-size: 1.2rem;
                margin-left: 35px;
            }

            .admin-header {
                padding: 10px;
            }

            .filters-section {
                padding: 12px;
            }

            .admin-table {
                font-size: 0.8rem;
            }

            .btn {
                padding: 6px 12px;
                font-size: 0.85rem;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 2px 4px;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .pagination a,
            .pagination span {
                padding: 5px 8px;
                font-size: 0.8rem;
            }
        }

        .admin-sidebar h2 {
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .admin-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .admin-sidebar li {
            margin-bottom: 10px;
        }

        .admin-sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .admin-sidebar a:hover, .admin-sidebar a.active {
            background: #34495e;
        }

        .admin-main {
            flex: 1;
            padding: 20px;
            background: #f5f5f5;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .admin-table th, .admin-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .admin-table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .status-select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .btn-danger {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #e2d9f3; color: #4c2889; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }

        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .filters-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .filters-form .form-group {
            margin-bottom: 0;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 10px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }

        .pagination a:hover, .pagination .current {
            background: #d4a762;
            color: white;
            border-color: #d4a762;
        }

        /* Orders Slider Container */
        .orders-slider-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background: white;
        }

        .orders-slider-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .orders-slider-wrapper::-webkit-scrollbar {
            display: none;
        }

        /* Enable horizontal scroll below 1050px */
        @media (max-width: 1050px) {
            .orders-slider-container {
                margin: 0 -10px;
                border-radius: 0;
                box-shadow: none;
            }

            .orders-slider-wrapper {
                padding: 0 10px;
            }

            .admin-table {
                min-width: 800px; /* Ensure table doesn't shrink too much */
                margin: 0;
            }

            .admin-table th,
            .admin-table td {
                white-space: nowrap;
                padding: 10px 12px;
                min-width: 100px;
            }

            .admin-table th:nth-child(1),
            .admin-table td:nth-child(1) {
                min-width: 120px; /* Order # column */
                position: sticky;
                left: 0;
                background: white;
                z-index: 2;
                box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            }

            .admin-table th:nth-child(1) {
                background: #f8f9fa;
            }

            .admin-table th:nth-child(2),
            .admin-table td:nth-child(2) {
                min-width: 150px; /* Customer name column */
            }

            .admin-table th:nth-child(3),
            .admin-table td:nth-child(3) {
                min-width: 180px; /* Email column */
            }
        }

        @media (max-width: 768px) {
            .orders-slider-container {
                margin: 0 -15px;
            }

            .orders-slider-wrapper {
                padding: 0 15px;
            }

            .admin-table {
                font-size: 0.85rem;
            }

            .admin-table th,
            .admin-table td {
                padding: 8px 10px;
                min-width: 90px;
            }

            .admin-table th:nth-child(1),
            .admin-table td:nth-child(1) {
                min-width: 100px;
            }

            .admin-table th:nth-child(2),
            .admin-table td:nth-child(2) {
                min-width: 130px;
            }

            .admin-table th:nth-child(3),
            .admin-table td:nth-child(3) {
                min-width: 160px;
            }
        }

        @media (max-width: 480px) {
            .orders-slider-container {
                margin: 0 -10px;
            }

            .orders-slider-wrapper {
                padding: 0 10px;
            }

            .admin-table {
                font-size: 0.8rem;
            }

            .admin-table th,
            .admin-table td {
                padding: 6px 8px;
                min-width: 80px;
            }

            .admin-table th:nth-child(1),
            .admin-table td:nth-child(1) {
                min-width: 90px;
            }

            .admin-table th:nth-child(2),
            .admin-table td:nth-child(2) {
                min-width: 110px;
            }

            .admin-table th:nth-child(3),
            .admin-table td:nth-child(3) {
                min-width: 140px;
            }
        }
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
                <li><a href="admin-orders.php" class="active"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                <li><a href="admin-products.php"><i class="fas fa-tshirt"></i> Products</a></li>
                <li><a href="admin-users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>Order Management</h1>
                <div class="admin-actions">
                    <button class="btn btn-primary" id="refreshBtn"><i class="fas fa-sync"></i> Refresh</button>
                </div>
            </div>

            <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
                <div class="alert alert-success">
                    Order status updated successfully.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
                <div class="alert alert-success">
                    Order deleted successfully.
                </div>
            <?php endif; ?>

            <!-- Filters Section -->
            <div class="filters-section">
                <h3>Search & Filter Orders</h3>
                <form method="GET" class="filters-form">
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Order #, Customer name, Email">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo $status_filter == 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="shipped" <?php echo $status_filter == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="delivered" <?php echo $status_filter == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_from">From Date</label>
                        <input type="date" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                    </div>
                    <div class="form-group">
                        <label for="date_to">To Date</label>
                        <input type="date" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="admin-orders.php" class="btn btn-secondary" style="margin-left: 10px;">Clear</a>
                    </div>
                </form>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                                <td><?php echo date('M j, Y H:i', strtotime($order['order_date'])); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['payment_status']; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="admin-order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline">View</a>
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
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>">&laquo; Previous</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div style="margin-top: 20px; color: #666;">
                Showing <?php echo ($offset + 1) . ' - ' . min($offset + $per_page, $total_orders); ?> of <?php echo $total_orders; ?> orders
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