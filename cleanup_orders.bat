@echo off
REM FashionHub Order Cleanup Script
REM This script runs the PHP cleanup script to remove cancelled orders after 24 hours
REM Schedule this to run daily using Windows Task Scheduler

echo [%DATE% %TIME%] Starting order cleanup...
cd /d "%~dp0"

REM Run the PHP cleanup script
php api/cleanup_cancelled_orders.php

if %ERRORLEVEL% EQU 0 (
    echo [%DATE% %TIME%] Order cleanup completed successfully.
) else (
    echo [%DATE% %TIME%] Order cleanup failed with error code %ERRORLEVEL%.
)

echo [%DATE% %TIME%] Order cleanup script finished.