<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Security;
use App\Models\User;
use App\Helpers\EmailService;

class AuthController extends Controller {
    
    public function login() {
        $isAjax = $this->isAjax();

        if ($this->isPost()) {
            Security::verifyCsrf();

            $user = new User();
            $user->username = $this->input('username');
            $password = $_POST['password'] ?? '';

            if (!empty($user->username) && !empty($password)) {
                if ($user->usernameExists() && password_verify($password, $user->password)) {
                    if ($user->status === 'pending') {
                        $msg = "Tu cuenta está pendiente de aprobación por el administrador.";
                        if ($isAjax) $this->json(['success' => false, 'message' => $msg]);
                        $_SESSION['swal_error'] = $msg;
                    } elseif ($user->status === 'rejected') {
                        $msg = "Tu cuenta ha sido rechazada. Contacta al administrador.";
                        if ($isAjax) $this->json(['success' => false, 'message' => $msg]);
                        $_SESSION['swal_error'] = $msg;
                    } else {
                        Security::regenerateSession();
                        $_SESSION['user_id']  = $user->id;
                        $_SESSION['username'] = $user->username;
                        $_SESSION['role']     = $user->role;

                        $redirect = ($user->role === 'admin')
                            ? 'index.php?action=admin_dashboard'
                            : 'index.php?action=dashboard';

                        if ($isAjax) $this->json(['success' => true, 'redirect' => $redirect]);
                        $this->redirect($redirect);
                    }
                } else {
                    $msg = "Usuario o contraseña incorrectos.";
                    if ($isAjax) $this->json(['success' => false, 'message' => $msg]);
                    $_SESSION['swal_error'] = $msg;
                }
            } else {
                $msg = "Por favor, complete todos los campos.";
                if ($isAjax) $this->json(['success' => false, 'message' => $msg]);
                $_SESSION['swal_error'] = $msg;
            }
        }

        require_once __DIR__ . '/../Views/auth/login.php';
    }
    
    public function register() {
        $isAjax = $this->isAjax();

        if ($this->isPost()) {
            Security::verifyCsrf();

            $user = new User();
            $user->username = $this->input('username');
            $user->email    = $this->input('email');
            $password = $_POST['password'] ?? '';

            if (empty($user->username) || empty($user->email) || empty($password)) {
                $msg = "Todos los campos son requeridos.";
                if ($isAjax) $this->json(['success' => false, 'message' => $msg]);
                $_SESSION['swal_error'] = $msg;
            } elseif (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $msg = "El correo electrónico no es válido.";
                if ($isAjax) $this->json(['success' => false, 'message' => $msg]);
                $_SESSION['swal_error'] = $msg;
            } elseif ($user->usernameExists()) {
                $msg = "El nombre de usuario ya está en uso.";
                if ($isAjax) $this->json(['success' => false, 'message' => $msg]);
                $_SESSION['swal_error'] = $msg;
            } else {
                $user->password = password_hash($password, PASSWORD_BCRYPT);
                $user->role     = 'user';
                $user->status   = 'pending';

                if ($user->create()) {
                    EmailService::sendRegistrationEmail($user->email, $user->username);
                    // Notificar al administrador principal
                    EmailService::sendAdminNotificationEmail(
                        'sebastian321hernandezno@gmail.com', 'Administrador', $user->username, $user->email
                    );
                    if ($isAjax) $this->json(['success' => true, 'redirect' => 'index.php?action=login']);
                    $this->redirectWithSuccess('index.php?action=login',
                        'Registro exitoso. Tu cuenta está pendiente de aprobación.');
                } else {
                    $msg = "Error al registrar el usuario. El correo podría estar en uso.";
                    if ($isAjax) $this->json(['success' => false, 'message' => $msg]);
                    $_SESSION['swal_error'] = $msg;
                }
            }
        }

        require_once __DIR__ . '/../Views/auth/register.php';
    }
    
    public function socialLogin() {
        $provider = $_GET['provider'] ?? 'social';
        // Redirigir a la pantalla de consentimiento simulada
        header("Location: index.php?action=oauth_consent&provider=" . urlencode($provider));
        exit;
    }

    public function oauthConsent() {
        $provider = $_GET['provider'] ?? 'social';
        // Mostrar la vista de consentimiento
        require_once __DIR__ . '/../Views/auth/oauth_consent.php';
    }

    public function oauthCallback() {
        $provider = $_GET['provider'] ?? 'social';
        
        // Simular datos obtenidos del proveedor social tras el consentimiento
        $social_email = strtolower($provider) . 'user' . rand(100,999) . '@example.com';
        
        // Guardar temporalmente en sesión para completar el perfil
        $_SESSION['oauth_provider'] = $provider;
        $_SESSION['oauth_email'] = $social_email;
        
        header('Location: index.php?action=complete_social_profile');
        exit;
    }

