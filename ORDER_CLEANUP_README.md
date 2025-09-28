# Order Cleanup System

This document explains how to set up automatic cleanup of cancelled orders after 24 hours.

## Overview

The system automatically removes cancelled orders from the database after 24 hours to keep the database clean and improve performance.

## Files

- `api/cleanup_cancelled_orders.php` - PHP script that performs the cleanup
- `cleanup_orders.bat` - Windows batch file to run the cleanup script
- `api/logs/order_cleanup.log` - Log file for successful cleanups
- `api/logs/order_cleanup_error.log` - Log file for errors

## Setup Instructions

### For Windows (XAMPP)

1. **Create a Scheduled Task:**
   - Open Windows Task Scheduler
   - Click "Create Basic Task"
   - Name: "FashionHub Order Cleanup"
   - Description: "Remove cancelled orders older than 24 hours"
   - Trigger: Daily at your preferred time (e.g., 2:00 AM)
   - Action: Start a program
   - Program/script: `C:\path\to\fashionhub-old\cleanup_orders.bat`
   - Start in: `C:\path\to\fashionhub-old`

2. **Alternative: Manual Execution**
   You can also run the batch file manually or set up a different scheduling method.

### For Linux/Unix (Production)

1. **Add to crontab:**
   ```bash
   # Edit crontab
   crontab -e

   # Add this line to run daily at 2:00 AM
   0 2 * * * /usr/bin/php /path/to/fashionhub-old/api/cleanup_cancelled_orders.php
   ```

2. **Or use the batch file approach:**
   ```bash
   0 2 * * * /path/to/fashionhub-old/cleanup_orders.sh
   ```

## What the Cleanup Does

1. Finds all orders with status 'cancelled' that are older than 24 hours
2. Deletes associated order items first
3. Deletes the cancelled orders
4. Logs the cleanup activity

## Monitoring

- Check `api/logs/order_cleanup.log` for successful cleanups
- Check `api/logs/order_cleanup_error.log` for any errors
- The script will output the number of orders cleaned up

## Testing

To test the cleanup script manually:

```bash
# Windows
php api/cleanup_cancelled_orders.php

# Or using the batch file
cleanup_orders.bat
```

## Safety Features

- Only removes orders that are truly cancelled
- Uses proper database transactions
- Comprehensive error logging
- Safe deletion order (items before orders)

## Troubleshooting

1. **Permission Issues:** Make sure the web server/PHP has write permissions to the logs directory
2. **PHP Path:** Update the batch file with the correct PHP executable path
3. **Database Connection:** Ensure the database connection settings are correct in `api/config.php`
4. **Time Zone:** The script uses server time, ensure your server time is set correctly