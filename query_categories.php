<?php

$host = '127.0.0.1';
$db = 'suit';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT 
        c.id,
        c.name,
        c.slug,
        COUNT(p.id) as active_products_count
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
    GROUP BY c.id, c.name, c.slug
    ORDER BY c.id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Categories with Active Products Count:\n";
    echo str_repeat('=', 80) . "\n";
    printf("%-5s %-25s %-30s %-15s\n", 'ID', 'Name', 'Slug', 'Active Products');
    echo str_repeat('-', 80) . "\n";
    
    foreach ($results as $row) {
        printf("%-5d %-25s %-30s %-15d\n", 
            $row['id'], 
            $row['name'], 
            $row['slug'], 
            $row['active_products_count']
        );
    }
    echo str_repeat('=', 80) . "\n";
    echo 'Total Categories: ' . count($results) . "\n";
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
?>
