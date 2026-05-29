-- ============================================================
-- RE-INSTALACIÓN Y LIMPIEZA DE TABLAS DE CURSOS
-- ============================================================
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Eliminar tablas existentes para recrearlas limpiamente
DROP TABLE IF EXISTS `lesson_progress`;
DROP TABLE IF EXISTS `lessons`;
DROP TABLE IF EXISTS `modules`;
DROP TABLE IF EXISTS `course_modules`; -- Por si existía con otro nombre
DROP TABLE IF EXISTS `certificates`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `progress`; -- Tabla de progreso vieja si existía

-- 2. Asegurar columnas correctas en `users`
ALTER TABLE `users` MODIFY `status` ENUM('pending','approved','rejected','suspended') DEFAULT 'pending';

-- 3. Limpiar tabla `courses` y actualizar su estructura
DELETE FROM `courses`;
ALTER TABLE `courses` AUTO_INCREMENT = 1;
ALTER TABLE `courses` MODIFY `status` ENUM('active','inactive','draft') DEFAULT 'draft';
ALTER TABLE `courses` MODIFY `level` ENUM('Básico','Intermedio','Avanzado') NOT NULL DEFAULT 'Básico';

-- Agregar o modificar columnas necesarias en `courses`
-- Usamos procedimientos o simplemente intentamos agregarlas ignorando fallas comunes
-- En MySQL, si la columna ya existe saltará un error silencioso o podemos usar ALTER TABLE directamente si sabemos cuáles faltan.
-- Para estar 100% seguros de que la estructura es la correcta, podemos reconstruir o simplemente alterar de forma segura.
-- Dado que ya comprobamos que 'short_description', etc. ya existen en la base de datos, no necesitamos volver a agregarlas, pero podemos recrear la tabla `courses` si queremos asegurar homogeneidad absoluta.
-- Vamos a recrear `courses` también para que esté en un estado perfecto.
DROP TABLE IF EXISTS `enrollments`; -- Borramos enrollments para poder recrear courses de forma segura
DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(150) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `short_description` TEXT NULL,
  `level` ENUM('Básico','Intermedio','Avanzado') NOT NULL DEFAULT 'Básico',
  `duration_hours` DECIMAL(5,1) DEFAULT 0.0,
  `requirements` TEXT NULL,
  `objectives` TEXT NULL,
  `tags` VARCHAR(255) NULL,
  `total_lessons` INT DEFAULT 0,
  `rating_avg` DECIMAL(3,2) DEFAULT 0.00,
  `rating_count` INT DEFAULT 0,
  `thumbnail` VARCHAR(255) DEFAULT 'assets/images/default-course.png',
  `banner` VARCHAR(255) NULL,
  `status` ENUM('active','inactive','draft') DEFAULT 'draft',
  `category` VARCHAR(100) DEFAULT 'Tecnología',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recrear `enrollments`
CREATE TABLE `enrollments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `motivation` TEXT NULL,
  `knowledge_level` VARCHAR(50) DEFAULT NULL,
  `weekly_hours` VARCHAR(20) DEFAULT NULL,
  `main_goal` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('active','completed') DEFAULT 'active',
  `progress_percentage` DECIMAL(5,2) DEFAULT 0.00,
  `last_lesson_id` INT NULL,
  `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `completed_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_course` (`user_id`,`course_id`),
  CONSTRAINT `fk_enrollments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_enrollments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Recrear `modules`
CREATE TABLE `modules` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `course_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_module_course` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Recrear `lessons`
CREATE TABLE `lessons` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `module_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NULL,
  `video_url` VARCHAR(500) NULL,
  `video_type` ENUM('youtube','local','none') DEFAULT 'none',
  `pdf_url` VARCHAR(255) NULL,
  `duration_minutes` INT DEFAULT 0,
  `sort_order` INT DEFAULT 0,
  `is_free` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_lesson_module` FOREIGN KEY (`module_id`) REFERENCES `modules`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Recrear `lesson_progress`
CREATE TABLE `lesson_progress` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `lesson_id` INT NOT NULL,
  `completed` TINYINT(1) DEFAULT 0,
  `completed_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_lesson` (`user_id`, `lesson_id`),
  CONSTRAINT `fk_lp_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lp_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Recrear `certificates`
CREATE TABLE `certificates` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `code` VARCHAR(64) NOT NULL,
  `issued_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cert_code` (`code`),
  UNIQUE KEY `user_course_cert` (`user_id`, `course_id`),
  CONSTRAINT `fk_cert_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cert_course` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Recrear `reviews`
CREATE TABLE `reviews` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `rating` TINYINT NOT NULL,
  `comment` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_course_review` (`user_id`, `course_id`),
  CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_course` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Recrear `notifications`
CREATE TABLE `notifications` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `message` TEXT NOT NULL,
  `link` VARCHAR(255) NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- INSERTAR LOS 8 CURSOS
-- ============================================================
INSERT INTO `courses` (`id`, `slug`,`title`,`short_description`,`description`,`level`,`duration_hours`,`thumbnail`,`banner`,`requirements`,`objectives`,`category`,`tags`,`status`,`total_lessons`) VALUES
(1, 'inteligencia-artificial','Inteligencia Artificial: De Cero a Experto','Domina los fundamentos de la IA, desde logica computacional hasta redes neuronales.','Este curso te llevara desde los conceptos basicos de la logica computacional hasta la implementacion de redes neuronales capaces de aprender por si solas. Aprenderas los principios matematicos detras del Machine Learning, como funcionan los algoritmos de clasificacion y como entrenar modelos con datos reales. Al final del curso seras capaz de construir tu propio perceptron y entender la arquitectura de una red neuronal profunda.','Intermedio',40.0,'assets/images/courses/ia-thumb.jpg','assets/images/courses/ia-banner.jpg','Conocimientos basicos de matematicas y logica.|Python basico (recomendado).','Comprender que es la IA y sus aplicaciones reales.|Implementar un Perceptron desde cero.|Entrenar redes neuronales simples.|Aplicar algoritmos de clasificacion.|Evaluar modelos de ML.','Inteligencia Artificial','IA, Machine Learning, Redes Neuronales, Python','active',12),

