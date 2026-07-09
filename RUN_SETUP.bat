@echo off
echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║  Super Admin Dashboard Setup                          ║
echo ╚════════════════════════════════════════════════════════╝
echo.

cd /d "d:\xampp\htdocs\Clothes"

echo Step 1: Running Migrations...
echo.
call php artisan migrate --force
echo.

echo Step 2: Running Seeder...
echo.
call php artisan db:seed --class=SuperAdminSeeder
echo.

echo ╔════════════════════════════════════════════════════════╗
echo ║  ✅ Setup Complete!                                   ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo 📧 Email:    admin@clothes.local
echo 🔑 Password: admin@123
echo 👤 Role:     Super Admin
echo.
echo 🌐 Login URL:  http://localhost:8000/login
echo 📊 Dashboard:  http://localhost:8000/admin/dashboard
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
pause
