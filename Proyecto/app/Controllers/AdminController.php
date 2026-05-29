<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Security;
use App\Models\User;
use App\Helpers\EmailService;
use Config\Database;
use PDO;

class AdminController extends Controller {

    public function __construct() {
        $this->requireRole('admin');
    }

    public function dashboard() {
        $userModel = new User();
        $stmt      = $userModel->readAll();
        $users     = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmtPending  = $userModel->readPending();
        $pendingUsers = $stmtPending->fetchAll(PDO::FETCH_ASSOC);
        $pendingCount = count($pendingUsers);

        $stats = [
            'total'     => count($users),
            'pending'   => $pendingCount,
            'approved'  => count(array_filter($users, function($u) { return $u['status'] === 'approved'; })),
            'rejected'  => count(array_filter($users, function($u) { return $u['status'] === 'rejected'; })),
            'suspended' => count(array_filter($users, function($u) { return $u['status'] === 'suspended'; })),
        ];

        $database = new Database();
        $conn     = $database->getConnection();
        $query    = "SELECT c.id, c.title, COUNT(e.id) as enroll_count
                     FROM courses c
                     LEFT JOIN enrollments e ON c.id = e.course_id
                     GROUP BY c.id, c.title";
        $stmtEnroll     = $conn->prepare($query);
        $stmtEnroll->execute();
        $enrollmentStats = $stmtEnroll->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function approveUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId) {
            $userModel = new User();
            if ($user = $userModel->findById($userId)) {
                $userModel->updateStatus($userId, 'approved');
                EmailService::sendAccountApprovedEmail($user['email'], $user['username']);
                $this->redirectWithSuccess('index.php?action=admin_dashboard', 'Usuario aprobado exitosamente.');
            }
        }
        $this->redirectWithError('index.php?action=admin_dashboard', 'Error al aprobar usuario.');
    }

    public function rejectUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId) {
            $userModel = new User();
            if ($user = $userModel->findById($userId)) {
                $userModel->updateStatus($userId, 'rejected');
                EmailService::sendAccountRejectedEmail($user['email'], $user['username']);
                $this->redirectWithSuccess('index.php?action=admin_dashboard', 'Usuario rechazado.');
            }
        }
        $this->redirectWithError('index.php?action=admin_dashboard', 'Error al rechazar usuario.');
    }

    public function suspendUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId && $userId != $_SESSION['user_id']) {
            $userModel = new User();
            if ($userModel->findById($userId)) {
                $userModel->updateStatus($userId, 'suspended');
                $this->redirectWithSuccess('index.php?action=admin_dashboard', 'Usuario suspendido.');
            }
        }
        $this->redirectWithError('index.php?action=admin_dashboard', 'Error al suspender usuario.');
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
        $this->redirectWithError('index.php?action=admin_dashboard', 'Usuario no encontrado.');
    }

    public function updateUser() {
        if ($this->isPost()) {
            Security::verifyCsrf();

            $userId   = $_POST['id'];
            $fullName = $this->input('full_name');
            $email    = $this->input('email');
            $phone    = $this->input('phone');
            $role     = $this->input('role');
            $status   = $this->input('status');

            $userModel = new User();
            $userModel->updateProfile($userId, $fullName, $email, $phone);

            $database = new Database();
            $conn     = $database->getConnection();
            $stmt = $conn->prepare("UPDATE users SET role = :role, status = :status WHERE id = :id");
            $stmt->bindParam(':role',   $role);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id',     $userId);
            $stmt->execute();

            $this->redirectWithSuccess('index.php?action=admin_dashboard', 'Datos de usuario actualizados.');
        }
    }

    public function deleteUser() {
        $userId = $_GET['id'] ?? null;
        if ($userId && $userId != $_SESSION['user_id']) {
            $userModel = new User();
            if ($userModel->delete($userId)) {
                $this->redirectWithSuccess('index.php?action=admin_dashboard', 'Usuario eliminado de forma permanente.');
            }
        }
        $this->redirectWithError('index.php?action=admin_dashboard', 'No se pudo eliminar el usuario.');
    }
}
