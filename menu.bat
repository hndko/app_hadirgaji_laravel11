@echo off
:menu
cls
echo ===========================
echo        Menu Utama
echo ===========================
echo 1. Jalankan Program (php artisan serve)
echo 2. Perbarui Program (git pull)
echo 3. Reset Table Database (php artisan migrate:fresh --seed)
echo 4. Keluar
echo ===========================
set /p choice="Pilih opsi [1-4]: "

if "%choice%"=="1" (
    echo Menjalankan program...
    start cmd /c "php artisan serve"
    timeout 5  :: Menunggu beberapa detik agar server siap
    start http://127.0.0.1:8000/  :: Ganti URL jika menggunakan port berbeda
    pause
    goto menu
) else if "%choice%"=="2" (
    echo Memperbarui program...
    git pull
    pause
    goto menu
) else if "%choice%"=="3" (
    echo Menjalankan program...
    start cmd /c "php artisan migrate:fresh --seed"
    pause
    goto menu
) else if "%choice%"=="4" (
    echo Keluar...
    exit
) else (
    echo Pilihan tidak valid, silahkan coba lagi.
    pause
    goto menu
)
