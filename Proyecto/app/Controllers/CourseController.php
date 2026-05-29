<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Security;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\Certificate;
use App\Models\Notification;

class CourseController extends Controller {
    public function __construct() {
        // Public actions that don't require auth
        $publicActions = ['certificate'];
        $action = $_GET['action'] ?? '';
        if (!in_array($action, $publicActions, true)) {
            $this->auth();
        }
    }

    public function dashboard() {
        $enrollmentModel = new Enrollment();
        $userEnrollmentsRaw = $enrollmentModel->getUserEnrollments($_SESSION['user_id']);

        $userEnrollments = [];
        foreach ($userEnrollmentsRaw as $e) {
            $userEnrollments[$e['course_id']] = $e;
        }

        $courseModel = new Course();
        // Get active courses for catalog
        $courses = $courseModel->readActive();

        // Get unread notifications
        $notifModel = new Notification();
        $notifications = $notifModel->getUnreadByUser($_SESSION['user_id']);

        // Gamification Stats
        $gamification = new \App\Models\Gamification();
        $userXp = $gamification->getTotalPoints($_SESSION['user_id']);
        $userStreak = $gamification->getStreak($_SESSION['user_id']);
        $userRank = $gamification->getUserRank($_SESSION['user_id']);

        require_once __DIR__ . '/../Views/user/dashboard.php';
    }

    public function details() {
        $courseId = $_GET['course'] ?? '';
        $courseModel = new Course();

        if (is_numeric($courseId)) {
            $course = $courseModel->findById($courseId);
        } else {
            $course = $courseModel->findBySlug($courseId);
        }

        if (!$course) {
            $_SESSION['swal_error'] = 'Curso no encontrado.';
            header('Location: index.php?action=dashboard');
            exit;
        }

        $enrollmentModel = new Enrollment();
        $isEnrolled = $enrollmentModel->isEnrolled($_SESSION['user_id'], $course['id']);

        // Load modules & lessons
        $moduleModel = new Module();
        $modules = $moduleModel->getByCourse($course['id']);

        $lessonModel = new Lesson();
        $lessonsByModule = [];
        foreach ($modules as $module) {
            $lessonsByModule[$module['id']] = $lessonModel->getByModule($module['id']);
        }

        // Load reviews
        $reviewModel = new Review();
        $reviews = $reviewModel->getReviewsByCourse($course['id']);
        $hasReviewed = $reviewModel->hasReviewed($_SESSION['user_id'], $course['id']);

        $courseExtras = $this->buildCourseExtras($course, $modules);
        $courseVideos = $courseExtras['videos'];
        $moduleExtras = $courseExtras['module_extras'];

        require_once __DIR__ . '/../Views/courses/details.php';
    }

    public function enroll_form() {
        $courseId = $_GET['course'] ?? '';
        $courseModel = new Course();

        if (is_numeric($courseId)) {
            $course = $courseModel->findById($courseId);
        } else {
            $course = $courseModel->findBySlug($courseId);
        }

        if (!$course) {
            $_SESSION['swal_error'] = 'Curso no encontrado.';
            header('Location: index.php?action=dashboard');
            exit;
        }

        require_once __DIR__ . '/../Views/courses/enroll_form.php';
    }

    private function buildCourseExtras(array $course, array $modules): array {
        $slug = $course['slug'] ?? '';
        $videos = $this->getCourseVideoResources($slug);
        $totalModules = count($modules);
        $moduleExtras = [];

        foreach ($modules as $index => $module) {
            $topic = $this->getModuleTopic($slug, $index, $module['title']);
            $isLastModule = ($index === $totalModules - 1);
            $moduleExtras[$module['id']] = [
                'topic' => $topic,
                'quiz_questions' => $this->getQuizQuestions($topic, $isLastModule),
                'has_lab' => $isLastModule,
                'lab_description' => $isLastModule ? $this->getLabDescription($module['title']) : ''
            ];
        }

        return [
            'videos' => $videos,
            'module_extras' => $moduleExtras,
        ];
    }

