@echo off
cd /d "C:\xampp\htdocs\RentMate"
php artisan schedule:run >> storage\logs\scheduler.log 2>&1
