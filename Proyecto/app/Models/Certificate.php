<?php
namespace App\Models;

use Config\Database;
use PDO;

class Certificate {
    private $conn;
    private $table_name = "certificates";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function findByCode($code) {
        $query = "SELECT cert.*, u.full_name as student_name, c.title as course_title, c.duration_hours, c.level as course_level
                  FROM " . $this->table_name . " cert
                  JOIN users u ON cert.user_id = u.id
                  JOIN courses c ON cert.course_id = c.id
                  WHERE cert.code = :code LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":code", $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserCertificateForCourse($userId, $courseId) {
        $query = "SELECT cert.*, u.full_name as student_name, c.title as course_title, c.duration_hours, c.level as course_level
                  FROM " . $this->table_name . " cert
                  JOIN users u ON cert.user_id = u.id
                  JOIN courses c ON cert.course_id = c.id
                  WHERE cert.user_id = :user_id AND cert.course_id = :course_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":course_id", $courseId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCertificates() {
        $query = "SELECT cert.*, u.full_name as student_name, u.email as student_email, c.title as course_title
                  FROM " . $this->table_name . " cert
                  JOIN users u ON cert.user_id = u.id
                  JOIN courses c ON cert.course_id = c.id
                  ORDER BY cert.issued_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserCertificates($userId) {
        $query = "SELECT cert.*, c.title as course_title, c.slug as course_slug
                  FROM " . $this->table_name . " cert
                  JOIN courses c ON cert.course_id = c.id
                  WHERE cert.user_id = :user_id
                  ORDER BY cert.issued_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
