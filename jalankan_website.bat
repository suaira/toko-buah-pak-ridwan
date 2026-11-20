@echo off
title Website Toko Buah Pak Ridwan
color 0B
echo ===================================================================
echo     MEMULAI WEBSITE TOKO BUAH PAK RIDWAN
echo ===================================================================
echo.
echo Website dapat diakses di: http://localhost:8000
echo.
echo Membuka website di browser Anda...
start http://localhost:8000
echo.
php artisan serve
pause
