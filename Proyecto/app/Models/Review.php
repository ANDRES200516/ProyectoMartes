<?php
namespace App\Models;

use Config\Database;
use PDO;

class Review {
    private $conn;
    private $table_name = "reviews";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function addReview($userId, $courseId, $rating, $comment) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, course_id, rating, comment)
                  VALUES (:user_id, :course_id, :rating, :comment)
                  ON DUPLICATE KEY UPDATE rating = :rating2, comment = :comment2";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->bindParam(":rating", $rating);
        $stmt->bindParam(":comment", $comment);
        $stmt->bindParam(":rating2", $rating);
        $stmt->bindParam(":comment2", $comment);

        if ($stmt->execute()) {
            $this->updateCourseRating($courseId);
            return true;
        }
        return false;
    }

    public function getReviewsByCourse($courseId) {
        $query = "SELECT r.*, u.full_name as user_name, u.photo as user_photo 
                  FROM " . $this->table_name . " r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.course_id = :course_id 
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_id", $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hasReviewed($userId, $courseId) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE user_id = :user_id AND course_id = :course_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    private function updateCourseRating($courseId) {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_ratings 
                  FROM " . $this->table_name . " 
                  WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['course_id' => $courseId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $avg = $row['avg_rating'] ? round(floatval($row['avg_rating']), 2) : 0.00;
        $count = $row['total_ratings'] ? intval($row['total_ratings']) : 0;

        $updateQuery = "UPDATE courses SET rating_avg = :avg, rating_count = :count WHERE id = :id";
        $upStmt = $this->conn->prepare($updateQuery);
        $upStmt->execute([
            'avg' => $avg,
            'count' => $count,
            'id' => $courseId
        ]);
    }
}
?>
