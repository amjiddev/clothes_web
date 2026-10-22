<?php
/**
 * Direct database update script
 * Updates order types from 'ready_made' to 'cloth'
 */

// Database connection
$host = '127.0.0.1';
$db = 'cloth';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully\n";
    
    // Step 1: Check current enum values
    $result = $pdo->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
                           WHERE TABLE_NAME='orders' AND COLUMN_NAME='type'");
    $row = $result->fetch();
    echo "Current enum: " . $row['COLUMN_TYPE'] . "\n";
    
    // Step 2: Update data
    echo "\nUpdating order types...\n";
    $stmt = $pdo->prepare("UPDATE orders SET type = 'cloth' WHERE type = 'ready_made'");
    $stmt->execute();
    $count = $stmt->rowCount();
    echo "Updated $count orders from 'ready_made' to 'cloth'\n";
    
    // Step 3: Modify enum column
    echo "\nModifying enum column...\n";
    $pdo->exec("ALTER TABLE orders MODIFY COLUMN type ENUM('cloth', 'stitching', 'combined') DEFAULT 'cloth'");
    echo "Enum column modified successfully\n";
    
    // Step 4: Verify
    $result = $pdo->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
                           WHERE TABLE_NAME='orders' AND COLUMN_NAME='type'");
    $row = $result->fetch();
    echo "Updated enum: " . $row['COLUMN_TYPE'] . "\n";
    
    // Count current order types
    $result = $pdo->query("SELECT type, COUNT(*) as count FROM orders GROUP BY type");
    echo "\nOrder type distribution:\n";
    while ($row = $result->fetch()) {
        echo "  " . $row['type'] . ": " . $row['count'] . "\n";
    }
    
    echo "\n✓ Database update completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
}
