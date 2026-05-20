<?php
namespace App\Models;

use Config\Database;
use PDO;

class Course {
    private $conn;
    private $table_name = "courses";

    public $id;
    public $slug;
    public $title;
    public $description;
    public $short_description;
    public $level;
    public $duration_hours;
    public $requirements;
    public $objectives;
    public $tags;
    public $total_lessons;
    public $rating_avg;
    public $rating_count;
    public $thumbnail;
    public $banner;
    public $status;
    public $category;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET slug=:slug, title=:title, description=:description, short_description=:short_description,
                      level=:level, duration_hours=:duration_hours, requirements=:requirements, objectives=:objectives,
                      tags=:tags, thumbnail=:thumbnail, banner=:banner, status=:status, category=:category";
        
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->slug = htmlspecialchars(strip_tags($this->slug));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = $this->description; // Allow HTML (enriched)
        $this->short_description = htmlspecialchars(strip_tags($this->short_description));
        $this->level = htmlspecialchars(strip_tags($this->level));
        $this->duration_hours = floatval($this->duration_hours);
        $this->requirements = htmlspecialchars(strip_tags($this->requirements));
        $this->objectives = htmlspecialchars(strip_tags($this->objectives));
        $this->tags = htmlspecialchars(strip_tags($this->tags));
        $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
        $this->banner = htmlspecialchars(strip_tags($this->banner));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->category = htmlspecialchars(strip_tags($this->category));

