-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema eventsapp
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema eventsapp2
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `eventsapp2` ;

-- -----------------------------------------------------
-- Schema eventsapp2
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `eventsapp2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `eventsapp2` ;

-- -----------------------------------------------------
-- Table `eventsapp2`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`users` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`users` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(20) NULL DEFAULT NULL,
  `profile_picture` VARCHAR(255) NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE UNIQUE INDEX `email` ON `eventsapp2`.`users` (`email` ASC);


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
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_event`),
  CONSTRAINT `events_ibfk_1`
    FOREIGN KEY (`created_by`)
    REFERENCES `eventsapp2`.`users` (`id_user`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE INDEX `created_by` ON `eventsapp2`.`events` (`created_by` ASC);


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
  `certificate_status` ENUM('not_eligible', 'pending', 'issued') NOT NULL DEFAULT 'not_eligible',
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
  `verified_by` INT NULL DEFAULT NULL,
  `verification_date` TIMESTAMP NULL DEFAULT NULL,
  `status` ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_payment`, `payment_date`),
  CONSTRAINT `payments_ibfk_1`
    FOREIGN KEY (`registration_id`)
    REFERENCES `eventsapp2`.`registrations` (`id_registration`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `payments_ibfk_2`
    FOREIGN KEY (`verified_by`)
    REFERENCES `eventsapp2`.`users` (`id_user`)
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE INDEX `registration_id` ON `eventsapp2`.`payments` (`registration_id` ASC);

CREATE INDEX `verified_by` ON `eventsapp2`.`payments` (`verified_by` ASC);


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
-- Table `eventsapp2`.`user_roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `eventsapp2`.`user_roles` ;

CREATE TABLE IF NOT EXISTS `eventsapp2`.`user_roles` (
  `user_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `role_id`),
  CONSTRAINT `user_roles_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `eventsapp2`.`users` (`id_user`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `user_roles_ibfk_2`
    FOREIGN KEY (`role_id`)
    REFERENCES `eventsapp2`.`roles` (`id_role`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

CREATE INDEX `role_id` ON `eventsapp2`.`user_roles` (`role_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
