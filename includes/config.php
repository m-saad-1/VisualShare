<?php

// Enhanced error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../php_errors.log');

// Session security
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
// define('DB_HOST', 'sql106.inflinityfree.com');
// define('DB_USER', 'if0_39557605');
// define('DB_PASS', 'rVCFt0dpxofgY3');
// define('DB_NAME', 'if0_39557605_pinterest_clone');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'visualshare');

// Create connection with error handling
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Set charset
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log($e->getMessage());
    die("System maintenance in progress. Please try again later.");
}

// Base URL configuration
define('BASE_URL', sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['HTTP_HOST'],
    rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\')
));

// Project root directory
define('ROOT_DIR', dirname(__DIR__));

// File upload configuration
define('UPLOAD_DIR', str_replace('\\', '/', __DIR__ . '/uploads/'));
define('MAX_FILE_SIZE', 20 * 1024 * 1024); // 20MB
define('ALLOWED_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp'
]);
