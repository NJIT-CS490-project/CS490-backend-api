-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: sql2.njit.edu    Database: mjc55
-- ------------------------------------------------------
-- Server version	5.5.29-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answers` (
  `qid` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `caseSensitive` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`qid`,`answer`),
  CONSTRAINT `fk_answers_questions` FOREIGN KEY (`qid`) REFERENCES `questions` (`qid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES (17,'blue',0);
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `choices`
--

DROP TABLE IF EXISTS `choices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `choices` (
  `qid` int(11) NOT NULL,
  `choice` varchar(255) NOT NULL,
  PRIMARY KEY (`qid`,`choice`),
  CONSTRAINT `fk_choices_questions` FOREIGN KEY (`qid`) REFERENCES `questions` (`qid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `choices`
--

LOCK TABLES `choices` WRITE;
/*!40000 ALTER TABLE `choices` DISABLE KEYS */;
INSERT INTO `choices` VALUES (13,'blue'),(13,'derp'),(13,'green'),(13,'purple'),(14,'blue'),(14,'derp'),(14,'green'),(14,'purple'),(15,'blue'),(15,'derp'),(15,'green'),(15,'purple'),(16,'blue'),(16,'derp'),(16,'green'),(16,'purple'),(17,'black'),(17,'red');
/*!40000 ALTER TABLE `choices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) NOT NULL,
  `uid_professor` int(11) NOT NULL,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `uc_class_name_uid_professor` (`class_name`,`uid_professor`),
  KEY `fk_classes_uid_professor` (`uid_professor`),
  CONSTRAINT `fk_classes_uid_professor` FOREIGN KEY (`uid_professor`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'cs280',2);
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `qid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `question_text` text NOT NULL,
  `question_tail` text,
  `question_hint` text,
  PRIMARY KEY (`qid`),
  KEY `fk_questions_users` (`uid`),
  CONSTRAINT `fk_questions_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,2,0,12,'What is the color of the sky?',NULL,''),(2,2,0,12,'What is the color of the sky?',NULL,''),(3,2,0,12,'What is the color of the sky?',NULL,''),(4,2,0,12,'What is the color of the sky?',NULL,''),(5,2,0,12,'What is the color of the sky?',NULL,''),(6,2,0,12,'What is the color of the sky?',NULL,''),(7,2,0,12,'What is the color of the sky?',NULL,''),(8,2,0,12,'What is the color of the sky?',NULL,''),(9,2,0,12,'What is the color of the sky?',NULL,''),(10,2,0,12,'What is the color of the sky?',NULL,''),(11,2,0,12,'What is the color of the sky?',NULL,''),(12,2,0,12,'What is the color of the sky?',NULL,''),(13,2,0,12,'What is the color of the sky?',NULL,''),(14,2,0,12,'What is the color of the sky?',NULL,''),(15,2,0,12,'What is the color of the sky?',NULL,''),(16,2,0,12,'What is the color of the sky?',NULL,''),(17,2,0,12,'What is the color of the sky?',NULL,'');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registration`
--

DROP TABLE IF EXISTS `registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registration` (
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `time_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`,`cid`),
  KEY `fk_registration_classes` (`cid`),
  CONSTRAINT `fk_registration_classes` FOREIGN KEY (`cid`) REFERENCES `classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_registration_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registration`
--

LOCK TABLES `registration` WRITE;
/*!40000 ALTER TABLE `registration` DISABLE KEYS */;
/*!40000 ALTER TABLE `registration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `submissions`
--

DROP TABLE IF EXISTS `submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `submissions` (
  `qid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `time_submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qid`,`uid`),
  KEY `fk_submissions_users` (`uid`),
  CONSTRAINT `fk_submissions_questions` FOREIGN KEY (`qid`) REFERENCES `questions` (`qid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_submissions_users` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_questions`
--

DROP TABLE IF EXISTS `test_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_questions` (
  `tid` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  PRIMARY KEY (`tid`,`qid`),
  KEY `fk_test_questions_questions` (`qid`),
  CONSTRAINT `fk_test_questions_questions` FOREIGN KEY (`qid`) REFERENCES `questions` (`qid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_test_questions_tests` FOREIGN KEY (`tid`) REFERENCES `tests` (`tid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_questions`
--

LOCK TABLES `test_questions` WRITE;
/*!40000 ALTER TABLE `test_questions` DISABLE KEYS */;
INSERT INTO `test_questions` VALUES (1,1),(1,17);
/*!40000 ALTER TABLE `test_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tests` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `test_name` varchar(255) DEFAULT NULL,
  `time_start` timestamp NULL DEFAULT NULL,
  `time_end` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tid`),
  KEY `fk_tests_classes` (`cid`),
  CONSTRAINT `fk_tests_classes` FOREIGN KEY (`cid`) REFERENCES `classes` (`cid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests`
--

LOCK TABLES `tests` WRITE;
/*!40000 ALTER TABLE `tests` DISABLE KEYS */;
INSERT INTO `tests` VALUES (1,1,'Final Exam',NULL,NULL);
/*!40000 ALTER TABLE `tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_professor` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uc_users_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'matt','Matt','Cifelli','password',0),(2,'gryan','Gerard','Ryan','password',1);
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

-- Dump completed on 2016-02-16  1:54:32
