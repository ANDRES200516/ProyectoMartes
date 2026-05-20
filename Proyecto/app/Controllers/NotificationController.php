<?php
namespace App\Controllers;

use App\Models\Notification;

class NotificationController {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function markAllRead() {
        $notif = new Notification();
        $notif->markAllAsRead($_SESSION['user_id']);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}
?>
