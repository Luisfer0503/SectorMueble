-- MySQL dump 10.13  Distrib 9.1.0, for Win64 (x86_64)
--
-- Host: localhost    Database: sectormueble
-- ------------------------------------------------------
-- Server version	9.1.0

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
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
-- Table structure for table `cupones`
--

DROP TABLE IF EXISTS `cupones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cupones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cupones_codigo_unique` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cupones`
--

LOCK TABLES `cupones` WRITE;
/*!40000 ALTER TABLE `cupones` DISABLE KEYS */;
INSERT INTO `cupones` VALUES (1,'MUEBLE10','porcentaje',10.00,1,'2026-07-10 04:09:01','2026-07-10 04:09:01'),(2,'BIENVENIDA500','fijo',500.00,1,'2026-07-10 04:09:01','2026-07-10 04:09:01'),(3,'ENVIORULETA','fijo',0.00,1,'2026-08-20 22:41:56','2026-08-20 22:41:56'),(4,'RULETA15','porcentaje',15.00,1,'2026-08-20 23:32:15','2026-08-20 23:32:15'),(5,'RULETA500','fijo',500.00,1,'2026-08-21 00:09:14','2026-08-21 00:09:14');
/*!40000 ALTER TABLE `cupones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles_pedido`
--

DROP TABLE IF EXISTS `detalles_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalles_pedido` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned DEFAULT NULL,
  `nombre_producto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalles_pedido_pedido_id_foreign` (`pedido_id`),
  KEY `detalles_pedido_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalles_pedido_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalles_pedido_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles_pedido`
--

LOCK TABLES `detalles_pedido` WRITE;
/*!40000 ALTER TABLE `detalles_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalles_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_09_000001_create_productos_table',1),(5,'2026_07_09_000002_create_pedidos_table',1),(6,'2026_07_09_000003_create_detalles_pedido_table',1),(7,'2026_07_09_000004_create_cupones_table',1),(8,'2026_07_09_000005_create_ruleta_opciones_table',2),(9,'2026_07_09_000006_add_colores_to_productos_table',3),(10,'2026_08_20_000001_add_ruleta_fields_to_users_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned DEFAULT NULL,
  `nombre_cliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo_cliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_cliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion_envio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `cupon_codigo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedidos_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `pedidos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL,
  `calificacion` decimal(2,1) NOT NULL DEFAULT '5.0',
  `destacado` tinyint(1) NOT NULL DEFAULT '0',
  `colores` json DEFAULT NULL,
  `porcentaje_descuento` tinyint unsigned DEFAULT NULL,
  `precio_descuento` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (13,'Kattia - Tapizada','kattia-tapizada','Silla Dimensiones: Ancho: 55.0 cm, Alto: 90.0 cm, Profundidad: 60.0 cm. Materiales / Acabados disponibles: Tabatex Liquid Otter, SAMAR Dylan Latte, SAMAR Napoli Oxford, SAMAR Napoli Sand. Proveedor: CASA TAPIER.',3400.00,'/storage/productos/1786643137_logo1.png','Sillas y Bancos',10,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:45:37'),(14,'Serrano - De Madera','serrano-de-madera','Silla Dimensiones: Ancho: 60.0 cm, Alto: 70.0 cm, Profundidad: 50.0 cm. Materiales / Acabados disponibles: Tabatex Roma Steel, Tabatex Liquid Wood, Tabatex Dylan Cream, Tabatex Dylan Evony, SAMAR Napoli Olivo. Proveedor: CASA TAPIER.',3650.00,'/storage/productos/mueble_tapi_row17.png','Sillas y Bancos',11,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(15,'Monna - De Madera','monna-de-madera','Silla Dimensiones: Ancho: 58.0 cm, Alto: 70.0 cm, Profundidad: 50.0 cm. Materiales / Acabados disponibles: Tabatex Roma Oyster, Tabatex Dylan Chocolate, SAMAR Flip Flop Silver, SAMAR Dylan Latte, SAMAR Napoli Oxford. Proveedor: CASA TAPIER.',3900.00,'/storage/productos/mueble_tapi_row18.png','Sillas y Bancos',12,4.7,0,NULL,15,3315.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(16,'Tina - De Madera','tina-de-madera','Silla Dimensiones: Ancho: 45.0 cm, Alto: 70.0 cm, Profundidad: 50.0 cm. Materiales / Acabados disponibles: Tabatex Roma Oyster, Tabatex Mondra Taupe, SAMAR Napoli Olivo, Madera de pino entonada- Blanco Medio, Madera de pino entonada- Nogal Medio 1. Proveedor: CASA TAPIER.',4150.00,'/storage/productos/mueble_tapi_row19.png','Sillas y Bancos',13,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(17,'Torento - Tapizada','torento-tapizada','Silla Dimensiones: Ancho: 64.0 cm, Alto: 84.0 cm, Profundidad: 64.0 cm. Materiales / Acabados disponibles: Tabatex Roma Steel, Tabatex Dylan Chocolate, SAMAR Dylan Latte, SAMAR Napoli Marino. Proveedor: CASA TAPIER.',4400.00,'/storage/productos/mueble_tapi_row20.png','Sillas y Bancos',14,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(18,'Padua - Herrería Y Tapizado','padua-herreria-y-tapizado','Silla Dimensiones: Ancho: 55.0 cm, Alto: 70.0 cm, Profundidad: 55.0 cm. Materiales / Acabados disponibles: Tabatex Roma Oyster, Tabatex Liquid Silver, Tabatex Dylan Evony. Proveedor: CASA TAPIER.',4650.00,'/storage/productos/mueble_tapi_row21.png','Sillas y Bancos',15,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(19,'Bimba - Tapizado','bimba-tapizado','Sillón Comedor Dimensiones: Ancho: 65.0 cm, Alto: 78.0 cm, Profundidad: 65.0 cm. Materiales / Acabados disponibles: Tabatex Liquid Wood, Tabatex Dylan Cream, SAMAR Napoli Oxford, SAMAR Napoli Black, Madera de pino entonada- Nogal Medio 1. Proveedor: CASA TAPIER.',15400.00,'/storage/productos/mueble_tapi_row22.png','Comedor',16,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(20,'Olvera - Tapizado','olvera-tapizado','Sillón Dimensiones: Ancho: 70.0 cm, Alto: 70.0 cm, Profundidad: 75.0 cm. Materiales / Acabados disponibles: Tabatex Germany Moka, Tabatex Germany Brick, Tabatex Germany Olive, Tabatex Mondra Coffe, SAMAR Flip Flop Silver. Proveedor: CASA TAPIER.',8500.00,'/storage/productos/mueble_tapi_row23.png','Salón',17,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(21,'Palermo - Modudlar','palermo-modudlar','Sofa Dimensiones: Ancho: 255.0 cm, Alto: 80.0 cm, Profundidad: 95.0 cm. Materiales / Acabados disponibles: Tabatex Roma Oyster, Tabatex Roma Steel, Tabatex Liquid Ebony. Proveedor: CASA TAPIER.',17050.00,'/storage/productos/mueble_tapi_row24.png','Salón',18,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(22,'Zenit - Tapizada','zenit-tapizada','Banca Dimensiones: Ancho: 142.0 cm, Alto: 44.0 cm, Profundidad: 42.0 cm. Materiales / Acabados disponibles: Tabatex Sakura Midnight, Tabatex Sakura Moleskin, Tabatex Germany Navy, Tabatex Roma Latte, Tabatex Liquid Olivo. Proveedor: CASA TAPIER.',5700.00,'/storage/productos/mueble_tapi_row25.png','Sillas y Bancos',19,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(23,'West - Sillon Ind. Angosto','west-sillon-ind-angosto','Sala Modular Dimensiones: Ancho: 75.0 cm, Alto: 72.5 cm, Profundidad: 100.0 cm. Materiales / Acabados disponibles: Tabatex Liquid Silver, SAMAR Napoli Oxford, SAMAR Napoli Black. Proveedor: CASA TAPIER.',25250.00,'/storage/productos/mueble_tapi_row26.png','Salón',20,4.9,0,NULL,15,21462.50,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(24,'West - Sillon Ind. Ancho','west-sillon-ind-ancho','Sala Modular Dimensiones: Ancho: 100.0 cm, Alto: 72.5 cm, Profundidad: 100.0 cm. Proveedor: CASA TAPIER.',25500.00,'/storage/productos/mueble_tapi_row26.png','Salón',21,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(25,'West - Love Seat','west-love-seat','Sala Modular Dimensiones: Ancho: 125.0 cm, Alto: 72.5 cm, Profundidad: 100.0 cm. Proveedor: CASA TAPIER.',25750.00,'/storage/productos/mueble_tapi_row26.png','Salón',22,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(26,'West - Chaice','west-chaice','Sala Modular Dimensiones: Ancho: 100.0 cm, Alto: 72.5 cm, Profundidad: 145.0 cm. Proveedor: CASA TAPIER.',26000.00,'/storage/productos/mueble_tapi_row26.png','Salón',23,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(27,'West - Esquinero','west-esquinero','Sala Modular Dimensiones: Ancho: 100.0 cm, Alto: 72.5 cm, Profundidad: 100.0 cm. Proveedor: CASA TAPIER.',24500.00,'/storage/productos/mueble_tapi_row26.png','Salón',24,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(28,'West - Taburete Cuadrado Grande','west-taburete-cuadrado-grande','Sala Modular Dimensiones: Ancho: 100.0 cm, Alto: 42.5 cm, Profundidad: 100.0 cm. Proveedor: CASA TAPIER.',4050.00,'/storage/productos/mueble_tapi_row26.png','Sillas y Bancos',10,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(29,'West - Taburete Rectangular Grande','west-taburete-rectangular-grande','Sala Modular Dimensiones: Ancho: 50.0 cm, Alto: 42.5 cm, Profundidad: 100.0 cm. Proveedor: CASA TAPIER.',4300.00,'/storage/productos/mueble_tapi_row26.png','Sillas y Bancos',11,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(30,'West - Taburete Cuadrado Mediano','west-taburete-cuadrado-mediano','Sala Modular Dimensiones: Ancho: 75.0 cm, Alto: 42.5 cm, Profundidad: 75.0 cm. Proveedor: CASA TAPIER.',4550.00,'/storage/productos/mueble_tapi_row26.png','Sillas y Bancos',12,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(31,'West - Brazos','west-brazos','Sala Modular Dimensiones: Ancho: 25.0 cm, Alto: 60.0 cm, Profundidad: 80.0 cm. Proveedor: CASA TAPIER.',25500.00,'/storage/productos/mueble_tapi_row26.png','Salón',13,4.5,1,NULL,15,21675.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(32,'Virgo - Taburete Cuadrado','virgo-taburete-cuadrado','Sala Modular Dimensiones: Ancho: 100.0 cm, Alto: 45.0 cm, Profundidad: 100.0 cm. Materiales / Acabados disponibles: Tabatex Roma Latte, Tabatex Roma Steel, Tabatex Liquid Silver, Tabatex Liquid Beige, Tabatex Liquid Navy. Proveedor: CASA TAPIER.',5050.00,'/storage/productos/mueble_tapi_row35.png','Sillas y Bancos',14,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(33,'Virgo - Taburete Rectangular','virgo-taburete-rectangular','Sala Modular Dimensiones: Ancho: 180.0 cm, Alto: 45.0 cm, Profundidad: 100.0 cm. Proveedor: CASA TAPIER.',5300.00,'/storage/productos/mueble_tapi_row35.png','Sillas y Bancos',15,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(34,'Virgo - Esquinero','virgo-esquinero','Sala Modular Dimensiones: Ancho: 135.0 cm, Alto: 45.0 cm, Profundidad: 135.0 cm. Proveedor: CASA TAPIER.',24500.00,'/storage/productos/mueble_tapi_row35.png','Salón',16,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(35,'Virgo - Respaldo','virgo-respaldo','Sala Modular Dimensiones: Ancho: 80.0 cm, Alto: 35.0 cm, Profundidad: 35.0 cm. Proveedor: CASA TAPIER.',24750.00,'/storage/productos/mueble_tapi_row35.png','Salón',17,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(36,'Lucier - Tapizada','lucier-tapizada','Recamara Dimensiones: Ancho: 214.0 cm, Alto: 110.0 cm, Profundidad: 210.0 cm. Materiales / Acabados disponibles: Tabatex Dylan Chocolate, SAMAR Napoli Oxford, SAMAR Napoli Sand. Proveedor: CASA TAPIER.',22400.00,'/storage/productos/mueble_tapi_row39.png','Dormitorio',18,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(37,'Aura - Tapizada','aura-tapizada','Recamara Dimensiones: Ancho: 230.0 cm, Alto: 130.0 cm, Profundidad: 218.0 cm. Materiales / Acabados disponibles: Tabatex Germany Ivory, Tabatex Germany Navy, Tabatex Germany Olive, Tabatex Liquid Wood, Tabatex Liquid Rose. Proveedor: CASA TAPIER.',22650.00,'/storage/productos/mueble_tapi_row40.png','Dormitorio',19,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(38,'Isabella - Tapizada','isabella-tapizada','Recamara Dimensiones: Ancho: 214.0 cm, Alto: 110.0 cm, Profundidad: 210.0 cm. Materiales / Acabados disponibles: Tabatex Liquid Silver, Tabatex Liquid Beige, Tabatex Mondra Taupe. Proveedor: CASA TAPIER.',22900.00,'/storage/productos/mueble_tapi_row41.png','Dormitorio',20,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(39,'Duna - Tapizada','duna-tapizada','Recamara Dimensiones: Ancho: 214.0 cm, Alto: 110.0 cm, Profundidad: 210.0 cm. Materiales / Acabados disponibles: Tabatex Liquid Wood, Tabatex Liquid Olivo, Tabatex Napoli Marino, Tabatex Liquid Otter. Proveedor: CASA TAPIER.',23150.00,'/storage/productos/mueble_tapi_row42.png','Dormitorio',21,4.7,0,NULL,15,19677.50,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(40,'Luna - Tapizada','luna-tapizada','Recamara Dimensiones: Ancho: 214.0 cm, Alto: 110.0 cm, Profundidad: 210.0 cm. Materiales / Acabados disponibles: Tabatex Germany Ivory, Tabatex Germany Olive, Tabatex Liquid Otter, Tabatex Mondra Coffe. Proveedor: CASA TAPIER.',23400.00,'/storage/productos/mueble_tapi_row43.png','Dormitorio',22,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(41,'Pistacho - Tapizada','pistacho-tapizada','Cama Dimensiones: Ancho: 214.0 cm, Alto: 120.0 cm, Profundidad: 220.0 cm. Materiales / Acabados disponibles: Tabatex Sakura Moleskin, Tabatex Germany Brick, SAMAR Flip Flop Silver, SAMAR Napoli Sand. Proveedor: SAMAR MUEBLES.',13500.00,'/storage/productos/mueble_tapi_row44.png','Dormitorio',23,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(42,'Molina - Madera Sólida','molina-madera-solida','Cama Dimensiones: Ancho: 190.0 cm, Alto: 87.0 cm, Profundidad: 210.0 cm. Materiales / Acabados disponibles: Madera de pino entonada- Nogal Medio 1, Madera de pino entonada- Nogal Oscuro. Proveedor: CASA TAPIER.',13750.00,'/storage/productos/mueble_tapi_row45.png','Dormitorio',24,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(43,'Patricio - Tapizado','patricio-tapizado','Taburete Dimensiones: Ancho: 67.0 cm, Alto: 51.5 cm, Profundidad: 58.0 cm. Materiales / Acabados disponibles: Tabatex Sakura Steel, Tabatex Germany Moka, Tabatex Mondra Coffe, SAMAR Flip Flop Latte, SAMAR Napoli Olivo. Proveedor: CASA TAPIER.',4300.00,'/storage/productos/mueble_tapi_row46.png','Sillas y Bancos',10,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(44,'Torres - Asiento Tapizado Base Madera','torres-asiento-tapizado-base-madera','Taburete Dimensiones: Ancho: 55.0 cm, Alto: 50.0 cm, Profundidad: 55.0 cm. Materiales / Acabados disponibles: Tabatex Sakura Midnight, Tabatex Germany Brick, Tabatex Liquid Ebony, SAMAR Flip Flop Latte, SAMAR Napoli Marino. Proveedor: CASA TAPIER.',4550.00,'/storage/productos/mueble_tapi_row47.png','Sillas y Bancos',11,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(45,'Del Rio - Tapizado Base Madera','del-rio-tapizado-base-madera','Taburete Dimensiones: Ancho: 45.0 cm, Alto: 50.0 cm, Profundidad: 45.0 cm. Materiales / Acabados disponibles: Tabatex Germany Silver, Tabatex Germany Moka, Tabatex Germany Olive, SAMAR Flip Flop Latte, Madera de pino entonada- Blanco Medio. Proveedor: CASA TAPIER.',4800.00,'/storage/productos/mueble_tapi_row48.png','Sillas y Bancos',12,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(46,'Del Rio','del-rio','Taburete Dimensiones: Ancho: ↔ (Ancho) cm, Alto: ↕ (Altura) cm, Profundidad: ↗ (Prof) cm. Materiales / Acabados disponibles: MATERIAL 2, MATERIAL 3, MATERIAL 4, MATERIAL 5, MATERIAL 6. Proveedor: CASA TAPIER.',5050.00,'/storage/productos/mueble_tapi_row48.png','Sillas y Bancos',13,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(47,'Risotto - Melamina','risotto-melamina','Buró Dimensiones: Ancho: 55.0 cm, Alto: 45.0 cm, Profundidad: 45.0 cm. Materiales / Acabados disponibles: Melamina Arauco Copal, Melamina Arauco Malta, Melamina Arauco Durango, Melamina Arauco Wengué. Proveedor: CASA TAPIER.',6300.00,'/storage/productos/mueble_tapi_row51.png','Dormitorio',14,4.9,0,NULL,15,5355.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(48,'Risotto - Con Cubierta De Mármol','risotto-con-cubierta-de-marmol','Buró Dimensiones: Ancho: 55.0 cm, Alto: 45.0 cm, Profundidad: 45.0 cm. Materiales / Acabados disponibles: Melamina Arauco Copal, Melamina Arauco Malta, Melamina Arauco Durango, Melamina Arauco Wengué, Mármol Travertino Blanco. Proveedor: CASA TAPIER.',4800.00,'/storage/productos/mueble_tapi_row52.png','Dormitorio',15,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(49,'Domingo - Melamina','domingo-melamina','Buró Dimensiones: Ancho: 45.0 cm, Alto: 50.0 cm, Profundidad: 40.0 cm. Materiales / Acabados disponibles: Melamina Arauco Copal, Melamina Arauco Malta, Melamina Arauco Durango, Melamina Arauco Wengué. Proveedor: CASA TAPIER.',5050.00,'/storage/productos/mueble_tapi_row53.png','Dormitorio',16,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(50,'Noruega - Mármol','noruega-marmol','Buró Dimensiones: Ancho: 45.0 cm, Alto: 50.0 cm, Profundidad: 45.0 cm. Materiales / Acabados disponibles: Mármol Travertino Blanco, Mármol Querétaro Negro, Mármol Carrara, Pdte. cotización. Proveedor: CASA TAPIER.',5300.00,'/storage/productos/mueble_tapi_row54.png','Dormitorio',17,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(51,'Suecia - Mármol','suecia-marmol','Buró Dimensiones: Ancho: 45.0 cm, Alto: 50.0 cm, Profundidad: 45.0 cm. Materiales / Acabados disponibles: Mármol Travertino Blanco, Mármol Querétaro Negro, Mármol Carrara, Pdte. cotización. Proveedor: CASA TAPIER.',5550.00,'/storage/productos/mueble_tapi_row55.png','Dormitorio',18,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(52,'Romina - Mármol','romina-marmol','Recibidor Dimensiones: Ancho: 80.0 cm, Alto: 85.0 cm, Profundidad: 25.0 cm. Materiales / Acabados disponibles: Mármol Travertino Blanco, Mármol Querétaro Negro, Mármol Carrara, Pdte. cotización. Proveedor: CASA TAPIER.',9900.00,'/storage/productos/mueble_tapi_row56.png','Muebles Auxiliares',19,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(53,'Julia - Pedestal','julia-pedestal','Buró/Mesa auxiliar Dimensiones: Ancho: 40.0 cm, Alto: 55.0 cm, Profundidad: 40.0 cm. Materiales / Acabados disponibles: Mármol Travertino Blanco, Mármol Querétaro Negro, Mármol Carrara, Pdte. cotización. Proveedor: CASA TAPIER.',15150.00,'/storage/productos/mueble_tapi_row57.png','Comedor',20,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(54,'Pino - Pedestal','pino-pedestal','Mesa de comedor Materiales / Acabados disponibles: Pdte. cotización cubierta. Proveedor: CASA TAPIER.',15400.00,'/storage/productos/mueble_tapi_row58.png','Comedor',21,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(55,'Diandra - Pedestal','diandra-pedestal','Mesa de comedor Proveedor: CASA TAPIER.',13900.00,'/storage/productos/mueble_tapi_row59.png','Comedor',22,4.5,1,NULL,15,11815.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(56,'Nina - Pedestal','nina-pedestal','Mesa de comedor Proveedor: CASA TAPIER.',14150.00,'/storage/productos/mueble_tapi_row60.png','Comedor',23,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(57,'Otoño - Pedestal','otono-pedestal','Mesa de comedor Proveedor: CASA TAPIER.',14400.00,'/storage/productos/mueble_tapi_row61.png','Comedor',24,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(58,'Romero - Pedestal','romero-pedestal','Mesa de comedor Proveedor: CASA TAPIER.',14650.00,'/storage/productos/mueble_tapi_row62.png','Comedor',10,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(59,'Carol - Pedestal','carol-pedestal','Mesa de comedor Proveedor: CASA TAPIER.',14900.00,'/storage/productos/mueble_tapi_row63.png','Comedor',11,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(60,'Sao Paulo - Cubierta Redonda 4 Personas','sao-paulo-cubierta-redonda-4-personas','Mesa de comedor Proveedor: CASA TAPIER.',12450.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',12,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(61,'Sao Paulo - Cubierta Redonda 6 Personas','sao-paulo-cubierta-redonda-6-personas','Mesa de comedor Proveedor: CASA TAPIER.',16000.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',13,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(62,'Sao Paulo - Cubierta Redonda 8 Personas','sao-paulo-cubierta-redonda-8-personas','Mesa de comedor Proveedor: CASA TAPIER.',18900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',14,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(63,'Atenas - Cubierta Cuadrada 4 Personas','atenas-cubierta-cuadrada-4-personas','Mesa de comedor Proveedor: CASA TAPIER.',11450.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',15,4.7,0,NULL,15,9732.50,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(64,'Atenas - Cubierta Cuadrada 8 Personas','atenas-cubierta-cuadrada-8-personas','Mesa de comedor Proveedor: CASA TAPIER.',19400.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',16,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(65,'Santiago - Cubierta Rectangular 6 Personas','santiago-cubierta-rectangular-6-personas','Mesa de comedor Proveedor: CASA TAPIER.',15250.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',17,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(66,'Santiago - Cubierta Rectangular 8 Personas','santiago-cubierta-rectangular-8-personas','Mesa de comedor Proveedor: CASA TAPIER.',19900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',18,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(67,'Oslo','oslo','Mesa de comedor Proveedor: CASA TAPIER.',15150.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',19,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(68,'Budapest','budapest','Mesa de comedor Proveedor: CASA TAPIER.',15400.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',20,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(69,'Budapest','budapest-1','Mesa de comedor Proveedor: CASA TAPIER.',13900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',21,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(70,'Budapest','budapest-2','Mesa de comedor Proveedor: CASA TAPIER.',14150.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',22,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(71,'Budapest','budapest-3','Mesa de comedor Proveedor: CASA TAPIER.',14400.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',23,4.9,0,NULL,15,12240.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(72,'Budapest','budapest-4','Mesa de comedor Proveedor: CASA TAPIER.',14650.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',24,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(73,'Budapest','budapest-5','Mesa de comedor Proveedor: CASA TAPIER.',14900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',10,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(74,'Budapest','budapest-6','Mesa de comedor Proveedor: CASA TAPIER.',15150.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',11,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(75,'Budapest','budapest-7','Mesa de comedor Proveedor: CASA TAPIER.',15400.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',12,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(76,'Budapest','budapest-8','Mesa de comedor Proveedor: CASA TAPIER.',13900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',13,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(77,'Budapest','budapest-9','Mesa de comedor Proveedor: CASA TAPIER.',14150.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',14,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(78,'Budapest','budapest-10','Mesa de comedor Proveedor: CASA TAPIER.',14400.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',15,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(79,'Budapest','budapest-11','Mesa de comedor Proveedor: CASA TAPIER.',14650.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',16,4.5,1,NULL,15,12452.50,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(80,'Budapest','budapest-12','Mesa de comedor Proveedor: CASA TAPIER.',14900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',17,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(81,'Budapest','budapest-13','Mesa de comedor Proveedor: CASA TAPIER.',15150.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',18,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(82,'Tokio','tokio','Mesa de comedor Proveedor: CASA TAPIER.',15400.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',19,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(83,'Denver','denver','Mesa de comedor Proveedor: CASA TAPIER.',13900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',20,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(84,'Moscou','moscou','Mesa de comedor Proveedor: CASA TAPIER.',14150.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',21,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(85,'Milán','milan','Mesa de comedor Proveedor: CASA TAPIER.',14400.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',22,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(86,'Nápoles','napoles','Mesa de comedor Proveedor: CASA TAPIER.',14650.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',23,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(87,'Lisboa','lisboa','Mesa de comedor Proveedor: CASA TAPIER.',14900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',24,4.7,0,NULL,15,12665.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(88,'Turín','turin','Mesa de comedor Proveedor: CASA TAPIER.',15150.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',10,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(89,'Osaka','osaka','Mesa de comedor Proveedor: CASA TAPIER.',15400.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',11,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(90,'Moringo','moringo','Mesa de comedor Proveedor: CASA TAPIER.',13900.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',12,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(91,'Berlín','berlin','Mesa de comedor Proveedor: CASA TAPIER.',14150.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Comedor',13,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(92,'Sillón','sillon','Sillón Dimensiones: Ancho: 55.0 cm, Alto: 90.0 cm, Profundidad: 60.0 cm. Materiales / Acabados disponibles: Tabatex Liquid Otter, SAMAR Dylan Latte, SAMAR Napoli Oxford, SAMAR Napoli Sand. Proveedor: CASA TAPIER.',9000.00,'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800','Salón',14,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(93,'Lucca - Sofá','lucca-sofa','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 199.0 cm, Alto: 88.0 cm, Profundidad: 167.0 cm. Materiales / Acabados disponibles: Tabatex Roma Steel, Tabatex Liquid Beige, Tabatex Liquid Ebony. Proveedor: SAMAR MUEBLES.',17550.00,'/storage/productos/mueble_sama_row16.jpg','Salón',15,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(94,'Cataluña - Sofá','cataluna-sofa','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 237.0 cm, Alto: 97.0 cm, Profundidad: 93.0 cm. Materiales / Acabados disponibles: SAMAR Napoli Oxford, SAMAR Napoli Sand, SAMAR Napoli Marino, SAMAR Napoli Black. Proveedor: SAMAR MUEBLES.',17800.00,'/storage/productos/mueble_sama_row17.png','Salón',16,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(95,'Cataluña - Loveseat','cataluna-loveseat','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 180.0 cm, Alto: 97.0 cm, Profundidad: 93.0 cm. Proveedor: SAMAR MUEBLES.',14150.00,'/storage/productos/mueble_sama_row17.png','Salón',17,4.9,0,NULL,15,12027.50,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(96,'Cataluña - Sillón','cataluna-sillon','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 130.0 cm, Alto: 97.0 cm, Profundidad: 93.0 cm. Proveedor: SAMAR MUEBLES.',10000.00,'/storage/productos/mueble_sama_row17.png','Salón',18,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(97,'Samoa - Esquinero','samoa-esquinero','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 90.0 cm, Alto: 75.0 cm, Profundidad: 90.0 cm. Materiales / Acabados disponibles: SAMAR Napoli Oxford, SAMAR Napoli Sand, SAMAR Napoli Marino, SAMAR Napoli Black. Proveedor: SAMAR MUEBLES.',24500.00,'/storage/productos/mueble_sama_row20.jpg','Salón',19,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(98,'Samoa - Sillón','samoa-sillon','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 90.0 cm, Alto: 73.0 cm, Profundidad: 90.0 cm. Proveedor: SAMAR MUEBLES.',8750.00,'/storage/productos/mueble_sama_row20.jpg','Salón',20,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(99,'Samoa - Taburete','samoa-taburete','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 90.0 cm, Alto: 45.0 cm, Profundidad: 90.0 cm. Proveedor: SAMAR MUEBLES.',4300.00,'/storage/productos/mueble_sama_row20.jpg','Sillas y Bancos',21,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(100,'Trevere - Loveseat','trevere-loveseat','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 195.0 cm, Alto: 97.0 cm, Profundidad: 93.0 cm. Materiales / Acabados disponibles: Tabatex Dylan Chocolate, Tabatex Dylan Evony, SAMAR Napoli Sand, SAMAR Napoli Black. Proveedor: SAMAR MUEBLES.',13650.00,'/storage/productos/mueble_sama_row23.jpg','Salón',22,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(101,'Sala Modular Fernanda','sala-modular-fernanda','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: CHAISE  : 85  CM X 80 CM X  160  CM                   LOVESEAT: 170 CM X  80 CM X 85 CM            ESQUINERO : 85 CM X 80CM  X 85  CM   TABURETE 75 CM X 45CM X 85 CM cm. Proveedor: SAMAR MUEBLES.',15500.00,'/storage/productos/mueble_sama_row24.jpg','Salón',23,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(102,'Edimburgo - Loveseat','edimburgo-loveseat','Sala modular reclinable fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 135.0 cm, Alto: 90.0 cm, Profundidad: 87.0 cm. Materiales / Acabados disponibles: Tabatex Dylan Chocolate, Tabatex Dylan Evony, SAMAR Napoli Sand, SAMAR Napoli Black. Proveedor: SAMAR MUEBLES.',25750.00,'/storage/productos/mueble_sama_row25.jpg','Salón',24,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(103,'Edimburgo - Esquinero','edimburgo-esquinero','Sala modular reclinable fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 104.0 cm, Alto: 90.0 cm, Profundidad: 87.0 cm. Proveedor: SAMAR MUEBLES.',26000.00,'/storage/productos/mueble_sama_row25.jpg','Salón',10,4.5,1,NULL,15,22100.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(104,'Edimburgo - Baúl','edimburgo-baul','Sala modular reclinable fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 91.0 cm, Alto: 45.0 cm, Profundidad: 90.0 cm. Proveedor: SAMAR MUEBLES.',24500.00,'/storage/productos/mueble_sama_row25.jpg','Salón',11,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(105,'Milán - Sofá','milan-sofa','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 220.0 cm, Alto: 100.0 cm, Profundidad: 70.0 cm. Materiales / Acabados disponibles: Tabatex Germany Silver, Tabatex Roma Oyster, Tabatex Liquid Wood, Tabatex Liquid Olivo, SAMAR Napoli Oxford. Proveedor: SAMAR MUEBLES.',17050.00,'/storage/productos/mueble_sama_row28.jpg','Salón',12,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(106,'Sillon Morrocoy - Sillón','sillon-morrocoy-sillon','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 85.0 cm, Alto: 83.0 cm, Profundidad: 83.0 cm. Materiales / Acabados disponibles: Tabatex Germany Silver, Tabatex Roma Oyster, Tabatex Liquid Wood, Tabatex Liquid Olivo, SAMAR Napoli Oxford. Proveedor: SAMAR MUEBLES.',9000.00,'/storage/productos/mueble_sama_row29.png','Salón',13,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(107,'Sala Venecia - Loveseat','sala-venecia-loveseat','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 168.0 cm, Alto: 92.0 cm, Profundidad: 83.0 cm. Materiales / Acabados disponibles: Tabatex Dylan Cream, Tabatex Dylan Chocolate, Tabatex Dylan Evony. Proveedor: SAMAR MUEBLES.',13650.00,'/storage/productos/mueble_sama_row30.png','Salón',14,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(108,'Sala Venecia - Sillón','sala-venecia-sillon','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 120.0 cm, Alto: 92.0 cm, Profundidad: 83.0 cm. Proveedor: SAMAR MUEBLES.',9500.00,'/storage/productos/mueble_sama_row30.png','Salón',15,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(109,'Sala Venecia - Sofá','sala-venecia-sofa','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 220.0 cm, Alto: 92.0 cm, Profundidad: 83.0 cm. Proveedor: SAMAR MUEBLES.',18050.00,'/storage/productos/mueble_sama_row30.png','Salón',16,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(110,'Sala Concherto - Loveseat','sala-concherto-loveseat','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 180.0 cm, Alto: 102.0 cm, Profundidad: 90.0 cm. Materiales / Acabados disponibles: Tabatex Dylan Cream, Tabatex Dylan Chocolate, Tabatex Dylan Evony. Proveedor: SAMAR MUEBLES.',14400.00,'/storage/productos/mueble_sama_row33.png','Salón',17,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(111,'Sala Concherto - Sillón','sala-concherto-sillon','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 125.0 cm, Alto: 102.0 cm, Profundidad: 90.0 cm. Proveedor: SAMAR MUEBLES.',8500.00,'/storage/productos/mueble_sama_row33.png','Salón',18,4.7,0,NULL,15,7225.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(112,'Sala Concherto - Sofá','sala-concherto-sofa','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 240.0 cm, Alto: 102.0 cm, Profundidad: 90.0 cm. Proveedor: SAMAR MUEBLES.',17050.00,'/storage/productos/mueble_sama_row33.png','Salón',19,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(113,'Yorkshire - Sofa','yorkshire-sofa','Sillón reclinable fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 218.0 cm, Alto: 97.0 cm, Profundidad: 93.0 cm. Materiales / Acabados disponibles: Tabatex Dylan Cream, Tabatex Dylan Chocolate, Tabatex Dylan Evony, SAMAR Napoli Black. Proveedor: SAMAR MUEBLES.',17300.00,'/storage/productos/mueble_sama_row36.png','Salón',20,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(114,'Yorkshire - Loveseat','yorkshire-loveseat','Sillón reclinable fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 167.0 cm, Alto: 97.0 cm, Profundidad: 93.0 cm. Proveedor: SAMAR MUEBLES.',13650.00,'/storage/productos/mueble_sama_row36.png','Salón',21,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(115,'Yorkshire - Sillon','yorkshire-sillon','Sillón reclinable fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 120.0 cm, Alto: 97.0 cm, Profundidad: 93.0 cm. Proveedor: SAMAR MUEBLES.',9500.00,'/storage/productos/mueble_sama_row36.png','Salón',22,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(116,'Bombay - Sofá Cama','bombay-sofa-cama','Sofá cama tamaño matrimonial Dimensiones: Ancho: 190.0 cm, Alto: 170.0 cm, Profundidad: 90.0 cm. Materiales / Acabados disponibles: Tabatex Germany Ivory, Tabatex Roma Steel, Tabatex Dylan Evony, SAMAR Napoli Sand, SAMAR Napoli Marino. Proveedor: SAMAR MUEBLES.',14750.00,'/storage/productos/mueble_sama_row39.png','Dormitorio',23,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(117,'Recámara Atenea','recamara-atenea','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: Base: 215 CM X 30 CM  2.05CM\r\nCABECERA : 205CM X 1.20 CM X25 CM cm. Proveedor: SAMAR MUEBLES.',16000.00,'/storage/productos/mueble_sama_row40.jpg','Salón',24,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(118,'Creta Matrimonial - Base','creta-matrimonial-base','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 149.0 cm, Alto: 25.0 cm, Profundidad: 197.0 cm. Materiales / Acabados disponibles: SAMAR Napoli Oxford, SAMAR Napoli Sand, SAMAR Napoli Marino, SAMAR Napoli Olivo. Proveedor: SAMAR MUEBLES.',13500.00,'/storage/productos/mueble_sama_row41.jpg','Dormitorio',10,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(119,'Creta Matrimonial - Cabecera','creta-matrimonial-cabecera','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 145.0 cm, Alto: 120.0 cm, Profundidad: 25.0 cm. Proveedor: SAMAR MUEBLES.',8050.00,'/storage/productos/mueble_sama_row41.jpg','Dormitorio',11,4.9,0,NULL,15,6842.50,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(120,'Recámara Santorini - Base','recamara-santorini-base','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 149.0 cm, Alto: 25.0 cm, Profundidad: 197.0 cm. Materiales / Acabados disponibles: Tabatex Sakura Midnight, Tabatex Sakura Moleskin, Tabatex Mondra Curry, Tabatex Mondra Coffe. Proveedor: SAMAR MUEBLES.',14000.00,'/storage/productos/mueble_sama_row43.jpg','Dormitorio',12,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(121,'Recámara Santorini - Cabecera','recamara-santorini-cabecera','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 145.0 cm, Alto: 120.0 cm, Profundidad: 25.0 cm. Proveedor: SAMAR MUEBLES.',8550.00,'/storage/productos/mueble_sama_row43.jpg','Dormitorio',13,4.5,1,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(122,'Mikonos - Base','mikonos-base','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 149.0 cm, Alto: 25.0 cm, Profundidad: 197.0 cm. Materiales / Acabados disponibles: Tabatex Dylan Chocolate, Tabatex Dylan Evony, Tabatex Dylan Latte. Proveedor: SAMAR MUEBLES.',14500.00,'/storage/productos/mueble_sama_row45.jpg','Dormitorio',14,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(123,'Mikonos - Cabecera','mikonos-cabecera','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil pendiente por definir. Dimensiones: Ancho: 145.0 cm, Alto: 120.0 cm, Profundidad: 15.0 cm. Proveedor: SAMAR MUEBLES.',9050.00,'/storage/productos/mueble_sama_row45.jpg','Dormitorio',15,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(124,'Artemisa - Sofá','artemisa-sofa','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil  TAMPA SILVER. Dimensiones: Ancho: 190.0 cm, Alto: 83.0 cm, Profundidad: 90.0 cm. Materiales / Acabados disponibles: Tabatex Germany Ivory, Tabatex Roma Oyster, Tabatex Dylan Evony, SAMAR Napoli Black. Proveedor: SAMAR MUEBLES.',18300.00,'/storage/productos/mueble_sama_row47.jpg','Salón',16,4.8,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(125,'Marielle - Sofá','marielle-sofa','Fabricado en madera estufada de Pino, forrada de hule espuma de alta densidad y textil  FRANKFURT Dimensiones: Ancho: 170.0 cm, Alto: 81.0 cm, Profundidad: 95.0 cm. Materiales / Acabados disponibles: Tabatex Germany Ivory, Tabatex Germany Navy, Tabatex Mondra Coffe, Tabatex Dylan Cream, SAMAR Napoli Oxford. Proveedor: SAMAR MUEBLES.',16800.00,'/storage/productos/mueble_sama_row48.jpg','Salón',17,4.9,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(126,'Capri Matromonial - Base','capri-matromonial-base','Base matrimonial Capri Dimensiones: Ancho: 150.0 cm, Alto: 120.0 cm, Profundidad: 210.0 cm. Materiales / Acabados disponibles: Tabatex Sakura Moleskin, Tabatex Germany Brick, SAMAR Flip Flop Silver, SAMAR Napoli Sand. Proveedor: SAMAR MUEBLES.',13750.00,'/storage/productos/mueble_sama_row49.jpg','Dormitorio',18,5.0,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(127,'Capri Matromonial - Cabecera','capri-matromonial-cabecera','Base matrimonial Capri Proveedor: SAMAR MUEBLES.',14000.00,'/storage/productos/mueble_sama_row49.jpg','Dormitorio',19,4.5,1,NULL,15,11900.00,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(128,'Capri Base King Capri','capri-base-king-capri','Base King Tipo Capri Dimensiones: Ancho: 216.0 cm, Alto: 217.0 cm, Profundidad: 210.0 cm. Materiales / Acabados disponibles: Tabatex Sakura Moleskin, Tabatex Germany Brick, SAMAR Flip Flop Silver, SAMAR Napoli Sand. Proveedor: SAMAR MUEBLES.',14250.00,'/storage/productos/mueble_sama_row51.jpg','Dormitorio',20,4.6,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10'),(129,'Capri Base King Capri - Cabecera','capri-base-king-capri-cabecera','Base King Tipo Capri Proveedor: SAMAR MUEBLES.',14500.00,'/storage/productos/mueble_sama_row51.jpg','Dormitorio',21,4.7,0,NULL,NULL,NULL,'2026-08-13 23:21:10','2026-08-13 23:21:10');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ruleta_opciones`