(2, 'machine-learning','Machine Learning Practico con Python','Aprende los algoritmos esenciales de ML y aplicalos en proyectos reales.','Sumergete en el mundo del Machine Learning con un enfoque completamente practico. Aprenderas regresion, clasificacion, clustering y mucho mas, usando Python y las librerias mas demandadas por la industria como scikit-learn, pandas y matplotlib. Cada modulo incluye un proyecto real que podras agregar a tu portafolio profesional.','Avanzado',50.0,'assets/images/courses/ml-thumb.jpg','assets/images/courses/ml-banner.jpg','Python intermedio.|Estadistica basica.|Algebra lineal basica.','Implementar algoritmos supervisados y no supervisados.|Preprocesar datos correctamente.|Evaluar y optimizar modelos.|Desplegar modelos en produccion.|Construir un portafolio de proyectos ML.','Inteligencia Artificial','ML, Python, scikit-learn, Data Science','active',12),

(3, 'python-desde-cero','Python desde Cero: La Guia Completa','Aprende a programar en Python desde absoluto cero hasta nivel intermedio-avanzado.','Python es el lenguaje de programacion mas demandado del mundo. En este curso aprenderas todo lo que necesitas saber para convertirte en un programador Python competente. Cubrimos desde la instalacion del entorno, variables y estructuras de datos, hasta programacion orientada a objetos, manejo de archivos, APIs y mucho mas. Perfecto para principiantes sin experiencia previa en programacion.','Basico',35.0,'assets/images/courses/python-thumb.jpg','assets/images/courses/python-banner.jpg','No se requiere experiencia previa en programacion.','Instalar y configurar Python correctamente.|Manejar variables, listas, diccionarios y funciones.|Programar orientado a objetos en Python.|Leer y escribir archivos.|Consumir APIs REST.|Crear scripts de automatizacion.','Programacion','Python, Programacion, Backend, Scripting','active',15),

(4, 'sql-bases-de-datos','SQL y Bases de Datos Relacionales','Domina SQL desde lo basico hasta consultas avanzadas y diseno de bases de datos.','Las bases de datos son el corazon de cualquier aplicacion moderna. En este curso aprenderas SQL desde cero: como crear tablas, insertar datos, hacer consultas complejas con JOINs, subconsultas, indices y procedimientos almacenados. Tambien aprenderas a disenar esquemas de bases de datos eficientes usando normalizacion y buenas practicas de la industria.','Basico',25.0,'assets/images/courses/sql-thumb.jpg','assets/images/courses/sql-banner.jpg','No se requiere experiencia previa.|Logica basica de computacion.','Crear y gestionar bases de datos MySQL/PostgreSQL.|Escribir consultas SELECT, INSERT, UPDATE, DELETE.|Usar JOINs para combinar tablas.|Disenar esquemas normalizados.|Optimizar consultas con indices.|Usar transacciones y procedimientos almacenados.','Bases de Datos','SQL, MySQL, PostgreSQL, Bases de Datos','active',10),

(5, 'desarrollo-web-full-stack','Desarrollo Web Full Stack Moderno','Construye aplicaciones web completas con HTML, CSS, JavaScript, PHP y MySQL.','El desarrollo web Full Stack te permite crear aplicaciones completas de principio a fin. En este curso aprenderas todo el stack tecnologico moderno: desde estructurar paginas con HTML5 y estilizarlas con CSS3, hasta crear interactividad con JavaScript, construir APIs con PHP y persistir datos con MySQL. Terminaras el curso con dos proyectos reales completos en tu portafolio.','Intermedio',60.0,'assets/images/courses/web-thumb.jpg','assets/images/courses/web-banner.jpg','Conocimientos basicos de computacion.|Python o cualquier lenguaje de programacion (recomendado).','Crear estructuras HTML5 semanticas.|Disenar interfaces responsivas con CSS3.|Programar interactividad con JavaScript ES6+.|Construir APIs RESTful con PHP.|Gestionar bases de datos MySQL.|Desplegar aplicaciones en servidor.','Desarrollo Web','HTML, CSS, JavaScript, PHP, MySQL, Full Stack','active',15),

(6, 'algoritmos-geneticos','Algoritmos Geneticos y Computacion Evolutiva','Aprende a resolver problemas complejos de optimizacion con algoritmos inspirados en la biologia.','La computacion evolutiva es una de las areas mas fascinantes de la inteligencia artificial. Los algoritmos geneticos imitan el proceso de seleccion natural para encontrar soluciones optimas a problemas que serian imposibles de resolver con metodos tradicionales. En este curso aprenderas los fundamentos matematicos y programaras tus propios algoritmos geneticos desde cero.','Avanzado',30.0,'assets/images/courses/genetic-thumb.jpg','assets/images/courses/genetic-banner.jpg','Programacion intermedia (Python recomendado).|Matematicas basicas.|Estadistica elemental.','Entender los principios de la seleccion natural aplicados a la computacion.|Implementar operadores geneticos: seleccion, cruce y mutacion.|Definir funciones de aptitud correctamente.|Aplicar AG a problemas de optimizacion reales.','Inteligencia Artificial','Algoritmos Geneticos, IA, Optimizacion, Computacion Evolutiva','active',10),