    public function completeSocialProfile() {
        $error = '';
        
        // Si no hay sesión oauth, devolver al login
        if (!isset($_SESSION['oauth_provider'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $user->username = $_POST['username'] ?? '';
            $user->email = $_POST['email'] ?? $_SESSION['oauth_email'];
            $password = $_POST['password'] ?? '';
            
            if (!empty($user->username) && !empty($password)) {
                if ($user->usernameExists()) {
                    $_SESSION['swal_error'] = "El usuario ya existe. Elija otro nombre.";
                } else {
                    $user->password = password_hash($password, PASSWORD_DEFAULT);
                    $user->role = 'user';
                    
                    // Insertar como 'pending' para requerir aprobación
                    $database = new \Config\Database();
                    $conn = $database->getConnection();
                    $insertQuery = "INSERT INTO users SET username=:username, email=:email, password=:password, role=:role, status='pending'";
                    $insStmt = $conn->prepare($insertQuery);
                    $insStmt->bindParam(":username", $user->username);
                    $insStmt->bindParam(":email", $user->email);
                    $insStmt->bindParam(":password", $user->password);
                    $insStmt->bindParam(":role", $user->role);
                    
                    if ($insStmt->execute()) {
                        // Enviar correo de notificación de registro exitoso (pendiente de aprobación)
                        EmailService::sendRegistrationEmail($user->email, $user->username);

                        // Notificar a todos los administradores registrados
                        $admins = $user->getAdmins();
                        foreach ($admins as $admin) {
                            EmailService::sendAdminNotificationEmail($admin['email'], $admin['username'], $user->username, $user->email);
                        }

                        // Limpiar variables temporales de oauth
                        unset($_SESSION['oauth_provider']);
                        unset($_SESSION['oauth_email']);
                        
                        // Redirigir al login con mensaje de éxito (pendiente de aprobación)
                        header('Location: index.php?action=login&status=pending');
                        exit;
                    } else {
                        $_SESSION['swal_error'] = "Error al completar el registro.";
                    }
                }
            } else {
                $_SESSION['swal_error'] = "Por favor, complete todos los campos.";
            }
        }
        
        require_once __DIR__ . '/../Views/auth/complete_profile.php';
    }

    public function githubLogin() {
        $authUrl = "https://github.com/login/oauth/authorize?" . http_build_query([
            'client_id' => GITHUB_CLIENT_ID,
            'redirect_uri' => GITHUB_REDIRECT_URI,
            'scope' => 'read:user user:email'
        ]);
        header("Location: $authUrl");
        exit;
    }

    public function githubCallback() {
        if (!isset($_GET['code'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $code = $_GET['code'];

        // 1. Obtener Access Token
        $tokenUrl = "https://github.com/login/oauth/access_token";
        $tokenData = [
            'client_id' => GITHUB_CLIENT_ID,
            'client_secret' => GITHUB_CLIENT_SECRET,
            'code' => $code,
            'redirect_uri' => GITHUB_REDIRECT_URI
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita errores SSL en localhost
        $tokenResponse = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($tokenResponse === false) {
            die("Error de conexión (cURL 1): " . $curlError);
        }

        $tokenJson = json_decode($tokenResponse, true);

        if (isset($tokenJson['error']) || !isset($tokenJson['access_token'])) {
            die("Error de GitHub OAuth. Verifica tus credenciales (CLIENT_ID y SECRET) en config/github.php. Detalle: " . htmlspecialchars($tokenJson['error_description'] ?? 'Error desconocido') . " | Respuesta cruda: " . htmlspecialchars($tokenResponse));
        }

        $accessToken = $tokenJson['access_token'];

        // 2. Obtener correos del usuario
        $userUrl = "https://api.github.com/user/emails";
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $userUrl);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, [
            'Authorization: token ' . $accessToken,
            'Accept: application/vnd.github.v3+json',
            'User-Agent: Learns-class-App' // Requisito obligatorio de GitHub
        ]);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false); // Evita errores SSL en localhost
        $userResponse = curl_exec($ch2);
        $curlError2 = curl_error($ch2);
        curl_close($ch2);

        if ($userResponse === false) {
            die("Error de conexión (cURL 2): " . $curlError2);
        }

        $emailsJson = json_decode($userResponse, true);
        $primaryEmail = null;

        // Buscar el correo primario verificado
        if (is_array($emailsJson)) {
            foreach ($emailsJson as $emailInfo) {
                if ($emailInfo['primary'] && $emailInfo['verified']) {
                    $primaryEmail = $emailInfo['email'];
                    break;
                }
            }
        }

        if ($primaryEmail) {
            $_SESSION['oauth_provider'] = 'github';
            $_SESSION['oauth_email'] = $primaryEmail;
            header('Location: index.php?action=complete_social_profile');
            exit;
        } else {
            die("Error obteniendo el correo electrónico de GitHub. Asegúrate de tener un correo verificado público.");
        }
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('index.php');
    }
}
