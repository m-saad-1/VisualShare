<?php
// db_connection.php

$host = 'localhost';
$dbname = 'fashionhub-old';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Don't expose database details in production
    error_log("Database connection failed: " . $e->getMessage());
    
    // Return JSON error for API requests
    if (php_sapi_name() !== 'cli') {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit();
    } else {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>