(7, 'data-science','Data Science: Analisis y Visualizacion de Datos','Transforma datos en insights accionables con Python, pandas y visualizaciones profesionales.','Los datos son el nuevo petroleo. En este curso aprenderas el flujo completo del Data Science: desde la recoleccion y limpieza de datos hasta el analisis exploratorio y la visualizacion de insights que generen valor para las organizaciones. Usaras las herramientas estandar de la industria: Python, pandas, NumPy, matplotlib, seaborn y Jupyter Notebooks.','Intermedio',45.0,'assets/images/courses/ds-thumb.jpg','assets/images/courses/ds-banner.jpg','Python basico.|Estadistica descriptiva.|Excel o Google Sheets (recomendado).','Recolectar y limpiar datasets reales.|Realizar analisis exploratorio de datos (EDA).|Crear visualizaciones profesionales e interactivas.|Identificar patrones y tendencias en datos.|Comunicar hallazgos efectivamente.','Ciencia de Datos','Data Science, Python, pandas, visualizacion, EDA','active',12),

(8, 'javascript-moderno','JavaScript Moderno: ES6+ y mas alla','Domina JavaScript moderno desde los fundamentos hasta async/await y primeros pasos con React.','JavaScript es el lenguaje del web. En este curso aprenderas JavaScript desde sus fundamentos hasta las caracteristicas modernas de ES6+: arrow functions, destructuring, Promises, async/await, modulos y mucho mas. Luego de dominar el lenguaje, daras tus primeros pasos con React para el frontend, convirtiendote en un desarrollador JavaScript moderno y demandado.','Intermedio',55.0,'assets/images/courses/js-thumb.jpg','assets/images/courses/js-banner.jpg','HTML y CSS basico.|Logica de programacion elemental.','Dominar JavaScript ES6+ completamente.|Trabajar con arrays, objetos y funciones de orden superior.|Manejar asincronismo con Promises y async/await.|Crear componentes con React.|Gestionar el estado de aplicaciones.|Construir proyectos reales para el portafolio.','Desarrollo Web','JavaScript, ES6, React, Frontend','active',14);

-- ============================================================
-- MÓDULOS DE EJEMPLO
-- ============================================================
-- Curso 1: Inteligencia Artificial (id=1)
INSERT INTO `modules` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(1, 1, 'Fundamentos de la IA', 'Historia, conceptos basicos y aplicaciones de la Inteligencia Artificial en el mundo real', 1),
(2, 1, 'Logica y Representacion del Conocimiento', 'Como las maquinas razonan y representan informacion del mundo', 2),
(3, 1, 'El Perceptron: Primera Red Neuronal', 'Teoria matematica y practica del perceptron de Rosenblatt', 3),
(4, 1, 'Redes Neuronales y Deep Learning', 'Arquitecturas modernas y entrenamiento de redes neuronales profundas', 4);

-- Curso 2: Machine Learning (id=2)
INSERT INTO `modules` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(5, 2, 'Introduccion al Machine Learning', 'Tipos de aprendizaje, ecosistema de herramientas y flujo de trabajo en ML', 1),
(6, 2, 'Aprendizaje Supervisado', 'Regresion, clasificacion, evaluacion y validacion cruzada de modelos', 2),
(7, 2, 'Aprendizaje No Supervisado', 'Clustering, reduccion de dimensionalidad y deteccion de anomalias', 3),
(8, 2, 'Proyectos Reales de ML', 'Aplicar Machine Learning a problemas reales con datasets publicos', 4);

-- Curso 3: Python desde Cero (id=3)
INSERT INTO `modules` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(9, 3, 'Primeros Pasos con Python', 'Instalacion, entorno de desarrollo y primeros programas', 1),
(10, 3, 'Estructuras de Datos', 'Listas, diccionarios, tuplas y conjuntos en profundidad', 2),
(11, 3, 'Control de Flujo y Funciones', 'Condicionales, bucles, funciones propias y recursion', 3),
(12, 3, 'Programacion Orientada a Objetos', 'Clases, herencia, encapsulamiento y polimorfismo en Python', 4),
(13, 3, 'Python Avanzado y APIs', 'Decoradores, generadores, context managers y consumo de APIs REST', 5);

-- Curso 4: SQL y Bases de Datos (id=4)
INSERT INTO `modules` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(14, 4, 'Fundamentos de Bases de Datos', 'Modelo relacional, entidades, atributos y relaciones', 1),
(15, 4, 'SQL Basico: CRUD Completo', 'SELECT, INSERT, UPDATE, DELETE y clausulas fundamentales', 2),
(16, 4, 'SQL Avanzado: JOINs y Subconsultas', 'Combinacion de tablas, consultas anidadas y vistas', 3),
(17, 4, 'Optimizacion y Buenas Practicas', 'Indices, transacciones, normalizacion y procedimientos almacenados', 4);

-- Curso 5: Desarrollo Web Full Stack (id=5)
INSERT INTO `modules` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(18, 5, 'HTML5 y CSS3 Moderno', 'Semantica HTML5, Flexbox, CSS Grid y animaciones CSS', 1),
(19, 5, 'JavaScript para el Frontend', 'Manipulacion del DOM, eventos, fetch y almacenamiento local', 2),
(20, 5, 'PHP y Programacion del Servidor', 'PHP 8, programacion orientada a objetos y arquitectura MVC', 3),
(21, 5, 'MySQL y Bases de Datos en PHP', 'PDO, consultas preparadas, relaciones y migraciones', 4),
(22, 5, 'Proyecto Final: App Completa', 'Construir una aplicacion web real de principio a fin con despliegue', 5);

