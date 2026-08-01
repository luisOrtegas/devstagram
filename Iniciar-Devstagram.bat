@echo off
setlocal
title Devstagram local
cd /d "%~dp0"

echo.
echo ========================================
echo        Iniciando Devstagram local
echo ========================================
echo.

where php >nul 2>&1
if errorlevel 1 (
    echo ERROR: PHP no esta disponible en PATH.
    echo Abre PowerShell y ejecuta: php --version
    echo.
    pause
    exit /b 1
)

where composer >nul 2>&1
if errorlevel 1 (
    echo ERROR: Composer no esta disponible en PATH.
    echo Abre PowerShell y ejecuta: composer --version
    echo.
    pause
    exit /b 1
)

php -m | findstr /i /x "pdo_sqlite" >nul 2>&1
if errorlevel 1 (
    echo ERROR: PHP no tiene habilitada la extension pdo_sqlite.
    echo Habilita extension=pdo_sqlite en el archivo php.ini.
    echo.
    pause
    exit /b 1
)

if not exist "vendor\autoload.php" (
    echo Preparando las dependencias de Laravel por primera vez...
    call composer install --no-interaction --prefer-dist
    if errorlevel 1 goto :error
)

if not exist ".env" (
    echo Creando la configuracion local...
    copy /y ".env.local.example" ".env" >nul
    php artisan key:generate --force
    if errorlevel 1 goto :error
)

if not exist "database\database.sqlite" (
    echo Creando la base de datos SQLite...
    type nul > "database\database.sqlite"
)

echo Actualizando la base de datos...
php artisan migrate --force
if errorlevel 1 goto :error

if not exist "public\build\manifest.json" (
    echo ERROR: No se encontraron los recursos compilados de la interfaz.
    echo Ejecuta npm install y npm run build desde la terminal de VS Code.
    echo.
    pause
    exit /b 1
)

echo.
echo Devstagram estara disponible en http://127.0.0.1:8000
echo Para detenerlo, cierra esta ventana o presiona Ctrl+C.
echo.
start "" "http://127.0.0.1:8000"
php artisan serve --host=127.0.0.1 --port=8000
exit /b 0

:error
echo.
echo No fue posible preparar Devstagram. Revisa el mensaje anterior.
echo.
pause
exit /b 1
