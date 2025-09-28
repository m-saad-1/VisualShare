ALTER TABLE `users` ADD COLUMN `role` ENUM('user', 'admin') DEFAULT 'user' AFTER `password`;

INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin User', 'admin@fashionhub.com', '$2y$10$.qx5jAy2udUha4jaPrVx2.XksE7EsHhLtErH42c2n6LDixvSsmsSO', 'admin');

UPDATE `users` SET `role` = 'user' WHERE `role` IS NULL;