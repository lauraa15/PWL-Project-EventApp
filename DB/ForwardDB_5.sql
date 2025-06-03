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
-- Table `eventsapp`.`event_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`event_types` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`event_types` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `eventsapp`.`events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`events` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_type_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `location` VARCHAR(255) NULL DEFAULT NULL,
  `poster_image` VARCHAR(255) NULL DEFAULT NULL,
  `registration_fee` DECIMAL(10,2) NULL DEFAULT '0.00',
  `registration_type` ENUM('event_only', 'session_only', 'both') NOT NULL DEFAULT 'event_only',
  `max_participants` INT NULL DEFAULT '0',
  `current_participants` INT NULL DEFAULT '0',
  `registration_open_date` DATETIME NOT NULL,
  `registration_close_date` DATETIME NOT NULL,
  `certificate_type` ENUM('per_event', 'per_session') NOT NULL DEFAULT 'per_event',
  `is_active` TINYINT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `event_to_types_id_idx` (`event_type_id` ASC),
  CONSTRAINT `event_to_types_id`
    FOREIGN KEY (`event_type_id`)
    REFERENCES `eventsapp`.`event_types` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


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
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` TINYINT NULL DEFAULT 1,
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
  `updated_at` TIMESTAMP NULL,
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
-- Table `eventsapp`.`payments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`payments` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`payments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  `notes` TEXT NULL,
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
-- Table `eventsapp`.`event_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`event_sessions` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`event_sessions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `speaker` VARCHAR(200) NOT NULL,
  `session_start` DATETIME NOT NULL,
  `session_end` DATETIME NOT NULL,
  `location` VARCHAR(200) NOT NULL,
  `registration_fee` DECIMAL(10,2) NULL DEFAULT '0.00',
  `max_participants` INT NULL DEFAULT NULL,
  `current_participants` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `event-sessions_to_events_id_idx` (`event_id` ASC),
  CONSTRAINT `event-sessions_to_events_id`
    FOREIGN KEY (`event_id`)
    REFERENCES `eventsapp`.`events` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `eventsapp`.`attendances`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`attendances` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`attendances` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `session_id` INT NULL,
  `scan_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `certificate_file_path` VARCHAR(255) NULL,
  `certificate_code` VARCHAR(50) NULL,
  `certificate_issued_at` TIMESTAMP NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`),
  INDEX `attendances_to_event_id_idx` (`session_id` ASC),
  INDEX `attendances_to_registrations_id_idx` (`registration_id` ASC),
  CONSTRAINT `attendances_to_events_session`
    FOREIGN KEY (`session_id`)
    REFERENCES `eventsapp`.`event_sessions` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `attendances_to_registrations_id`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp`.`registrations` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


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
  INDEX `committees_to_event_id_idx` (`event_id` ASC),
  INDEX `committees_to_user_id_idx` (`user_id` ASC),
  CONSTRAINT `committees_to_event_id`
    FOREIGN KEY (`event_id`)
    REFERENCES `eventsapp`.`events` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `committees_to_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `eventsapp`.`users` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


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
  INDEX `registration_session_idx` (`session_id` ASC),
  INDEX `session-regist_to_registrations_idx` (`registration_id` ASC),
  CONSTRAINT `session-regist_to_event-sessions`
    FOREIGN KEY (`session_id`)
    REFERENCES `eventsapp`.`event_sessions` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `session-regist_to_registrations`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp`.`registrations` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `eventsapp`.`event_genres`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`event_genres` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`event_genres` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `genre` VARCHAR(50) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `eventsapp`.`event_genre_pivot`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`event_genre_pivot` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`event_genre_pivot` (
  `event_id` INT NOT NULL,
  `genre_id` INT NOT NULL,
  PRIMARY KEY (`event_id`, `genre_id`),
  INDEX `genre_to_events_in_pivot_idx` (`genre_id` ASC),
  CONSTRAINT `events_to_genres_in_pivot`
    FOREIGN KEY (`event_id`)
    REFERENCES `eventsapp`.`events` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `genre_to_events_in_pivot`
    FOREIGN KEY (`genre_id`)
    REFERENCES `eventsapp`.`event_genres` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-- begin attached script 'script'
-- -----------------------------------------------------
-- Insert Default Roles
-- -----------------------------------------------------
INSERT INTO `eventsapp`.`roles` (`name`, `description`) VALUES
('admin', 'System Administrator'),
('finance', 'Tim Keuangan'),
('organizer', 'Panitia Pelaksana Kegiatan'),
('member', 'Member/Peserta');
-- end attached script 'script'
-- begin attached script 'script1'
-- -----------------------------------------------------
-- Insert Default Event Types
-- -----------------------------------------------------
INSERT INTO `eventsapp`.`event_types` (`type`) VALUES
('Conference'),
('Workshop'),
('Exhibition'),
('Seminar'),
('Webinar'),
('Festival'),
('Talkshow'),
('Competition'),
('Networking'),
('Meet-up'),
('Launching');

-- end attached script 'script1'
-- begin attached script 'script2'
-- -----------------------------------------------------
-- Insert Default Event Genres
-- -----------------------------------------------------
INSERT INTO `eventsapp`.`event_genres` (`genre`) VALUES
('Music'),
('Fashion'),
('Technology'),
('Culinary'),
('Art'),
('Business'),
('Gaming'),
('Health'),
('Education'),
('Sports'),
('Photography');

-- end attached script 'script2'
