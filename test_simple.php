<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=suit', 'root', '');
$stmt = $pdo->query('SELECT c.id, c.name, c.slug, COUNT(p.id) as cnt FROM categories c LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1 GROUP BY c.id ORDER BY c.id');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ' | ' . $row['name'] . ' | ' . $row['slug'] . ' | ' . $row['cnt'] . "\n";
}
?>
