@echo off
echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║  Creating Demo User                                   ║
echo ╚════════════════════════════════════════════════════════╝
echo.

cd /d "d:\xampp\htdocs\Clothes"

echo Running DemoUserSeeder...
echo.

php artisan db:seed --class=DemoUserSeeder

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║  ✅ Demo User Created Successfully!                  ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo 📧 Email:    demo@demo.com
echo 🔑 Password: demoadmin
echo 👤 Role:     Super Admin
echo.
echo 🌐 Login URL:  http://localhost:8000/login
echo 📊 Dashboard:  http://localhost:8000/admin/dashboard
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.
pause
