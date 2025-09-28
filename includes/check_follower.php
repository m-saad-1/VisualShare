<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_GET['user_id']) || !isset($_GET['current_user_id'])) {
    echo json_encode(['error' => 'User ID and Current User ID are required']);
    exit;
}

$user_id = intval($_GET['user_id']);
$current_user_id = intval($_GET['current_user_id']);

try {
    $stmt = $conn->prepare("
        SELECT COUNT(*) as is_follower 
        FROM user_follows 
        WHERE follower_id = ? AND following_id = ?
    ");
    $stmt->bind_param("ii", $user_id, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    echo json_encode(['is_follower' => $data['is_follower'] > 0]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>