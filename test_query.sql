SELECT 
    c.id,
    c.name,
    c.slug,
    COUNT(p.id) as active_products_count
FROM categories c
LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
GROUP BY c.id, c.name, c.slug
ORDER BY c.id;
