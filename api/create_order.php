<?php
// create_order.php
require_once 'config.php';

header("Content-Type: application/json");

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['items', 'total_amount', 'payment_method', 'shipping_address'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing required field: $field"]);
        exit();
    }
}

try {
    // Begin transaction
    $conn->beginTransaction();
    
    // Generate unique order number with consistent short format
    $random_part = strtoupper(substr(uniqid(), -7));
    $order_number = 'ORD-' . date('Ymd') . '-' . $random_part;
    
    // Create order
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, order_number, order_date, total_amount, payment_method, payment_status, shipping_address, billing_address, status)
        VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)
    ");
    
    $shipping_address_json = json_encode($input['shipping_address']);
    $billing_address_json = isset($input['billing_address']) ? json_encode($input['billing_address']) : $shipping_address_json;
    
    $stmt->execute([
        $_SESSION['user_id'],
        $order_number,
        $input['total_amount'],
        $input['payment_method'],
        $input['payment_status'] ?? 'pending',
        $shipping_address_json,
        $billing_address_json,
        $input['status'] ?? 'pending'
    ]);
    
    $order_id = $conn->lastInsertId();
    
    // Add order items
    $stmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, quantity, price, size, color, image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($input['items'] as $item) {
        $stmt->execute([
            $order_id,
            $item['id'],
            $item['title'],
            $item['quantity'],
            $item['price'],
            $item['size'] ?? null,
            $item['color'] ?? null,
            $item['image'] ?? null
        ]);
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Order created successfully',
        'order_id' => $order_id,
        'order_number' => $order_number
    ]);
    
} catch (PDOException $e) {
    // Rollback transaction on error
    $conn->rollBack();
    error_log("Create order error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>