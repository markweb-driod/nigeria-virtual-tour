-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: tour_nigeria
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `entity_type` varchar(255) DEFAULT NULL,
  `entity_id` bigint(20) unsigned DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attractions`
--

DROP TABLE IF EXISTS `attractions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attractions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `state_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `history` longtext DEFAULT NULL,
  `interesting_facts` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `lga` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `audio` varchar(255) DEFAULT NULL,
  `visiting_hours` varchar(255) DEFAULT NULL,
  `entry_fee` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `tour_available` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('published','draft','archived') NOT NULL DEFAULT 'draft',
  `view_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attractions_slug_unique` (`slug`),
  KEY `attractions_category_id_foreign` (`category_id`),
  KEY `attractions_state_id_foreign` (`state_id`),
  CONSTRAINT `attractions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attractions_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attractions`
--

LOCK TABLES `attractions` WRITE;
/*!40000 ALTER TABLE `attractions` DISABLE KEYS */;
INSERT INTO `attractions` VALUES (1,3,1,'Olumo Rock','olumo-rock','A monumental fortress and sacred rock sanctuary standing at 137 metres above sea level in Abeokuta, Ogun State.','Olumo Rock is one of Nigeria\'s most celebrated historical monuments and cultural heritage treasures. Located in the ancient city of Abeokuta (which literally translates to \'Under the Rock\'), this massive outcrop of granite served as a natural citadel and refuge for the Egba people during the inter-tribal Yoruba civil wars of the 19th century.\n\nVisitors today can ascend the rock via ancient carved stone stairs or modern elevators, discovering hidden caves, historic shrines, centuries-old shrines tended by elder priestesses, and breathtaking 360° panoramic vistas across the Ogun River and the sprawling rusty iron rooftops of ancient Abeokuta.','During the 19th-century Yoruba civil wars, the displaced Egba refugees found an impenetrable natural defensive fortress beneath the mammoth overhangs of Olumo Rock in approximately 1830. Led by the celebrated hunter and warrior Sodeke, the community established Abeokuta around the rock.\n\nThe rock provided strategic vantage points to spot advancing Dahomey and rival warriors miles away, while deep cavernous crevices offered safe living quarters, freshwater catchment, and medicinal herb reserves. The name \'Olumo\' derives from \'Oluwa fimo\' — meaning \'God has put an end to our wanderings\'.','• The rock reaches an elevation of 137 metres above sea level.\n• The ancient Egba dwellers carved holes into the stone floor used for pounding yams and grain that remain visible today.\n• The sacred Igi Odan tree on the rock summit has lived for centuries and is revered for producing fresh leaves year-round without withering.\n• An ancient priestess over 130 years old resided inside the rock caves for decades tending the royal shrine.','Ikija Road, Abeokuta','Abeokuta North',7.15570000,3.34530000,'attractions/thumbs/olumo-thumb.jpg','attractions/heroes/olumo-hero.jpg',NULL,'8:00 AM - 6:00 PM Daily','₦1,000 (Adults), ₦500 (Children)',1,1,'published',5422,'2026-09-27 11:07:41','2026-11-12 11:21:46'),(2,1,2,'Zuma Rock','zuma-rock','A colossal natural monolith standing 725 metres above its surroundings, famously known as the Gateway to Abuja with a natural human face profile.','Zuma Rock is one of Nigeria\'s most striking geographical wonders. Rising majestically to 725 metres (2,379 ft) above the surrounding plateau just west of Nigeria\'s capital city Abuja, this gigantic natural gabbro monolith has captivated explorers and indigenous populations for centuries.\n\nThe most fascinating feature of Zuma Rock is its natural human-like face contour carved by wind and rain into the sheer rock face, featuring clear depictions of eyes, nose, and mouth. Depicted on the Nigerian 100-Naira banknote, it remains a symbol of national heritage.','Historically, Zuma Rock was used as an impenetrable defensive fortress by the Gbagyi (Gwari) people during inter-tribal warfare. In indigenous folklore, Zuma Rock is venerated as a spiritual seat and protective entity.','• Stands approximately 725 metres above the surrounding landscape and over 1,100 metres above sea level.\n• Appears on Nigeria\'s 100-Naira currency note.\n• During heavy rainy seasons, the top of the monolith occasionally catches fire in folklore sightings caused by friction from falling boulders.','Madalla, along Abuja-Kaduna Expressway','Suleja',9.12870000,7.23430000,'attractions/thumbs/zuma-thumb.jpg','attractions/heroes/zuma-hero.jpg',NULL,'Always visible / 8:00 AM - 6:00 PM for photography visits','Free viewing / Guided tours ₦1,500',1,1,'published',4890,'2026-09-27 11:07:42','2026-09-27 19:13:57'),(3,4,3,'Yankari Game Reserve','yankari-game-reserve','Nigeria\'s premier wildlife national park, home to the crystal-clear Wikki Warm Springs, elephants, lions, and ancient Marshall Caves.','Yankari Game Reserve is the premier wildlife conservation park in West Africa, spanning approximately 2,244 square kilometres in south-central Bauchi State. Established originally as a game reserve in 1956 and later converted into a national park in 1991, Yankari is renowned for hosting the largest surviving herd of African elephants in Nigeria, as well as roan antelopes, olive baboons, and diverse bird species.\n\nIts crowning jewel is the world-famous Wikki Warm Spring, a crystal-clear thermal natural spring maintaining a constant, soothing 31.1°C temperature 24 hours a day, alongside the subterranean archaeological marvel known as the Marshall Caves.','Yankari was first conceived by Alhaji Muhammadu Ngeleruma in 1953 after visiting Sudanese game preserves, opening to the public on December 1, 1962. It represents Nigeria\'s foremost eco-tourism and scientific research habitat.','• Wikki Warm Springs discharges 21,000 litres of pure, crystalline warm water per minute at a constant 31.1°C.\n• Contains 59 Marshall Caves dug into sandstone cliffs that early humans inhabited centuries ago.\n• Hosts over 350 species of resident and migratory birds.','Mainamaji Village, Alkaleri LGA, Bauchi State','Alkaleri',9.75420000,10.51260000,'attractions/thumbs/yankari-thumb.jpg','attractions/heroes/yankari-hero.jpg',NULL,'Safari drives 7:00 AM & 3:30 PM / Warm spring open 24/7 for guests','₦2,500 (Adults), ₦1,000 (Students)',1,1,'published',3750,'2026-09-27 11:07:43','2026-09-27 19:13:57'),(4,4,12,'Obudu Mountain Resort','obudu-mountain-resort','A breathtaking highland paradise nestled at 1,576 metres in the clouds of Cross River State, featuring one of the longest cable cars in the world.','Obudu Mountain Resort (formerly known as the Obudu Cattle Ranch) is nestled in the Sankwala Mountains of Cross River State. Perched at an altitude of approximately 1,576 metres (5,170 ft) above sea level, the climate is temperate and cool, frequently wrapped in gentle alpine mist and clouds.\n\nThe resort features the remarkable 4-kilometre Obudu Cable Car, a spectacular canopy walkway suspended above ancient montane rainforest canopy, the Holy Mountain viewpoint, natural water grottoes, and rolling highland vistas reminiscent of European alpine landscapes.',NULL,'• Located at 1,576 metres elevation above sea level.\n• Home to one of the longest cable car systems in the world (4 km).\n• Features an alpine climate where temperatures frequently drop to 15°C.','Obanliku Local Government Area, Cross River State','Obanliku',6.38330000,9.38330000,'attractions/thumbs/obudu-thumb.jpg','attractions/heroes/obudu-hero.jpg',NULL,'Open 24 hours daily for resort guests and sightseers','₦1,000 entrance / Cable car ₦4,000 return',1,1,'published',3210,'2026-09-27 11:07:43','2026-09-27 19:13:57'),(5,1,4,'Lekki Conservation Centre','lekki-conservation-centre','A pristine 78-hectare coastal wetland and rainforest reserve in Lagos, boasting the longest canopy walkway in Africa.','Lekki Conservation Centre (LCC) is a 78-hectare natural reserve located on the Lekki Peninsula in Lagos. Founded in 1990 by the Nigerian Conservation Foundation (NCF), it preserves a vital tract of coastal mangrove swamps, savannah grassland, and pristine secondary rainforest amidst the rapid urbanization of modern Lagos.\n\nThe centerpiece attraction is Africa\'s longest canopy walkway — a 401-metre suspended bridge that lets visitors stroll amongst tree crowns 22 feet above the forest floor alongside monkeys, monitor lizards, and exotic birdlife.',NULL,'• Hosts Africa\'s longest canopy walkway at 401 metres in length.\n• Home to a 95-year-old giant tortoise that roams the central family park.\n• Houses the largest wooden floor outdoor chess game in West Africa.','Km 19 Lekki-Epe Expressway, Lekki, Lagos','Eti-Osa',6.43890000,3.53580000,'attractions/thumbs/lekki-thumb.jpg','attractions/heroes/lekki-hero.jpg',NULL,'8:30 AM - 5:00 PM Daily','₦1,500 (Entry) + ₦1,500 (Canopy Walk)',1,1,'published',4120,'2026-09-27 11:07:43','2026-09-27 19:13:57');
/*!40000 ALTER TABLE `attractions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#0B3D2E',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Nature','nature','🌿','#22c55e',NULL,'2026-09-27 09:31:23','2026-09-27 09:31:23'),(2,'Heritage','heritage','🏛️','#D4A017',NULL,'2026-09-27 09:31:24','2026-09-27 09:31:24'),(3,'Historical','historical','📜','#8B4513',NULL,'2026-09-27 09:31:24','2026-09-27 09:31:24'),(4,'Parks & Resorts','parks-resorts','🏕️','#0B3D2E',NULL,'2026-09-27 09:31:24','2026-09-27 09:31:24'),(5,'Cultural','cultural','🎭','#7c3aed',NULL,'2026-09-27 09:31:24','2026-09-27 09:31:24'),(6,'Recreational','recreational','🌊','#0ea5e9',NULL,'2026-09-27 09:31:24','2026-09-27 09:31:24');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotspots`
--

DROP TABLE IF EXISTS `hotspots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotspots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `scene_id` bigint(20) unsigned NOT NULL,
  `type` enum('scene','info','media') NOT NULL,
  `yaw` double NOT NULL,
  `pitch` double NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `target_scene_id` bigint(20) unsigned DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `media_type` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotspots_scene_id_foreign` (`scene_id`),
  KEY `hotspots_target_scene_id_foreign` (`target_scene_id`),
  CONSTRAINT `hotspots_scene_id_foreign` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hotspots_target_scene_id_foreign` FOREIGN KEY (`target_scene_id`) REFERENCES `scenes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotspots`
--

LOCK TABLES `hotspots` WRITE;
/*!40000 ALTER TABLE `hotspots` DISABLE KEYS */;
INSERT INTO `hotspots` VALUES (1,1,'scene',22.5,-4,'Climb to Lower Platform →',NULL,2,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(2,1,'info',-45,8,'History of Olumo Rock Entrance','The entrance complex was modernised with heritage stone archways while preserving the original footpaths trodden since 1830.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(3,2,'scene',55,6,'Continue to Cave Entrance →',NULL,3,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(4,2,'scene',-160,-12,'← Return to Main Plaza',NULL,1,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(5,2,'info',110,2,'Ancient Defense Outpost','Lookout sentries stood on this granite shelf to detect smoke signals from Dahomey skirmish parties.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(6,3,'scene',85,-2,'Enter Cave Interior →',NULL,4,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(7,3,'scene',15,18,'Ascend to Summit Peak →',NULL,5,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(8,3,'scene',-120,-8,'← Down to Lower Platform',NULL,2,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(9,4,'scene',-90,4,'← Exit to Cave Entrance',NULL,3,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(10,4,'info',30,-15,'Ancient Grain Grinding Mortars','Notice the circular depressions smoothed into the solid rock bed. Egba women pounded grains here during extended wartime sieges.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(11,5,'scene',175,-20,'Descend to Cave Entrance →',NULL,3,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(12,5,'info',-25,-8,'Sacred Igi Odan Tree','Legend states this tree has withstood all seasons without ever shedding its foliage, serving as an emblem of resilience for Abeokuta.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(13,5,'info',90,-5,'View of Ogun River & Abeokuta City','From 137m high, you can see the meandering Ogun River and the distinctive rust-brown corrugated rooftops of historic Egba quarters.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(14,6,'scene',15,8,'Move Closer to Rock Base →',NULL,7,NULL,NULL,NULL,0,'2026-09-27 11:07:42','2026-09-27 11:07:42'),(15,6,'info',-30,18,'Natural Human Face Silhouette','Observe the upper central face of the monolith where weather patterns carved distinct facial features.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:43','2026-09-27 11:07:43'),(16,7,'scene',-160,-8,'← Back to Wide Perspective',NULL,6,NULL,NULL,NULL,0,'2026-09-27 11:07:43','2026-09-27 11:07:43'),(17,9,'info',45,-12,'Constant 31.1°C Mineral Water','The warm spring bubbles continuously out of a limestone fissure, providing warm crystal-clear water year-round.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:43','2026-09-27 11:07:43'),(18,9,'scene',170,-4,'Head to Savannah Safari Concourse →',NULL,10,NULL,NULL,NULL,0,'2026-09-27 11:07:43','2026-09-27 11:07:43'),(19,11,'info',45,0,'4km Highland Cable Car Span','The cable car carries visitors from the tropical bottom of the valley straight into the alpine plateau clouds in 11 scenic minutes.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:43','2026-09-27 11:07:43'),(20,12,'info',30,-10,'Longest Canopy Walkway in Africa','Engineered with suspended cable bridges traversing 401 metres across the upper canopy of the swamp forest.',NULL,NULL,NULL,NULL,0,'2026-09-27 11:07:43','2026-09-27 11:07:43');
/*!40000 ALTER TABLE `hotspots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_items`
--

DROP TABLE IF EXISTS `media_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attraction_id` bigint(20) unsigned DEFAULT NULL,
  `scene_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('photo','video','audio','document') NOT NULL,
  `path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_items_attraction_id_foreign` (`attraction_id`),
  KEY `media_items_scene_id_foreign` (`scene_id`),
  CONSTRAINT `media_items_attraction_id_foreign` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `media_items_scene_id_foreign` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_items`
--

LOCK TABLES `media_items` WRITE;
/*!40000 ALTER TABLE `media_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_create_states_table',1),(5,'2024_01_01_000002_create_categories_table',1),(6,'2024_01_01_000003_create_attractions_table',1),(7,'2024_01_01_000004_create_scenes_table',1),(8,'2024_01_01_000005_create_hotspots_table',1),(9,'2024_01_01_000006_create_media_table',1),(10,'2024_01_01_000007_create_tour_sessions_table',1),(11,'2024_01_01_000008_create_scene_views_table',1),(12,'2024_01_01_000009_create_activity_logs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scene_views`
--

DROP TABLE IF EXISTS `scene_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scene_views` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tour_session_id` bigint(20) unsigned NOT NULL,
  `scene_id` bigint(20) unsigned NOT NULL,
  `viewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scene_views_tour_session_id_foreign` (`tour_session_id`),
  KEY `scene_views_scene_id_foreign` (`scene_id`),
  CONSTRAINT `scene_views_scene_id_foreign` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `scene_views_tour_session_id_foreign` FOREIGN KEY (`tour_session_id`) REFERENCES `tour_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=323 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scene_views`
