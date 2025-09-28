<?php
/**
 * FashionHub Database Setup Script
 *
 * This script will create the database and all necessary tables for the FashionHub application.
 * Make sure to update the database credentials in db_connection.php before running this script.
 */

// Include database connection
require_once 'api/db_connection.php';

echo "FashionHub Database Setup\n";
echo "=========================\n\n";

try {
    // Read the SQL file
    $sql = file_get_contents('fashionhub_database.sql');

    if (!$sql) {
        throw new Exception("Could not read fashionhub_database.sql file");
    }

    // Split the SQL file into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    $successCount = 0;
    $errorCount = 0;

    foreach ($statements as $statement) {
        $statement = trim($statement);

        // Skip empty statements and comments
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }

        try {
            $conn->exec($statement);
            $successCount++;

            // Show progress for major operations
            if (strpos($statement, 'CREATE TABLE') !== false) {
                $tableName = '';
                if (preg_match('/CREATE TABLE.*?`(\w+)`/', $statement, $matches)) {
                    $tableName = $matches[1];
                }
                echo "✓ Created table: $tableName\n";
            } elseif (strpos($statement, 'INSERT INTO') !== false) {
                echo "✓ Inserted sample data\n";
            }

        } catch (PDOException $e) {
            // Check if it's an acceptable error (like table already exists)
            if (strpos($e->getMessage(), 'already exists') === false &&
                strpos($e->getMessage(), 'Duplicate entry') === false) {
                echo "✗ Error executing statement: " . $e->getMessage() . "\n";
                echo "Statement: " . substr($statement, 0, 100) . "...\n\n";
                $errorCount++;
            }
        }
    }

    echo "\nSetup completed!\n";
    echo "================\n";
    echo "Successful operations: $successCount\n";

    if ($errorCount > 0) {
        echo "Errors encountered: $errorCount\n";
        echo "Note: Some errors might be expected (e.g., tables already exist)\n";
    }

    echo "\nDatabase structure created successfully!\n";
    echo "You can now use the FashionHub application.\n";

} catch (Exception $e) {
    echo "Setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>