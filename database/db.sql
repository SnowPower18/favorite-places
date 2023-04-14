CREATE DATABASE IF NOT EXISTS `locations`;
USE `locations`;

CREATE TABLE `utenti` (
    `id_utente` INT AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(16) NOT NULL,
    `password` VARCHAR(16) NOT NULL,
    `privileges` ,
    PRIMARY KEY (`id_utente`)
);

CREATE TABLE `locations` (
    `id_location` INT AUTO_INCREMENT NOT NULL,
    `id_utente` INT NOT NULL,
    `nome` VARCHAR(16) NOT NULL,
    `lat` FLOAT NOT NULL,
    `lng` FLOAT NOT NULL,
    `public` TINYINT(1) NOT NULL,
    PRIMARY KEY (`id_location`),
    FOREIGN KEY (`id_utente`) REFERENCES `utenti`(`id_utente`)
);