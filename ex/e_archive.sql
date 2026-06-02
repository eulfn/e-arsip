-- ============================================
-- Database: e_archive
-- Reconstructed from CI3 project source code
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `e_archive` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `e_archive`;

-- ============================================
-- Table: user
-- ============================================
-- Source: User_model.php (insert into 'user'), Auth.php (username, email, password, role, profile_pic, status)
-- Roles from user_create.php: 'magang', 'staff', 'administrator'
-- Status from Users controller toggle(): 'active', 'nonactive'
-- profile_pic from Auth.php register + Profile.php update

CREATE TABLE `user` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('magang', 'staff', 'administrator') NOT NULL DEFAULT 'magang',
  `profile_pic` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active', 'nonactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: user_permission
-- ============================================
-- Source: Permission_model.php, Permissions controller (can_upload, can_edit, can_delete, can_view_audit)
-- Created automatically when a new user registers (User_model::push)
-- Default values are 0 (no permissions) — managed by administrator

CREATE TABLE `user_permission` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `can_upload` TINYINT(1) NOT NULL DEFAULT 0,
  `can_edit` TINYINT(1) NOT NULL DEFAULT 0,
  `can_delete` TINYINT(1) NOT NULL DEFAULT 0,
  `can_view_audit` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_permission_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: categories
-- ============================================
-- Source: Category_model.php (insert into 'categories', columns: id, name)
-- Used in document upload/edit forms via $cat->id, $cat->name

CREATE TABLE `categories` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: documents
-- ============================================
-- Source: Document_model.php (insert into 'documents')
-- Columns from Documents controller save(): title, description, filename, path, uploaded_by, status
-- Status from Documents controller toggle(): 'active', 'nonactive'
-- get_all() joins: documents.uploaded_by = user.id

CREATE TABLE `documents` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `uploaded_by` INT(11) UNSIGNED NOT NULL,
  `status` ENUM('active', 'nonactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `fk_document_user` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: documents_categories (pivot / junction table)
-- ============================================
-- Source: Document_model.php push/update (insert into 'documents_categories')
-- Columns: document_id, category_id

CREATE TABLE `documents_categories` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_id` INT(11) UNSIGNED NOT NULL,
  `category_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `document_id` (`document_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_dc_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dc_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Table: audit_logs
-- ============================================
-- Source: Audit_log_model.php (insert into 'audit_logs')
-- Columns: user_id, action, module, documents_id, created_at
-- get_all() joins: audit_logs.user_id = user.id, ORDER BY created_at DESC

CREATE TABLE `audit_logs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `module` VARCHAR(50) NOT NULL,
  `documents_id` INT(11) UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `documents_id` (`documents_id`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_audit_document` FOREIGN KEY (`documents_id`) REFERENCES `documents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================
-- Seed: Default administrator account
-- ============================================
-- Password: admin (bcrypt hashed)
-- You can change this after first login

INSERT INTO `user` (`username`, `email`, `password`, `role`, `status`) VALUES
('admin', 'admin@e-archive.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrator', 'active');

-- Give admin full permissions
INSERT INTO `user_permission` (`user_id`, `can_upload`, `can_edit`, `can_delete`, `can_view_audit`) VALUES
(1, 1, 1, 1, 1);

-- Seed: Some example categories
INSERT INTO `categories` (`name`) VALUES
('Surat Masuk'),
('Surat Keluar'),
('Laporan'),
('Memo');