-- Curso 6: Algoritmos Genéticos (id=6)
INSERT INTO `modules` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(23, 6, 'Introduccion a la Computacion Evolutiva', 'Principios biologicos aplicados a la computacion y optimizacion', 1),
(24, 6, 'Estructura de un Algoritmo Genetico', 'Poblacion, cromosomas, genes, codificacion y funcion de aptitud', 2),
(25, 6, 'Operadores Geneticos en Detalle', 'Seleccion por torneo, cruce de un punto y mutacion adaptativa', 3),
(26, 6, 'Aplicaciones y Proyectos Finales', 'Resolver problemas de optimizacion reales: TSP, knapsack y scheduling', 4);

-- Curso 7: Data Science (id=7)
INSERT INTO `modules` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(27, 7, 'El Rol del Data Scientist', 'Flujo de trabajo CRISP-DM, herramientas y mercado laboral en 2024', 1),
(28, 7, 'Recoleccion y Limpieza de Datos', 'Web scraping, APIs publicas y preprocesamiento con pandas', 2),
(29, 7, 'Analisis Exploratorio (EDA)', 'Estadisticas descriptivas, distribuciones y correlaciones', 3),
(30, 7, 'Visualizacion Avanzada de Datos', 'matplotlib, seaborn, plotly y dashboards interactivos', 4);

-- Curso 8: JavaScript Moderno (id=8)
INSERT INTO `modules` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(31, 8, 'JavaScript Fundamentals', 'Tipos de datos, variables, operadores, funciones y scope', 1),
(32, 8, 'JavaScript ES6+ Moderno', 'Arrow functions, destructuring, spread, modules y clases', 2),
(33, 8, 'Asincronismo en JavaScript', 'Event loop, callbacks, Promises, async/await y Fetch API', 3),
(34, 8, 'Introduccion a React', 'Componentes, JSX, props, state, useState y useEffect', 4),
(35, 8, 'Proyecto: App React Completa', 'Construir una SPA con React, estado global y consumo de APIs', 5);

-- ============================================================
-- LECCIONES DE EJEMPLO
-- ============================================================

-- Inteligencia Artificial (Modulos 1 a 4)
-- Modulo 1: Fundamentos de IA (id=1)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(1,'Que es la Inteligencia Artificial','<h2>Introduccion a la IA</h2><p>La Inteligencia Artificial (IA) es la capacidad de las maquinas para imitar procesos cognitivos humanos como el aprendizaje, el razonamiento, la resolucion de problemas, la percepcion y la comprension del lenguaje.</p><h3>Tipos de IA</h3><ul><li><strong>IA Estrecha (ANI):</strong> Diseñada para una tarea especifica (ej: reconocimiento facial, recomendaciones de Netflix).</li><li><strong>IA General (AGI):</strong> Capaz de realizar cualquier tarea intelectual humana.</li><li><strong>Superinteligencia (ASI):</strong> Forma hipotetica que supera la inteligencia humana en todos los aspectos.</li></ul><h3>Por que estudiar IA?</h3><p>La IA es la tecnologia mas transformadora del siglo XXI. Dominarla abre puertas a oportunidades laborales extraordinarias y a la capacidad de construir soluciones que impactan millones de vidas.</p>','https://www.youtube.com/embed/mJeNghZXtMo','youtube',15,1,1),
(1,'Historia y Evolucion de la IA','<h2>Historia de la Inteligencia Artificial</h2><p>La IA tiene sus raices en los trabajos de matematicos y logicos del siglo XX. El termino fue acunado oficialmente en 1956 por John McCarthy en la Conferencia de Dartmouth.</p><h3>Cronologia Clave</h3><ul><li><strong>1950:</strong> Test de Turing - Alan Turing propone una prueba de inteligencia maquina.</li><li><strong>1956:</strong> Conferencia de Dartmouth - nace la IA como disciplina academica.</li><li><strong>1980s:</strong> Sistemas expertos - la primera oleada de IA comercial.</li><li><strong>1997:</strong> Deep Blue vence al campeon mundial de ajedrez Garry Kasparov.</li><li><strong>2012:</strong> AlexNet revoluciona el reconocimiento de imagenes con Deep Learning.</li><li><strong>2016:</strong> AlphaGo vence al campeon mundial de Go.</li><li><strong>2022:</strong> ChatGPT democratiza el acceso a la IA generativa.</li></ul>','','none',20,2,0),
(1,'Aplicaciones Reales de la IA','<h2>IA en el Mundo Real</h2><p>La Inteligencia Artificial esta presente en nuestra vida cotidiana de formas sorprendentes:</p><h3>Casos de Uso Actuales</h3><ul><li><strong>Salud:</strong> Diagnostico de cancer, prediccion de enfermedades, descubrimiento de farmacos.</li><li><strong>Finanzas:</strong> Deteccion de fraudes en tiempo real, trading algoritmico.</li><li><strong>Entretenimiento:</strong> Recomendaciones personalizadas en Netflix, Spotify, YouTube.</li><li><strong>Transporte:</strong> Vehiculos autonomos, optimizacion de rutas (Google Maps).</li><li><strong>Asistentes:</strong> Siri, Alexa, Google Assistant, ChatGPT.</li><li><strong>Vision:</strong> Reconocimiento facial, filtros de redes sociales, inspeccion industrial.</li></ul>','','none',18,3,0);

