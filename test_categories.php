<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Product;

// Get all categories
echo "=== CATEGORIES ===\n";
$categories = Category::all();
foreach ($categories as $cat) {
    $productCount = $cat->products()->where('is_active', 1)->count();
    echo "ID: {$cat->id}, Name: {$cat->name}, Slug: {$cat->slug}, Active Products: {$productCount}\n";
}

// Get some products with their categories
echo "\n=== PRODUCTS ===\n";
$products = Product::where('is_active', 1)->take(10)->get();
foreach ($products as $prod) {
    echo "ID: {$prod->id}, Name: {$prod->name}, Category ID: {$prod->category_id}, Category: " . ($prod->category ? $prod->category->name : 'NULL') . "\n";
}

// Show products with NO category
echo "\n=== PRODUCTS WITH NO CATEGORY ===\n";
$noCategoryCount = Product::where('category_id', null)->where('is_active', 1)->count();
echo "Count: {$noCategoryCount}\n";
