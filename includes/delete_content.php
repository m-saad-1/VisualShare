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

$contentId = $_POST['id'] ?? null;
if (!$contentId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Content ID required']);
    exit;
}

try {
    $conn->begin_transaction();
    
    // Check ownership
    $stmt = $conn->prepare("SELECT user_id, filepath, thumbnail_path FROM uploads WHERE id = ?");
    $stmt->bind_param("i", $contentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Content not found']);
        exit;
    }
    
    $content = $result->fetch_assoc();
    $isAdmin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'];
    
    if ($content['user_id'] != $_SESSION['user_id'] && !$isAdmin) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'You do not have permission to delete this content']);
        exit;
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
    
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Content deleted successfully']);
    
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}