    private function getCourseVideoResources(string $slug): array {
        $videos = [
            'inteligencia-artificial' => [
                [
                    'title' => 'Introducción a la Inteligencia Artificial',
                    'description' => 'Conceptos clave de IA y cómo se emplea en sistemas reales.',
                    'url' => 'https://www.youtube.com/watch?v=7Tmd8OYRmwA'
                ],
                [
                    'title' => 'Redes neuronales en español',
                    'description' => 'Explicación paso a paso del funcionamiento de redes neuronales.',
                    'url' => 'https://www.youtube.com/watch?v=cX1SqlbN6AI'
                ],
                [
                    'title' => 'Proyecto práctico de IA',
                    'description' => 'Cómo montar un prototipo sencillo con datos reales.',
                    'url' => 'https://www.youtube.com/watch?v=OJz77I9V5zM'
                ],
            ],
            'machine-learning' => [
                [
                    'title' => 'Machine Learning básico en español',
                    'description' => 'Bases de ML, tipos de aprendizaje y ejemplos prácticos.',
                    'url' => 'https://www.youtube.com/watch?v=8u3b_d6d2Yw'
                ],
                [
                    'title' => 'Regresión y clasificación',
                    'description' => 'Comparación entre modelos de regresión y clasificación.',
                    'url' => 'https://www.youtube.com/watch?v=8sQdrk6Dh0Q'
                ],
                [
                    'title' => 'Validación y métricas en ML',
                    'description' => 'Cómo evaluar y ajustar modelos con datos reales.',
                    'url' => 'https://www.youtube.com/watch?v=wz7xjiqF0WI'
                ],
            ],
            'python-desde-cero' => [
                [
                    'title' => 'Python desde cero',
                    'description' => 'Fundamentos de Python para empezar a programar.',
                    'url' => 'https://www.youtube.com/watch?v=kV7C6yZelBU'
                ],
                [
                    'title' => 'Estructuras y funciones en Python',
                    'description' => 'Cómo crear programas reales usando funciones y datos.',
                    'url' => 'https://www.youtube.com/watch?v=QFfYd_ctOQ8'
                ],
                [
                    'title' => 'Proyecto práctico en Python',
                    'description' => 'Aplicaciones prácticas con variables, ciclos y funciones.',
                    'url' => 'https://www.youtube.com/watch?v=gYk1hqXh_f0'
                ],
            ],
            'sql-bases-de-datos' => [
                [
                    'title' => 'SQL para principiantes',
                    'description' => 'Cómo crear y consultar bases de datos con SQL.',
                    'url' => 'https://www.youtube.com/watch?v=hZK4Y0kL6Jc'
                ],
                [
                    'title' => 'Consultas avanzadas en SQL',
                    'description' => 'JOINs, subconsultas y optimizaciones básicas.',
                    'url' => 'https://www.youtube.com/watch?v=Q4fRX5-vdWU'
                ],
                [
                    'title' => 'Laboratorio práctico de bases de datos',
                    'description' => 'Ejercicios reales para diseñar y consultar tablas.',
                    'url' => 'https://www.youtube.com/watch?v=3Zt1ffN6e4w'
                ],
            ],
            'desarrollo-web-full-stack' => [
                [
                    'title' => 'Desarrollo web full stack',
                    'description' => 'Recorrido completo desde HTML hasta backend con PHP.',
                    'url' => 'https://www.youtube.com/watch?v=WW0qvB0YwK4'
                ],
                [
                    'title' => 'JavaScript y frontend moderno',
                    'description' => 'Interacciones, DOM y estilos dinámicos en español.',
                    'url' => 'https://www.youtube.com/watch?v=g-428Iynawm'
                ],
                [
                    'title' => 'Proyecto práctico full stack',
                    'description' => 'Construye una aplicación web completa paso a paso.',
                    'url' => 'https://www.youtube.com/watch?v=YhVjTEnsc7Q'
                ],
            ],
            'algoritmos-geneticos' => [
                [
                    'title' => 'Algoritmos genéticos desde cero',
                    'description' => 'Principios y aplicación de algoritmos evolutivos.',
                    'url' => 'https://www.youtube.com/watch?v=Fv4cMbKhcxM'
                ],
                [
                    'title' => 'Selección, cruce y mutación',
                    'description' => 'Cómo crear soluciones optimizadas con algoritmos genéticos.',
                    'url' => 'https://www.youtube.com/watch?v=PedRn5Z1JNs'
                ],
                [
                    'title' => 'Laboratorio práctico de optimización',
                    'description' => 'Ejercicios reales con funciones de evaluación.',
                    'url' => 'https://www.youtube.com/watch?v=UsSWZx9VSAE'
                ],
            ],
            'data-science' => [
                [
                    'title' => 'Data Science en español',
                    'description' => 'Análisis, limpieza y visualización de datos.',
                    'url' => 'https://www.youtube.com/watch?v=VqnS0p5zMsc'
                ],
                [
                    'title' => 'Modelos predictivos y estadísticas',
                    'description' => 'Cómo preparar datos para modelos y métricas clave.',
                    'url' => 'https://www.youtube.com/watch?v=JbWbLZ8j0cw'
                ],
                [
                    'title' => 'Proyecto de laboratorio en Data Science',
                    'description' => 'Casos prácticos con datos reales y resultados accionables.',
                    'url' => 'https://www.youtube.com/watch?v=zpG8aS2mQe0'
                ],
            ],
            'javascript-moderno' => [
                [
                    'title' => 'JavaScript moderno en español',
                    'description' => 'Sintaxis ES6+, funciones y trabajo con el DOM.',
                    'url' => 'https://www.youtube.com/watch?v=OZjZQGfbXMM'
                ],
                [
                    'title' => 'Async/Await y peticiones fetch',
                    'description' => 'Manejo de datos en tiempo real con JavaScript.',
                    'url' => 'https://www.youtube.com/watch?v=1Q8JzSdcz8M'
                ],
                [
                    'title' => 'Proyecto práctico en JavaScript',
                    'description' => 'Construye una aplicación interactiva desde cero.',
                    'url' => 'https://www.youtube.com/watch?v=HbhLEHaqwLQ'
                ],
            ],
            'regresion-lineal' => [
                [
                    'title' => 'Regresión Lineal: Conceptos Básicos',
                    'description' => 'Introducción a la regresión lineal y su interpretación en modelos simples.',
                    'url' => 'https://www.youtube.com/watch?v=Kc8fRk9vZ1o'
                ],
                [
                    'title' => 'Regresión Lineal Múltiple en español',
                    'description' => 'Cómo construir modelos de regresión con múltiples variables y evaluar su desempeño.',
                    'url' => 'https://www.youtube.com/watch?v=6k2m3m8KpYk'
                ],
                [
                    'title' => 'Laboratorio práctico: Regresión con datos reales',
                    'description' => 'Ejemplo práctico paso a paso aplicando regresión lineal en Python.',
                    'url' => 'https://www.youtube.com/watch?v=Z3u5b5jKJmY'
                ],
            ],
        ];

        return $videos[$slug] ?? [
            [
                'title' => 'Video introductorio en español',
                'description' => 'Video en español con los fundamentos del curso.',
                'url' => 'https://www.youtube.com/watch?v=espanol-intro'
            ],
            [
                'title' => 'Video práctico en español',
                'description' => 'Ejemplo práctico relacionado con el curso.',
                'url' => 'https://www.youtube.com/watch?v=espanol-practico'
            ],
            [
                'title' => 'Video de repaso en español',
                'description' => 'Resumen de los puntos clave del curso.',
                'url' => 'https://www.youtube.com/watch?v=espanol-repaso'
            ],
        ];
    }

