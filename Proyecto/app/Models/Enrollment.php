<?php
namespace App\Models;

use Config\Database;
use PDO;

class Enrollment {
    private $conn;
    private $table_name = "enrollments";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function enroll($userId, $courseId, $motivation = '', $knowledge = '', $hours = '', $goal = '') {
        $query = "INSERT IGNORE INTO " . $this->table_name . " (user_id, course_id, motivation, knowledge_level, weekly_hours, main_goal, status, progress_percentage) 
                  VALUES (:user_id, :course_id, :motivation, :knowledge, :hours, :goal, 'active', 0.00)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->bindParam(":motivation", $motivation);
        $stmt->bindParam(":knowledge", $knowledge);
        $stmt->bindParam(":hours", $hours);
        $stmt->bindParam(":goal", $goal);
        return $stmt->execute();
    }

    public function isEnrolled($userId, $courseId) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getUserEnrollments($userId) {
        $query = "SELECT e.*, c.title as course_title, c.slug as course_slug, c.thumbnail as course_thumbnail, c.level as course_level, c.duration_hours,
                  (SELECT COUNT(*) FROM lessons l JOIN modules m ON l.module_id = m.id WHERE m.course_id = e.course_id) as total_lessons
                  FROM " . $this->table_name . " e 
                  JOIN courses c ON e.course_id = c.id
                  WHERE e.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEnrollmentDetails($userId, $courseId) {
        $query = "SELECT e.*, c.title as course_title, c.slug as course_slug, c.thumbnail as course_thumbnail
                  FROM " . $this->table_name . " e
                  JOIN courses c ON e.course_id = c.id
                  WHERE e.user_id = :user_id AND e.course_id = :course_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateLastLesson($userId, $courseId, $lessonId) {
        $query = "UPDATE " . $this->table_name . " SET last_lesson_id = :lesson_id WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":lesson_id", $lessonId);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":course_id", $courseId);
        return $stmt->execute();
    }

    public function updateProgressAndCheckCompletion($userId, $courseId) {
        // Calculate progress percentage
        // Total lessons in the course
        $query = "SELECT COUNT(l.id) as total FROM lessons l 
                  JOIN modules m ON l.module_id = m.id 
                  WHERE m.course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['course_id' => $courseId]);
        $totalLessons = intval($stmt->fetch(PDO::FETCH_ASSOC)['total']);

        if ($totalLessons === 0) return false;

        // Completed lessons by the user in this course
        $query = "SELECT COUNT(lp.id) as completed FROM lesson_progress lp
                  JOIN lessons l ON lp.lesson_id = l.id
                  JOIN modules m ON l.module_id = m.id
                  WHERE m.course_id = :course_id AND lp.user_id = :user_id AND lp.completed = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'course_id' => $courseId,
            'user_id' => $userId
        ]);
        $completedLessons = intval($stmt->fetch(PDO::FETCH_ASSOC)['completed']);

        $percentage = ($completedLessons / $totalLessons) * 100.0;
        if ($percentage > 100) $percentage = 100;

        $completedQueryPart = "";
        if ($percentage >= 100) {
            $completedQueryPart = ", status = 'completed', completed_at = CURRENT_TIMESTAMP";
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET progress_percentage = :progress " . $completedQueryPart . " 
                  WHERE user_id = :user_id AND course_id = :course_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":progress", $percentage);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->execute();

        if ($percentage >= 100) {
            // Check if certificate already exists, otherwise create it
            $certQuery = "SELECT id FROM certificates WHERE user_id = :user_id AND course_id = :course_id";
            $certStmt = $this->conn->prepare($certQuery);
            $certStmt->execute(['user_id' => $userId, 'course_id' => $courseId]);
            if ($certStmt->rowCount() === 0) {
                $code = strtoupper(substr(md5($userId . '_' . $courseId . '_' . time()), 0, 12));
                $createCert = "INSERT INTO certificates (user_id, course_id, code) VALUES (:user_id, :course_id, :code)";
                $cStmt = $this->conn->prepare($createCert);
                $cStmt->execute(['user_id' => $userId, 'course_id' => $courseId, 'code' => $code]);

                // Create a notification for the user
                $notif = new Notification();
                $notif->create($userId, 'success', '¡Felicitaciones! Has completado el curso y obtenido tu certificado.', "?action=certificate&code=" . $code);
            }
            return true;
        }

        return false;
    }

    public function getEnrolledStudents($courseId) {
        $query = "SELECT u.id, u.full_name, u.email, u.photo, e.progress_percentage, e.status, e.enrolled_at, e.completed_at
                  FROM " . $this->table_name . " e
                  JOIN users u ON e.user_id = u.id
                  WHERE e.course_id = :course_id
                  ORDER BY e.enrolled_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_id", $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
