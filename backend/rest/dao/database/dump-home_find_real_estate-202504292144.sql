-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: home_find_real_estate
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `property_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1,1,2,'2025-04-01 19:44:20'),(2,1,3,'2025-04-28 19:13:26'),(5,1,5,'2025-04-28 22:02:41'),(6,4,7,'2025-04-28 22:02:41'),(7,6,10,'2025-04-28 22:02:41'),(8,7,8,'2025-04-28 22:02:42'),(9,8,15,'2025-04-28 22:02:42'),(10,9,12,'2025-04-28 22:02:42'),(11,10,6,'2025-04-28 22:02:42'),(12,11,16,'2025-04-28 22:02:42'),(13,12,9,'2025-04-28 22:02:42'),(14,13,14,'2025-04-28 22:02:42'),(15,14,11,'2025-04-28 22:02:42'),(16,15,18,'2025-04-28 22:02:42'),(17,16,20,'2025-04-28 22:02:42'),(18,17,17,'2025-04-28 22:02:42'),(19,18,22,'2025-04-28 22:02:42');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `properties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(12,2) NOT NULL,
  `property_type` enum('townhouse','apartment','villa','studio','commercial') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sqft` int DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `listing_type` enum('sale','rent') DEFAULT NULL,
  `bedrooms` int DEFAULT NULL,
  `bathrooms` int DEFAULT NULL,
  `agent_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `additional_features` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_id` (`agent_id`),
  CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` VALUES (2,'Modern Apartment in Sarajevo','A beautiful 2-bedroom apartment in the center of Sarajevo.',250000.00,'apartment',80,NULL,'Sarajevo',NULL,'sale',2,1,3,'2025-04-01 19:24:34',NULL,NULL),(3,'Luxury Villa with Ocean View','Stunning 3-bedroom villa featuring panoramic ocean views and private beach access.',3250000.00,'villa',3200,'45 Coastal Drive','Malibu','90265','sale',3,3,5,'2025-04-28 18:49:01','USA','[\"beach_access\", \"smart_home\", \"heated_pool\", \"wine_cellar\"]'),(5,'Luxury Villa in Beverly Hills','A beautiful luxury villa with a private pool and garden.',2500000.00,'villa',4500,'123 Beverly Hills St','Los Angeles','90210','sale',5,4,3,'2025-04-28 21:51:50','USA','[\"pool\", \"garden\", \"garage\"]'),(6,'Modern Studio Downtown','Perfect for singles or students, close to amenities.',1500.00,'studio',550,'789 City Center Ave','New York','10001','rent',1,1,5,'2025-04-28 21:51:50','USA','[\"elevator\", \"security\"]'),(7,'Cozy Apartment in Paris','Charming apartment near the Eiffel Tower.',750000.00,'apartment',900,'45 Rue de Paris','Paris','75007','sale',2,1,26,'2025-04-28 21:51:50','France','[\"balcony\", \"elevator\"]'),(8,'Spacious Townhouse','Ideal for families, located in a peaceful suburb.',400000.00,'townhouse',1800,'12 Greenfield Ln','Dallas','75201','sale',3,3,27,'2025-04-28 21:51:50','USA','[\"garage\", \"garden\"]'),(9,'Commercial Space in Downtown','Prime commercial property ideal for shops and offices.',950000.00,'commercial',5000,'67 Business Park','Chicago','60601','sale',0,2,28,'2025-04-28 21:51:50','USA','[\"parking\", \"elevator\"]'),(10,'Seaside Villa with Ocean View','Experience luxury living with this stunning seaside villa offering panoramic ocean views. Spacious living areas, modern kitchen, and a large patio perfect for sunset watching. Private beach access included.',3500000.00,'villa',4800,'456 Ocean Drive','Miami','33139','sale',5,5,3,'2025-04-28 21:57:25','USA','[\"pool\", \"private_beach\", \"jacuzzi\"]'),(11,'Downtown Penthouse Apartment','Live in the heart of the city in this luxurious penthouse apartment. Features include a rooftop terrace, designer interiors, and state-of-the-art appliances. Close to shopping, dining, and entertainment.',2000000.00,'apartment',2200,'789 Skyline Ave','New York','10018','sale',3,2,5,'2025-04-28 21:57:25','USA','[\"rooftop\", \"concierge\", \"gym\"]'),(12,'Modern Studio near University','A stylish and efficient studio apartment ideal for students or young professionals. Walking distance to the main university campus and public transportation. Secure building with amenities.',1200.00,'studio',450,'321 College Street','Boston','02115','rent',1,1,26,'2025-04-28 21:57:25','USA','[\"security\", \"bike_storage\"]'),(13,'Elegant Family Townhouse','This beautifully maintained townhouse offers a family-friendly neighborhood, spacious interiors, and a fenced backyard. Perfect for families seeking comfort, convenience, and community.',450000.00,'townhouse',2000,'678 Maple Drive','Atlanta','30303','sale',4,3,27,'2025-04-28 21:57:25','USA','[\"garage\", \"garden\", \"playground\"]'),(14,'Retail Commercial Space Downtown','Prime location for your business! This spacious commercial space offers high foot traffic, large display windows, and flexible lease terms. Ideal for retail, boutique, or office use.',8000.00,'commercial',3600,'123 Commerce Blvd','Houston','77002','rent',0,2,28,'2025-04-28 21:57:25','USA','[\"parking\", \"large_windows\"]'),(15,'Charming Villa in Tuscany','Escape to the countryside with this charming villa nestled in the hills of Tuscany. Stone construction, rustic interiors, vineyard views, and a large pool create an authentic Italian retreat.',1500000.00,'villa',3700,'Via delle Rose 45','Florence','50123','sale',4,3,3,'2025-04-28 21:57:25','Italy','[\"pool\", \"vineyard_view\", \"fireplace\"]'),(16,'Studio Flat in Central London','Modern and compact studio flat perfect for urban living. Minutes away from major tube stations and located within a secure building. Modern kitchen and stylish bathroom included.',1800.00,'studio',400,'99 Oxford Street','London','W1D 2DZ','rent',1,1,5,'2025-04-28 21:57:25','UK','[\"security\", \"modern_kitchen\"]'),(17,'Newly Renovated Apartment','This modern apartment features fresh renovations including updated appliances, hardwood floors, and a redesigned open floor plan. Perfectly located near parks and local cafes.',2300.00,'apartment',1000,'88 Elm Street','San Francisco','94102','rent',2,2,26,'2025-04-28 21:57:25','USA','[\"hardwood_floors\", \"open_plan\"]'),(18,'Suburban Family Townhouse','Bright and airy townhouse in a quiet suburb with parks, schools, and shops nearby. Offers a private backyard, modern kitchen, and community amenities like a pool and playground.',380000.00,'townhouse',1600,'250 Sunnyvale Road','Seattle','98101','sale',3,2,27,'2025-04-28 21:57:25','USA','[\"community_pool\", \"playground\", \"garage\"]'),(19,'Luxury Commercial Office Space','Premium office space with panoramic city views, a professional lobby, and multiple conference rooms. Perfect for headquarters or high-profile businesses.',15000.00,'commercial',8000,'1 Financial Center','Boston','02110','rent',0,4,28,'2025-04-28 21:57:25','USA','[\"conference_rooms\", \"lobby\", \"valet_parking\"]'),(20,'Historic Townhouse in Old Town','Own a piece of history with this lovingly restored townhouse in Old Town. Classic charm meets modern upgrades. Walking distance to local shops, museums, and riverside cafes.',620000.00,'townhouse',1700,'12 Old Town Road','Philadelphia','19106','sale',3,2,3,'2025-04-28 21:57:25','USA','[\"historic\", \"renovated\"]'),(21,'Secluded Mountain Villa','Tucked away in a lush mountain landscape, this villa offers privacy, natural beauty, and luxury. Features a heated pool, stone fireplace, and stunning hiking trails right outside.',2700000.00,'villa',5200,'99 Highland View','Aspen','81611','sale',6,6,5,'2025-04-28 21:57:25','USA','[\"mountain_view\", \"heated_pool\", \"fireplace\"]'),(22,'Urban Chic Apartment','Step into a fully upgraded chic apartment with exposed brick walls, industrial lighting, and open concept design. Located in the trendiest district downtown.',2800.00,'apartment',1200,'55 Industrial Way','Austin','73301','rent',2,2,26,'2025-04-28 21:57:25','USA','[\"exposed_brick\", \"city_view\"]'),(23,'Contemporary Commercial Building','State-of-the-art commercial building designed for flexibility. Easily customizable layouts and green building certifications make it the ideal investment.',2900000.00,'commercial',12000,'777 Innovation Drive','San Jose','95112','sale',0,6,27,'2025-04-28 21:57:25','USA','[\"green_building\", \"custom_layouts\", \"large_parking\"]');
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property_images`
--

DROP TABLE IF EXISTS `property_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `property_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `property_id` int NOT NULL,
  `image_url` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `property_images_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property_images`
--

LOCK TABLES `property_images` WRITE;
/*!40000 ALTER TABLE `property_images` DISABLE KEYS */;
INSERT INTO `property_images` VALUES (1,3,'someimage.jpg','2025-04-28 19:39:32'),(2,2,'image2_1.jpg','2025-04-28 22:07:52'),(3,2,'image2_2.jpg','2025-04-28 22:07:52'),(4,3,'image3_1.jpg','2025-04-28 22:07:52'),(5,5,'image5_1.jpg','2025-04-28 22:07:52'),(6,5,'image5_2.jpg','2025-04-28 22:07:52'),(7,6,'image6_1.jpg','2025-04-28 22:07:52'),(8,7,'image7_1.jpg','2025-04-28 22:07:52'),(9,7,'image7_2.jpg','2025-04-28 22:07:52'),(10,8,'image8_1.jpg','2025-04-28 22:07:52'),(11,9,'image9_1.jpg','2025-04-28 22:07:52'),(12,10,'image10_1.jpg','2025-04-28 22:07:52'),(13,11,'image11_1.jpg','2025-04-28 22:07:52'),(14,11,'image11_2.jpg','2025-04-28 22:07:52'),(15,12,'image12_1.jpg','2025-04-28 22:07:52'),(16,13,'image13_1.jpg','2025-04-28 22:07:52'),(17,14,'image14_1.jpg','2025-04-28 22:07:52'),(18,15,'image15_1.jpg','2025-04-28 22:07:52'),(19,16,'image16_1.jpg','2025-04-28 22:07:52'),(20,17,'image17_1.jpg','2025-04-28 22:07:52'),(21,18,'image18_1.jpg','2025-04-28 22:07:52'),(22,19,'image19_1.jpg','2025-04-28 22:07:52'),(23,20,'image20_1.jpg','2025-04-28 22:07:52'),(24,21,'image21_1.jpg','2025-04-28 22:07:53'),(25,22,'image22_1.jpg','2025-04-28 22:07:53'),(26,23,'image23_1.jpg','2025-04-28 22:07:53');
/*!40000 ALTER TABLE `property_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rentals`
--

DROP TABLE IF EXISTS `rentals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rentals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `property_id` int NOT NULL,
  `user_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rentals`
