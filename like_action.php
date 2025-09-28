<?php
// ---- Basic JSON header early
header('Content-Type: application/json');

// ---- Error reporting: ENABLE while debugging; DISABLE on production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ---- Project root: file is now in ROOT, not /ajax
define('ROOT_DIR', __DIR__);

// ---- Resolve include paths safely
$configPath    = ROOT_DIR . '/includes/config.php';
$functionsPath = ROOT_DIR . '/includes/functions.php';

if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Config file not found']);
    exit;
}
require_once $configPath;

if (file_exists($functionsPath)) {
    require_once $functionsPath;
}

// ---- Init response
$response = [
    'success'    => false,
    'message'    => 'Invalid request',
    'like_count' => 0,
];

// ---- Only POST
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode($response);
    exit;
}

// ---- Session
if (session_status() === PHP_SESSION_NONE) {
    // Keep it simple for local dev; add cookie flags later if needed
    session_start();
}

// ---- Auth
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required', 'like_count' => 0]);
    exit;
}

// ---- Validate input
$upload_id = filter_input(INPUT_POST, 'upload_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$actionRaw = $_POST['action'] ?? '';
$action    = ($actionRaw === 'like') ? 'like' : 'unlike';
$user_id   = (int) $_SESSION['user_id'];

if (!$upload_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid content ID', 'like_count' => 0]);
    exit;
}

// ---- DB connection check
if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed', 'like_count' => 0]);
    exit;
}

try {
    // Ensure mysqli throws exceptions (better error messages)
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // 1) Does the upload exist?
    $stmt = $conn->prepare('SELECT id FROM uploads WHERE id = ?');
    $stmt->bind_param('i', $upload_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Content not found', 'like_count' => 0]);
        exit;
    }
    $stmt->close();

    // 2) Like/Unlike
    if ($action === 'like') {
        $check = $conn->prepare('SELECT id FROM likes WHERE user_id = ? AND upload_id = ?');
        $check->bind_param('ii', $user_id, $upload_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $insert = $conn->prepare('INSERT INTO likes (user_id, upload_id) VALUES (?, ?)');
            $insert->bind_param('ii', $user_id, $upload_id);
            $insert->execute();
            $insert->close();
        }
        $check->close();
    } else {
        $delete = $conn->prepare('DELETE FROM likes WHERE user_id = ? AND upload_id = ?');
        $delete->bind_param('ii', $user_id, $upload_id);
        $delete->execute();
        $delete->close();
    }

    // 3) Return latest like count
    $count_stmt = $conn->prepare('SELECT COUNT(*) FROM likes WHERE upload_id = ?');
    $count_stmt->bind_param('i', $upload_id);
    $count_stmt->execute();
    $count_stmt->bind_result($count);
    $count_stmt->fetch();
    $count_stmt->close();

    echo json_encode([
        'success'    => true,
        'message'    => ucfirst($action) . ' successful',
        'like_count' => (int) $count,
    ]);
    exit;

} catch (Throwable $e) {
    // Any DB or runtime error
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage(), 'like_count' => 0]);
    exit;
}
