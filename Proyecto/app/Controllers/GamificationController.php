<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Gamification;

/**
 * GamificationController — Endpoints del sistema de gamificación.
 * Leaderboard, badges del usuario, historial de XP.
 */
class GamificationController extends Controller {

    public function __construct() {
        $this->auth();
    }

    /** GET ?action=leaderboard → Vista pública del ranking */
    public function leaderboard(): void {
        $gamification = new Gamification();
        $leaderboard  = $gamification->getLeaderboard(20);
        $myRank       = $gamification->getUserRank((int)$_SESSION['user_id']);
        $myPoints     = $gamification->getTotalPoints((int)$_SESSION['user_id']);
        $myStreak     = $gamification->getStreak((int)$_SESSION['user_id']);
        $myBadges     = $gamification->getUserBadges((int)$_SESSION['user_id']);

        require_once __DIR__ . '/../Views/gamification/leaderboard.php';
    }

    /** GET ?action=badges → Catálogo de badges del usuario */
    public function badges(): void {
        $gamification = new Gamification();
        $allBadges    = $gamification->getAllBadgesWithStatus((int)$_SESSION['user_id']);
        $myPoints     = $gamification->getTotalPoints((int)$_SESSION['user_id']);
        $myStreak     = $gamification->getStreak((int)$_SESSION['user_id']);
        $myRank       = $gamification->getUserRank((int)$_SESSION['user_id']);

        require_once __DIR__ . '/../Views/gamification/badges.php';
    }

    /** GET ?action=analytics (admin only) */
    public function analytics(): void {
        $this->requireRole(['admin']);
        $gamification = new Gamification();
        $stats        = $gamification->getPlatformStats();
        $leaderboard  = $gamification->getLeaderboard(5);

        require_once __DIR__ . '/../Views/admin/analytics.php';
    }
}