    private function getModuleTopic(string $slug, int $index, string $moduleTitle): string {
        $topicsByCourse = [
            'inteligencia-artificial' => [
                'Fundamentos de Inteligencia Artificial',
                'Modelado y algoritmos inteligentes',
                'Redes neuronales y aprendizaje profundo',
                'Laboratorio práctico de IA'
            ],
            'machine-learning' => [
                'Preparación y limpieza de datos',
                'Modelos de regresión y clasificación',
                'Validación y ajuste de modelos',
                'Laboratorio práctico de Machine Learning'
            ],
            'python-desde-cero' => [
                'Sintaxis, variables y tipos de datos',
                'Estructuras de control y loops',
                'Funciones y módulos en Python',
                'Laboratorio práctico de programación'
            ],
            'sql-bases-de-datos' => [
                'Diseño de bases de datos relacionales',
                'Consultas SELECT avanzadas',
                'Uniones, subconsultas y agregaciones',
                'Laboratorio práctico de SQL'
            ],
            'desarrollo-web-full-stack' => [
                'Fundamentos de HTML y CSS',
                'JavaScript para interfaces dinámicas',
                'Backend con PHP y bases de datos',
                'Laboratorio práctico full stack'
            ],
            'algoritmos-geneticos' => [
                'Introducción a algoritmos genéticos',
                'Selección, cruce y mutación',
                'Funciones de fitness y optimización',
                'Laboratorio práctico de optimización'
            ],
            'data-science' => [
                'Análisis exploratorio de datos',
                'Visualización y limpieza de datos',
                'Modelos predictivos y métricas',
                'Laboratorio práctico de Data Science'
            ],
            'javascript-moderno' => [
                'ECMAScript moderno y sintaxis',
                'DOM, eventos y manipulación del navegador',
                'Async/Await, fetch y APIs',
                'Laboratorio práctico de JavaScript'
            ],
        ];

        return $topicsByCourse[$slug][$index] ?? $moduleTitle;
    }

