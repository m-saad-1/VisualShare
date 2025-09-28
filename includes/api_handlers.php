<?php
// includes/api_handlers.php

// Custom exception classes
class AuthenticationException extends Exception {}
class DatabaseException extends Exception {}
class ValidationException extends Exception {}

function handleCommentsApi($conn, $user_id) {
    header('Content-Type: application/json');

    

    $request_method = $_SERVER['REQUEST_METHOD'];
    $input = [];

    // Unified input handling based on the original method
    if ($request_method === 'POST') {
        $input = $_POST;
    } elseif ($request_method === 'GET') {
        $input = $_GET;
    } else { // For PUT, DELETE, etc., that might be blocked
        parse_str(file_get_contents("php://input"), $input);
    }

    // Method override for tunneling POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
        $request_method = strtoupper($_POST['_method']);
        $input = $_POST; // Data for tunneled requests is in the POST body
    }

    switch ($request_method) {
        case 'GET':
            $upload_id = filter_var($input['upload_id'] ?? 0, FILTER_VALIDATE_INT);
            if (!$upload_id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid upload ID']);
                exit;
            }
            try {
                $stmt = $conn->prepare("
                    SELECT comments.*, users.username, users.profile_pic
                    FROM comments JOIN users ON comments.user_id = users.id
                    WHERE comments.upload_id = ? ORDER BY comments.created_at DESC
                ");
                $stmt->bind_param("i", $upload_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $comments = [];
                while ($row = $result->fetch_assoc()) {
                    $comments[] = [
                        'id' => $row['id'],
                        'comment' => htmlspecialchars($row['comment']),
                        'username' => htmlspecialchars($row['username']),
                        'profile_pic' => $row['profile_pic'],
                        'user_id' => $row['user_id'],
                        'created_at' => date('M j, Y g:i A', strtotime($row['created_at'])),
                        'is_owner' => $row['user_id'] == $user_id
                    ];
                }
                echo json_encode(['success' => true, 'comments' => $comments]);
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'comments') !== false && (strpos($e->getMessage(), 'exist') !== false || strpos($e->getMessage(), "doesn't exist") !== false)) {
                    echo json_encode(['success' => true, 'comments' => []]);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
                }
            }
            break;

        case 'POST': // CREATE
            if (empty($user_id)) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Authentication required']);
                exit;
            }
            $upload_id = filter_var($input['upload_id'] ?? 0, FILTER_VALIDATE_INT);
            $comment = trim($input['comment'] ?? '');
            if (!$upload_id || empty($comment)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
                exit;
            }
            if (strlen($comment) > 500) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Comment too long']);
                exit;
            }
            $stmt = $conn->prepare("SELECT id FROM uploads WHERE id = ?");
            $stmt->bind_param("i", $upload_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Content not found']);
                exit;
            }
            try {
                $insert_stmt = $conn->prepare("INSERT INTO comments (upload_id, user_id, comment) VALUES (?, ?, ?)");
                $insert_stmt->bind_param("iis", $upload_id, $user_id, $comment);
                if ($insert_stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Comment added']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Database error on insert']);
            }
            break;

        case 'PUT': // UPDATE
            if (empty($user_id)) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Authentication required']);
                exit;
            }
            $comment_id = filter_var($input['comment_id'] ?? 0, FILTER_VALIDATE_INT);
            $comment = trim($input['comment'] ?? '');
            if (!$comment_id || empty($comment)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid input for update']);
                exit;
            }
            if (strlen($comment) > 500) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Comment too long']);
                exit;
            }
            try {
                $check_stmt = $conn->prepare("SELECT id FROM comments WHERE id = ? AND user_id = ?");
                $check_stmt->bind_param("ii", $comment_id, $user_id);
                $check_stmt->execute();
                if ($check_stmt->get_result()->num_rows === 0) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Comment not found or access denied']);
                    exit;
                }
                $update_stmt = $conn->prepare("UPDATE comments SET comment = ? WHERE id = ? AND user_id = ?");
                $update_stmt->bind_param("sii", $comment, $comment_id, $user_id);
                if ($update_stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Comment updated']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Failed to update comment']);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Database error on update']);
            }
            break;

        case 'DELETE': // DELETE
            if (empty($user_id)) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Authentication required']);
                exit;
            }
            $comment_id = filter_var($input['comment_id'] ?? 0, FILTER_VALIDATE_INT);
            if (!$comment_id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid comment ID']);
                exit;
            }
            try {
                $check_stmt = $conn->prepare("SELECT id FROM comments WHERE id = ? AND user_id = ?");
                $check_stmt->bind_param("ii", $comment_id, $user_id);
                $check_stmt->execute();
                if ($check_stmt->get_result()->num_rows === 0) {
                    http_response_code(403);
                    echo json_encode(['success' => false, 'message' => 'Comment not found or access denied']);
                    exit;
                }
                $delete_stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
                $delete_stmt->bind_param("ii", $comment_id, $user_id);
                if ($delete_stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Comment deleted']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Database error on delete']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            break;
    }
    exit;
}

