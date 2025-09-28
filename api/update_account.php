<?php
session_start();
include 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

// Get current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Validate current password only if changing password
if (!empty($data['new_password'])) {
    if (empty($data['current_password']) || !password_verify($data['current_password'], $user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
        exit();
    }
    
    // Update password
    $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
}

// Update other fields
$fullName = $data['first_name'] . ' ' . $data['last_name'];
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
$stmt->execute([$fullName, $data['email'], $_SESSION['user_id']]);

echo json_encode(['status' => 'success']);
?>