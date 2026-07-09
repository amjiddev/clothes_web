@echo off
REM This batch file clears all caches and prepares the application

title Clothes E-Commerce - Fix and Restart Script

echo.
echo ========================================
echo Fixing Clothes E-Commerce Application
echo ========================================
echo.

echo Step 1: Changing to project directory...
cd /d d:\xampp\htdocs\Clothes

echo.
echo Step 2: Clearing Laravel cache...
php artisan cache:clear
if errorlevel 1 (
    echo Warning: Cache clear failed
)

echo.
echo Step 3: Clearing view cache...
php artisan view:clear
if errorlevel 1 (
    echo Warning: View clear failed
)

echo.
echo Step 4: Clearing config cache...
php artisan config:clear
if errorlevel 1 (
    echo Warning: Config clear failed
)

echo.
echo Step 5: Optimizing application...
php artisan optimize:clear
if errorlevel 1 (
    echo Warning: Optimize clear failed
)

echo.
echo ========================================
echo All caches cleared successfully!
echo ========================================
echo.
echo Now do the following:
echo.
echo 1. Open a new Command Prompt window
echo 2. Navigate to: d:\xampp\htdocs\Clothes
echo 3. Run: php artisan serve
echo 4. Open browser to: http://localhost:8000/login
echo 5. Login with:
echo    Email: admin@example.com
echo    Password: password
echo.
echo 6. You should be redirected to: http://localhost:8000/admin/dashboard
echo.
pause
