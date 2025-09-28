-- FashionHub Database Structure
-- Generated for FashionHub e-commerce application
-- Database: fashionhub-old

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `fashionhub-old` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fashionhub-old`;

-- ==============================================
-- USERS TABLE
-- ==============================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- PRODUCTS TABLE
-- ==============================================
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `old_price` DECIMAL(10,2) NULL DEFAULT NULL,
    `image` VARCHAR(500) NOT NULL,
    `colors` JSON NOT NULL,
    `sizes` JSON NOT NULL,
    `rating` DECIMAL(3,2) DEFAULT 0.00,
    `reviews` INT(11) DEFAULT 0,
    `badge` VARCHAR(50) NULL DEFAULT NULL,
    `featured` BOOLEAN DEFAULT FALSE,
    `new_arrival` BOOLEAN DEFAULT FALSE,
    `sku` VARCHAR(100) UNIQUE,
    `description` TEXT,
    `features` JSON,
    `color_codes` JSON,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_category` (`category`),
    INDEX `idx_featured` (`featured`),
    INDEX `idx_new_arrival` (`new_arrival`),
    INDEX `idx_sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- ORDERS TABLE
-- ==============================================
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    `total_amount` DECIMAL(10,2) NOT NULL,
    `subtotal_amount` DECIMAL(10,2) NULL DEFAULT NULL,
    `payment_fee` DECIMAL(10,2) NULL DEFAULT NULL,
    `payment_method` VARCHAR(100) NOT NULL,
    `payment_status` ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    `shipping_address` JSON NOT NULL,
    `billing_address` JSON NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_order_number` (`order_number`),
    INDEX `idx_status` (`status`),
    INDEX `idx_order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- ORDER ITEMS TABLE
-- ==============================================
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `order_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `quantity` INT(11) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `size` VARCHAR(50) NULL DEFAULT NULL,
    `color` VARCHAR(50) NULL DEFAULT NULL,
    `image` VARCHAR(500) NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL,
    INDEX `idx_order_id` (`order_id`),
    INDEX `idx_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- ADDRESSES TABLE
-- ==============================================
CREATE TABLE IF NOT EXISTS `addresses` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `type` ENUM('billing', 'shipping') NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `street` VARCHAR(255) NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `zip` VARCHAR(20) NOT NULL,
    `country` VARCHAR(10) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_type` (`type`),
    UNIQUE KEY `unique_user_address_type` (`user_id`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- CART TABLE
-- ==============================================
CREATE TABLE IF NOT EXISTS `cart` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `quantity` INT(11) NOT NULL DEFAULT 1,
    `size` VARCHAR(50) NULL DEFAULT NULL,
    `color` VARCHAR(50) NULL DEFAULT NULL,
    `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_product_id` (`product_id`),
    UNIQUE KEY `unique_user_product_variant` (`user_id`, `product_id`, `size`, `color`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- WISHLIST TABLE
-- ==============================================
CREATE TABLE IF NOT EXISTS `wishlist` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_product_id` (`product_id`),
    UNIQUE KEY `unique_user_product_wishlist` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- REMEMBER TOKENS TABLE
-- ==============================================
CREATE TABLE IF NOT EXISTS `remember_tokens` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `expires_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_token` (`token`),
    INDEX `idx_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================
-- SAMPLE DATA INSERTION
-- ==============================================

-- Insert sample products (from the JavaScript data in account.html)
INSERT INTO `products` (`title`, `category`, `price`, `old_price`, `image`, `colors`, `sizes`, `rating`, `reviews`, `badge`, `featured`, `new_arrival`, `sku`, `description`, `features`, `color_codes`) VALUES
('Premium Cotton Shirt', 'shirts', 59.99, 74.99, 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["black", "blue", "white"]', '["XS", "S", "M", "L", "XL"]', 4.5, 24, 'sale', 1, 0, 'FH-001', 'This premium cotton shirt is crafted from 100% organic cotton for maximum comfort and breathability. The tailored fit provides a modern silhouette while allowing freedom of movement. Perfect for both casual and business casual occasions.', '["100% Organic Cotton", "Button-down collar", "Single chest pocket", "Tailored fit", "Machine washable"]', '{"black": "#3a3a3a", "blue": "#5a8ac1", "white": "#e6e6e6"}'),
('Slim Fit Jeans', 'jeans', 79.99, 89.99, 'https://images.unsplash.com/photo-1542272604-787c3835535d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["black", "blue"]', '["28", "30", "32", "34", "36"]', 4.0, 18, 'new', 0, 1, 'FH-002', 'These slim fit jeans are designed for a modern, tailored look. Made from premium denim with just the right amount of stretch for comfort. The dark wash makes them versatile enough for both casual and dressier occasions.', '["98% Cotton, 2% Elastane", "Slim fit through hip and thigh", "Zip fly with button closure", "Five-pocket styling", "Machine wash cold"]', '{"black": "#3a3a3a", "blue": "#5a8ac1"}'),
('Classic Denim Jacket', 'jackets', 99.99, NULL, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["blue", "black"]', '["S", "M", "L", "XL"]', 5.0, 32, NULL, 1, 0, 'FH-003', 'This timeless denim jacket is a wardrobe essential. Made from durable 12-ounce denim, it features a classic fit that layers easily over your favorite tops. The jacket has a button-front closure, chest pockets, and adjustable waist tabs for a custom fit.', '["100% Cotton denim", "Classic fit", "Button-front closure", "Chest pockets with flap", "Adjustable waist tabs"]', '{"blue": "#5a8ac1", "black": "#3a3a3a"}'),
('Casual Summer Dress', 'dresses', 69.99, 79.99, 'https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["beige", "white", "black"]', '["XS", "S", "M"]', 4.0, 21, 'sale', 0, 0, 'FH-004', 'This breezy summer dress is perfect for warm weather. Made from lightweight linen blend fabric that drapes beautifully and keeps you cool. The wrap-style design with tie waist creates a flattering silhouette for all body types.', '["65% Linen, 35% Cotton", "Wrap-style with tie waist", "V-neckline", "Short sleeves", "Machine wash gentle"]', '{"beige": "#d4a762", "white": "#e6e6e6", "black": "#3a3a3a"}'),
('Leather Crossbody Bag', 'accessories', 89.99, NULL, 'https://images.unsplash.com/photo-1543076447-215ad9ba6923?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["black", "beige"]', '["One Size"]', 4.5, 27, NULL, 1, 0, 'FH-005', 'This stylish crossbody bag is crafted from genuine leather that develops a beautiful patina over time. The compact design fits all your essentials while keeping your hands free. Features multiple compartments for organization and an adjustable strap for comfort.', '["Genuine leather", "Adjustable crossbody strap", "Main zip compartment", "Interior slip pocket", "Exterior back zip pocket"]', '{"black": "#3a3a3a", "beige": "#d4a762"}'),
('Premium Wool Coat', 'jackets', 129.99, NULL, 'https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["black", "beige"]', '["S", "M", "L"]', 4.0, 15, 'new', 0, 1, 'FH-006', 'This premium wool coat is perfect for transitional weather. Made from a wool blend that provides warmth without bulk. The tailored silhouette and notched lapel create a polished look that works from office to evening.', '["70% Wool, 30% Polyester", "Notched lapel", "Single-breasted button front", "Flap pockets", "Lined interior"]', '{"black": "#3a3a3a", "beige": "#d4a762"}'),
('Linen Button-Up Shirt', 'shirts', 49.99, 59.99, 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["white", "blue"]', '["S", "M", "L", "XL"]', 4.5, 19, 'sale', 1, 0, 'FH-007', 'This lightweight linen shirt is perfect for warm weather. The breathable fabric and relaxed fit keep you cool and comfortable all day long. The button-up design makes it versatile enough for both casual and dressier occasions.', '["100% Linen", "Button-up front", "Chest pocket", "Relaxed fit", "Machine washable"]', '{"white": "#e6e6e6", "blue": "#5a8ac1"}'),
('Cashmere Scarf', 'accessories', 59.99, NULL, 'https://images.unsplash.com/photo-1595341595379-cf0ff4911a1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["red", "black", "beige"]', '["One Size"]', 5.0, 12, NULL, 0, 0, 'FH-008', 'This luxurious cashmere scarf is the perfect accessory for cooler weather. The ultra-soft cashmere provides warmth without bulk, and the generous size allows for versatile styling. A timeless piece that will last for years.', '["100% Cashmere", "Generous size: 70\\" x 12\\"", "Fringed ends", "Ultra-soft hand feel", "Dry clean recommended"]', '{"red": "#ff0000", "black": "#3a3a3a", "beige": "#d4a762"}'),
('Classic White Sneakers', 'shoes', 89.99, NULL, 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["white", "black"]', '["US 7", "US 8", "US 9", "US 10", "US 11"]', 4.8, 42, 'best-seller', 1, 0, 'FH-009', 'These classic white sneakers are a wardrobe staple. Made from premium leather with a comfortable cushioned insole, they\'re perfect for all-day wear. The timeless design pairs well with any outfit.', '["Premium leather upper", "Cushioned insole for comfort", "Rubber outsole for traction", "Lace-up closure", "Machine washable"]', '{"white": "#ffffff", "black": "#3a3a3a"}'),
('Wool Blend Sweater', 'sweaters', 79.99, 99.99, 'https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["navy", "gray", "burgundy"]', '["S", "M", "L", "XL"]', 4.3, 28, 'sale', 1, 0, 'FH-010', 'This wool blend sweater offers warmth and style for the cooler months. The relaxed fit and ribbed cuffs provide both comfort and a polished look. Perfect for layering or wearing on its own.', '["70% Wool, 30% Acrylic", "Ribbed cuffs and hem", "Relaxed fit", "Crew neckline", "Machine wash cold"]', '{"navy": "#001f3f", "gray": "#aaaaaa", "burgundy": "#800020"}'),
('Silk Blouse', 'shirts', 89.99, NULL, 'https://images.unsplash.com/photo-1581044777550-4cfa60707c03?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["ivory", "black", "sage"]', '["XS", "S", "M", "L"]', 4.6, 35, NULL, 0, 1, 'FH-011', 'This elegant silk blouse features a delicate drape and subtle sheen that elevates any outfit. The button-front design and pointed collar create a sophisticated look perfect for work or special occasions.', '["100% Silk", "Button-front closure", "Pointed collar", "Long sleeves with button cuffs", "Dry clean only"]', '{"ivory": "#fffff0", "black": "#3a3a3a", "sage": "#b2ac88"}'),
('Leather Belt', 'accessories', 49.99, NULL, 'https://images.unsplash.com/photo-1595341595379-cf0ff4911a1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80', '["brown", "black"]', '["S (30\\")", "M (32\\")", "L (34\\")", "XL (36\\")"]', 4.2, 19, NULL, 0, 0, 'FH-012', 'This genuine leather belt features a classic design with a polished buckle. The high-quality leather develops a beautiful patina over time, making it a durable and stylish accessory.', '["Genuine leather", "Polished metal buckle", "Adjustable fit", "Reversible (black/brown option)", "Width: 1.5 inches"]', '{"brown": "#964B00", "black": "#3a3a3a"}');

-- ==============================================
-- CREATE ADMIN USER (Optional)
-- ==============================================
-- Uncomment the following lines to create an admin user
-- INSERT INTO `users` (`name`, `email`, `password`) VALUES
-- ('Admin User', 'admin@fashionhub.com', 'adminpassword');

COMMIT;