@echo off
cd /d "%~dp0"
start /b "" "%~dp0php_engine\php.exe" artisan serve --port=8000 >nul 2>&1
timeout /t 3 /nobreak >nul
start chrome.exe http://127.0.0.1:8000
