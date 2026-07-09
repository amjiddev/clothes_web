<?php

// Direct database connection
$dbHost = '127.0.0.1';
$dbName = 'suit';
$dbUser = 'root';
$dbPass = '';

try {
    $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "\n╔════════════════════════════════════════════════════════╗\n";
    echo "║     Creating Demo User                                ║\n";
    echo "╚════════════════════════════════════════════════════════╝\n\n";

    // Hash the password using bcrypt
    $hashedPassword = password_hash('demoadmin', PASSWORD_BCRYPT);
    
    // Check if user exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute(['demo@demo.com']);
    $userExists = $checkStmt->fetch();

    if (!$userExists) {
        // Insert user
        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW(), NOW())
        ");
        
        $stmt->execute([
            'Demo User',
            'demo@demo.com',
            $hashedPassword
        ]);

        $userId = $conn->lastInsertId();
        echo "✅ Demo user created successfully (ID: $userId)\n";
    } else {
        $userId = $userExists['id'];
        echo "✅ Demo user already exists (ID: $userId)\n";
        
        // Update password
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, email_verified_at = NOW() WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $userId]);
        echo "✅ Password updated to: demoadmin\n";
    }

    // Check if super_admin role exists, if not create it
    $roleStmt = $conn->prepare("SELECT id FROM roles WHERE name = ?");
    $roleStmt->execute(['super_admin']);
    $roleResult = $roleStmt->fetch();

    if (!$roleResult) {
        // Create role
        $insertRole = $conn->prepare("
            INSERT INTO roles (name, guard_name, created_at, updated_at)
            VALUES (?, ?, NOW(), NOW())
        ");
        $insertRole->execute(['super_admin', 'web']);
        $roleId = $conn->lastInsertId();
        echo "✅ Super Admin role created (ID: $roleId)\n";
    } else {
        $roleId = $roleResult['id'];
        echo "✅ Super Admin role exists (ID: $roleId)\n";
    }

    // Assign role to user
    $assignStmt = $conn->prepare("
        DELETE FROM model_has_roles WHERE model_id = ? AND model_type = ?
    ");
    $assignStmt->execute([$userId, 'App\\Models\\User']);

    $assignRoleStmt = $conn->prepare("
        INSERT INTO model_has_roles (role_id, model_type, model_id)
        VALUES (?, ?, ?)
    ");
    $assignRoleStmt->execute([$roleId, 'App\\Models\\User', $userId]);
    echo "✅ Super Admin role assigned to user\n\n";

    // Display credentials
    echo "╔════════════════════════════════════════════════════════╗\n";
    echo "║              ✅ Setup Complete!                       ║\n";
    echo "╚════════════════════════════════════════════════════════╝\n\n";
    echo "📧 Email:    demo@demo.com\n";
    echo "🔑 Password: demoadmin\n";
    echo "👤 Role:     Super Admin\n\n";
    echo "🌐 Login URL:  http://localhost:8000/login\n";
    echo "📊 Dashboard:  http://localhost:8000/admin/dashboard\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "\nMake sure:\n";
    echo "1. MySQL is running (XAMPP Control Panel)\n";
    echo "2. Database 'suit' exists\n";
    echo "3. Users table exists\n";
    echo "4. Run migrations: php artisan migrate --force\n";
}

$conn = null;
?>
