-- ============================================================
-- UPGRADE SCRIPT FASE 1 - Compatible MySQL 8.0
-- Tablas nuevas y alteraciones de estructura
-- ============================================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1. Actualizar enum status en users (agregar 'suspended')
-- ------------------------------------------------------------
ALTER TABLE `users` 
  MODIFY `status` ENUM('pending','approved','rejected','suspended') DEFAULT 'pending';

-- ------------------------------------------------------------
-- 2. Actualizar enum status en courses (agregar 'draft')
-- ------------------------------------------------------------
ALTER TABLE `courses`
  MODIFY `status` ENUM('active','inactive','draft') DEFAULT 'draft';

-- ------------------------------------------------------------
-- 3. Agregar columnas a courses (ignorar error si ya existen)
-- ------------------------------------------------------------
ALTER TABLE `courses` ADD COLUMN `short_description` TEXT NULL AFTER `description`;
ALTER TABLE `courses` ADD COLUMN `banner` VARCHAR(255) NULL AFTER `thumbnail`;
ALTER TABLE `courses` ADD COLUMN `requirements` TEXT NULL AFTER `duration_hours`;
ALTER TABLE `courses` ADD COLUMN `objectives` TEXT NULL AFTER `requirements`;
ALTER TABLE `courses` ADD COLUMN `tags` VARCHAR(255) NULL AFTER `objectives`;
ALTER TABLE `courses` ADD COLUMN `total_lessons` INT DEFAULT 0 AFTER `tags`;
ALTER TABLE `courses` ADD COLUMN `rating_avg` DECIMAL(3,2) DEFAULT 0.00 AFTER `total_lessons`;
ALTER TABLE `courses` ADD COLUMN `rating_count` INT DEFAULT 0 AFTER `rating_avg`;

-- ------------------------------------------------------------
-- 4. Tabla modules
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `modules` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `course_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_course_id` (`course_id`),
  CONSTRAINT `fk_module_course` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. Tabla lessons
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lessons` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `module_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NULL,
  `video_url` VARCHAR(500) NULL,
  `video_type` ENUM('youtube','local','none') DEFAULT 'none',
  `pdf_url` VARCHAR(255) NULL,
  `duration_minutes` INT DEFAULT 0,
  `sort_order` INT DEFAULT 0,
  `is_free` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_module_id` (`module_id`),
  CONSTRAINT `fk_lesson_module` FOREIGN KEY (`module_id`) REFERENCES `modules`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. Tabla lesson_progress
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lesson_progress` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `lesson_id` INT NOT NULL,
  `completed` TINYINT(1) DEFAULT 0,
  `completed_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_lesson` (`user_id`, `lesson_id`),
  CONSTRAINT `fk_lp_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lp_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. Agregar columnas a enrollments
-- ------------------------------------------------------------
ALTER TABLE `enrollments` ADD COLUMN `progress_percentage` DECIMAL(5,2) DEFAULT 0.00;
ALTER TABLE `enrollments` ADD COLUMN `last_lesson_id` INT NULL;
ALTER TABLE `enrollments` ADD COLUMN `completed_at` TIMESTAMP NULL;

-- ------------------------------------------------------------
-- 8. Tabla certificates
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `certificates` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `code` VARCHAR(64) NOT NULL,
  `issued_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cert_code` (`code`),
  UNIQUE KEY `user_course_cert` (`user_id`, `course_id`),
  CONSTRAINT `fk_cert_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cert_course` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. Tabla reviews
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `rating` TINYINT NOT NULL,
  `comment` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_course_review` (`user_id`, `course_id`),
  CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_course` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 10. Tabla notifications
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `message` TEXT NOT NULL,
  `link` VARCHAR(255) NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_read` (`user_id`, `is_read`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'FASE 1 COMPLETADA: Estructura de tablas lista.' AS resultado;
