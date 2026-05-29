<?php
namespace App\Core;

/**
 * Controller — Clase base para todos los controladores de la plataforma.
 * Compatible con PHP 7.2+
 */
abstract class Controller {

    // ── Vista ───────────────────────────────────────────────────────────────

    protected function view($viewPath, $data = []): void {
        extract($data, EXTR_SKIP);
        $file = __DIR__ . '/../../app/Views/' . ltrim($viewPath, '/') . '.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("Vista no encontrada: {$viewPath}");
        }
        require $file;
    }

    // ── Redirección ─────────────────────────────────────────────────────────

    protected function redirect($url): void {
        header("Location: {$url}");
        exit;
    }

    protected function redirectWithError($url, $message): void {
        $_SESSION['swal_error'] = $message;
        $this->redirect($url);
    }

    protected function redirectWithSuccess($url, $message): void {
        $_SESSION['swal_success'] = $message;
        $this->redirect($url);
    }

    // ── JSON ────────────────────────────────────────────────────────────────

    protected function json($data, $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── Utilidades de Request ───────────────────────────────────────────────

    protected function isAjax(): bool {
        return isset($_GET['ajax'])
            || (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    protected function isPost(): bool {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    }

    protected function input($key, $default = ''): string {
        $value = $_POST[$key] ?? $default;
        return htmlspecialchars(strip_tags(trim((string)$value)), ENT_QUOTES, 'UTF-8');
    }

    protected function query($key, $default = ''): string {
        $value = $_GET[$key] ?? $default;
        return htmlspecialchars(strip_tags(trim((string)$value)), ENT_QUOTES, 'UTF-8');
    }

    // ── Autenticación y Roles ───────────────────────────────────────────────

    protected function auth(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?action=login');
        }
    }

    /**
     * Verifica que el usuario tenga uno de los roles permitidos.
     * @param string|array $roles  Ej: 'admin'  o  ['admin', 'teacher']
     */
    protected function requireRole($roles): void {
        $this->auth();
        $allowed = (array)$roles;
        $current = $_SESSION['role'] ?? '';
        if (!in_array($current, $allowed, true)) {
            $this->redirectWithError('index.php?action=dashboard', 'No tienes permisos para acceder a esta sección.');
        }
    }
}