    private function getQuizQuestions(string $topic, bool $isLastModule): array {
        $baseQuestions = [
            "¿Qué conceptos esenciales cubre este módulo sobre {$topic}?",
            "¿Cuál es la aplicación práctica más importante de {$topic}?",
            "Describe un ejemplo real donde {$topic} mejore un proyecto educativo o empresarial."
        ];
        if ($isLastModule) {
            $baseQuestions[] = "En el laboratorio práctico final, ¿qué resultado esperas obtener al aplicar {$topic}?";
            $baseQuestions[] = "¿Qué pasos debes seguir para completar correctamente el laboratorio práctico del último módulo?";
        }
        return $baseQuestions;
    }

    private function getLabDescription(string $moduleTitle): string {
        return "En este laboratorio práctico del último módulo desarrollarás una actividad real relacionada con {$moduleTitle}, aplicando los conceptos aprendidos y verificando los resultados con datos reales.";
    }

    public function enroll() {
        Security::verifyCsrf();
        $courseId   = $_POST['course_id'] ?? '';
        $motivation = $_POST['motivation'] ?? '';
        $knowledge = $_POST['knowledge_level'] ?? '';
        $hours = $_POST['weekly_hours'] ?? '';
        $goal = $_POST['main_goal'] ?? '';

        if (!empty($courseId)) {
            $enrollmentModel = new Enrollment();
            if ($enrollmentModel->enroll($_SESSION['user_id'], $courseId, $motivation, $knowledge, $hours, $goal)) {
                // Get the first lesson to direct student
                $moduleModel = new Module();
                $modules = $moduleModel->getByCourse($courseId);
                
                $lessonId = '';
                if (!empty($modules)) {
                    $lessonModel = new Lesson();
                    $lessons = $lessonModel->getByModule($modules[0]['id']);
                    if (!empty($lessons)) {
                        $lessonId = $lessons[0]['id'];
                    }
                }

                $_SESSION['swal_success'] = '¡Inscripción exitosa! Bienvenido al curso.';
                if (!empty($lessonId)) {
                    header("Location: index.php?action=learn&course=$courseId&lesson=$lessonId");
                } else {
                    header("Location: index.php?action=learn&course=$courseId");
                }
                exit;
            }
        }
        $_SESSION['swal_error'] = 'No se pudo realizar la inscripción.';
        header('Location: index.php?action=dashboard');
        exit;
    }

