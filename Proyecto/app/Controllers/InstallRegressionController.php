<?php
/**
 * Script web para instalar el curso de Regresión Lineal
 * Acceder desde: http://localhost:8000/index.php?action=install_regression
 * 
 * Protegido: Solo accesible si estás autenticado como admin
 */

namespace App\Controllers;

use App\Models\Course;
use Config\Database;
use PDO;

class InstallRegressionController {
    
    public function __construct() {
        // Verificar que sea admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }
    }
    
    public function install() {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            // Script SQL para insertar el curso
            $sqls = [
                // 1. Insertar el curso
                "INSERT INTO `courses` (`slug`,`title`,`short_description`,`description`,`level`,`duration_hours`,`thumbnail`,`banner`,`requirements`,`objectives`,`category`,`tags`,`status`,`total_lessons`) VALUES
                ('regresion-lineal','Regresión Lineal: Fundamentos a Proyectos Reales','Domina la regresión lineal desde matemática básica hasta modelos predictivos avanzados.','La regresión lineal es el algoritmo más fundamental del Machine Learning y la estadística. En este curso aprenderás cómo construir modelos que predicen valores continuos a partir de datos históricos. Cubriremos desde la matemática detrás de mínimos cuadrados, interpretación de coeficientes, validación del modelo, hasta regularización con Ridge y Lasso. Implementarás proyectos reales: predicción de precios, análisis de tendencias y modelado de relaciones entre variables. Perfecto para data scientists, analistas y desarrolladores que quieren entender el corazón del Machine Learning.','Intermedio',32,'assets/images/courses/regression-thumb.jpg','assets/images/courses/regression-banner.jpg','Python básico.|Álgebra lineal elemental.|Estadística descriptiva (media, varianza, correlación).','Comprender la teoría matemática de regresión lineal.|Implementar regresión lineal desde cero sin librerías.|Usar scikit-learn para construir modelos predictivos.|Validar y evaluar la calidad de modelos con métricas adecuadas.|Aplicar regularización Ridge, Lasso y Elastic Net.|Detectar y tratar multicolinealidad y outliers.|Construir 3 proyectos reales para tu portafolio.','Data Science','Regresión Lineal, Machine Learning, Predicción, Python, Estadística','active',27)",
                
                // 2. Insertar módulos
                "INSERT INTO `modules` (`course_id`,`title`,`description`,`sort_order`) VALUES
                (9,'Fundamentos Matemáticos de la Regresión','Conceptos de álgebra lineal, geometría y cálculo aplicados a regresión',1),
                (9,'Regresión Lineal Simple: Teoría y Práctica','De una variable independiente a predicciones: la base de todo',2),
                (9,'Regresión Múltiple: Varios Predictores','Extensión a múltiples variables y interpretación de coeficientes',3),
                (9,'Validación y Evaluación de Modelos','Métricas, train-test split, cross-validation y diagnósticos',4),
                (9,'Regularización: Ridge, Lasso y Elastic Net','Técnicas para prevenir overfitting y mejorar generalización',5),
                (9,'Supuestos del Modelo y Diagnósticos','Verificar normalidad, homocedasticidad, multicolinealidad',6),
                (9,'Regresión Polinomial y No Lineal','Extender regresión lineal a relaciones curvadas',7),
                (9,'Series Temporales: Predicción de Tendencias','Aplicar regresión a datos temporales',8),
                (9,'Proyecto Final: Sistema Predictivo Integral','Construir una aplicación completa de predicción',9)",
            ];
            
            foreach ($sqls as $sql) {
                $conn->exec($sql);
            }
            
            // Ahora insertar las lecciones (las más simples)
            $lessons_sql = file_get_contents(__DIR__ . '/../../add_regression_course.sql');
            // Filtrar solo las líneas de INSERT INTO lessons
            preg_match_all('/INSERT INTO `lessons`[^;]+;/is', $lessons_sql, $matches);
            
            foreach ($matches[0] as $lesson_sql) {
                $conn->exec($lesson_sql);
            }
            
            // Verificar que fue insertado
            $check = $conn->query("SELECT COUNT(*) as total FROM courses WHERE slug = 'regresion-lineal'")->fetch(PDO::FETCH_ASSOC);
            
            if ($check['total'] > 0) {
                $_SESSION['swal_success'] = '✅ Curso de Regresión Lineal instalado correctamente con 9 módulos y 27 lecciones!';
            } else {
                $_SESSION['swal_error'] = '❌ Error al instalar el curso';
            }
            
            header('Location: index.php?action=admin_courses');
            exit;
            
        } catch (Exception $e) {
            $_SESSION['swal_error'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?action=admin_courses');
            exit;
        }
    }
}
?>
