<?php
// update_cart.php
require_once 'config.php';

header("Content-Type: application/json");

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);
$cartItems = $input['cart'] ?? [];



try {
    // Begin transaction
    $conn->beginTransaction();
    
    // First, remove all existing cart items for this user
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    
    // Then, insert the updated cart items
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size, color) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($cartItems as $item) {
        $stmt->execute([
            $_SESSION['user_id'],
            $item['id'],
            $item['quantity'],
            $item['size'] ?? null,
            $item['color'] ?? null
        ]);
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully']);
} catch (PDOException $e) {
    // Rollback transaction on error
    $conn->rollBack();
    error_log("Update cart error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>