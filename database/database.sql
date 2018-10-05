-- MySQL Script generated by MySQL Workbench
-- Pá 5. října 2018, 11:10:06 CEST
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema ticketsonline
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `ticketsonline` ;

-- -----------------------------------------------------
-- Schema ticketsonline
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ticketsonline` DEFAULT CHARACTER SET utf8 ;
USE `ticketsonline` ;

-- -----------------------------------------------------
-- Table `ticketsonline`.`Interpret`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Interpret` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Interpret` (
  `idInterpret` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `label` VARCHAR(255) NOT NULL,
  `founded` DATE NULL,
  PRIMARY KEY (`idInterpret`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Album`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Album` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Album` (
  `idAlbum` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `label` VARCHAR(255) NULL,
  `release` DATE NULL,
  `idInterpret` INT NOT NULL,
  PRIMARY KEY (`idAlbum`),
  INDEX `fk_Album_Interpret_idx` (`idInterpret` ASC),
  CONSTRAINT `fk_Album_Interpret`
    FOREIGN KEY (`idInterpret`)
    REFERENCES `ticketsonline`.`Interpret` (`idInterpret`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Genre`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Genre` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Genre` (
  `idGenre` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idGenre`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Album_has_Genre`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Album_has_Genre` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Album_has_Genre` (
  `idAlbum` INT NOT NULL,
  `idGenre` INT NOT NULL,
  PRIMARY KEY (`idAlbum`, `idGenre`),
  INDEX `fk_Album_has_Genre_Genre1_idx` (`idGenre` ASC),
  INDEX `fk_Album_has_Genre_Album1_idx` (`idAlbum` ASC),
  CONSTRAINT `fk_Album_has_Genre_Album1`
    FOREIGN KEY (`idAlbum`)
    REFERENCES `ticketsonline`.`Album` (`idAlbum`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Album_has_Genre_Genre1`
    FOREIGN KEY (`idGenre`)
    REFERENCES `ticketsonline`.`Genre` (`idGenre`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Member` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Member` (
  `idMember` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `surname` VARCHAR(255) NOT NULL,
  `birth` VARCHAR(45) NULL,
  PRIMARY KEY (`idMember`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Interpret_has_Member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Interpret_has_Member` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Interpret_has_Member` (
  `idInterpret` INT NOT NULL,
  `idMember` INT NOT NULL,
  PRIMARY KEY (`idInterpret`, `idMember`),
  INDEX `fk_Interpret_has_Member_Member1_idx` (`idMember` ASC),
  INDEX `fk_Interpret_has_Member_Interpret1_idx` (`idInterpret` ASC),
  CONSTRAINT `fk_Interpret_has_Member_Interpret1`
    FOREIGN KEY (`idInterpret`)
    REFERENCES `ticketsonline`.`Interpret` (`idInterpret`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Interpret_has_Member_Member1`
    FOREIGN KEY (`idMember`)
    REFERENCES `ticketsonline`.`Member` (`idMember`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`City`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`City` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`City` (
  `idCity` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idCity`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Place`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Place` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Place` (
  `idPlace` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NULL,
  `gps` VARCHAR(255) NULL,
  `info` TEXT(1024) NULL,
  `idCity` INT NULL,
  PRIMARY KEY (`idPlace`),
  INDEX `fk_Place_City1_idx` (`idCity` ASC),
  CONSTRAINT `fk_Place_City1`
    FOREIGN KEY (`idCity`)
    REFERENCES `ticketsonline`.`City` (`idCity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Concert`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Concert` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Concert` (
  `idConcert` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `date` DATE NOT NULL,
  `capacity` INT NULL,
  `idPlace` INT NOT NULL,
  PRIMARY KEY (`idConcert`),
  INDEX `fk_Concert_Place1_idx` (`idPlace` ASC),
  CONSTRAINT `fk_Concert_Place1`
    FOREIGN KEY (`idPlace`)
    REFERENCES `ticketsonline`.`Place` (`idPlace`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Concert_has_Interpret`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Concert_has_Interpret` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Concert_has_Interpret` (
  `idConcert` INT NOT NULL,
  `idInterpret` INT NOT NULL,
  `headliner` TINYINT NOT NULL,
  PRIMARY KEY (`idConcert`, `idInterpret`),
  INDEX `fk_Concert_has_Interpret_Interpret1_idx` (`idInterpret` ASC),
  INDEX `fk_Concert_has_Interpret_Concert1_idx` (`idConcert` ASC),
  CONSTRAINT `fk_Concert_has_Interpret_Concert1`
    FOREIGN KEY (`idConcert`)
    REFERENCES `ticketsonline`.`Concert` (`idConcert`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Concert_has_Interpret_Interpret1`
    FOREIGN KEY (`idInterpret`)
    REFERENCES `ticketsonline`.`Interpret` (`idInterpret`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Festival`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Festival` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Festival` (
  `idFestival` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idFestival`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Year`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Year` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Year` (
  `idYear` INT NOT NULL AUTO_INCREMENT,
  `season` VARCHAR(255) NULL,
  `volume` INT NULL,
  `start` DATETIME NULL,
  `end` DATETIME NULL,
  `idFestival` INT NOT NULL,
  `idPlace` INT NOT NULL,
  PRIMARY KEY (`idYear`),
  INDEX `fk_Year_Festival1_idx` (`idFestival` ASC),
  INDEX `fk_Year_Place1_idx` (`idPlace` ASC),
  CONSTRAINT `fk_Year_Festival1`
    FOREIGN KEY (`idFestival`)
    REFERENCES `ticketsonline`.`Festival` (`idFestival`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Year_Place1`
    FOREIGN KEY (`idPlace`)
    REFERENCES `ticketsonline`.`Place` (`idPlace`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`User` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`User` (
  `idUser` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `surname` VARCHAR(255) NOT NULL,
  `birth` DATE NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `admin` TINYINT NOT NULL DEFAULT 0,
  `idCity` INT NOT NULL,
  PRIMARY KEY (`idUser`),
  INDEX `fk_User_City1_idx` (`idCity` ASC))
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Ticket`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Ticket` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Ticket` (
  `idTicket` INT NOT NULL AUTO_INCREMENT,
  `price` INT NOT NULL,
  `bought` TINYINT NOT NULL DEFAULT 0,
  `idTicketType` INT NOT NULL,
  `idYear` INT NULL,
  `idConcert` INT NULL,
  `idUser` INT NOT NULL,
  `type` ENUM("SIT", "STAND", "VIP") NOT NULL,
  PRIMARY KEY (`idTicket`),
  INDEX `fk_Ticket_Year1_idx` (`idYear` ASC),
  INDEX `fk_Ticket_Concert1_idx` (`idConcert` ASC),
  INDEX `fk_Ticket_User1_idx` (`idUser` ASC),
  CONSTRAINT `fk_Ticket_Year1`
    FOREIGN KEY (`idYear`)
    REFERENCES `ticketsonline`.`Year` (`idYear`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ticket_Concert1`
    FOREIGN KEY (`idConcert`)
    REFERENCES `ticketsonline`.`Concert` (`idConcert`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ticket_User1`
    FOREIGN KEY (`idUser`)
    REFERENCES `ticketsonline`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Year_has_Interpret`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Year_has_Interpret` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Year_has_Interpret` (
  `idInterpret` INT NOT NULL,
  `idYear` INT NOT NULL,
  PRIMARY KEY (`idInterpret`, `idYear`),
  INDEX `fk_Interpret_has_Year_Year1_idx` (`idYear` ASC),
  INDEX `fk_Interpret_has_Year_Interpret1_idx` (`idInterpret` ASC),
  CONSTRAINT `fk_Interpret_has_Year_Interpret1`
    FOREIGN KEY (`idInterpret`)
    REFERENCES `ticketsonline`.`Interpret` (`idInterpret`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Interpret_has_Year_Year1`
    FOREIGN KEY (`idYear`)
    REFERENCES `ticketsonline`.`Year` (`idYear`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Stage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Stage` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Stage` (
  `idStage` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `capacity` INT NULL,
  `capacityInterprets` INT NULL,
  `area_m2` INT NULL,
  PRIMARY KEY (`idStage`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Year_has_Stage`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Year_has_Stage` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Year_has_Stage` (
  `idYear` INT NOT NULL,
  `idStage` INT NOT NULL,
  PRIMARY KEY (`idYear`, `idStage`),
  INDEX `fk_Year_has_Stage_Stage1_idx` (`idStage` ASC),
  INDEX `fk_Year_has_Stage_Year1_idx` (`idYear` ASC),
  CONSTRAINT `fk_Year_has_Stage_Year1`
    FOREIGN KEY (`idYear`)
    REFERENCES `ticketsonline`.`Year` (`idYear`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Year_has_Stage_Stage1`
    FOREIGN KEY (`idStage`)
    REFERENCES `ticketsonline`.`Stage` (`idStage`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`Stage_has_Interpret`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`Stage_has_Interpret` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`Stage_has_Interpret` (
  `idStage` INT NOT NULL,
  `idInterpret` INT NOT NULL,
  `headliner` TINYINT NOT NULL DEFAULT 0,
  `start` DATETIME NOT NULL,
  `end` DATETIME NOT NULL,
  PRIMARY KEY (`idStage`, `idInterpret`),
  INDEX `fk_Stage_has_Interpret_Interpret1_idx` (`idInterpret` ASC),
  INDEX `fk_Stage_has_Interpret_Stage1_idx` (`idStage` ASC),
  CONSTRAINT `fk_Stage_has_Interpret_Stage1`
    FOREIGN KEY (`idStage`)
    REFERENCES `ticketsonline`.`Stage` (`idStage`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Stage_has_Interpret_Interpret1`
    FOREIGN KEY (`idInterpret`)
    REFERENCES `ticketsonline`.`Interpret` (`idInterpret`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`User_has_Interpret`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`User_has_Interpret` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`User_has_Interpret` (
  `idUser` INT NOT NULL,
  `idInterpret` INT NOT NULL,
  PRIMARY KEY (`idUser`, `idInterpret`),
  INDEX `fk_User_has_Interpret_Interpret1_idx` (`idInterpret` ASC),
  INDEX `fk_User_has_Interpret_User1_idx` (`idUser` ASC),
  CONSTRAINT `fk_User_has_Interpret_User1`
    FOREIGN KEY (`idUser`)
    REFERENCES `ticketsonline`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Interpret_Interpret1`
    FOREIGN KEY (`idInterpret`)
    REFERENCES `ticketsonline`.`Interpret` (`idInterpret`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ticketsonline`.`ZipCode`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ticketsonline`.`ZipCode` ;

CREATE TABLE IF NOT EXISTS `ticketsonline`.`ZipCode` (
  `idzipCode` INT NOT NULL AUTO_INCREMENT,
  `zip` VARCHAR(6) NOT NULL,
  `idCity` INT NOT NULL,
  PRIMARY KEY (`idzipCode`),
  INDEX `fk_zipCode_City1_idx` (`idCity` ASC),
  CONSTRAINT `fk_zipCode_City1`
    FOREIGN KEY (`idCity`)
    REFERENCES `ticketsonline`.`City` (`idCity`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
