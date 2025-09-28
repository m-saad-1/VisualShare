<?php
// toggle_wishlist.php
require_once 'config.php';

header("Content-Type: application/json");

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);
$productId = isset($input['product_id']) ? intval($input['product_id']) : 0;

if ($productId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
    exit();
}

try {
    // Check if the product exists in the database
    $stmt = $conn->prepare("SELECT id, title, price, image FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit();
    }
    
    // Check if the product is already in the user's wishlist
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $productId]);
    $existingWishlistItem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingWishlistItem) {
        // Remove from wishlist
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$_SESSION['user_id'], $productId]);
        
        echo json_encode(['status' => 'success', 'action' => 'removed']);
    } else {
        // Add to wishlist
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, added_at) VALUES (?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $productId]);
        
        echo json_encode([
            'status' => 'success', 
            'action' => 'added',
            'product' => [
                'id' => $product['id'],
                'title' => $product['title'],
                'price' => $product['price'],
                'image' => $product['image']
            ]
        ]);
    }
} catch (PDOException $e) {
    error_log("Wishlist error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>