-- --------------------------------------------------------
-- TPAK - Schéma complet de la base de données
-- --------------------------------------------------------

-- Table `role`
CREATE TABLE `role` (
  `idrole` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `level` int DEFAULT NULL,
  PRIMARY KEY (`idrole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données initiales des rôles (obligatoire avant tout INSERT dans users)
INSERT INTO `role` (`idrole`, `name`, `level`) VALUES
  (1, 'admin', 1),
  (2, 'utilisateur', 0);


-- --------------------------------------------------------
-- Table `users`
-- --------------------------------------------------------
CREATE TABLE `users` (
  `idusers` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `mail` varchar(45) DEFAULT NULL,
  `phone` int DEFAULT NULL,
  `idrole` int DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`idusers`),
  KEY `users_role` (`idrole`),
  CONSTRAINT `users_role` FOREIGN KEY (`idrole`) REFERENCES `role` (`idrole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- TPAK.agences definition
-- --------------------------------------------------------

CREATE TABLE `agences` (
  `idagences` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idagences`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table `posts` (trajets proposés par les utilisateurs)
-- --------------------------------------------------------
CREATE TABLE `posts` (
  `idposts` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `departure` int NOT NULL,
  `arrival` int NOT NULL,
  `travel_date` datetime DEFAULT NULL,
  `seats` int DEFAULT '1',
  `idusers` int DEFAULT NULL,
  `arrival_date` datetime DEFAULT NULL,
  PRIMARY KEY (`idposts`),
  KEY `posts_users` (`idusers`),
  KEY `posts_departure_agence` (`departure`),
  KEY `posts_arrival_agence` (`arrival`),
  CONSTRAINT `fk_posts_arrival` FOREIGN KEY (`arrival`) REFERENCES `agences` (`idagences`),
  CONSTRAINT `fk_posts_departure` FOREIGN KEY (`departure`) REFERENCES `agences` (`idagences`),
  CONSTRAINT `posts_users` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