--

LOCK TABLES `rentals` WRITE;
/*!40000 ALTER TABLE `rentals` DISABLE KEYS */;
INSERT INTO `rentals` VALUES (1,2,4,'2025-06-01','2025-06-10','2025-04-28 20:47:41'),(3,3,1,'2025-06-15','2025-06-20','2025-04-28 20:48:59'),(4,6,6,'2025-07-01','2025-07-08','2025-04-28 22:12:59'),(5,12,7,'2025-07-05','2025-07-10','2025-04-28 22:12:59'),(6,14,8,'2025-07-12','2025-07-18','2025-04-28 22:12:59'),(7,16,9,'2025-07-15','2025-07-22','2025-04-28 22:12:59'),(8,17,10,'2025-07-20','2025-07-25','2025-04-28 22:12:59'),(9,19,11,'2025-07-25','2025-08-01','2025-04-28 22:12:59'),(10,22,12,'2025-08-01','2025-08-08','2025-04-28 22:12:59'),(11,19,13,'2025-08-05','2025-08-12','2025-04-28 22:12:59'),(12,17,14,'2025-08-10','2025-08-17','2025-04-28 22:12:59'),(13,16,15,'2025-08-15','2025-08-22','2025-04-28 22:12:59'),(14,14,16,'2025-08-20','2025-08-27','2025-04-28 22:12:59'),(15,12,17,'2025-08-25','2025-09-01','2025-04-28 22:12:59'),(16,6,18,'2025-08-30','2025-09-06','2025-04-28 22:12:59'),(17,19,19,'2025-09-01','2025-09-08','2025-04-28 22:12:59'),(18,17,20,'2025-09-05','2025-09-12','2025-04-28 22:12:59'),(19,6,8,'2025-09-10','2025-09-15','2025-04-28 22:55:30');
/*!40000 ALTER TABLE `rentals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `property_id` int NOT NULL,
  `rating` tinyint DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_chk_1` CHECK (((`rating` >= 1) and (`rating` <= 5)))
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,2,4,'Great property! Loved the space and location.','2025-04-01 19:48:35'),(2,1,5,5,'Absolutely stunning place! Would highly recommend.','2025-04-28 22:24:13'),(3,6,6,4,'Very clean and well-maintained. Great experience overall.','2025-04-28 22:24:13'),(4,7,7,3,'Decent property but could use some upgrades.','2025-04-28 22:24:13'),(5,8,8,5,'Perfect for our family trip. Everything was as described.','2025-04-28 22:24:13'),(6,9,9,4,'Nice neighborhood, property was cozy and comfortable.','2025-04-28 22:24:13'),(7,10,10,5,'Luxury at its finest. Could not ask for more.','2025-04-28 22:24:13'),(8,11,11,4,'Very convenient location, would stay again.','2025-04-28 22:24:13'),(9,12,12,5,'Loved the modern design and amenities.','2025-04-28 22:24:13'),(10,13,13,3,'It was okay, but the pictures looked better than reality.','2025-04-28 22:24:13'),(11,14,14,5,'Incredible stay, very peaceful and beautiful views.','2025-04-28 22:24:13'),(12,15,15,4,'Good value for the price, minor issues but nothing major.','2025-04-28 22:24:13'),(13,16,16,5,'Absolutely perfect getaway, highly recommend it.','2025-04-28 22:24:13'),(14,17,17,4,'Really enjoyed the pool and garden area.','2025-04-28 22:24:13'),(15,18,18,5,'Spotless property, super responsive host.','2025-04-28 22:24:13'),(16,19,19,5,'Best experience ever! I will definitely book again.','2025-04-28 22:24:13'),(17,20,20,2,'Not as clean as advertised. The location was good but the property needs work.','2025-04-28 22:24:13'),(18,21,21,1,'Very disappointing stay. The amenities were broken and the photos were misleading.','2025-04-28 22:24:13'),(19,22,22,2,'Okay for a short stay, but not worth the price. Would not recommend for longer trips.','2025-04-28 22:24:13'),(20,23,23,1,'Terrible experience. Unresponsive host and dirty rooms.','2025-04-28 22:24:13');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `user_role` enum('customer','agent','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `profile_picture` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `password_changed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'John','Doe','john@example.com','$2y$10$fBopZb/rVN8.7STOrnCCTO0YG7rieqDfNNsOcfzyuUUaFAG1i3RNq','12345','customer','fhkshks','2025-03-26 13:35:42','2025-03-26 10:17:38'),(3,'Bob','Smith','bob@example.com','$2y$10$MNxr8uhStvWSKS0Il.001.rpAInLVqbxtB6FQhC6G/G61PPou9qWu','75285432','agent','ffgjdevl','2025-04-01 19:05:50','2025-04-28 17:22:47'),(4,'Gregory','House','greg@example.com','$2y$10$1c9U0I/9QlcVtrvJwqMy2e8jD1cobE23PA8ZQ0aHvkoOv5fuv83Jm','+12356543','customer','default.png','2025-04-28 17:15:05','2025-04-28 17:15:05'),(5,'Dora','The Explorer','dora@example.com','$2y$10$9azWrXJI8i1yrtpI9oYVAu/b8FOCOjlmOkcMIBPcvY8BKZd9bwVwu','+0987654321','agent','agent.png','2025-04-28 17:22:47','2025-04-28 17:22:47'),(6,'Alice','Johnson','alice.johnson@example.com','$2y$10$pRh28SqFm8AyARObrR4Hiu/c1TKlZF8SZjLccerAiS3pCUuCUhHeS','+1111111','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(7,'Charlie','Brown','charlie.brown@example.com','$2y$10$MQjkXpo8a0LW9dkwq/GmzO2Xo4xT9yoZIUqJGSD/f0gHHs26xwJ86','+2222222','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(8,'Eve','Williams','eve.williams@example.com','$2y$10$EN/vtQ5OoY6jhu.XJM3q6./YrnHUlwq/wVcI0bRyX/GmdA.98LJQm','+3333333','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(9,'Frank','Miller','frank.miller@example.com','$2y$10$DalvsQ7GSLqNB8uanRfdT.w6m.JDagtazf.Ytvgp6LCwswEiOPpQK','+4444444','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(10,'Grace','Lee','grace.lee@example.com','$2y$10$VpB72GVdHlWb2e15qUrH1ubj8ENwzghiGhE49M6RojXGQmd8JqhIG','+5555555','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(11,'Hank','Green','hank.green@example.com','$2y$10$EbhpKH.zQXKy3c5Dgl1U8u8pkNHlZHLGDq9qi/pMAYHzii4BD/ng.','+6666666','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(12,'Ivy','Clark','ivy.clark@example.com','$2y$10$6WWpkjvl0sWHsY2OuvT8fON8bmYHbuFkFV1IWOleRxUoIolZI1EfW','+7777777','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(13,'Jack','White','jack.white@example.com','$2y$10$GnoJPcWRguOtRI0/uhRuueG4NPOgjO6N6HtpjWA7.EBrUtDU9KUeK','+8888888','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(14,'Karen','Young','karen.young@example.com','$2y$10$f.soaKcJqXLD8RQVSf5tneC6h2Fsl7iZ/yqLKHvRBqgNkleCBpcYK','+9999999','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(15,'Leo','King','leo.king@example.com','$2y$10$a6XThi7EOzpgS8ASK.6iUOU3T/2gVDl9M3kWiWubnYXKbb.7GjIPK','+1010101010','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(16,'Mona','Scott','mona.scott@example.com','$2y$10$wyeZprdkC.jsWixpLnteLOZ/fKWRfpm9GAcApR6EhssvfM/ky84F.','+1112333','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(17,'Nate','Hill','nate.hill@example.com','$2y$10$v9C1B6NoZgQXcvxPJKK8ve9zW9Ek/rx4TilYl/q6bUnYNEXSuk5Ym','+4445566','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(18,'Olivia','Walker','olivia.walker@example.com','$2y$10$0kUoO85JHfEvLlNfz.KTd.G0Bb1.yQo16I9Msor7Mk0ZwjUK2IyLa','+7778999','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(19,'Paul','Baker','paul.baker@example.com','$2y$10$DlHmqtbeqCg5dBera0sb2eLpI2nYgVf0zL0zWVROyGH532p3i3JYC','+12121212','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(20,'Quinn','Adams','quinn.adams@example.com','$2y$10$wtK7xV7uftO4BOF3i3ryKuvVoiD5/1gI5cCNPqdCG8OEcqFR5M9/6','+34343434','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(21,'Rachel','Carter','rachel.carter@example.com','$2y$10$.lFeNhF8XMqqCkAahK6DuusKV6PpjiwxBM0g5oQ6Ji6b8sHp3PpwK','+56565656','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(22,'Sam','Perry','sam.perry@example.com','$2y$10$IckN4gR8raV63D4qpYohVu4Ka/uVUua1IRPQt8X9PISZaDukx7MPq','+78787878','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(23,'Tina','Moore','tina.moore@example.com','$2y$10$jwv91eclVdtumNbeBDY7Xu67ATX73EE8pnq9FnZZPs/nKrXFbj5TC','+90909090','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(24,'Uma','Bell','uma.bell@example.com','$2y$10$d2taYMewb0nFHh0w1nuNL.Mlsc9g0j8wMlINcw3Kt/r1J28cS1RXa','+98989898','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(25,'Victor','Cook','victor.cook@example.com','$2y$10$PWLBlKuYJB9naXCLgOwBWeNK/c75N9zCv9iV0egim4ouLPf5R7Pf2','+67676767','customer','default.png','2025-04-28 21:43:39','2025-04-28 21:43:39'),(26,'Wendy','Lopez','wendy.lopez@example.com','$2y$10$NhbQuKJX7uEGIZV9R8QAvuzWOmYmkB/X9g/tS6IKIHV1ZYZd/DxPy','+5656561234','agent','agent.png','2025-04-28 21:46:31','2025-04-28 21:46:31'),(27,'Xavier','Morgan','xavier.morgan@example.com','$2y$10$5S5gu7c.NfvvfWwsF9fkte6Uhu8M8Q3wkxoXwbPiFH15ZlfroyU/e','+7878781234','agent','agent.png','2025-04-28 21:46:31','2025-04-28 21:46:31'),(28,'Yasmine','Simmons','yasmine.simmons@example.com','$2y$10$XnVS10BsJffyFTtAobAMe.Lwax/LzHwWDQu6Rw0cruWw3TpHfxp5e','+9090901234','agent','agent.png','2025-04-28 21:46:31','2025-04-28 21:46:31');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'home_find_real_estate'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-29 21:44:28
