<?php
session_start();
include 'db_connection.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit();
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Validate required fields
    if (!isset($data['type']) || !isset($data['name']) || !isset($data['street']) || 
        !isset($data['city']) || !isset($data['state']) || !isset($data['zip']) || !isset($data['country'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit();
    }
    
    // Validate address type
    if (!in_array($data['type'], ['billing', 'shipping'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid address type']);
        exit();
    }

    // Map full country names to country codes
    $countryCodeMap = [
        'United States' => 'US',
        'United Kingdom' => 'UK',
        'Pakistan' => 'PK',
        'Canada' => 'CA',
        'Australia' => 'AU',
        'Germany' => 'DE',
        'France' => 'FR',
        'Italy' => 'IT',
        'Japan' => 'JP',
        'China' => 'CN',
        'Brazil' => 'BR',
        'India' => 'IN',
        'Russia' => 'RU',
        'Other' => 'other'
    ];
    
    // Convert country name to code if needed
    $country = $data['country'];
    if (strlen($country) > 2 && isset($countryCodeMap[$country])) {
        $country = $countryCodeMap[$country];
    }

    // Check if address already exists for this type
    $stmt = $conn->prepare("SELECT * FROM addresses WHERE user_id = ? AND type = ?");
    $stmt->execute([$_SESSION['user_id'], $data['type']]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Update existing address
        $stmt = $conn->prepare("UPDATE addresses SET name = ?, street = ?, city = ?, state = ?, zip = ?, country = ? WHERE user_id = ? AND type = ?");
        $success = $stmt->execute([
            $data['name'],
            $data['street'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $country, // Use the converted country code
            $_SESSION['user_id'],
            $data['type']
        ]);
    } else {
        // Insert new address
        $stmt = $conn->prepare("INSERT INTO addresses (user_id, type, name, street, city, state, zip, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([
            $_SESSION['user_id'],
            $data['type'],
            $data['name'],
            $data['street'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $country // Use the converted country code
        ]);
    }

    if ($success) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to save address']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>