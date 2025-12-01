-- Migration: add role/is_active columns and reseed default admin
USE `inventory`;

ALTER TABLE `users`
    ADD COLUMN IF NOT EXISTS `role` ENUM('superadmin','admin','security','maintenance','resident','external') DEFAULT 'resident' AFTER `phone`,
    ADD COLUMN IF NOT EXISTS `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `role`;

UPDATE `users`
SET `role` = COALESCE(`role`, 'resident');

UPDATE `users`
SET `is_active` = IFNULL(`is_active`, 1);

INSERT INTO `users` (`username`, `email`, `password`, `first_name`, `last_name`, `phone`, `role`, `is_active`)
VALUES ('admin', 'admin@inventory.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System', 'Administrator', NULL, 'superadmin', 1)
ON DUPLICATE KEY UPDATE
    `password` = VALUES(`password`),
    `role` = 'superadmin',
    `is_active` = 1;
