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
INSERT INTO `client` VALUES (2,'izidaggsgb','fdsf','fdf','dfdf','fdff'),(55,'izidaggsg1','fdsf','fdf','dfdf','fdff'),(56,'izidaggsg2','fdsf','fdf','dfdf','fdff'),(57,'izidaggsg3','fdsf','fdf','dfdf','fdff'),(58,'izidaggsg4','fdsf','fdf','dfdf','fdff'),(59,'CLI725460833','fdsf','fdf','dfdf','fdff'),(61,'CLI_57778900','fdsf','fdf','dfdf','fdff'),(62,'CLI_86102150','fdsf','fdf','dfdf','fdff'),(63,'CLI_806044672','fdsf','fdf','dfdf','fdff'),(64,'CLI_489752591','fdsf','fdf','dfdf','fdff'),(65,'CLI_916363604','fdsf','fdf','dfdf','fdff'),(66,'CLI_49894195','fdsf','fdf','dfdf','fdff'),(67,'CLI_43211311','fdsf','fdf','dfdf','fdff'),(68,'CLI_559487815','fdsf','fdf','dfdf','fdff'),(69,'CLI_473532585','fdsf','fdf','dfdf','fdff'),(70,'CLI_970947595','fdsf','fdf','dfdf','fdff'),(71,'CLI_92842314','fdsf','fdf','dfdf','fdff'),(72,'CLI_719035765','fdsf','fdf','dfdf','fdff'),(73,'CLI_221090147','fdsf','fdf','dfdf','fdff'),(74,'CLI_186027215','fdsf','fdf','dfdf','fdff'),(75,'CLI_645632826','fdsf','fdf','dfdf','fdff'),(76,'CLI_725819927','fdsf','fdf','dfdf','fdff');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `company`
--

LOCK TABLES `company` WRITE;
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` VALUES (14,'CPY933306913','Ma companie',767.2,'fdff'),(23,'CPY522566866','fdsfsdfds',6.9,'fdff'),(32,'CPY_922837311','fdssdsdsdf',6.9,'fdff');
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
INSERT INTO `product` VALUES (17,'PRD_PRV_88551222',NULL,'paume de douche',12.6,8.6,16,NULL,1),(19,'PRD_673909751',NULL,'raccord plomberie acier',167.9,10.6,19,14,NULL),(20,'PRD_522495749',NULL,'eau de cologne',12.6,8.6,144,14,NULL),(22,'PRD_PRV_206199867',NULL,'serviettes de bain',4.6,10,67,NULL,1),(31,'PRD_236786758',NULL,'pot de fleur plastique',1.6,8.6,173,14,NULL),(32,'PRD_236786750','PRD_PRV_206199867','serviettes de bain',18.7,10,361,14,NULL),(40,'PRD_244400797','PRD_PRV_88551222','paume de douche',NULL,NULL,34,14,NULL),(41,'PRD_PRV_202374871',NULL,'serviette de bain',1.6,8.6,67,NULL,1);
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
INSERT INTO `rel_transaction_product` VALUES (94,32,10,4.6,10),(94,40,1,12.6,8.6),(95,20,2,12.6,8.6),(95,31,9,1.6,8.6);
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
INSERT INTO `transaction` VALUES (94,'2022-04-07 11:32:18','TRA_168457175','supply',38,NULL,58.6),(95,'2022-04-07 11:32:18','TRA_918547345','sell',38,56,39.6);
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

-- Dump completed on 2022-04-07 11:38:55
