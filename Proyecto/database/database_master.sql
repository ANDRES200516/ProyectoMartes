-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: localhost    Database: courses_db
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_keys` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `api_key` varchar(64) NOT NULL,
  `label` varchar(80) NOT NULL DEFAULT 'Mi API Key',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_used` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `api_keys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_keys`
--

LOCK TABLES `api_keys` WRITE;
/*!40000 ALTER TABLE `api_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `badges`
--

DROP TABLE IF EXISTS `badges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `badges` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(80) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(80) NOT NULL DEFAULT 'fa-trophy',
  `color` varchar(20) NOT NULL DEFAULT '#fbbf24',
  `points_cost` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `badges`
--

LOCK TABLES `badges` WRITE;
/*!40000 ALTER TABLE `badges` DISABLE KEYS */;
INSERT INTO `badges` VALUES (1,'first_login','Primer Paso','Iniciaste sesión por primera vez.','fa-door-open','#10b981',0,'2026-05-29 06:47:31'),(2,'first_lesson','Curiosidad Activa','Completaste tu primera lección.','fa-book-open','#38bdf8',0,'2026-05-29 06:47:31'),(3,'first_course','Graduado','Completaste tu primer curso completo.','fa-graduation-cap','#fbbf24',0,'2026-05-29 06:47:31'),(4,'streak_7','Semana de Fuego','Mantuviste una racha de 7 días consecutivos de estudio.','fa-fire','#f97316',0,'2026-05-29 06:47:31'),(5,'streak_30','Imparable','Mantuviste una racha de 30 días consecutivos de estudio.','fa-bolt','#a855f7',0,'2026-05-29 06:47:31'),(6,'quiz_ace','Mente Brillante','Obtuviste 100% en un examen.','fa-brain','#ec4899',0,'2026-05-29 06:47:31'),(7,'five_courses','Coleccionista','Completaste 5 cursos diferentes.','fa-layer-group','#14b8a6',0,'2026-05-29 06:47:31'),(8,'top_leaderboard','Leyenda','Alcanzaste el top 3 del leaderboard global.','fa-crown','#fbbf24',0,'2026-05-29 06:47:31'),(9,'night_owl','Búho Nocturno','Completaste una lección entre las 00:00 y las 05:00.','fa-moon','#818cf8',0,'2026-05-29 06:47:31'),(10,'speed_learner','Aprendiz Veloz','Completaste un curso en menos de 3 días.','fa-gauge-high','#f43f5e',0,'2026-05-29 06:47:31');
/*!40000 ALTER TABLE `badges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cert_code` (`code`),
  UNIQUE KEY `user_course_cert` (`user_id`,`course_id`),
  KEY `fk_cert_course` (`course_id`),
  CONSTRAINT `fk_cert_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cert_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
INSERT INTO `certificates` VALUES (1,3,1,'7EC8667B6AB4','2026-05-20 07:33:16');
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `level` enum('B??sico','Intermedio','Avanzado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'B??sico',
  `duration_hours` decimal(5,1) DEFAULT '0.0',
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `objectives` text COLLATE utf8mb4_unicode_ci,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_lessons` int DEFAULT '0',
  `rating_avg` decimal(3,2) DEFAULT '0.00',
  `rating_count` int DEFAULT '0',
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'assets/images/default-course.png',
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','draft') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Tecnolog??a',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'inteligencia-artificial','Inteligencia Artificial: De Cero a Experto','Este curso te llevara desde los conceptos basicos de la logica computacional hasta la implementacion de redes neuronales capaces de aprender por si solas. Aprenderas los principios matematicos detras del Machine Learning, como funcionan los algoritmos de clasificacion y como entrenar modelos con datos reales. Al final del curso seras capaz de construir tu propio perceptron y entender la arquitectura de una red neuronal profunda.','Domina los fundamentos de la IA, desde logica computacional hasta redes neuronales.','Intermedio',40.0,'Conocimientos basicos de matematicas y logica.|Python basico (recomendado).','Comprender que es la IA y sus aplicaciones reales.|Implementar un Perceptron desde cero.|Entrenar redes neuronales simples.|Aplicar algoritmos de clasificacion.|Evaluar modelos de ML.','IA, Machine Learning, Redes Neuronales, Python',12,0.00,0,'assets/images/courses/ia-thumb.jpg','assets/images/courses/ia-banner.jpg','active','Inteligencia Artificial','2026-05-20 06:07:12'),(2,'machine-learning','Machine Learning Practico con Python','Sumergete en el mundo del Machine Learning con un enfoque completamente practico. Aprenderas regresion, clasificacion, clustering y mucho mas, usando Python y las librerias mas demandadas por la industria como scikit-learn, pandas y matplotlib. Cada modulo incluye un proyecto real que podras agregar a tu portafolio profesional.','Aprende los algoritmos esenciales de ML y aplicalos en proyectos reales.','Avanzado',50.0,'Python intermedio.|Estadistica basica.|Algebra lineal basica.','Implementar algoritmos supervisados y no supervisados.|Preprocesar datos correctamente.|Evaluar y optimizar modelos.|Desplegar modelos en produccion.|Construir un portafolio de proyectos ML.','ML, Python, scikit-learn, Data Science',12,0.00,0,'assets/images/courses/ml-thumb.jpg','assets/images/courses/ml-banner.jpg','active','Inteligencia Artificial','2026-05-20 06:07:12'),(3,'python-desde-cero','Python desde Cero: La Guia Completa','Python es el lenguaje de programacion mas demandado del mundo. En este curso aprenderas todo lo que necesitas saber para convertirte en un programador Python competente. Cubrimos desde la instalacion del entorno, variables y estructuras de datos, hasta programacion orientada a objetos, manejo de archivos, APIs y mucho mas. Perfecto para principiantes sin experiencia previa en programacion.','Aprende a programar en Python desde absoluto cero hasta nivel intermedio-avanzado.','',35.0,'No se requiere experiencia previa en programacion.','Instalar y configurar Python correctamente.|Manejar variables, listas, diccionarios y funciones.|Programar orientado a objetos en Python.|Leer y escribir archivos.|Consumir APIs REST.|Crear scripts de automatizacion.','Python, Programacion, Backend, Scripting',15,0.00,0,'assets/images/courses/python-thumb.jpg','assets/images/courses/python-banner.jpg','active','Programacion','2026-05-20 06:07:12'),(4,'sql-bases-de-datos','SQL y Bases de Datos Relacionales','Las bases de datos son el corazon de cualquier aplicacion moderna. En este curso aprenderas SQL desde cero: como crear tablas, insertar datos, hacer consultas complejas con JOINs, subconsultas, indices y procedimientos almacenados. Tambien aprenderas a disenar esquemas de bases de datos eficientes usando normalizacion y buenas practicas de la industria.','Domina SQL desde lo basico hasta consultas avanzadas y diseno de bases de datos.','',25.0,'No se requiere experiencia previa.|Logica basica de computacion.','Crear y gestionar bases de datos MySQL/PostgreSQL.|Escribir consultas SELECT, INSERT, UPDATE, DELETE.|Usar JOINs para combinar tablas.|Disenar esquemas normalizados.|Optimizar consultas con indices.|Usar transacciones y procedimientos almacenados.','SQL, MySQL, PostgreSQL, Bases de Datos',10,0.00,0,'assets/images/courses/sql-thumb.jpg','assets/images/courses/sql-banner.jpg','active','Bases de Datos','2026-05-20 06:07:12'),(5,'desarrollo-web-full-stack','Desarrollo Web Full Stack Moderno','El desarrollo web Full Stack te permite crear aplicaciones completas de principio a fin. En este curso aprenderas todo el stack tecnologico moderno: desde estructurar paginas con HTML5 y estilizarlas con CSS3, hasta crear interactividad con JavaScript, construir APIs con PHP y persistir datos con MySQL. Terminaras el curso con dos proyectos reales completos en tu portafolio.','Construye aplicaciones web completas con HTML, CSS, JavaScript, PHP y MySQL.','Intermedio',60.0,'Conocimientos basicos de computacion.|Python o cualquier lenguaje de programacion (recomendado).','Crear estructuras HTML5 semanticas.|Disenar interfaces responsivas con CSS3.|Programar interactividad con JavaScript ES6+.|Construir APIs RESTful con PHP.|Gestionar bases de datos MySQL.|Desplegar aplicaciones en servidor.','HTML, CSS, JavaScript, PHP, MySQL, Full Stack',15,0.00,0,'assets/images/courses/web-thumb.jpg','assets/images/courses/web-banner.jpg','active','Desarrollo Web','2026-05-20 06:07:12'),(6,'algoritmos-geneticos','Algoritmos Geneticos y Computacion Evolutiva','La computacion evolutiva es una de las areas mas fascinantes de la inteligencia artificial. Los algoritmos geneticos imitan el proceso de seleccion natural para encontrar soluciones optimas a problemas que serian imposibles de resolver con metodos tradicionales. En este curso aprenderas los fundamentos matematicos y programaras tus propios algoritmos geneticos desde cero.','Aprende a resolver problemas complejos de optimizacion con algoritmos inspirados en la biologia.','Avanzado',30.0,'Programacion intermedia (Python recomendado).|Matematicas basicas.|Estadistica elemental.','Entender los principios de la seleccion natural aplicados a la computacion.|Implementar operadores geneticos: seleccion, cruce y mutacion.|Definir funciones de aptitud correctamente.|Aplicar AG a problemas de optimizacion reales.','Algoritmos Geneticos, IA, Optimizacion, Computacion Evolutiva',10,0.00,0,'assets/images/courses/genetic-thumb.jpg','assets/images/courses/genetic-banner.jpg','active','Inteligencia Artificial','2026-05-20 06:07:12'),(7,'data-science','Data Science: Analisis y Visualizacion de Datos','Los datos son el nuevo petroleo. En este curso aprenderas el flujo completo del Data Science: desde la recoleccion y limpieza de datos hasta el analisis exploratorio y la visualizacion de insights que generen valor para las organizaciones. Usaras las herramientas estandar de la industria: Python, pandas, NumPy, matplotlib, seaborn y Jupyter Notebooks.','Transforma datos en insights accionables con Python, pandas y visualizaciones profesionales.','Intermedio',45.0,'Python basico.|Estadistica descriptiva.|Excel o Google Sheets (recomendado).','Recolectar y limpiar datasets reales.|Realizar analisis exploratorio de datos (EDA).|Crear visualizaciones profesionales e interactivas.|Identificar patrones y tendencias en datos.|Comunicar hallazgos efectivamente.','Data Science, Python, pandas, visualizacion, EDA',12,0.00,0,'assets/images/courses/ds-thumb.jpg','assets/images/courses/ds-banner.jpg','active','Ciencia de Datos','2026-05-20 06:07:12'),(8,'javascript-moderno','JavaScript Moderno: ES6+ y mas alla','JavaScript es el lenguaje del web. En este curso aprenderas JavaScript desde sus fundamentos hasta las caracteristicas modernas de ES6+: arrow functions, destructuring, Promises, async/await, modulos y mucho mas. Luego de dominar el lenguaje, daras tus primeros pasos con React para el frontend, convirtiendote en un desarrollador JavaScript moderno y demandado.','Domina JavaScript moderno desde los fundamentos hasta async/await y primeros pasos con React.','Intermedio',55.0,'HTML y CSS basico.|Logica de programacion elemental.','Dominar JavaScript ES6+ completamente.|Trabajar con arrays, objetos y funciones de orden superior.|Manejar asincronismo con Promises y async/await.|Crear componentes con React.|Gestionar el estado de aplicaciones.|Construir proyectos reales para el portafolio.','JavaScript, ES6, React, Frontend',14,0.00,0,'assets/images/courses/js-thumb.jpg','assets/images/courses/js-banner.jpg','active','Desarrollo Web','2026-05-20 06:07:12'),(9,'regresion-lineal','Regresi??n Lineal: Fundamentos a Proyectos Reales','La regresi??n lineal es el algoritmo m??s fundamental del Machine Learning. Aprender??s c??mo construir modelos que predicen valores continuos. Cubriremos desde m??nimos cuadrados, interpretaci??n de coeficientes, validaci??n, hasta regularizaci??n con Ridge y Lasso. Implementar??s 3 proyectos reales.','Domina la regresi??n lineal desde matem??tica b??sica hasta modelos predictivos avanzados.','Intermedio',32.0,'Python b??sico.|??lgebra lineal elemental.|Estad??stica descriptiva.','Comprender teor??a de regresi??n.|Implementar desde cero en NumPy.|Usar scikit-learn.|Validar modelos correctamente.|Aplicar regularizaci??n.|Detectar multicolinealidad.|Construir 3 proyectos profesionales.','Regresi??n Lineal, Machine Learning, Predicci??n, Python, Estad??stica',27,0.00,0,'assets/images/courses/regression-thumb.jpg','assets/images/courses/regression-banner.jpg','active','Data Science','2026-05-20 16:31:33');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enrollments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `motivation` text COLLATE utf8mb4_unicode_ci,
  `knowledge_level` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weekly_hours` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_goal` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `progress_percentage` decimal(5,2) DEFAULT '0.00',
  `last_lesson_id` int DEFAULT NULL,
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_course` (`user_id`,`course_id`),
  KEY `fk_enrollments_course` (`course_id`),
  CONSTRAINT `fk_enrollments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_enrollments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
INSERT INTO `enrollments` VALUES (1,3,1,'deseo aprender ','Principiante','5','Mejorar habilidades profesionales','completed',100.00,11,'2026-05-20 06:14:44','2026-05-20 07:33:16');
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_progress`
--

DROP TABLE IF EXISTS `lesson_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_progress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `lesson_id` int NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_lesson` (`user_id`,`lesson_id`),
  KEY `fk_lp_lesson` (`lesson_id`),
  CONSTRAINT `fk_lp_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lp_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_progress`
--

LOCK TABLES `lesson_progress` WRITE;
/*!40000 ALTER TABLE `lesson_progress` DISABLE KEYS */;
INSERT INTO `lesson_progress` VALUES (1,3,12,1,'2026-05-20 07:32:52'),(2,3,2,1,'2026-05-20 07:33:01'),(3,3,3,1,'2026-05-20 07:33:05'),(4,3,4,1,'2026-05-20 07:33:11'),(5,3,5,1,'2026-05-20 07:31:59'),(6,3,6,1,'2026-05-20 07:32:20'),(7,3,9,1,'2026-05-20 07:32:39'),(9,3,7,1,'2026-05-20 07:32:24'),(10,3,8,1,'2026-05-20 07:32:29'),(12,3,10,1,'2026-05-20 07:32:44'),(13,3,11,1,'2026-05-20 07:32:48'),(18,3,1,1,'2026-05-20 07:33:16');
/*!40000 ALTER TABLE `lesson_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lessons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_type` enum('youtube','local','none') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `pdf_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_minutes` int DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `is_free` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_lesson_module` (`module_id`),
  CONSTRAINT `fk_lesson_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` VALUES (1,1,'Que es la Inteligencia Artificial','<h2>Introduccion a la IA</h2><p>La Inteligencia Artificial (IA) es la capacidad de las maquinas para imitar procesos cognitivos humanos como el aprendizaje, el razonamiento, la resolucion de problemas, la percepcion y la comprension del lenguaje.</p><h3>Tipos de IA</h3><ul><li><strong>IA Estrecha (ANI):</strong> Dise??ada para una tarea especifica (ej: reconocimiento facial, recomendaciones de Netflix).</li><li><strong>IA General (AGI):</strong> Capaz de realizar cualquier tarea intelectual humana.</li><li><strong>Superinteligencia (ASI):</strong> Forma hipotetica que supera la inteligencia humana en todos los aspectos.</li></ul><h3>Por que estudiar IA?</h3><p>La IA es la tecnologia mas transformadora del siglo XXI. Dominarla abre puertas a oportunidades laborales extraordinarias y a la capacidad de construir soluciones que impactan millones de vidas.</p>','https://www.youtube.com/embed/mJeNghZXtMo','youtube',NULL,15,1,1,'2026-05-20 06:07:12'),(2,1,'Historia y Evolucion de la IA','<h2>Historia de la Inteligencia Artificial</h2><p>La IA tiene sus raices en los trabajos de matematicos y logicos del siglo XX. El termino fue acunado oficialmente en 1956 por John McCarthy en la Conferencia de Dartmouth.</p><h3>Cronologia Clave</h3><ul><li><strong>1950:</strong> Test de Turing - Alan Turing propone una prueba de inteligencia maquina.</li><li><strong>1956:</strong> Conferencia de Dartmouth - nace la IA como disciplina academica.</li><li><strong>1980s:</strong> Sistemas expertos - la primera oleada de IA comercial.</li><li><strong>1997:</strong> Deep Blue vence al campeon mundial de ajedrez Garry Kasparov.</li><li><strong>2012:</strong> AlexNet revoluciona el reconocimiento de imagenes con Deep Learning.</li><li><strong>2016:</strong> AlphaGo vence al campeon mundial de Go.</li><li><strong>2022:</strong> ChatGPT democratiza el acceso a la IA generativa.</li></ul>','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(3,1,'Aplicaciones Reales de la IA','<h2>IA en el Mundo Real</h2><p>La Inteligencia Artificial esta presente en nuestra vida cotidiana de formas sorprendentes:</p><h3>Casos de Uso Actuales</h3><ul><li><strong>Salud:</strong> Diagnostico de cancer, prediccion de enfermedades, descubrimiento de farmacos.</li><li><strong>Finanzas:</strong> Deteccion de fraudes en tiempo real, trading algoritmico.</li><li><strong>Entretenimiento:</strong> Recomendaciones personalizadas en Netflix, Spotify, YouTube.</li><li><strong>Transporte:</strong> Vehiculos autonomos, optimizacion de rutas (Google Maps).</li><li><strong>Asistentes:</strong> Siri, Alexa, Google Assistant, ChatGPT.</li><li><strong>Vision:</strong> Reconocimiento facial, filtros de redes sociales, inspeccion industrial.</li></ul>','','none',NULL,18,3,0,'2026-05-20 06:07:12'),(4,2,'Representacion del Conocimiento','<h2>Como las Maquinas Representan el Conocimiento</h2><p>Para que una IA pueda razonar, necesita representar el conocimiento del mundo en un formato que las computadoras puedan procesar.</p><h3>Metodos de Representacion</h3><ul><li><strong>Logica Proposicional:</strong> Declaraciones verdaderas o falsas.</li><li><strong>Logica de Primer Orden:</strong> Predicados, cuantificadores y funciones.</li><li><strong>Redes Semanticas:</strong> Grafos de conceptos relacionados.</li><li><strong>Marcos (Frames):</strong> Estructuras para representar objetos y sus propiedades.</li><li><strong>Ontologias:</strong> Vocabularios formales de un dominio.</li></ul>','','none',NULL,22,1,0,'2026-05-20 06:07:12'),(5,2,'Busqueda y Solucion de Problemas','<h2>Algoritmos de Busqueda en IA</h2><p>Muchos problemas de IA se pueden reformular como una busqueda en un espacio de estados. Los algoritmos de busqueda son fundamentales en IA clasica.</p><h3>Tipos de Busqueda</h3><ul><li><strong>BFS (Busqueda en Anchura):</strong> Explora todos los nodos de un nivel antes de pasar al siguiente.</li><li><strong>DFS (Busqueda en Profundidad):</strong> Explora una rama completamente antes de retroceder.</li><li><strong>A*:</strong> Usa heuristica para encontrar el camino optimo eficientemente.</li><li><strong>Minimax:</strong> Para juegos de dos jugadores como ajedrez.</li></ul>','https://www.youtube.com/embed/oDqjPvD1T-0','youtube',NULL,25,2,0,'2026-05-20 06:07:12'),(6,2,'Razonamiento con Incertidumbre','<h2>La Probabilidad en la IA</h2><p>El mundo real es incierto. Las IA modernas usan probabilidad para razonar con informacion incompleta o ruidosa.</p><h3>Conceptos Clave</h3><ul><li><strong>Redes Bayesianas:</strong> Representan dependencias probabilisticas entre variables.</li><li><strong>Teorema de Bayes:</strong> Actualizar creencias en base a evidencia nueva.</li><li><strong>Modelos de Markov:</strong> Procesos probabilisticos con estados ocultos.</li></ul>','','none',NULL,20,3,0,'2026-05-20 06:07:12'),(7,3,'Inspiracion Biologica: La Neurona','<h2>De la Neurona Biologica al Perceptron</h2><p>El perceptron fue dise??ado como una simplificacion matematica de la neurona biologica. Comprender la neurona biologica nos ayuda a entender por que el perceptron funciona como lo hace.</p><h3>La Neurona Biologica</h3><p>Una neurona recibe se??ales electricas a traves de sus <strong>dendritas</strong>, las procesa en el <strong>soma</strong> (cuerpo celular), y si la se??al supera un umbral, envia un impulso por el <strong>axon</strong>. Esta es exactamente la logica que imita el perceptron.</p>','','none',NULL,18,1,0,'2026-05-20 06:07:12'),(8,3,'Matematica del Perceptron','<h2>El Perceptron de Rosenblatt</h2><p>Un perceptron toma multiples entradas, las pondera y produce una salida binaria. La formula es:</p><pre><code>salida = 1 si (w1*x1 + w2*x2 + ... + wn*xn + bias) >= 0\n         0 en caso contrario</code></pre><h3>Componentes</h3><ul><li><strong>Entradas (x):</strong> Los datos que el perceptron recibe.</li><li><strong>Pesos (w):</strong> La importancia de cada entrada.</li><li><strong>Bias:</strong> Permite desplazar la funcion de activacion.</li><li><strong>Funcion de activacion:</strong> Determina cuando el perceptron se activa.</li></ul>','https://www.youtube.com/embed/aircAruvnKk','youtube',NULL,30,2,0,'2026-05-20 06:07:12'),(9,3,'Implementando un Perceptron en Python','<h2>Codigo: Tu Primer Perceptron</h2><p>Implementaremos un perceptron desde cero en Python para que entiendas cada parte del algoritmo:</p><pre><code>import numpy as np\n\nclass Perceptron:\n    def __init__(self, tasa_aprendizaje=0.01, n_iteraciones=1000):\n        self.tasa = tasa_aprendizaje\n        self.n_iter = n_iteraciones\n\n    def fit(self, X, y):\n        self.pesos = np.zeros(X.shape[1])\n        self.bias = 0\n\n        for _ in range(self.n_iter):\n            for xi, yi in zip(X, y):\n                prediccion = self.predict(xi)\n                error = yi - prediccion\n                self.pesos += self.tasa * error * xi\n                self.bias += self.tasa * error\n\n    def predict(self, X):\n        suma = np.dot(X, self.pesos) + self.bias\n        return np.where(suma >= 0, 1, 0)</code></pre>','','none',NULL,35,3,0,'2026-05-20 06:07:12'),(10,4,'Arquitectura de una Red Neuronal','<h2>De Perceptron a Red Neuronal Profunda</h2><p>Una red neuronal es simplemente una coleccion de perceptrones organizados en capas. Cada capa transforma la representacion de los datos, extrayendo caracteristicas cada vez mas abstractas.</p><h3>Capas de una Red Neuronal</h3><ul><li><strong>Capa de Entrada:</strong> Recibe los datos en bruto.</li><li><strong>Capas Ocultas:</strong> Aprenden representaciones intermedias.</li><li><strong>Capa de Salida:</strong> Produce la prediccion final.</li></ul>','https://www.youtube.com/embed/aircAruvnKk','youtube',NULL,28,1,0,'2026-05-20 06:07:12'),(11,4,'Backpropagation: Como Aprenden las Redes','<h2>El Algoritmo de Retropropagacion</h2><p>Backpropagation es el algoritmo que permite entrenar redes neuronales calculando los gradientes del error con respecto a cada peso, y actualizando los pesos para minimizar el error.</p><p>El proceso es:</p><ol><li>Propagacion hacia adelante: calcular la prediccion.</li><li>Calcular el error (loss).</li><li>Propagacion hacia atras: calcular gradientes.</li><li>Actualizar pesos con descenso de gradiente.</li></ol>','','none',NULL,35,2,0,'2026-05-20 06:07:12'),(12,4,'Funciones de Activacion Modernas','<h2>Funciones de Activacion en Deep Learning</h2><p>La funcion de activacion determina si una neurona debe activarse o no. La eleccion correcta es crucial para el entrenamiento efectivo.</p><ul><li><strong>Sigmoid:</strong> Salida entre 0 y 1. Buena para clasificacion binaria.</li><li><strong>Tanh:</strong> Salida entre -1 y 1. Centrada en cero.</li><li><strong>ReLU:</strong> max(0, x). La mas popular en redes profundas.</li><li><strong>Softmax:</strong> Convierte logits en probabilidades para clasificacion multiclase.</li></ul>','','none',NULL,25,3,0,'2026-05-20 06:07:12'),(13,5,'Introducci??n al Machine Learning','Contenido introductorio sobre tipos de aprendizaje supervisado y no supervisado.','https://www.youtube.com/embed/f_uwKZIAeM0','youtube',NULL,20,1,1,'2026-05-20 06:07:12'),(14,5,'El flujo de trabajo en ML','An??lisis, recolecci??n y limpieza de datos en pipelines de Machine Learning.','','none',NULL,15,2,0,'2026-05-20 06:07:12'),(15,5,'Instalaci??n de librer??as esenciales','Instalaci??n de Numpy, Pandas y Scikit-Learn.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(16,6,'Regresi??n Lineal Simple','Modelo de regresi??n matem??tica para predecir variables continuas.','https://www.youtube.com/embed/J4Wdy0Wc_xQ','youtube',NULL,25,1,0,'2026-05-20 06:07:12'),(17,6,'Regresi??n M??ltiple','A??adiendo m??ltiples variables predictoras a nuestro modelo.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(18,6,'Evaluaci??n con R2 y MSE','M??tricas fundamentales para evaluar el error de regresi??n.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(19,7,'Clustering K-Means','Algoritmo de agrupaci??n basado en centroides para aprendizaje no supervisado.','','none',NULL,25,1,0,'2026-05-20 06:07:12'),(20,7,'Reducci??n con PCA','Reducci??n de dimensionalidad para simplificar datasets.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(21,7,'Validaci??n Cruzada','Validaci??n del modelo por K-folds para evitar sobreajuste.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(22,8,'Construcci??n del modelo clasificador','C??digo real y entrenamiento de un clasificador.','https://www.youtube.com/embed/qFJeN9V1ZsI','youtube',NULL,30,1,0,'2026-05-20 06:07:12'),(23,8,'Evaluaci??n de matriz de confusi??n','M??tricas de precisi??n, recall y puntuaci??n F1.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(24,8,'Exportaci??n de modelos en producci??n','Uso de Pickle o Joblib para serializar modelos de ML.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(25,9,'Introducci??n a Python y sintaxis','Primer contacto con el lenguaje interactivo de Python.','https://www.youtube.com/embed/TqPzwenhMj0','youtube',NULL,15,1,1,'2026-05-20 06:07:12'),(26,9,'Variables y cadenas de texto','Tipos primitivos en Python y formateo de cadenas.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(27,9,'Operaciones aritm??ticas b??sicas','Uso de operadores matem??ticos en Python.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(28,10,'Listas y manipulaci??n','Uso avanzado de listas, m??todos append, insert, remove y pop.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(29,10,'Diccionarios en Python','Almacenamiento clave-valor estructurado en diccionarios.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(30,10,'Tuplas y conjuntos (Sets)','Estructuras inmutables y conjuntos sin elementos duplicados.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(31,11,'Estructura IF, ELIF y ELSE','Flujos de decisi??n y condiciones complejas en Python.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(32,11,'Bucle FOR y funciones range','C??mo iterar sobre colecciones de forma sencilla.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(33,11,'Bucle WHILE y control break/continue','Bucle por condici??n y alteraci??n del flujo del ciclo.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(34,12,'Definici??n de clases y constructores','Uso de class y el inicializador __init__.','https://www.youtube.com/embed/pTB0EiLXUC8','youtube',NULL,25,1,0,'2026-05-20 06:07:12'),(35,12,'Herencia simple de clases','Extender clases secundarias a partir de una clase base.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(36,12,'Polimorfismo en Python','Sobreescritura de m??todos para respuestas polim??rficas.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(37,13,'Decoradores y propiedades','Modificar el comportamiento de funciones mediante decoradores.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(38,13,'Generadores (yield)','Iteradores eficientes creados con funciones generadoras.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(39,13,'Consumo de APIs REST (requests)','Hacer peticiones HTTP GET y POST para interactuar con servicios externos.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(40,14,'??Qu?? es una base de datos relacional?','Definici??n de gestor de base de datos relacional (RDBMS) y SQL.','https://www.youtube.com/embed/FR4QIeZaPeM','youtube',NULL,20,1,1,'2026-05-20 06:07:12'),(41,14,'Modelo Entidad-Relaci??n (MER)','Estructura conceptual y mapeo f??sico de tablas.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(42,14,'Instalaci??n de MySQL Workbench','Configuraci??n de servidor local y entorno de desarrollo gr??fico.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(43,15,'Uso de SELECT y WHERE','Consultar informaci??n con filtrados b??sicos en SQL.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(44,15,'Sentencias INSERT y UPDATE','Inserci??n y modificaci??n de datos de manera segura.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(45,15,'DELETE y sentencias de precauci??n','Borrados condicionales para evitar p??rdida de datos.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(46,16,'INNER JOIN en consultas m??ltiples','Unir datos de dos tablas utilizando llaves for??neas.','https://www.youtube.com/embed/9yeOJ0ZMUYw','youtube',NULL,25,1,0,'2026-05-20 06:07:12'),(47,16,'LEFT, RIGHT y FULL JOIN','Casos de uni??n de datos asim??tricos.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(48,16,'Subconsultas en WHERE','Anidaci??n de consultas SQL para reportes avanzados.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(49,17,'Creaci??n de ??ndices (Index)','Acelerar el rendimiento de consultas masivas con ??ndices.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(50,17,'Transacciones ACID (Commit/Rollback)','Asegurar consistencia de la base de datos relacional.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(51,17,'Procedimientos almacenados b??sicos','Automatizaci??n de sentencias mediante STORED PROCEDURES.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(52,18,'Estructura sem??ntica de HTML5','Etiquetas header, nav, main, article, section y footer.','https://www.youtube.com/embed/pQN-pnXPaVg','youtube',NULL,20,1,1,'2026-05-20 06:07:12'),(53,18,'Layouts modernos con CSS Flexbox','Alineaciones, direcci??n de cajas y dise??os lineales fluidos.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(54,18,'Layouts bidimensionales con CSS Grid','Estructuras complejas mediante grillas e Hitos CSS.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(55,19,'Selecci??n de elementos en el DOM','Uso de querySelector y querySelectorAll en JavaScript.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(56,19,'Eventos click, submit y input','Capturar interacciones de usuario en el frontend.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(57,19,'Fetch API y promesas as??ncronas','Llamar a servicios externos sin recargar la p??gina.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(58,20,'Estructura b??sica de PHP y sintaxis','Declaraci??n de variables, concatenaci??n y echo en PHP.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(59,20,'Orientaci??n a Objetos en PHP 8','Definici??n de clases, propiedades de acceso y m??todos.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(60,20,'Arquitectura Modelo Vista Controlador','Separaci??n de l??gica del sistema para aplicaciones mantenibles.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(61,21,'Conexi??n segura mediante PDO','Configuraci??n de credenciales y driver PDO de MySQL.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(62,21,'Consultas preparadas contra inyecci??n SQL','Seguridad en base de datos previniendo ataques maliciosos.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(63,21,'Creaci??n de endpoints de API JSON','Devolver objetos codificados desde PHP.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(64,22,'Integrando frontend JavaScript con backend PHP','Env??o de formularios v??a Fetch API hacia PHP.','https://www.youtube.com/embed/ysEN5RaKOlA','youtube',NULL,25,1,0,'2026-05-20 06:07:12'),(65,22,'Subida segura de archivos en el servidor','Validaci??n de tipos MIME y mover archivos subidos.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(66,22,'Hosting y configuraci??n web','Despliegues productivos de proyectos PHP.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(67,23,'Evoluci??n y computaci??n combinatoria','Principios evolutivos adaptados a la b??squeda heur??stica.','https://www.youtube.com/embed/1i8muvzZkPw','youtube',NULL,20,1,1,'2026-05-20 06:07:12'),(68,23,'Espacio de b??squeda y ??ptimos locales','Diferencia entre ??ptimos locales y el m??ximo global.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(69,23,'Casos pr??cticos de computaci??n evolutiva','Ejemplos reales aplicados en log??stica y dise??o estructural.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(70,24,'Definici??n del cromosoma','Modelado gen??tico binario e hiperespacial para soluciones.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(71,24,'Dise??o de la funci??n de Aptitud (Fitness)','Calificaci??n de la calidad de individuos dentro del algoritmo.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(72,24,'Poblaci??n inicial aleatoria','Generaci??n de diversidad gen??tica inicial.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(73,25,'Cruce de un punto y multipunto','Intercambio de genes entre progenitores.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(74,25,'Operador de mutaci??n por bit','A??adir mutabilidad para evitar caer en ??ptimos locales.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(75,25,'Criterios de convergencia','Definici??n de parada por generaciones o umbral de fitness.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(76,26,'Proyecto: Resolviendo el TSP con AG','C??digo completo en Python para resolver el agente viajero.','https://www.youtube.com/embed/9zfeTw-uFCw','youtube',NULL,30,1,0,'2026-05-20 06:07:12'),(77,26,'Optimizaci??n de funciones multivariables','Encuentro del ??ptimo en superficies matem??ticas rugosas.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(78,26,'Introducci??n a algoritmos evolutivos multiobjetivo','Optimizaci??n paralela de variables en conflicto.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(79,27,'??Qu?? es la Ciencia de Datos?','Introducci??n al flujo de trabajo del cient??fico de datos.','https://www.youtube.com/embed/xC-c7E5PK0Y','youtube',NULL,20,1,1,'2026-05-20 06:07:12'),(80,27,'Configurando Jupyter Lab y Anaconda','Instalaci??n de la suite integrada para an??lisis cient??fico.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(81,27,'Markdown y celdas interactivas en Jupyter','Documentar la investigaci??n matem??tica con celdas de Markdown.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(82,28,' pandas DataFrames y Series','Estructuras tabulares esenciales para el an??lisis en pandas.','https://www.youtube.com/embed/vmEHCJofslg','youtube',NULL,25,1,0,'2026-05-20 06:07:12'),(83,28,'Indexaci??n y filtrado de DataFrames','Filtrar renglones y seleccionar columnas por condiciones l??gicas.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(84,28,'Manejo de valores nulos (NaN)','Limpieza de valores perdidos por imputaci??n o eliminaci??n.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(85,29,'Estad??sticas descriptivas b??sicas','C??lculos de promedio, desviaci??n est??ndar, cuantiles y moda.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(86,29,'Matriz de correlaci??n de Pearson','Evaluaci??n matem??tica de relaciones de causa-efecto entre columnas.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(87,29,'Histogramas y densidades emp??ricas','Estudio visual del comportamiento probabil??stico de variables.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(88,30,'Visualizaci??n de datos con Matplotlib','Creaci??n de gr??ficos de barras, dispersi??n y l??neas.','https://www.youtube.com/embed/a9UrKTVEeZA','youtube',NULL,25,1,0,'2026-05-20 06:07:12'),(89,30,'Graficaci??n avanzada con Seaborn','Estilizaci??n premium de gr??ficos cient??ficos.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(90,30,'Visualizaciones interactivas con Plotly','Generaci??n de archivos HTML interactivos para dashboards.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(91,31,'Fundamentos del motor de JavaScript','Entendimiento de la consola del navegador y sintaxis elemental.','https://www.youtube.com/embed/RqQ1d1qEWlE','youtube',NULL,15,1,1,'2026-05-20 06:07:12'),(92,31,'Variables let, const y el scope global','Comprender por qu?? evitar var y c??mo funciona la inmutabilidad.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(93,31,'Declaraci??n de funciones b??sicas','Funciones por declaraci??n y por asignaci??n en JS.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(94,32,'Arrow functions en detalle','Sintaxis compacta de funciones flecha y vinculaci??n l??xica de this.','','none',NULL,20,1,0,'2026-05-20 06:07:12'),(95,32,'Desestructuraci??n (Destructuring)','Extracci??n r??pida de llaves de objetos y listas.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(96,32,'M??dulos import y export','Divisi??n del c??digo del lado del cliente en m??dulos independientes.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(97,33,'El Event Loop y asincron??a','Entender la naturaleza no bloqueante del hilo ??nico en JavaScript.','https://www.youtube.com/embed/8aGhZQkoFbQ','youtube',NULL,25,1,0,'2026-05-20 06:07:12'),(98,33,'Promesas (Promises)','Manejo de estados resolve, reject y encadenamientos then/catch.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(99,33,'Estructura Async/Await','Simplificaci??n de asincron??a con bloques limpios.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(100,34,'Introducci??n a la librer??a React','Comprender qu?? es el Virtual DOM y por qu?? usar React.','https://www.youtube.com/embed/Tn6-PIqc4UM','youtube',NULL,20,1,0,'2026-05-20 06:07:12'),(101,34,'Componentes basados en funciones y Props','Env??o de datos unidireccional utilizando props.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(102,34,'Uso b??sico del hook useState','Gesti??n de estados interactivos en componentes funcionales.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(103,35,'Creando componentes interactivos','Aplicaci??n pr??ctica de React e integraci??n de eventos.','https://www.youtube.com/embed/w7ejDZ8SWv8','youtube',NULL,30,1,0,'2026-05-20 06:07:12'),(104,35,'Consumo de APIs en useEffect','Realizar fetch de datos al montar el componente en React.','','none',NULL,20,2,0,'2026-05-20 06:07:12'),(105,35,'Despliegues est??ticos y producci??n','Compilaci??n para producci??n (npm run build) y hosting.','','none',NULL,15,3,0,'2026-05-20 06:07:12'),(106,10,'??lgebra Lineal: Vectores y Matrices','<h2>Conceptos Esenciales de ??lgebra Lineal</h2><p>La regresi??n lineal es fundamentalmente un problema de ??lgebra lineal. Necesitamos entender vectores, matrices, productos punto y espacios lineales.</p><h3>Conceptos Clave</h3><ul><li><strong>Vector:</strong> Arreglo ordenado de n??meros.</li><li><strong>Matriz:</strong> Arreglo bidimensional de n??meros.</li><li><strong>Producto Punto:</strong> Combina dos vectores en un escalar.</li><li><strong>Norma:</strong> La longitud de un vector.</li><li><strong>Producto Matricial:</strong> La operaci??n fundamental en ML.</li></ul>','https://www.youtube.com/embed/fNk_zzaMoSs','youtube',NULL,28,1,1,'2026-05-20 16:31:33'),(107,10,'C??lculo: Derivadas y Gradientes','<h2>El Gradiente: Direcci??n de M??ximo Cambio</h2><p>Para encontrar el m??nimo de una funci??n necesitamos derivadas. El gradiente es el vector de derivadas parciales.</p><h3>Conceptos Clave</h3><ul><li><strong>Derivada:</strong> Tasa de cambio instant??nea.</li><li><strong>Gradiente:</strong> Vector que apunta hacia mayor aumento.</li><li><strong>Descenso de Gradiente:</strong> Algoritmo para minimizar funciones.</li></ul>','','none',NULL,32,2,0,'2026-05-20 16:31:33'),(108,10,'Probabilidad y Estad??stica Bayesiana','<h2>Interpretaci??n Probabil??stica de Regresi??n</h2><p>La regresi??n lineal es un modelo probabil??stico donde los datos siguen una distribuci??n normal alrededor de la l??nea de regresi??n.</p>','','none',NULL,25,3,0,'2026-05-20 16:31:33'),(109,11,'M??nimos Cuadrados: La L??nea que Mejor se Ajusta','<h2>Derivando la Soluci??n de M??nimos Cuadrados</h2><p>La regresi??n lineal busca encontrar la l??nea que minimiza la suma de errores cuadrados.</p><pre><code>????? = Cov(X,Y)/Var(X)\n????? = ?? - ?????x??</code></pre>','https://www.youtube.com/embed/P8hT5nDaiMQ','youtube',NULL,35,1,1,'2026-05-20 16:31:33'),(110,11,'Implementaci??n desde Cero en NumPy','<h2>Tu Primer Modelo: Regresi??n Lineal Manual</h2><p>Implementaremos regresi??n lineal sin scikit-learn para comprender cada paso.</p><pre><code>import numpy as np\n\nclass LinearRegression:\n    def fit(self, X, y):\n        X = np.column_stack([np.ones(len(X)), X])\n        self.coef_ = np.linalg.lstsq(X, y, rcond=None)[0]\n    \n    def predict(self, X):\n        X = np.column_stack([np.ones(len(X)), X])\n        return X @ self.coef_</code></pre>','','none',NULL,40,2,0,'2026-05-20 16:31:33'),(111,11,'Uso de Scikit-Learn: API Est??ndar de ML','<h2>Regresi??n Lineal con Scikit-Learn</h2><p>En pr??ctica usamos librer??as optimizadas.</p><pre><code>from sklearn.linear_model import LinearRegression\n\nmodel = LinearRegression()\nmodel.fit(X_train, y_train)\ny_pred = model.predict(X_test)\nprint(f\"R??: {model.score(X_test, y_test)}\")</code></pre>','','none',NULL,30,3,0,'2026-05-20 16:31:33'),(112,12,'Extensi??n a M??ltiples Variables','<h2>De Uno a Muchos Predictores</h2><p>y = ????? + ?????x??? + ?????x??? + ... + ?????x??? + ??</p><p>En forma matricial: y = X?? + ??</p>','','none',NULL,33,1,0,'2026-05-20 16:31:33'),(113,12,'Interpretaci??n de Coeficientes','<h2>??Qu?? Significan los Coeficientes?</h2><p>Cada coeficiente ??_i representa el cambio esperado en y cuando x_i aumenta en 1 unidad, manteniendo otras variables constantes.</p><h3>Ejemplo</h3><p>Precio = 50000 + 200*Area + 5000*Habitaciones</p>','https://www.youtube.com/embed/nk2CQITm_eo','youtube',NULL,28,2,0,'2026-05-20 16:31:33'),(114,12,'Proyecto 1: Predicci??n de Precios de Casas','<h2>Dataset: Housing Prices</h2><p>Construiremos un modelo que predice precios de casas basado en caracter??sticas como ??rea, ubicaci??n, antig??edad.</p><ol><li>Cargar dataset</li><li>Explorar datos</li><li>Dividir train/test</li><li>Entrenar modelo</li><li>Evaluar con R?? y RMSE</li></ol>','','none',NULL,45,3,0,'2026-05-20 16:31:33'),(115,13,'M??tricas de Evaluaci??n para Regresi??n','<h2>??C??mo Saber si el Modelo es Bueno?</h2><ul><li>MAE: Promedio del error absoluto</li><li>MSE: Promedio del error al cuadrado</li><li>RMSE: Ra??z del MSE</li><li>R??: Proporci??n de varianza explicada (0-1)</li></ul>','','none',NULL,32,1,0,'2026-05-20 16:31:33'),(116,13,'Train-Test Split y Validaci??n Cruzada','<h2>Evitar Enga??arse a Uno Mismo</h2><p>Dividir datos: 70-80% entrenamiento, 20-30% prueba.</p><pre><code>from sklearn.model_selection import cross_val_score\nscores = cross_val_score(model, X, y, cv=5)\nprint(f\"R?? promedio: {scores.mean():.3f}\")</code></pre>','https://www.youtube.com/embed/fSytzGwwBVw','youtube',NULL,30,2,0,'2026-05-20 16:31:33'),(117,13,'Diagn??sticos de Residuos','<h2>??Est?? el Modelo Cumpliendo sus Supuestos?</h2><p>Los residuos deben ser aleatorios y normalmente distribuidos.</p><p>Gr??ficos clave: Q-Q Plot, Residuos vs Valores Ajustados</p>','','none',NULL,28,3,0,'2026-05-20 16:31:33'),(118,14,'El Problema del Overfitting','<h2>??Cu??ndo el Modelo Memoriza?</h2><p>Con muchas variables el modelo puede memorizar ruido. S??ntomas: alto R?? en train, bajo en test.</p>','','none',NULL,25,1,0,'2026-05-20 16:31:33'),(119,14,'Ridge Regression (L2 Regularization)','<h2>Penalizar Coeficientes Grandes</h2><p>Ridge a??ade un t??rmino de penalizaci??n:</p><p>RSS + ?? ??(??_i??)</p><pre><code>from sklearn.linear_model import Ridge\nmodel = Ridge(alpha=1.0)</code></pre>','https://www.youtube.com/embed/1dKRdX9bfIo','youtube',NULL,30,2,0,'2026-05-20 16:31:33'),(120,14,'Lasso y Elastic Net: Selecci??n de Variables','<h2>L1 y Combinaciones</h2><p>Lasso puede hacer coeficientes exactamente cero (selecci??n autom??tica).</p><pre><code>from sklearn.linear_model import Lasso, ElasticNet\nlasso = Lasso(alpha=0.1)</code></pre>','','none',NULL,32,3,0,'2026-05-20 16:31:33'),(121,15,'Supuestos de la Regresi??n Lineal','<h2>Las Premisas B??sicas</h2><ol><li>Linealidad: relaci??n es lineal</li><li>Independencia: observaciones independientes</li><li>Normalidad: residuos normales</li><li>Homocedasticidad: varianza constante</li><li>No multicolinealidad: variables no correlacionadas</li></ol>','','none',NULL,28,1,0,'2026-05-20 16:31:33'),(122,15,'Multicolinealidad y Correlaci??n','<h2>Cuando X variables se Correlacionan</h2><p>VIF mide multicolinealidad:</p><p>VIF < 5: Sin multicolinealidad</p><p>VIF 5-10: Moderada</p><p>VIF > 10: Problema serio</p>','https://www.youtube.com/embed/Esm2zvsQlh0','youtube',NULL,25,2,0,'2026-05-20 16:31:33'),(123,15,'Detecci??n y Tratamiento de Outliers','<h2>??Qu?? Hacer con Valores Extremos?</h2><ul><li>Investigar: ??Es un error?</li><li>Eliminar si es error claro</li><li>Usar regresi??n robusta</li><li>Transformar variables</li></ul>','','none',NULL,30,3,0,'2026-05-20 16:31:33'),(124,16,'Extensiones No Lineales','<h2>Cuando la Recta No es Suficiente</h2><p>Regresi??n polinomial a??ade t??rminos potencia:</p><p>y = ????? + ?????x + ?????x?? + ?????x??</p>','','none',NULL,28,1,0,'2026-05-20 16:31:33'),(125,16,'Feature Engineering para Regresi??n','<h2>Creando Nuevas Caracter??sticas</h2><pre><code>from sklearn.preprocessing import PolynomialFeatures\npoly = PolynomialFeatures(degree=2)\nX_poly = poly.fit_transform(X)</code></pre>','https://www.youtube.com/embed/neXJc-_f5U4','youtube',NULL,32,2,0,'2026-05-20 16:31:33'),(126,16,'Proyecto 2: Predicci??n de Demanda','<h2>Dataset: Predicci??n de Demanda de Energ??a</h2><p>Predice demanda basada en temperatura, hora del d??a, etc.</p>','','none',NULL,40,3,0,'2026-05-20 16:31:33'),(127,17,'Caracter??sticas de Datos Temporales','<h2>Cuando el Tiempo Importa</h2><p>Los datos de series temporales tienen autocorrelaci??n: observaciones cercanas est??n relacionadas.</p>','','none',NULL,30,1,0,'2026-05-20 16:31:33'),(128,17,'Tendencias y Estacionalidad','<h2>Descomposici??n de Series Temporales</h2><ul><li>Tendencia: movimiento de largo plazo</li><li>Estacionalidad: patrones repetitivos</li><li>Residual: ruido aleatorio</li></ul>','https://www.youtube.com/embed/e8Yw4alG16Q','youtube',NULL,28,2,0,'2026-05-20 16:31:33'),(129,17,'Proyecto 3: Predicci??n de Ventas Mensuales','<h2>Dataset: Ventas Hist??ricas</h2><p>Predice ventas futuras usando variables temporales: mes, estaci??n, tendencia.</p>','','none',NULL,45,3,0,'2026-05-20 16:31:33'),(130,18,'Integraci??n de Conceptos','<h2>Construyendo un Sistema Predictivo Profesional</h2><p>Integraremos todo lo aprendido en un pipeline completo.</p>','','none',NULL,50,1,0,'2026-05-20 16:31:33'),(131,18,'Despliegue en Producci??n','<h2>De Jupyter a Producci??n</h2><p>Guardar modelo entrenado y crear API simple con Flask.</p>','https://www.youtube.com/embed/BS8uS-4NcUY','youtube',NULL,40,2,0,'2026-05-20 16:31:33'),(132,18,'Portfolio: Presentando tu Trabajo','<h2>Documentaci??n y Presentaci??n Profesional</h2><p>Documentar, visualizar y presentar tu proyecto de forma impactante.</p>','','none',NULL,35,3,0,'2026-05-20 16:31:33');
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_module_course` (`course_id`),
  CONSTRAINT `fk_module_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,1,'Fundamentos de la IA','Historia, conceptos basicos y aplicaciones de la Inteligencia Artificial en el mundo real',1,'2026-05-20 06:07:12'),(2,1,'Logica y Representacion del Conocimiento','Como las maquinas razonan y representan informacion del mundo',2,'2026-05-20 06:07:12'),(3,1,'El Perceptron: Primera Red Neuronal','Teoria matematica y practica del perceptron de Rosenblatt',3,'2026-05-20 06:07:12'),(4,1,'Redes Neuronales y Deep Learning','Arquitecturas modernas y entrenamiento de redes neuronales profundas',4,'2026-05-20 06:07:12'),(5,2,'Introduccion al Machine Learning','Tipos de aprendizaje, ecosistema de herramientas y flujo de trabajo en ML',1,'2026-05-20 06:07:12'),(6,2,'Aprendizaje Supervisado','Regresion, clasificacion, evaluacion y validacion cruzada de modelos',2,'2026-05-20 06:07:12'),(7,2,'Aprendizaje No Supervisado','Clustering, reduccion de dimensionalidad y deteccion de anomalias',3,'2026-05-20 06:07:12'),(8,2,'Proyectos Reales de ML','Aplicar Machine Learning a problemas reales con datasets publicos',4,'2026-05-20 06:07:12'),(9,3,'Primeros Pasos con Python','Instalacion, entorno de desarrollo y primeros programas',1,'2026-05-20 06:07:12'),(10,3,'Estructuras de Datos','Listas, diccionarios, tuplas y conjuntos en profundidad',2,'2026-05-20 06:07:12'),(11,3,'Control de Flujo y Funciones','Condicionales, bucles, funciones propias y recursion',3,'2026-05-20 06:07:12'),(12,3,'Programacion Orientada a Objetos','Clases, herencia, encapsulamiento y polimorfismo en Python',4,'2026-05-20 06:07:12'),(13,3,'Python Avanzado y APIs','Decoradores, generadores, context managers y consumo de APIs REST',5,'2026-05-20 06:07:12'),(14,4,'Fundamentos de Bases de Datos','Modelo relacional, entidades, atributos y relaciones',1,'2026-05-20 06:07:12'),(15,4,'SQL Basico: CRUD Completo','SELECT, INSERT, UPDATE, DELETE y clausulas fundamentales',2,'2026-05-20 06:07:12'),(16,4,'SQL Avanzado: JOINs y Subconsultas','Combinacion de tablas, consultas anidadas y vistas',3,'2026-05-20 06:07:12'),(17,4,'Optimizacion y Buenas Practicas','Indices, transacciones, normalizacion y procedimientos almacenados',4,'2026-05-20 06:07:12'),(18,5,'HTML5 y CSS3 Moderno','Semantica HTML5, Flexbox, CSS Grid y animaciones CSS',1,'2026-05-20 06:07:12'),(19,5,'JavaScript para el Frontend','Manipulacion del DOM, eventos, fetch y almacenamiento local',2,'2026-05-20 06:07:12'),(20,5,'PHP y Programacion del Servidor','PHP 8, programacion orientada a objetos y arquitectura MVC',3,'2026-05-20 06:07:12'),(21,5,'MySQL y Bases de Datos en PHP','PDO, consultas preparadas, relaciones y migraciones',4,'2026-05-20 06:07:12'),(22,5,'Proyecto Final: App Completa','Construir una aplicacion web real de principio a fin con despliegue',5,'2026-05-20 06:07:12'),(23,6,'Introduccion a la Computacion Evolutiva','Principios biologicos aplicados a la computacion y optimizacion',1,'2026-05-20 06:07:12'),(24,6,'Estructura de un Algoritmo Genetico','Poblacion, cromosomas, genes, codificacion y funcion de aptitud',2,'2026-05-20 06:07:12'),(25,6,'Operadores Geneticos en Detalle','Seleccion por torneo, cruce de un punto y mutacion adaptativa',3,'2026-05-20 06:07:12'),(26,6,'Aplicaciones y Proyectos Finales','Resolver problemas de optimizacion reales: TSP, knapsack y scheduling',4,'2026-05-20 06:07:12'),(27,7,'El Rol del Data Scientist','Flujo de trabajo CRISP-DM, herramientas y mercado laboral en 2024',1,'2026-05-20 06:07:12'),(28,7,'Recoleccion y Limpieza de Datos','Web scraping, APIs publicas y preprocesamiento con pandas',2,'2026-05-20 06:07:12'),(29,7,'Analisis Exploratorio (EDA)','Estadisticas descriptivas, distribuciones y correlaciones',3,'2026-05-20 06:07:12'),(30,7,'Visualizacion Avanzada de Datos','matplotlib, seaborn, plotly y dashboards interactivos',4,'2026-05-20 06:07:12'),(31,8,'JavaScript Fundamentals','Tipos de datos, variables, operadores, funciones y scope',1,'2026-05-20 06:07:12'),(32,8,'JavaScript ES6+ Moderno','Arrow functions, destructuring, spread, modules y clases',2,'2026-05-20 06:07:12'),(33,8,'Asincronismo en JavaScript','Event loop, callbacks, Promises, async/await y Fetch API',3,'2026-05-20 06:07:12'),(34,8,'Introduccion a React','Componentes, JSX, props, state, useState y useEffect',4,'2026-05-20 06:07:12'),(35,8,'Proyecto: App React Completa','Construir una SPA con React, estado global y consumo de APIs',5,'2026-05-20 06:07:12'),(36,9,'Fundamentos Matem??ticos de la Regresi??n','Conceptos de ??lgebra lineal, geometr??a y c??lculo aplicados a regresi??n',1,'2026-05-20 16:31:33'),(37,9,'Regresi??n Lineal Simple: Teor??a y Pr??ctica','De una variable independiente a predicciones: la base de todo',2,'2026-05-20 16:31:33'),(38,9,'Regresi??n M??ltiple: Varios Predictores','Extensi??n a m??ltiples variables e interpretaci??n de coeficientes',3,'2026-05-20 16:31:33'),(39,9,'Validaci??n y Evaluaci??n de Modelos','M??tricas, train-test split, cross-validation y diagn??sticos',4,'2026-05-20 16:31:33'),(40,9,'Regularizaci??n: Ridge, Lasso y Elastic Net','T??cnicas para prevenir overfitting y mejorar generalizaci??n',5,'2026-05-20 16:31:33'),(41,9,'Supuestos del Modelo y Diagn??sticos','Verificar normalidad, homocedasticidad, multicolinealidad',6,'2026-05-20 16:31:33'),(42,9,'Regresi??n Polinomial y No Lineal','Extender regresi??n lineal a relaciones curvadas',7,'2026-05-20 16:31:33'),(43,9,'Series Temporales: Predicci??n de Tendencias','Aplicar regresi??n a datos temporales',8,'2026-05-20 16:31:33'),(44,9,'Proyecto Final: Sistema Predictivo Integral','Construir una aplicaci??n completa de predicci??n',9,'2026-05-20 16:31:33');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_notif_user` (`user_id`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,3,'success','¡Felicitaciones! Has completado el curso y obtenido tu certificado.','?action=certificate&code=7EC8667B6AB4',0,'2026-05-20 07:33:16');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz_attempts`
--

DROP TABLE IF EXISTS `quiz_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz_attempts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_id` varchar(80) NOT NULL,
  `lesson_id` int NOT NULL,
  `score` int NOT NULL DEFAULT '0',
  `passed` tinyint(1) NOT NULL DEFAULT '0',
  `answers` text,
  `attempted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_lesson_quiz` (`user_id`,`lesson_id`),
  KEY `idx_quiz_user_course` (`user_id`,`course_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_attempts`
--

LOCK TABLES `quiz_attempts` WRITE;
/*!40000 ALTER TABLE `quiz_attempts` DISABLE KEYS */;
INSERT INTO `quiz_attempts` VALUES (1,3,'sql-bases-datos',37,100,1,'{\"answer\":\"b\"}','2026-05-19 19:44:29'),(2,3,'sql-bases-datos',40,100,1,'{\"answers\":{\"1\":\"b\",\"2\":\"b\",\"3\":\"b\",\"4\":\"b\",\"5\":\"b\",\"6\":\"b\",\"7\":\"b\",\"8\":\"b\",\"9\":\"b\",\"10\":\"b\",\"11\":\"b\",\"12\":\"b\",\"13\":\"b\",\"14\":\"b\",\"15\":\"b\",\"16\":\"b\",\"17\":\"b\",\"18\":\"b\",\"19\":\"b\",\"20\":\"b\"},\"answer\":\"\",\"passing_score\":80}','2026-05-19 21:42:58'),(4,3,'sql-bases-datos',43,100,1,'{\"answers\":{\"1\":\"b\",\"2\":\"b\",\"3\":\"b\",\"4\":\"b\",\"5\":\"b\",\"6\":\"b\",\"7\":\"b\",\"8\":\"b\",\"9\":\"b\",\"10\":\"b\",\"11\":\"b\",\"12\":\"b\",\"13\":\"b\",\"14\":\"b\",\"15\":\"b\",\"16\":\"b\",\"17\":\"b\",\"18\":\"b\",\"19\":\"b\",\"20\":\"b\"},\"answer\":\"\",\"passing_score\":80}','2026-05-19 21:44:06'),(5,3,'sql-bases-datos',46,100,1,'{\"answers\":{\"1\":\"b\",\"2\":\"b\",\"3\":\"b\",\"4\":\"b\",\"5\":\"b\",\"6\":\"b\",\"7\":\"b\",\"8\":\"b\",\"9\":\"b\",\"10\":\"b\",\"11\":\"b\",\"12\":\"b\",\"13\":\"b\",\"14\":\"b\",\"15\":\"b\",\"16\":\"b\",\"17\":\"b\",\"18\":\"b\",\"19\":\"b\",\"20\":\"b\"},\"answer\":\"\",\"passing_score\":80}','2026-05-19 21:45:04');
/*!40000 ALTER TABLE `quiz_attempts` ENABLE KEYS */;
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
  `course_id` int NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_course_review` (`user_id`,`course_id`),
  KEY `fk_review_course` (`course_id`),
  CONSTRAINT `fk_review_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_badges`
--

DROP TABLE IF EXISTS `user_badges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_badges` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `badge_id` int NOT NULL,
  `awarded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_badge` (`user_id`,`badge_id`),
  KEY `badge_id` (`badge_id`),
  CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_badges`
