-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: courses_db
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `full_name` varchar(100) DEFAULT '',
  `phone` varchar(20) DEFAULT '',
  `photo` varchar(255) DEFAULT '',
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','sebastian321hernandezno@gmail.com','Sebastian Andres Hernandez Noriega ','3004659844','','$2y$10$FY8zTXGbwBm4rp1GiXUsQuFqu96lg8y/C.sCxoWFKpFZ/mNgmuICm','admin','approved','2026-05-15 16:09:23'),(3,'user','sebastian321hernandezno@gmail.com','Jesus Alfonso Lucumi ','3004659844','user_3_1778955476.jpg','$2y$10$oft4GgbQMAxmgpF.ljdolOf.VV0vgaF2O/2ExEwkSuvHmNaokCyjq','user','approved','2026-05-15 16:22:28'),(4,'luis','sh1021394280@gmail.com','','','','$2y$10$Gybb1NkmCiok.VzaQKqzVe9pDH3W4UlAHGcqHhHNR7wXGbAr/k8p6','user','approved','2026-05-15 18:46:26'),(10,'Sebastian','sebastian321hernandezno@gmail.com','','','','$2y$10$W/kp2Em/TgG3Y0NIJLemn.Mo6zHL8IA2CBzF7REWtpSKg6GPNaRcW','user','approved','2026-05-16 07:40:29'),(11,'Pedro ','sh1021394280@gmail.com','','','','$2y$10$ZOn0gBepP29nNdc8afu8nuyCbvTLYF8giFSNMElWp7iz70nNxIO9e','user','approved','2026-05-16 07:42:43'),(12,'sebas','sh1021394280@gmail.com','','','','$2y$10$A5G.JGkA0R30cXvP8TMDNOlWA/mOCxMVWwnemXS1Ds66NFKgiuwam','user','rejected','2026-05-16 19:10:30'),(13,'nata ','paolaagudelo082004@gmail.com','','','','$2y$10$T6MqMajURWwA01oooc1WSunmH1NFsRKKl.RHWr6TGDBANQkktT.GO','user','approved','2026-05-16 19:12:45'),(15,'pedro','jose200516@gmail.com','','','','$2y$10$ylCWEoI2Szh1sFTzJy4i3uR6lUa7Jmqk1c.Yen.x7XdMn1qcx.7q2','user','rejected','2026-05-18 19:36:27'),(16,'pedro luis','jose200516@gmail.com','','','','$2y$10$X5v8oMBKm.yWeZ64TOlTh.hi1WFNk7rdEG0r/MoRDJstTJL9byqV6','user','approved','2026-05-18 19:43:26'),(17,'vera ','veranoriega04@gmail.com','','','','$2y$10$0qKqLkyS9udEU/iN5pi1G.X6yzHdIygCo/do7bcVAxc5oZPqt8/qu','user','approved','2026-05-18 19:59:13'),(18,'jesus ','ninojesus816@gmail.com','','','','$2y$10$XN6tZ08K9CUzf1lAjPxDIe6Mk9S4IsXLoJzNQDDVgrgvpd5qWkdb2','user','approved','2026-05-18 20:03:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-18 23:34:53
