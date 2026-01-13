@echo off
echo ========================================
echo  SISTEMA DE GESTION DE CLIENTES
echo ========================================
echo.
echo Iniciando servidor PHP en el puerto 8000...
echo.
echo IMPORTANTE: Asegurate de tener MySQL corriendo en XAMPP
echo.
echo Accede al sistema en:
echo http://localhost:8000/CapaPresentacion/index.html
echo.
echo Presiona Ctrl+C para detener el servidor
echo ========================================
cd /d "%~dp0"
php -S localhost:8000
pause
