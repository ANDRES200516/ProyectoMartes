<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\EmailService;
use Config\Database;
use PDO;

class AdminController {
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }
    }
    
    public function dashboard() {
        $userModel = new User();
        $stmt = $userModel->readAll();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmtPending = $userModel->readPending();
        $pendingUsers = $stmtPending->fetchAll(PDO::FETCH_ASSOC);
        $pendingCount = count($pendingUsers);

        // Dynamic stats
        $stats = [
            'total' => count($users),
            'pending' => $pendingCount,
            'approved' => count(array_filter($users, function($u) { 
                return $u['status'] === 'approved'; 
            })),
            'rejected' => count(array_filter($users, function($u) {
                return $u['status'] === 'rejected';
            })),
            'suspended' => count(array_filter($users, function($u) {
                return $u['status'] === 'suspended';
            }))
        ];
        
        // Query enrollment counts by course for the Chart
        $database = new Database();
        $conn = $database->getConnection();
        $query = "SELECT c.title, COUNT(e.id) as enroll_count 
                  FROM courses c 
                  LEFT JOIN enrollments e ON c.id = e.course_id 
                  GROUP BY c.id, c.title";
        // incluir el id del curso en los resultados para enlaces
        $query = "SELECT c.id, c.title, COUNT(e.id) as enroll_count 
                  FROM courses c 
                  LEFT JOIN enrollments e ON c.id = e.course_id 
                  GROUP BY c.id, c.title";
        $stmtEnroll = $conn->prepare($query);
        $stmtEnroll->execute();
        $enrollmentStats = $stmtEnroll->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }
    
    public function approveUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId) {
            $userModel = new User();
            if ($userModel->findById($userId)) {
                $userModel->updateStatus($userId, 'approved');
                
                // Send email notification using EmailService
                EmailService::sendApprovalEmail($userModel->email, $userModel->username);
                
                $_SESSION['swal_success'] = 'Usuario aprobado exitosamente.';
                header('Location: index.php?action=admin_dashboard');
                exit;
            }
        }
        $_SESSION['swal_error'] = 'Error al aprobar usuario.';
        header('Location: index.php?action=admin_dashboard');
        exit;
    }
    
    public function rejectUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId) {
            $userModel = new User();
            if ($userModel->findById($userId)) {
                $userModel->updateStatus($userId, 'rejected');
                
                // Send rejection email using EmailService
                EmailService::sendRejectionEmail($userModel->email, $userModel->username);
                
                $_SESSION['swal_success'] = 'Usuario rechazado.';
                header('Location: index.php?action=admin_dashboard');
                exit;
            }
        }
        $_SESSION['swal_error'] = 'Error al rechazar usuario.';
        header('Location: index.php?action=admin_dashboard');
        exit;
    }
    
    public function suspendUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId && $userId != $_SESSION['user_id']) {
            $userModel = new User();
            if ($userModel->findById($userId)) {
                $userModel->updateStatus($userId, 'suspended');
                $_SESSION['swal_success'] = 'Usuario suspendido.';
                header('Location: index.php?action=admin_dashboard');
                exit;
            }
        }
        $_SESSION['swal_error'] = 'Error al suspender usuario.';
        header('Location: index.php?action=admin_dashboard');
        exit;
    }

    public function editUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId) {
            $userModel = new User();
            if ($userModel->findById($userId)) {
                $user = $userModel;
                require_once __DIR__ . '/../Views/admin/edit_user.php';
                return;
            }
        }
        $_SESSION['swal_error'] = 'Usuario no encontrado.';
        header('Location: index.php?action=admin_dashboard');
        exit;
    }

    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['id'];
            $fullName = $_POST['full_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $role = $_POST['role'];
            $status = $_POST['status'];

            $userModel = new User();
            $userModel->updateProfile($userId, $fullName, $email, $phone);
            
            $query = "UPDATE users SET role = :role, status = :status WHERE id = :id";
            $database = new Database();
            $conn = $database->getConnection();
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":role", $role);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":id", $userId);
            $stmt->execute();

            $_SESSION['swal_success'] = 'Datos de usuario actualizados.';
            header('Location: index.php?action=admin_dashboard');
            exit;
        }
    }

    public function deleteUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId && $userId != $_SESSION['user_id']) { 
            $userModel = new User();
            if ($userModel->delete($userId)) {
                $_SESSION['swal_success'] = 'Usuario eliminado de forma permanente.';
                header('Location: index.php?action=admin_dashboard');
                exit;
            }
        }
        $_SESSION['swal_error'] = 'No se pudo eliminar el usuario.';
        header('Location: index.php?action=admin_dashboard');
        exit;
    }
}
?>
