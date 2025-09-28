
<?php
// check_auth.php
require_once 'config.php';

header("Content-Type: application/json");

$response = ['status' => 'error', 'message' => 'Not authenticated'];

// Check session first (using your session structure)
if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] === true) {
    try {
        $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Update last activity
            $_SESSION['last_activity'] = time();
            
            // Get wishlist count
            $wishlistStmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?");
            $wishlistStmt->execute([$_SESSION['user_id']]);
            $wishlistCount = $wishlistStmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            $response = [
                'status' => 'success',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'wishlistCount' => $wishlistCount
                ]
            ];
            echo json_encode($response);
            exit();
        }
    } catch(PDOException $e) {
        $response['message'] = 'Database error';
        error_log("Check auth error: " . $e->getMessage());
    }
}

// Check remember token if session not found (from your original auth_check.php)
if (isset($_COOKIE['remember_token'])) {
    try {
        $cookieData = json_decode($_COOKIE['remember_token'], true);
        
        if ($cookieData && isset($cookieData['user_id']) && isset($cookieData['token'])) {
            // Verify token in database
            $stmt = $conn->prepare("SELECT t.user_id, u.name, u.email 
                                  FROM remember_tokens t
                                  JOIN users u ON t.user_id = u.id
                                  WHERE t.user_id = ? 
                                  AND t.token = ? 
                                  AND t.expires_at > NOW()");
            $stmt->execute([
                $cookieData['user_id'],
                hash('sha256', $cookieData['token'])
            ]);
            
            $token = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($token) {
                // Regenerate session
                session_regenerate_id(true);
                $_SESSION['user_id'] = $token['user_id'];
                $_SESSION['user_email'] = $token['email'];
                $_SESSION['user_name'] = $token['name'];
                $_SESSION['user_authenticated'] = true;
                $_SESSION['last_activity'] = time();
                
                // Get wishlist count
                $wishlistStmt = $conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?");
                $wishlistStmt->execute([$token['user_id']]);
                $wishlistCount = $wishlistStmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                $response = [
                    'status' => 'success',
                    'user' => [
                        'id' => $token['user_id'],
                        'name' => $token['name'],
                        'email' => $token['email'],
                        'wishlistCount' => $wishlistCount
                    ]
                ];
            } else {
                // Invalid token - clear cookie
                setcookie('remember_token', '', time() - 3600, '/');
            }
        }
    } catch(PDOException $e) {
        $response['message'] = 'Database error';
        error_log("Remember token error: " . $e->getMessage());
    }
}

echo json_encode($response);
?>