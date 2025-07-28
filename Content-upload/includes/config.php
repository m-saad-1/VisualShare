<?php
session_start();

// Database configuration
define('DB_HOST', 'sql106.inflinityfree.com');
define('DB_USER', 'if0_39557605');
define('DB_PASS', 'rVCFt0dpxofgY3');
define('DB_NAME', 'if0_39557605_pinterest_clone');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// File upload configuration
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/Content-upload/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
?>