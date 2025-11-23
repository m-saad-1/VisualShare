<?php

require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle checking if item is saved
    $userId = $_SESSION['user_id'] ?? 0;
    $contentId = $_GET['check'] ?? null;

    if (!$userId || !$contentId) {
        echo json_encode(['saved' => false]);
        exit;
    }

    $check = $conn->prepare("SELECT 1 FROM user_favorites WHERE user_id = ? AND upload_id = ?");
    $check->bind_param("ii", $userId, $contentId);
    $check->execute();

    echo json_encode(['saved' => $check->get_result()->num_rows > 0]);
    exit;
}

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

$userId = $_SESSION['user_id'];
$contentId = $_POST['id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$contentId || !in_array($action, ['save', 'unsave'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    if ($action === 'save') {
        // Check if already favorited
        $check = $conn->prepare("SELECT 1 FROM user_favorites WHERE user_id = ? AND upload_id = ?");
        $check->bind_param("ii", $userId, $contentId);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Already favorited']);
            exit;
        }

        // Add to favorites
        $stmt = $conn->prepare("INSERT INTO user_favorites (user_id, upload_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $contentId);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Added to favorites']);
    } else {
        // Remove from favorites
        $stmt = $conn->prepare("DELETE FROM user_favorites WHERE user_id = ? AND upload_id = ?");
        $stmt->bind_param("ii", $userId, $contentId);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Removed from saved items!']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
