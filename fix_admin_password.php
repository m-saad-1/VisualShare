<?php
/**
 * Fix Admin Password
 *
 * This script updates the admin user's password with the correct hash.
 */

require_once 'api/db_connection.php';

// Remove the JSON header that was set in db_connection.php for CLI usage
if (php_sapi_name() === 'cli') {
    header_remove('Content-Type');
}

echo "Fixing Admin Password\n";
echo "====================\n\n";

try {
    // Generate correct password hash for 'admin123'
    $correctHash = password_hash('admin123', PASSWORD_DEFAULT);
    echo "Generated new password hash: " . $correctHash . "\n\n";

    // Update the admin user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? AND role = 'admin'");
    $result = $stmt->execute([$correctHash, 'admin@fashionhub.com']);

    if ($result) {
        echo "✓ Admin password updated successfully!\n\n";

        // Verify the update worked
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ? AND role = 'admin'");
        $stmt->execute(['admin@fashionhub.com']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify('admin123', $user['password'])) {
            echo "✓ Password verification test passed!\n\n";
            echo "Admin login credentials:\n";
            echo "- Email: admin@fashionhub.com\n";
            echo "- Password: admin123\n";
        } else {
            echo "✗ Password verification still failing\n";
        }
    } else {
        echo "✗ Failed to update admin password\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>