-- MariaDB dump 10.19  Distrib 10.11.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: attendance_report
-- ------------------------------------------------------
-- Server version	10.11.2-MariaDB-1

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
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance_data`
--

DROP TABLE IF EXISTS `attendance_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `userid` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_uid` (`uid`),
  CONSTRAINT `attendance_data_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user_data` (`uid`),
  CONSTRAINT `fk_uid` FOREIGN KEY (`uid`) REFERENCES `user_data` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance_data`
--

LOCK TABLES `attendance_data` WRITE;
/*!40000 ALTER TABLE `attendance_data` DISABLE KEYS */;
INSERT INTO `attendance_data` VALUES
(1,1,'78','Atif Ijaz ','Face','2020-08-24','12:39:37','Check-in'),
(2,2,'78','Atif Ijaz ','Face','2020-08-24','12:39:39','Check-in'),
(3,3,'8','Sharafat Ali ','Face','2020-08-24','12:41:17','Check-in'),
(4,4,'8','Sharafat Ali ','Face','2020-08-24','12:41:19','Check-in'),
(5,5,'8','Sharafat Ali ','Face','2020-08-24','12:41:22','Check-in'),
(6,6,'12','M.Zubair ','Fingerprint','2020-08-24','13:14:56','Check-in'),
(7,7,'12','M.Zubair ','Fingerprint','2020-08-24','13:15:19','Check-in'),
(8,8,'12','M.Zubair ','Face','2020-08-24','13:15:36','Check-in'),
(9,9,'12','M.Zubair ','Face','2020-08-24','13:15:38','Check-in'),
(10,10,'12','M.Zubair ','Face','2020-08-24','13:15:47','Check-in'),
(11,11,'51','Khurram Shahzad ','Fingerprint','2020-08-24','13:17:45','Check-in');
/*!40000 ALTER TABLE `attendance_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_info`
--

DROP TABLE IF EXISTS `device_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `os_version` varchar(50) DEFAULT NULL,
  `platform` varchar(50) DEFAULT NULL,
  `firmware_version` varchar(50) DEFAULT NULL,
  `serial_number` varchar(50) DEFAULT NULL,
  `device_name` varchar(50) DEFAULT NULL,
  `current_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_info`
--

LOCK TABLES `device_info` WRITE;
/*!40000 ALTER TABLE `device_info` DISABLE KEYS */;
INSERT INTO `device_info` VALUES
(1,'Connected','Ver 6.60 May 14 2018\0','~OS=1\0','~Platform=ZMM220_TFT\0','~ZKFPVersion=10\0','~SerialNumber=AF4C194560052\0','~DeviceName=uFace800/ID\0','2024-11-07 05:30:26'),
(2,'Connected','Ver 6.60 May 14 2018\0','~OS=1\0','~Platform=ZMM220_TFT\0','~ZKFPVersion=10\0','~SerialNumber=AF4C194560052\0','~DeviceName=uFace800/ID\0','2024-11-07 05:35:58'),
(3,'Connected','Ver 6.60 May 14 2018\0','~OS=1\0','~Platform=ZMM220_TFT\0','~ZKFPVersion=10\0','~SerialNumber=AF4C194560052\0','~DeviceName=uFace800/ID\0','2024-11-07 05:36:37');
/*!40000 ALTER TABLE `device_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_data`
--

DROP TABLE IF EXISTS `user_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_data` (
  `uid` int(11) NOT NULL,
  `userid` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `cardno` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_data`
--

LOCK TABLES `user_data` WRITE;
/*!40000 ALTER TABLE `user_data` DISABLE KEYS */;
INSERT INTO `user_data` VALUES
(1,'1','M. Irfan Aslam ','0000000000 ','Admin',''),
(2,'2','Mr. Hafiz Shakeel','0000000000 ','User',''),
(3,'3','Jalil Ahmad ','0001700825 ','Admin',''),
(4,'4','M. Kashif ','0000000000 ','Admin',''),
(5,'5','Rana Amir Shahzad ','0000000000 ','User',''),
(6,'6','Khurram Saleem ','0000000000 ','User',''),
(7,'7','Zahid ur Rehman ','0000000000 ','User',''),
(8,'8','Sharafat Ali ','0000000000 ','User',''),
(9,'9','Shahid Naveed ','0000000000 ','User',''),
(10,'10','Mr. Muhammad Shahzad ','0000000000 ','User',''),
(11,'11','Muhammad Ashrif ','0000000000 ','User',''),
(13,'13','Zulfiqar Ali Stenographr','0000000000 ','User',''),
(14,'14','M. Kalim ulah ','0000000000 ','User',''),
(15,'15','Bhanwar Ali ','0000000000 ','User',''),
(16,'16','Muhammad Ahmed ','0000000000 ','User',''),
(17,'17','M. Raffique ','0000000000 ','User',''),
(18,'18','Waqar Maqsood ','0000000000 ','User',''),
(19,'19','Zain Ul Abdin Tracer','0000000000 ','User',''),
(20,'20','M. Faryad Sr.Clk','0000000000 ','User',''),
(21,'21','M. Adeel ','0000000000 ','User',''),
(22,'22','Majeed Sweeper','0000000000 ','User',''),
(23,'23','M. Babar Stenographr','0000000000 ','User',''),
(24,'24','Raza Umer ','0000000000 ','User',''),
(25,'25','Nazar Hussain Technician','0000000000 ','User',''),
(26,'27','Qasim Mashi Sweeper','0000000000 ','User',''),
(27,'28','Muhammad Muzamil Sweeper','0000000000 ','User',''),
(28,'29','Danish Ali ','0000000000 ','User',''),
(29,'30','Rafaqat Mughal ','0000000000 ','User',''),
(30,'31','Sobia Hanif ','0000000000 ','User',''),
(31,'32','Summera Rashid ','0000000000 ','User',''),
(32,'33','Zahid Iqbal ','0000000000 ','User',''),
(33,'34','Joseph Masih ','0000000000 ','User',''),
(34,'35','Zaheer Abbas','0000000000 ','User',''),
(35,'36','Shamus Din ','0000000000 ','User',''),
(36,'37','Hamza Khalid ','0000000000 ','User',''),
(37,'38','M. Shafqat ','0000000000 ','User',''),
(38,'39','Roohulameen Sr.Clk','0000000000 ','User',''),
(39,'40','Imdad Hussain ','0000000000 ','User',''),
(40,'41','Muhammad Tariq ','0000000000 ','User',''),
(41,'42','Parveen Saleem Stnogrphr','0000000000 ','User',''),
(42,'43','Muhammad Awais ','0000000000 ','User',''),
(43,'44','Thomson Ashraf ','0000000000 ','User',''),
(44,'45','Khurram Shahzad ','0000000000 ','User',''),
(45,'46','Amjad Hussain Tracer','0000000000 ','User',''),
(46,'47','Alam Sher ','0000000000 ','User',''),
(47,'48','Adil Paal ','0000000000 ','User',''),
(48,'49','Muhammad Waqar ','0000000000 ','User',''),
(49,'50','Muhammad Faizan ','0000000000 ','User',''),
(50,'51','Khurram Shahzad ','0000000000 ','User',''),
(51,'52','Khawar Ramzan ','0000000000 ','User',''),
(52,'53','Muhammad Qadeer ','0000000000 ','User',''),
(53,'54','Maria Amin ','0000000000 ','User',''),
(54,'55','Ayesha Gulshan ','0000000000 ','User',''),
(55,'56','Abida Akbar ','0000000000 ','User',''),
(56,'57','Waheed Khan Draftsman','0000000000 ','User',''),
(57,'58','M. Moeed Tracer','0000000000 ','User',''),
(58,'59','M. Riaz ','0000000000 ','User',''),
(59,'60','Shakeel Ahmed','0000000000 ','User',''),
(60,'61','Nabeel Masih Sweeper','0000000000 ','User',''),
(62,'63','Mazhar Ali ','0000000000 ','User',''),
(63,'64','Sidra Hafeez ','0000000000 ','User',''),
(64,'65','Agnus Saleem ','0000000000 ','User',''),
(65,'66','Muhammad Asif','0000000000 ','User',''),
(66,'67','Sufia Ather ','0000000000 ','User',''),
(67,'68','Ishrat Fatima ','0000000000 ','User',''),
(69,'70','Sharafat Ali ','0000000000 ','User',''),
(70,'71','Fazila Javed ','0000000000 ','User',''),
(71,'72','Kamran Shahzad ','0000000000 ','User',''),
(72,'73','Abdur Rehman ','0000000000 ','User',''),
(73,'74','Imran Latif ','0000000000 ','User',''),
(74,'75','M. Kamran ','0000000000 ','User',''),
(75,'76','Ali Raza ','0000000000 ','User',''),
(76,'77','Muhammad ali ','0000000000 ','User',''),
(77,'79','Mubashar Mumtaz ','0000000000 ','User',''),
(78,'80','Kamran Anjum ','0000000000 ','User',''),
(79,'81','Mr. Muhammad Ahsan ','0000000000 ','User',''),
(80,'82','Muhammad Afzal Sr.Clk','0000000000 ','User',''),
(81,'84','Mr. Muhammad Arshad ','0000000000 ','User',''),
(82,'86','Muhammad Usman ','0000000000 ','User',''),
(83,'87','Asad Ameer ','0000000000 ','User',''),
(84,'26','M.Yaqoob Khan S.Clerk','0000000000 ','User',''),
(85,'78','Atif Ijaz ','0000000000 ','User',''),
(86,'83','Mr. Majid Nawaz','0000000000 ','User',''),
(87,'85','Aurangzeb ','0000000000 ','User',''),
(88,'88','Rojar ','0000000000 ','User',''),
(90,'90','Nagina Maqsood ','0000000000 ','User',''),
(91,'91','Ubad ur Rahman ','0000000000 ','User',''),
(92,'92','Khizar Abbas ','0000000000 ','User',''),
(93,'93','Muhammad Adeel ','0000000000 ','User',''),
(94,'94','M. Zafar Atif ','0000000000 ','User',''),
(95,'95','Zaheer Abbas ','0000000000 ','User',''),
(96,'96','Sarfraz Ahmad Superident','0000000000 ','User',''),
(97,'97','Muhammad.Haroon ','0000000000 ','User',''),
(99,'99','Adil Shabir ','0000000000 ','User',''),
(100,'100','M.Shoaib Nasir ','0000000000 ','User',''),
(101,'101','Asghar Niaz ','0000000000 ','User',''),
(102,'102','Babar Liaqat ','0000000000 ','User',''),
(103,'103','Muhammad. Altaf S.Clk','0000000000 ','User',''),
(104,'104','Mehboob Shah Sr.Clk','0000000000 ','User',''),
(106,'106','Rizwan Niamat ','0000000000 ','User',''),
(107,'107','M. Faizan Ali ','0000000000 ','User',''),
(109,'109','Aslam Nazir Stenographer','0000000000 ','User',''),
(111,'111','Iqbal Hassan ','0000000000 ','User',''),
(112,'112','Saddam Hussain Stenogrph','0000000000 ','User',''),
(113,'113','Mr. Muhammad Rizwan','0000000000 ','User',''),
(115,'115','Muhammad Jahangir ','0000000000 ','User',''),
(116,'116','Asmatullah ','0000000000 ','User',''),
(117,'117','Abdur Rehman ','0000000000 ','User',''),
(118,'118','Rashid Iqbal ','0000000000 ','User',''),
(120,'120','Imran Hussain ','0000000000 ','User',''),
(121,'121','Muhammad Zohaib ','0000000000 ','User',''),
(122,'122','Muhammad Yasin ','0000000000 ','User',''),
(123,'123','Muhammad Mudassar ','0000000000 ','User',''),
(124,'124','Amjad Niaz ','0000000000 ','User',''),
(125,'125','Murtaza Ali ','0000000000 ','User',''),
(126,'126','Arslan Riaz ','0000000000 ','User',''),
(127,'127','Ghulam Murtaza ','0000000000 ','User',''),
(128,'128','M. Siddique ','0000000000 ','User',''),
(129,'12','M.Zubair ','0000000000 ','User',''),
(130,'114','M.Zubair Stenogrphr','0000000000 ','User',''),
(131,'89','Javerya Nasir','0000000000 ','User',''),
(133,'130','Tariq Aziz Superintendnt','0000000000 ','User',''),
(134,'131','Aqeel Awan Stenographer','0000000000 ','User',''),
(135,'132','Allah Ditta Sweeper','0000000000 ','User',''),
(136,'133','Shabana Babar ','0000000000 ','User',''),
(137,'134','Naveed Ahmad ','0000000000 ','User',''),
(139,'136','Ali sher A.D','0000000000 ','User',''),
(140,'137','Uzma Shahzadi N.Q','0000000000 ','User',''),
(141,'138','Arslan Haider A.D','0000000000 ','User',''),
(142,'139','Kalsoom Bibi N.Q','0000000000 ','User',''),
(143,'62','Irfan Athar Superint','0000000000 ','User',''),
(144,'105','zubair idrees A.D','0000000000 ','User',''),
(145,'140','M.Amjad NQ','0000000000 ','User',''),
(146,'98','Mushtaq Sweeper','0000000000 ','User',''),
(147,'108','M. Fiaz Sr.Clerk','0000000000 ','User',''),
(148,'141','Nadeem ali Driver','0000000000 ','User',''),
(153,'135','Abdul Saeed','0000000000 ','User',''),
(155,'147','Rana Faizan Saleem','0000000000 ','User',''),
(156,'142','M. Iqbal Draftsman','0000000000 ','User',''),
(157,'110','Muhammad Ayaz Assistant','0000000000 ','User',''),
(158,'119','Muhammad Hafeez AD','0000000000 ','User',''),
(159,'129','Usman Umar AD','0000000000 ','User',''),
(160,'143','Haris Rizwan','0000000000 ','User',''),
(161,'144','Umer Rasool','0000000000 ','User',''),
(162,'145','mubashir DBA','0000000000 ','User',''),
(163,'146','Dilawer Liaqat NQ','0000000000 ','User',''),
(164,'148','hammad AD','0000000000 ','User',''),
(165,'149','amjid niazi AD','0000000000 ','User',''),
(166,'150','hamza shahid n.Q','0000000000 ','User',''),
(167,'151','naveed ac','0000000000 ','User',''),
(168,'152','m. ismail head draft','0000000000 ','User',''),
(169,'153','asma librarian','0000000000 ','User',''),
(170,'154','Khurram Shahzad Driver','0000000000 ','User',''),
(171,'155','Tariq Hussain Driver','0000000000 ','User',''),
(172,'156','Rafiq Kamran MDO','0000000000 ','User',''),
(173,'157','Muhammad Alam','0000000000 ','User',''),
(174,'69','Sonia AD','0002892625 ','User',''),
(175,'158','tanzila Sweeper','0000000000 ','User',''),
(176,'159','Muhammad Tanveer','0000000000 ','User',''),
(178,'161','tayyaba abid','0000000000 ','User',''),
(179,'162','kashifa zawar','0000000000 ','User',''),
(181,'163','m haseeb J.clerk','0000000000 ','User',''),
(182,'160','Muhammad Awais','0000000000 ','User','');
/*!40000 ALTER TABLE `user_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Designation` varchar(25) NOT NULL DEFAULT 'Naib Qasid',
  `Posting` varchar(25) NOT NULL DEFAULT 'Head Quarter',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'M. Irfan Aslam ','Hardware Technician','Head Quarter'),
(2,'Mr. Hafiz Shakeel','Assistant','Head Quarter'),
(3,'Jalil Ahmad ','Hardware Technician','Head Quarter'),
(4,'Muhammad Kashif','Computer Programmer','Head Quarter'),
(5,'Rana Amir Shahzad ','Assistant','Head Quarter'),
(6,'Khurram Saleem ','Helper','Head Quarter'),
(7,'Zahid Ur Rehman ','Naib Qasid','Head Quarter'),
(8,'Sharafat Ali ','Assistant','Head Quarter'),
(9,'Shahid Naveed ','Driver','Not Using'),
(10,'Mr. Muhammad Shahzad ','Chowkidar','Head Quarter'),
(11,'Muhammad Ashrif ','Naib Qasid','Retired'),
(12,'Muhammad Zubair','Naib Qasid','Retired'),
(13,'Zulfiqar Ali ','Superintendent','Head Quarter'),
(14,'M. Kaleem Ullah ','Electrician','Head Quarter'),
(15,'Bhanwar Ali ','Driver','Not Using'),
(16,'Muhammad Ahmed ','Junior Clerk','Head Quarter'),
(17,'M. Raffique ','Naib Qasid','Retired'),
(18,'Waqar Maqsood ','Naib Qasid','Head Quarter'),
(19,'Zain Ul Abdin ','Tracer','Head Quarter'),
(20,'M. Faryad ','Senior Clerk','Head Quarter'),
(21,'M. Adeel ','Naib Qasid','Head Quarter'),
(22,'Majeed ','Sweeper','Head Quarter'),
(23,'M. Babar ','Stenographer','Head Quarter'),
(24,'Raza Umer ','Computer Operator','Resigned'),
(25,'Nazar Hussain ','Technician','Head Quarter'),
(26,'Yaqoob Khan','Senior Clerk','Head Quarter'),
(27,'Qasim Mashi ','Junior Clerk','Head Quarter'),
(28,'Muhammad Muzamil ','Sweeper','Head Quarter'),
(29,'Danish Ali ','Naib Qasid','Head Quarter'),
(30,'Rafaqat Mughal ','Naib Qasid','Head Quarter'),
(31,'Sobia Hanif ','Naib Qasid','Head Quarter'),
(32,'Summera Rashid ','Naib Qasid','Head Quarter'),
(33,'Zahid Iqbal ','Superintendnt','Retired'),
(34,'Joseph Masih ','Naib Qasid','Head Quarter'),
(35,'Zaheer Abbas','Assistant Director','On Leave'),
(36,'Shamus Din ','Superintendent','Head Quarter'),
(37,'Hamza Khalid ','Naib Qasid','Head Quarter'),
(38,'M. Shafqat ','Superintendent','Head Quarter'),
(39,'Roohulameen ','Senior Clerk','Transfered'),
(40,'Imdad Hussain ','Naib Qasid','Head Quarter'),
(41,'Muhammad Tariq ','Helper','Head Quarter'),
(42,'Parveen Saleem ','Stenographer','Head Quarter'),
(43,'Muhammad Awais ','Junior Clerk','Head Quarter'),
(44,'Thomson Ashraf ','Naib Qasid','Head Quarter'),
(45,'Khurram Shahzad ','Junior Clerk','Head Quarter'),
(46,'Amjad Hussain ','Draftsman','Head Quarter'),
(47,'Alam Sher ','Naib Qasid','Head Quarter'),
(48,'Adil Paal ','Naib Qasid','Head Quarter'),
(49,'Muhammad Waqar ','Naib Qasid','Head Quarter'),
(50,'Muhammad Faizan ','Junior Clerk','Head Quarter'),
(51,'Khurram Shahzad Gujjar','Naib Qasid','Head Quarter'),
(52,'Khawar Ramzan ','Naib Qasid','Head Quarter'),
(53,'Muhammad Qadeer ','Draftsman','Head Quarter'),
(54,'Maria Amin ','Assistant Director','Head Quarter'),
(55,'Ayesha Gulshan ','Naib Qasid','Head Quarter'),
(56,'Abida Akbar ','Senior Clerk','Head Quarter'),
(57,'Waheed Khan ','Draftsman','Transfered'),
(58,'M. Moeed ','Tracer','Head Quarter'),
(59,'Muhammad Riaz ','Senior Clerk','Head Quarter'),
(60,'Shakeel Ahmed','Driller','Head Quarter'),
(61,'Nabeel Masih ','Sweeper','Transfered'),
(62,'Irfan Athar ','Superintendent','Retired'),
(63,'Mazhar Ali ','Driver','Not Using'),
(64,'Sidra Hafeez ','Naib Qasid','Head Quarter'),
(65,'Agnus Saleem ','Naib Qasid','Head Quarter'),
(66,'Muhammad Asif','Junior Clerk','Head Quarter'),
(67,'Sufia Ather ','Assistant','Head Quarter'),
(68,'Ishrat Fatima ','Naib Qasid','On Leave'),
(69,'Sonia Liaqat','Assistant Director','Head Quarter'),
(70,'Sharafat Ali ','Junior Clerk','Head Quarter'),
(71,'Fazila Javed ','Computer Programmer','Resigned'),
(72,'Kamran Shahzad ','Chowkidar','Head Quarter'),
(73,'Abdur Rehman ','Junior Clerk','Head Quarter'),
(74,'Imran Latif ','Senior Clerk','Head Quarter'),
(75,'Muhammad Kamran ','Senior Clerk','On Leave'),
(76,'Ali Raza ','Naib Qasid','Head Quarter'),
(77,'Muhammad Ali ','Assistant','Head Quarter'),
(78,'Atif Ijaz ','Chainman','Transfered'),
(79,'Mubashar Mumtaz ','Driller','Head Quarter'),
(80,'Kamran Anjum ','Chainman','Resigned'),
(81,'Mr. Muhammad Ahsan ','Senior Clerk','Head Quarter'),
(82,'Muhammad Afzal ','Senior Clerk','Head Quarter'),
(83,'Mr. Majid Nawaz','Technician','Transfered'),
(84,'Mr. Muhammad Arshad ','Draftsman','Retired'),
(85,'Aurangzeb ','Deputy Director','Not Using'),
(86,'Muhammad Usman ','Senior Clerk','Head Quarter'),
(87,'Asad Ameer ','Deputy Manager','Head Quarter'),
(88,'Rojar ','Naib Qasid','Head Quarter'),
(89,'Javerya Nasir','Gis Officer','Head Quarter'),
(90,'Nagina Maqsood ','Naib Qasid','Head Quarter'),
(91,'Ubad Ur Rahman ','Diesel Engine Operator','Transfered'),
(92,'Khizar Abbas ','Driver','Not Using'),
(93,'Muhammad Adeel ','Naib Qasid','Secretariat'),
(94,'M. Zafar Atif ','Account Officer','Retired'),
(95,'Zaheer Abbas ','Feroprinter','Resigned'),
(96,'Sarfraz Ahmad ','Assistant Director','Head Quarter'),
(97,'Muhammad Haroon','Naib Qasid','Head Quarter'),
(98,'Mushtaq ','Sweeper','Secretariat'),
(99,'Adil Shabir ','Naib Qasid','Minster Office'),
(100,'M.shoaib Nasir ','Naib Qasid','Secretariat'),
(101,'Asghar Niaz ','Account Officer','Head Quarter'),
(102,'Babar Liaqat ','Naib Qasid','Head Quarter'),
(103,'Muhammad Altaf ','Senior Clerk','Head Quarter'),
(104,'Mehboob Shah ','Senior Clerk','Head Quarter'),
(105,'Zubair Idrees ','Deputy Manager','Head Quarter'),
(106,'Rizwan Niamat ','Junior Clerk','Head Quarter'),
(107,'M. Faizan Ali ','Chainman','Head Quarter'),
(108,'Muhammad Fiaz ','Assistant','Head Quarter'),
(109,'Aslam Nazir',' Stenographer','Head Quarter'),
(110,'Muhammad Ayaz ','Assistant','Minster Office'),
(111,'Iqbal Hassan ','Gis Officer','Head Quarter'),
(112,'Saddam Hussain ','Stenographer','Head Quarter'),
(113,'Mr. Muhammad Rizwan','Superintendent','Head Quarter'),
(114,'Muhammad Zubair','Stenographer','Head Quarter'),
(115,'Muhammad Jahangir ','Driver','Not Using'),
(116,'Asmatullah ','Head Draftsman','Retired'),
(117,'Abdur Rehman ','Gis Officer','Head Quarter'),
(118,'Rashid Iqbal ','Electrician','Head Quarter'),
(119,'Muhammad Hafeez ','Assistant Director','Field Office'),
(120,'Imran Hussain ','Naib Qasid','Head Quarter'),
(121,'Muhammad Zohaib ','Naib Qasid','Head Quarter'),
(122,'Muhammad Yasin ','Deputy Manager','Head Quarter'),
(123,'Muhammad Mudassar ','Naib Qasid','Head Quarter'),
(124,'Amjad Niaz ','Assistant Director','Field Office'),
(125,'Murtaza Ali ','Superintendent','Death'),
(126,'Arslan Riaz ','Naib Qasid','Head Quarter'),
(127,'Ghulam Murtaza ','Naib Qasid','Head Quarter'),
(128,'M. Siddique ','Deputy Manager','Head Quarter'),
(129,'Usman Umar ','Assistant Director','Field Office'),
(130,'Tariq Aziz ','Superintendnt','On Leave'),
(131,'Aqeel Awan ','Superintendent','Head Quarter'),
(132,'Allah Ditta',' Sweeper','Head Quarter'),
(133,'Shabana Babar ','Computer Operator','Head Quarter'),
(134,'Naveed Ahmad ','Driver','Not Using'),
(135,'Abdul Saeed','Naib Qasid','Retired'),
(136,'Ali Sher','Assistant Director','Transfered'),
(137,'Uzma Shahzadi ','Naib Qasid','Head Quarter'),
(138,'Arslan Haider ','Assistant Director','Head Quarter'),
(139,'Kalsoom Bibi ','Naib Qasid','Head Quarter'),
(140,'M. Amjad','Naib Qasid','Secretariat'),
(141,'Nadeem Ali ','Driver','Not Using'),
(142,'M.iqbal ','Draftsman','Head Quarter'),
(143,'Haris Rizwan','Assistant Director','Resigned'),
(144,'Umer Rasool','Audit Officer','Head Quarter'),
(145,'Rana Mubashir ','Database Administrator','Head Quarter'),
(146,'Dilawer Liaqat','Naib Qasid','Secretariat'),
(147,'Rana Faizan Saleem','Deputy Manager','On Leave'),
(148,'Hammad ','Assistant Director','Field Office'),
(149,'Amjid Niazi ','Assistant Director','Field Office'),
(150,'Hamza Shahid ','Naib Qasid','Head Quarter'),
(151,'Muhammad Naveed ','Account Officer','Head Quarter'),
(152,'M Ismail','Head Draftsman','Head Quarter'),
(153,'Asma ','Librarian','Head Quarter'),
(154,'Khurram Shahzad ','Driver','Not Using'),
(155,'Tariq Hussain',' Driver','Not Using'),
(156,'Rafiq Kamran ','Mineral Development Offic','Transfered'),
(157,'Muhammad Alam','Assistant','Minster Office'),
(158,'Tanzila ','Sweeper','Transfered'),
(159,'Muhammad Tanveer','Naib Qasid','Transfered'),
(161,'Tayyaba Abid','Assistant Director','Head Quarter'),
(162,'Kashifa Zawar','Assistant Director','Head Quarter'),
(163,'M Haseeb ','Junior Clerk','Head Quarter');
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

-- Dump completed on 2025-02-27 14:11:29
