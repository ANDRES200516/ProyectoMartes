<?php
namespace App\Models;

use Config\Database;
use PDO;

/**
 * Gamification — Modelo de puntos, badges, streaks y leaderboard.
 *
 * Usa el sistema de puntos XP para motivar al alumno:
 *  - Completar lección  → +10 XP
 *  - Aprobar quiz       → +30 XP (+10 bonus por 100%)
 *  - Completar curso    → +100 XP
 *  - Racha diaria       → +5 XP/día (bonus ×2 desde el día 7)
 */
class Gamification {
    private $db;

    // ── Tabla de puntos por acción ──────────────────────────────
    public const POINTS = [
        'lesson_complete'  => 10,
        'quiz_pass'        => 30,
        'quiz_perfect'     => 40,   // 100% score
        'course_complete'  => 100,
        'streak_daily'     => 5,
        'streak_bonus'     => 10,   // extra from day 7
    ];

    public function __construct() {
        $database  = new Database();
        $this->db  = $database->getConnection();
    }

    // ── Puntos ──────────────────────────────────────────────────

    /**
     * Añadir puntos a un usuario y guardar el motivo.
     */
    public function addPoints($userId, $reason, $referenceId = 0) {
        $points = self::POINTS[$reason] ?? 0;
        if ($points <= 0) return 0;

        $stmt = $this->db->prepare(
            "INSERT INTO user_points (user_id, points, reason, reference_id) VALUES (:uid, :pts, :reason, :ref)"
        );
        $stmt->execute([':uid' => $userId, ':pts' => $points, ':reason' => $reason, ':ref' => $referenceId]);
        return $points;
    }

