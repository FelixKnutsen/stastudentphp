@echo off
echo ===================================================
echo   Starting STA WordPress Local Browser Preview
echo ===================================================
echo.
echo Server running at: http://localhost:8080
echo Opening your web browser...
echo.

start "" http://localhost:8080

cd /d "%~dp0local-test\wordpress"
php -S localhost:8080 router.php
pause
