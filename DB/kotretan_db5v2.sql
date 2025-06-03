-- MySQL Workbench Forward Engineering - IMPROVED VERSION

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema eventsapp
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `eventsapp` ;

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
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
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
  `is_active` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_users_roles_idx` (`role_id` ASC),
  CONSTRAINT `fk_users_roles`
    FOREIGN KEY (`role_id`)
    REFERENCES `eventsapp`.`roles` (`id`)
    ON DELETE RESTRICT
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
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `location` VARCHAR(255) NULL,
  `poster_image` VARCHAR(255) NULL DEFAULT NULL,
  `registration_fee` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'Fee untuk paket lengkap event (semua sesi)',
  `registration_type` ENUM('event_only', 'session_only', 'both') NOT NULL DEFAULT 'event_only' COMMENT 'Tipe registrasi: event saja, session saja, atau keduanya',
  `max_participants` INT NULL DEFAULT '0',
  `current_participants` INT NULL DEFAULT '0',
  `registration_open_date` DATETIME NOT NULL,
  `registration_close_date` DATETIME NOT NULL,
  `certificate_type` ENUM('per_event', 'per_session') NOT NULL DEFAULT 'per_event' COMMENT 'Sertifikat per event atau per sesi',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `eventsapp`.`event_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`event_sessions` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`event_sessions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NULL,
  `speaker` VARCHAR(200) NOT NULL,
  `session_start` DATETIME NOT NULL,
  `session_end` DATETIME NOT NULL,
  `location` VARCHAR(200) NOT NULL,
  `registration_fee` DECIMAL(10,2) NULL DEFAULT '0.00' COMMENT 'Fee khusus untuk sesi ini (untuk registration_type = session_only atau both)',
  `max_participants` INT NULL DEFAULT NULL COMMENT 'Jika NULL, ikuti max dari event',
  `current_participants` INT NULL DEFAULT '0',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_event_sessions_events_idx` (`event_id` ASC),
  CONSTRAINT `fk_event_sessions_events`
    FOREIGN KEY (`event_id`)
    REFERENCES `eventsapp`.`events` (`id`)
    ON DELETE CASCADE
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
  `status` ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `registration_code_UNIQUE` (`registration_code` ASC),
  UNIQUE INDEX `user_event_UNIQUE` (`user_id` ASC, `event_id` ASC),
  INDEX `fk_registrations_events_idx` (`event_id` ASC),
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
-- Table `eventsapp`.`session_registrations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`session_registrations` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`session_registrations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `session_id` INT NOT NULL,
  `registered_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `registration_session_UNIQUE` (`registration_id` ASC, `session_id` ASC),
  INDEX `fk_session_registrations_sessions_idx` (`session_id` ASC),
  CONSTRAINT `fk_session_registrations_registrations`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp`.`registrations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_session_registrations_sessions`
    FOREIGN KEY (`session_id`)
    REFERENCES `eventsapp`.`event_sessions` (`id`)
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
  `status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  `notes` TEXT NULL COMMENT 'Catatan verifikasi',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_payments_registrations_idx` (`registration_id` ASC),
  CONSTRAINT `fk_payments_registrations`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp`.`registrations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `eventsapp`.`attendances`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`attendances` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`attendances` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `session_id` INT NULL COMMENT 'Jika NULL berarti attendance untuk event secara keseluruhan',
  `scan_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `certificate_file_path` VARCHAR(255) NULL COMMENT 'Path file sertifikat',
  `certificate_code` VARCHAR(50) NULL COMMENT 'Kode unik sertifikat',
  `certificate_issued_at` TIMESTAMP NULL COMMENT 'Kapan sertifikat diterbitkan',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `registration_session_attendance_UNIQUE` (`registration_id` ASC, `session_id` ASC),
  UNIQUE INDEX `certificate_code_UNIQUE` (`certificate_code` ASC),
  INDEX `fk_attendances_sessions_idx` (`session_id` ASC),
  CONSTRAINT `fk_attendances_registrations`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp`.`registrations` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_attendances_sessions`
    FOREIGN KEY (`session_id`)
    REFERENCES `eventsapp`.`event_sessions` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;



-- -----------------------------------------------------
-- Table `eventsapp`.`event_committees`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`event_committees` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`event_committees` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `event_user_committee_UNIQUE` (`event_id` ASC, `user_id` ASC),
  INDEX `fk_committees_events_idx` (`event_id` ASC),
  INDEX `fk_committees_users_idx` (`user_id` ASC),
  CONSTRAINT `fk_committees_events`
    FOREIGN KEY (`event_id`)
    REFERENCES `eventsapp`.`events` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_committees_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `eventsapp`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Insert Default Roles
-- -----------------------------------------------------
INSERT INTO `eventsapp`.`roles` (`name`, `description`) VALUES
('admin', 'System Administrator'),
('finance', 'Tim Keuangan'),
('organizer', 'Panitia Pelaksana Kegiatan'),
('member', 'Member/Peserta');

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;