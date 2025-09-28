<?php
// get_orders.php
require_once 'config.php';

header("Content-Type: application/json");

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Log the request for debugging
error_log("get_orders.php called for user_id: " . $_SESSION['user_id']);

try {
    // First get the orders
    $stmt = $conn->prepare("
        SELECT 
            o.id,
            o.order_number,
            o.order_date,
            o.status,
            o.total_amount,
            o.payment_method,
            o.payment_status,
            o.shipping_address,
            o.billing_address,
            o.created_at,
            o.updated_at
        FROM orders o
        WHERE o.user_id = ?
        ORDER BY o.order_date DESC
    ");
    
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log the number of orders found
    error_log("Number of orders found: " . count($orders));
    
    // Now get items for each order
    $stmt = $conn->prepare("
        SELECT 
            oi.order_id,
            oi.product_id,
            oi.product_name,
            oi.quantity,
            oi.price,
            oi.size,
            oi.color,
            oi.image
        FROM order_items oi
        WHERE oi.order_id = ?
    ");
    
    foreach ($orders as &$order) {
        $stmt->execute([$order['id']]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $order['items'] = $items;
        $order['shipping_address'] = json_decode($order['shipping_address'], true);
        $order['billing_address'] = json_decode($order['billing_address'], true);
        
        // Log each order for debugging
        error_log("Order #" . $order['order_number'] . " (length: " . strlen($order['order_number']) . ") with " . count($order['items']) . " items");
    }
    
    echo json_encode([
        'status' => 'success', 
        'orders' => $orders,
        'count' => count($orders)
    ]);
    
} catch (PDOException $e) {
    error_log("Get orders error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error', 
        'message' => 'Database error',
        'debug' => $e->getMessage()
    ]);
}
?>