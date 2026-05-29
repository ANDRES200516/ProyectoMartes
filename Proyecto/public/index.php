<?php
/**
 * Entry Point — public/index.php
 * 
 * Fases 2 & 3: Router centralizado + Seguridad de sesión.
 */

use App\Core\Router;
use App\Helpers\Security;

// ── 1. Autoloader PSR-4 básico ───────────────────────────────────────────────
require_once __DIR__ . '/../config/github.php';

spl_autoload_register(function (string $class): void {
    $mappings = [
        'App\\'    => __DIR__ . '/../app/',
        'Config\\' => __DIR__ . '/../config/',
    ];
    foreach ($mappings as $prefix => $dir) {
        if (strpos($class, $prefix) === 0) {
            $relative = substr($class, strlen($prefix));
            $file = $dir . str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// ── 2. Session Hardening (Fase 3) ────────────────────────────────────────────
Security::hardenSession();
session_start();

// ── 3. Middleware helpers ─────────────────────────────────────────────────────

/** Middleware: Usuario autenticado */
$authRequired = function (): void {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?action=login');
        exit;
    }
};

/** Middleware: Rol admin */
$adminOnly = function () use ($authRequired): void {
    $authRequired();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        $_SESSION['swal_error'] = 'No tienes permisos de administrador.';
        header('Location: index.php?action=dashboard');
        exit;
    }
};

/** Middleware: Rol admin o teacher */
$staffOnly = function () use ($authRequired): void {
    $authRequired();
    if (!in_array($_SESSION['role'] ?? '', ['admin', 'teacher'], true)) {
        $_SESSION['swal_error'] = 'No tienes permisos para acceder a esta sección.';
        header('Location: index.php?action=dashboard');
        exit;
    }
};

// ── 4. Router & Rutas ─────────────────────────────────────────────────────────
$router = new Router();

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\CourseAdminController;
use App\Controllers\CourseController;
use App\Controllers\ProfileController;
use App\Controllers\NotificationController;
use App\Controllers\GamificationController;
use App\Controllers\Api\CourseApiController;

// Auth
$router->any('login',                   [AuthController::class, 'login']);
$router->any('register',                [AuthController::class, 'register']);
$router->any('social_login',            [AuthController::class, 'socialLogin']);
$router->any('github_login',            [AuthController::class, 'githubLogin']);
$router->any('github_callback',         [AuthController::class, 'githubCallback']);
$router->any('oauth_consent',           [AuthController::class, 'oauthConsent']);
$router->any('oauth_callback',          [AuthController::class, 'oauthCallback']);
$router->any('complete_social_profile', [AuthController::class, 'completeSocialProfile']);
$router->get('logout',                  [AuthController::class, 'logout']);

// Home
$router->get('home',                    [App\Controllers\HomeController::class, 'index']);

// Alumno (rutas protegidas)
$router->any('dashboard',               [CourseController::class, 'dashboard'],         [$authRequired]);
$router->any('course_details',          [CourseController::class, 'details'],            [$authRequired]);
$router->any('enroll_form',             [CourseController::class, 'enroll_form'],        [$authRequired]);
$router->post('enroll',                 [CourseController::class, 'enroll'],             [$authRequired]);
$router->any('learn',                   [CourseController::class, 'learn'],              [$authRequired]);
$router->post('mark_lesson_progress',   [CourseController::class, 'markLessonProgress'],[$authRequired]);
$router->post('save_review',            [CourseController::class, 'saveReview'],         [$authRequired]);
$router->any('quiz',                    [CourseController::class, 'quiz'],               [$authRequired]);
$router->post('submit_quiz',            [CourseController::class, 'submitQuiz'],         [$authRequired]);
$router->get('certificate',             [CourseController::class, 'certificate']); // Público

// Notificaciones
$router->post('mark_all_notifications_read', [NotificationController::class, 'markAllRead'], [$authRequired]);

// Gamificación
$router->get('leaderboard',             [GamificationController::class, 'leaderboard'], [$authRequired]);
$router->get('badges',                  [GamificationController::class, 'badges'],      [$authRequired]);
$router->get('admin_analytics',         [GamificationController::class, 'analytics'],   [$adminOnly]);

// Perfil
$router->any('profile',                 [ProfileController::class, 'show'],             [$authRequired]);
$router->post('update_profile',         [ProfileController::class, 'updateData'],       [$authRequired]);
$router->post('update_photo',           [ProfileController::class, 'updatePhoto'],      [$authRequired]);
$router->post('update_password',        [ProfileController::class, 'updatePassword'],   [$authRequired]);

// Admin — Usuarios
$router->any('admin_dashboard',         [AdminController::class, 'dashboard'],          [$adminOnly]);
$router->get('approve_user',            [AdminController::class, 'approveUser'],        [$adminOnly]);
$router->get('reject_user',             [AdminController::class, 'rejectUser'],         [$adminOnly]);
$router->any('edit_user',               [AdminController::class, 'editUser'],           [$adminOnly]);
$router->post('update_user',            [AdminController::class, 'updateUser'],         [$adminOnly]);
$router->get('delete_user',             [AdminController::class, 'deleteUser'],         [$adminOnly]);

// Admin / Teacher — Cursos
$router->any('admin_courses',              [CourseAdminController::class, 'courses'],        [$staffOnly]);
$router->any('admin_courses_create',       [CourseAdminController::class, 'create'],         [$staffOnly]);
$router->any('admin_courses_edit',         [CourseAdminController::class, 'edit'],           [$staffOnly]);
$router->get('admin_courses_delete',       [CourseAdminController::class, 'delete'],         [$staffOnly]);
$router->get('admin_courses_duplicate',    [CourseAdminController::class, 'duplicate'],      [$staffOnly]);
$router->any('admin_course_content',       [CourseAdminController::class, 'manageContent'],  [$staffOnly]);
$router->post('admin_save_module',         [CourseAdminController::class, 'saveModule'],     [$staffOnly]);
$router->get('admin_delete_module',        [CourseAdminController::class, 'deleteModule'],   [$staffOnly]);
$router->post('admin_save_lesson',         [CourseAdminController::class, 'saveLesson'],     [$staffOnly]);
$router->get('admin_delete_lesson',        [CourseAdminController::class, 'deleteLesson'],   [$staffOnly]);
$router->get('admin_course_students',      [CourseAdminController::class, 'students'],       [$staffOnly]);

// API REST
$router->any('api/courses',             [CourseApiController::class, 'courses']);
$router->any('api/courses_detail',      [CourseApiController::class, 'courseDetail']); // ?id=
$router->any('api/leaderboard',         [CourseApiController::class, 'leaderboard']);
$router->any('api/me',                  [CourseApiController::class, 'me']);
$router->post('api/keys/generate',      [CourseApiController::class, 'generateKey'],    [$authRequired]);

// ── 5. Default (home o dashboard según sesión) ────────────────────────────────
if (!isset($_GET['action'])) {
    if (isset($_SESSION['user_id'])) {
        header('Location: index.php?action=' . ($_SESSION['role'] === 'admin' ? 'admin_dashboard' : 'dashboard'));
        exit;
    }
    $_GET['action'] = 'home';
}

// ── 6. Dispatch ───────────────────────────────────────────────────────────────
$router->dispatch();
