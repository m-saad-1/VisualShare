<?php
/**
 * Fix Admin User Role
 */

require_once 'api/db_connection.php';

// Remove the JSON header that was set in db_connection.php for CLI usage
if (php_sapi_name() === 'cli') {
    header_remove('Content-Type');
}

echo "Fixing Admin User Role\n";
echo "=====================\n\n";

try {
    // Update admin user role
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
    $result = $stmt->execute(['admin@fashionhub.com']);

    if ($result) {
        echo "✓ Admin user role updated to 'admin'\n";
    } else {
        echo "✗ Failed to update admin user role\n";
    }

    // Verify the update
    $stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE email = ?");
    $stmt->execute(['admin@fashionhub.com']);
    $adminUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminUser) {
        echo "\nAdmin user details:\n";
        echo "  - ID: " . $adminUser['id'] . "\n";
        echo "  - Name: " . $adminUser['name'] . "\n";
        echo "  - Email: " . $adminUser['email'] . "\n";
        echo "  - Role: " . $adminUser['role'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>