<?php
// cancel_order.php
require_once 'config.php';

header("Content-Type: application/json");

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$order_id = $input['order_id'] ?? null;

if (!$order_id) {
    echo json_encode(['status' => 'error', 'message' => 'Order ID is required']);
    exit();
}

try {
    // First, verify the order belongs to the authenticated user and check its status
    $stmt = $conn->prepare("
        SELECT id, status, user_id
        FROM orders
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['status' => 'error', 'message' => 'Order not found or access denied']);
        exit();
    }

    // Check if order can be cancelled
    if ($order['status'] === 'shipped') {
        echo json_encode(['status' => 'error', 'message' => 'Cannot cancel order that has been shipped']);
        exit();
    }

    if ($order['status'] === 'delivered') {
        echo json_encode(['status' => 'error', 'message' => 'Cannot cancel order that has been delivered']);
        exit();
    }

    if ($order['status'] === 'cancelled') {
        echo json_encode(['status' => 'error', 'message' => 'Order is already cancelled']);
        exit();
    }

    // Update order status to cancelled
    $stmt = $conn->prepare("
        UPDATE orders
        SET status = 'cancelled', updated_at = NOW()
        WHERE id = ? AND user_id = ?
    ");
    $result = $stmt->execute([$order_id, $_SESSION['user_id']]);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Order cancelled successfully',
            'order_id' => $order_id
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to cancel order']);
    }

} catch (PDOException $e) {
    error_log("Cancel order error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error occurred'
    ]);
}
?>