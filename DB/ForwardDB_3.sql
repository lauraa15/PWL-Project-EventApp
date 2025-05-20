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
CREATE SCHEMA IF NOT EXISTS `eventsapp` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema eventsapp2
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `eventsapp2` ;

-- -----------------------------------------------------
-- Schema eventsapp2
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `eventsapp2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `eventsapp` ;

-- -----------------------------------------------------
-- Table `eventsapp2`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`roles` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`roles` (
  `id_role` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_role`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE UNIQUE INDEX `name` ON `eventsapp2`.`roles` (`name` ASC);


-- -----------------------------------------------------
-- Table `eventsapp2`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`users` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`users` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `role_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(20) NULL DEFAULT NULL,
  `profile_picture` VARCHAR(255) NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `userscol` VARCHAR(45) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  CONSTRAINT `id_role_di_user`
    FOREIGN KEY (`role_id`)
    REFERENCES `eventsapp2`.`roles` (`id_role`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE UNIQUE INDEX `email` ON `eventsapp2`.`users` (`email` ASC);

CREATE INDEX `id_role_di_user_idx` ON `eventsapp2`.`users` (`role_id` ASC);


-- -----------------------------------------------------
-- Table `eventsapp2`.`events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`events` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`events` (
  `id_event` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `event_date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `speaker` VARCHAR(255) NOT NULL,
  `poster_image` VARCHAR(255) NULL DEFAULT NULL,
  `registration_fee` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `max_participants` INT NOT NULL DEFAULT '0',
  `registration_open_date` DATETIME NOT NULL,
  `registration_close_date` DATETIME NOT NULL,
  `status` ENUM('draft', 'published', 'ongoing', 'completed', 'cancelled') NOT NULL DEFAULT 'draft',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_event`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `eventsapp`.`users_events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp`.`users_events` ;

CREATE TABLE IF NOT EXISTS `eventsapp`.`users_events` (
  `id_user` INT NOT NULL,
  `id_event` INT NOT NULL,
  PRIMARY KEY (`id_user`, `id_event`),
  CONSTRAINT `id_user_di_users_events`
    FOREIGN KEY (`id_user`)
    REFERENCES `eventsapp2`.`users` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `id_events_di_users_events`
    FOREIGN KEY (`id_event`)
    REFERENCES `eventsapp2`.`events` (`id_event`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `id_events_di_users_events_idx` ON `eventsapp`.`users_events` (`id_event` ASC);

USE `eventsapp2` ;

-- -----------------------------------------------------
-- Table `eventsapp2`.`registrations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`registrations` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`registrations` (
  `id_registration` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `event_id` INT NOT NULL,
  `registration_code` VARCHAR(50) NOT NULL,
  `registration_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `qr_code` VARCHAR(255) NULL DEFAULT NULL,
  `payment_status` ENUM('unpaid', 'pending', 'verified', 'rejected') NOT NULL DEFAULT 'unpaid',
  `attendance_status` ENUM('absent', 'present') NOT NULL DEFAULT 'absent',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_registration`),
  CONSTRAINT `registrations_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `eventsapp2`.`users` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `registrations_ibfk_2`
    FOREIGN KEY (`event_id`)
    REFERENCES `eventsapp2`.`events` (`id_event`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE UNIQUE INDEX `registration_code` ON `eventsapp2`.`registrations` (`registration_code` ASC);

CREATE UNIQUE INDEX `user_id` ON `eventsapp2`.`registrations` (`user_id` ASC, `event_id` ASC);

CREATE INDEX `event_id` ON `eventsapp2`.`registrations` (`event_id` ASC);


-- -----------------------------------------------------
-- Table `eventsapp2`.`attendances`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`attendances` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`attendances` (
  `id_attendance` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `check_in_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_attendance`),
  CONSTRAINT `attendances_ibfk_1`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp2`.`registrations` (`id_registration`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE UNIQUE INDEX `registration_id` ON `eventsapp2`.`attendances` (`registration_id` ASC);


-- -----------------------------------------------------
-- Table `eventsapp2`.`cache`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`cache` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp2`.`cache_locks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`cache_locks` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp2`.`certificates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`certificates` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`certificates` (
  `id_certificate` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `certificate_file` VARCHAR(255) NOT NULL,
  `certificate_code` VARCHAR(50) NOT NULL,
  `issued_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_certificate`),
  CONSTRAINT `certificates_ibfk_1`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp2`.`registrations` (`id_registration`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE UNIQUE INDEX `certificate_code` ON `eventsapp2`.`certificates` (`certificate_code` ASC);

CREATE UNIQUE INDEX `registration_id` ON `eventsapp2`.`certificates` (`registration_id` ASC);


-- -----------------------------------------------------
-- Table `eventsapp2`.`failed_jobs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`failed_jobs` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

CREATE UNIQUE INDEX `failed_jobs_uuid_unique` ON `eventsapp2`.`failed_jobs` (`uuid` ASC);


-- -----------------------------------------------------
-- Table `eventsapp2`.`job_batches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`job_batches` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL DEFAULT NULL,
  `cancelled_at` INT NULL DEFAULT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp2`.`jobs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`jobs` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

CREATE INDEX `jobs_queue_index` ON `eventsapp2`.`jobs` (`queue` ASC);


-- -----------------------------------------------------
-- Table `eventsapp2`.`migrations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`migrations` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`migrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp2`.`password_reset_tokens`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`password_reset_tokens` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `eventsapp2`.`payments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`payments` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`payments` (
  `id_payment` INT NOT NULL AUTO_INCREMENT,
  `registration_id` INT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(100) NULL DEFAULT NULL,
  `payment_proof` VARCHAR(255) NULL DEFAULT NULL,
  `payment_date` TIMESTAMP NOT NULL,
  `verification_date` TIMESTAMP NULL DEFAULT NULL,
  `status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_payment`, `payment_date`),
  CONSTRAINT `payments_ibfk_1`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp2`.`registrations` (`id_registration`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE INDEX `registration_id` ON `eventsapp2`.`payments` (`registration_id` ASC);


-- -----------------------------------------------------
-- Table `eventsapp2`.`sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`sessions` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `user_agent` TEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

CREATE INDEX `sessions_user_id_index` ON `eventsapp2`.`sessions` (`user_id` ASC);

CREATE INDEX `sessions_last_activity_index` ON `eventsapp2`.`sessions` (`last_activity` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