--

LOCK TABLES `scene_views` WRITE;
/*!40000 ALTER TABLE `scene_views` DISABLE KEYS */;
INSERT INTO `scene_views` VALUES (1,31,4,'2026-09-21 10:44:08','2026-09-27 19:13:58','2026-09-27 19:13:58'),(2,31,1,'2026-09-21 10:44:23','2026-09-27 19:13:58','2026-09-27 19:13:58'),(3,31,2,'2026-09-21 10:44:38','2026-09-27 19:13:58','2026-09-27 19:13:58'),(4,31,5,'2026-09-21 10:44:53','2026-09-27 19:13:58','2026-09-27 19:13:58'),(5,32,4,'2026-09-17 03:33:15','2026-09-27 19:13:58','2026-09-27 19:13:58'),(6,32,3,'2026-09-17 03:33:30','2026-09-27 19:13:58','2026-09-27 19:13:58'),(7,33,2,'2026-09-20 18:11:54','2026-09-27 19:13:58','2026-09-27 19:13:58'),(8,34,3,'2026-09-15 13:01:13','2026-09-27 19:13:58','2026-09-27 19:13:58'),(9,34,2,'2026-09-15 13:01:28','2026-09-27 19:13:58','2026-09-27 19:13:58'),(10,34,5,'2026-09-15 13:01:43','2026-09-27 19:13:58','2026-09-27 19:13:58'),(11,34,1,'2026-09-15 13:01:58','2026-09-27 19:13:58','2026-09-27 19:13:58'),(12,35,1,'2026-09-15 20:56:30','2026-09-27 19:13:58','2026-09-27 19:13:58'),(13,35,4,'2026-09-15 20:56:45','2026-09-27 19:13:58','2026-09-27 19:13:58'),(14,36,4,'2026-09-14 06:07:54','2026-09-27 19:13:58','2026-09-27 19:13:58'),(15,36,5,'2026-09-14 06:08:09','2026-09-27 19:13:58','2026-09-27 19:13:58'),(16,36,3,'2026-09-14 06:08:24','2026-09-27 19:13:58','2026-09-27 19:13:58'),(17,37,1,'2026-09-14 13:55:29','2026-09-27 19:13:58','2026-09-27 19:13:58'),(18,38,4,'2026-09-16 00:46:35','2026-09-27 19:13:58','2026-09-27 19:13:58'),(19,38,2,'2026-09-16 00:46:50','2026-09-27 19:13:58','2026-09-27 19:13:58'),(20,39,1,'2026-09-14 05:09:03','2026-09-27 19:13:58','2026-09-27 19:13:58'),(21,39,2,'2026-09-14 05:09:18','2026-09-27 19:13:58','2026-09-27 19:13:58'),(22,39,3,'2026-09-14 05:09:33','2026-09-27 19:13:58','2026-09-27 19:13:58'),(23,40,2,'2026-09-18 14:15:48','2026-09-27 19:13:58','2026-09-27 19:13:58'),(24,40,1,'2026-09-18 14:16:03','2026-09-27 19:13:58','2026-09-27 19:13:58'),(25,40,3,'2026-09-18 14:16:18','2026-09-27 19:13:58','2026-09-27 19:13:58'),(26,40,5,'2026-09-18 14:16:33','2026-09-27 19:13:58','2026-09-27 19:13:58'),(27,41,4,'2026-09-26 23:39:02','2026-09-27 19:13:58','2026-09-27 19:13:58'),(28,41,1,'2026-09-26 23:39:17','2026-09-27 19:13:58','2026-09-27 19:13:58'),(29,41,3,'2026-09-26 23:39:32','2026-09-27 19:13:58','2026-09-27 19:13:58'),(30,41,5,'2026-09-26 23:39:47','2026-09-27 19:13:59','2026-09-27 19:13:59'),(31,41,2,'2026-09-26 23:40:02','2026-09-27 19:13:59','2026-09-27 19:13:59'),(32,42,4,'2026-09-15 10:21:48','2026-09-27 19:13:59','2026-09-27 19:13:59'),(33,43,1,'2026-09-20 08:30:54','2026-09-27 19:13:59','2026-09-27 19:13:59'),(34,44,2,'2026-09-16 07:54:21','2026-09-27 19:13:59','2026-09-27 19:13:59'),(35,44,5,'2026-09-16 07:54:36','2026-09-27 19:13:59','2026-09-27 19:13:59'),(36,44,1,'2026-09-16 07:54:51','2026-09-27 19:13:59','2026-09-27 19:13:59'),(37,44,3,'2026-09-16 07:55:06','2026-09-27 19:13:59','2026-09-27 19:13:59'),(38,45,5,'2026-09-26 17:29:35','2026-09-27 19:13:59','2026-09-27 19:13:59'),(39,45,4,'2026-09-26 17:29:50','2026-09-27 19:13:59','2026-09-27 19:13:59'),(40,45,3,'2026-09-26 17:30:05','2026-09-27 19:13:59','2026-09-27 19:13:59'),(41,45,2,'2026-09-26 17:30:20','2026-09-27 19:13:59','2026-09-27 19:13:59'),(42,45,1,'2026-09-26 17:30:35','2026-09-27 19:13:59','2026-09-27 19:13:59'),(43,46,5,'2026-09-19 00:42:51','2026-09-27 19:13:59','2026-09-27 19:13:59'),(44,46,3,'2026-09-19 00:43:06','2026-09-27 19:13:59','2026-09-27 19:13:59'),(45,47,4,'2026-09-19 09:50:03','2026-09-27 19:13:59','2026-09-27 19:13:59'),(46,47,1,'2026-09-19 09:50:18','2026-09-27 19:13:59','2026-09-27 19:13:59'),(47,48,4,'2026-09-19 22:45:38','2026-09-27 19:13:59','2026-09-27 19:13:59'),(48,48,1,'2026-09-19 22:45:53','2026-09-27 19:13:59','2026-09-27 19:13:59'),(49,48,2,'2026-09-19 22:46:08','2026-09-27 19:13:59','2026-09-27 19:13:59'),(50,48,3,'2026-09-19 22:46:23','2026-09-27 19:13:59','2026-09-27 19:13:59'),(51,48,5,'2026-09-19 22:46:38','2026-09-27 19:13:59','2026-09-27 19:13:59'),(52,49,1,'2026-09-22 07:20:57','2026-09-27 19:13:59','2026-09-27 19:13:59'),(53,49,2,'2026-09-22 07:21:12','2026-09-27 19:13:59','2026-09-27 19:13:59'),(54,49,3,'2026-09-22 07:21:27','2026-09-27 19:13:59','2026-09-27 19:13:59'),(55,49,5,'2026-09-22 07:21:42','2026-09-27 19:13:59','2026-09-27 19:13:59'),(56,50,5,'2026-09-25 04:16:40','2026-09-27 19:13:59','2026-09-27 19:13:59'),(57,50,1,'2026-09-25 04:16:55','2026-09-27 19:13:59','2026-09-27 19:13:59'),(58,50,2,'2026-09-25 04:17:10','2026-09-27 19:13:59','2026-09-27 19:13:59'),(59,50,3,'2026-09-25 04:17:25','2026-09-27 19:13:59','2026-09-27 19:13:59'),(60,51,5,'2026-09-21 16:13:51','2026-09-27 19:13:59','2026-09-27 19:13:59'),(61,52,4,'2026-09-26 14:19:38','2026-09-27 19:13:59','2026-09-27 19:13:59'),(62,52,3,'2026-09-26 14:19:53','2026-09-27 19:13:59','2026-09-27 19:13:59'),(63,52,2,'2026-09-26 14:20:08','2026-09-27 19:13:59','2026-09-27 19:13:59'),(64,52,5,'2026-09-26 14:20:23','2026-09-27 19:13:59','2026-09-27 19:13:59'),(65,52,1,'2026-09-26 14:20:38','2026-09-27 19:13:59','2026-09-27 19:13:59'),(66,53,5,'2026-09-17 06:41:02','2026-09-27 19:13:59','2026-09-27 19:13:59'),(67,53,2,'2026-09-17 06:41:17','2026-09-27 19:13:59','2026-09-27 19:13:59'),(68,53,4,'2026-09-17 06:41:32','2026-09-27 19:13:59','2026-09-27 19:13:59'),(69,53,3,'2026-09-17 06:41:47','2026-09-27 19:13:59','2026-09-27 19:13:59'),(70,54,4,'2026-09-22 10:10:11','2026-09-27 19:13:59','2026-09-27 19:13:59'),(71,54,1,'2026-09-22 10:10:26','2026-09-27 19:13:59','2026-09-27 19:13:59'),(72,54,5,'2026-09-22 10:10:41','2026-09-27 19:13:59','2026-09-27 19:13:59'),(73,55,2,'2026-09-20 01:21:03','2026-09-27 19:13:59','2026-09-27 19:13:59'),(74,55,4,'2026-09-20 01:21:18','2026-09-27 19:13:59','2026-09-27 19:13:59'),(75,55,5,'2026-09-20 01:21:33','2026-09-27 19:13:59','2026-09-27 19:13:59'),(76,56,4,'2026-09-19 09:28:01','2026-09-27 19:13:59','2026-09-27 19:13:59'),(77,56,3,'2026-09-19 09:28:16','2026-09-27 19:13:59','2026-09-27 19:13:59'),(78,56,2,'2026-09-19 09:28:31','2026-09-27 19:13:59','2026-09-27 19:13:59'),(79,56,5,'2026-09-19 09:28:46','2026-09-27 19:13:59','2026-09-27 19:13:59'),(80,57,2,'2026-09-25 09:07:55','2026-09-27 19:13:59','2026-09-27 19:13:59'),(81,57,1,'2026-09-25 09:08:10','2026-09-27 19:13:59','2026-09-27 19:13:59'),(82,57,4,'2026-09-25 09:08:25','2026-09-27 19:13:59','2026-09-27 19:13:59'),(83,58,1,'2026-09-16 22:52:26','2026-09-27 19:14:00','2026-09-27 19:14:00'),(84,58,2,'2026-09-16 22:52:41','2026-09-27 19:14:00','2026-09-27 19:14:00'),(85,59,3,'2026-09-15 07:43:38','2026-09-27 19:14:00','2026-09-27 19:14:00'),(86,59,4,'2026-09-15 07:43:53','2026-09-27 19:14:00','2026-09-27 19:14:00'),(87,60,4,'2026-09-14 20:10:47','2026-09-27 19:14:00','2026-09-27 19:14:00'),(88,61,1,'2026-09-17 19:40:54','2026-09-27 19:14:00','2026-09-27 19:14:00'),(89,61,4,'2026-09-17 19:41:09','2026-09-27 19:14:00','2026-09-27 19:14:00'),(90,61,3,'2026-09-17 19:41:24','2026-09-27 19:14:00','2026-09-27 19:14:00'),(91,61,5,'2026-09-17 19:41:39','2026-09-27 19:14:00','2026-09-27 19:14:00'),(92,61,2,'2026-09-17 19:41:54','2026-09-27 19:14:00','2026-09-27 19:14:00'),(93,62,5,'2026-09-18 05:28:36','2026-09-27 19:14:00','2026-09-27 19:14:00'),(94,62,4,'2026-09-18 05:28:51','2026-09-27 19:14:00','2026-09-27 19:14:00'),(95,63,5,'2026-09-24 02:47:57','2026-09-27 19:14:00','2026-09-27 19:14:00'),(96,63,2,'2026-09-24 02:48:12','2026-09-27 19:14:00','2026-09-27 19:14:00'),(97,63,1,'2026-09-24 02:48:27','2026-09-27 19:14:00','2026-09-27 19:14:00'),(98,64,1,'2026-09-21 14:48:54','2026-09-27 19:14:00','2026-09-27 19:14:00'),(99,64,3,'2026-09-21 14:49:09','2026-09-27 19:14:00','2026-09-27 19:14:00'),(100,65,1,'2026-09-22 07:39:58','2026-09-27 19:14:00','2026-09-27 19:14:00'),(101,65,3,'2026-09-22 07:40:13','2026-09-27 19:14:00','2026-09-27 19:14:00'),(102,65,2,'2026-09-22 07:40:28','2026-09-27 19:14:00','2026-09-27 19:14:00'),(103,65,4,'2026-09-22 07:40:43','2026-09-27 19:14:00','2026-09-27 19:14:00'),(104,66,6,'2026-09-17 07:29:36','2026-09-27 19:14:00','2026-09-27 19:14:00'),(105,66,7,'2026-09-17 07:29:51','2026-09-27 19:14:00','2026-09-27 19:14:00'),(106,67,7,'2026-09-18 09:57:45','2026-09-27 19:14:00','2026-09-27 19:14:00'),(107,67,8,'2026-09-18 09:58:00','2026-09-27 19:14:00','2026-09-27 19:14:00'),(108,67,6,'2026-09-18 09:58:15','2026-09-27 19:14:00','2026-09-27 19:14:00'),(109,68,6,'2026-09-21 13:37:59','2026-09-27 19:14:00','2026-09-27 19:14:00'),(110,68,7,'2026-09-21 13:38:14','2026-09-27 19:14:00','2026-09-27 19:14:00'),(111,69,6,'2026-09-19 22:14:07','2026-09-27 19:14:00','2026-09-27 19:14:00'),(112,70,8,'2026-09-22 04:21:46','2026-09-27 19:14:00','2026-09-27 19:14:00'),(113,70,7,'2026-09-22 04:22:01','2026-09-27 19:14:00','2026-09-27 19:14:00'),(114,70,6,'2026-09-22 04:22:16','2026-09-27 19:14:00','2026-09-27 19:14:00'),(115,71,8,'2026-09-27 10:58:01','2026-09-27 19:14:00','2026-09-27 19:14:00'),(116,71,7,'2026-09-27 10:58:16','2026-09-27 19:14:00','2026-09-27 19:14:00'),(117,71,6,'2026-09-27 10:58:31','2026-09-27 19:14:00','2026-09-27 19:14:00'),(118,72,6,'2026-09-24 11:46:43','2026-09-27 19:14:00','2026-09-27 19:14:00'),(119,73,6,'2026-09-15 07:52:58','2026-09-27 19:14:00','2026-09-27 19:14:00'),(120,73,7,'2026-09-15 07:53:13','2026-09-27 19:14:00','2026-09-27 19:14:00'),(121,73,8,'2026-09-15 07:53:28','2026-09-27 19:14:00','2026-09-27 19:14:00'),(122,74,6,'2026-09-14 04:05:49','2026-09-27 19:14:00','2026-09-27 19:14:00'),(123,74,8,'2026-09-14 04:06:04','2026-09-27 19:14:00','2026-09-27 19:14:00'),(124,75,6,'2026-09-25 01:59:09','2026-09-27 19:14:00','2026-09-27 19:14:00'),(125,76,6,'2026-09-21 21:57:19','2026-09-27 19:14:00','2026-09-27 19:14:00'),(126,77,7,'2026-09-23 23:10:16','2026-09-27 19:14:00','2026-09-27 19:14:00'),(127,77,6,'2026-09-23 23:10:31','2026-09-27 19:14:01','2026-09-27 19:14:01'),(128,77,8,'2026-09-23 23:10:46','2026-09-27 19:14:01','2026-09-27 19:14:01'),(129,78,8,'2026-09-18 20:50:00','2026-09-27 19:14:01','2026-09-27 19:14:01'),(130,79,7,'2026-09-15 03:42:43','2026-09-27 19:14:01','2026-09-27 19:14:01'),(131,80,6,'2026-09-26 02:48:59','2026-09-27 19:14:01','2026-09-27 19:14:01'),(132,80,8,'2026-09-26 02:49:14','2026-09-27 19:14:01','2026-09-27 19:14:01'),(133,80,7,'2026-09-26 02:49:29','2026-09-27 19:14:01','2026-09-27 19:14:01'),(134,81,6,'2026-09-21 07:52:14','2026-09-27 19:14:01','2026-09-27 19:14:01'),(135,81,7,'2026-09-21 07:52:29','2026-09-27 19:14:01','2026-09-27 19:14:01'),(136,82,6,'2026-09-21 14:10:19','2026-09-27 19:14:01','2026-09-27 19:14:01'),(137,82,7,'2026-09-21 14:10:34','2026-09-27 19:14:01','2026-09-27 19:14:01'),(138,83,8,'2026-09-14 13:34:50','2026-09-27 19:14:01','2026-09-27 19:14:01'),(139,83,7,'2026-09-14 13:35:05','2026-09-27 19:14:01','2026-09-27 19:14:01'),(140,84,8,'2026-09-18 12:34:36','2026-09-27 19:14:01','2026-09-27 19:14:01'),(141,84,7,'2026-09-18 12:34:51','2026-09-27 19:14:01','2026-09-27 19:14:01'),(142,85,7,'2026-09-17 23:12:00','2026-09-27 19:14:01','2026-09-27 19:14:01'),(143,85,6,'2026-09-17 23:12:15','2026-09-27 19:14:01','2026-09-27 19:14:01'),(144,86,7,'2026-09-17 20:43:59','2026-09-27 19:14:01','2026-09-27 19:14:01'),(145,86,8,'2026-09-17 20:44:14','2026-09-27 19:14:01','2026-09-27 19:14:01'),(146,86,6,'2026-09-17 20:44:29','2026-09-27 19:14:01','2026-09-27 19:14:01'),(147,87,6,'2026-09-24 10:50:13','2026-09-27 19:14:01','2026-09-27 19:14:01'),(148,87,7,'2026-09-24 10:50:28','2026-09-27 19:14:01','2026-09-27 19:14:01'),(149,87,8,'2026-09-24 10:50:43','2026-09-27 19:14:01','2026-09-27 19:14:01'),(150,88,6,'2026-09-25 19:11:37','2026-09-27 19:14:01','2026-09-27 19:14:01'),(151,89,8,'2026-09-23 06:40:39','2026-09-27 19:14:01','2026-09-27 19:14:01'),(152,89,7,'2026-09-23 06:40:54','2026-09-27 19:14:01','2026-09-27 19:14:01'),(153,90,8,'2026-09-25 16:50:56','2026-09-27 19:14:01','2026-09-27 19:14:01'),(154,90,7,'2026-09-25 16:51:11','2026-09-27 19:14:01','2026-09-27 19:14:01'),(155,91,6,'2026-09-16 02:09:19','2026-09-27 19:14:01','2026-09-27 19:14:01'),(156,92,6,'2026-09-24 22:56:13','2026-09-27 19:14:01','2026-09-27 19:14:01'),(157,93,7,'2026-09-21 16:42:23','2026-09-27 19:14:01','2026-09-27 19:14:01'),(158,93,6,'2026-09-21 16:42:38','2026-09-27 19:14:01','2026-09-27 19:14:01'),(159,94,8,'2026-09-20 00:17:37','2026-09-27 19:14:01','2026-09-27 19:14:01'),(160,94,7,'2026-09-20 00:17:52','2026-09-27 19:14:01','2026-09-27 19:14:01'),(161,94,6,'2026-09-20 00:18:07','2026-09-27 19:14:01','2026-09-27 19:14:01'),(162,95,8,'2026-09-23 03:23:49','2026-09-27 19:14:01','2026-09-27 19:14:01'),(163,95,7,'2026-09-23 03:24:04','2026-09-27 19:14:01','2026-09-27 19:14:01'),(164,96,6,'2026-09-15 15:01:42','2026-09-27 19:14:01','2026-09-27 19:14:01'),(165,97,6,'2026-09-21 15:18:58','2026-09-27 19:14:01','2026-09-27 19:14:01'),(166,97,7,'2026-09-21 15:19:13','2026-09-27 19:14:01','2026-09-27 19:14:01'),(167,97,8,'2026-09-21 15:19:28','2026-09-27 19:14:01','2026-09-27 19:14:01'),(168,98,8,'2026-09-14 21:58:36','2026-09-27 19:14:01','2026-09-27 19:14:01'),(169,99,6,'2026-09-18 16:55:30','2026-09-27 19:14:01','2026-09-27 19:14:01'),(170,99,7,'2026-09-18 16:55:45','2026-09-27 19:14:01','2026-09-27 19:14:01'),(171,100,6,'2026-09-19 02:44:54','2026-09-27 19:14:01','2026-09-27 19:14:01'),(172,101,7,'2026-09-26 20:11:50','2026-09-27 19:14:01','2026-09-27 19:14:01'),(173,102,8,'2026-09-23 01:35:03','2026-09-27 19:14:01','2026-09-27 19:14:01'),(174,102,7,'2026-09-23 01:35:18','2026-09-27 19:14:01','2026-09-27 19:14:01'),(175,103,6,'2026-09-22 01:52:31','2026-09-27 19:14:01','2026-09-27 19:14:01'),(176,104,7,'2026-09-14 10:40:31','2026-09-27 19:14:01','2026-09-27 19:14:01'),(177,105,6,'2026-09-26 00:20:41','2026-09-27 19:14:01','2026-09-27 19:14:01'),(178,105,7,'2026-09-26 00:20:56','2026-09-27 19:14:01','2026-09-27 19:14:01'),(179,105,8,'2026-09-26 00:21:11','2026-09-27 19:14:02','2026-09-27 19:14:02'),(180,106,8,'2026-09-22 12:41:15','2026-09-27 19:14:02','2026-09-27 19:14:02'),(181,107,10,'2026-09-17 12:24:54','2026-09-27 19:14:02','2026-09-27 19:14:02'),(182,108,10,'2026-09-14 00:05:20','2026-09-27 19:14:02','2026-09-27 19:14:02'),(183,109,10,'2026-09-17 05:23:38','2026-09-27 19:14:02','2026-09-27 19:14:02'),(184,110,9,'2026-09-17 12:03:05','2026-09-27 19:14:02','2026-09-27 19:14:02'),(185,111,9,'2026-09-18 15:06:50','2026-09-27 19:14:02','2026-09-27 19:14:02'),(186,111,10,'2026-09-18 15:07:05','2026-09-27 19:14:02','2026-09-27 19:14:02'),(187,112,10,'2026-09-16 01:56:30','2026-09-27 19:14:02','2026-09-27 19:14:02'),(188,112,9,'2026-09-16 01:56:45','2026-09-27 19:14:02','2026-09-27 19:14:02'),(189,113,9,'2026-09-27 13:53:25','2026-09-27 19:14:02','2026-09-27 19:14:02'),(190,114,10,'2026-09-15 22:00:04','2026-09-27 19:14:02','2026-09-27 19:14:02'),(191,114,9,'2026-09-15 22:00:19','2026-09-27 19:14:02','2026-09-27 19:14:02'),(192,115,9,'2026-09-21 16:27:11','2026-09-27 19:14:02','2026-09-27 19:14:02'),(193,115,10,'2026-09-21 16:27:26','2026-09-27 19:14:02','2026-09-27 19:14:02'),(194,116,9,'2026-09-14 06:00:52','2026-09-27 19:14:02','2026-09-27 19:14:02'),(195,116,10,'2026-09-14 06:01:07','2026-09-27 19:14:02','2026-09-27 19:14:02'),(196,117,10,'2026-09-18 08:36:14','2026-09-27 19:14:02','2026-09-27 19:14:02'),(197,117,9,'2026-09-18 08:36:29','2026-09-27 19:14:02','2026-09-27 19:14:02'),(198,118,9,'2026-09-21 11:44:25','2026-09-27 19:14:02','2026-09-27 19:14:02'),(199,119,10,'2026-09-20 23:52:56','2026-09-27 19:14:02','2026-09-27 19:14:02'),(200,119,9,'2026-09-20 23:53:11','2026-09-27 19:14:02','2026-09-27 19:14:02'),(201,120,9,'2026-09-21 11:07:08','2026-09-27 19:14:02','2026-09-27 19:14:02'),(202,121,10,'2026-09-24 05:21:59','2026-09-27 19:14:02','2026-09-27 19:14:02'),(203,122,10,'2026-09-24 03:10:57','2026-09-27 19:14:02','2026-09-27 19:14:02'),(204,122,9,'2026-09-24 03:11:12','2026-09-27 19:14:02','2026-09-27 19:14:02'),(205,123,9,'2026-09-15 05:47:05','2026-09-27 19:14:02','2026-09-27 19:14:02'),(206,123,10,'2026-09-15 05:47:20','2026-09-27 19:14:02','2026-09-27 19:14:02'),(207,124,9,'2026-09-20 12:47:10','2026-09-27 19:14:02','2026-09-27 19:14:02'),(208,124,10,'2026-09-20 12:47:25','2026-09-27 19:14:02','2026-09-27 19:14:02'),(209,125,10,'2026-09-15 10:58:17','2026-09-27 19:14:02','2026-09-27 19:14:02'),(210,126,10,'2026-09-21 18:14:40','2026-09-27 19:14:02','2026-09-27 19:14:02'),(211,126,9,'2026-09-21 18:14:55','2026-09-27 19:14:02','2026-09-27 19:14:02'),(212,127,10,'2026-09-24 23:00:22','2026-09-27 19:14:02','2026-09-27 19:14:02'),(213,127,9,'2026-09-24 23:00:37','2026-09-27 19:14:02','2026-09-27 19:14:02'),(214,128,10,'2026-09-22 13:07:50','2026-09-27 19:14:02','2026-09-27 19:14:02'),(215,128,9,'2026-09-22 13:08:05','2026-09-27 19:14:02','2026-09-27 19:14:02'),(216,129,10,'2026-09-25 02:10:07','2026-09-27 19:14:02','2026-09-27 19:14:02'),(217,129,9,'2026-09-25 02:10:22','2026-09-27 19:14:02','2026-09-27 19:14:02'),(218,130,10,'2026-09-17 11:12:35','2026-09-27 19:14:02','2026-09-27 19:14:02'),(219,131,9,'2026-09-18 11:29:28','2026-09-27 19:14:02','2026-09-27 19:14:02'),(220,132,9,'2026-09-18 10:34:20','2026-09-27 19:14:02','2026-09-27 19:14:02'),(221,132,10,'2026-09-18 10:34:35','2026-09-27 19:14:02','2026-09-27 19:14:02'),(222,133,10,'2026-09-15 18:31:47','2026-09-27 19:14:02','2026-09-27 19:14:02'),(223,133,9,'2026-09-15 18:32:02','2026-09-27 19:14:02','2026-09-27 19:14:02'),(224,134,9,'2026-09-20 01:03:12','2026-09-27 19:14:02','2026-09-27 19:14:02'),(225,134,10,'2026-09-20 01:03:27','2026-09-27 19:14:02','2026-09-27 19:14:02'),(226,135,10,'2026-09-20 09:22:02','2026-09-27 19:14:02','2026-09-27 19:14:02'),(227,136,9,'2026-09-26 02:06:44','2026-09-27 19:14:03','2026-09-27 19:14:03'),(228,136,10,'2026-09-26 02:06:59','2026-09-27 19:14:03','2026-09-27 19:14:03'),(229,137,9,'2026-09-15 01:29:41','2026-09-27 19:14:03','2026-09-27 19:14:03'),(230,137,10,'2026-09-15 01:29:56','2026-09-27 19:14:03','2026-09-27 19:14:03'),(231,138,9,'2026-09-16 04:02:35','2026-09-27 19:14:03','2026-09-27 19:14:03'),(232,139,10,'2026-09-19 16:43:23','2026-09-27 19:14:03','2026-09-27 19:14:03'),(233,140,10,'2026-09-20 05:13:36','2026-09-27 19:14:03','2026-09-27 19:14:03'),(234,140,9,'2026-09-20 05:13:51','2026-09-27 19:14:03','2026-09-27 19:14:03'),(235,141,10,'2026-09-14 10:48:48','2026-09-27 19:14:03','2026-09-27 19:14:03'),(236,141,9,'2026-09-14 10:49:03','2026-09-27 19:14:03','2026-09-27 19:14:03'),(237,142,9,'2026-09-15 19:09:04','2026-09-27 19:14:03','2026-09-27 19:14:03'),(238,142,10,'2026-09-15 19:09:19','2026-09-27 19:14:03','2026-09-27 19:14:03'),(239,143,9,'2026-09-21 08:58:32','2026-09-27 19:14:03','2026-09-27 19:14:03'),(240,144,9,'2026-09-19 13:43:54','2026-09-27 19:14:03','2026-09-27 19:14:03'),(241,144,10,'2026-09-19 13:44:09','2026-09-27 19:14:03','2026-09-27 19:14:03'),(242,145,9,'2026-09-14 08:42:49','2026-09-27 19:14:03','2026-09-27 19:14:03'),(243,145,10,'2026-09-14 08:43:04','2026-09-27 19:14:03','2026-09-27 19:14:03'),(244,146,11,'2026-09-24 17:46:37','2026-09-27 19:14:03','2026-09-27 19:14:03'),(245,147,11,'2026-09-25 16:39:37','2026-09-27 19:14:03','2026-09-27 19:14:03'),(246,148,11,'2026-09-16 18:30:12','2026-09-27 19:14:03','2026-09-27 19:14:03'),(247,149,11,'2026-09-21 16:08:41','2026-09-27 19:14:03','2026-09-27 19:14:03'),(248,150,11,'2026-09-26 08:54:57','2026-09-27 19:14:03','2026-09-27 19:14:03'),(249,151,11,'2026-09-26 11:46:02','2026-09-27 19:14:03','2026-09-27 19:14:03'),(250,152,11,'2026-09-18 22:08:40','2026-09-27 19:14:03','2026-09-27 19:14:03'),(251,153,11,'2026-09-14 19:54:18','2026-09-27 19:14:03','2026-09-27 19:14:03'),(252,154,11,'2026-09-13 22:20:06','2026-09-27 19:14:03','2026-09-27 19:14:03'),(253,155,11,'2026-09-20 16:56:03','2026-09-27 19:14:03','2026-09-27 19:14:03'),(254,156,11,'2026-09-15 03:03:07','2026-09-27 19:14:03','2026-09-27 19:14:03'),(255,157,11,'2026-09-18 09:56:15','2026-09-27 19:14:03','2026-09-27 19:14:03'),(256,158,11,'2026-09-25 05:55:18','2026-09-27 19:14:03','2026-09-27 19:14:03'),(257,159,11,'2026-09-18 09:25:18','2026-09-27 19:14:03','2026-09-27 19:14:03'),(258,160,11,'2026-09-22 00:54:22','2026-09-27 19:14:03','2026-09-27 19:14:03'),(259,161,11,'2026-09-17 16:54:23','2026-09-27 19:14:03','2026-09-27 19:14:03'),(260,162,11,'2026-09-24 14:40:16','2026-09-27 19:14:03','2026-09-27 19:14:03'),(261,163,11,'2026-09-14 03:14:03','2026-09-27 19:14:03','2026-09-27 19:14:03'),(262,164,11,'2026-09-17 21:40:09','2026-09-27 19:14:03','2026-09-27 19:14:03'),(263,165,11,'2026-09-19 12:26:49','2026-09-27 19:14:03','2026-09-27 19:14:03'),(264,166,11,'2026-09-21 16:14:08','2026-09-27 19:14:03','2026-09-27 19:14:03'),(265,167,11,'2026-09-26 01:54:30','2026-09-27 19:14:03','2026-09-27 19:14:03'),(266,168,11,'2026-09-13 20:01:22','2026-09-27 19:14:03','2026-09-27 19:14:03'),(267,169,11,'2026-09-25 18:33:12','2026-09-27 19:14:03','2026-09-27 19:14:03'),(268,170,11,'2026-09-16 07:17:59','2026-09-27 19:14:03','2026-09-27 19:14:03'),(269,171,11,'2026-09-21 23:37:11','2026-09-27 19:14:03','2026-09-27 19:14:03'),(270,172,11,'2026-09-27 09:25:02','2026-09-27 19:14:03','2026-09-27 19:14:03'),(271,173,11,'2026-09-15 06:37:31','2026-09-27 19:14:03','2026-09-27 19:14:03'),(272,174,11,'2026-09-21 14:21:04','2026-09-27 19:14:03','2026-09-27 19:14:03'),(273,175,11,'2026-09-25 05:18:43','2026-09-27 19:14:04','2026-09-27 19:14:04'),(274,176,11,'2026-09-22 19:22:00','2026-09-27 19:14:04','2026-09-27 19:14:04'),(275,177,11,'2026-09-20 08:45:34','2026-09-27 19:14:04','2026-09-27 19:14:04'),(276,178,11,'2026-09-26 23:09:36','2026-09-27 19:14:04','2026-09-27 19:14:04'),(277,179,11,'2026-09-22 09:37:37','2026-09-27 19:14:04','2026-09-27 19:14:04'),(278,180,11,'2026-09-19 20:27:53','2026-09-27 19:14:04','2026-09-27 19:14:04'),(279,181,12,'2026-09-25 11:55:02','2026-09-27 19:14:04','2026-09-27 19:14:04'),(280,182,12,'2026-09-19 20:39:07','2026-09-27 19:14:04','2026-09-27 19:14:04'),(281,183,12,'2026-09-16 07:10:26','2026-09-27 19:14:04','2026-09-27 19:14:04'),(282,184,12,'2026-09-25 13:16:31','2026-09-27 19:14:04','2026-09-27 19:14:04'),(283,185,12,'2026-09-18 21:38:20','2026-09-27 19:14:04','2026-09-27 19:14:04'),(284,186,12,'2026-09-20 06:12:26','2026-09-27 19:14:04','2026-09-27 19:14:04'),(285,187,12,'2026-09-25 02:51:27','2026-09-27 19:14:04','2026-09-27 19:14:04'),(286,188,12,'2026-09-19 05:46:53','2026-09-27 19:14:04','2026-09-27 19:14:04'),(287,189,12,'2026-09-26 08:16:00','2026-09-27 19:14:04','2026-09-27 19:14:04'),(288,190,12,'2026-09-16 01:24:54','2026-09-27 19:14:04','2026-09-27 19:14:04'),(289,191,12,'2026-09-20 05:20:45','2026-09-27 19:14:04','2026-09-27 19:14:04'),(290,192,12,'2026-09-22 17:03:47','2026-09-27 19:14:04','2026-09-27 19:14:04'),(291,193,12,'2026-09-16 05:16:08','2026-09-27 19:14:04','2026-09-27 19:14:04'),(292,194,12,'2026-09-23 08:51:11','2026-09-27 19:14:04','2026-09-27 19:14:04'),(293,195,12,'2026-09-27 11:33:41','2026-09-27 19:14:04','2026-09-27 19:14:04'),(294,196,12,'2026-09-26 07:39:12','2026-09-27 19:14:04','2026-09-27 19:14:04'),(295,197,12,'2026-09-24 18:49:28','2026-09-27 19:14:04','2026-09-27 19:14:04'),(296,198,12,'2026-09-22 07:13:49','2026-09-27 19:14:04','2026-09-27 19:14:04'),(297,199,12,'2026-09-26 04:20:36','2026-09-27 19:14:04','2026-09-27 19:14:04'),(298,200,12,'2026-09-20 13:02:29','2026-09-27 19:14:04','2026-09-27 19:14:04'),(299,201,12,'2026-09-25 09:56:30','2026-09-27 19:14:04','2026-09-27 19:14:04'),(300,202,12,'2026-09-16 02:43:29','2026-09-27 19:14:04','2026-09-27 19:14:04'),(301,203,12,'2026-09-21 06:13:27','2026-09-27 19:14:04','2026-09-27 19:14:04'),(302,204,12,'2026-09-21 23:01:38','2026-09-27 19:14:04','2026-09-27 19:14:04'),(303,205,12,'2026-09-25 21:19:19','2026-09-27 19:14:04','2026-09-27 19:14:04'),(304,206,12,'2026-09-14 20:36:00','2026-09-27 19:14:04','2026-09-27 19:14:04'),(305,207,12,'2026-09-21 08:20:14','2026-09-27 19:14:04','2026-09-27 19:14:04'),(306,208,12,'2026-09-24 08:49:15','2026-09-27 19:14:04','2026-09-27 19:14:04'),(307,209,12,'2026-09-26 00:56:06','2026-09-27 19:14:04','2026-09-27 19:14:04'),(308,210,12,'2026-09-27 01:21:24','2026-09-27 19:14:04','2026-09-27 19:14:04'),(309,211,12,'2026-09-20 05:04:53','2026-09-27 19:14:04','2026-09-27 19:14:04'),(310,212,12,'2026-09-22 02:47:58','2026-09-27 19:14:04','2026-09-27 19:14:04'),(311,213,12,'2026-09-18 17:38:30','2026-09-27 19:14:04','2026-09-27 19:14:04'),(312,214,12,'2026-09-24 06:37:44','2026-09-27 19:14:04','2026-09-27 19:14:04'),(313,215,12,'2026-09-25 09:24:06','2026-09-27 19:14:04','2026-09-27 19:14:04'),(314,216,12,'2026-09-20 22:24:55','2026-09-27 19:14:04','2026-09-27 19:14:04'),(315,217,12,'2026-09-20 15:39:10','2026-09-27 19:14:04','2026-09-27 19:14:04'),(316,218,12,'2026-09-18 14:30:48','2026-09-27 19:14:04','2026-09-27 19:14:04'),(317,219,12,'2026-09-20 21:37:19','2026-09-27 19:14:04','2026-09-27 19:14:04'),(318,220,12,'2026-09-14 16:17:50','2026-09-27 19:14:05','2026-09-27 19:14:05'),(319,221,12,'2026-09-14 14:43:42','2026-09-27 19:14:05','2026-09-27 19:14:05'),(320,222,12,'2026-09-24 13:05:01','2026-09-27 19:14:05','2026-09-27 19:14:05'),(321,223,12,'2026-09-25 11:40:57','2026-09-27 19:14:05','2026-09-27 19:14:05'),(322,224,12,'2026-09-23 03:38:08','2026-09-27 19:14:05','2026-09-27 19:14:05');
/*!40000 ALTER TABLE `scene_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `scenes`
--

DROP TABLE IF EXISTS `scenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attraction_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `panorama_path` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `initial_yaw` double NOT NULL DEFAULT 0,
  `initial_pitch` double NOT NULL DEFAULT 0,
  `initial_hfov` double NOT NULL DEFAULT 100,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `audio_narration` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('published','draft') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scenes_attraction_id_slug_unique` (`attraction_id`,`slug`),
  CONSTRAINT `scenes_attraction_id_foreign` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `scenes`
--

LOCK TABLES `scenes` WRITE;
/*!40000 ALTER TABLE `scenes` DISABLE KEYS */;
INSERT INTO `scenes` VALUES (1,1,'Main Entrance & Plaza','main-entrance','panoramas/olumo-rock/main-entrance.jpg','panoramas/olumo-rock/thumbs/main-entrance-thumb.jpg',15,-3,100,1,7.15570000,3.34530000,'The arrival plaza at the base of Olumo Rock showcasing the historic gateway, art gallery, and ticket concourse.',NULL,1,'published','2026-09-27 11:07:42','2026-09-27 11:07:42'),(2,1,'Lower Platform & Ancient Steps','lower-platform','panoramas/olumo-rock/lower-platform.jpg','panoramas/olumo-rock/thumbs/lower-platform-thumb.jpg',45,5,95,2,7.15570000,3.34530000,'The lower staging plateau featuring the carved stone steps that historic Egba defenders mounted.',NULL,0,'published','2026-09-27 11:07:42','2026-09-27 11:07:42'),(3,1,'Cave Entrance','cave-entrance','panoramas/olumo-rock/cave-entrance.jpg','panoramas/olumo-rock/thumbs/cave-entrance-thumb.jpg',180,-2,100,3,7.15570000,3.34530000,'The sheltered overhang where Egba warriors and families took refuge during the 1830 Dahomey wars.',NULL,0,'published','2026-09-27 11:07:42','2026-09-27 11:07:42'),(4,1,'Cave Interior & Sacred Shrine','cave-interior','panoramas/olumo-rock/cave-interior.jpg','panoramas/olumo-rock/thumbs/cave-interior-thumb.jpg',90,0,90,4,7.15570000,3.34530000,'Interior rock sanctuary preserving the grinding holes, medicinal herb store, and ancient Orisa shrine.',NULL,0,'published','2026-09-27 11:07:42','2026-09-27 11:07:42'),(5,1,'Summit Panoramic View','summit','panoramas/olumo-rock/summit.jpg','panoramas/olumo-rock/thumbs/summit-thumb.jpg',0,-10,105,5,7.15570000,3.34530000,'The highest peak of Olumo Rock (137m) delivering an awe-inspiring 360-degree panorama of Abeokuta and Ogun River.',NULL,0,'published','2026-09-27 11:07:42','2026-09-27 11:07:42'),(6,2,'Monolith Viewing Grounds','viewing-grounds','panoramas/zuma-rock/viewing-grounds.jpg',NULL,0,12,100,1,NULL,NULL,NULL,NULL,1,'published','2026-09-27 11:07:42','2026-09-27 11:07:42'),(7,2,'Base of the Giant Monolith','rock-base','panoramas/zuma-rock/rock-base.jpg',NULL,45,20,100,2,NULL,NULL,NULL,NULL,0,'published','2026-09-27 11:07:42','2026-09-27 11:07:42'),(8,2,'Human Face Contour Perspective','face-profile','panoramas/zuma-rock/face-profile.jpg',NULL,10,25,100,3,NULL,NULL,NULL,NULL,0,'published','2026-09-27 11:07:42','2026-09-27 11:07:42'),(9,3,'Wikki Warm Spring Basin','wikki-spring','panoramas/yankari/wikki-spring.jpg',NULL,0,-8,100,1,NULL,NULL,NULL,NULL,1,'published','2026-09-27 11:07:43','2026-09-27 11:07:43'),(10,3,'Safari Main Savannah Gate','safari-gate','panoramas/yankari/safari-gate.jpg',NULL,30,2,100,2,NULL,NULL,NULL,NULL,0,'published','2026-09-27 11:07:43','2026-09-27 11:07:43'),(11,4,'Cable Car Summit Plateau','cable-car-plateau','panoramas/obudu/cable-car-plateau.jpg',NULL,0,-5,100,1,NULL,NULL,NULL,NULL,1,'published','2026-09-27 11:07:43','2026-09-27 11:07:43'),(12,5,'Suspended Canopy Walkway','canopy-walkway','panoramas/lekki/canopy-walkway.jpg',NULL,0,-8,100,1,NULL,NULL,NULL,NULL,1,'published','2026-09-27 11:07:43','2026-09-27 11:07:43');
/*!40000 ALTER TABLE `scenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `states` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `states_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
INSERT INTO `states` VALUES (1,'Ogun','ogun','South West','2026-09-27 09:31:23','2026-09-27 09:31:23'),(2,'Niger','niger','North Central','2026-09-27 09:31:23','2026-09-27 09:31:23'),(3,'Bauchi','bauchi','North East','2026-09-27 09:31:23','2026-09-27 09:31:23'),(4,'Lagos','lagos','South West','2026-09-27 09:31:23','2026-09-27 09:31:23'),(5,'Kano','kano','North West','2026-09-27 09:31:23','2026-09-27 09:31:23'),(6,'Enugu','enugu','South East','2026-09-27 09:31:23','2026-09-27 09:31:23'),(7,'Rivers','rivers','South South','2026-09-27 09:31:23','2026-09-27 09:31:23'),(8,'Plateau','plateau','North Central','2026-09-27 09:31:23','2026-09-27 09:31:23'),(9,'Osun','osun','South West','2026-09-27 09:31:23','2026-09-27 09:31:23'),(10,'Anambra','anambra','South East','2026-09-27 09:31:23','2026-09-27 09:31:23'),(11,'Akwa Ibom','akwa-ibom','South South','2026-09-27 09:31:23','2026-09-27 09:31:23'),(12,'Cross River','cross-river','South South','2026-09-27 09:31:23','2026-09-27 09:31:23'),(13,'Ondo','ondo','South West','2026-09-27 09:31:23','2026-09-27 09:31:23'),(14,'Kwara','kwara','North Central','2026-09-27 09:31:23','2026-09-27 09:31:23'),(15,'Abuja FCT','abuja-fct','North Central','2026-09-27 09:31:23','2026-09-27 09:31:23');
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tour_sessions`
--

DROP TABLE IF EXISTS `tour_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tour_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attraction_id` bigint(20) unsigned NOT NULL,
  `session_uuid` varchar(255) NOT NULL,
  `device_type` enum('mobile','tablet','desktop') NOT NULL DEFAULT 'desktop',
  `browser` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `avg_fps` double DEFAULT NULL,
  `load_time_ms` double DEFAULT NULL,
  `transition_time_ms` double DEFAULT NULL,
  `scenes_visited` int(11) NOT NULL DEFAULT 0,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tour_sessions_session_uuid_unique` (`session_uuid`),
  KEY `tour_sessions_attraction_id_foreign` (`attraction_id`),
  CONSTRAINT `tour_sessions_attraction_id_foreign` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour_sessions`
--

LOCK TABLES `tour_sessions` WRITE;
/*!40000 ALTER TABLE `tour_sessions` DISABLE KEYS */;
INSERT INTO `tour_sessions` VALUES (1,1,'f2c3f851-f96a-4552-aa21-ff87bdaa879c','mobile','Chrome','Android / iOS',37,1857,745,3,1,'2026-09-27 08:53:45','2026-09-27 10:58:45','2026-09-27 11:07:45','2026-09-27 11:07:45'),(2,1,'7d5c019d-4143-4d2c-bb93-d19f90f7caae','mobile','Safari','Android / iOS',38,2210,606,4,0,'2026-09-26 12:30:45','2026-09-27 11:01:45','2026-09-27 11:07:45','2026-09-27 11:07:45'),(3,1,'291dab5d-71ae-4da5-8f87-9386aed08b08','desktop','Chrome','Windows / macOS',53,2377,772,5,1,'2026-09-24 19:35:45','2026-09-27 11:04:45','2026-09-27 11:07:45','2026-09-27 11:07:45'),(4,1,'b3b1650b-a490-4262-878f-cc562d67b8bb','mobile','Firefox','Android / iOS',45,2268,619,3,0,'2026-09-25 06:56:45','2026-09-27 10:59:45','2026-09-27 11:07:45','2026-09-27 11:07:45'),(5,1,'5e27fa6c-ea7a-43a9-8459-975a8e712089','tablet','Chrome','Windows / macOS',42,1783,781,3,1,'2026-09-26 15:23:45','2026-09-27 11:06:45','2026-09-27 11:07:45','2026-09-27 11:07:45'),(6,1,'b51678c1-f99e-40d4-8582-1931985adaa5','desktop','Edge','Windows / macOS',59,2323,715,5,0,'2026-09-24 15:58:45','2026-09-27 11:05:45','2026-09-27 11:07:45','2026-09-27 11:07:45'),(7,1,'143773a4-3f04-4722-82d9-89566985314d','mobile','Chrome','Android / iOS',40,2159,771,3,1,'2026-09-26 05:54:45','2026-09-27 11:03:45','2026-09-27 11:07:45','2026-09-27 11:07:45'),(8,1,'8fd2e20e-f55b-41bc-b30d-a476db2d8082','desktop','Safari','Windows / macOS',55,2259,701,5,0,'2026-09-26 00:59:45','2026-09-27 10:59:45','2026-09-27 11:07:45','2026-09-27 11:07:45'),(9,1,'4e5acfe7-9c16-417f-a554-81537cfd4dfe','mobile','Chrome','Android / iOS',35,2277,763,3,1,'2026-09-26 09:01:46','2026-09-27 11:03:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(10,1,'2b4e3b6e-12ff-4783-b5ac-3cab29815250','mobile','Safari','Android / iOS',39,1773,695,5,0,'2026-09-25 11:37:46','2026-09-27 11:04:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(11,1,'0ec46909-3508-42df-ac33-1dcc378055fc','desktop','Chrome','Windows / macOS',60,2132,634,5,0,'2026-09-26 14:44:46','2026-09-27 11:00:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(12,1,'a3b5dbee-efd5-4e85-8139-0a9dfdafdd9b','mobile','Firefox','Android / iOS',43,2349,621,5,1,'2026-09-26 13:28:46','2026-09-27 11:06:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(13,1,'dcd44953-1b7f-45d6-8d0b-aff91289fb43','tablet','Chrome','Windows / macOS',37,1895,801,4,0,'2026-09-26 06:06:46','2026-09-27 11:02:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(14,1,'68b7e506-1c89-4fad-9a2b-28a32b661657','desktop','Edge','Windows / macOS',55,2253,618,4,0,'2026-09-24 04:45:46','2026-09-27 11:00:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(15,1,'08cfc04a-9f20-4311-a304-a23c1cc33f3d','mobile','Chrome','Android / iOS',42,2340,612,5,1,'2026-09-24 14:08:46','2026-09-27 11:04:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(16,1,'b9739709-eb62-471f-9f3d-6b713deed93f','desktop','Safari','Windows / macOS',54,1937,723,3,1,'2026-09-25 14:05:46','2026-09-27 11:05:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(17,1,'115009e7-fa99-4734-b7eb-f21557b19c78','mobile','Chrome','Android / iOS',34,2029,638,4,0,'2026-09-25 04:15:46','2026-09-27 10:59:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(18,1,'a145eefd-c80b-4ef0-ae05-d36895ee8ece','mobile','Safari','Android / iOS',42,2360,806,4,1,'2026-09-25 01:43:46','2026-09-27 11:01:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(19,1,'cc5edb2f-590f-4478-83a5-5860502bb465','desktop','Chrome','Windows / macOS',51,2088,637,4,0,'2026-09-25 01:58:46','2026-09-27 11:06:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(20,1,'51586cb3-244c-4953-9c75-de692ed51104','mobile','Firefox','Android / iOS',39,1781,668,5,1,'2026-09-24 14:40:46','2026-09-27 10:59:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(21,1,'21e8ae15-81ba-4435-8234-ab84f0cd7d54','tablet','Chrome','Windows / macOS',43,2163,635,3,1,'2026-09-24 21:35:46','2026-09-27 10:58:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(22,1,'cb43adb5-fd17-44c6-9954-d81d7196aad7','desktop','Edge','Windows / macOS',56,2274,603,5,1,'2026-09-26 11:34:46','2026-09-27 11:05:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(23,1,'8dbcf373-f8a6-4411-93d1-326fae225cea','mobile','Chrome','Android / iOS',35,1891,753,5,1,'2026-09-26 06:11:46','2026-09-27 10:58:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(24,1,'3da1bc29-2217-4707-9bfc-bc0695d59069','desktop','Safari','Windows / macOS',57,2395,773,3,0,'2026-09-24 19:16:46','2026-09-27 11:04:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(25,1,'68968256-8a06-4dc3-8ecb-19cc8e18d67e','mobile','Chrome','Android / iOS',42,2412,645,4,1,'2026-09-24 18:09:46','2026-09-27 11:01:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(26,1,'1573f814-d625-4e52-a469-2b4865bb7ff2','mobile','Safari','Android / iOS',37,2137,644,5,0,'2026-09-25 02:49:46','2026-09-27 11:01:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(27,1,'40e93292-d1b4-4c71-81a2-13b4c167c7c2','desktop','Chrome','Windows / macOS',58,2325,580,3,0,'2026-09-26 16:58:46','2026-09-27 10:59:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(28,1,'56d133e1-af5f-4084-96f7-3eca219a2002','mobile','Firefox','Android / iOS',45,1767,705,5,0,'2026-09-25 19:05:46','2026-09-27 11:01:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(29,1,'25810371-c651-46b6-9f9b-a258a3398adf','tablet','Chrome','Windows / macOS',40,1659,592,5,1,'2026-09-26 04:28:46','2026-09-27 11:06:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(30,1,'9d3db451-8f24-482c-bd69-643a864dfccc','desktop','Edge','Windows / macOS',58,1870,772,4,0,'2026-09-26 15:59:46','2026-09-27 10:58:46','2026-09-27 11:07:46','2026-09-27 11:07:46'),(31,1,'1966997f-a787-4c5d-8d34-c1433c5d51e6','mobile','Chrome','Android / iOS',39,1762,738,4,0,'2026-09-21 10:44:08','2026-09-21 10:47:27','2026-09-27 19:13:58','2026-09-27 19:13:58'),(32,1,'4d16b0f3-d33b-407f-acd5-e1aff547f556','mobile','Edge','Android / iOS',43,1698,598,2,0,'2026-09-17 03:33:15','2026-09-17 03:35:34','2026-09-27 19:13:58','2026-09-27 19:13:58'),(33,1,'c081e38c-3e5b-46a2-b3dc-460e789ccbec','mobile','Chrome','Android / iOS',45,1615,764,1,1,'2026-09-20 18:11:54','2026-09-20 18:13:37','2026-09-27 19:13:58','2026-09-27 19:13:58'),(34,1,'5f1db590-0ad3-4417-b80a-5e2dbc8765fc','desktop','Edge','Windows / macOS',53,2111,797,4,1,'2026-09-15 13:01:13','2026-09-15 13:04:43','2026-09-27 19:13:58','2026-09-27 19:13:58'),(35,1,'4c723cc7-d92b-446e-bf7c-2ef52e897153','mobile','Chrome','Android / iOS',45,1570,587,2,0,'2026-09-15 20:56:30','2026-09-15 20:58:45','2026-09-27 19:13:58','2026-09-27 19:13:58'),(36,1,'744fd5f1-c79f-49ec-b179-0bf00ceacbd9','desktop','Chrome','Windows / macOS',57,1610,767,3,1,'2026-09-14 06:07:54','2026-09-14 06:11:05','2026-09-27 19:13:58','2026-09-27 19:13:58'),(37,1,'9344d9a5-4d1a-4c14-8b86-b965fde80f0b','mobile','Chrome','Android / iOS',40,1528,685,1,0,'2026-09-14 13:55:29','2026-09-14 13:59:55','2026-09-27 19:13:58','2026-09-27 19:13:58'),(38,1,'f7a67e0c-573a-4775-912c-727814705baf','desktop','Chrome','Windows / macOS',55,2372,662,2,1,'2026-09-16 00:46:35','2026-09-16 00:50:28','2026-09-27 19:13:58','2026-09-27 19:13:58'),(39,1,'56160a64-478b-494e-b7a0-d2214aa1b583','desktop','Chrome','Windows / macOS',58,1885,590,3,1,'2026-09-14 05:09:03','2026-09-14 05:11:08','2026-09-27 19:13:58','2026-09-27 19:13:58'),(40,1,'f9a2a8b3-e6bb-4fef-8cc4-ef271fbefcfb','desktop','Chrome','Windows / macOS',52,1817,499,4,1,'2026-09-18 14:15:48','2026-09-18 14:18:59','2026-09-27 19:13:58','2026-09-27 19:13:58'),(41,1,'280be1de-aa85-4966-b392-f19e6dfd2eda','desktop','Safari','Windows / macOS',58,1849,517,5,1,'2026-09-26 23:39:02','2026-09-26 23:42:13','2026-09-27 19:13:58','2026-09-27 19:13:58'),(42,1,'ca86f0ee-058c-4021-a0aa-6af8ec1e4276','mobile','Safari','Android / iOS',37,2351,767,1,0,'2026-09-15 10:21:48','2026-09-15 10:22:49','2026-09-27 19:13:59','2026-09-27 19:13:59'),(43,1,'fd2b9c32-4d7d-4d57-a997-10fe84c8dbbd','mobile','Chrome','Android / iOS',44,1597,606,1,1,'2026-09-20 08:30:54','2026-09-20 08:32:43','2026-09-27 19:13:59','2026-09-27 19:13:59'),(44,1,'ef219228-ff59-4b9e-8a3c-b69d05dac54e','mobile','Safari','Android / iOS',35,1972,568,4,1,'2026-09-16 07:54:21','2026-09-16 07:56:40','2026-09-27 19:13:59','2026-09-27 19:13:59'),(45,1,'e2f4303f-dc6e-454e-9455-20326815d8b8','desktop','Chrome','Windows / macOS',54,2048,495,5,1,'2026-09-26 17:29:35','2026-09-26 17:32:15','2026-09-27 19:13:59','2026-09-27 19:13:59'),(46,1,'291d94ca-7751-42f5-8a68-37ebba50881c','mobile','Chrome','Android / iOS',41,1746,696,2,1,'2026-09-19 00:42:51','2026-09-19 00:43:53','2026-09-27 19:13:59','2026-09-27 19:13:59'),(47,1,'57ff79bb-c7a4-4078-92f7-2b701e618535','desktop','Chrome','Windows / macOS',52,2268,550,2,1,'2026-09-19 09:50:03','2026-09-19 09:52:06','2026-09-27 19:13:59','2026-09-27 19:13:59'),(48,1,'af6bd256-58a2-4349-9a0e-8b965a94359b','mobile','Edge','Android / iOS',37,1660,635,5,1,'2026-09-19 22:45:38','2026-09-19 22:48:31','2026-09-27 19:13:59','2026-09-27 19:13:59'),(49,1,'130b7082-0dc5-4aff-b87a-3503e44ef7b6','desktop','Chrome','Windows / macOS',58,2401,526,4,1,'2026-09-22 07:20:57','2026-09-22 07:22:57','2026-09-27 19:13:59','2026-09-27 19:13:59'),(50,1,'c4b427e4-0239-43fa-b868-32e35bd72b6b','mobile','Chrome','Android / iOS',45,2429,770,4,1,'2026-09-25 04:16:40','2026-09-25 04:19:33','2026-09-27 19:13:59','2026-09-27 19:13:59'),(51,1,'48940ab0-a2ef-4156-ab80-05951d65abc4','mobile','Chrome','Android / iOS',47,2011,748,1,1,'2026-09-21 16:13:51','2026-09-21 16:17:02','2026-09-27 19:13:59','2026-09-27 19:13:59'),(52,1,'1b8083c1-04d8-4c13-a160-f1ac11890b8f','desktop','Chrome','Windows / macOS',58,2072,673,5,1,'2026-09-26 14:19:38','2026-09-26 14:23:55','2026-09-27 19:13:59','2026-09-27 19:13:59'),(53,1,'77863944-4fae-43bf-9d5a-3edb3d457c72','mobile','Safari','Android / iOS',39,1820,672,4,1,'2026-09-17 06:41:02','2026-09-17 06:42:28','2026-09-27 19:13:59','2026-09-27 19:13:59'),(54,1,'848824bb-8964-4252-a33f-b0cd9a1b41a9','desktop','Safari','Windows / macOS',54,1518,629,3,1,'2026-09-22 10:10:11','2026-09-22 10:11:39','2026-09-27 19:13:59','2026-09-27 19:13:59'),(55,1,'d842b7cc-2233-48b1-ae71-654204cfdc00','desktop','Chrome','Windows / macOS',54,1796,674,3,1,'2026-09-20 01:21:03','2026-09-20 01:24:31','2026-09-27 19:13:59','2026-09-27 19:13:59'),(56,1,'3af080b7-6d4d-4875-841b-a85f1c366606','mobile','Edge','Android / iOS',37,2054,786,4,0,'2026-09-19 09:28:01','2026-09-19 09:30:54','2026-09-27 19:13:59','2026-09-27 19:13:59'),(57,1,'b067855a-aef6-4239-a551-bdc62d5d9100','desktop','Chrome','Windows / macOS',58,1580,610,3,1,'2026-09-25 09:07:55','2026-09-25 09:10:13','2026-09-27 19:13:59','2026-09-27 19:13:59'),(58,1,'9e39d1ff-dd2e-4a9b-9e11-9276cf0599e9','mobile','Safari','Android / iOS',45,1734,737,2,0,'2026-09-16 22:52:26','2026-09-16 22:54:14','2026-09-27 19:13:59','2026-09-27 19:13:59'),(59,1,'ea8daeb1-fe44-4a8c-a5db-f1ac7e089338','desktop','Chrome','Windows / macOS',52,1374,601,2,1,'2026-09-15 07:43:38','2026-09-15 07:44:35','2026-09-27 19:14:00','2026-09-27 19:14:00'),(60,1,'ef579fd5-860b-475b-8115-1c2d39a31796','mobile','Chrome','Android / iOS',41,1586,544,1,1,'2026-09-14 20:10:47','2026-09-14 20:14:55','2026-09-27 19:14:00','2026-09-27 19:14:00'),(61,1,'716f4198-d704-46e8-aa6e-93bdcb030924','mobile','Firefox','Android / iOS',42,1802,586,5,1,'2026-09-17 19:40:54','2026-09-17 19:44:30','2026-09-27 19:14:00','2026-09-27 19:14:00'),(62,1,'890aa1e3-bfc2-4636-be15-270e6d0c9f49','mobile','Firefox','Android / iOS',45,2144,593,2,1,'2026-09-18 05:28:36','2026-09-18 05:29:25','2026-09-27 19:14:00','2026-09-27 19:14:00'),(63,1,'711056c2-d8aa-448d-ac46-115647e6b7f7','mobile','Safari','Android / iOS',46,1530,772,3,1,'2026-09-24 02:47:57','2026-09-24 02:49:16','2026-09-27 19:14:00','2026-09-27 19:14:00'),(64,1,'e92cbebe-be76-4a18-8a2d-01dce4378387','desktop','Chrome','Windows / macOS',56,1626,494,2,1,'2026-09-21 14:48:54','2026-09-21 14:52:48','2026-09-27 19:14:00','2026-09-27 19:14:00'),(65,1,'6524378d-d3a5-43d2-ae21-a2d9c01bf16b','mobile','Firefox','Android / iOS',41,1940,550,4,1,'2026-09-22 07:39:58','2026-09-22 07:41:26','2026-09-27 19:14:00','2026-09-27 19:14:00'),(66,2,'fc872add-017e-40df-b3b2-1a5fcf4f32ea','desktop','Chrome','Windows / macOS',55,2079,641,2,1,'2026-09-17 07:29:36','2026-09-17 07:30:22','2026-09-27 19:14:00','2026-09-27 19:14:00'),(67,2,'59182021-76a6-49e5-adb7-ccc2be49ede2','desktop','Chrome','Windows / macOS',58,2119,590,3,1,'2026-09-18 09:57:45','2026-09-18 10:01:33','2026-09-27 19:14:00','2026-09-27 19:14:00'),(68,2,'b0ebec1b-d8ac-4efa-a887-813aec57ecd0','mobile','Chrome','Android / iOS',39,1516,567,2,1,'2026-09-21 13:37:59','2026-09-21 13:40:18','2026-09-27 19:14:00','2026-09-27 19:14:00'),(69,2,'4e7734be-c015-4914-a5b9-5f2e9ac4974c','mobile','Firefox','Android / iOS',37,2110,570,1,1,'2026-09-19 22:14:07','2026-09-19 22:15:49','2026-09-27 19:14:00','2026-09-27 19:14:00'),(70,2,'517855d6-d8da-4cc5-b889-9d06f2b59ed7','mobile','Edge','Android / iOS',43,1477,580,3,1,'2026-09-22 04:21:46','2026-09-22 04:26:27','2026-09-27 19:14:00','2026-09-27 19:14:00'),(71,2,'1bf5316b-83ec-4cef-a8c5-f9f9362bc38b','desktop','Chrome','Windows / macOS',58,1906,796,3,1,'2026-09-27 10:58:01','2026-09-27 10:58:55','2026-09-27 19:14:00','2026-09-27 19:14:00'),(72,2,'ae80e322-60dc-4b5f-8788-99d4522bb60b','mobile','Safari','Android / iOS',38,1886,640,1,0,'2026-09-24 11:46:43','2026-09-24 11:47:51','2026-09-27 19:14:00','2026-09-27 19:14:00'),(73,2,'7e206bc1-c716-4f1d-b0dd-7798945ab5eb','mobile','Firefox','Android / iOS',37,1928,752,3,0,'2026-09-15 07:52:58','2026-09-15 07:54:16','2026-09-27 19:14:00','2026-09-27 19:14:00'),(74,2,'fe7c59b5-e1c4-4249-99ff-1467a8d2cc30','desktop','Chrome','Windows / macOS',59,2191,628,2,1,'2026-09-14 04:05:49','2026-09-14 04:09:20','2026-09-27 19:14:00','2026-09-27 19:14:00'),(75,2,'614207c1-c9e9-4641-8877-8056fe383b84','mobile','Chrome','Android / iOS',46,2353,645,1,1,'2026-09-25 01:59:09','2026-09-25 02:02:18','2026-09-27 19:14:00','2026-09-27 19:14:00'),(76,2,'15ddf652-4f9a-4a7c-bf3f-e88fb2571624','desktop','Chrome','Windows / macOS',57,2322,780,1,1,'2026-09-21 21:57:19','2026-09-21 22:01:08','2026-09-27 19:14:00','2026-09-27 19:14:00'),(77,2,'f9f3b256-cf87-49dd-b509-0de37f6198bd','mobile','Chrome','Android / iOS',35,2130,637,3,1,'2026-09-23 23:10:16','2026-09-23 23:11:06','2026-09-27 19:14:00','2026-09-27 19:14:00'),(78,2,'1d3fab57-64d6-4afb-a62c-930112c6cac0','mobile','Safari','Android / iOS',47,2415,536,1,1,'2026-09-18 20:50:00','2026-09-18 20:52:35','2026-09-27 19:14:01','2026-09-27 19:14:01'),(79,2,'b336b6fd-c2b3-4f3a-85cf-e744ebadaf79','mobile','Chrome','Android / iOS',40,2342,544,1,0,'2026-09-15 03:42:43','2026-09-15 03:45:51','2026-09-27 19:14:01','2026-09-27 19:14:01'),(80,2,'d8b5e89b-af59-401f-af48-8ff592efdb14','mobile','Chrome','Android / iOS',44,1622,688,3,1,'2026-09-26 02:48:59','2026-09-26 02:50:09','2026-09-27 19:14:01','2026-09-27 19:14:01'),(81,2,'b664e9ab-2ad6-477d-ba35-e33704ef3c50','desktop','Chrome','Windows / macOS',59,1768,703,2,1,'2026-09-21 07:52:14','2026-09-21 07:56:38','2026-09-27 19:14:01','2026-09-27 19:14:01'),(82,2,'66e2c89c-ee1d-4cb0-af5b-0a9dc1462c57','desktop','Chrome','Windows / macOS',53,2066,484,2,1,'2026-09-21 14:10:19','2026-09-21 14:14:00','2026-09-27 19:14:01','2026-09-27 19:14:01'),(83,2,'86700e24-1456-4bae-8080-89a87b8d389d','desktop','Safari','Windows / macOS',54,1722,504,2,1,'2026-09-14 13:34:50','2026-09-14 13:36:58','2026-09-27 19:14:01','2026-09-27 19:14:01'),(84,2,'5884174a-50cf-4b1d-a617-8dc45fc06f10','desktop','Firefox','Windows / macOS',58,1775,589,2,1,'2026-09-18 12:34:36','2026-09-18 12:37:23','2026-09-27 19:14:01','2026-09-27 19:14:01'),(85,2,'21c1a51b-3945-43f2-816c-cebd5b130c1b','mobile','Chrome','Android / iOS',45,1962,607,2,1,'2026-09-17 23:12:00','2026-09-17 23:16:03','2026-09-27 19:14:01','2026-09-27 19:14:01'),(86,2,'94bfac84-cd59-4657-988a-29a360bcf12d','desktop','Chrome','Windows / macOS',52,1532,656,3,1,'2026-09-17 20:43:59','2026-09-17 20:46:37','2026-09-27 19:14:01','2026-09-27 19:14:01'),(87,2,'b26bf433-d22c-4cc1-8071-699380f9738b','mobile','Firefox','Android / iOS',35,2371,785,3,0,'2026-09-24 10:50:13','2026-09-24 10:52:21','2026-09-27 19:14:01','2026-09-27 19:14:01'),(88,2,'ed919fcb-b667-4b00-817e-9631493fb94a','mobile','Chrome','Android / iOS',44,1489,638,1,1,'2026-09-25 19:11:37','2026-09-25 19:14:28','2026-09-27 19:14:01','2026-09-27 19:14:01'),(89,2,'baf3a25d-d0b5-4957-9fe3-7fe05fbe1647','mobile','Chrome','Android / iOS',40,2344,761,2,0,'2026-09-23 06:40:39','2026-09-23 06:41:54','2026-09-27 19:14:01','2026-09-27 19:14:01'),(90,2,'8c8ea31f-d0b1-412c-bb9d-78215f40aa89','desktop','Chrome','Windows / macOS',56,2140,674,2,1,'2026-09-25 16:50:56','2026-09-25 16:55:07','2026-09-27 19:14:01','2026-09-27 19:14:01'),(91,2,'360f83ec-2529-410b-a3bf-11e3f56e2296','mobile','Chrome','Android / iOS',34,1489,599,1,0,'2026-09-16 02:09:19','2026-09-16 02:11:00','2026-09-27 19:14:01','2026-09-27 19:14:01'),(92,2,'ddbafba0-991c-47af-b247-a9f819028367','mobile','Chrome','Android / iOS',34,1713,597,1,0,'2026-09-24 22:56:13','2026-09-24 22:57:43','2026-09-27 19:14:01','2026-09-27 19:14:01'),(93,2,'d2d11781-a74a-4249-a7f4-811ede9e68a8','mobile','Chrome','Android / iOS',39,2142,558,2,1,'2026-09-21 16:42:23','2026-09-21 16:46:26','2026-09-27 19:14:01','2026-09-27 19:14:01'),(94,2,'b9f25b31-7905-4527-9e25-d0c5e0f8064e','mobile','Chrome','Android / iOS',45,1703,567,3,0,'2026-09-20 00:17:37','2026-09-20 00:19:16','2026-09-27 19:14:01','2026-09-27 19:14:01'),(95,2,'0d7f689d-a766-4059-8772-04dd9fd12478','mobile','Edge','Android / iOS',35,2403,618,2,0,'2026-09-23 03:23:49','2026-09-23 03:28:20','2026-09-27 19:14:01','2026-09-27 19:14:01'),(96,2,'16dfbf13-9fe0-497f-94c0-c4b186a54722','mobile','Chrome','Android / iOS',38,1362,500,1,1,'2026-09-15 15:01:42','2026-09-15 15:03:38','2026-09-27 19:14:01','2026-09-27 19:14:01'),(97,2,'91990c55-9982-42cf-a271-8c6655e5046f','mobile','Safari','Android / iOS',45,2100,714,3,1,'2026-09-21 15:18:58','2026-09-21 15:22:50','2026-09-27 19:14:01','2026-09-27 19:14:01'),(98,2,'9f501bcf-6138-4a86-828b-7903dfbb325d','mobile','Chrome','Android / iOS',42,1874,783,1,1,'2026-09-14 21:58:36','2026-09-14 22:02:34','2026-09-27 19:14:01','2026-09-27 19:14:01'),(99,2,'6d404098-22be-4ec5-ae0a-7de040d6d422','tablet','Chrome','iPadOS / Android Tablet',43,2096,587,2,1,'2026-09-18 16:55:30','2026-09-18 16:56:53','2026-09-27 19:14:01','2026-09-27 19:14:01'),(100,2,'7bc20c12-36af-4c07-a4b0-5cf855e3b58d','desktop','Chrome','Windows / macOS',59,2423,569,1,1,'2026-09-19 02:44:54','2026-09-19 02:49:14','2026-09-27 19:14:01','2026-09-27 19:14:01'),(101,2,'73ea8815-b775-41d7-aeb9-0a5faa709ba4','mobile','Chrome','Android / iOS',47,2053,653,1,0,'2026-09-26 20:11:50','2026-09-26 20:13:14','2026-09-27 19:14:01','2026-09-27 19:14:01'),(102,2,'5a745f39-fd6a-4784-b4b2-1bd73c3a08da','mobile','Safari','Android / iOS',45,1728,710,2,1,'2026-09-23 01:35:03','2026-09-23 01:35:51','2026-09-27 19:14:01','2026-09-27 19:14:01'),(103,2,'ddc30221-97ba-4e16-8b0e-44a1865030a9','mobile','Chrome','Android / iOS',47,1899,794,1,0,'2026-09-22 01:52:31','2026-09-22 01:56:34','2026-09-27 19:14:01','2026-09-27 19:14:01'),(104,2,'67562511-1fc4-4867-aeaf-2d8fcb478857','tablet','Chrome','iPadOS / Android Tablet',44,2403,506,1,1,'2026-09-14 10:40:31','2026-09-14 10:42:36','2026-09-27 19:14:01','2026-09-27 19:14:01'),(105,2,'9a121cca-6020-45d4-b5dd-dc0a9e872057','desktop','Chrome','Windows / macOS',52,2152,601,3,1,'2026-09-26 00:20:41','2026-09-26 00:23:13','2026-09-27 19:14:01','2026-09-27 19:14:01'),(106,2,'0015ff61-10a7-4532-8bd5-3b8ba5f39875','desktop','Chrome','Windows / macOS',58,1497,797,1,1,'2026-09-22 12:41:15','2026-09-22 12:42:54','2026-09-27 19:14:02','2026-09-27 19:14:02'),(107,3,'52dea13c-a447-4f32-ab13-e057bf85ccd0','desktop','Safari','Windows / macOS',54,2104,585,1,1,'2026-09-17 12:24:54','2026-09-17 12:26:36','2026-09-27 19:14:02','2026-09-27 19:14:02'),(108,3,'24b693c5-53b4-41c9-bf46-e94bcc483dbd','mobile','Chrome','Android / iOS',44,1441,485,1,1,'2026-09-14 00:05:20','2026-09-14 00:06:34','2026-09-27 19:14:02','2026-09-27 19:14:02'),(109,3,'23c8b8df-a955-451a-b77d-ea2f7fbf389e','mobile','Chrome','Android / iOS',35,1494,717,1,1,'2026-09-17 05:23:38','2026-09-17 05:24:30','2026-09-27 19:14:02','2026-09-27 19:14:02'),(110,3,'59339878-8bae-48a9-a4c5-78a6f0c212d7','mobile','Safari','Android / iOS',39,1662,689,1,1,'2026-09-17 12:03:05','2026-09-17 12:03:54','2026-09-27 19:14:02','2026-09-27 19:14:02'),(111,3,'7a35c66e-87de-44c3-a087-e407879c7aa3','tablet','Safari','iPadOS / Android Tablet',38,2217,706,2,0,'2026-09-18 15:06:50','2026-09-18 15:11:08','2026-09-27 19:14:02','2026-09-27 19:14:02'),(112,3,'a498eade-2a59-4be3-a475-9f6373ee9afb','desktop','Chrome','Windows / macOS',59,1685,566,2,1,'2026-09-16 01:56:30','2026-09-16 01:57:20','2026-09-27 19:14:02','2026-09-27 19:14:02'),(113,3,'5c5da64d-debe-42df-b82c-cd232289f1c1','desktop','Safari','Windows / macOS',58,2023,532,1,1,'2026-09-27 13:53:25','2026-09-27 13:55:49','2026-09-27 19:14:02','2026-09-27 19:14:02'),(114,3,'2a0505de-02a4-4065-91a6-129d70c2ad76','desktop','Chrome','Windows / macOS',56,1650,609,2,1,'2026-09-15 22:00:04','2026-09-15 22:01:18','2026-09-27 19:14:02','2026-09-27 19:14:02'),(115,3,'61822c66-b3cb-4f42-a037-98737ccbdc7b','mobile','Chrome','Android / iOS',46,1831,480,2,1,'2026-09-21 16:27:11','2026-09-21 16:28:29','2026-09-27 19:14:02','2026-09-27 19:14:02'),(116,3,'f8bc0b13-adcb-454b-b776-29f7e6be435c','mobile','Safari','Android / iOS',38,2313,643,2,1,'2026-09-14 06:00:52','2026-09-14 06:05:30','2026-09-27 19:14:02','2026-09-27 19:14:02'),(117,3,'fbcc7822-d684-4c63-bbc6-a3d8d90fb97b','mobile','Chrome','Android / iOS',37,1682,640,2,1,'2026-09-18 08:36:14','2026-09-18 08:40:02','2026-09-27 19:14:02','2026-09-27 19:14:02'),(118,3,'af2011f6-95b3-42e8-9730-f7e61bb34ef0','mobile','Chrome','Android / iOS',34,1397,581,1,1,'2026-09-21 11:44:25','2026-09-21 11:46:11','2026-09-27 19:14:02','2026-09-27 19:14:02'),(119,3,'0c164e00-a3e5-48f6-bd54-ff34dd59cec3','mobile','Chrome','Android / iOS',42,2179,763,2,1,'2026-09-20 23:52:56','2026-09-20 23:57:07','2026-09-27 19:14:02','2026-09-27 19:14:02'),(120,3,'1615e07a-c0a7-4899-a9fc-77b5e2b902dd','desktop','Chrome','Windows / macOS',56,2125,760,1,1,'2026-09-21 11:07:08','2026-09-21 11:07:53','2026-09-27 19:14:02','2026-09-27 19:14:02'),(121,3,'413f9ed2-21d8-4524-8d05-2e75285f9ba0','mobile','Safari','Android / iOS',36,1737,489,1,1,'2026-09-24 05:21:59','2026-09-24 05:24:52','2026-09-27 19:14:02','2026-09-27 19:14:02'),(122,3,'45a698a4-77d3-4be9-8e4a-17374db214f2','desktop','Chrome','Windows / macOS',59,2362,698,2,1,'2026-09-24 03:10:57','2026-09-24 03:13:38','2026-09-27 19:14:02','2026-09-27 19:14:02'),(123,3,'93b344a0-3398-476c-8ec0-3718b1a0d2c0','mobile','Chrome','Android / iOS',43,2342,715,2,1,'2026-09-15 05:47:05','2026-09-15 05:47:50','2026-09-27 19:14:02','2026-09-27 19:14:02'),(124,3,'9bdf4be2-62ca-4788-82e9-775a51584abe','mobile','Chrome','Android / iOS',43,1764,612,2,0,'2026-09-20 12:47:10','2026-09-20 12:48:35','2026-09-27 19:14:02','2026-09-27 19:14:02'),(125,3,'cfdacd28-8fb1-4c94-b85a-601149e9f225','tablet','Chrome','iPadOS / Android Tablet',41,1615,752,1,0,'2026-09-15 10:58:17','2026-09-15 10:59:29','2026-09-27 19:14:02','2026-09-27 19:14:02'),(126,3,'fdb794b3-6fc6-419e-b881-65c9ab5fccc6','mobile','Chrome','Android / iOS',46,1563,739,2,1,'2026-09-21 18:14:40','2026-09-21 18:18:23','2026-09-27 19:14:02','2026-09-27 19:14:02'),(127,3,'ea816f75-3067-4a69-83e9-8c39b0845b6e','desktop','Chrome','Windows / macOS',53,1713,555,2,1,'2026-09-24 23:00:22','2026-09-24 23:04:06','2026-09-27 19:14:02','2026-09-27 19:14:02'),(128,3,'58d6be66-49f1-401b-92ac-0dd2299d7e2e','mobile','Chrome','Android / iOS',45,2044,578,2,1,'2026-09-22 13:07:50','2026-09-22 13:11:52','2026-09-27 19:14:02','2026-09-27 19:14:02'),(129,3,'436af141-db64-4437-b9dd-248e6c822991','desktop','Chrome','Windows / macOS',54,2041,797,2,1,'2026-09-25 02:10:07','2026-09-25 02:11:39','2026-09-27 19:14:02','2026-09-27 19:14:02'),(130,3,'df03aa25-8338-41a4-8503-8372fcc2a59a','mobile','Chrome','Android / iOS',39,1520,717,1,0,'2026-09-17 11:12:35','2026-09-17 11:16:36','2026-09-27 19:14:02','2026-09-27 19:14:02'),(131,3,'6ef782b5-ae73-4f25-885c-6344d697587b','mobile','Chrome','Android / iOS',41,1524,558,1,0,'2026-09-18 11:29:28','2026-09-18 11:31:21','2026-09-27 19:14:02','2026-09-27 19:14:02'),(132,3,'c0f071fa-33b5-4734-9002-4d217959719e','mobile','Chrome','Android / iOS',44,2274,723,2,1,'2026-09-18 10:34:20','2026-09-18 10:35:22','2026-09-27 19:14:02','2026-09-27 19:14:02'),(133,3,'cd8ca811-d9e7-40fc-ab40-82b27b0b5767','mobile','Chrome','Android / iOS',44,1517,643,2,1,'2026-09-15 18:31:47','2026-09-15 18:34:43','2026-09-27 19:14:02','2026-09-27 19:14:02'),(134,3,'5813f3aa-fc50-4a2a-abf6-6e62359fecac','mobile','Safari','Android / iOS',40,2068,603,2,0,'2026-09-20 01:03:12','2026-09-20 01:06:23','2026-09-27 19:14:02','2026-09-27 19:14:02'),(135,3,'54feb057-9079-4a74-877b-33c4b661e40d','mobile','Chrome','Android / iOS',47,1558,510,1,0,'2026-09-20 09:22:02','2026-09-20 09:25:39','2026-09-27 19:14:02','2026-09-27 19:14:02'),(136,3,'2d21ad10-3156-4683-bd7b-221e9f0ec809','desktop','Chrome','Windows / macOS',56,2391,722,2,1,'2026-09-26 02:06:44','2026-09-26 02:09:22','2026-09-27 19:14:03','2026-09-27 19:14:03'),(137,3,'2e9c0936-a346-423d-bd51-b9111a322486','desktop','Firefox','Windows / macOS',56,1417,735,2,1,'2026-09-15 01:29:41','2026-09-15 01:31:22','2026-09-27 19:14:03','2026-09-27 19:14:03'),(138,3,'401b2c14-34ac-49a2-b2fd-82da338f1e4b','mobile','Chrome','Android / iOS',43,1490,590,1,1,'2026-09-16 04:02:35','2026-09-16 04:04:17','2026-09-27 19:14:03','2026-09-27 19:14:03'),(139,3,'d946e4b5-f97f-4aeb-85a0-2416a7ec9c7a','mobile','Chrome','Android / iOS',37,2145,630,1,1,'2026-09-19 16:43:23','2026-09-19 16:47:36','2026-09-27 19:14:03','2026-09-27 19:14:03'),(140,3,'adf6e298-d687-4f1a-ac13-2b446215aa73','desktop','Edge','Windows / macOS',54,1638,790,2,1,'2026-09-20 05:13:36','2026-09-20 05:15:07','2026-09-27 19:14:03','2026-09-27 19:14:03'),(141,3,'ccd2e04b-f785-4ec8-842d-5e2a6123cd73','mobile','Chrome','Android / iOS',37,1992,657,2,1,'2026-09-14 10:48:48','2026-09-14 10:51:10','2026-09-27 19:14:03','2026-09-27 19:14:03'),(142,3,'cad853af-0a5e-4ff9-8c27-9f813216112d','mobile','Safari','Android / iOS',38,1683,487,2,1,'2026-09-15 19:09:04','2026-09-15 19:12:37','2026-09-27 19:14:03','2026-09-27 19:14:03'),(143,3,'52147076-54a4-4c92-8356-e872443c3d6c','mobile','Chrome','Android / iOS',39,1575,744,1,1,'2026-09-21 08:58:32','2026-09-21 08:59:18','2026-09-27 19:14:03','2026-09-27 19:14:03'),(144,3,'98b0097c-7a90-4686-81e3-b1b4a1fde770','mobile','Chrome','Android / iOS',47,1678,509,2,1,'2026-09-19 13:43:54','2026-09-19 13:47:36','2026-09-27 19:14:03','2026-09-27 19:14:03'),(145,3,'356c469a-a27c-4575-9100-ebe1d16a5670','desktop','Firefox','Windows / macOS',59,1674,681,2,0,'2026-09-14 08:42:49','2026-09-14 08:44:42','2026-09-27 19:14:03','2026-09-27 19:14:03'),(146,4,'d6444128-8d78-4967-8370-5273e4a5ba92','mobile','Chrome','Android / iOS',46,2175,798,1,1,'2026-09-24 17:46:37','2026-09-24 17:48:29','2026-09-27 19:14:03','2026-09-27 19:14:03'),(147,4,'84c1596b-5d7d-492d-a433-b2b50cd2f72e','desktop','Edge','Windows / macOS',54,2445,536,1,1,'2026-09-25 16:39:37','2026-09-25 16:44:11','2026-09-27 19:14:03','2026-09-27 19:14:03'),(148,4,'82fd1ecf-3587-4dfe-bbe6-800f830f7918','mobile','Safari','Android / iOS',38,2179,766,1,1,'2026-09-16 18:30:12','2026-09-16 18:34:24','2026-09-27 19:14:03','2026-09-27 19:14:03'),(149,4,'ef4b3934-8a95-4358-97c5-c622b5863538','mobile','Chrome','Android / iOS',47,1502,744,1,1,'2026-09-21 16:08:41','2026-09-21 16:12:18','2026-09-27 19:14:03','2026-09-27 19:14:03'),(150,4,'0f9ef313-9684-4195-be91-e67d3cf76b80','desktop','Chrome','Windows / macOS',55,1847,626,1,1,'2026-09-26 08:54:57','2026-09-26 08:59:21','2026-09-27 19:14:03','2026-09-27 19:14:03'),(151,4,'c39b10c4-d659-4d02-b188-b9d3f8bd15b3','mobile','Safari','Android / iOS',45,1801,604,1,1,'2026-09-26 11:46:02','2026-09-26 11:47:54','2026-09-27 19:14:03','2026-09-27 19:14:03'),(152,4,'1ec13662-fa22-4e6b-8621-01157e372d4c','mobile','Chrome','Android / iOS',40,2104,670,1,1,'2026-09-18 22:08:40','2026-09-18 22:11:13','2026-09-27 19:14:03','2026-09-27 19:14:03'),(153,4,'b71cd3b8-b2f4-41e8-b175-bc5dd685af10','desktop','Chrome','Windows / macOS',52,1647,685,1,0,'2026-09-14 19:54:18','2026-09-14 19:55:59','2026-09-27 19:14:03','2026-09-27 19:14:03'),(154,4,'231e6f3d-8541-4543-9d8e-d394e9baa9b1','mobile','Chrome','Android / iOS',44,2174,499,1,0,'2026-09-13 22:20:06','2026-09-13 22:21:32','2026-09-27 19:14:03','2026-09-27 19:14:03'),(155,4,'350d23d3-f821-496d-b7e4-6273f9f3bfea','desktop','Chrome','Windows / macOS',54,1933,620,1,1,'2026-09-20 16:56:03','2026-09-20 16:59:19','2026-09-27 19:14:03','2026-09-27 19:14:03'),(156,4,'80f6b1a0-17d4-4570-850e-0b683992ce3d','mobile','Chrome','Android / iOS',36,1575,773,1,1,'2026-09-15 03:03:07','2026-09-15 03:07:13','2026-09-27 19:14:03','2026-09-27 19:14:03'),(157,4,'badfa715-43e1-433a-8b0b-bc8a7fb8661f','mobile','Safari','Android / iOS',39,1417,575,1,1,'2026-09-18 09:56:15','2026-09-18 10:00:04','2026-09-27 19:14:03','2026-09-27 19:14:03'),(158,4,'fd95b405-f0fb-4b48-8076-9ecc616cc065','mobile','Chrome','Android / iOS',38,2207,734,1,1,'2026-09-25 05:55:18','2026-09-25 05:57:21','2026-09-27 19:14:03','2026-09-27 19:14:03'),(159,4,'c44fa314-5dbe-45fb-aa68-d5814836098e','desktop','Chrome','Windows / macOS',54,1390,666,1,1,'2026-09-18 09:25:18','2026-09-18 09:29:33','2026-09-27 19:14:03','2026-09-27 19:14:03'),(160,4,'df657023-c052-490f-9bc1-d42dd1b5388c','desktop','Chrome','Windows / macOS',56,2437,635,1,1,'2026-09-22 00:54:22','2026-09-22 00:57:22','2026-09-27 19:14:03','2026-09-27 19:14:03'),(161,4,'f6ae95ff-2742-443e-a2c0-043d3515ffc3','mobile','Safari','Android / iOS',36,1646,764,1,1,'2026-09-17 16:54:23','2026-09-17 16:58:15','2026-09-27 19:14:03','2026-09-27 19:14:03'),(162,4,'f241dde7-1a0f-4970-85d3-65936ce9a4e5','desktop','Safari','Windows / macOS',54,2143,640,1,0,'2026-09-24 14:40:16','2026-09-24 14:42:21','2026-09-27 19:14:03','2026-09-27 19:14:03'),(163,4,'350c69e1-4dea-4d42-af02-656b61a00aad','mobile','Chrome','Android / iOS',34,2056,618,1,0,'2026-09-14 03:14:03','2026-09-14 03:18:15','2026-09-27 19:14:03','2026-09-27 19:14:03'),(164,4,'3fd40bac-2e03-48ea-9b69-4f22307c3873','mobile','Chrome','Android / iOS',45,1599,659,1,1,'2026-09-17 21:40:09','2026-09-17 21:44:20','2026-09-27 19:14:03','2026-09-27 19:14:03'),(165,4,'878a02dc-c6f3-4b56-98fb-136d850c1516','mobile','Chrome','Android / iOS',37,1666,796,1,0,'2026-09-19 12:26:49','2026-09-19 12:30:09','2026-09-27 19:14:03','2026-09-27 19:14:03'),(166,4,'97470ac1-cd49-4d62-b289-66bbb7125d08','mobile','Chrome','Android / iOS',37,1464,684,1,1,'2026-09-21 16:14:08','2026-09-21 16:15:24','2026-09-27 19:14:03','2026-09-27 19:14:03'),(167,4,'e63a3856-d73d-4298-a945-0847aef565fb','desktop','Edge','Windows / macOS',56,1796,501,1,0,'2026-09-26 01:54:30','2026-09-26 01:56:49','2026-09-27 19:14:03','2026-09-27 19:14:03'),(168,4,'376f7f4c-21cc-4b66-8cc6-8b958e93e1c4','mobile','Chrome','Android / iOS',36,1552,768,1,0,'2026-09-13 20:01:22','2026-09-13 20:04:04','2026-09-27 19:14:03','2026-09-27 19:14:03'),(169,4,'69941a01-0b8b-40db-b3e9-83aba01fe8a4','desktop','Safari','Windows / macOS',59,2266,731,1,1,'2026-09-25 18:33:12','2026-09-25 18:35:11','2026-09-27 19:14:03','2026-09-27 19:14:03'),(170,4,'514df0b6-6e9c-4639-bf0c-5eb631651172','mobile','Safari','Android / iOS',46,1954,530,1,1,'2026-09-16 07:17:59','2026-09-16 07:19:13','2026-09-27 19:14:03','2026-09-27 19:14:03'),(171,4,'3e775292-ca10-47a7-9013-c9ab5769f8f1','desktop','Chrome','Windows / macOS',55,2326,534,1,1,'2026-09-21 23:37:11','2026-09-21 23:40:43','2026-09-27 19:14:03','2026-09-27 19:14:03'),(172,4,'ee29c814-8086-4826-ba43-a041bec16faf','mobile','Chrome','Android / iOS',35,1554,730,1,1,'2026-09-27 09:25:02','2026-09-27 09:26:58','2026-09-27 19:14:03','2026-09-27 19:14:03'),(173,4,'e2c24cd3-134c-4ff8-be07-1f84730f8c56','mobile','Chrome','Android / iOS',41,1379,557,1,1,'2026-09-15 06:37:31','2026-09-15 06:41:16','2026-09-27 19:14:03','2026-09-27 19:14:03'),(174,4,'7693d8e8-4a1b-43f7-a1e7-0ca60928742f','mobile','Chrome','Android / iOS',38,1955,621,1,1,'2026-09-21 14:21:04','2026-09-21 14:25:18','2026-09-27 19:14:03','2026-09-27 19:14:03'),(175,4,'6a033f05-8a06-4189-ac69-611afa56cd1f','mobile','Firefox','Android / iOS',35,1606,519,1,1,'2026-09-25 05:18:43','2026-09-25 05:23:00','2026-09-27 19:14:03','2026-09-27 19:14:03'),(176,4,'458623bb-c068-486f-a8b3-d57999deb208','desktop','Chrome','Windows / macOS',55,2279,609,1,1,'2026-09-22 19:22:00','2026-09-22 19:26:10','2026-09-27 19:14:04','2026-09-27 19:14:04'),(177,4,'ddfd47fb-2b08-4203-b95a-07470a79f756','desktop','Chrome','Windows / macOS',53,2286,673,1,0,'2026-09-20 08:45:34','2026-09-20 08:46:50','2026-09-27 19:14:04','2026-09-27 19:14:04'),(178,4,'eaa6e5eb-555b-4810-be9f-86e7a684a8e7','mobile','Edge','Android / iOS',45,2335,754,1,1,'2026-09-26 23:09:36','2026-09-26 23:12:01','2026-09-27 19:14:04','2026-09-27 19:14:04'),(179,4,'62332381-22d3-4991-8e5a-a39ee565d424','mobile','Safari','Android / iOS',34,2301,506,1,1,'2026-09-22 09:37:37','2026-09-22 09:39:17','2026-09-27 19:14:04','2026-09-27 19:14:04'),(180,4,'493884b2-a602-49cc-bcb7-bfb7dd0ebf88','mobile','Safari','Android / iOS',34,2413,638,1,1,'2026-09-19 20:27:53','2026-09-19 20:32:08','2026-09-27 19:14:04','2026-09-27 19:14:04'),(181,5,'ec87e975-116f-4ae6-95c9-114eca17fcc3','mobile','Chrome','Android / iOS',45,1859,782,1,1,'2026-09-25 11:55:02','2026-09-25 11:57:53','2026-09-27 19:14:04','2026-09-27 19:14:04'),(182,5,'fc20fc52-fc5b-458d-8f0f-9a34e8658c03','mobile','Edge','Android / iOS',41,1645,576,1,1,'2026-09-19 20:39:07','2026-09-19 20:43:06','2026-09-27 19:14:04','2026-09-27 19:14:04'),(183,5,'3bc31c7f-57a0-4cfc-adfe-e610793a24c6','mobile','Chrome','Android / iOS',46,2000,505,1,1,'2026-09-16 07:10:26','2026-09-16 07:13:45','2026-09-27 19:14:04','2026-09-27 19:14:04'),(184,5,'08c8b650-6cf8-49ba-9658-77e6d1703ea6','mobile','Safari','Android / iOS',40,1624,603,1,0,'2026-09-25 13:16:31','2026-09-25 13:18:29','2026-09-27 19:14:04','2026-09-27 19:14:04'),(185,5,'7a05fe10-1f59-455e-bb67-184adc4bca91','mobile','Safari','Android / iOS',39,2387,662,1,1,'2026-09-18 21:38:20','2026-09-18 21:42:25','2026-09-27 19:14:04','2026-09-27 19:14:04'),(186,5,'2e931627-3fbb-4aea-af2e-57a8f7fa684d','mobile','Chrome','Android / iOS',37,1811,656,1,1,'2026-09-20 06:12:26','2026-09-20 06:14:10','2026-09-27 19:14:04','2026-09-27 19:14:04'),(187,5,'054b80ef-adcf-4957-be87-8cd77ec3db5d','mobile','Edge','Android / iOS',46,2244,531,1,1,'2026-09-25 02:51:27','2026-09-25 02:53:29','2026-09-27 19:14:04','2026-09-27 19:14:04'),(188,5,'d2b5b598-50f7-434d-b3b0-a9dd84912a03','desktop','Safari','Windows / macOS',56,2293,544,1,0,'2026-09-19 05:46:53','2026-09-19 05:51:05','2026-09-27 19:14:04','2026-09-27 19:14:04'),(189,5,'eed96850-df2e-4dc9-8029-14f4a324308c','mobile','Chrome','Android / iOS',34,2334,511,1,1,'2026-09-26 08:16:00','2026-09-26 08:20:22','2026-09-27 19:14:04','2026-09-27 19:14:04'),(190,5,'954599a3-1d1b-4313-99d5-da128500bd39','mobile','Chrome','Android / iOS',36,1399,512,1,1,'2026-09-16 01:24:54','2026-09-16 01:26:30','2026-09-27 19:14:04','2026-09-27 19:14:04'),(191,5,'67d26498-6f9c-4e39-8e31-25035f710c5d','mobile','Firefox','Android / iOS',43,2162,791,1,0,'2026-09-20 05:20:45','2026-09-20 05:22:51','2026-09-27 19:14:04','2026-09-27 19:14:04'),(192,5,'9aa9eb2c-9e4e-40e4-a5be-9a12339816bf','mobile','Chrome','Android / iOS',43,2181,532,1,1,'2026-09-22 17:03:47','2026-09-22 17:06:02','2026-09-27 19:14:04','2026-09-27 19:14:04'),(193,5,'07018656-a43c-4f42-91be-7ec8763f14c5','mobile','Chrome','Android / iOS',38,2306,531,1,0,'2026-09-16 05:16:08','2026-09-16 05:19:42','2026-09-27 19:14:04','2026-09-27 19:14:04'),(194,5,'293bef7c-4ace-46a7-9a3c-137a4caa13a8','mobile','Chrome','Android / iOS',47,2220,725,1,0,'2026-09-23 08:51:11','2026-09-23 08:55:16','2026-09-27 19:14:04','2026-09-27 19:14:04'),(195,5,'3bed6a3f-0114-453f-b107-c6cf8bca03ac','mobile','Edge','Android / iOS',46,2438,667,1,1,'2026-09-27 11:33:41','2026-09-27 11:38:01','2026-09-27 19:14:04','2026-09-27 19:14:04'),(196,5,'32b61bee-3a5a-4ae8-94b0-32c8daf051fe','mobile','Safari','Android / iOS',43,2447,550,1,0,'2026-09-26 07:39:12','2026-09-26 07:42:36','2026-09-27 19:14:04','2026-09-27 19:14:04'),(197,5,'56af59ca-5eaf-469e-a0b6-b76e8d4ba74b','mobile','Chrome','Android / iOS',38,1463,600,1,1,'2026-09-24 18:49:28','2026-09-24 18:52:17','2026-09-27 19:14:04','2026-09-27 19:14:04'),(198,5,'5ae48367-2fbb-473e-8796-c1a19debbcea','mobile','Firefox','Android / iOS',40,2375,555,1,1,'2026-09-22 07:13:49','2026-09-22 07:17:53','2026-09-27 19:14:04','2026-09-27 19:14:04'),(199,5,'d0bd56de-f164-4e7c-91df-c0ce443f1313','tablet','Chrome','iPadOS / Android Tablet',43,2015,538,1,1,'2026-09-26 04:20:36','2026-09-26 04:24:13','2026-09-27 19:14:04','2026-09-27 19:14:04'),(200,5,'a411e526-5da8-43ce-9286-de2ea37cdecb','mobile','Chrome','Android / iOS',35,1632,786,1,0,'2026-09-20 13:02:29','2026-09-20 13:06:34','2026-09-27 19:14:04','2026-09-27 19:14:04'),(201,5,'7ad3cf0e-81b0-4146-a58e-0a7b136028ac','mobile','Chrome','Android / iOS',40,2210,564,1,1,'2026-09-25 09:56:30','2026-09-25 10:00:48','2026-09-27 19:14:04','2026-09-27 19:14:04'),(202,5,'76cfb38c-17b9-48ac-aff0-da5d6b4e716d','mobile','Chrome','Android / iOS',42,1357,675,1,1,'2026-09-16 02:43:29','2026-09-16 02:45:46','2026-09-27 19:14:04','2026-09-27 19:14:04'),(203,5,'3f28dc10-965a-40f2-895c-cb7b621ebad5','mobile','Edge','Android / iOS',47,1769,575,1,1,'2026-09-21 06:13:27','2026-09-21 06:14:21','2026-09-27 19:14:04','2026-09-27 19:14:04'),(204,5,'8e8fb4ec-6afe-4b18-a783-5331a8cef244','desktop','Chrome','Windows / macOS',53,1763,641,1,1,'2026-09-21 23:01:38','2026-09-21 23:03:04','2026-09-27 19:14:04','2026-09-27 19:14:04'),(205,5,'23e0d6b1-49f6-491b-a879-4590108fd86e','mobile','Chrome','Android / iOS',40,2437,694,1,0,'2026-09-25 21:19:19','2026-09-25 21:23:32','2026-09-27 19:14:04','2026-09-27 19:14:04'),(206,5,'f3d06199-f08a-4a39-a2bd-4988a1d64477','mobile','Chrome','Android / iOS',46,2109,602,1,1,'2026-09-14 20:36:00','2026-09-14 20:39:44','2026-09-27 19:14:04','2026-09-27 19:14:04'),(207,5,'e1de7a02-56ef-4438-ac68-e224c86ad208','mobile','Chrome','Android / iOS',44,1935,563,1,1,'2026-09-21 08:20:14','2026-09-21 08:24:18','2026-09-27 19:14:04','2026-09-27 19:14:04'),(208,5,'96aac9a8-ed28-4b16-976c-76f17e436c76','mobile','Safari','Android / iOS',42,1874,794,1,1,'2026-09-24 08:49:15','2026-09-24 08:51:45','2026-09-27 19:14:04','2026-09-27 19:14:04'),(209,5,'f8b2ff69-8429-4eef-be66-d9767f93b38e','mobile','Chrome','Android / iOS',44,1911,594,1,1,'2026-09-26 00:56:06','2026-09-26 00:58:57','2026-09-27 19:14:04','2026-09-27 19:14:04'),(210,5,'8f3a1e68-71ac-461e-bbdc-2dd92fb0c44e','desktop','Safari','Windows / macOS',52,1832,761,1,1,'2026-09-27 01:21:24','2026-09-27 01:24:11','2026-09-27 19:14:04','2026-09-27 19:14:04'),(211,5,'241bc836-fbff-4591-8c75-147a99f17344','desktop','Chrome','Windows / macOS',54,2203,784,1,1,'2026-09-20 05:04:53','2026-09-20 05:07:03','2026-09-27 19:14:04','2026-09-27 19:14:04'),(212,5,'6dc42232-e01c-4033-ada8-6bab0c3431a2','desktop','Chrome','Windows / macOS',56,2130,581,1,0,'2026-09-22 02:47:58','2026-09-22 02:51:59','2026-09-27 19:14:04','2026-09-27 19:14:04'),(213,5,'1021a5bd-6414-4b59-b433-9e1e70f5e75c','desktop','Edge','Windows / macOS',56,1500,484,1,1,'2026-09-18 17:38:30','2026-09-18 17:42:56','2026-09-27 19:14:04','2026-09-27 19:14:04'),(214,5,'96f22fe2-46ab-474b-b457-16ad0b8a3b1b','tablet','Safari','iPadOS / Android Tablet',35,1627,522,1,1,'2026-09-24 06:37:44','2026-09-24 06:40:20','2026-09-27 19:14:04','2026-09-27 19:14:04'),(215,5,'0fed0d91-16a6-44a1-85d2-4c9847c1fca8','desktop','Firefox','Windows / macOS',57,1379,661,1,1,'2026-09-25 09:24:06','2026-09-25 09:24:58','2026-09-27 19:14:04','2026-09-27 19:14:04'),(216,5,'b73f32c3-dbd0-4eaf-900b-8e2e08e55f7a','desktop','Chrome','Windows / macOS',56,1783,654,1,1,'2026-09-20 22:24:55','2026-09-20 22:27:50','2026-09-27 19:14:04','2026-09-27 19:14:04'),(217,5,'cb2f2c33-a731-44f3-822d-112c9624e93a','mobile','Chrome','Android / iOS',38,2097,718,1,1,'2026-09-20 15:39:10','2026-09-20 15:40:05','2026-09-27 19:14:04','2026-09-27 19:14:04'),(218,5,'342f5a55-a95a-41cb-9027-c16b792c7d64','mobile','Safari','Android / iOS',36,1664,683,1,0,'2026-09-18 14:30:48','2026-09-18 14:33:51','2026-09-27 19:14:04','2026-09-27 19:14:04'),(219,5,'d90d2ccb-77b5-4f40-8b27-3594ad6ec333','mobile','Chrome','Android / iOS',42,2344,522,1,1,'2026-09-20 21:37:19','2026-09-20 21:41:25','2026-09-27 19:14:04','2026-09-27 19:14:04'),(220,5,'e82b14ad-982f-41bb-b27c-c89ceee798e6','tablet','Chrome','iPadOS / Android Tablet',34,1350,712,1,1,'2026-09-14 16:17:50','2026-09-14 16:20:08','2026-09-27 19:14:05','2026-09-27 19:14:05'),(221,5,'362cfd63-26c7-4ea8-889b-ff94bbdf0edc','desktop','Chrome','Windows / macOS',54,1765,503,1,1,'2026-09-14 14:43:42','2026-09-14 14:44:48','2026-09-27 19:14:05','2026-09-27 19:14:05'),(222,5,'5166499a-e4f2-4e13-9d71-f0cbe59d2ab7','mobile','Chrome','Android / iOS',47,2181,698,1,1,'2026-09-24 13:05:01','2026-09-24 13:07:21','2026-09-27 19:14:05','2026-09-27 19:14:05'),(223,5,'421d592a-3b70-4698-b561-f55631a7d359','desktop','Chrome','Windows / macOS',57,1963,702,1,1,'2026-09-25 11:40:57','2026-09-25 11:45:26','2026-09-27 19:14:05','2026-09-27 19:14:05'),(224,5,'b36eef81-e224-4c7a-a639-4cf52b742ae9','mobile','Chrome','Android / iOS',40,2196,668,1,1,'2026-09-23 03:38:08','2026-09-23 03:40:30','2026-09-27 19:14:05','2026-09-27 19:14:05');
/*!40000 ALTER TABLE `tour_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','tourist') NOT NULL DEFAULT 'tourist',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@tour.ng',NULL,'$2y$12$hjqSUnSmgbCvPF1dQ2UFqOwMZkYFT3/6lnNWf8vFQDxaVeg0i..He','admin',NULL,'2026-09-27 09:31:23','2026-09-27 09:31:23');
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

-- Dump completed on 2026-11-15 19:03:19