    public function learn() {
        $courseId = $_GET['course'] ?? '';
        $courseModel = new Course();

        if (is_numeric($courseId)) {
            $course = $courseModel->findById($courseId);
        } else {
            $course = $courseModel->findBySlug($courseId);
        }

        if (!$course) {
            $_SESSION['swal_error'] = 'Curso no encontrado.';
            header('Location: index.php?action=dashboard');
            exit;
        }

        // Verify if user is enrolled
        $enrollmentModel = new Enrollment();
        $enrollment = $enrollmentModel->getEnrollmentDetails($_SESSION['user_id'], $course['id']);

        $moduleModel = new Module();
        $modules = $moduleModel->getByCourse($course['id']);

        $lessonModel = new Lesson();
        $lessonsByModule = [];
        foreach ($modules as $module) {
            $lessonsByModule[$module['id']] = $lessonModel->getByModule($module['id']);
        }

        // Get current lesson
        $currentLessonId = $_GET['lesson'] ?? ($enrollment ? $enrollment['last_lesson_id'] : null);
        $currentLesson = null;

        if ($currentLessonId) {
            $currentLesson = $lessonModel->findById($currentLessonId);
        }

        // If no lesson is specified or found, get the very first lesson of the course
        if (!$currentLesson && !empty($modules)) {
            foreach ($modules as $module) {
                if (!empty($lessonsByModule[$module['id']])) {
                    $currentLesson = $lessonsByModule[$module['id']][0];
                    break;
                }
            }
        }

        $isFreePreview = false;
        if (!$enrollment) {
            if ($currentLesson && intval($currentLesson['is_free']) === 1) {
                $isFreePreview = true;
            } else {
                $_SESSION['swal_error'] = 'No estás inscrito en este curso o esta lección no es de acceso libre.';
                header('Location: index.php?action=course_details&course=' . $course['slug']);
                exit;
            }
        }

        if ($currentLesson) {
            if (!$isFreePreview) {
                // Update last viewed lesson
                $enrollmentModel->updateLastLesson($_SESSION['user_id'], $course['id'], $currentLesson['id']);
                $isLessonCompleted = $lessonModel->isCompleted($_SESSION['user_id'], $currentLesson['id']);
            } else {
                $isLessonCompleted = false;
            }
            
            // Get Next & Previous Lessons for navigation
            $nextLesson = $lessonModel->getNextLesson($currentLesson['id']);
            $prevLesson = $lessonModel->getPreviousLesson($currentLesson['id']);
        }

        require_once __DIR__ . '/../Views/courses/learn.php';
    }

