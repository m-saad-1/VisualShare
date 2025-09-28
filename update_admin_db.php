<?php
/**
 * Update Admin Database Schema
 *
 * This script will update the database to add role column and create admin user.
 */

require_once 'api/db_connection.php';

// Remove the JSON header that was set in db_connection.php for CLI usage
if (php_sapi_name() === 'cli') {
    header_remove('Content-Type');
}

echo "Updating FashionHub Database for Admin Functionality\n";
echo "==================================================\n\n";

try {
    // Read the SQL file
    $sql = file_get_contents('update_admin_schema.sql');

    if (!$sql) {
        throw new Exception("Could not read update_admin_schema.sql file");
    }

    echo "SQL file contents:\n" . $sql . "\n\n";

    // Split the SQL file into individual statements
    $raw_statements = explode(';', $sql);
    echo "Raw statements count: " . count($raw_statements) . "\n";

    $statements = array_filter(array_map('trim', $raw_statements));
    echo "Filtered statements count: " . count($statements) . "\n";

    foreach ($statements as $i => $stmt) {
        echo "Statement $i: '" . substr($stmt, 0, 50) . "'\n";
    }
    echo "\n";

    $successCount = 0;
    $errorCount = 0;

    foreach ($statements as $statement) {
        $statement = trim($statement);

        // Skip empty statements and comments
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }

        echo "Executing: " . substr($statement, 0, 50) . "...\n";

        try {
            $conn->exec($statement);
            $successCount++;

            // Show progress
            if (strpos($statement, 'ALTER TABLE') !== false) {
                echo "✓ Added role column to users table\n";
            } elseif (strpos($statement, 'INSERT INTO') !== false) {
                echo "✓ Created admin user\n";
            } elseif (strpos($statement, 'UPDATE') !== false) {
                echo "✓ Updated existing users\n";
            }

        } catch (PDOException $e) {
            // Check if it's an acceptable error
            if (strpos($e->getMessage(), 'Duplicate column name') === false &&
                strpos($e->getMessage(), 'Duplicate entry') === false) {
                echo "✗ Error executing statement: " . $e->getMessage() . "\n";
                echo "Statement: " . substr($statement, 0, 100) . "...\n\n";
                $errorCount++;
            } else {
                echo "✓ Operation already completed (skipping)\n";
                $successCount++; // Count as success if it was already done
            }
        }
    }

    echo "\nUpdate completed!\n";
    echo "=================\n";
    echo "Successful operations: $successCount\n";

    if ($errorCount > 0) {
        echo "Errors encountered: $errorCount\n";
    }

    echo "\nAdmin functionality has been added to the database!\n";
    echo "Admin user: admin@fashionhub.com\n";
    echo "Admin password: admin123\n";

} catch (Exception $e) {
    echo "Update failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>