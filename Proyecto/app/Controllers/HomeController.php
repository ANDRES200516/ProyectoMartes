<?php

namespace App\Controllers;

class HomeController {
    public function index() {
        // La landing page es pública, no requiere verificación de sesión aquí
        // a menos que queramos redirigir si ya están logueados (opcional)
        require_once __DIR__ . '/../Views/home.php';
    }
}