    public function markLessonProgress() {
        $lessonId = $_POST['lesson_id'] ?? null;
        $completed = isset($_POST['completed']) ? intval($_POST['completed']) : 0;
        $enrollmentModel = new Enrollment();

        if ($lessonId) {
            $lessonModel = new Lesson();
            if ($lessonModel->markProgress($_SESSION['user_id'], $lessonId, $completed)) {
                // Return updated enrollment details to show progress updates
                $lesson = $lessonModel->findById($lessonId);
                $enrollmentModel = new Enrollment();
                $enrollment = $enrollmentModel->getEnrollmentDetails($_SESSION['user_id'], $lesson['course_id']);
                
                // GAMIFICATION: +10 XP for lesson completion
                $gamification = new \App\Models\Gamification();
                $gamification->addPoints($_SESSION['user_id'], 'lesson_complete', $lessonId);
                $gamification->updateStreak($_SESSION['user_id']);
                $newBadges = $gamification->checkAndAwardBadges($_SESSION['user_id']);

                header('Content-Type: application/json');
                $response = [
                    'success' => true,
                    'progress_percentage' => $enrollment['progress_percentage'],
                    'status' => $enrollment['status']
                ];
                if (!empty($newBadges)) {
                    $response['new_badges'] = $newBadges;
                }
                echo json_encode($response);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }

    public function saveReview() {
        if ($this->isPost()) {
            Security::verifyCsrf();
            $courseId = $_POST['course_id'];
            $rating   = intval($_POST['rating']);
            $comment  = $this->input('comment');

            $reviewModel = new Review();
            if ($reviewModel->addReview($_SESSION['user_id'], $courseId, $rating, $comment)) {
                $_SESSION['swal_success'] = '¡Gracias por dejar tu reseña!';
            } else {
                $_SESSION['swal_error'] = 'No se pudo registrar la reseña.';
            }
            header('Location: index.php?action=course_details&course=' . $courseId);
            exit;
        }
    }

    public function quiz() {
        $moduleId = $_GET['module'] ?? '';
        
        $moduleModel = new Module();
        $module = $moduleModel->findById($moduleId);
        
        if (!$module) {
            $_SESSION['swal_error'] = 'Módulo no encontrado.';
            header('Location: index.php?action=dashboard');
            exit;
        }

        $quizModel = new \App\Models\Quiz();
        $quiz = $quizModel->findByModuleId($moduleId);

        if (!$quiz) {
            $_SESSION['swal_error'] = 'Este módulo no tiene un examen configurado.';
            header('Location: index.php?action=course_details&course=' . $module['course_id']);
            exit;
        }

        $questionModel = new \App\Models\Question();
        $questions = $questionModel->getByQuizId($quiz['id']);

        // Prevent passing if there are no questions
        if (empty($questions)) {
            $_SESSION['swal_error'] = 'El examen aún no tiene preguntas configuradas.';
            header('Location: index.php?action=course_details&course=' . $module['course_id']);
            exit;
        }

        require_once __DIR__ . '/../Views/courses/quiz.php';
    }

    public function submitQuiz() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $quizId = $_POST['quiz_id'] ?? '';
        $answers = $_POST['answers'] ?? []; // Associative array of question_id => answer
        
        $quizModel = new \App\Models\Quiz();
        $quiz = $quizModel->findById($quizId);

        if (!$quiz) {
            echo json_encode(['success' => false, 'message' => 'Examen inválido.']);
            exit;
        }

        $questionModel = new \App\Models\Question();
        $questions = $questionModel->getByQuizId($quizId);
        
        $totalQuestions = count($questions);
        $correctCount = 0;

        foreach ($questions as $q) {
            $qId = $q['id'];
            if (isset($answers[$qId]) && $answers[$qId] === $q['correct_option']) {
                $correctCount++;
            }
        }

        $score = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;
        $passed = ($score >= 70) ? 1 : 0; // Minimum 70% to pass

        $quizModel->saveAttempt($_SESSION['user_id'], $quizId, $score, $passed);

        $newBadges = [];
        if ($passed) {
            // GAMIFICATION: +30 XP for passing, +40 XP if perfect score
            $gamification = new \App\Models\Gamification();
            $gamification->addPoints($_SESSION['user_id'], $score == 100 ? 'quiz_perfect' : 'quiz_pass', $quizId);
            $gamification->updateStreak($_SESSION['user_id']);
            $newBadges = $gamification->checkAndAwardBadges($_SESSION['user_id']);
            
            // Check course completion logic and award 'course_complete' if applicable (usually handled in certificate generation, but doing a check here or there)
        }

        echo json_encode([
            'success' => true,
            'score'   => $score,
            'passed'  => $passed,
            'message' => $passed ? '¡Felicidades! Has aprobado el examen.' : 'No has alcanzado la nota mínima. Inténtalo de nuevo.',
            'new_badges' => $newBadges
        ]);
        exit;
    }

    public function certificate() {
        $code = $_GET['code'] ?? '';
        $certificateModel = new Certificate();
        $certificate = $certificateModel->findByCode($code);

        if (!$certificate) {
            $_SESSION['swal_error'] = 'Certificado inválido o no encontrado.';
            header('Location: index.php?action=dashboard');
            exit;
        }

        // Render clean HTML printable certificate page
        $isPublic = !isset($_SESSION['user_id']) || $_SESSION['user_id'] != $certificate['user_id'];
        require_once __DIR__ . '/../Views/courses/certificate.php';
    }
}
?>
