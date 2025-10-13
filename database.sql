-- database.sql — TickyList (portable seed for DP)
-- MySQL 8.0+ / InnoDB / utf8mb4
-- Safe to re-run: drops existing tables, (re)creates schema, inserts minimal demo data.

SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_TIME_ZONE=@@TIME_ZONE, TIME_ZONE='+00:00';
SET NAMES utf8mb4;
START TRANSACTION;

-- 1) Create database (edit the DB name below if you want a different one)
CREATE DATABASE IF NOT EXISTS `tickylist`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `tickylist`;

-- 2) Drop existing tables (order matters because of foreign keys)
DROP TABLE IF EXISTS `items`;
DROP TABLE IF EXISTS `lists`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

-- 3) Tables
CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lists` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` INT NOT NULL,
  `category_id` INT DEFAULT NULL,
  `is_archived` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lists_user` (`user_id`),
  KEY `idx_lists_category` (`category_id`),
  CONSTRAINT `fk_lists_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_lists_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `list_id` INT NOT NULL,
  `name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_done` TINYINT(1) NOT NULL DEFAULT 0,
  `position` INT DEFAULT NULL,
  `due_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_items_list` (`list_id`),
  KEY `idx_items_done` (`is_done`),
  CONSTRAINT `fk_items_list` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4) Seed data (minimal, deterministic)
-- Password hash for "Password123!" (bcrypt, cost 10)
-- You can change it later in-app; this is just to allow demo login.
INSERT INTO `users` (`id`,`email`,`password`,`display_name`)
VALUES
  (1, 'test@example.com', '$2y$10$Do/SIenKrsFBNKQAJB6FIuO1TEYrWJybP3rLBSxyP3xJH1RQYCRvq', 'Demo User');

INSERT INTO `categories` (`id`,`name`,`icon`) VALUES
  (1, 'Courses', 'bi-basket'),
  (2, 'Travail', 'bi-briefcase'),
  (3, 'Maison',  'bi-house'),
  (4, 'Divers',  'bi-card-checklist');

INSERT INTO `lists` (`id`,`title`,`user_id`,`category_id`,`is_archived`)
VALUES
  (1, 'Ma première liste', 1, 4, 0),
  (2, 'Repas anniversaire', 1, 1, 0);

INSERT INTO `items` (`id`,`list_id`,`name`,`is_done`,`position`,`due_date`)
VALUES
  (1, 1, 'Acheter du lait', 0, 1, NULL),
  (2, 1, 'Payer la facture', 0, 2, NULL),
  (3, 2, 'Pain',            0, NULL, NULL),
  (4, 2, 'Gruyère',         0, NULL, NULL);

-- 5) Reset auto-increments to the next values
ALTER TABLE `users`      AUTO_INCREMENT=2;
ALTER TABLE `categories` AUTO_INCREMENT=5;
ALTER TABLE `lists`      AUTO_INCREMENT=3;
ALTER TABLE `items`      AUTO_INCREMENT=5;

COMMIT;
SET TIME_ZONE=@OLD_TIME_ZONE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET SQL_MODE=@OLD_SQL_MODE;

-- Import guide:
--   mysql -u root -p < database.sql
-- Or in phpMyAdmin: Import -> choose this file.
