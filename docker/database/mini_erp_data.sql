-- MySQL dump 10.19  Distrib 10.3.34-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: mini_erp_database    Database: mini_erp
-- ------------------------------------------------------
-- Server version	10.6.7-MariaDB-1:10.6.7+maria~focal

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `mini_erp`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mini_erp` /*!40100 DEFAULT CHARACTER SET utf8mb3 */;

USE `mini_erp`;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (2,'izidaggsgb','fdsf','fdf','dfdf','fdff'),(55,'izidaggsg1','fdsf','fdf','dfdf','fdff'),(56,'izidaggsg2','fdsf','fdf','dfdf','fdff'),(57,'izidaggsg3','fdsf','fdf','dfdf','fdff'),(58,'izidaggsg4','fdsf','fdf','dfdf','fdff'),(59,'CLI725460833','fdsf','fdf','dfdf','fdff'),(61,'CLI_57778900','fdsf','fdf','dfdf','fdff');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (14,'CPY933306913','Ma companie',43.8,'fdff'),(23,'CPY522566866','fdsfsdfds',6.9,'fdff');
/*!40000 ALTER TABLE `company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (35,'EMP693087953','aaaaaaaaaaaaaaa','fdssdsdsdf','2021-01-02','sfsdfdf','2021-01-03',14),(38,'EMP_640738211','bbbbbbbbbbbbbbbbb','fdssdsdsdf',NULL,'sfsdfdf','2021-01-01',14);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (17,'PRD_PRV_88551222',NULL,'paume de douche',12.6,8.6,26,NULL,1),(19,'PRD_673909751',NULL,'raccord plomberie acier',167.9,10.6,19,14,NULL),(20,'PRD_522495749',NULL,'eau de cologne',12.6,8.6,156,14,NULL),(22,'PRD_PRV_206199867',NULL,'serviettes de bain',4.6,10,NULL,NULL,1),(31,'PRD_236786758',NULL,'pot de fleur plastique',1.6,8.6,22,14,NULL),(32,'PRD_236786750','PRD_PRV_206199867','serviettes de bain',18.7,10,261,14,NULL),(40,'PRD_244400797','PRD_PRV_88551222','paume de douche',NULL,NULL,24,14,NULL);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `provider`
--

