<?php
namespace App\Helpers;

// Cargar PHPMailer
require_once __DIR__ . '/../Libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Servicio básico de envío de correos.
 * Utiliza PHPMailer con configuración SMTP cargada desde el .env.
 */
class EmailService {
    
    private static $fromEmail = 'no-reply@learnclass.com';
    private static $fromName  = 'Learn class Admin';

    private static function loadEnv() {
        $envPath = __DIR__ . '/../../.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }
    }

    private static function send($to, $subject, $messageHTML) {
        self::loadEnv();
        // Enviar usando PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'] ?? ''; // ej: tu_correo@gmail.com
            $mail->Password   = $_ENV['SMTP_PASS'] ?? ''; // ej: contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'] ?? 587;
            
            // Si no se han configurado credenciales en el .env, no intentamos enviar para evitar crash.
            if (empty($mail->Username) || empty($mail->Password)) {
                return false;
            }

            // Remitente y destinatario
            $mail->setFrom(self::$fromEmail, self::$fromName);
            $mail->addAddress($to);

            // Layout básico premium
            $html = "
            <html>
            <body style='font-family: Arial, sans-serif; background-color: #f1f5f9; padding: 20px;'>
                <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>
                    <div style='background: linear-gradient(135deg, #7c4dff, #38bdf8); padding: 20px; text-align: center; color: #ffffff;'>
                        <h2 style='margin: 0;'>Learn class</h2>
                    </div>
                    <div style='padding: 30px; color: #334155; line-height: 1.6;'>
                        {$messageHTML}
                    </div>
                    <div style='background: #f8fafc; padding: 15px; text-align: center; font-size: 12px; color: #94a3b8;'>
                        &copy; " . date('Y') . " Learn class. Todos los derechos reservados.
                    </div>
                </div>
            </body>
            </html>";

            // Contenido
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("No se pudo enviar el correo a $to. Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    /**
     * Enviar correo al usuario cuando se registra (pendiente de aprobación)
     */
    public static function sendRegistrationEmail($email, $username) {
        $subject = "Bienvenido a Learn class - Registro Pendiente";
        $msg = "
            <h3>Hola {$username},</h3>
            <p>Gracias por registrarte en nuestra plataforma de cursos.</p>
            <p>Tu cuenta ha sido creada exitosamente y actualmente se encuentra <strong>pendiente de aprobación</strong> por parte de un administrador.</p>
            <p>Recibirás un nuevo correo electrónico tan pronto como tu cuenta sea activada para que puedas comenzar a aprender.</p>
        ";
        return self::send($email, $subject, $msg);
    }

    /**
     * Enviar correo a los administradores notificando un nuevo registro
     */
    public static function sendAdminNotificationEmail($adminEmail, $adminName, $newUsername, $newUserEmail) {
        $subject = "Nuevo usuario registrado - Requiere aprobación";
        $msg = "
            <h3>Hola {$adminName},</h3>
            <p>Un nuevo usuario se ha registrado en la plataforma y requiere aprobación.</p>
            <table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>
                <tr>
                    <td style='padding: 8px; border-bottom: 1px solid #e2e8f0;'><strong>Usuario:</strong></td>
                    <td style='padding: 8px; border-bottom: 1px solid #e2e8f0;'>{$newUsername}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; border-bottom: 1px solid #e2e8f0;'><strong>Email:</strong></td>
                    <td style='padding: 8px; border-bottom: 1px solid #e2e8f0;'>{$newUserEmail}</td>
                </tr>
            </table>
            <br>
            <p>Por favor, ingresa al panel de administración para aprobar o rechazar esta solicitud.</p>
            <a href='http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/index.php?action=login' style='display: inline-block; padding: 10px 20px; background-color: #7c4dff; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;'>Ir al Panel</a>
        ";
        return self::send($adminEmail, $subject, $msg);
    }

    /**
     * Enviar correo al usuario cuando su cuenta ha sido aprobada
     */
    public static function sendAccountApprovedEmail($email, $username) {
        $subject = "¡Tu cuenta en Learn class ha sido aprobada!";
        $msg = "
            <h3>¡Felicidades {$username}!</h3>
            <p>Tu cuenta ha sido aprobada por un administrador. Ya puedes iniciar sesión y acceder a todos nuestros cursos.</p>
            <br>
            <a href='http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/index.php?action=login' style='display: inline-block; padding: 10px 20px; background-color: #10b981; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;'>Iniciar Sesión</a>
        ";
        return self::send($email, $subject, $msg);
    }

    /**
     * Enviar correo al usuario cuando su cuenta ha sido rechazada
     */
    public static function sendAccountRejectedEmail($email, $username) {
        $subject = "Actualización sobre tu registro en Learn class";
        $msg = "
            <h3>Hola {$username},</h3>
            <p>Lamentamos informarte que tu solicitud de registro en nuestra plataforma no ha sido aprobada en esta ocasión.</p>
            <p>Si crees que esto es un error, por favor contacta con soporte.</p>
        ";
        return self::send($email, $subject, $msg);
    }
}
