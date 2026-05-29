<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Security;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Enrollment;

class CourseAdminController extends Controller {
    private $isAdmin = false;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $role = $_SESSION['role'] ?? '';
        if ($role === 'admin') {
            $this->isAdmin = true;
        } elseif ($role !== 'teacher') {
            $_SESSION['swal_error'] = 'No tienes permisos para acceder a esta sección.';
            header('Location: index.php?action=dashboard');
            exit;
        }
    }

    /**
     * Check if the current user (teacher) owns a course.
     * Admins always pass. If a teacher doesn't own it, redirect.
     */
    private function checkOwnership(array $course): void {
        if ($this->isAdmin) return;

        if ((int)($course['instructor_id'] ?? 0) !== (int)$_SESSION['user_id']) {
            $_SESSION['swal_error'] = 'No tienes permisos para modificar este curso.';
            header('Location: index.php?action=admin_courses');
            exit;
        }
    }

    public function courses() {
        $courseModel = new Course();
        
        $filters = [
            'search' => $_GET['search'] ?? '',
            'level'  => $_GET['level'] ?? '',
            'status' => $_GET['status'] ?? '',
            'sort'   => $_GET['sort'] ?? ''
        ];

        // Teachers only see their own courses
        if (!$this->isAdmin) {
            $filters['instructor_id'] = $_SESSION['user_id'];
        }

        $courses = $courseModel->readAll($filters);
        $stats   = $courseModel->getStats();

        require_once __DIR__ . '/../Views/admin/courses.php';
    }

    public function create() {
        if ($this->isPost()) {
            Security::verifyCsrf();
            $courseModel = new Course();
            $courseModel->title            = $_POST['title'];
            $courseModel->slug             = $this->slugify($_POST['title']);
            $courseModel->short_description = $_POST['short_description'];
            $courseModel->description      = $_POST['description'];
            $courseModel->level            = $_POST['level'];
            $courseModel->duration_hours   = $_POST['duration_hours'];
            $courseModel->requirements     = $_POST['requirements'];
            $courseModel->objectives       = $_POST['objectives'];
            $courseModel->tags             = $_POST['tags'];
            $courseModel->category         = $_POST['category'];
            $courseModel->status           = $_POST['status'] ?? 'draft';
            // Assign current user as instructor
            $courseModel->instructor_id    = $_SESSION['user_id'];

            // Check if slug already exists
            if ($courseModel->findBySlug($courseModel->slug)) {
                $courseModel->slug .= '-' . time();
            }

            // Upload thumbnail
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $courseModel->thumbnail = $this->uploadFile($_FILES['thumbnail'], 'assets/images/courses/');
            } else {
                $courseModel->thumbnail = 'assets/images/courses/default-thumb.svg';
            }

            // Upload banner
            if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
                $courseModel->banner = $this->uploadFile($_FILES['banner'], 'assets/images/courses/');
            } else {
                $courseModel->banner = 'assets/images/courses/default-banner.svg';
            }

            if ($courseModel->create()) {
                $_SESSION['swal_success'] = 'Curso creado exitosamente.';
                header('Location: index.php?action=admin_courses');
                exit;
            } else {
                $_SESSION['swal_error'] = 'Error al crear el curso.';
            }
        }
        require_once __DIR__ . '/../Views/admin/course_create.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=admin_courses');
            exit;
        }

        $courseModel = new Course();
        $course = $courseModel->findById($id);

        if (!$course) {
            $_SESSION['swal_error'] = 'Curso no encontrado.';
            header('Location: index.php?action=admin_courses');
            exit;
        }

        $this->checkOwnership($course);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseModel->id = $id;
            $courseModel->title = $_POST['title'];
            $courseModel->short_description = $_POST['short_description'];
            $courseModel->description = $_POST['description'];
            $courseModel->level = $_POST['level'];
            $courseModel->duration_hours = $_POST['duration_hours'];
            $courseModel->requirements = $_POST['requirements'];
            $courseModel->objectives = $_POST['objectives'];
            $courseModel->tags = $_POST['tags'];
            $courseModel->category = $_POST['category'];
            $courseModel->status = $_POST['status'];

            // Upload thumbnail if set
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $courseModel->thumbnail = $this->uploadFile($_FILES['thumbnail'], 'assets/images/courses/');
            }
            // Upload banner if set
            if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
                $courseModel->banner = $this->uploadFile($_FILES['banner'], 'assets/images/courses/');
            }

            if ($courseModel->update()) {
                $_SESSION['swal_success'] = 'Curso actualizado exitosamente.';
                header('Location: index.php?action=admin_courses');
                exit;
            } else {
                $_SESSION['swal_error'] = 'Error al actualizar el curso.';
            }
        }

        require_once __DIR__ . '/../Views/admin/course_edit.php';
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $courseModel = new Course();
            $course = $courseModel->findById($id);
            if ($course) {
                $this->checkOwnership($course);
                if ($courseModel->delete($id)) {
                    $_SESSION['swal_success'] = 'Curso eliminado exitosamente.';
                } else {
                    $_SESSION['swal_error'] = 'No se pudo eliminar el curso.';
                }
            }
        }
        header('Location: index.php?action=admin_courses');
        exit;
    }

    public function duplicate() {
        $id = $_GET['id'] ?? null;
        $courseModel = new Course();
        if ($id) {
            $course = $courseModel->findById($id);
            if ($course) {
                $this->checkOwnership($course);
                if ($courseModel->duplicate($id)) {
                    $_SESSION['swal_success'] = 'Curso clonado exitosamente (en borrador).';
                } else {
                    $_SESSION['swal_error'] = 'No se pudo clonar el curso.';
                }
            }
        }
        header('Location: index.php?action=admin_courses');
        exit;
    }

    public function manageContent() {
        $courseId = $_GET['id'] ?? null;
        if (!$courseId) {
            header('Location: index.php?action=admin_courses');
            exit;
        }

        $courseModel = new Course();
        $course = $courseModel->findById($courseId);

        if (!$course) {
            $_SESSION['swal_error'] = 'Curso no encontrado.';
            header('Location: index.php?action=admin_courses');
            exit;
        }

        $moduleModel = new Module();
        $modules = $moduleModel->getByCourse($courseId);

        $lessonModel = new Lesson();
        $lessonsByModule = [];
        foreach ($modules as $module) {
            $lessonsByModule[$module['id']] = $lessonModel->getByModule($module['id']);
        }

        require_once __DIR__ . '/../Views/admin/course_content.php';
    }

    // Module endpoints (AJAX/POST)
    public function saveModule() {
        if ($this->isPost()) {
            Security::verifyCsrf();
            $moduleModel = new Module();
            $moduleModel->course_id = $_POST['course_id'];
            $moduleModel->title = $_POST['title'];
            $moduleModel->description = $_POST['description'] ?? '';
            $moduleModel->sort_order = $_POST['sort_order'] ?? 0;

            if (!empty($_POST['module_id'])) {
                $moduleModel->id = $_POST['module_id'];
                if ($moduleModel->update()) {
                    $_SESSION['swal_success'] = 'Módulo actualizado.';
                } else {
                    $_SESSION['swal_error'] = 'Error al actualizar módulo.';
                }
            } else {
                if ($moduleModel->create()) {
                    $_SESSION['swal_success'] = 'Módulo agregado.';
                } else {
                    $_SESSION['swal_error'] = 'Error al agregar módulo.';
                }
            }
            header('Location: index.php?action=admin_course_content&id=' . $_POST['course_id']);
            exit;
        }
    }

    public function deleteModule() {
        $id = $_GET['id'] ?? null;
        $courseId = $_GET['course_id'] ?? null;
        if ($id) {
            $moduleModel = new Module();
            if ($moduleModel->delete($id)) {
                $_SESSION['swal_success'] = 'Módulo eliminado.';
            } else {
                $_SESSION['swal_error'] = 'No se pudo eliminar el módulo.';
            }
        }
        header('Location: index.php?action=admin_course_content&id=' . $courseId);
        exit;
    }

    // Lesson endpoints (POST)
    public function saveLesson() {
        if ($this->isPost()) {
            Security::verifyCsrf();
            $lessonModel = new Lesson();
            $lessonModel->module_id = $_POST['module_id'];
            $lessonModel->title = $_POST['title'];
            $lessonModel->content = $_POST['content'] ?? '';
            $lessonModel->video_url = $_POST['video_url'] ?? '';
            $lessonModel->video_type = $_POST['video_type'] ?? 'none';
            $lessonModel->duration_minutes = $_POST['duration_minutes'] ?? 0;
            $lessonModel->sort_order = $_POST['sort_order'] ?? 0;
            $lessonModel->is_free = isset($_POST['is_free']) ? 1 : 0;

            // Handle YouTube URL clean embedding
            if ($lessonModel->video_type === 'youtube' && !empty($lessonModel->video_url)) {
                $lessonModel->video_url = $this->cleanYoutubeUrl($lessonModel->video_url);
            }

            // PDF upload
            if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
                $lessonModel->pdf_url = $this->uploadFile($_FILES['pdf_file'], 'assets/uploads/pdf/', ['pdf']);
            } else {
                $lessonModel->pdf_url = $_POST['existing_pdf'] ?? null;
            }

            if (!empty($_POST['lesson_id'])) {
                $lessonModel->id = $_POST['lesson_id'];
                if ($lessonModel->update()) {
                    $_SESSION['swal_success'] = 'Lección actualizada.';
                } else {
                    $_SESSION['swal_error'] = 'Error al actualizar lección.';
                }
            } else {
                if ($lessonModel->create()) {
                    $_SESSION['swal_success'] = 'Lección agregada.';
                } else {
                    $_SESSION['swal_error'] = 'Error al agregar lección.';
                }
            }
            header('Location: index.php?action=admin_course_content&id=' . $_POST['course_id']);
            exit;
        }
    }

    public function deleteLesson() {
        $id = $_GET['id'] ?? null;
        $courseId = $_GET['course_id'] ?? null;
        if ($id) {
            $lessonModel = new Lesson();
            if ($lessonModel->delete($id)) {
                $_SESSION['swal_success'] = 'Lección eliminada.';
            } else {
                $_SESSION['swal_error'] = 'No se pudo eliminar la lección.';
            }
        }
        header('Location: index.php?action=admin_course_content&id=' . $courseId);
        exit;
    }

    public function students() {
        $courseId = $_GET['id'] ?? null;
        if (!$courseId) {
            header('Location: index.php?action=admin_courses');
            exit;
        }

        $courseModel = new Course();
        $course = $courseModel->findById($courseId);

        if (!$course) {
            $_SESSION['swal_error'] = 'Curso no encontrado.';
            header('Location: index.php?action=admin_courses');
            exit;
        }

        $enrollmentModel = new Enrollment();
        $students = $enrollmentModel->getEnrolledStudents($courseId);

        require_once __DIR__ . '/../Views/admin/course_students.php';
    }

    // Helpers
    private function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    private function uploadFile($file, $targetDir, $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'pdf']) {
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = basename($file['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts)) {
            return null;
        }

        $newFilename = uniqid() . '.' . $ext;
        $targetFile = $targetDir . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $targetFile;
        }

        return null;
    }

    private function cleanYoutubeUrl($url) {
        // Formatos de YouTube:
        // https://www.youtube.com/watch?v=VIDEO_ID
        // https://youtu.be/VIDEO_ID
        // https://www.youtube.com/embed/VIDEO_ID
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return 'https://www.youtube.com/embed/' . $match[1];
        }
        return $url;
    }
}
?>
