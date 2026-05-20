<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\Notification;

class ProfileController {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }
    
    public function show() {
        $userModel = new User();
        $userModel->findById($_SESSION['user_id']);
        $user = $userModel;
        
        $success = $_GET['msg'] ?? '';
        $error = '';

        // Obtener inscripciones y progreso
        $enrollmentModel = new Enrollment();
        $enrollments = $enrollmentModel->getUserEnrollments($_SESSION['user_id']);

        // Certificados del usuario
        $certificateModel = new Certificate();
        $certificates = $certificateModel->getUserCertificates($_SESSION['user_id']);

        // Actividad / notificaciones recientes
        $notifModel = new Notification();
        $notifications = $notifModel->getUnreadByUser($_SESSION['user_id']);

        require_once __DIR__ . '/../Views/user/profile.php';
    }
    
    public function updateData() {
        $error = '';
        $userModel = new User();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: index.php?action=profile&msg=invalid_email');
                exit;
            }
            
            if ($userModel->updateProfile($_SESSION['user_id'], $full_name, $email, $phone)) {
                header('Location: index.php?action=profile&msg=data_updated');
            } else {
                header('Location: index.php?action=profile&msg=error');
            }
            exit;
        }
        
        header('Location: index.php?action=profile');
        exit;
    }
    
    public function updatePhoto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
            $file = $_FILES['photo'];
            
            // Validar que sea una imagen
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                header('Location: index.php?action=profile&msg=invalid_type');
                exit;
            }
            
            // Validar tamaño (máximo 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                header('Location: index.php?action=profile&msg=too_large');
                exit;
            }
            
            // Generar nombre único
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
            $uploadDir = __DIR__ . '/../../public/uploads/';
            $uploadPath = $uploadDir . $filename;
            
            // Eliminar foto anterior si existe
            $userModel = new User();
            $userModel->findById($_SESSION['user_id']);
            if (!empty($userModel->photo)) {
                $oldPhoto = $uploadDir . $userModel->photo;
                if (file_exists($oldPhoto)) {
                    unlink($oldPhoto);
                }
            }
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $userModel->updatePhoto($_SESSION['user_id'], $filename);
                header('Location: index.php?action=profile&msg=photo_updated');
            } else {
                header('Location: index.php?action=profile&msg=upload_error');
            }
            exit;
        }
        
        header('Location: index.php?action=profile');
        exit;
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (empty($current) || empty($new) || empty($confirm)) {
                header('Location: index.php?action=profile&msg=password_missing');
                exit;
            }

            if ($new !== $confirm) {
                header('Location: index.php?action=profile&msg=password_mismatch');
                exit;
            }

            if (strlen($new) < 8) {
                header('Location: index.php?action=profile&msg=password_short');
                exit;
            }

            $userModel = new User();
            if (!$userModel->verifyPassword($_SESSION['user_id'], $current)) {
                header('Location: index.php?action=profile&msg=current_incorrect');
                exit;
            }

            $hash = password_hash($new, PASSWORD_DEFAULT);
            if ($userModel->updatePassword($_SESSION['user_id'], $hash)) {
                header('Location: index.php?action=profile&msg=password_updated');
            } else {
                header('Location: index.php?action=profile&msg=password_error');
            }
            exit;
        }

        header('Location: index.php?action=profile');
        exit;
    }
}
?>
