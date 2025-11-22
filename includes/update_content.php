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
    $data = $_POST;

    // Validate required fields
    if (empty($data['id']) || empty($data['title'])) {
        jsonResponse(false, 'Missing required fields', 400);
    }

    // Start transaction
    $conn->begin_transaction();

    // Verify content ownership - fixed with proper parameter binding
    $check = $conn->prepare("SELECT user_id, filepath, thumbnail_path FROM uploads WHERE id = ?");
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

    $new_filepath = null;
    $new_filename = null;

    // Handle file upload if a new file is provided
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $file_type = mime_content_type($file['tmp_name']);
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validation from config
        if (!in_array($file_type, ALLOWED_TYPES)) {
            jsonResponse(false, 'Invalid file type. Only images and videos are allowed.', 400);
        }
        if ($file_size > MAX_FILE_SIZE) {
            jsonResponse(false, 'File size exceeds the limit of ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB.', 400);
        }

        // Generate new filename and path
        if (!file_exists(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0777, true);
        }
        $new_filename = uniqid('', true) . '.' . $file_ext;
        $new_filepath_absolute = UPLOAD_DIR . $new_filename;
        $new_filepath_relative = 'includes/uploads/' . $new_filename;

        if (move_uploaded_file($file['tmp_name'], $new_filepath_absolute)) {
            chmod($new_filepath_absolute, 0644);

            // Delete old files
            if (!empty($content['filepath']) && file_exists(ROOT_DIR . DIRECTORY_SEPARATOR . $content['filepath'])) {
                unlink(ROOT_DIR . DIRECTORY_SEPARATOR . $content['filepath']);
            }
            if (!empty($content['thumbnail_path']) && file_exists(ROOT_DIR . DIRECTORY_SEPARATOR . $content['thumbnail_path'])) {
                unlink(ROOT_DIR . DIRECTORY_SEPARATOR . $content['thumbnail_path']);
            }
            
            $new_filepath = $new_filepath_relative;
        } else {
            jsonResponse(false, 'Failed to move uploaded file.', 500);
        }
    }

    // Prepare update query
    if ($new_filepath) {
        $update = $conn->prepare("UPDATE uploads SET title = ?, description = ?, filepath = ?, filename = ? WHERE id = ?");
        $update->bind_param("ssssi", $title, $description, $new_filepath, $new_filename, $contentId);
    } else {
        $update = $conn->prepare("UPDATE uploads SET title = ?, description = ? WHERE id = ?");
        $update->bind_param("ssi", $title, $description, $contentId);
    }

    $title = trim($data['title']);
    $description = trim($data['description'] ?? '');
    
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