@echo off
title Setup Database Toko Buah Pak Ridwan
color 0A
echo ===================================================================
echo     SETUP OTOMATIS DATABASE TOKO BUAH PAK RIDWAN (LARAGON MYSQL)
echo ===================================================================
echo.
echo Pastikan tombol "Start All" di Laragon sudah dinyalakan.
echo Sedang membuat database dan meng-import seluruh 11 tabel beserta data awal...
echo.

if exist "D:\Syafiqah_BPF\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe" (
    "D:\Syafiqah_BPF\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS toko_buah_pak_ridwan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    "D:\Syafiqah_BPF\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe" -u root toko_buah_pak_ridwan < "%~dp0database\toko_buah_pak_ridwan.sql"
    echo [BERHASIL] Database MySQL 'toko_buah_pak_ridwan' sukses di-import tanpa perlu buka HeidiSQL!
) else (
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS toko_buah_pak_ridwan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    mysql -u root toko_buah_pak_ridwan < "%~dp0database\toko_buah_pak_ridwan.sql"
    echo [BERHASIL] Database MySQL 'toko_buah_pak_ridwan' sukses di-import!
)

echo.
echo ===================================================================
echo Akun Login yang Tersedia:
echo 1. Admin (Pak Ridwan) : admin@tokobuahpakridwan.com  (Password: password)
echo 2. Kasir (Siti)       : kasir@tokobuahpakridwan.com  (Password: password)
echo 3. Customer (Pika)    : pika@gmail.com               (Password: password)
echo ===================================================================
echo.
pause
