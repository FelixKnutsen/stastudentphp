@echo off
echo Stopping local PHP server on port 8080...
powershell -Command "Get-Process -Name php -ErrorAction SilentlyContinue | Stop-Process -Force"
echo Local server stopped.
pause
