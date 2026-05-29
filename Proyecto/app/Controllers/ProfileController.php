<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Security;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\Notification;

class ProfileController extends Controller {

    public function __construct() {
        $this->auth();
    }

    public function show() {
        $userModel = new User();
        $userModel->findById($_SESSION['user_id']);
        $user = $userModel;

        $success = $_GET['msg'] ?? '';
        $error   = '';

        $enrollmentModel = new Enrollment();
        $enrollments     = $enrollmentModel->getUserEnrollments($_SESSION['user_id']);

        $certificateModel = new Certificate();
        $certificates     = $certificateModel->getUserCertificates($_SESSION['user_id']);

        $notifModel    = new Notification();
        $notifications = $notifModel->getUnreadByUser($_SESSION['user_id']);

        require_once __DIR__ . '/../Views/user/profile.php';
    }

    public function updateData() {
        if ($this->isPost()) {
            Security::verifyCsrf();

            $full_name = $this->input('full_name');
            $email     = $this->input('email');
            $phone     = $this->input('phone');

            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->redirect('index.php?action=profile&msg=invalid_email');
            }

            $userModel = new User();
            if ($userModel->updateProfile($_SESSION['user_id'], $full_name, $email, $phone)) {
                $this->redirectWithSuccess('index.php?action=profile', 'Perfil actualizado correctamente.');
            } else {
                $this->redirectWithError('index.php?action=profile', 'Error al actualizar el perfil.');
            }
        }
        $this->redirect('index.php?action=profile');
    }

    public function updatePhoto() {
        if ($this->isPost() && isset($_FILES['photo'])) {
            Security::verifyCsrf();

            $file = $_FILES['photo'];

            // Validación de MIME real (Fase 3 — Seguridad)
            if (!Security::validateUpload($file, 'image')) {
                $this->redirectWithError('index.php?action=profile', 'Tipo de archivo no permitido.');
            }

            if ($file['size'] > 5 * 1024 * 1024) {
                $this->redirectWithError('index.php?action=profile', 'La imagen excede el tamaño máximo (5 MB).');
            }

            $uploadDir = __DIR__ . '/../../public/assets/uploads/photos/';
            $saved = Security::moveUpload($file, $uploadDir, 'image');

            if ($saved) {
                $userModel = new User();
                $userModel->findById($_SESSION['user_id']);

                // Borrar foto anterior
                if (!empty($userModel->photo) && file_exists($userModel->photo)) {
                    @unlink($userModel->photo);
                }

                $userModel->updatePhoto($_SESSION['user_id'], $saved);
                $this->redirectWithSuccess('index.php?action=profile', 'Foto de perfil actualizada.');
            } else {
                $this->redirectWithError('index.php?action=profile', 'Error al subir la imagen.');
            }
        }
        $this->redirect('index.php?action=profile');
    }

    public function updatePassword() {
        if ($this->isPost()) {
            Security::verifyCsrf();

            $current = $_POST['current_password'] ?? '';
            $new     = $_POST['new_password']     ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (empty($current) || empty($new) || empty($confirm)) {
                $this->redirectWithError('index.php?action=profile', 'Completa todos los campos de contraseña.');
            }
            if ($new !== $confirm) {
                $this->redirectWithError('index.php?action=profile', 'Las contraseñas nuevas no coinciden.');
            }
            if (strlen($new) < 8) {
                $this->redirectWithError('index.php?action=profile', 'La contraseña debe tener mínimo 8 caracteres.');
            }

            $userModel = new User();
            if (!$userModel->verifyPassword($_SESSION['user_id'], $current)) {
                $this->redirectWithError('index.php?action=profile', 'La contraseña actual es incorrecta.');
            }

            $hash = password_hash($new, PASSWORD_BCRYPT);
            if ($userModel->updatePassword($_SESSION['user_id'], $hash)) {
                $this->redirectWithSuccess('index.php?action=profile', 'Contraseña actualizada correctamente.');
            } else {
                $this->redirectWithError('index.php?action=profile', 'Error al actualizar la contraseña.');
            }
        }
        $this->redirect('index.php?action=profile');
    }
}
