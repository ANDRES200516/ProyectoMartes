<?php
namespace App\Controllers;

use App\Models\Course;

class AdminCourseController {
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }
    }
    
    public function index() {
        $courseModel = new Course();
        $stmt = $courseModel->readAll();
        $courses = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stats = $courseModel->getStats();
        
        require_once __DIR__ . '/../Views/admin/courses/index.php';
    }

    public function create() {
        require_once __DIR__ . '/../Views/admin/courses/form.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseModel = new Course();
            $courseModel->title = $_POST['title'] ?? '';
            
            // Generar slug básico a partir del título
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $courseModel->title)));
            // Verificar unicidad del slug en una aplicación real (aquí se asume simpleza por demostración o se añade un random)
            $courseModel->slug = $slug . '-' . rand(1000, 9999);
            
            $courseModel->description = $_POST['description'] ?? '';
            $courseModel->level = $_POST['level'] ?? 'Básico';
            $courseModel->duration_hours = $_POST['duration_hours'] ?? 0;
            $courseModel->status = $_POST['status'] ?? 'active';
            $courseModel->category = $_POST['category'] ?? 'Tecnología';

            // Subir imagen
            $courseModel->thumbnail = 'assets/images/courses/default-thumb.svg'; // fallback
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
                $target_dir = __DIR__ . '/../../public/assets/images/courses/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION);
                $file_name = $courseModel->slug . '.' . $file_extension;
                $target_file = $target_dir . $file_name;
                
                if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                    $courseModel->thumbnail = 'assets/images/courses/' . $file_name;
                }
            }

            if ($courseModel->create()) {
                $_SESSION['swal_success'] = 'Curso creado correctamente.';
            } else {
                $_SESSION['swal_error'] = 'Error al crear el curso.';
            }
            header('Location: index.php?action=admin_courses');
            exit;
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $courseModel = new Course();
            $course = $courseModel->findById($id);
            if ($course) {
                require_once __DIR__ . '/../Views/admin/courses/form.php';
                return;
            }
        }
        $_SESSION['swal_error'] = 'Curso no encontrado.';
        header('Location: index.php?action=admin_courses');
        exit;
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $courseModel = new Course();
                $courseModel->id = $id;
                $courseModel->title = $_POST['title'] ?? '';
                $courseModel->description = $_POST['description'] ?? '';
                $courseModel->level = $_POST['level'] ?? 'Básico';
                $courseModel->duration_hours = $_POST['duration_hours'] ?? 0;
                $courseModel->status = $_POST['status'] ?? 'active';
                $courseModel->category = $_POST['category'] ?? 'Tecnología';
                
                // Obtener slug existente
                $existingCourse = $courseModel->findById($id);
                $slug = $existingCourse['slug'];

                // Subir imagen si hay nueva
                if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
                    $target_dir = __DIR__ . '/../../public/assets/images/courses/';
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }
                    
                    $file_extension = pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION);
                    $file_name = $slug . '-' . time() . '.' . $file_extension; // add time to avoid cache issues
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                        $courseModel->thumbnail = 'assets/images/courses/' . $file_name;
                    }
                }

                if ($courseModel->update()) {
                    $_SESSION['swal_success'] = 'Curso actualizado exitosamente.';
                } else {
                    $_SESSION['swal_error'] = 'Error al actualizar el curso.';
                }
            }
            header('Location: index.php?action=admin_courses');
            exit;
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $courseModel = new Course();
            if ($courseModel->delete($id)) {
                $_SESSION['swal_success'] = 'Curso eliminado de forma segura.';
            } else {
                $_SESSION['swal_error'] = 'No se pudo eliminar el curso.';
            }
        }
        header('Location: index.php?action=admin_courses');
        exit;
    }
}
