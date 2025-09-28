<?php
// remove_cart_item.php
require_once 'config.php';

header("Content-Type: application/json");

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);
$product_id = $input['product_id'] ?? null;
$size = $input['size'] ?? null;
$color = $input['color'] ?? null;

// Validate input
if (!$product_id) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID is required']);
    exit();
}

try {
    // Remove the cart item
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id, $size, $color]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Cart item removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Cart item not found']);
    }
} catch (PDOException $e) {
    error_log("Remove cart item error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>