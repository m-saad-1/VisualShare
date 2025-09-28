<?php
require_once 'db_connection.php';

header("Content-Type: application/json");

$response = ['status' => 'error', 'message' => 'User not found'];

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $response = [
                'status' => 'success',
                'user' => $user
            ];
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error';
        error_log("Get user error: " . $e->getMessage());
    }
}

echo json_encode($response);
?>