<?php

require_once 'includes/config.php';

/**
 * Handles all AJAX API requests for the application.
 */
function handleApiRequest(): void
{
    header('Content-Type: application/json');

    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Authentication required']);
        exit;
    }

    $requestType = $_POST['ajax'] ?? $_GET['ajax'] ?? null;
    $uploadId = filter_var($_POST['upload_id'] ?? $_GET['upload_id'] ?? 0, FILTER_VALIDATE_INT);
    $userId = (int)$_SESSION['user_id'];

    if (!$uploadId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid content ID']);
        exit;
    }

    global $conn;
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    try {
        switch ($requestType) {
            case 'like':
                handleLikeAction($conn, $userId, $uploadId);
                break;
            case 'save':
                handleSaveAction($conn, $userId, $uploadId);
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
}

/**
 * Handles the like/unlike action.
 */
function handleLikeAction(mysqli $conn, int $userId, int $uploadId): void
{
    $action = ($_POST['action'] === 'like') ? 'like' : 'unlike';

    if ($action === 'like') {
        $stmt = $conn->prepare("INSERT IGNORE INTO likes (user_id, upload_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $uploadId);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND upload_id = ?");
        $stmt->bind_param("ii", $userId, $uploadId);
        $stmt->execute();
    }

    // Return the new like count
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE upload_id = ?");
    $countStmt->bind_param("i", $uploadId);
    $countStmt->execute();
    $countStmt->bind_result($count);
    $countStmt->fetch();

    echo json_encode([
        'success' => true,
        'like_count' => $count,
        'message' => ucfirst($action) . ' successful'
    ]);
}

/**
 * Handles the save/unsave action.
 */
function handleSaveAction(mysqli $conn, int $userId, int $uploadId): void
{
    $check = $conn->prepare("SELECT user_id FROM user_favorites WHERE user_id = ? AND upload_id = ?");
    $check->bind_param("ii", $userId, $uploadId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO user_favorites (user_id, upload_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $uploadId);
        $stmt->execute();
        echo json_encode(['success' => true, 'saved' => true, 'message' => 'Content saved successfully']);
    } else {
        $stmt = $conn->prepare("DELETE FROM user_favorites WHERE user_id = ? AND upload_id = ?");
        $stmt->bind_param("ii", $userId, $uploadId);
        $stmt->execute();
        echo json_encode(['success' => true, 'saved' => false, 'message' => 'Content removed from saved items']);
    }
}

handleApiRequest();
