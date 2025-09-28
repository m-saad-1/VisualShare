<?php
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

if (!isset($_POST['user_id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$follower_id = $_SESSION['user_id'];
$following_id = intval($_POST['user_id']);
$action = $_POST['action'];

try {
    if ($action === 'follow') {
        // Check if already following
        $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM user_follows WHERE follower_id = ? AND following_id = ?");
        $check_stmt->bind_param("ii", $follower_id, $following_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $data = $result->fetch_assoc();
        
        if ($data['count'] > 0) {
            echo json_encode(['success' => false, 'message' => 'Already following this user']);
            exit;
        }
        
        // Insert follow relationship
        $stmt = $conn->prepare("INSERT INTO user_follows (follower_id, following_id, follow_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $follower_id, $following_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Successfully followed user']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to follow user']);
        }
    } elseif ($action === 'unfollow') {
        // Delete follow relationship
        $stmt = $conn->prepare("DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?");
        $stmt->bind_param("ii", $follower_id, $following_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Successfully unfollowed user']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to unfollow user']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>