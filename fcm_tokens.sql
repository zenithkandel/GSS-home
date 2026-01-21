-- FCM Tokens Table for LifeLine Push Notifications
-- Run this SQL to create the fcm_tokens table

CREATE TABLE IF NOT EXISTS `fcm_tokens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `token` VARCHAR(512) NOT NULL UNIQUE,
    `user_agent` TEXT,
    `user_id` INT DEFAULT NULL,
    `active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_active` (`active`),
    INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key if users table exists (optional)
-- ALTER TABLE `fcm_tokens` ADD CONSTRAINT `fk_fcm_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL;
