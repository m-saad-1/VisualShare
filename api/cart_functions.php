<?php
// cart_functions.php
require_once 'config.php';

header("Content-Type: application/json");

// Get cart items for authenticated user
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
        exit();
    }
    
    try {
        $stmt = $conn->prepare("
            SELECT c.*, p.title, p.image, p.price 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['status' => 'success', 'cart' => $cartItems]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
        exit();
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $productId = $input['product_id'] ?? null;
    $quantity = $input['quantity'] ?? 1;
    $size = $input['size'] ?? null;
    $color = $input['color'] ?? null;
    
    if (!$productId) {
        echo json_encode(['status' => 'error', 'message' => 'Product ID is required']);
        exit();
    }
    
    try {
        // Check if product already exists in cart with same size and color
        $stmt = $conn->prepare("
            SELECT id, quantity FROM cart 
            WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $productId, $size, $color]);
        $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $conn->prepare("
                UPDATE cart SET quantity = ? WHERE id = ?
            ");
            $stmt->execute([$newQuantity, $existingItem['id']]);
        } else {
            // Insert new item
            $stmt = $conn->prepare("
                INSERT INTO cart (user_id, product_id, quantity, size, color, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$_SESSION['user_id'], $productId, $quantity, $size, $color]);
        }
        
        echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Update cart item quantity
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
        exit();
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $cartItemId = $input['cart_item_id'] ?? null;
    $quantity = $input['quantity'] ?? 1;
    
    if (!$cartItemId) {
        echo json_encode(['status' => 'error', 'message' => 'Cart item ID is required']);
        exit();
    }
    
    try {
        // Verify the cart item belongs to the user
        $stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cartItemId, $_SESSION['user_id']]);
        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$cartItem) {
            echo json_encode(['status' => 'error', 'message' => 'Cart item not found']);
            exit();
        }
        
        if ($quantity <= 0) {
            // Remove item if quantity is 0 or less
            $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
            $stmt->execute([$cartItemId]);
        } else {
            // Update quantity
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt->execute([$quantity, $cartItemId]);
        }
        
        echo json_encode(['status' => 'success', 'message' => 'Cart updated']);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
        exit();
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $cartItemId = $input['cart_item_id'] ?? null;
    
    if (!$cartItemId) {
        echo json_encode(['status' => 'error', 'message' => 'Cart item ID is required']);
        exit();
    }
    
    try {
        // Verify the cart item belongs to the user
        $stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cartItemId, $_SESSION['user_id']]);
        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$cartItem) {
            echo json_encode(['status' => 'error', 'message' => 'Cart item not found']);
            exit();
        }
        
        // Delete item
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->execute([$cartItemId]);
        
        echo json_encode(['status' => 'success', 'message' => 'Item removed from cart']);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>