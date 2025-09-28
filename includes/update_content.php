<?php
// Start output buffering to prevent any accidental output
ob_start();

require_once 'config.php';

// Set JSON header
header('Content-Type: application/json');

function jsonResponse($success, $message, $code = 200, $data = []) {
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

try {
    // Verify request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, 'Method not allowed', 405);
    }

    // Check session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['user_id'])) {
        jsonResponse(false, 'Authentication required', 401);
    }

    // Get input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    if ($data === null) {
        $data = $_POST;
    }

    // Validate required fields
    if (empty($data['id']) || empty($data['title'])) {
        jsonResponse(false, 'Missing required fields', 400);
    }

    // Start transaction
    $conn->begin_transaction();

    // Verify content ownership - fixed with proper parameter binding
    $check = $conn->prepare("SELECT user_id FROM uploads WHERE id = ?");
    $contentId = (int)$data['id'];
    $check->bind_param("i", $contentId);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows === 0) {
        jsonResponse(false, 'Content not found', 404);
    }
    
    $content = $result->fetch_assoc();
    $check->close();
    
    // Check if the logged-in user owns the content or is admin
    $userId = (int)$_SESSION['user_id'];
    $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    
    if ($content['user_id'] !== $userId && !$isAdmin) {
        jsonResponse(false, 'Unauthorized to update this content', 403);
    }

    // Update content - fixed binding
    $update = $conn->prepare("UPDATE uploads SET title = ?, description = ? WHERE id = ?");
    $title = trim($data['title']);
    $description = trim($data['description'] ?? '');
    $update->bind_param("ssi", $title, $description, $contentId);
    
    if (!$update->execute()) {
        throw new Exception("Update failed: " . $conn->error);
    }

    // Process tags if provided - fixed binding
    if (!empty($data['tags'])) {
        // Clear existing tags
        $deleteTags = $conn->prepare("DELETE FROM upload_tags WHERE upload_id = ?");
        $deleteTags->bind_param("i", $contentId);
        $deleteTags->execute();
        $deleteTags->close();

        // Insert new tags
        $tags = array_unique(array_filter(array_map('trim', explode(',', $data['tags']))));
        if (!empty($tags)) {
            $tagInsert = $conn->prepare("INSERT IGNORE INTO tags (name) VALUES (?)");
            $linkInsert = $conn->prepare("INSERT INTO upload_tags (upload_id, tag_id) VALUES (?, ?)");
            
            foreach ($tags as $tag) {
                if (empty($tag)) continue;
                
                // Insert or get tag
                $tagInsert->bind_param("s", $tag);
                $tagInsert->execute();
                
                // Get tag ID
                $tagId = $conn->insert_id;
                if (!$tagId) {
                    $getTag = $conn->prepare("SELECT id FROM tags WHERE name = ?");
                    $getTag->bind_param("s", $tag);
                    $getTag->execute();
                    $tagResult = $getTag->get_result();
                    $tagId = $tagResult->fetch_assoc()['id'];
                    $getTag->close();
                }
                
                // Link tag to content
                $linkInsert->bind_param("ii", $contentId, $tagId);
                $linkInsert->execute();
            }
            
            $tagInsert->close();
            $linkInsert->close();
        }
    }

    // Commit changes
    $conn->commit();
    
    jsonResponse(true, 'Content updated successfully', 200, [
        'affected_rows' => $update->affected_rows
    ]);

} catch (Throwable $e) {
    // Rollback on any error
    if (isset($conn) && $conn instanceof mysqli && $conn->thread_id) {
        $conn->rollback();
    }
    
    error_log("Content update error: " . $e->getMessage());
    jsonResponse(false, 'Server error: ' . $e->getMessage(), 500);
} finally {
    // Clean any accidental output
    ob_end_clean();
}