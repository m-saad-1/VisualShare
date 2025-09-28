<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$user_id = intval($_GET['user_id']);
$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

try {
    $stmt = $conn->prepare("
        SELECT u.id, u.username, u.profile_pic, u.gender,
               EXISTS(SELECT 1 FROM user_follows WHERE follower_id = ? AND following_id = u.id) as is_following,
               EXISTS(SELECT 1 FROM user_follows WHERE follower_id = u.id AND following_id = ?) as follows_you
        FROM users u
        JOIN user_follows uf ON u.id = uf.following_id
        WHERE uf.follower_id = ?
        ORDER BY u.username
    ");
    $stmt->bind_param("iii", $current_user_id, $current_user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $following = [];
    while ($row = $result->fetch_assoc()) {
        $following[] = $row;
    }
    
    echo json_encode($following);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>