-- Modulo 2: Logica y Conocimiento (id=2)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(2,'Representacion del Conocimiento','<h2>Como las Maquinas Representan el Conocimiento</h2><p>Para que una IA pueda razonar, necesita representar el conocimiento del mundo en un formato que las computadoras puedan procesar.</p><h3>Metodos de Representacion</h3><ul><li><strong>Logica Proposicional:</strong> Declaraciones verdaderas o falsas.</li><li><strong>Logica de Primer Orden:</strong> Predicados, cuantificadores y funciones.</li><li><strong>Redes Semanticas:</strong> Grafos de conceptos relacionados.</li><li><strong>Marcos (Frames):</strong> Estructuras para representar objetos y sus propiedades.</li><li><strong>Ontologias:</strong> Vocabularios formales de un dominio.</li></ul>','','none',22,1,0),
(2,'Busqueda y Solucion de Problemas','<h2>Algoritmos de Busqueda en IA</h2><p>Muchos problemas de IA se pueden reformular como una busqueda en un espacio de estados. Los algoritmos de busqueda son fundamentales en IA clasica.</p><h3>Tipos de Busqueda</h3><ul><li><strong>BFS (Busqueda en Anchura):</strong> Explora todos los nodos de un nivel antes de pasar al siguiente.</li><li><strong>DFS (Busqueda en Profundidad):</strong> Explora una rama completamente antes de retroceder.</li><li><strong>A*:</strong> Usa heuristica para encontrar el camino optimo eficientemente.</li><li><strong>Minimax:</strong> Para juegos de dos jugadores como ajedrez.</li></ul>','https://www.youtube.com/embed/oDqjPvD1T-0','youtube',25,2,0),
(2,'Razonamiento con Incertidumbre','<h2>La Probabilidad en la IA</h2><p>El mundo real es incierto. Las IA modernas usan probabilidad para razonar con informacion incompleta o ruidosa.</p><h3>Conceptos Clave</h3><ul><li><strong>Redes Bayesianas:</strong> Representan dependencias probabilisticas entre variables.</li><li><strong>Teorema de Bayes:</strong> Actualizar creencias en base a evidencia nueva.</li><li><strong>Modelos de Markov:</strong> Procesos probabilisticos con estados ocultos.</li></ul>','','none',20,3,0);

-- Modulo 3: El Perceptron (id=3)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(3,'Inspiracion Biologica: La Neurona','<h2>De la Neurona Biologica al Perceptron</h2><p>El perceptron fue diseñado como una simplificacion matematica de la neurona biologica. Comprender la neurona biologica nos ayuda a entender por que el perceptron funciona como lo hace.</p><h3>La Neurona Biologica</h3><p>Una neurona recibe señales electricas a traves de sus <strong>dendritas</strong>, las procesa en el <strong>soma</strong> (cuerpo celular), y si la señal supera un umbral, envia un impulso por el <strong>axon</strong>. Esta es exactamente la logica que imita el perceptron.</p>','','none',18,1,0),
(3,'Matematica del Perceptron','<h2>El Perceptron de Rosenblatt</h2><p>Un perceptron toma multiples entradas, las pondera y produce una salida binaria. La formula es:</p><pre><code>salida = 1 si (w1*x1 + w2*x2 + ... + wn*xn + bias) >= 0\n         0 en caso contrario</code></pre><h3>Componentes</h3><ul><li><strong>Entradas (x):</strong> Los datos que el perceptron recibe.</li><li><strong>Pesos (w):</strong> La importancia de cada entrada.</li><li><strong>Bias:</strong> Permite desplazar la funcion de activacion.</li><li><strong>Funcion de activacion:</strong> Determina cuando el perceptron se activa.</li></ul>','https://www.youtube.com/embed/aircAruvnKk','youtube',30,2,0),
(3,'Implementando un Perceptron en Python','<h2>Codigo: Tu Primer Perceptron</h2><p>Implementaremos un perceptron desde cero en Python para que entiendas cada parte del algoritmo:</p><pre><code>import numpy as np\n\nclass Perceptron:\n    def __init__(self, tasa_aprendizaje=0.01, n_iteraciones=1000):\n        self.tasa = tasa_aprendizaje\n        self.n_iter = n_iteraciones\n\n    def fit(self, X, y):\n        self.pesos = np.zeros(X.shape[1])\n        self.bias = 0\n\n        for _ in range(self.n_iter):\n            for xi, yi in zip(X, y):\n                prediccion = self.predict(xi)\n                error = yi - prediccion\n                self.pesos += self.tasa * error * xi\n                self.bias += self.tasa * error\n\n    def predict(self, X):\n        suma = np.dot(X, self.pesos) + self.bias\n        return np.where(suma >= 0, 1, 0)</code></pre>','','none',35,3,0);

