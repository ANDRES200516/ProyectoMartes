<?php
namespace App\Core;

/**
 * Router — Enrutador centralizado para la plataforma E-Learning.
 * Compatible con PHP 7.2+
 *
 * Soporta:
 *  - Rutas simples tipo ?action=xxx (compatibilidad actual)
 *  - Rutas limpias tipo /login, /dashboard, /admin/courses (con .htaccess)
 *  - Middleware de autenticación y roles por ruta.
 *  - Verbos HTTP: GET, POST, ANY.
 */
class Router {
    private $routes = [];
    private $basePath;

    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }

    // ── Registro de rutas ───────────────────────────────────────────────────

    public function get($action, $handler, $middleware = []): void {
        $this->add('GET', $action, $handler, $middleware);
    }

    public function post($action, $handler, $middleware = []): void {
        $this->add('POST', $action, $handler, $middleware);
    }

    public function any($action, $handler, $middleware = []): void {
        $this->add('GET',  $action, $handler, $middleware);
        $this->add('POST', $action, $handler, $middleware);
    }

    private function add($method, $action, $handler, $middleware): void {
        $this->routes[] = compact('method', 'action', 'handler', 'middleware');
    }

    // ── Despacho ────────────────────────────────────────────────────────────

    public function dispatch(): void {
        $action = $this->resolveAction();
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        foreach ($this->routes as $route) {
            if ($route['action'] === $action && ($route['method'] === $method || $route['method'] === 'ANY')) {
                // Run middleware chain
                foreach ($route['middleware'] as $mw) {
                    $mw();
                }
                // Invoke handler
                $this->invoke($route['handler']);
                return;
            }
        }

        // 404 fallback
        http_response_code(404);
        echo '<h1 style="font-family:sans-serif;color:#ef4444;padding:2rem">404 — Página no encontrada</h1>';
    }

    private function resolveAction(): string {
        // Priority 1: ?action= query param (existing behaviour)
        if (!empty($_GET['action'])) {
            return trim($_GET['action']);
        }

        // Priority 2: PATH_INFO (clean URL via .htaccess)
        $pathInfo = $_SERVER['PATH_INFO'] ?? '/';
        $path = trim(substr($pathInfo, strlen($this->basePath)), '/');
        return $path ?: 'home';
    }

    private function invoke($handler): void {
        // If handler is [ClassName, 'method'], always instantiate the class
        if (is_array($handler) && count($handler) === 2 && is_string($handler[0])) {
            $class  = $handler[0];
            $method = $handler[1];
            $controller = new $class();
            $controller->$method();
            return;
        }

        // Plain callable (closure)
        if (is_callable($handler)) {
            call_user_func($handler);
            return;
        }
    }
}
