@echo off
setlocal

REM Script untuk mengimpor data obat dari SatuSehat API secara otomatis
REM 
REM Cara penggunaan:
REM import_medications.bat [jumlah] [kategori1,kategori2,...]
REM 
REM Contoh:
REM import_medications.bat 100 antibiotik,vitamin

cd /d "%~dp0.."

set COUNT=100
set CATEGORIES=

if not "%~1"=="" set COUNT=%~1
if not "%~2"=="" set CATEGORIES=%~2

if "%CATEGORIES%"=="" (
    php artisan satusehat:import-medications %COUNT%
) else (
    for %%C in (%CATEGORIES:,= %) do (
        set CMD=!CMD! --category=%%C
    )
    php artisan satusehat:import-medications %COUNT% !CMD!
)

echo.
echo Import selesai.
echo.

pause