--

LOCK TABLES `user_badges` WRITE;
/*!40000 ALTER TABLE `user_badges` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_badges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_points`
--

DROP TABLE IF EXISTS `user_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_points` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `points` int NOT NULL DEFAULT '0',
  `reason` varchar(120) NOT NULL,
  `reference_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_points_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_points`
--

LOCK TABLES `user_points` WRITE;
/*!40000 ALTER TABLE `user_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_streaks`
--

DROP TABLE IF EXISTS `user_streaks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_streaks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `current_streak` int NOT NULL DEFAULT '0',
  `longest_streak` int NOT NULL DEFAULT '0',
  `last_activity` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `user_streaks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_streaks`
--

LOCK TABLES `user_streaks` WRITE;
/*!40000 ALTER TABLE `user_streaks` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_streaks` ENABLE KEYS */;
UNLOCK TABLES;

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
  `status` enum('pending','approved','rejected','suspended') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','sebastian321hernandezno@gmail.com','Sebastian Andres Hernandez Noriega ','3004659844','','$2y$10$FY8zTXGbwBm4rp1GiXUsQuFqu96lg8y/C.sCxoWFKpFZ/mNgmuICm','admin','approved','2026-05-15 16:09:23'),(3,'user','sebastian321hernandezno@gmail.com','Jesus Alfonso Lucumi ','3004659844','user_3_1778955476.jpg','$2y$10$oft4GgbQMAxmgpF.ljdolOf.VV0vgaF2O/2ExEwkSuvHmNaokCyjq','user','approved','2026-05-15 16:22:28'),(4,'luis','sh1021394280@gmail.com','','','','$2y$10$Gybb1NkmCiok.VzaQKqzVe9pDH3W4UlAHGcqHhHNR7wXGbAr/k8p6','user','approved','2026-05-15 18:46:26'),(10,'Sebastian','sebastian321hernandezno@gmail.com','','','','$2y$10$W/kp2Em/TgG3Y0NIJLemn.Mo6zHL8IA2CBzF7REWtpSKg6GPNaRcW','user','approved','2026-05-16 07:40:29'),(11,'Pedro ','sh1021394280@gmail.com','','','','$2y$10$ZOn0gBepP29nNdc8afu8nuyCbvTLYF8giFSNMElWp7iz70nNxIO9e','user','approved','2026-05-16 07:42:43'),(12,'sebas','sh1021394280@gmail.com','','','','$2y$10$A5G.JGkA0R30cXvP8TMDNOlWA/mOCxMVWwnemXS1Ds66NFKgiuwam','user','rejected','2026-05-16 19:10:30'),(13,'nata ','paolaagudelo082004@gmail.com','','','','$2y$10$T6MqMajURWwA01oooc1WSunmH1NFsRKKl.RHWr6TGDBANQkktT.GO','user','approved','2026-05-16 19:12:45'),(15,'pedro','jose200516@gmail.com','','','','$2y$10$ylCWEoI2Szh1sFTzJy4i3uR6lUa7Jmqk1c.Yen.x7XdMn1qcx.7q2','user','rejected','2026-05-18 19:36:27'),(16,'pedro luis','jose200516@gmail.com','','','','$2y$10$X5v8oMBKm.yWeZ64TOlTh.hi1WFNk7rdEG0r/MoRDJstTJL9byqV6','user','approved','2026-05-18 19:43:26'),(17,'vera ','veranoriega04@gmail.com','','','','$2y$10$0qKqLkyS9udEU/iN5pi1G.X6yzHdIygCo/do7bcVAxc5oZPqt8/qu','user','approved','2026-05-18 19:59:13'),(18,'jesus ','ninojesus816@gmail.com','','','','$2y$10$XN6tZ08K9CUzf1lAjPxDIe6Mk9S4IsXLoJzNQDDVgrgvpd5qWkdb2','user','approved','2026-05-18 20:03:42'),(19,'pablo','sebastian321hernandezno@gmail.com','','','','$2y$10$tF9qcqzkhWoommYJnWQKG./K6JnPJ6hSOqdzsy24pQtFpPSGrV8Qy','user','pending','2026-05-29 05:55:22'),(20,'tester_1780035602','test@example.com','','','','$2y$10$087aDETddV73W8i5HVR7nOaz7A8NqTLZ5AxCZ3NCq7QQ6JAUh7yJG','user','approved','2026-05-29 06:20:02');
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

-- Dump completed on 2026-05-29  1:48:58
