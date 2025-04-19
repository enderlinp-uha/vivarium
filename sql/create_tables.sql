-- 
-- Création de la base de données
-- 
-- CREATE DATABASE IF NOT EXISTS `vivarium`;

-- 
-- Utilisation de la base de données
--
-- USE `vivarium`;

-- 
-- Création des tables
--
CREATE TABLE IF NOT EXISTS `snakes` (
  `id_snake` INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT "Identifiant unique du serpent",
  `name` VARCHAR(50) NOT NULL COMMENT "Nom du serpent",
  `weight` INT unsigned NOT NULL COMMENT "Poids du serpent en grammes",
  `lifespan` INT unsigned NOT NULL COMMENT "Durée de vie du serpent en années",
  `birth_date` DATETIME NOT NULL COMMENT "Date et heure de naissance du serpent",
  `status` ENUM('Vivant', 'Mort') NOT NULL DEFAULT 'Vivant' COMMENT "Statut du serpent",
  `race` VARCHAR(50) NOT NULL COMMENT "Race de serpent",
  `gender` ENUM('Mâle', 'Femelle') NOT NULL COMMENT "Genre du serpent",
  `parent1_id` INT unsigned NULL COMMENT "Identifiant du parent #1",
  `parent2_id` INT unsigned NULL COMMENT "Identifiant du parent #2",
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT "Date et heure de création",
  `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP COMMENT "Date et heure de modification",
  `deleted_at` DATETIME NULL COMMENT "Date et heure de suppression",
  INDEX (`parent1_id`),
  INDEX (`parent2_id`),
  CONSTRAINT fk_parent1 FOREIGN KEY (parent1_id) REFERENCES snakes(id_snake) ON DELETE SET NULL,
  CONSTRAINT fk_parent2 FOREIGN KEY (parent2_id) REFERENCES snakes(id_snake) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `couplings` (
  `id_coupling` INT unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT "Identifiant unique de l'accouplement",
  `id_male` INT unsigned NOT NULL COMMENT "Clef étrangère du mâle",
  `id_female` INT unsigned NOT NULL COMMENT "Clef étrangère de la femelle",
  `children_count` TINYINT unsigned NOT NULL DEFAULT 0 COMMENT "Nombre d'enfants générés",
  `max_children` TINYINT unsigned NOT NULL DEFAULT 3 COMMENT "Limite maximale d'enfants",
  `last_reproduction_at` DATETIME DEFAULT NULL COMMENT 'Date de la dernière reproduction',
  `created_at` DATETIME NOT NULL COMMENT "Date et heure de création",
  FOREIGN KEY (id_male) REFERENCES snakes(id_snake),
  FOREIGN KEY (id_female) REFERENCES snakes(id_snake)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