function handleLikesApi($conn, $user_id) {
    header('Content-Type: application/json');

    $response = [
        'success' => false,
        'message' => 'Invalid request',
        'like_count' => 0
    ];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode($response);
        exit;
    }

    if (empty($user_id)) {
        $response['message'] = 'Authentication required';
        http_response_code(401);
        echo json_encode($response);
        exit;
    }

    if (!isset($_POST['upload_id']) || !isset($_POST['action'])) {
        $response['message'] = 'Missing parameters';
        http_response_code(400);
        echo json_encode($response);
        exit;
    }

    if (!filter_var($_POST['upload_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
        $response['message'] = 'Invalid content ID';
        http_response_code(400);
        echo json_encode($response);
        exit;
    }

    $upload_id = (int)$_POST['upload_id'];
    $action = $_POST['action'] === 'like' ? 'like' : 'unlike';

    if ($conn->connect_error) {
        $response['message'] = 'Database connection failed';
        http_response_code(500);
        echo json_encode($response);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id FROM uploads WHERE id = ?");
        if (!$stmt) {
            throw new DatabaseException('Database prepare failed');
        }
        
        $stmt->bind_param("i", $upload_id);
        if (!$stmt->execute()) {
            throw new DatabaseException('Database query failed');
        }
        
        if ($stmt->get_result()->num_rows === 0) {
            $response['message'] = 'Content not found';
            http_response_code(404);
            echo json_encode($response);
            exit;
        }

        if ($action === 'like') {
            // Check for existing like
            $check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND upload_id = ?");
            $check->bind_param("ii", $user_id, $upload_id);
            $check->execute();
            
            if ($check->get_result()->num_rows === 0) {
                $insert = $conn->prepare("INSERT INTO likes (user_id, upload_id) VALUES (?, ?)");
                if (!$insert || !$insert->bind_param("ii", $user_id, $upload_id) || !$insert->execute()) {
                    throw new DatabaseException('Failed to like content');
                }
            }
        } else {
            $delete = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND upload_id = ?");
            if (!$delete || !$delete->bind_param("ii", $user_id, $upload_id) || !$delete->execute()) {
                throw new DatabaseException('Failed to unlike content');
            }
        }

        // Get updated like count
        $count_stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM likes WHERE upload_id = ?");
        if (!$count_stmt || !$count_stmt->bind_param("i", $upload_id) || !$count_stmt->execute()) {
            throw new DatabaseException('Failed to get like count');
        }
        
        $count_result = $count_stmt->get_result();
        $count = $count_result->fetch_assoc()['cnt'];
        
        $response = [
            'success' => true,
            'like_count' => $count,
            'message' => ucfirst($action) . ' successful'
        ];

    } catch (AuthenticationException $e) {
        $response['message'] = 'Authentication error';
        http_response_code(401);
    } catch (DatabaseException $e) {
        $response['message'] = 'Database error';
        http_response_code(500);
    } catch (Exception $e) {
        $response['message'] = 'Server error';
        http_response_code(500);
    } 
    
    echo json_encode($response);
    exit;
}
?>