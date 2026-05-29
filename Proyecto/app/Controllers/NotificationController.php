<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller {

    public function __construct() {
        $this->auth();
    }

    public function markAllRead() {
        $notif = new Notification();
        $notif->markAllAsRead($_SESSION['user_id']);
        $this->json(['success' => true]);
    }
}
