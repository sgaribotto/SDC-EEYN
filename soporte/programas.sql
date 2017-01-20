-- MySQL dump 10.13  Distrib 5.5.36, for Win32 (x86)
--
-- Host: localhost    Database: programas
-- ------------------------------------------------------
-- Server version	5.5.36

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
-- Table structure for table `afectacion`
--

DROP TABLE IF EXISTS `afectacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `afectacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `docente` int(10) unsigned NOT NULL,
  `materia` varchar(10) NOT NULL,
  `anio` int(10) unsigned NOT NULL,
  `cuatrimestre` enum('1','2','v') DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `tipoafectacion` enum('Titular','Asociado','Adjunto','JTP','Ayudante graduado','Ayudante alumno','otro') DEFAULT NULL,
  `estado` enum('Pendiente','Aprobado','Rechazado') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `docente` (`docente`),
  KEY `materia` (`materia`),
  CONSTRAINT `afectacion_ibfk_1` FOREIGN KEY (`docente`) REFERENCES `docente` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `afectacion`
--

LOCK TABLES `afectacion` WRITE;
/*!40000 ALTER TABLE `afectacion` DISABLE KEYS */;
INSERT INTO `afectacion` VALUES (1,1,'24',2015,'1',1,'Ayudante alumno','Pendiente'),(2,1,'312',2015,'1',1,'Titular','Pendiente'),(3,1,'312',2015,'1',1,'Titular','Pendiente'),(6,1,'312',2015,'1',1,'Adjunto','Pendiente'),(9,1,'312',2015,'1',1,'Adjunto','Pendiente'),(10,1,'312',2015,'1',1,'Asociado','Pendiente'),(11,1,'312',2015,'1',1,'JTP','Pendiente'),(12,1,'312',2015,'1',1,'','Pendiente'),(13,1,'312',2015,'1',1,'Ayudante graduado','Pendiente'),(14,1,'312',2015,'1',1,'Ayudante alumno','Pendiente');
/*!40000 ALTER TABLE `afectacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bibliografia`
--

DROP TABLE IF EXISTS `bibliografia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bibliografia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `materia` varchar(10) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `editorial` varchar(255) NOT NULL,
  `paginas` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `materia` (`materia`),
  CONSTRAINT `bibliografia_ibfk_1` FOREIGN KEY (`materia`) REFERENCES `materia` (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bibliografia`
--

LOCK TABLES `bibliografia` WRITE;
/*!40000 ALTER TABLE `bibliografia` DISABLE KEYS */;
INSERT INTO `bibliografia` VALUES (1,'312','titulo de prueba','asdf','prueba',123),(2,'312','otro título','otra prueba','otra editorial',132),(3,'312','Título','Autor','Otra',298);
/*!40000 ALTER TABLE `bibliografia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carrera`
--

DROP TABLE IF EXISTS `carrera`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carrera` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cod` varchar(25) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cod` (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrera`
--

LOCK TABLES `carrera` WRITE;
/*!40000 ALTER TABLE `carrera` DISABLE KEYS */;
INSERT INTO `carrera` VALUES (1,'EYN-3','Lic. Administración y gestión empresarial',1),(2,'EYN-4','Lic. Economía',1),(3,'LITUR','Lic. Turismo',1),(4,'EYN-3 Y EYN-4','Lic. Administación y Lic. Economía',1);
/*!40000 ALTER TABLE `carrera` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `correlatividad`
--

DROP TABLE IF EXISTS `correlatividad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `correlatividad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `materia` varchar(10) NOT NULL,
  `requisito` varchar(10) NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `materia` (`materia`),
  KEY `requisito` (`requisito`),
  CONSTRAINT `correlatividad_ibfk_1` FOREIGN KEY (`materia`) REFERENCES `materia` (`cod`),
  CONSTRAINT `correlatividad_ibfk_2` FOREIGN KEY (`requisito`) REFERENCES `materia` (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `correlatividad`
--

LOCK TABLES `correlatividad` WRITE;
/*!40000 ALTER TABLE `correlatividad` DISABLE KEYS */;
INSERT INTO `correlatividad` VALUES (1,'312','24',1);
/*!40000 ALTER TABLE `correlatividad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cronograma`
--

DROP TABLE IF EXISTS `cronograma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cronograma` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `materia` varchar(10) NOT NULL,
  `clase` tinyint(3) unsigned NOT NULL,
  `fecha` date DEFAULT NULL,
  `unidadtematica` tinyint(3) unsigned NOT NULL,
  `descripcion` text,
  `metodo` varchar(50) NOT NULL,
  `bibliografia` text,
  `paginas` int(10) unsigned DEFAULT NULL,
  `activo` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `materia` (`materia`),
  CONSTRAINT `cronograma_ibfk_1` FOREIGN KEY (`materia`) REFERENCES `materia` (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cronograma`
--

LOCK TABLES `cronograma` WRITE;
/*!40000 ALTER TABLE `cronograma` DISABLE KEYS */;
/*!40000 ALTER TABLE `cronograma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `designacion`
--

DROP TABLE IF EXISTS `designacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `docente` int(10) unsigned NOT NULL,
  `tipo_designacion` enum('exclusiva','semi-exclusiva','simple','otra') DEFAULT NULL,
  `anio` int(10) unsigned NOT NULL,
  `cuatrimestre` enum('1','2','v') DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `estado` enum('Pendiente','Aprobada','Rechazada') DEFAULT NULL,
  `caracter` enum('Ordinario','Interino','Contratado','Otro') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `docente` (`docente`),
  CONSTRAINT `designacion_ibfk_1` FOREIGN KEY (`docente`) REFERENCES `docente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designacion`
--

LOCK TABLES `designacion` WRITE;
/*!40000 ALTER TABLE `designacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `designacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docente`
--

DROP TABLE IF EXISTS `docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `docente` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dni` varchar(10) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `fechanacimiento` date NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `fechaingreso` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docente`
--

LOCK TABLES `docente` WRITE;
/*!40000 ALTER TABLE `docente` DISABLE KEYS */;
INSERT INTO `docente` VALUES (1,'30651515','﻿﻿﻿Garibotto','Santiago Hernán','1983-11-21',1,'2013-06-01'),(2,'','﻿23','','2015-05-05',1,'2015-05-02');
/*!40000 ALTER TABLE `docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materia`
--

DROP TABLE IF EXISTS `materia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materia` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `cod` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `carrera` int(10) unsigned NOT NULL,
  `cuatrimestre` varchar(3) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `plan` varchar(20) NOT NULL,
  `contenidosminimos` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cod` (`cod`,`carrera`),
  KEY `nombre` (`nombre`),
  KEY `materia_ibfk_1` (`carrera`),
  CONSTRAINT `materia_ibfk_1` FOREIGN KEY (`carrera`) REFERENCES `carrera` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materia`
--

LOCK TABLES `materia` WRITE;
/*!40000 ALTER TABLE `materia` DISABLE KEYS */;
INSERT INTO `materia` VALUES (1,'312','Contabilidad Gerencial',1,'8',1,'1999','Contenidos mínimos de la materia Contabilidad Gerencial. Escribo bastante más para ver como se ven los saltos de línea. Por las dudas voy a escribir más.'),(2,'24','Informática y sistemas de información gerencial',1,'5',1,'1999','Contenidos mínimos de la materia informática y sistemas de información');
/*!40000 ALTER TABLE `materia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dni` varchar(10) DEFAULT NULL,
  `apellido` varchar(31) DEFAULT NULL,
  `nombres` varchar(31) DEFAULT NULL,
  `usuario` varchar(31) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal`
--

LOCK TABLES `personal` WRITE;
/*!40000 ALTER TABLE `personal` DISABLE KEYS */;
INSERT INTO `personal` VALUES (1,'30651515','Garibotto','Santiago Hernán','sgaribotto','1dff9ef3751f8930e1222a440958f295',1);
/*!40000 ALTER TABLE `personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programa`
--

DROP TABLE IF EXISTS `programa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `materia` varchar(10) NOT NULL,
  `usuario` int(11) NOT NULL,
  `campo` varchar(255) DEFAULT NULL,
  `valor` text,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campo` (`usuario`,`materia`,`campo`),
  KEY `materia` (`materia`),
  CONSTRAINT `programa_ibfk_1` FOREIGN KEY (`materia`) REFERENCES `materia` (`cod`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programa`
--

LOCK TABLES `programa` WRITE;
/*!40000 ALTER TABLE `programa` DISABLE KEYS */;
INSERT INTO `programa` VALUES (28,'24',1,'objetivos','',NULL),(29,'24',1,'fundamentacion','',NULL),(30,'24',1,'evaluacion','',NULL),(31,'312',1,'objetivos','Objetivos de Contabilidad Gerencial.',NULL),(33,'312',1,'fundamentacion','Primera prueba del enfoque metodológico. Para ser la primera, anduvo bien. La segunda debería reemplazar. Esto parece andar bien.',NULL),(35,'312',1,'evaluacion','funcionando la evaluación. ¿Correctamente?asdffffffffff\r\nOtra\r\nasdf',NULL);
/*!40000 ALTER TABLE `programa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responsable`
--

DROP TABLE IF EXISTS `responsable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `responsable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` int(10) unsigned NOT NULL,
  `materia` varchar(10) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`),
  KEY `activo` (`activo`),
  KEY `materia` (`materia`),
  CONSTRAINT `responsable_ibfk_1` FOREIGN KEY (`materia`) REFERENCES `materia` (`cod`) ON UPDATE CASCADE,
  CONSTRAINT `responsable_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `personal` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responsable`
--

LOCK TABLES `responsable` WRITE;
/*!40000 ALTER TABLE `responsable` DISABLE KEYS */;
INSERT INTO `responsable` VALUES (1,1,'24','2015-05-04 14:15:22',1),(2,1,'312','2015-05-04 14:15:36',1);
/*!40000 ALTER TABLE `responsable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad_tematica`
--

DROP TABLE IF EXISTS `unidad_tematica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidad_tematica` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `materia` varchar(10) NOT NULL,
  `unidad` tinyint(3) unsigned NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unidad` (`materia`,`unidad`),
  CONSTRAINT `unidad_tematica_ibfk_1` FOREIGN KEY (`materia`) REFERENCES `materia` (`cod`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad_tematica`
--

LOCK TABLES `unidad_tematica` WRITE;
/*!40000 ALTER TABLE `unidad_tematica` DISABLE KEYS */;
INSERT INTO `unidad_tematica` VALUES (4,'312',1,'unidad temática 3442344234 reemplazando la primera.','2015-04-30 13:46:06'),(5,'312',2,'Que asidfjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjasdfijasodifjjfjfjajssidfjaosidj oasidfjo aosdifj faosdf aosdifjf fasdf faosidjf','2015-04-30 13:46:29'),(6,'312',3,'﻿﻿﻿Unidad temática 3. Le agrego cosas a la unidad 3.','2015-04-30 14:31:46'),(7,'312',4,'﻿﻿﻿Unidad temática 4.','2015-05-05 12:44:58');
/*!40000 ALTER TABLE `unidad_tematica` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-07  9:39:22