--

DROP TABLE IF EXISTS `ruleta_opciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ruleta_opciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `posicion` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_cupon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_descuento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'porcentaje',
  `descuento_valor` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tiempo_minutos` int NOT NULL DEFAULT '15',
  `color_bg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#D97706',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ruleta_opciones_posicion_unique` (`posicion`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruleta_opciones`
--

LOCK TABLES `ruleta_opciones` WRITE;
/*!40000 ALTER TABLE `ruleta_opciones` DISABLE KEYS */;
INSERT INTO `ruleta_opciones` VALUES (1,1,'15% OFF en tu primera compra','RULETA15','porcentaje',15.00,15,'#B45309',1,'2026-08-11 01:30:34','2026-08-11 01:30:34'),(2,2,'Envío Gratis en tu pedido','ENVIORULETA','envio_gratis',0.00,20,'#15803D',1,'2026-08-11 01:30:34','2026-08-11 01:30:34'),(3,3,'$500 Descuento Especial','RULETA500','fijo',500.00,10,'#1E3A8A',1,'2026-08-11 01:30:34','2026-08-11 01:30:34');
/*!40000 ALTER TABLE `ruleta_opciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('2LrpCxKCVpPMPLpBZR8oaj8eIoTAG2hK3jd1TSYe',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJueVVQbFN1VHFSMEZrYktEWVlaVUUwcnVJMUpwQUtldHZqT24yUTZzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvU2VjdG9yTXVlYmxlXC9wdWJsaWNcL2NhcnJpdG8iLCJyb3V0ZSI6ImNhcnJpdG8ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJjdXBvbiI6eyJjb2RpZ28iOiJFTlZJT1JVTEVUQSIsInRpcG8iOiJmaWpvIiwidmFsb3IiOjAsInRpdHVsbyI6IkVudlx1MDBlZG8gR3JhdGlzIGVuIHR1IHBlZGlkbyIsImV4cGlyYV9lbiI6MTc4NzI0NTg0Nn0sInJ1bGV0YV9qdWdhZGEiOnRydWV9',1787244647),('AaICsLUSfcpBhkk1MV81fvsW7wPKd7bzQXAIOG4k',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJxeEJHeUdzZ1paT0dSQ3RzOEtFUURTcXZ0VFNGYm9QZ0VJenVlSkRMIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvU2VjdG9yTXVlYmxlXC9wdWJsaWMiLCJyb3V0ZSI6ImluaWNpbyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImN1cG9uIjp7ImNvZGlnbyI6IlJVTEVUQTE1IiwidGlwbyI6InBvcmNlbnRhamUiLCJ2YWxvciI6MTUsInRpdHVsbyI6IjE1JSBPRkYgZW4gdHUgcHJpbWVyYSBjb21wcmEiLCJleHBpcmFfZW4iOjE3ODcyNDgwMzV9LCJydWxldGFfanVnYWRhIjp0cnVlfQ==',1787251054),('CMYRHiwQk0mVKm9UsKKUnlEdcK50h5nHDCDnZNjm',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJsc0JvN3VQeEVUd3FBMEJ1ZEpQa2V3TGNZd1JRRkJwUXBJaU5uazE4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvU2VjdG9yTXVlYmxlXC9wdWJsaWNcL2NhdGFsb2dvP2NhdGVnb3JpYT1Eb3JtaXRvcmlvIiwicm91dGUiOiJjYXRhbG9nbyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImN1cG9uIjp7ImNvZGlnbyI6IlJVTEVUQTUwMCIsInRpcG8iOiJmaWpvIiwidmFsb3IiOjUwMCwidGl0dWxvIjoiJDUwMCBEZXNjdWVudG8gRXNwZWNpYWwiLCJleHBpcmFfZW4iOjE3ODcyNDk5NTR9LCJydWxldGFfanVnYWRhIjp0cnVlfQ==',1787249368),('eiVprez63txSDfFmW3Mn9k5QugDk1stO9tmRMZB9',2,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJvOGR1Snl6d0JRbnZQR25Zb2V1bGZMTjJGbUFCVTBLOTg2OHpSaE1nIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvU2VjdG9yTXVlYmxlXC9wdWJsaWMiLCJyb3V0ZSI6ImluaWNpbyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImNhcnJpdG8iOnsiMzYiOnsibm9tYnJlIjoiTHVjaWVyIC0gVGFwaXphZGEiLCJwcmVjaW8iOjIyNDAwLCJwcmVjaW9fb3JpZ2luYWwiOjIyNDAwLCJjb25fZGVzY3VlbnRvIjpmYWxzZSwiaW1hZ2VuX3VybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvU2VjdG9yTXVlYmxlXC9wdWJsaWNcL3N0b3JhZ2VcL3Byb2R1Y3Rvc1wvbXVlYmxlX3RhcGlfcm93MzkucG5nIiwiY2FudGlkYWQiOjEsImNhdGVnb3JpYSI6IkRvcm1pdG9yaW8iLCJzdG9ja19kaXNwb25pYmxlIjoxOH19LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Mn0=',1787259991),('QDPdUtgv3aQZUS885W9R5gQT812sedwSjR3gUxdM',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJPR1JGNmRqVWNpZmZUbmRvd1BJVzVwNUduNWhBeHFGMzl5aVFBbzRnIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvU2VjdG9yTXVlYmxlXC9wdWJsaWMiLCJyb3V0ZSI6ImluaWNpbyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1787591546);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruleta_jugada` tinyint(1) NOT NULL DEFAULT '0',
  `ruleta_premio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com','2026-07-10 04:09:00','$2y$12$5zgwX01Q3GMENGHivUsQX.ee.Y87bfimJEa0Suo/aA/yis9y6c.ou',0,NULL,0,'z2Dp91oira','2026-07-10 04:09:01','2026-07-10 04:09:01'),(2,'Administrador','admin@sectormueble.com','2026-07-10 04:09:01','$2y$12$qcr41GLJ9VNXKMMAykoIqeZ0ef3848h/fWQrQ9EjP5t5Fc55o5M5m',0,NULL,1,'6M8slEcaq9is87yS7QBH5i0ZiHYhEwxxyyn2RPgdBW80r1b4VzKtj5MsuvGP','2026-07-10 04:09:01','2026-07-10 04:09:01'),(3,'Luis Coyotecatl','luis@gmail.com',NULL,'$2y$12$NHSRWRbcJAMIMmgYtlEzvum3pbJkHyoVMie4vHHHc2WukYyEuaGJ6',0,NULL,1,NULL,'2026-07-10 04:10:21','2026-07-10 04:10:21'),(4,'Irvin','irvin@gmail.com',NULL,'$2y$12$XkwoHqx6ytPQoPYPp6X7auHXq1jaWNB6febPVDRNwghoC3RKO2vs6',0,NULL,0,NULL,'2026-07-10 04:30:05','2026-07-10 04:30:05');
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

-- Dump completed on 2026-08-24 11:13:41
