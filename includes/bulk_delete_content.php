<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['ids']) || !is_array($data['ids']) || empty($data['ids'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No items selected for deletion']);
    exit;
}

$user_id = $_SESSION['user_id'];
$deleted_count = 0;

try {
    $conn->begin_transaction();
    
    foreach ($data['ids'] as $contentId) {
        // Check ownership
        $stmt = $conn->prepare("SELECT user_id, filepath, thumbnail_path FROM uploads WHERE id = ?");
        $stmt->bind_param("i", $contentId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            continue; // Skip if not found
        }
        
        $content = $result->fetch_assoc();
        
        // Verify ownership (users can only delete their own content)
        if ($content['user_id'] != $user_id) {
            continue; // Skip if not owner
        }
        
        // Delete file from server
        if (file_exists($content['filepath'])) {
            unlink($content['filepath']);
        }
        if (!empty($content['thumbnail_path']) && file_exists($content['thumbnail_path'])) {
            unlink($content['thumbnail_path']);
        }
        
        // Delete from database
        $deleteStmt = $conn->prepare("DELETE FROM uploads WHERE id = ?");
        $deleteStmt->bind_param("i", $contentId);
        $deleteStmt->execute();
        
        $deleted_count++;
    }
    
    $conn->commit();
    echo json_encode([
        'success' => true, 
        'message' => 'Content deleted successfully',
        'deleted_count' => $deleted_count
    ]);
    
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}