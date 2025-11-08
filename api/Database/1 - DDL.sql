START TRANSACTION;

CREATE DATABASE IF NOT EXISTS `Yerothia`;

USE `Yerothia`;

DROP TABLE IF EXISTS `AutomatischeIncassos`;
DROP TABLE IF EXISTS `AbonnementsProducten`;
DROP TABLE IF EXISTS `Abonnementen`;
DROP TABLE IF EXISTS `Facturen`;
DROP TABLE IF EXISTS `Services`;
DROP TABLE IF EXISTS `Retouren`;
DROP TABLE IF EXISTS `Orderregels`;
DROP TABLE IF EXISTS `Orders`;
DROP TABLE IF EXISTS `Adressen`;
DROP TABLE IF EXISTS `Klanten`;
DROP TABLE IF EXISTS `Accounts`;
DROP TABLE IF EXISTS `Emails`;
DROP TABLE IF EXISTS `Contacten`;
DROP TABLE IF EXISTS `Betaalgegevens`;
DROP TABLE IF EXISTS `Producten`;
DROP TABLE IF EXISTS `Zoektermen`;

CREATE TABLE IF NOT EXISTS `Emails` (
  `emailId` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Accounts` (
  `accountId` INT AUTO_INCREMENT PRIMARY KEY,
  `emailId` INT NOT NULL,
  `wachtwoord` VARCHAR(255),
  `accounttype` ENUM('Beheerder', 'Klant', 'Medewerker') NOT NULL,
  FOREIGN KEY (`emailId`) 
    REFERENCES `Emails`(`emailId`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
)  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Contacten` (
  `contactId` INT AUTO_INCREMENT PRIMARY KEY,
  `telefoonnummer` INT,
  `contactpersoon` VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Klanten` (
  `klantId` INT AUTO_INCREMENT PRIMARY KEY,
  `kvkNummer` INT(8) UNSIGNED NOT NULL,
  `accountId` INT,
  FOREIGN KEY (`accountId`) 
    REFERENCES `Accounts`(`accountId`)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Adressen` (
  `adresId` INT AUTO_INCREMENT PRIMARY KEY,
  `vestigingsnaam` VARCHAR(255),
  `postcode` VARCHAR(10) NOT NULL,
  `huisnummer` INT NOT NULL,
  `toevoeging` VARCHAR(10),
  `klantId` INT NOT NULL,
  `contactId` INT,
  `emailId` INT,
  FOREIGN KEY (`klantId`) 
    REFERENCES `Klanten`(`klantId`) 
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (`contactId`) 
    REFERENCES `Contacten`(`contactId`) 
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  FOREIGN KEY (`emailId`) 
    REFERENCES `Emails`(`emailId`) 
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Abonnementen` (
  `abonnementId` INT AUTO_INCREMENT PRIMARY KEY,
  `naam` VARCHAR(255),
  `omschrijving` TEXT,
  `omschrijving_en` TEXT,
  `prijs` DECIMAL(10, 2),
  `afsluitdatum` DATE,
  `ingangsdatum` DATE,
  `abonnementsperiode` ENUM('1 jaar', '2 jaar', '3 jaar'),
  `capaciteit` INT,
  `afgesloten` BOOLEAN DEFAULT FALSE,
  `adresId` INT NOT NULL,
  FOREIGN KEY (`adresId`) 
    REFERENCES `Adressen`(`adresId`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Producten` (
  `productId` INT PRIMARY KEY AUTO_INCREMENT,
  `naam` VARCHAR(255) NOT NULL,
  `naam_en` VARCHAR(255) NOT NULL,
  `omschrijving` TEXT,
  `omschrijving_en` TEXT,
  `prijs` DECIMAL(10, 2),
  `image` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `AbonnementsProducten` (
  `productId` INT NOT NULL,
  `abonnementId` INT NOT NULL,
  `aantal` INT DEFAULT 1,
  PRIMARY KEY (`productId`, `abonnementId`),
  FOREIGN KEY (`productId`) 
    REFERENCES `Producten`(`productID`) 
    ON UPDATE CASCADE
    ON DELETE CASCADE, 
  FOREIGN KEY (`abonnementId`) 
    REFERENCES `Abonnementen`(`abonnementId`) 
    ON UPDATE CASCADE
    ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Betaalgegevens` (
  `betaalId` INT PRIMARY KEY AUTO_INCREMENT,
  `rekeningnummer` VARCHAR(34) NOT NULL,
  `betaalmethode` ENUM('Creditcard', 'iDEAL', 'Factuur', 'Incasso', 'OpRekening') NOT NULL,
  `betalingstype` ENUM('Eenmalig', 'Maandelijks', 'Jaarlijks') NOT NULL,
  `betaaldatum` DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `AutomatischeIncassos` (
  `abonnementId` INT,
  `betaalId` INT,
  `status` ENUM('Lopend', 'Betaald', 'Beëindigd', 'Mislukt', 'Geannuleerd') NOT NULL,
  `incassobedrag` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`abonnementId`, `betaalId`),
  FOREIGN KEY (`abonnementId`) 
    REFERENCES `Abonnementen`(`abonnementId`) 
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (`betaalId`) 
    REFERENCES `Betaalgegevens`(`betaalId`) 
    ON UPDATE CASCADE
    ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Orders` (
  `orderId` INT AUTO_INCREMENT PRIMARY KEY,
  `status` ENUM('Winkelmand', 'Betaald', 'Geleverd', 'Geannuleerd') NOT NULL,
  `totaalprijs` DECIMAL(10, 2) DEFAULT 0,
  `orderdatum` DATE,
  `adresId` INT NOT NULL,
  `betaald` BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (`adresId`) 
    REFERENCES `Adressen`(`adresId`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Facturen` (
  `factuurId` INT AUTO_INCREMENT PRIMARY KEY,
  `adresId` INT,
  `betaalId` INT,
  `orderId` INT NOT NULL,
  `status` ENUM('Open', 'Betaald', 'Vervallen', 'Mislukt', 'Geannuleerd'),
  `factuurbedrag` DECIMAL(10,2) DEFAULT 0,
  FOREIGN KEY (`adresId`) 
    REFERENCES `Adressen`(`adresId`) 
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  FOREIGN KEY (`betaalId`) 
    REFERENCES `Betaalgegevens`(`betaalId`) 
    ON UPDATE CASCADE
    ON DELETE SET NULL, 
  FOREIGN KEY (`orderId`) 
    REFERENCES `Orders`(`orderId`) 
    ON UPDATE CASCADE
    ON DELETE RESTRICT 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Orderregels` (
  `orderregelId` INT AUTO_INCREMENT PRIMARY KEY,
  `orderId` INT NOT NULL,
  `productId` INT NOT NULL,
  `factuurprijs` DECIMAL(10, 2),
  `aantal` INT,
  FOREIGN KEY (`orderId`) 
    REFERENCES `Orders`(`orderId`) 
    ON UPDATE CASCADE
    ON DELETE CASCADE, 
  FOREIGN KEY (`productId`) 
    REFERENCES `Producten`(`productId`) 
    ON UPDATE CASCADE
    ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Retouren` (
  `retourId` INT AUTO_INCREMENT PRIMARY KEY,
  `reden` TEXT,
  `aanvraagdatum` DATE,
  `retourdatum` DATE,
  `orderregelId` INT NOT NULL,
  `status` VARCHAR(255) NOT NULL,
  FOREIGN KEY (`orderregelId`) 
    REFERENCES `Orderregels`(`orderregelId`) 
    ON UPDATE CASCADE
    ON DELETE RESTRICT 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Services` (
  `serviceId` INT AUTO_INCREMENT PRIMARY KEY,
  `servicetijdstip` DATETIME,
  `aanvraagdatum` DATE,
  `soortservice` ENUM('Serviceaanvraag', 'Chataanvraag', 'Retouraanvraag'), 
  `adresId` INT NOT NULL,
  `retourId` INT DEFAULT NULL,
  FOREIGN KEY (`adresId`) 
    REFERENCES `Adressen`(`adresId`) 
    ON UPDATE CASCADE
    ON DELETE CASCADE, 
  FOREIGN KEY (`retourId`) 
    REFERENCES `Retouren`(`retourId`) 
    ON UPDATE CASCADE
    ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Zoektermen` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `zoekterm` VARCHAR(255) NOT NULL,
    `aantal` INT NOT NULL DEFAULT 1,
    `tijdstip` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (`zoekterm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `Services`
  CHANGE COLUMN `soortservice` `servicetype` ENUM('Serviceaanvraag', 'Chataanvraag', 'Retouraanvraag');

COMMIT;
