@echo off
title Tunel publico - Tienda POS
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0start-tunnel.ps1"
echo.
pause
