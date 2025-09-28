<?php
// admin_analytics.php
require_once 'config.php';

header("Content-Type: application/json");

// Check admin authentication
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Admin authentication required']);
    exit();
}

try {
    $analytics = [];

    // Total orders
    $stmt = $conn->query("SELECT COUNT(*) as total_orders FROM orders");
    $analytics['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

    // Orders by status
    $stmt = $conn->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
    $analytics['orders_by_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Total revenue
    $stmt = $conn->query("SELECT SUM(total_amount) as total_revenue FROM orders WHERE payment_status = 'paid'");
    $analytics['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'] ?? 0;

    // Total customers
    $stmt = $conn->query("SELECT COUNT(DISTINCT user_id) as total_customers FROM orders");
    $analytics['total_customers'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_customers'];

    // Recent orders (last 7 days)
    $stmt = $conn->prepare("
        SELECT COUNT(*) as recent_orders
        FROM orders
        WHERE order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ");
    $stmt->execute();
    $analytics['recent_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['recent_orders'];

    // Revenue last 7 days
    $stmt = $conn->prepare("
        SELECT SUM(total_amount) as recent_revenue
        FROM orders
        WHERE order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND payment_status = 'paid'
    ");
    $stmt->execute();
    $analytics['recent_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['recent_revenue'] ?? 0;

    // Cancellations
    $stmt = $conn->query("SELECT COUNT(*) as cancellations FROM orders WHERE status = 'cancelled'");
    $analytics['cancellations'] = $stmt->fetch(PDO::FETCH_ASSOC)['cancellations'];

    // Recent cancellations (last 7 days)
    $stmt = $conn->prepare("
        SELECT COUNT(*) as recent_cancellations
        FROM orders
        WHERE status = 'cancelled' AND order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ");
    $stmt->execute();
    $analytics['recent_cancellations'] = $stmt->fetch(PDO::FETCH_ASSOC)['recent_cancellations'];

    // Top products
    $stmt = $conn->query("
        SELECT
            p.title,
            SUM(oi.quantity) as total_sold,
            SUM(oi.price * oi.quantity) as total_revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN orders o ON oi.order_id = o.id
        WHERE o.status != 'cancelled'
        GROUP BY p.id, p.title
        ORDER BY total_sold DESC
        LIMIT 5
    ");
    $analytics['top_products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Monthly revenue for chart
    $stmt = $conn->query("
        SELECT
            DATE_FORMAT(order_date, '%Y-%m') as month,
            SUM(total_amount) as revenue,
            COUNT(*) as orders_count
        FROM orders
        WHERE payment_status = 'paid' AND order_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(order_date, '%Y-%m')
        ORDER BY month DESC
    ");
    $analytics['monthly_revenue'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'analytics' => $analytics
    ]);

} catch (Exception $e) {
    error_log("Admin analytics error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>