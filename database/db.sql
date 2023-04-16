CREATE DATABASE IF NOT EXISTS `locations`;
USE `locations`;

CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INT AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(16) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('user', 'admin') DEFAULT 'user',
    PRIMARY KEY (`user_id`),
    UNIQUE KEY (`username`)
);

CREATE TABLE IF NOT EXISTS `locations` (
    `location_id` INT AUTO_INCREMENT NOT NULL,
    `user_id` INT NOT NULL,
    `name` VARCHAR(64) NOT NULL,
    `lat` FLOAT NOT NULL,
    `lng` FLOAT NOT NULL,
    `public` TINYINT(1) NOT NULL,
    PRIMARY KEY (`location_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);