-- Modulo 4: Redes Neuronales (id=4)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(4,'Arquitectura de una Red Neuronal','<h2>De Perceptron a Red Neuronal Profunda</h2><p>Una red neuronal es simplemente una coleccion de perceptrones organizados en capas. Cada capa transforma la representacion de los datos, extrayendo caracteristicas cada vez mas abstractas.</p><h3>Capas de una Red Neuronal</h3><ul><li><strong>Capa de Entrada:</strong> Recibe los datos en bruto.</li><li><strong>Capas Ocultas:</strong> Aprenden representaciones intermedias.</li><li><strong>Capa de Salida:</strong> Produce la prediccion final.</li></ul>','https://www.youtube.com/embed/aircAruvnKk','youtube',28,1,0),
(4,'Backpropagation: Como Aprenden las Redes','<h2>El Algoritmo de Retropropagacion</h2><p>Backpropagation es el algoritmo que permite entrenar redes neuronales calculando los gradientes del error con respecto a cada peso, y actualizando los pesos para minimizar el error.</p><p>El proceso es:</p><ol><li>Propagacion hacia adelante: calcular la prediccion.</li><li>Calcular el error (loss).</li><li>Propagacion hacia atras: calcular gradientes.</li><li>Actualizar pesos con descenso de gradiente.</li></ol>','','none',35,2,0),
(4,'Funciones de Activacion Modernas','<h2>Funciones de Activacion en Deep Learning</h2><p>La funcion de activacion determina si una neurona debe activarse o no. La eleccion correcta es crucial para el entrenamiento efectivo.</p><ul><li><strong>Sigmoid:</strong> Salida entre 0 y 1. Buena para clasificacion binaria.</li><li><strong>Tanh:</strong> Salida entre -1 y 1. Centrada en cero.</li><li><strong>ReLU:</strong> max(0, x). La mas popular en redes profundas.</li><li><strong>Softmax:</strong> Convierte logits en probabilidades para clasificacion multiclase.</li></ul>','','none',25,3,0);

-- Para los demas modulos (5 a 35) insertamos lecciones genericas para tener un progreso funcional de 3 lecciones por modulo
-- Curso 2: Machine Learning (Modulos 5 a 8)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(5, 'Introducción al Machine Learning', 'Contenido introductorio sobre tipos de aprendizaje supervisado y no supervisado.', 'https://www.youtube.com/embed/f_uwKZIAeM0', 'youtube', 20, 1, 1),
(5, 'El flujo de trabajo en ML', 'Análisis, recolección y limpieza de datos en pipelines de Machine Learning.', '', 'none', 15, 2, 0),
(5, 'Instalación de librerías esenciales', 'Instalación de Numpy, Pandas y Scikit-Learn.', '', 'none', 15, 3, 0),
(6, 'Regresión Lineal Simple', 'Modelo de regresión matemática para predecir variables continuas.', 'https://www.youtube.com/embed/J4Wdy0Wc_xQ', 'youtube', 25, 1, 0),
(6, 'Regresión Múltiple', 'Añadiendo múltiples variables predictoras a nuestro modelo.', '', 'none', 20, 2, 0),
(6, 'Evaluación con R2 y MSE', 'Métricas fundamentales para evaluar el error de regresión.', '', 'none', 15, 3, 0),
(7, 'Clustering K-Means', 'Algoritmo de agrupación basado en centroides para aprendizaje no supervisado.', '', 'none', 25, 1, 0),
(7, 'Reducción con PCA', 'Reducción de dimensionalidad para simplificar datasets.', '', 'none', 20, 2, 0),
(7, 'Validación Cruzada', 'Validación del modelo por K-folds para evitar sobreajuste.', '', 'none', 15, 3, 0),
(8, 'Construcción del modelo clasificador', 'Código real y entrenamiento de un clasificador.', 'https://www.youtube.com/embed/qFJeN9V1ZsI', 'youtube', 30, 1, 0),
(8, 'Evaluación de matriz de confusión', 'Métricas de precisión, recall y puntuación F1.', '', 'none', 20, 2, 0),
(8, 'Exportación de modelos en producción', 'Uso de Pickle o Joblib para serializar modelos de ML.', '', 'none', 15, 3, 0);

-- Curso 3: Python (Modulos 9 a 13)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(9, 'Introducción a Python y sintaxis', 'Primer contacto con el lenguaje interactivo de Python.', 'https://www.youtube.com/embed/TqPzwenhMj0', 'youtube', 15, 1, 1),
(9, 'Variables y cadenas de texto', 'Tipos primitivos en Python y formateo de cadenas.', '', 'none', 20, 2, 0),
(9, 'Operaciones aritméticas básicas', 'Uso de operadores matemáticos en Python.', '', 'none', 15, 3, 0),
(10, 'Listas y manipulación', 'Uso avanzado de listas, métodos append, insert, remove y pop.', '', 'none', 20, 1, 0),
(10, 'Diccionarios en Python', 'Almacenamiento clave-valor estructurado en diccionarios.', '', 'none', 20, 2, 0),
(10, 'Tuplas y conjuntos (Sets)', 'Estructuras inmutables y conjuntos sin elementos duplicados.', '', 'none', 15, 3, 0),
(11, 'Estructura IF, ELIF y ELSE', 'Flujos de decisión y condiciones complejas en Python.', '', 'none', 20, 1, 0),
(11, 'Bucle FOR y funciones range', 'Cómo iterar sobre colecciones de forma sencilla.', '', 'none', 20, 2, 0),
(11, 'Bucle WHILE y control break/continue', 'Bucle por condición y alteración del flujo del ciclo.', '', 'none', 15, 3, 0),
(12, 'Definición de clases y constructores', 'Uso de class y el inicializador __init__.', 'https://www.youtube.com/embed/pTB0EiLXUC8', 'youtube', 25, 1, 0),
(12, 'Herencia simple de clases', 'Extender clases secundarias a partir de una clase base.', '', 'none', 20, 2, 0),
(12, 'Polimorfismo en Python', 'Sobreescritura de métodos para respuestas polimórficas.', '', 'none', 15, 3, 0),
(13, 'Decoradores y propiedades', 'Modificar el comportamiento de funciones mediante decoradores.', '', 'none', 20, 1, 0),
(13, 'Generadores (yield)', 'Iteradores eficientes creados con funciones generadoras.', '', 'none', 20, 2, 0),
(13, 'Consumo de APIs REST (requests)', 'Hacer peticiones HTTP GET y POST para interactuar con servicios externos.', '', 'none', 15, 3, 0);

