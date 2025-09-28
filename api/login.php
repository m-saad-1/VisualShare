<?php
header("Content-Type: application/json");
error_reporting(0);

require_once 'db_connection.php';
require_once 'config.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON input");
    }

    $email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $input['password'] ?? '';
    $rememberMe = $input['rememberMe'] ?? false;

    if (empty($email)) {
        throw new Exception("Email is required");
    }

    if (empty($password)) {
        throw new Exception("Password is required");
    }

    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Invalid email or password");
    }

    if (!password_verify($password, $user['password'])) {
        throw new Exception("Invalid email or password");
    }

    session_regenerate_id(true);

    $_SESSION = [
        'user_id' => $user['id'],
        'user_email' => $user['email'],
        'user_name' => $user['name'],
        'user_role' => $user['role'] ?? 'user',
        'user_authenticated' => true,
        'last_activity' => time()
    ];

    if ($rememberMe) {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60);
        
        $stmt = $conn->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([
            $user['id'],
            hash('sha256', $token),
            date('Y-m-d H:i:s', $expiry)
        ]);

        setcookie(
            'remember_token',
            json_encode(['user_id' => $user['id'], 'token' => $token]),
            [
                'expires' => $expiry,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]
        );
    }

    echo json_encode([
        'status' => 'success',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]
    ]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    error_log("Login error: " . $e->getMessage());
}
?>