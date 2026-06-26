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
-- Table `posts` (trajets proposés par les utilisateurs)
-- --------------------------------------------------------
CREATE TABLE `posts` (
  `idposts` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `departure` varchar(100) NOT NULL,
  `arrival` varchar(100) NOT NULL,
  `travel_date` datetime DEFAULT NULL,
  `seats` int DEFAULT 1,
  `idusers` int DEFAULT NULL,
  PRIMARY KEY (`idposts`),
  KEY `posts_users` (`idusers`),
  CONSTRAINT `posts_users` FOREIGN KEY (`idusers`) REFERENCES `users` (`idusers`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;