-- Curso 4: SQL (Modulos 14 a 17)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(14, '¿Qué es una base de datos relacional?', 'Definición de gestor de base de datos relacional (RDBMS) y SQL.', 'https://www.youtube.com/embed/FR4QIeZaPeM', 'youtube', 20, 1, 1),
(14, 'Modelo Entidad-Relación (MER)', 'Estructura conceptual y mapeo físico de tablas.', '', 'none', 20, 2, 0),
(14, 'Instalación de MySQL Workbench', 'Configuración de servidor local y entorno de desarrollo gráfico.', '', 'none', 15, 3, 0),
(15, 'Uso de SELECT y WHERE', 'Consultar información con filtrados básicos en SQL.', '', 'none', 20, 1, 0),
(15, 'Sentencias INSERT y UPDATE', 'Inserción y modificación de datos de manera segura.', '', 'none', 20, 2, 0),
(15, 'DELETE y sentencias de precaución', 'Borrados condicionales para evitar pérdida de datos.', '', 'none', 15, 3, 0),
(16, 'INNER JOIN en consultas múltiples', 'Unir datos de dos tablas utilizando llaves foráneas.', 'https://www.youtube.com/embed/9yeOJ0ZMUYw', 'youtube', 25, 1, 0),
(16, 'LEFT, RIGHT y FULL JOIN', 'Casos de unión de datos asimétricos.', '', 'none', 20, 2, 0),
(16, 'Subconsultas en WHERE', 'Anidación de consultas SQL para reportes avanzados.', '', 'none', 15, 3, 0),
(17, 'Creación de índices (Index)', 'Acelerar el rendimiento de consultas masivas con índices.', '', 'none', 20, 1, 0),
(17, 'Transacciones ACID (Commit/Rollback)', 'Asegurar consistencia de la base de datos relacional.', '', 'none', 20, 2, 0),
(17, 'Procedimientos almacenados básicos', 'Automatización de sentencias mediante STORED PROCEDURES.', '', 'none', 15, 3, 0);

-- Curso 5: Full Stack Web (Modulos 18 a 22)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(18, 'Estructura semántica de HTML5', 'Etiquetas header, nav, main, article, section y footer.', 'https://www.youtube.com/embed/pQN-pnXPaVg', 'youtube', 20, 1, 1),
(18, 'Layouts modernos con CSS Flexbox', 'Alineaciones, dirección de cajas y diseños lineales fluidos.', '', 'none', 20, 2, 0),
(18, 'Layouts bidimensionales con CSS Grid', 'Estructuras complejas mediante grillas e Hitos CSS.', '', 'none', 15, 3, 0),
(19, 'Selección de elementos en el DOM', 'Uso de querySelector y querySelectorAll en JavaScript.', '', 'none', 20, 1, 0),
(19, 'Eventos click, submit y input', 'Capturar interacciones de usuario en el frontend.', '', 'none', 20, 2, 0),
(19, 'Fetch API y promesas asíncronas', 'Llamar a servicios externos sin recargar la página.', '', 'none', 15, 3, 0),
(20, 'Estructura básica de PHP y sintaxis', 'Declaración de variables, concatenación y echo en PHP.', '', 'none', 20, 1, 0),
(20, 'Orientación a Objetos en PHP 8', 'Definición de clases, propiedades de acceso y métodos.', '', 'none', 20, 2, 0),
(20, 'Arquitectura Modelo Vista Controlador', 'Separación de lógica del sistema para aplicaciones mantenibles.', '', 'none', 15, 3, 0),
(21, 'Conexión segura mediante PDO', 'Configuración de credenciales y driver PDO de MySQL.', '', 'none', 20, 1, 0),
(21, 'Consultas preparadas contra inyección SQL', 'Seguridad en base de datos previniendo ataques maliciosos.', '', 'none', 20, 2, 0),
(21, 'Creación de endpoints de API JSON', 'Devolver objetos codificados desde PHP.', '', 'none', 15, 3, 0),
(22, 'Integrando frontend JavaScript con backend PHP', 'Envío de formularios vía Fetch API hacia PHP.', 'https://www.youtube.com/embed/ysEN5RaKOlA', 'youtube', 25, 1, 0),
(22, 'Subida segura de archivos en el servidor', 'Validación de tipos MIME y mover archivos subidos.', '', 'none', 20, 2, 0),
(22, 'Hosting y configuración web', 'Despliegues productivos de proyectos PHP.', '', 'none', 15, 3, 0);

-- Curso 6: Algoritmos Genéticos (Modulos 23 a 26)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(23, 'Evolución y computación combinatoria', 'Principios evolutivos adaptados a la búsqueda heurística.', 'https://www.youtube.com/embed/1i8muvzZkPw', 'youtube', 20, 1, 1),
(23, 'Espacio de búsqueda y óptimos locales', 'Diferencia entre óptimos locales y el máximo global.', '', 'none', 20, 2, 0),
(23, 'Casos prácticos de computación evolutiva', 'Ejemplos reales aplicados en logística y diseño estructural.', '', 'none', 15, 3, 0),
(24, 'Definición del cromosoma', 'Modelado genético binario e hiperespacial para soluciones.', '', 'none', 20, 1, 0),
(24, 'Diseño de la función de Aptitud (Fitness)', 'Calificación de la calidad de individuos dentro del algoritmo.', '', 'none', 20, 2, 0),
(24, 'Población inicial aleatoria', 'Generación de diversidad genética inicial.', '', 'none', 15, 3, 0),
(25, 'Cruce de un punto y multipunto', 'Intercambio de genes entre progenitores.', '', 'none', 20, 1, 0),
(25, 'Operador de mutación por bit', 'Añadir mutabilidad para evitar caer en óptimos locales.', '', 'none', 20, 2, 0),
(25, 'Criterios de convergencia', 'Definición de parada por generaciones o umbral de fitness.', '', 'none', 15, 3, 0),
(26, 'Proyecto: Resolviendo el TSP con AG', 'Código completo en Python para resolver el agente viajero.', 'https://www.youtube.com/embed/9zfeTw-uFCw', 'youtube', 30, 1, 0),
(26, 'Optimización de funciones multivariables', 'Encuentro del óptimo en superficies matemáticas rugosas.', '', 'none', 20, 2, 0),
(26, 'Introducción a algoritmos evolutivos multiobjetivo', 'Optimización paralela de variables en conflicto.', '', 'none', 15, 3, 0);

