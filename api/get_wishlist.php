<?php
// get_wishlist.php
require_once 'config.php';

header("Content-Type: application/json");

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

try {
    // Get user's wishlist items with product details
    $stmt = $conn->prepare("
        SELECT p.id as product_id, p.title, p.price, p.image 
        FROM wishlist w
        JOIN products p ON w.product_id = p.id
        WHERE w.user_id = ?
        ORDER BY w.added_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'wishlist' => $wishlistItems
    ]);
} catch (PDOException $e) {
    error_log("Get wishlist error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>