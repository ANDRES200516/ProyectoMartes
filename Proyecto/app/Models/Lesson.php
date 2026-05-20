<?php
namespace App\Models;

use Config\Database;
use PDO;

class Lesson {
    private $conn;
    private $table_name = "lessons";

    public $id;
    public $module_id;
    public $title;
    public $content;
    public $video_url;
    public $video_type;
    public $pdf_url;
    public $duration_minutes;
    public $sort_order;
    public $is_free;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET module_id=:module_id, title=:title, content=:content, video_url=:video_url, 
                      video_type=:video_type, pdf_url=:pdf_url, duration_minutes=:duration_minutes, 
                      sort_order=:sort_order, is_free=:is_free";
        $stmt = $this->conn->prepare($query);

        $this->module_id = intval($this->module_id);
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = $this->content; // Allow rich text (HTML editor)
        $this->video_url = htmlspecialchars(strip_tags($this->video_url));
        $this->video_type = htmlspecialchars(strip_tags($this->video_type));
        $this->pdf_url = htmlspecialchars(strip_tags($this->pdf_url));
        $this->duration_minutes = intval($this->duration_minutes);
        $this->sort_order = intval($this->sort_order);
        $this->is_free = intval($this->is_free);

        $stmt->bindParam(":module_id", $this->module_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":video_url", $this->video_url);
        $stmt->bindParam(":video_type", $this->video_type);
        $stmt->bindParam(":pdf_url", $this->pdf_url);
        $stmt->bindParam(":duration_minutes", $this->duration_minutes);
        $stmt->bindParam(":sort_order", $this->sort_order);
        $stmt->bindParam(":is_free", $this->is_free);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->updateCourseLessonsCountByModule($this->module_id);
            return $this->id;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title=:title, content=:content, video_url=:video_url, video_type=:video_type,
                      duration_minutes=:duration_minutes, sort_order=:sort_order, is_free=:is_free";
        
        if (!empty($this->pdf_url)) {
            $query .= ", pdf_url=:pdf_url";
        }
        
        $query .= " WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = $this->content;
        $this->video_url = htmlspecialchars(strip_tags($this->video_url));
        $this->video_type = htmlspecialchars(strip_tags($this->video_type));
        $this->duration_minutes = intval($this->duration_minutes);
        $this->sort_order = intval($this->sort_order);
        $this->is_free = intval($this->is_free);
        $this->id = intval($this->id);

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":video_url", $this->video_url);
        $stmt->bindParam(":video_type", $this->video_type);
        $stmt->bindParam(":duration_minutes", $this->duration_minutes);
        $stmt->bindParam(":sort_order", $this->sort_order);
        $stmt->bindParam(":is_free", $this->is_free);
        $stmt->bindParam(":id", $this->id);

        if (!empty($this->pdf_url)) {
            $this->pdf_url = htmlspecialchars(strip_tags($this->pdf_url));
            $stmt->bindParam(":pdf_url", $this->pdf_url);
        }

        if ($stmt->execute()) {
            $this->updateCourseLessonsCountByModule($this->module_id);
            return true;
        }
        return false;
    }

    public function delete($id) {
        $lesson = $this->findById($id);
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($lesson) {
                $this->updateCourseLessonsCountByModule($lesson['module_id']);
            }
            return true;
        }
        return false;
    }

    private function updateCourseLessonsCountByModule($moduleId) {
        $query = "SELECT course_id FROM modules WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $moduleId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $course = new Course();
            $course->updateLessonsCount($row['course_id']);
        }
    }

    public function getByModule($moduleId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE module_id = :module_id ORDER BY sort_order ASC, id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":module_id", $moduleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT l.*, m.course_id FROM " . $this->table_name . " l 
                  JOIN modules m ON l.module_id = m.id 
                  WHERE l.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Progress helpers
    public function isCompleted($userId, $lessonId) {
        $query = "SELECT completed FROM lesson_progress WHERE user_id = :user_id AND lesson_id = :lesson_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'user_id' => $userId,
            'lesson_id' => $lessonId
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (intval($row['completed']) === 1) : false;
    }

    public function markProgress($userId, $lessonId, $completed) {
        $completedVal = $completed ? 1 : 0;
        $completedAt = $completed ? 'CURRENT_TIMESTAMP' : 'NULL';

        $query = "INSERT INTO lesson_progress (user_id, lesson_id, completed, completed_at)
                  VALUES (:user_id, :lesson_id, :completed, " . ($completed ? "CURRENT_TIMESTAMP" : "NULL") . ")
                  ON DUPLICATE KEY UPDATE completed = :completed2, completed_at = " . ($completed ? "CURRENT_TIMESTAMP" : "NULL");
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":lesson_id", $lessonId);
        $stmt->bindParam(":completed", $completedVal);
        $stmt->bindParam(":completed2", $completedVal);
        
        if ($stmt->execute()) {
            // Find course ID to trigger overall progress update
            $lesson = $this->findById($lessonId);
            if ($lesson) {
                $enrollment = new Enrollment();
                $enrollment->updateProgressAndCheckCompletion($userId, $lesson['course_id']);
                $enrollment->updateLastLesson($userId, $lesson['course_id'], $lessonId);
            }
            return true;
        }
        return false;
    }

    public function getNextLesson($lessonId) {
        $current = $this->findById($lessonId);
        if (!$current) return null;

        // Try to find next lesson in same module
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE module_id = :module_id AND (sort_order > :sort_order OR (sort_order = :sort_order2 AND id > :id))
                  ORDER BY sort_order ASC, id ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'module_id' => $current['module_id'],
            'sort_order' => $current['sort_order'],
            'sort_order2' => $current['sort_order'],
            'id' => $current['id']
        ]);
        $next = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($next) return $next;

        // If not found, try to find first lesson of the next module in same course
        $query = "SELECT m.id FROM modules m 
                  WHERE m.course_id = :course_id AND m.sort_order > (
                      SELECT sort_order FROM modules WHERE id = :module_id
                  )
                  ORDER BY m.sort_order ASC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'course_id' => $current['course_id'],
            'module_id' => $current['module_id']
        ]);
        $nextModule = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($nextModule) {
            $query = "SELECT * FROM " . $this->table_name . " 
                      WHERE module_id = :module_id 
                      ORDER BY sort_order ASC, id ASC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['module_id' => $nextModule['id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null; // Completed course, no next lesson
    }

    public function getPreviousLesson($lessonId) {
        $current = $this->findById($lessonId);
        if (!$current) return null;

        // Try to find previous lesson in same module
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE module_id = :module_id AND (sort_order < :sort_order OR (sort_order = :sort_order2 AND id < :id))
                  ORDER BY sort_order DESC, id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'module_id' => $current['module_id'],
            'sort_order' => $current['sort_order'],
            'sort_order2' => $current['sort_order'],
            'id' => $current['id']
        ]);
        $prev = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($prev) return $prev;

        // If not found, try to find last lesson of the previous module in same course
        $query = "SELECT m.id FROM modules m 
                  WHERE m.course_id = :course_id AND m.sort_order < (
                      SELECT sort_order FROM modules WHERE id = :module_id
                  )
                  ORDER BY m.sort_order DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            'course_id' => $current['course_id'],
            'module_id' => $current['module_id']
        ]);
        $prevModule = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($prevModule) {
            $query = "SELECT * FROM " . $this->table_name . " 
                      WHERE module_id = :module_id 
                      ORDER BY sort_order DESC, id DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['module_id' => $prevModule['id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null; // First lesson, no previous
    }
}
?>
