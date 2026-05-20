<?php
namespace App\Models;

use Config\Database;
use PDO;

class Notification {
    private $conn;
    private $table_name = "notifications";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($userId, $type, $message, $link = null) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, type, message, link) 
                  VALUES (:user_id, :type, :message, :link)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":link", $link);
        return $stmt->execute();
    }

    public function getUnreadByUser($userId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAllAsRead($userId) {
        $query = "UPDATE " . $this->table_name . " SET is_read = 1 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>
