@echo off
echo.
echo ========================================
echo Fixing Validation Error...
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
echo ✓ DONE! Validation error is fixed!
echo ========================================
echo.
echo Now:
echo 1. Close this window
echo 2. Refresh your browser (Ctrl + Shift + Delete to clear browser cache)
echo 3. Try adding receptionist again
echo.
pause
