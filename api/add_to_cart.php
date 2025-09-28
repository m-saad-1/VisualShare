<?php
// add_to_cart.php
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
$quantity = $input['quantity'] ?? 1;
$size = $input['size'] ?? null;
$color = $input['color'] ?? null;

// Validate input
if (!$product_id) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID is required']);
    exit();
}

try {
    // Get product details to apply default values if needed
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit();
    }
    
    // Apply default values if not provided
    if (!$size && $product['sizes']) {
        $sizes = json_decode($product['sizes'], true);
        $size = $sizes[0] ?? null; // Use first available size as default
    }
    
    if (!$color && $product['colors']) {
        $colors = json_decode($product['colors'], true);
        $color = $colors[0] ?? null; // Use first available color as default
    }
    
    // Ensure quantity is at least 1
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    // Check if the product already exists in the user's cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?");
    $stmt->execute([$_SESSION['user_id'], $product_id, $size, $color]);
    $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingItem) {
        // Update quantity if item already exists
        $newQuantity = $existingItem['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Insert new item
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size, color) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $product_id, $quantity, $size, $color]);
    }
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Product added to cart',
        'applied_defaults' => [
            'size' => $size,
            'color' => $color,
            'quantity' => $quantity
        ]
    ]);
} catch (PDOException $e) {
    error_log("Add to cart error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>