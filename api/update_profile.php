<?php
// update_profile.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

// Check if user is logged in (using your session structure)
if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to update your profile']);
    exit();
}

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['name']) || !isset($data['email'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Name and email are required']);
    exit();
}

$user_id = $_SESSION['user_id'];
$name = filter_var($data['name'], FILTER_SANITIZE_STRING);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

try {
    // Check if email already exists (excluding current user)
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        exit();
    }
    
    // Update user
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $user_id]);
    
    // Update session
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Profile updated successfully',
        'user' => [
            'id' => $user_id,
            'name' => $name,
            'email' => $email
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>