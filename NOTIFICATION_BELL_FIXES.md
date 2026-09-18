# 🔔 Notification Bell System - Debugging & Fixes

## Critical Issue Found & Fixed ✅

**PSR-4 Autoload Compliance Violation:**
- File: `app/View/Components/BuyFormModal.php` 
- Class: `ProductBuyFormModal`
- **Problem**: Class name didn't match filename (PSR-4 standard requires they match)
- **Impact**: This broke Laravel's autoloader, preventing all Artisan commands and the app from loading properly
- **Fix**: File renamed to `app/View/Components/ProductBuyFormModal.php` ✅

## Steps to Complete the Fix

### Step 1: Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 2: Regenerate Autoloader
```bash
composer dump-autoload -o
```

### Step 3: Run Migrations (if not already run)
```bash
php artisan migrate
```

This will add the `is_seen` and `seen_at` columns to:
- `orders` table
- `customer_measurements` table

### Step 4: Hard Refresh Your Browser
- **Chrome/Firefox**: `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
- This clears cached JavaScript and forces the bell to reload

### Step 5: Test the API

#### Option A: Using the test tool (recommended)
1. Visit: `http://localhost/cloth/public/test-notification-api.php`
2. Click each button to test the endpoints
3. Check browser console (F12) for detailed error info

#### Option B: Check database schema
1. Visit: `http://localhost/cloth/public/check-db-schema.php`
2. Verify that `is_seen`, `seen_at`, `is_read`, `read_at` columns exist
3. If they don't exist, run `php artisan migrate` again

## System Architecture

### Components
- **NotificationController**: `app/Http/Controllers/NotificationController.php` (5 endpoints)
- **Blade Component**: `resources/views/components/notification-bell.blade.php` (bell UI + JS)
- **Models**: Order, CustomerMeasurement, ContactSubmission (all have `unseen()` or `unread()` scopes)

### Routes
Both `/admin` and `/receptionist` routes have:
```
GET    /notifications              (get all unseen notifications)
GET    /notifications/unread-count (get badge count)
POST   /notifications/mark-as-seen (mark one as seen)
POST   /notifications/mark-all-as-seen (mark all as seen)
GET    /notifications/details      (get single notification)
```

### Notifications Show
1. **Orders** (field: `is_seen`)
2. **Contact Messages** (field: `is_read`)
3. **Measurements** (field: `is_seen`)

## Common Issues & Solutions

### Issue: "Failed to load notifications. Please try again."

**Possible Causes:**

1. **Migrations not run**
   - Solution: `php artisan migrate`

2. **Routes not loaded**
   - Solution: `php artisan route:clear` + browser refresh

3. **Autoloader issues**
   - Solution: `composer dump-autoload -o`

4. **CSRF token missing**
   - Check: `<meta name="csrf-token" content="...">` exists in your layout
   - Should be in: `resources/views/admin/layouts/app.blade.php`

5. **API returning 404**
   - Routes aren't registered properly
   - Run the test tool at `/public/test-notification-api.php`

6. **API returning 500**
   - Database error (columns don't exist)
   - Model method issue
   - Check Laravel logs: `storage/logs/laravel.log`

### Issue: Badge doesn't show count

**Solutions:**
- Check if there are actually unseen items in database
- Verify `is_seen` column exists: `check-db-schema.php`
- Check browser console for JS errors (F12)

### Issue: Clicking bell does nothing

**Solutions:**
- Check browser console for errors
- Verify Bootstrap modal JS is loaded
- Try refreshing page

## File Checklist

✅ **Required Files Created:**
- [ ] `app/Http/Controllers/NotificationController.php`
- [ ] `resources/views/components/notification-bell.blade.php`
- [ ] `database/migrations/2026_09_14_000001_add_is_seen_to_orders_table.php`
- [ ] `database/migrations/2026_09_14_000002_add_is_seen_to_customer_measurements_table.php`

✅ **Required Files Modified:**
- [ ] `app/Models/Order.php` (added is_seen methods)
- [ ] `app/Models/CustomerMeasurement.php` (added is_seen methods)
- [ ] `app/Models/ContactSubmission.php` (enhanced is_read methods)
- [ ] `routes/admin.php` (added notification routes)
- [ ] `routes/receptionist.php` (added notification routes)
- [ ] `resources/views/admin/components/topbar.blade.php` (@include notification-bell)
- [ ] `resources/views/receptionist/layouts/app.blade.php` (@include notification-bell)

✅ **Fixed:**
- [ ] `app/View/Components/BuyFormModal.php` → `ProductBuyFormModal.php` (PSR-4 fix)

## Next Steps if Still Not Working

1. **Enable Debug Mode:**
   - Set `APP_DEBUG=true` in `.env`
   - Check `storage/logs/laravel.log` for errors

2. **Open Browser Console:**
   - Press `F12` in browser
   - Go to Console tab
   - Click notification bell
   - Look for detailed error messages
   - **Take a screenshot and share the error**

3. **Check Database:**
   - Visit `/public/check-db-schema.php`
   - Verify all required columns exist
   - If not, run `php artisan migrate --force`

4. **Test the API directly:**
   - Visit `/public/test-notification-api.php`
   - Click each test button
   - Share the exact HTTP responses

## Testing Commands

```bash
# Test if tables have the right columns
php artisan tinker
>>> DB::select("DESCRIBE orders");  # Check for is_seen, seen_at
>>> DB::select("DESCRIBE customer_measurements");
```

## Support

If the issue persists after following these steps:
1. Share the **browser console error** (F12)
2. Share the **Laravel log error** (`storage/logs/laravel.log`)
3. Share the **test API results** (from `/public/test-notification-api.php`)
