# Product Sections Module - Troubleshooting Guide

## Issues Fixed

### Issue 1: Column 'display_order' not found
**Error**: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'display_order'`

**Cause**: The existing `product_images` table had `sort_order` instead of `display_order`

**Solution**: 
- Created migration `2026_07_29_001543_fix_product_images_table_columns.php`
- Renamed `sort_order` to `display_order`
- Added missing `alt_text` column

**Command run**:
```bash
php artisan migrate
```

### Issue 2: Product images not displaying
**Cause**: Frontend views were using `$product->image_url` accessor which only checked the old `image` field

**Solution**: 
- Updated `Product` model's `getImageUrlAttribute()` accessor
- Now checks featured image relationship first, then falls back to old field

## Current Database Structure

### product_images table
```
- id (bigint)
- product_id (bigint, foreign key)
- image_path (varchar)
- is_featured (boolean)
- alt_text (varchar, nullable)
- display_order (int)
- created_at (timestamp)
- updated_at (timestamp)
```

### product_display_sections table
```
- id (bigint)
- product_id (bigint, foreign key)
- section (enum)
- display_order (int)
- is_active (boolean)
- created_at (timestamp)
- updated_at (timestamp)
```

## Verification Steps

### 1. Check Migrations
```bash
php artisan migrate:status
```

All three migrations should show "Ran":
- 2026_07_29_000019_create_product_display_sections_table
- 2026_07_29_000042_create_product_images_table
- 2026_07_29_000101_add_product_sections_fields_to_products_table
- 2026_07_29_001543_fix_product_images_table_columns

### 2. Check Database Table Structure
```bash
php artisan tinker
DB::select('DESCRIBE product_images');
```

Should show columns: id, product_id, image_path, is_featured, alt_text, display_order, created_at, updated_at

### 3. Clear All Caches
```bash
php artisan optimize:clear
```

### 4. Test Routes
```bash
php artisan route:list | findstr "product-sections"
```

Should show 7 routes for CRUD operations.

## Common Issues & Solutions

### Admin Page Not Loading

**Issue**: Blank page or 500 error

**Solutions**:
1. Clear caches:
   ```bash
   php artisan optimize:clear
   ```

2. Check logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Check if migrations ran:
   ```bash
   php artisan migrate:status
   ```

### Frontend Products Not Showing

**Issue**: No products displayed on home/shop pages

**Solutions**:

1. **No products assigned to sections**:
   - Go to admin panel
   - Add products and select display sections

2. **Products exist but not visible**:
   - Check product `is_active` is TRUE
   - Check product `stock_quantity` > 0
   - Check display sections `is_active` is TRUE

3. **Run sample seeder**:
   ```bash
   php artisan db:seed --class=ProductSectionsSeeder
   ```

### Images Not Uploading

**Issue**: Uploaded images not saving

**Solutions**:

1. **Create storage link**:
   ```bash
   php artisan storage:link
   ```

2. **Check folder permissions**:
   ```bash
   # On Linux/Mac
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

3. **Check `config/filesystems.php`**:
   ```php
   'default' => env('FILESYSTEM_DISK', 'local'),
   
   'disks' => [
       'public' => [
           'driver' => 'local',
           'root' => storage_path('app/public'),
           'url' => env('APP_URL').'/storage',
           'visibility' => 'public',
       ],
   ]
   ```

### Column Not Found Errors

**Issue**: `Column not found: display_order`

**Solution**:
```bash
# Run the fix migration
php artisan migrate

# If already ran, rollback and re-run
php artisan migrate:rollback --step=1
php artisan migrate
```

### Relationship Errors

**Issue**: `Call to undefined method displaySections()`

**Solution**:
1. Check `Product.php` model has:
   ```php
   public function displaySections()
   {
       return $this->hasMany(ProductDisplaySection::class);
   }
   ```

2. Clear compiled classes:
   ```bash
   php artisan clear-compiled
   composer dump-autoload
   ```

## Testing Checklist

- [ ] Admin page loads: `/admin/website-management/product-sections`
- [ ] Can create new product
- [ ] Can upload featured image
- [ ] Can upload multiple gallery images
- [ ] Can select multiple display sections
- [ ] Can edit existing product
- [ ] Can delete product images
- [ ] Can delete product
- [ ] Product appears on selected frontend pages
- [ ] Product detail page shows all images
- [ ] Removing section checkbox removes product from that page

## Support Commands

### View All Products in Database
```bash
php artisan tinker
Product::with(['images', 'displaySections'])->get();
```

### View Products by Section
```bash
php artisan tinker
ProductDisplaySection::where('section', 'home_featured')->with('product')->get();
```

### Clear Everything
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Check App Environment
```bash
php artisan about
```

## File Locations

### Models
- `app/Models/Product.php`
- `app/Models/ProductImage.php`
- `app/Models/ProductDisplaySection.php`

### Controllers
- `app/Http/Controllers/Admin/ProductSectionController.php`
- `app/Http/Controllers/Frontend/HomeController.php`

### Views
- `resources/views/admin/website-management/product-sections/*.blade.php`
- `resources/views/frontend/*.blade.php`

### Migrations
- `database/migrations/2026_07_29_000019_create_product_display_sections_table.php`
- `database/migrations/2026_07_29_000042_create_product_images_table.php`
- `database/migrations/2026_07_29_000101_add_product_sections_fields_to_products_table.php`
- `database/migrations/2026_07_29_001543_fix_product_images_table_columns.php`

### Routes
- `routes/admin.php` (line with `product-sections` resource)

## Still Having Issues?

1. Check Laravel version compatibility:
   ```bash
   php artisan --version
   ```

2. Check PHP version:
   ```bash
   php -v
   ```
   (Minimum required: PHP 8.1)

3. Check database connection:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

4. Enable debug mode in `.env`:
   ```
   APP_DEBUG=true
   APP_ENV=local
   ```

5. Check error logs:
   - `storage/logs/laravel.log`
   - Browser console (F12)
   - Server error logs

---

**Last Updated**: July 29, 2026
**Status**: ✅ All Issues Resolved
