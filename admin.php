<?php
session_start();
require_once 'api/db_connection.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get dashboard statistics
try {
    // Total orders
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM orders");
    $stmt->execute();
    $total_orders = $stmt->fetch()['total'];

    // Total revenue
    $stmt = $conn->prepare("SELECT SUM(total_amount) as revenue FROM orders WHERE payment_status = 'paid'");
    $stmt->execute();
    $total_revenue = $stmt->fetch()['revenue'] ?? 0;

    // Total products
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products");
    $stmt->execute();
    $total_products = $stmt->fetch()['total'];

    // Total users
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $total_users = $stmt->fetch()['total'];

    // Recent orders (last 10)
    $stmt = $conn->prepare("SELECT o.*, u.name as customer_name
                           FROM orders o
                           JOIN users u ON o.user_id = u.id
                           ORDER BY o.order_date DESC LIMIT 10");
    $stmt->execute();
    $recent_orders = $stmt->fetchAll();

    // Order status distribution
    $stmt = $conn->prepare("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
    $stmt->execute();
    $status_counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Top selling products
    $stmt = $conn->prepare("SELECT p.title, SUM(oi.quantity) as total_sold
                           FROM order_items oi
                           JOIN products p ON oi.product_id = p.id
                           JOIN orders o ON oi.order_id = o.id
                           WHERE o.payment_status = 'paid'
                           GROUP BY p.id, p.title
                           ORDER BY total_sold DESC LIMIT 5");
    $stmt->execute();
    $top_products = $stmt->fetchAll();

    // Monthly revenue for chart
    $stmt = $conn->prepare("SELECT DATE_FORMAT(order_date, '%Y-%m') as month,
                           SUM(total_amount) as revenue
                           FROM orders
                           WHERE payment_status = 'paid'
                           AND order_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                           GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                           ORDER BY month");
    $stmt->execute();
    $monthly_revenue = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $total_orders = $total_revenue = $total_products = $total_users = 0;
    $recent_orders = $status_counts = $top_products = $monthly_revenue = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Admin Panel</title>
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
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 0;
            }

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

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .stat-card {
                padding: 15px;
                text-align: center;
            }

            .stat-number {
                font-size: 1.8rem;
            }

            .quick-actions {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .action-card {
                padding: 20px;
            }

            .action-card h3 {
                font-size: 1.1rem;
            }

            .recent-orders table {
                font-size: 0.85rem;
            }

            .recent-orders th,
            .recent-orders td {
                padding: 8px 4px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }

            .hamburger-menu {
                top: 10px;
                left: 10px;
                padding: 8px;
                font-size: 16px;
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

            .stat-card {
                padding: 12px;
            }

            .stat-number {
                font-size: 1.5rem;
            }

            .action-card {
                padding: 15px;
            }

            .action-card h3 {
                font-size: 1rem;
            }

            .recent-orders {
                overflow-x: auto;
            }

            .recent-orders table {
                min-width: 600px;
                font-size: 0.8rem;
            }

            .btn {
                padding: 6px 12px;
                font-size: 0.85rem;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #d4a762;
            margin-bottom: 10px;
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
        
        .old-order-note {
            color: #f44336;
            font-size: 0.8em;
        }

        /* Dashboard Styles */
        .dashboard-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .dashboard-section h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 1.2rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .stat-icon {
            font-size: 1.5rem;
            margin-top: 10px;
            opacity: 0.7;
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

        .view-all {
            text-align: center;
            margin-top: 15px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .action-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #333;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .action-card i {
            font-size: 2rem;
            color: #d4a762;
            margin-bottom: 10px;
        }

        .action-card span {
            font-weight: 500;
            text-align: center;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
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
                <li><a href="admin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="admin-orders.php"><i class="fas fa-shopping-bag"></i> Orders</a></li>
                <li><a href="admin-products.php"><i class="fas fa-tshirt"></i> Products</a></li>
                <li><a href="admin-users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-actions">
                    <button class="btn btn-primary" id="refreshBtn"><i class="fas fa-sync"></i> Refresh</button>
                </div>
            </div>

            <!-- Overview Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($total_orders); ?></div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">$<?php echo number_format($total_revenue, 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($total_products); ?></div>
                    <div class="stat-label">Total Products</div>
                    <div class="stat-icon"><i class="fas fa-tshirt"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($total_users); ?></div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                </div>
            </div>

            <!-- Order Status Overview -->
            <div class="dashboard-section">
                <h2>Order Status Overview</h2>
                <div class="stats-grid">
                    <?php
                    $status_labels = [
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled'
                    ];

                    $status_colors = [
                        'pending' => '#ff9800',
                        'processing' => '#2196f3',
                        'shipped' => '#673ab7',
                        'delivered' => '#4caf50',
                        'cancelled' => '#f44336'
                    ];

                    foreach ($status_labels as $status => $label):
                        $count = $status_counts[$status] ?? 0;
                    ?>
                        <div class="stat-card">
                            <div class="stat-number" style="color: <?php echo $status_colors[$status]; ?>">
                                <?php echo $count; ?>
                            </div>
                            <div class="stat-label"><?php echo $label; ?> Orders</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="dashboard-section">
                <h2>Recent Orders</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($recent_orders, 0, 5) as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($order['order_date'])); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="view-all">
                    <a href="admin-orders.php" class="btn btn-primary">View All Orders</a>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="dashboard-section">
                <h2>Top Selling Products</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Units Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['title']); ?></td>
                                <td><?php echo $product['total_sold']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="view-all">
                    <a href="admin-products.php" class="btn btn-primary">View All Products</a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-section">
                <h2>Quick Actions</h2>
                <div class="quick-actions">
                    <a href="admin-orders.php" class="action-card">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Manage Orders</span>
                    </a>
                    <a href="admin-products.php" class="action-card">
                        <i class="fas fa-tshirt"></i>
                        <span>Manage Products</span>
                    </a>
                    <a href="admin-users.php" class="action-card">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="admin-product-edit.php" class="action-card">
                        <i class="fas fa-plus"></i>
                        <span>Add Product</span>
                    </a>
                </div>
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

        // Automatic removal of cancelled orders after 24 hours
        function checkOldCancelledOrders() {
            const cancelledOrders = document.querySelectorAll('tr td select[value="cancelled"]');
            cancelledOrders.forEach(select => {
                const orderRow = select.closest('tr');
                const orderDateText = orderRow.querySelector('td:nth-child(3)').textContent;
                const orderDate = new Date(orderDateText);
                const now = new Date();
                const hoursDiff = Math.abs(now - orderDate) / 36e5;

                if (hoursDiff > 24) {
                    // Add a note that this order will be removed soon
                    if (!orderRow.querySelector('.old-order-note')) {
                        const note = document.createElement('span');
                        note.className = 'old-order-note';
                        note.textContent = ' (Scheduled for removal)';
                        orderRow.querySelector('td:nth-child(1)').appendChild(note);
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', checkOldCancelledOrders);
    </script>
</body>
</html>