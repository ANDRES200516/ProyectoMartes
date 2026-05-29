<?php
namespace App\Models;

use Config\Database;
use PDO;

class Question {
    private $conn;
    private $table_name = "quiz_questions";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($quizId, $question, $optA, $optB, $optC, $optD, $correct, $explanation = '') {
        $query = "INSERT INTO " . $this->table_name . " (quiz_id, question, option_a, option_b, option_c, option_d, correct_option, explanation) 
                  VALUES (:quiz_id, :question, :optA, :optB, :optC, :optD, :correct, :explanation)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quiz_id", $quizId);
        $stmt->bindParam(":question", $question);
        $stmt->bindParam(":optA", $optA);
        $stmt->bindParam(":optB", $optB);
        $stmt->bindParam(":optC", $optC);
        $stmt->bindParam(":optD", $optD);
        $stmt->bindParam(":correct", $correct);
        $stmt->bindParam(":explanation", $explanation);
        return $stmt->execute();
    }

    public function getByQuizId($quizId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE quiz_id = :quiz_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quiz_id", $quizId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionWithAnswer($id) {
        $query = "SELECT id, correct_option, explanation FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
