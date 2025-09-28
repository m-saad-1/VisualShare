<?php
// admin_order_details.php
require_once 'config.php';

header("Content-Type: application/json");

// Check admin authentication
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Admin authentication required']);
    exit();
}

$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    echo json_encode(['status' => 'error', 'message' => 'Order ID is required']);
    exit();
}

try {
    // Get order details
    $stmt = $conn->prepare("
        SELECT
            o.*,
            u.name as customer_name,
            u.email as customer_email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ?
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception("Order not found");
    }

    // Get order items
    $stmt = $conn->prepare("
        SELECT
            oi.*,
            p.title as product_title,
            p.image as product_image
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format addresses
    $order['shipping_address'] = json_decode($order['shipping_address'], true);
    $order['billing_address'] = json_decode($order['billing_address'], true);

    echo json_encode([
        'status' => 'success',
        'order' => $order,
        'items' => $items
    ]);

} catch (Exception $e) {
    error_log("Admin order details error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>