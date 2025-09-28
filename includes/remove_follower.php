<?php
// remove_follower.php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user ID']);
    exit;
}

$current_user_id = $_SESSION['user_id'];
$follower_id = intval($_POST['user_id']);

try {
    // Delete the follow relationship (remove someone who follows you)
    $stmt = $conn->prepare("DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $follower_id, $current_user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Follower removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove follower']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>