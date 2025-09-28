# FashionHub Database Setup

This document explains how to set up the MySQL database for the FashionHub e-commerce application.

## Files Included

- `fashionhub_database.sql` - Complete database schema and sample data
- `setup_database.php` - PHP script to automatically create the database
- `api/db_connection.php` - Database connection configuration

## Database Structure

The database consists of the following tables:

### Core Tables
- **`users`** - User accounts and authentication
- **`products`** - Product catalog with variants
- **`orders`** - Order management
- **`order_items`** - Individual items within orders

### User Data Tables
- **`addresses`** - User billing and shipping addresses
- **`cart`** - Shopping cart items
- **`wishlist`** - User wishlists

### Authentication Tables
- **`remember_tokens`** - "Remember me" functionality

## Setup Instructions

### Method 1: Using PHP Setup Script (Recommended)

1. **Update Database Credentials**
   ```php
   // In api/db_connection.php, update these lines:
   $host = 'localhost';
   $dbname = 'fashionhub-old';
   $username = 'your_mysql_username';
   $password = 'your_mysql_password';
   ```

2. **Run the Setup Script**
   ```bash
   php setup_database.php
   ```

   The script will:
   - Create the database if it doesn't exist
   - Create all tables with proper structure
   - Insert sample product data
   - Show progress and any errors

### Method 2: Manual MySQL Import

1. **Create Database**
   ```sql
   CREATE DATABASE fashionhub-old CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Import SQL File**
   ```bash
   mysql -u your_username -p fashionhub-old < fashionhub_database.sql
   ```

## Table Details

### Users Table
```sql
- id (Primary Key)
- name (User's full name)
- email (Unique email address)
- password (Hashed password)
- created_at, updated_at (Timestamps)
```

### Products Table
```sql
- id (Primary Key)
- title, category, price, old_price
- image (Product image URL)
- colors, sizes (JSON arrays)
- rating, reviews (Product reviews)
- badge (Sale, new, etc.)
- featured, new_arrival (Boolean flags)
- sku (Unique product code)
- description, features (JSON)
- color_codes (JSON color mappings)
```

### Orders Table
```sql
- id (Primary Key)
- user_id (Foreign Key to users)
- order_number (Unique order identifier)
- order_date, status
- total_amount, subtotal_amount, payment_fee
- payment_method, payment_status
- shipping_address, billing_address (JSON)
```

### Addresses Table
```sql
- id (Primary Key)
- user_id (Foreign Key to users)
- type (billing/shipping)
- name, street, city, state, zip, country
```

### Cart & Wishlist Tables
```sql
- id (Primary Key)
- user_id, product_id (Foreign Keys)
- quantity, size, color (For cart)
- added_at (Timestamp)
```

## Sample Data

The database includes sample products from the JavaScript data in `account.html`:

- Premium Cotton Shirt
- Slim Fit Jeans
- Classic Denim Jacket
- Casual Summer Dress
- Leather Crossbody Bag
- And 7 more products...

## API Integration

The database is designed to work with the existing API endpoints:

- `check_auth.php` - User authentication
- `get_orders.php` - Order history
- `get_addresses.php` - User addresses
- `get_wishlist.php` - Wishlist items
- `get_cart.php` - Cart items
- `create_order.php` - Order creation
- `save_addresses.php` - Address management
- `update_account.php` - Account updates

## Security Features

- Password hashing using `password_hash()`
- Prepared statements for SQL injection prevention
- Session-based authentication
- "Remember me" tokens with expiration
- Input validation and sanitization

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check MySQL server is running
   - Verify credentials in `db_connection.php`
   - Ensure user has proper permissions

2. **Table Already Exists**
   - The setup script handles this gracefully
   - Use `DROP DATABASE` if you want to start fresh

3. **Foreign Key Errors**
   - Ensure tables are created in the correct order
   - Check that referenced records exist

### Manual Verification

After setup, you can verify the database:

```sql
USE fashionhub-old;
SHOW TABLES;
DESCRIBE users;
SELECT COUNT(*) FROM products;
```

## Production Considerations

For production deployment:

1. **Update Database Credentials**
   - Use environment variables for sensitive data
   - Create a dedicated database user with limited permissions

2. **Enable SSL**
   - Configure MySQL for SSL connections
   - Update connection string accordingly

3. **Backup Strategy**
   - Set up automated database backups
   - Test restore procedures regularly

4. **Performance Optimization**
   - Add appropriate indexes
   - Consider partitioning for large tables
   - Monitor query performance

## Support

If you encounter issues:

1. Check the PHP error logs
2. Verify MySQL error logs
3. Ensure all required PHP extensions are installed
4. Test database connectivity manually

The database structure is fully compatible with the existing FashionHub application code.