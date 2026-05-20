<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\EmailService;

class AuthController {
    
    public function login() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $user->username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (!empty($user->username) && !empty($password)) {
                if ($user->usernameExists() && password_verify($password, $user->password)) {
                    // Verificar status de aprobación
                    if ($user->status === 'pending') {
                        $_SESSION['swal_error'] = "Tu cuenta está pendiente de aprobación por el administrador.";
                    } else if ($user->status === 'rejected') {
                        $_SESSION['swal_error'] = "Tu cuenta ha sido rechazada. Contacta al administrador.";
                    } else {
                        $_SESSION['user_id'] = $user->id;
                        $_SESSION['username'] = $user->username;
                        $_SESSION['role'] = $user->role;
                        
                        if ($user->role === 'admin') {
                            header('Location: index.php?action=admin_dashboard');
                        } else {
                            header('Location: index.php?action=dashboard');
                        }
                        exit;
                    }
                } else {
                    $_SESSION['swal_error'] = "Usuario o contraseña incorrectos.";
                }
            } else {
                $_SESSION['swal_error'] = "Por favor, complete todos los campos.";
            }
        }
        
        require_once __DIR__ . '/../Views/auth/login.php';
    }
    
    public function register() {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $user->username = $_POST['username'] ?? '';
            $user->email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (!empty($user->username) && !empty($user->email) && !empty($password)) {
                if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['swal_error'] = "El correo electrónico no es válido.";
                } else if ($user->usernameExists()) {
                    $_SESSION['swal_error'] = "El usuario ya existe. Elija otro nombre.";
                } else {
                    $user->password = password_hash($password, PASSWORD_DEFAULT);
                    $user->role = 'user';
                    
                    if ($user->create()) {
                        // Enviar correo de notificación de registro exitoso (pendiente de aprobación)
                        EmailService::sendRegistrationEmail($user->email, $user->username);
                        
                        // Notificar a todos los administradores registrados
                        $admins = $user->getAdmins();
                        foreach ($admins as $admin) {
                            EmailService::sendAdminNotificationEmail($admin['email'], $admin['username'], $user->username, $user->email);
                        }

                        $_SESSION['swal_success'] = "Registro exitoso. Tu cuenta está pendiente de aprobación por el administrador. Recibirás un correo cuando sea aprobada.";
                    } else {
                        $_SESSION['swal_error'] = "Error al registrar el usuario. El correo podría estar en uso.";
                    }
                }
            } else {
                $_SESSION['swal_error'] = "Por favor, complete todos los campos.";
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
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>
