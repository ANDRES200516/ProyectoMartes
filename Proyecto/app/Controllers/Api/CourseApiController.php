<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Gamification;
use Config\Database;
use PDO;

/**
 * CourseApiController — REST API JSON para la plataforma.
 *
 * Autenticación: API Key via header X-API-KEY o query param ?api_key=
 * Base URL: index.php?action=api/...
 *
 * Endpoints:
 *  GET  api/courses          → Lista de cursos activos
 *  GET  api/courses/{id}     → Detalle de un curso
 *  GET  api/leaderboard      → Top 10 usuarios por XP
 *  GET  api/me               → Perfil + puntos del usuario autenticado
 *  POST api/keys/generate    → Generar API Key (requiere sesión)
 */
class CourseApiController extends Controller {

    private $apiUser = null;

    public function __construct() {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: X-API-KEY, Content-Type');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }

    // ── Autenticación API Key ────────────────────────────────────

    private function authenticate(): bool {
        $key = $_SERVER['HTTP_X_API_KEY'] ?? $_GET['api_key'] ?? '';
        if (empty($key)) return false;

        $db   = (new Database())->getConnection();
        $stmt = $db->prepare(
            "SELECT u.id, u.username, u.role FROM api_keys k
             JOIN users u ON u.id = k.user_id
             WHERE k.api_key = :key AND k.is_active = 1"
        );
        $stmt->execute([':key' => $key]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return false;

        $this->apiUser = $user;
        // Update last_used
        $db->prepare("UPDATE api_keys SET last_used = NOW() WHERE api_key = :key")
           ->execute([':key' => $key]);
        return true;
    }

    private function requireAuth(): void {
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized. Provide a valid API Key via X-API-KEY header or ?api_key=']);
            exit;
        }
    }

    // ── Endpoints ────────────────────────────────────────────────

    /** GET api/courses */
    public function courses(): void {
        $this->requireAuth();

        $courseModel = new Course();
        $courses     = $courseModel->readAll(['status' => 'active', 'search' => '', 'level' => '', 'sort' => '']);

        $data = array_map(function($c) {
            return [
                'id'                => $c['id'],
                'title'             => $c['title'],
                'slug'              => $c['slug'],
                'level'             => $c['level'],
                'category'          => $c['category'],
                'short_description' => $c['short_description'],
                'duration_hours'    => $c['duration_hours'],
                'thumbnail'         => $c['thumbnail'],
                'enrollments'       => $c['enrollment_count'] ?? 0,
            ];
        }, $courses);

        $this->json(['success' => true, 'count' => count($data), 'data' => $data]);
    }

    /** GET api/courses/{id} */
    public function courseDetail(): void {
        $this->requireAuth();

        $id          = (int)($_GET['id'] ?? 0);
        $courseModel = new Course();
        $course      = $courseModel->findById($id);

        if (!$course) {
            http_response_code(404);
            $this->json(['success' => false, 'error' => 'Course not found']);
        }

        $this->json(['success' => true, 'data' => $course]);
    }

    /** GET api/leaderboard */
    public function leaderboard(): void {
        $this->requireAuth();

        $gamification = new Gamification();
        $top          = $gamification->getLeaderboard(10);

        $data = array_map(function($u) use ($top) {
            return [
                'rank'         => array_search($u, $top) + 1,
                'username'     => $u['username'],
                'total_points' => (int)$u['total_points'],
                'streak'       => (int)($u['streak'] ?? 0),
            ];
        }, $top);

        $this->json(['success' => true, 'data' => $data]);
    }

    /** GET api/me — Perfil del usuario autenticado con la API key */
    public function me(): void {
        $this->requireAuth();

        $gamification = new Gamification();
        $uid          = (int)$this->apiUser['id'];

        $this->json([
            'success' => true,
            'data'    => [
                'id'           => $uid,
                'username'     => $this->apiUser['username'],
                'role'         => $this->apiUser['role'],
                'total_points' => $gamification->getTotalPoints($uid),
                'rank'         => $gamification->getUserRank($uid),
                'streak'       => $gamification->getStreak($uid),
                'badges'       => $gamification->getUserBadges($uid),
            ]
        ]);
    }

    /** POST api/keys/generate — Requiere sesión web activa, no API key */
    public function generateKey(): void {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            $this->json(['success' => false, 'error' => 'Session required to generate API keys']);
        }

        $uid    = (int)$_SESSION['user_id'];
        $label  = htmlspecialchars($_POST['label'] ?? 'Mi API Key', ENT_QUOTES, 'UTF-8');
        $newKey = bin2hex(random_bytes(32));

        $db = (new Database())->getConnection();
        $db->prepare(
            "INSERT INTO api_keys (user_id, api_key, label) VALUES (:uid, :key, :label)"
        )->execute([':uid' => $uid, ':key' => $newKey, ':label' => $label]);

        $this->json(['success' => true, 'api_key' => $newKey, 'label' => $label,
                     'message' => 'Guarda esta clave en un lugar seguro, no se mostrará de nuevo.']);
    }
}
