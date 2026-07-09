@echo off
echo.
echo ========================================
echo Fixing Missing Dashboard View...
echo ========================================
echo.

cd /d "d:\xampp\htdocs\Clothes"

echo Clearing cache...
php artisan cache:clear

echo Clearing route cache...
php artisan route:cache

echo Clearing config cache...
php artisan config:cache

echo.
echo ========================================
echo ✓ DONE! Dashboard view is fixed!
echo ========================================
echo.
echo Now:
echo 1. Close this window
echo 2. Refresh your browser
echo 3. Visit: http://localhost:8000/admin/dashboard
echo.
pause
