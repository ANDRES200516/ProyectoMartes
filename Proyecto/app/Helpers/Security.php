<?php
namespace App\Helpers;

/**
 * Security — Helper centralizado de seguridad para la plataforma E-Learning.
 * 
 * Provee:
 *  - Generación y validación de CSRF Tokens.
 *  - Función de escape XSS `e()` (alias de htmlspecialchars).
 *  - Validación de MIME Type para uploads.
 */
class Security {

    // ── CSRF ────────────────────────────────────────────────────────────────

    /**
     * Genera (o recupera de sesión) el token CSRF actual.
     */
    public static function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Renderiza el input hidden del token CSRF.
     * Uso en vistas: <?php echo Security::csrfField(); ?>
     */
    public static function csrfField(): string {
        return '<input type="hidden" name="csrf_token" value="' . self::csrfToken() . '">';
    }

    /**
     * Valida que el token enviado coincida con el de sesión.
     * Si falla, aborta con HTTP 419 (CSRF mismatch).
     */
    public static function verifyCsrf(): void {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(419);
            // Si es AJAX devuelve JSON, si no, redirige con error
            if (
                isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Token de seguridad inválido. Recarga la página.']);
                exit;
            }
            $_SESSION['swal_error'] = 'Sesión de seguridad expirada. Por favor, intenta de nuevo.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
            exit;
        }
        // Rotación del token después de validación exitosa
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    // ── XSS Escape ──────────────────────────────────────────────────────────

    /**
     * Escapa HTML para salida segura en vistas.
     * Uso: <?= Security::e($variable) ?>
     */
    public static function e($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    // ── Uploads ─────────────────────────────────────────────────────────────

    /** MIME types seguros permitidos por categoría. */
    private static $allowedMimes = [
        'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
        'pdf'   => ['application/pdf'],
        'video' => ['video/mp4', 'video/webm', 'video/ogg'],
        'doc'   => ['application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
    ];

    /**
     * Valida un archivo subido verificando el MIME type real (no la extensión).
     * @param  array  $file       Elemento de $_FILES
     * @param  string $category   'image' | 'pdf' | 'video' | 'doc'
     * @return bool
     */
    public static function validateUpload(array $file, string $category = 'image'): bool {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowed = self::$allowedMimes[$category] ?? self::$allowedMimes['image'];

        // Leer MIME real del archivo (no del browser)
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $realMime = $finfo->file($file['tmp_name']);

        return in_array($realMime, $allowed, true);
    }

    /**
     * Mueve un archivo subido con nombre aleatorio y validación de MIME.
     * Devuelve la ruta relativa del archivo guardado, o null si falla.
     */
    public static function moveUpload(array $file, string $targetDir, string $category = 'image'): ?string {
        if (!self::validateUpload($file, $category)) {
            return null;
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Determinar extensión desde el MIME real
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $realMime = $finfo->file($file['tmp_name']);
        $mimeToExt = [
            'image/jpeg'      => 'jpg',
            'image/png'       => 'png',
            'image/gif'       => 'gif',
            'image/webp'      => 'webp',
            'image/svg+xml'   => 'svg',
            'application/pdf' => 'pdf',
            'video/mp4'       => 'mp4',
            'video/webm'      => 'webm',
        ];

        $ext      = $mimeToExt[$realMime] ?? 'bin';
        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $target   = rtrim($targetDir, '/') . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $target;
        }

        return null;
    }

    // ── Session Hardening ────────────────────────────────────────────────────

    /**
     * Aplica configuraciones de seguridad de sesión antes de session_start().
     * Llamar al inicio de index.php, antes de session_start().
     */
    public static function hardenSession(): void {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? '1' : '0');
        ini_set('session.gc_maxlifetime', '3600');        // 1 hora de inactividad
        ini_set('session.cookie_lifetime', '0');           // Expira al cerrar el navegador
    }

    /**
     * Regenera el ID de sesión para evitar Session Fixation.
     * Llamar justo después de autenticar a un usuario.
     */
    public static function regenerateSession(): void {
        session_regenerate_id(true);
    }
}
