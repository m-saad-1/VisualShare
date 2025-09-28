<?php
session_start();
require_once 'api/db_connection.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get order ID from URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$order_id) {
    header("Location: admin-orders.php");
    exit();
}

// Get order details
$stmt = $conn->prepare("
    SELECT o.*, u.name as customer_name, u.email as customer_email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: admin-orders.php");
    exit();
}

// Get order items
$stmt = $conn->prepare("
    SELECT oi.*, p.title as product_name, p.image as product_image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

// Get shipping and billing addresses
$shipping_address = json_decode($order['shipping_address'], true);
$billing_address = json_decode($order['billing_address'], true);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    // Refresh order data
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    $message = "Order status updated successfully!";
    $message_type = "success";
}

// Handle order deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_order'])) {
    // Delete order items first
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->execute([$order_id]);

    // Then delete order
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);

    header("Location: admin-orders.php?deleted=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionHub - Order Details #<?php echo $order['order_number']; ?></title>
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
            .order-details { grid-template-columns: 1fr; gap: 20px; }
        }

        @media (max-width: 768px) {
            .admin-main { padding: 15px; }
            .admin-header h1 { font-size: 1.3rem; margin-left: 40px; }
            .admin-header { padding: 15px; margin-bottom: 20px; }
            .order-details { gap: 15px; }
            .order-summary, .customer-info, .order-items, .shipping-info { padding: 15px; }
            .order-item { flex-direction: column; align-items: flex-start; gap: 10px; }
            .item-image { width: 60px; height: 60px; align-self: center; }
            .item-details { width: 100%; }
            .item-price { align-self: flex-end; margin-top: 10px; }
            .address-block { margin-bottom: 12px; }
            .order-total { padding: 12px; }
            .btn { padding: 8px 16px; font-size: 0.9rem; }
            .hamburger-menu { top: 10px; left: 10px; padding: 8px; font-size: 16px; }
            .status-select { padding: 8px; font-size: 16px; }
        }

        @media (max-width: 480px) {
            .admin-main { padding: 10px; }
            .admin-header h1 { font-size: 1.2rem; margin-left: 35px; }
            .admin-header { padding: 10px; }
            .order-details { gap: 12px; }
            .order-summary, .customer-info, .order-items, .shipping-info { padding: 12px; }
            .order-item { gap: 8px; }
            .item-image { width: 50px; height: 50px; }
            .item-details { font-size: 0.9rem; }
            .item-price { font-size: 0.9rem; }
            .address-block { margin-bottom: 10px; }
            .address-block p { font-size: 0.9rem; margin: 1px 0; }
            .order-total { padding: 10px; }
            .order-total .total-row { font-size: 0.9rem; }
            .btn { padding: 6px 12px; font-size: 0.85rem; }
            .status-select { padding: 6px; font-size: 14px; }
            .hamburger-menu { top: 8px; left: 8px; padding: 6px; font-size: 14px; }
        }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #d4a762; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn:hover { opacity: 0.9; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
        .order-details { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px; }
        .order-summary, .customer-info, .order-items, .shipping-info { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .order-summary h3, .customer-info h3, .order-items h3, .shipping-info h3 { margin-bottom: 15px; color: #333; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500; text-transform: uppercase; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-shipped { background: #e2d9f3; color: #4c2889; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .payment-pending { background: #fff3cd; color: #856404; }
        .payment-paid { background: #d4edda; color: #155724; }
        .payment-failed { background: #f8d7da; color: #721c24; }
        .order-item { display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee; }
        .order-item:last-child { border-bottom: none; }
        .item-image { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; margin-right: 15px; }
        .item-details { flex: 1; }
        .item-price { font-weight: bold; color: #d4a762; }
        .address-block { margin-bottom: 15px; }
        .address-block h4 { margin-bottom: 8px; color: #666; }
        .address-block p { margin: 2px 0; }
        .order-total { background: #f8f9fa; padding: 15px; border-radius: 4px; margin-top: 15px; }
        .order-total .total-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .order-total .grand-total { font-size: 1.2rem; font-weight: bold; color: #d4a762; }
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
                <li><a href="admin-products.php"><i class="fas fa-tshirt"></i> Products</a></li>
                <li><a href="admin-users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="index.php"><i class="fas fa-home"></i> Back to Site</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>Order Details #<?php echo htmlspecialchars($order['order_number']); ?></h1>
                <div class="admin-actions">
                    <a href="admin-orders.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Orders</a>
                    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>

            <?php if (isset($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="order-details">
                <!-- Order Summary -->
                <div>
                    <div class="order-summary">
                        <h3>Order Summary</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <strong>Order Number:</strong><br>
                                <?php echo htmlspecialchars($order['order_number']); ?>
                            </div>
                            <div>
                                <strong>Order Date:</strong><br>
                                <?php echo date('M j, Y H:i', strtotime($order['order_date'])); ?>
                            </div>
                            <div>
                                <strong>Status:</strong><br>
                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                            <div>
                                <strong>Payment Status:</strong><br>
                                <span class="status-badge payment-<?php echo $order['payment_status']; ?>">
                                    <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Status Update Form -->
                        <form method="POST" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                            <h4>Update Order Status</h4>
                            <div style="display: flex; gap: 15px; align-items: center;">
                                <select name="status" class="status-select" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: white; font-size: 14px;">
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary" style="padding: 10px 20px;">Update Status</button>
                            </div>
                        </form>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items" style="margin-top: 30px;">
                        <h3>Order Items</h3>
                        <?php foreach ($order_items as $item): ?>
                            <div class="order-item">
                                <img src="<?php echo htmlspecialchars($item['product_image']); ?>" alt="Product" class="item-image">
                                <div class="item-details">
                                    <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                    <br>
                                    <small>Size: <?php echo htmlspecialchars($item['size']); ?> | Color: <?php echo htmlspecialchars($item['color']); ?></small>
                                    <br>
                                    <span>Quantity: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></span>
                                </div>
                                <div class="item-price">
                                    $<?php echo number_format($item['quantity'] * $item['price'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Order Total -->
                        <div class="order-total">
                            <div class="total-row">
                                <span>Subtotal:</span>
                                <span>$<?php echo number_format($order['subtotal_amount'] ?? $order['total_amount'], 2); ?></span>
                            </div>
                            <?php if (isset($order['payment_fee']) && $order['payment_fee'] > 0): ?>
                                <div class="total-row">
                                    <span>Payment Fee:</span>
                                    <span>$<?php echo number_format($order['payment_fee'], 2); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="total-row grand-total">
                                <span>Total:</span>
                                <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer & Shipping Info -->
                <div>
                    <!-- Customer Info -->
                    <div class="customer-info">
                        <h3>Customer Information</h3>
                        <div style="margin-bottom: 15px;">
                            <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($order['customer_email']); ?></small>
                        </div>
                        <div>
                            <strong>Payment Method:</strong><br>
                            <?php echo htmlspecialchars($order['payment_method']); ?>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="shipping-info" style="margin-top: 30px;">
                        <h3>Shipping & Billing</h3>

                        <div class="address-block">
                            <h4>Shipping Address</h4>
                            <?php if ($shipping_address): ?>
                                <p><strong><?php echo htmlspecialchars($shipping_address['name'] ?? 'N/A'); ?></strong></p>
                                <p><?php echo htmlspecialchars($shipping_address['street'] ?? $shipping_address['address'] ?? 'N/A'); ?></p>
                                <p><?php echo htmlspecialchars($shipping_address['city'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($shipping_address['state'] ?? 'N/A'); ?> <?php echo htmlspecialchars($shipping_address['zip'] ?? $shipping_address['postal_code'] ?? 'N/A'); ?></p>
                                <p><?php echo htmlspecialchars($shipping_address['country'] ?? 'N/A'); ?></p>
                            <?php else: ?>
                                <p>No shipping address provided</p>
                            <?php endif; ?>
                        </div>

                        <?php if ($billing_address): ?>
                            <div class="address-block">
                                <h4>Billing Address</h4>
                                <p><strong><?php echo htmlspecialchars($billing_address['name'] ?? 'N/A'); ?></strong></p>
                                <p><?php echo htmlspecialchars($billing_address['street'] ?? $billing_address['address'] ?? 'N/A'); ?></p>
                                <p><?php echo htmlspecialchars($billing_address['city'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($billing_address['state'] ?? 'N/A'); ?> <?php echo htmlspecialchars($billing_address['zip'] ?? $billing_address['postal_code'] ?? 'N/A'); ?></p>
                                <p><?php echo htmlspecialchars($billing_address['country'] ?? 'N/A'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Order Actions -->
                    <div class="order-items" style="margin-top: 30px;">
                        <h3>Order Actions</h3>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="mailto:<?php echo htmlspecialchars($order['customer_email']); ?>" class="btn btn-primary">
                                <i class="fas fa-envelope"></i> Contact Customer
                            </a>
                            <button onclick="window.print()" class="btn btn-secondary">
                                <i class="fas fa-print"></i> Print Invoice
                            </button>
                            <form method="POST" style="margin-top: 20px;" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                <button type="submit" name="delete_order" class="btn btn-danger" style="width: 100%;">
                                    <i class="fas fa-trash"></i> Delete Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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

        // Add print styles
        const printStyles = `
            @media print {
                .admin-sidebar, .admin-actions, .btn, form, .hamburger-menu { display: none !important; }
                .admin-main { padding: 0 !important; margin-left: 0 !important; }
                .admin-header { margin-bottom: 20px !important; }
            }
        `;

        const style = document.createElement('style');
        style.textContent = printStyles;
        document.head.appendChild(style);
    </script>
</body>
</html>