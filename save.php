<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'redirect' => true]);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_id'], $_POST['action'])) {
        $upload_id = intval($_POST['upload_id']);
        $user_id = $_SESSION['user_id'];
        $action = $_POST['action'];
        
        // Check if saved_posts table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'saved_posts'");
        if ($table_check->num_rows === 0) {
            throw new Exception("Saved posts table does not exist");
        }
        
        if ($action === 'save') {
            // Check if already saved
            $stmt = $conn->prepare("SELECT id FROM saved_posts WHERE user_id = ? AND upload_id = ?");
            $stmt->bind_param("ii", $user_id, $upload_id);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO saved_posts (user_id, upload_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $upload_id);
                $stmt->execute();
            }
        } else {
            $stmt = $conn->prepare("DELETE FROM saved_posts WHERE user_id = ? AND upload_id = ?");
            $stmt->bind_param("ii", $user_id, $upload_id);
            $stmt->execute();
        }
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>