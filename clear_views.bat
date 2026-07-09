@echo off
cd /d d:\xampp\htdocs\Clothes\storage\framework\views

for /f %%f in ('dir /b *.php ^| findstr /v ".gitignore"') do (
    echo Deleting %%f
    del /q "%%f" 2>nul
)

echo.
echo All view cache files cleared!
echo.
pause