LOCK TABLES `provider` WRITE;
/*!40000 ALTER TABLE `provider` DISABLE KEYS */;
INSERT INTO `provider` VALUES (1,'PRV_175838822','fdssdsdsdf','10 avsodifjoij oiodsds','fdff'),(6,'PRV_367402174','UIHSIUHDUSD','sodfijsfiosdojfio odisfjodjso ','oooooooooooo');
/*!40000 ALTER TABLE `provider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `rel_transaction_product`
--

LOCK TABLES `rel_transaction_product` WRITE;
/*!40000 ALTER TABLE `rel_transaction_product` DISABLE KEYS */;
INSERT INTO `rel_transaction_product` VALUES (44,32,10,4.6,10),(44,40,1,12.6,8.6),(45,32,10,4.6,10),(45,40,1,12.6,8.6),(46,32,10,4.6,10),(46,40,1,12.6,8.6),(47,32,10,4.6,10),(47,40,1,12.6,8.6),(50,32,10,4.6,10),(50,40,1,12.6,8.6),(51,32,10,4.6,10),(51,40,1,12.6,8.6),(52,32,10,4.6,10),(52,40,1,12.6,8.6),(53,32,10,4.6,10),(53,40,1,12.6,8.6),(54,32,10,4.6,10),(54,40,1,12.6,8.6),(55,32,10,4.6,10),(55,40,1,12.6,8.6),(56,32,10,4.6,10),(56,40,1,12.6,8.6),(57,32,10,4.6,10),(57,40,1,12.6,8.6),(58,32,10,4.6,10),(58,40,1,12.6,8.6),(59,32,10,4.6,10),(59,40,1,12.6,8.6),(60,32,10,4.6,10),(60,40,1,12.6,8.6),(61,32,10,4.6,10),(61,40,1,12.6,8.6),(62,32,10,4.6,10),(62,40,1,12.6,8.6),(63,32,10,4.6,10),(63,40,1,12.6,8.6),(64,32,10,4.6,10),(64,40,1,12.6,8.6),(65,32,10,4.6,10),(65,40,1,12.6,8.6),(66,32,10,4.6,10),(66,40,1,12.6,8.6),(67,32,10,4.6,10),(67,40,1,12.6,8.6),(68,32,10,4.6,10),(68,40,1,12.6,8.6),(77,20,2,12.6,8.6),(77,31,9,1.6,8.6),(78,20,2,12.6,8.6),(78,31,9,1.6,8.6),(79,20,2,12.6,8.6),(79,31,9,1.6,8.6),(80,20,2,12.6,8.6),(80,31,9,1.6,8.6),(81,20,2,12.6,8.6),(81,31,9,1.6,8.6);
/*!40000 ALTER TABLE `rel_transaction_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `transaction`
--

LOCK TABLES `transaction` WRITE;
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
INSERT INTO `transaction` VALUES (16,'2022-04-06 16:39:29','TRA_202921070','supply',38,NULL),(20,'2022-04-06 16:53:05','TRA_995086335','supply',38,NULL),(22,'2022-04-06 16:55:18','TRA_693730138','supply',38,NULL),(31,'2022-04-06 17:06:47','TRA_87114967','supply',38,NULL),(44,'2022-04-06 17:21:22','TRA_297428074','supply',38,NULL),(45,'2022-04-06 19:58:38','TRA_610869012','supply',38,NULL),(46,'2022-04-06 20:01:38','TRA_384165980','supply',38,NULL),(47,'2022-04-06 20:01:45','TRA_713032925','supply',38,NULL),(50,'2022-04-06 20:03:30','TRA_902602538','supply',38,NULL),(51,'2022-04-06 20:05:11','TRA_933293017','supply',38,NULL),(52,'2022-04-06 20:07:21','TRA_613717496','supply',38,NULL),(53,'2022-04-06 20:15:00','TRA_215051030','supply',38,NULL),(54,'2022-04-06 20:16:37','TRA_638984989','supply',38,NULL),(55,'2022-04-06 20:18:56','TRA_281687079','supply',38,NULL),(56,'2022-04-06 20:21:52','TRA_507218157','supply',38,NULL),(57,'2022-04-06 20:22:25','TRA_272007972','supply',38,NULL),(58,'2022-04-06 20:23:12','TRA_543210123','supply',38,NULL),(59,'2022-04-06 20:23:54','TRA_319067074','supply',38,NULL),(60,'2022-04-06 20:25:00','TRA_927792856','supply',38,NULL),(61,'2022-04-06 20:25:33','TRA_145263694','supply',38,NULL),(62,'2022-04-06 20:25:55','TRA_478284116','supply',38,NULL),(63,'2022-04-06 20:26:35','TRA_3312260','supply',38,NULL),(64,'2022-04-06 20:27:06','TRA_629758568','supply',38,NULL),(65,'2022-04-06 20:27:21','TRA_617224140','supply',38,NULL),(66,'2022-04-06 20:48:08','TRA_423744286','supply',38,NULL),(67,'2022-04-06 21:57:33','TRA_103837910','supply',38,NULL),(68,'2022-04-06 21:58:02','TRA_578733915','supply',38,NULL),(77,'2022-04-06 23:11:00','TRA_347222988','sell',38,56),(78,'2022-04-06 23:11:19','TRA_675295099','sell',38,56),(79,'2022-04-06 23:11:45','TRA_373791832','sell',38,56),(80,'2022-04-06 23:11:49','TRA_669839700','sell',38,56),(81,'2022-04-06 23:11:55','TRA_767768859','sell',38,56);
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `transaction_history`
--

LOCK TABLES `transaction_history` WRITE;
/*!40000 ALTER TABLE `transaction_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-06 23:38:55