    /**
     * Total de puntos XP de un usuario.
     */
    public function getTotalPoints($userId) {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(points),0) FROM user_points WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Historial de puntos reciente de un usuario.
     */
    public function getPointsHistory($userId, $limit = 10) {
        $stmt = $this->db->prepare(
            "SELECT points, reason, reference_id, created_at FROM user_points
             WHERE user_id = :uid ORDER BY created_at DESC LIMIT :lim"
        );
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Leaderboard ─────────────────────────────────────────────

    /**
     * Top N usuarios por puntos totales.
     */
    public function getLeaderboard($limit = 10) {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.username, u.full_name, u.photo,
                    COALESCE(SUM(p.points), 0) AS total_points,
                    (SELECT current_streak FROM user_streaks WHERE user_id = u.id) AS streak
             FROM users u
             LEFT JOIN user_points p ON u.id = p.user_id
             WHERE u.role NOT IN ('admin')
             GROUP BY u.id
             ORDER BY total_points DESC
             LIMIT :lim"
        );
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Posición del usuario en el leaderboard global.
     */
    public function getUserRank($userId) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) + 1 FROM (
                SELECT user_id, SUM(points) AS total
                FROM user_points GROUP BY user_id
                HAVING total > (
                    SELECT COALESCE(SUM(points),0) FROM user_points WHERE user_id = :uid
                )
            ) AS ranked"
        );
        $stmt->execute([':uid' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    // ── Streaks ─────────────────────────────────────────────────

    /**
     * Actualiza la racha diaria del usuario.
     * Devuelve el número de días actuales de racha.
     */
    public function updateStreak($userId) {
        $today     = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $stmt = $this->db->prepare(
            "SELECT current_streak, longest_streak, last_activity FROM user_streaks WHERE user_id = :uid"
        );
        $stmt->execute([':uid' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            // Primera actividad
            $this->db->prepare(
                "INSERT INTO user_streaks (user_id, current_streak, longest_streak, last_activity)
                 VALUES (:uid, 1, 1, :today)"
            )->execute([':uid' => $userId, ':today' => $today]);
            return 1;
        }

        if ($row['last_activity'] === $today) {
            return (int)$row['current_streak']; // Ya se contó hoy
        }

        if ($row['last_activity'] === $yesterday) {
            $newStreak = (int)$row['current_streak'] + 1;
        } else {
            $newStreak = 1; // Racha rota
        }

        $longest = max($newStreak, (int)$row['longest_streak']);
        $this->db->prepare(
            "UPDATE user_streaks SET current_streak = :streak, longest_streak = :longest, last_activity = :today
             WHERE user_id = :uid"
        )->execute([':streak' => $newStreak, ':longest' => $longest, ':today' => $today, ':uid' => $userId]);

        // Puntos de racha
        $this->addPoints($userId, 'streak_daily', 0);
        if ($newStreak >= 7) {
            $this->addPoints($userId, 'streak_bonus', 0);
        }

        return $newStreak;
    }

    /**
     * Obtener datos de racha del usuario.
     */
    public function getStreak($userId) {
        $stmt = $this->db->prepare("SELECT * FROM user_streaks WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['current_streak' => 0, 'longest_streak' => 0, 'last_activity' => null];
    }

    // ── Badges ──────────────────────────────────────────────────

    /**
     * Otorgar un badge a un usuario (si no lo tiene ya).
     * Devuelve true si fue otorgado, false si ya lo tenía.
     */
    public function awardBadge($userId, $badgeSlug) {
        $stmt = $this->db->prepare("SELECT id FROM badges WHERE slug = :slug");
        $stmt->execute([':slug' => $badgeSlug]);
        $badge = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$badge) return false;

        try {
            $this->db->prepare(
                "INSERT IGNORE INTO user_badges (user_id, badge_id) VALUES (:uid, :bid)"
            )->execute([':uid' => $userId, ':bid' => $badge['id']]);
            return $this->db->lastInsertId() > 0;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Verificar y otorgar badges automáticamente según logros del usuario.
     * Llamar después de cada acción relevante.
     */
    public function checkAndAwardBadges($userId) {
        $awarded = [];

        // Primera lección
        $lessons = $this->countPointsByReason($userId, 'lesson_complete');
        if ($lessons >= 1 && $this->awardBadge($userId, 'first_lesson')) {
            $awarded[] = 'first_lesson';
        }

        // Primer curso completo
        $courses = $this->countPointsByReason($userId, 'course_complete');
        if ($courses >= 1 && $this->awardBadge($userId, 'first_course')) {
            $awarded[] = 'first_course';
        }
        if ($courses >= 5 && $this->awardBadge($userId, 'five_courses')) {
            $awarded[] = 'five_courses';
        }

        // Streak badges
        $streak = $this->getStreak($userId);
        if ((int)($streak['current_streak'] ?? 0) >= 7 && $this->awardBadge($userId, 'streak_7')) {
            $awarded[] = 'streak_7';
        }
        if ((int)($streak['current_streak'] ?? 0) >= 30 && $this->awardBadge($userId, 'streak_30')) {
            $awarded[] = 'streak_30';
        }

        // Leaderboard top 3
        if ($this->getUserRank($userId) <= 3 && $this->awardBadge($userId, 'top_leaderboard')) {
            $awarded[] = 'top_leaderboard';
        }

        // Quiz perfecto
        $perfect = $this->countPointsByReason($userId, 'quiz_perfect');
        if ($perfect >= 1 && $this->awardBadge($userId, 'quiz_ace')) {
            $awarded[] = 'quiz_ace';
        }

        return $awarded;
    }

    /**
     * Obtener todos los badges de un usuario con metadatos.
     */
    public function getUserBadges($userId) {
        $stmt = $this->db->prepare(
            "SELECT b.slug, b.name, b.description, b.icon, b.color, ub.awarded_at
             FROM user_badges ub
             JOIN badges b ON b.id = ub.badge_id
             WHERE ub.user_id = :uid
             ORDER BY ub.awarded_at DESC"
        );
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Todos los badges del catálogo, marcando cuáles tiene el usuario.
     */
    public function getAllBadgesWithStatus($userId) {
        $stmt = $this->db->prepare(
            "SELECT b.*, IF(ub.badge_id IS NOT NULL, 1, 0) AS earned
             FROM badges b
             LEFT JOIN user_badges ub ON ub.badge_id = b.id AND ub.user_id = :uid
             ORDER BY earned DESC, b.id ASC"
        );
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Analytics para Admin ─────────────────────────────────────

    /**
     * Estadísticas globales de la plataforma para el panel analytics.
     */
    public function getPlatformStats() {
        $stats = [];

        // Usuarios registrados total / nuevos esta semana
        $stmt = $this->db->query(
            "SELECT COUNT(*) as total,
                    SUM(IF(created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY), 1, 0)) as new_this_week
             FROM users WHERE role = 'user'"
        );
        $stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Cursos activos
        $stmt = $this->db->query(
            "SELECT COUNT(*) as total, SUM(IF(status='active',1,0)) as active FROM courses"
        );
        $stats['courses'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Inscripciones
        $stmt = $this->db->query(
            "SELECT COUNT(*) as total,
                    SUM(IF(enrolled_at >= DATE_SUB(NOW(), INTERVAL 30 DAY), 1, 0)) as last_30d
             FROM enrollments"
        );
        $stats['enrollments'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Certificados emitidos
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM certificates");
        $stats['certificates'] = (int)$stmt->fetchColumn();

        // Total puntos otorgados
        $stmt = $this->db->query("SELECT COALESCE(SUM(points),0) FROM user_points");
        $stats['total_xp'] = (int)$stmt->fetchColumn();

        // Inscripciones por día últimos 30 días
        $stmt = $this->db->query(
            "SELECT DATE(enrolled_at) as day, COUNT(*) as count
             FROM enrollments
             WHERE enrolled_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY day ORDER BY day ASC"
        );
        $stats['enrollments_chart'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Top 5 cursos por inscripciones
        $stmt = $this->db->query(
            "SELECT c.title, COUNT(e.id) as count
             FROM courses c
             LEFT JOIN enrollments e ON c.id = e.course_id
             GROUP BY c.id ORDER BY count DESC LIMIT 5"
        );
        $stats['top_courses'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Distribución de niveles de curso
        $stmt = $this->db->query(
            "SELECT level, COUNT(*) as count FROM courses GROUP BY level"
        );
        $stats['levels_dist'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    // ── Helpers privados ─────────────────────────────────────────

    private function countPointsByReason($userId, $reason) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM user_points WHERE user_id = :uid AND reason = :reason"
        );
        $stmt->execute([':uid' => $userId, ':reason' => $reason]);
        return (int)$stmt->fetchColumn();
    }
}
