<?php
session_start();
require_once __DIR__ . '/../config/github.php';

// Autoloader para MVC
spl_autoload_register(function ($class_name) {
    // Convertir Namespace a Path
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    
    // Mapear namespaces a directorios reales
    $mappings = [
        'App' . DIRECTORY_SEPARATOR => __DIR__ . '/../app' . DIRECTORY_SEPARATOR,
        'Config' . DIRECTORY_SEPARATOR => __DIR__ . '/../config' . DIRECTORY_SEPARATOR,
    ];
    
    foreach ($mappings as $prefix => $dir) {
        if (strpos($path, $prefix) === 0) {
            $relative = substr($path, strlen($prefix));
            $file = $dir . $relative . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Obtener la acción solicitada
$action = $_GET['action'] ?? 'home';

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\CourseAdminController;
use App\Controllers\CourseController;
use App\Controllers\ProfileController;
use App\Controllers\NotificationController;

switch ($action) {
    case 'login':
        $authController = new AuthController();
        $authController->login();
        break;
    case 'register':
        $authController = new AuthController();
        $authController->register();
        break;
    case 'social_login':
        $authController = new AuthController();
        $authController->socialLogin();
        break;
    case 'github_login':
        $authController = new AuthController();
        $authController->githubLogin();
        break;
    case 'github_callback':
        $authController = new AuthController();
        $authController->githubCallback();
        break;

    case 'oauth_consent':
        $authController = new AuthController();
        $authController->oauthConsent();
        break;
    case 'oauth_callback':
        $authController = new AuthController();
        $authController->oauthCallback();
        break;
    case 'complete_social_profile':
        $authController = new AuthController();
        $authController->completeSocialProfile();
        break;
    case 'logout':
        $authController = new AuthController();
        $authController->logout();
        break;
    case 'admin_dashboard':
        $adminController = new AdminController();
        $adminController->dashboard();
        break;
    case 'approve_user':
        $adminController = new AdminController();
        $adminController->approveUser();
        break;
    case 'reject_user':
        $adminController = new AdminController();
        $adminController->rejectUser();
        break;
    case 'edit_user':
        $adminController = new AdminController();
        $adminController->editUser();
        break;
    case 'update_user':
        $adminController = new AdminController();
        $adminController->updateUser();
        break;
    case 'delete_user':
        $adminController = new AdminController();
        $adminController->deleteUser();
        break;
    case 'dashboard':
        $courseController = new CourseController();
        $courseController->dashboard();
        break;
    case 'enroll':
        $courseController = new CourseController();
        $courseController->enroll();
        break;
    case 'course_details':
        $courseController = new CourseController();
        $courseController->details();
        break;
    case 'enroll_form':
        $courseController = new CourseController();
        $courseController->enroll_form();
        break;
    case 'learn':
        $courseController = new CourseController();
        $courseController->learn();
        break;
    case 'mark_lesson_progress':
        $courseController = new CourseController();
        $courseController->markLessonProgress();
        break;
    case 'save_review':
        $courseController = new CourseController();
        $courseController->saveReview();
        break;
    case 'certificate':
        $courseController = new CourseController();
        $courseController->certificate();
        break;

    // --- ADMIN COURSES ROUTES ---
    case 'admin_courses':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->courses();
        break;
    case 'admin_courses_create':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->create();
        break;
    case 'admin_courses_edit':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->edit();
        break;
    case 'admin_courses_delete':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->delete();
        break;
    case 'admin_courses_duplicate':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->duplicate();
        break;
    case 'admin_course_content':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->manageContent();
        break;
    case 'admin_save_module':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->saveModule();
        break;
    case 'admin_delete_module':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->deleteModule();
        break;
    case 'admin_save_lesson':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->saveLesson();
        break;
    case 'admin_delete_lesson':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->deleteLesson();
        break;
    case 'admin_course_students':
        $courseAdmin = new CourseAdminController();
        $courseAdmin->students();
        break;
    
    // --- NOTIFICATIONS ROUTE ---
    case 'mark_all_notifications_read':
        $notifController = new NotificationController();
        $notifController->markAllRead();
        break;

    case 'profile':
        $profileController = new ProfileController();
        $profileController->show();
        break;
    case 'update_profile':
        $profileController = new ProfileController();
        $profileController->updateData();
        break;
    case 'update_photo':
        $profileController = new ProfileController();
        $profileController->updatePhoto();
        break;
    case 'update_password':
        $profileController = new ProfileController();
        $profileController->updatePassword();
        break;
    case 'home':
        $homeController = new App\Controllers\HomeController();
        $homeController->index();
        break;
    default:
        // Redirigir dependiendo de la sesión
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                header('Location: index.php?action=admin_dashboard');
            } else {
                header('Location: index.php?action=dashboard');
            }
        } else {
            $homeController = new App\Controllers\HomeController();
            $homeController->index();
        }
        break;
}
?>
