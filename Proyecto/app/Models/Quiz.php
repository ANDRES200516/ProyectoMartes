<?php
namespace App\Models;

use Config\Database;
use PDO;

class Quiz {
    private $conn;
    private $table_name = "quizzes";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($moduleId, $title, $description = '') {
        $query = "INSERT INTO " . $this->table_name . " (module_id, title, description) VALUES (:module_id, :title, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":module_id", $moduleId);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        return $stmt->execute();
    }

    public function findByModuleId($moduleId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE module_id = :module_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":module_id", $moduleId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveAttempt($userId, $quizId, $score, $passed) {
        $query = "INSERT INTO quiz_attempts (user_id, quiz_id, score, passed) VALUES (:user_id, :quiz_id, :score, :passed)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":quiz_id", $quizId);
        $stmt->bindParam(":score", $score);
        $stmt->bindParam(":passed", $passed, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUserBestAttempt($userId, $quizId) {
        $query = "SELECT * FROM quiz_attempts WHERE user_id = :user_id AND quiz_id = :quiz_id ORDER BY score DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":quiz_id", $quizId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
