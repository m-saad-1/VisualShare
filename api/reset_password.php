<?php
require_once 'db_connection.php';

$input = json_decode(file_get_contents('php://input'), true);

$email = filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL);

if (empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'Email is required']);
    exit();
}

try {
    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email not found']);
        exit();
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Delete any existing tokens for this user
    $stmt = $conn->prepare("DELETE FROM password_reset_tokens WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->execute();

    // Insert new token
    $stmt = $conn->prepare("INSERT INTO password_reset_tokens (user_id, token, expires_at) 
                           VALUES (:user_id, :token, :expires_at)");
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':expires_at', $expiresAt);
    $stmt->execute();

    // In a real application, you would send an email with the reset link
    // $resetLink = "https://yourdomain.com/reset-password?token=$token";
    // mail($email, "Password Reset", "Click here to reset your password: $resetLink");

    echo json_encode([
        'status' => 'success',
        'message' => 'Password reset link has been sent to your email'
    ]);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Password reset failed']);
}
?>