<?php
// admin_login.php
require_once 'config.php';

header("Content-Type: application/json");

$response = ['status' => 'error', 'message' => 'Authentication failed'];

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON input");
    }

    $email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        throw new Exception("Email and password are required");
    }

    // Check if user exists and is admin
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Invalid admin credentials");
    }

    if (!password_verify($password, $user['password'])) {
        throw new Exception("Invalid admin credentials");
    }

    // Start admin session
    session_regenerate_id(true);
    $_SESSION = [
        'admin_id' => $user['id'],
        'admin_email' => $user['email'],
        'admin_name' => $user['name'],
        'admin_role' => $user['role'],
        'admin_authenticated' => true,
        'last_activity' => time()
    ];

    $response = [
        'status' => 'success',
        'admin' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Admin login error: " . $e->getMessage());
}

echo json_encode($response);
?>