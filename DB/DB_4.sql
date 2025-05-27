-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema eventsapp
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `eventsapp` ;

-- -----------------------------------------------------
-- Schema eventsapp
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `eventsapp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;
USE `eventsapp` ;

-- -----------------------------------------------------
-- Table `eventsapp`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`roles` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL COMMENT 'admin,finance,organizer,member',
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`users` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(20) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  INDEX `fk_users_roles_idx` (`role_id` ASC) VISIBLE,
  CONSTRAINT `fk_users_roles`
    FOREIGN KEY (`role_id`)
    REFERENCES `eventsapp`.`roles` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp`.`api_tokens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`api_tokens` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`api_tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `last_used_at` DATETIME NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC) VISIBLE,
  INDEX `fk_api_tokens_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_api_tokens_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `eventsapp`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp`.`events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`events` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_by` INT NOT NULL COMMENT 'Panitia yang membuat event',
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `event_date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `poster_image` VARCHAR(255) NULL DEFAULT NULL,
  `event_speaker` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Nama pembicara acara',
  `registration_fee` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `max_participants` INT NOT NULL DEFAULT '0',
  `current_participants` INT NOT NULL DEFAULT '0',
  `registration_open_date` DATETIME NOT NULL,
  `registration_close_date` DATETIME NOT NULL,
  `status` ENUM('draft', 'published', 'completed', 'cancelled') NOT NULL DEFAULT 'draft',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_events_creator_idx` (`created_by` ASC) VISIBLE,
  CONSTRAINT `fk_events_creator`
    FOREIGN KEY (`created_by`)
    REFERENCES `eventsapp`.`users` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp`.`registrations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`registrations` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`registrations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL COMMENT 'Member yang mendaftar',
  `event_id` INT NOT NULL,
  `registration_code` VARCHAR(50) NOT NULL COMMENT 'Kode unik registrasi',
  `qr_code` VARCHAR(255) NULL DEFAULT NULL COMMENT 'QR code untuk attendance',
  `registration_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attendance_status` ENUM('absent', 'present') NOT NULL DEFAULT 'absent',
  `attendance_time` TIMESTAMP NULL DEFAULT NULL COMMENT 'Waktu scan QR oleh panitia',
  `attendance_verified_by` INT NULL DEFAULT NULL COMMENT 'Panitia yang melakukan scan',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `registration_code_UNIQUE` (`registration_code` ASC) VISIBLE,
  UNIQUE INDEX `user_event_UNIQUE` (`user_id` ASC, `event_id` ASC) VISIBLE,
  INDEX `fk_registrations_events_idx` (`event_id` ASC) VISIBLE,
  INDEX `fk_registrations_attendance_verifier_idx` (`attendance_verified_by` ASC) VISIBLE,
  CONSTRAINT `fk_registrations_attendance_verifier`
    FOREIGN KEY (`attendance_verified_by`)
    REFERENCES `eventsapp`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_registrations_events`
    FOREIGN KEY (`event_id`)
    REFERENCES `eventsapp`.`events` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_registrations_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `eventsapp`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp`.`certificates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`certificates` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`certificates` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `certificate_code` VARCHAR(50) NOT NULL COMMENT 'Kode unik sertifikat',
  `issued_by` INT NOT NULL COMMENT 'Panitia yang mengupload',
  `issued_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `certificate_code_UNIQUE` (`certificate_code` ASC) VISIBLE,
  UNIQUE INDEX `registration_id_UNIQUE` (`registration_id` ASC) VISIBLE,
  INDEX `fk_certificates_issuer_idx` (`issued_by` ASC) VISIBLE,
  CONSTRAINT `fk_certificates_issuer`
    FOREIGN KEY (`issued_by`)
    REFERENCES `eventsapp`.`users` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_certificates_registrations`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp`.`registrations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp`.`payments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`payments` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`payments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `payment_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified_by` INT NULL DEFAULT NULL COMMENT 'Tim keuangan yang memverifikasi',
  `verification_date` TIMESTAMP NULL DEFAULT NULL,
  `status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  `notes` TEXT NULL DEFAULT NULL COMMENT 'Catatan jika ditolak',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_payments_registrations_idx` (`registration_id` ASC) VISIBLE,
  INDEX `fk_payments_verifier_idx` (`verified_by` ASC) VISIBLE,
  CONSTRAINT `fk_payments_registrations`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp`.`registrations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_payments_verifier`
    FOREIGN KEY (`verified_by`)
    REFERENCES `eventsapp`.`users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
