<?php
// logout.php
require_once 'config.php';

header("Content-Type: application/json");

// Destroy the session
session_destroy();

// Clear remember token cookie if exists
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

echo json_encode(['status' => 'success', 'message' => 'Logged out successfully']);
exit();
?>