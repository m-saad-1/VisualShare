<?php
// get_wishlist_count.php
require_once 'config.php';

header("Content-Type: application/json");

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated', 'count' => 0]);
    exit();
}

try {
    // Get wishlist count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'count' => $result['count']]);
} catch(PDOException $e) {
    error_log("Wishlist count error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error', 'count' => 0]);
}
?>