-- Curso 7: Data Science (Modulos 27 a 30)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(27, '¿Qué es la Ciencia de Datos?', 'Introducción al flujo de trabajo del científico de datos.', 'https://www.youtube.com/embed/xC-c7E5PK0Y', 'youtube', 20, 1, 1),
(27, 'Configurando Jupyter Lab y Anaconda', 'Instalación de la suite integrada para análisis científico.', '', 'none', 20, 2, 0),
(27, 'Markdown y celdas interactivas en Jupyter', 'Documentar la investigación matemática con celdas de Markdown.', '', 'none', 15, 3, 0),
(28, ' pandas DataFrames y Series', 'Estructuras tabulares esenciales para el análisis en pandas.', 'https://www.youtube.com/embed/vmEHCJofslg', 'youtube', 25, 1, 0),
(28, 'Indexación y filtrado de DataFrames', 'Filtrar renglones y seleccionar columnas por condiciones lógicas.', '', 'none', 20, 2, 0),
(28, 'Manejo de valores nulos (NaN)', 'Limpieza de valores perdidos por imputación o eliminación.', '', 'none', 15, 3, 0),
(29, 'Estadísticas descriptivas básicas', 'Cálculos de promedio, desviación estándar, cuantiles y moda.', '', 'none', 20, 1, 0),
(29, 'Matriz de correlación de Pearson', 'Evaluación matemática de relaciones de causa-efecto entre columnas.', '', 'none', 20, 2, 0),
(29, 'Histogramas y densidades empíricas', 'Estudio visual del comportamiento probabilístico de variables.', '', 'none', 15, 3, 0),
(30, 'Visualización de datos con Matplotlib', 'Creación de gráficos de barras, dispersión y líneas.', 'https://www.youtube.com/embed/a9UrKTVEeZA', 'youtube', 25, 1, 0),
(30, 'Graficación avanzada con Seaborn', 'Estilización premium de gráficos científicos.', '', 'none', 20, 2, 0),
(30, 'Visualizaciones interactivas con Plotly', 'Generación de archivos HTML interactivos para dashboards.', '', 'none', 15, 3, 0);

-- Curso 8: JavaScript Moderno (Modulos 31 a 35)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(31, 'Fundamentos del motor de JavaScript', 'Entendimiento de la consola del navegador y sintaxis elemental.', 'https://www.youtube.com/embed/RqQ1d1qEWlE', 'youtube', 15, 1, 1),
(31, 'Variables let, const y el scope global', 'Comprender por qué evitar var y cómo funciona la inmutabilidad.', '', 'none', 20, 2, 0),
(31, 'Declaración de funciones básicas', 'Funciones por declaración y por asignación en JS.', '', 'none', 15, 3, 0),
(32, 'Arrow functions en detalle', 'Sintaxis compacta de funciones flecha y vinculación léxica de this.', '', 'none', 20, 1, 0),
(32, 'Desestructuración (Destructuring)', 'Extracción rápida de llaves de objetos y listas.', '', 'none', 20, 2, 0),
(32, 'Módulos import y export', 'División del código del lado del cliente en módulos independientes.', '', 'none', 15, 3, 0),
(33, 'El Event Loop y asincronía', 'Entender la naturaleza no bloqueante del hilo único en JavaScript.', 'https://www.youtube.com/embed/8aGhZQkoFbQ', 'youtube', 25, 1, 0),
(33, 'Promesas (Promises)', 'Manejo de estados resolve, reject y encadenamientos then/catch.', '', 'none', 20, 2, 0),
(33, 'Estructura Async/Await', 'Simplificación de asincronía con bloques limpios.', '', 'none', 15, 3, 0),
(34, 'Introducción a la librería React', 'Comprender qué es el Virtual DOM y por qué usar React.', 'https://www.youtube.com/embed/Tn6-PIqc4UM', 'youtube', 20, 1, 0),
(34, 'Componentes basados en funciones y Props', 'Envío de datos unidireccional utilizando props.', '', 'none', 20, 2, 0),
(34, 'Uso básico del hook useState', 'Gestión de estados interactivos en componentes funcionales.', '', 'none', 15, 3, 0),
(35, 'Creando componentes interactivos', 'Aplicación práctica de React e integración de eventos.', 'https://www.youtube.com/embed/w7ejDZ8SWv8', 'youtube', 30, 1, 0),
(35, 'Consumo de APIs en useEffect', 'Realizar fetch de datos al montar el componente en React.', '', 'none', 20, 2, 0),
(35, 'Despliegues estáticos y producción', 'Compilación para producción (npm run build) y hosting.', '', 'none', 15, 3, 0);

SET FOREIGN_KEY_CHECKS = 1;

SELECT 'INSTALACION COMPLETA REALIZADA EXITOSAMENTE' AS resultado;
