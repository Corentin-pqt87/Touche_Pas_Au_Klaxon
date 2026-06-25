-- TPAK.`role` definition

CREATE TABLE `role` (
  `idrole` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `level` int DEFAULT NULL,
  PRIMARY KEY (`idrole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- TPAK.users definition

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