        // bind
        $stmt->bindParam(":slug", $this->slug);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":short_description", $this->short_description);
        $stmt->bindParam(":level", $this->level);
        $stmt->bindParam(":duration_hours", $this->duration_hours);
        $stmt->bindParam(":requirements", $this->requirements);
        $stmt->bindParam(":objectives", $this->objectives);
        $stmt->bindParam(":tags", $this->tags);
        $stmt->bindParam(":thumbnail", $this->thumbnail);
        $stmt->bindParam(":banner", $this->banner);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":category", $this->category);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title=:title, description=:description, short_description=:short_description,
                      level=:level, duration_hours=:duration_hours, requirements=:requirements, objectives=:objectives,
                      tags=:tags, status=:status, category=:category";
        
        if (!empty($this->thumbnail)) {
            $query .= ", thumbnail=:thumbnail";
        }
        if (!empty($this->banner)) {
            $query .= ", banner=:banner";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = $this->description; // Allow HTML
        $this->short_description = htmlspecialchars(strip_tags($this->short_description));
        $this->level = htmlspecialchars(strip_tags($this->level));
        $this->duration_hours = floatval($this->duration_hours);
        $this->requirements = htmlspecialchars(strip_tags($this->requirements));
        $this->objectives = htmlspecialchars(strip_tags($this->objectives));
        $this->tags = htmlspecialchars(strip_tags($this->tags));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = intval($this->id);

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":short_description", $this->short_description);
        $stmt->bindParam(":level", $this->level);
        $stmt->bindParam(":duration_hours", $this->duration_hours);
        $stmt->bindParam(":requirements", $this->requirements);
        $stmt->bindParam(":objectives", $this->objectives);
        $stmt->bindParam(":tags", $this->tags);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":id", $this->id);

        if (!empty($this->thumbnail)) {
            $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
            $stmt->bindParam(":thumbnail", $this->thumbnail);
        }
        if (!empty($this->banner)) {
            $this->banner = htmlspecialchars(strip_tags($this->banner));
            $stmt->bindParam(":banner", $this->banner);
        }

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function duplicate($id) {
        $course = $this->findById($id);
        if (!$course) return false;

        $this->title = $course['title'] . ' (Copia)';
        $this->slug = $course['slug'] . '-copia-' . time();
        $this->description = $course['description'];
        $this->short_description = $course['short_description'];
        $this->level = $course['level'];
        $this->duration_hours = $course['duration_hours'];
        $this->requirements = $course['requirements'];
        $this->objectives = $course['objectives'];
        $this->tags = $course['tags'];
        $this->thumbnail = $course['thumbnail'];
        $this->banner = $course['banner'];
        $this->status = 'draft'; // Duplicate defaults to draft
        $this->category = $course['category'];

        $newCourseId = $this->create();
        if ($newCourseId) {
            // Duplicate modules and lessons too!
            $query = "SELECT * FROM modules WHERE course_id = :id ORDER BY sort_order";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $id]);
            $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($modules as $m) {
                $modQuery = "INSERT INTO modules SET course_id = :course_id, title = :title, description = :description, sort_order = :sort_order";
                $modStmt = $this->conn->prepare($modQuery);
                $modStmt->execute([
                    'course_id' => $newCourseId,
                    'title' => $m['title'],
                    'description' => $m['description'],
                    'sort_order' => $m['sort_order']
                ]);
                $newModuleId = $this->conn->lastInsertId();

                // Duplicate lessons for this module
                $lesQuery = "SELECT * FROM lessons WHERE module_id = :id ORDER BY sort_order";
                $lesStmt = $this->conn->prepare($lesQuery);
                $lesStmt->execute(['id' => $m['id']]);
                $lessons = $lesStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($lessons as $l) {
                    $lInsert = "INSERT INTO lessons SET module_id = :module_id, title = :title, content = :content,
                                video_url = :video_url, video_type = :video_type, pdf_url = :pdf_url,
                                duration_minutes = :duration_minutes, sort_order = :sort_order, is_free = :is_free";
                    $lStmt = $this->conn->prepare($lInsert);
                    $lStmt->execute([
                        'module_id' => $newModuleId,
                        'title' => $l['title'],
                        'content' => $l['content'],
                        'video_url' => $l['video_url'],
                        'video_type' => $l['video_type'],
                        'pdf_url' => $l['pdf_url'],
                        'duration_minutes' => $l['duration_minutes'],
                        'sort_order' => $l['sort_order'],
                        'is_free' => $l['is_free']
                    ]);
                }
            }
            $this->updateLessonsCount($newCourseId);
            return true;
        }
        return false;
    }

    public function updateLessonsCount($courseId) {
        $query = "UPDATE " . $this->table_name . " c
                  SET total_lessons = (
                      SELECT COUNT(*) FROM lessons l 
                      JOIN modules m ON l.module_id = m.id 
                      WHERE m.course_id = c.id
                  )
                  WHERE c.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $courseId]);
    }

    public static function resolveThumbnail(array $course): string {
        $relative = trim($course['thumbnail'] ?? '');
        if ($relative !== '' && self::fileExistsPublic($relative)) {
            return $relative;
        }

        $slug = strtolower($course['slug'] ?? '');
        $category = strtolower($course['category'] ?? '');
        $map = [
            'inteligencia-artificial' => 'assets/images/courses/ia-thumb.svg',
            'machine-learning' => 'assets/images/courses/ml-thumb.svg',
            'python-desde-cero' => 'assets/images/courses/python-thumb.svg',
            'sql-bases-de-datos' => 'assets/images/courses/sql-thumb.svg',
            'desarrollo-web-full-stack' => 'assets/images/courses/web-thumb.svg',
            'algoritmos-geneticos' => 'assets/images/courses/genetic-thumb.svg',
            'data-science' => 'assets/images/courses/ds-thumb.svg',
            'javascript-moderno' => 'assets/images/courses/js-thumb.svg',
        ];

        if (isset($map[$slug])) {
            return $map[$slug];
        }

        if (stripos($category, 'inteligencia') !== false || stripos($category, 'ia') !== false) {
            return 'assets/images/courses/ia-thumb.svg';
        }
        if (stripos($category, 'machine') !== false || stripos($category, 'aprendizaje') !== false) {
            return 'assets/images/courses/ml-thumb.svg';
        }
        if (stripos($category, 'python') !== false) {
            return 'assets/images/courses/python-thumb.svg';
        }
        if (stripos($category, 'sql') !== false || stripos($category, 'base de datos') !== false) {
            return 'assets/images/courses/sql-thumb.svg';
        }
        if (stripos($category, 'web') !== false || stripos($category, 'desarrollo') !== false) {
            return 'assets/images/courses/web-thumb.svg';
        }
        if (stripos($category, 'genet') !== false) {
            return 'assets/images/courses/genetic-thumb.svg';
        }
        if (stripos($category, 'data') !== false || stripos($category, 'science') !== false) {
            return 'assets/images/courses/ds-thumb.svg';
        }
        if (stripos($category, 'javascript') !== false) {
            return 'assets/images/courses/js-thumb.svg';
        }

        return 'assets/images/courses/default-thumb.svg';
    }

    public static function resolveBanner(array $course): string {
        $relative = trim($course['banner'] ?? '');
        if ($relative !== '' && self::fileExistsPublic($relative)) {
            return $relative;
        }

        $slug = strtolower($course['slug'] ?? '');
        $map = [
            'inteligencia-artificial' => 'assets/images/courses/ia-banner.svg',
            'machine-learning' => 'assets/images/courses/ml-banner.svg',
            'python-desde-cero' => 'assets/images/courses/python-banner.svg',
            'sql-bases-de-datos' => 'assets/images/courses/sql-banner.svg',
            'desarrollo-web-full-stack' => 'assets/images/courses/web-banner.svg',
            'algoritmos-geneticos' => 'assets/images/courses/genetic-banner.svg',
            'data-science' => 'assets/images/courses/ds-banner.svg',
            'javascript-moderno' => 'assets/images/courses/js-banner.svg',
        ];

        if (isset($map[$slug])) {
            return $map[$slug];
        }

        return 'assets/images/courses/default-banner.svg';
    }

    private static function fileExistsPublic(string $relativePath): bool {
        $publicFile = __DIR__ . '/../../public/' . ltrim($relativePath, '/');
        return file_exists($publicFile);
    }

    public function readAll($filters = []) {
        $query = "SELECT c.*, 
                  (SELECT COUNT(*) FROM modules WHERE course_id = c.id) as modules_count,
                  (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as students_count
                  FROM " . $this->table_name . " c WHERE 1=1";
        
        $params = [];
        if (!empty($filters['search'])) {
            $query .= " AND (c.title LIKE :search OR c.tags LIKE :search OR c.category LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['level'])) {
            $query .= " AND c.level = :level";
            $params['level'] = $filters['level'];
        }
        if (!empty($filters['status'])) {
            $query .= " AND c.status = :status";
            $params['status'] = $filters['status'];
        }

        // Sorting
        $orderBy = "c.created_at DESC";
        if (!empty($filters['sort'])) {
            if ($filters['sort'] == 'title') $orderBy = "c.title ASC";
            elseif ($filters['sort'] == 'students') $orderBy = "students_count DESC";
            elseif ($filters['sort'] == 'rating') $orderBy = "c.rating_avg DESC";
        }
        $query .= " ORDER BY " . $orderBy;

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readActive() {
        $query = "SELECT c.*, 
                  (SELECT COUNT(*) FROM modules WHERE course_id = c.id) as modules_count 
                  FROM " . $this->table_name . " c WHERE status = 'active' ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findBySlug($slug) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE slug = :slug LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStats() {
        $stats = [
            'total' => 0,
            'active' => 0,
            'inactive' => 0,
            'draft' => 0,
            'total_enrollments' => 0
        ];

        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM " . $this->table_name);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $stmt = $this->conn->prepare("SELECT COUNT(*) as active FROM " . $this->table_name . " WHERE status = 'active'");
        $stmt->execute();
        $stats['active'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];

        $stmt = $this->conn->prepare("SELECT COUNT(*) as inactive FROM " . $this->table_name . " WHERE status = 'inactive'");
        $stmt->execute();
        $stats['inactive'] = $stmt->fetch(PDO::FETCH_ASSOC)['inactive'];

        $stmt = $this->conn->prepare("SELECT COUNT(*) as draft FROM " . $this->table_name . " WHERE status = 'draft'");
        $stmt->execute();
        $stats['draft'] = $stmt->fetch(PDO::FETCH_ASSOC)['draft'];

        $stmt = $this->conn->prepare("SELECT COUNT(*) as enrolls FROM enrollments");
        $stmt->execute();
        $stats['total_enrollments'] = $stmt->fetch(PDO::FETCH_ASSOC)['enrolls'];

        return $stats;
    }
}
?>
