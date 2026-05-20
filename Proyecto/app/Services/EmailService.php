<?php
namespace App\Services;

class EmailService {
    
    private static function sendMail($to, $subject, $messageHtml) {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Learns class <noreply@learnsclass.com>\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "Subject: $subject\r\n";
        
        // 1. Intentar entrega directa por socket SMTP en localhost (para capturar con Maildev)
        $smtp_host = "127.0.0.1";
        $smtp_port = 1025;
        $timeout = 1; // Timeout corto para no alentar la carga si no está encendido
        
        $socket = @fsockopen($smtp_host, $smtp_port, $errno, $errstr, $timeout);
        if ($socket) {
            fgets($socket, 512); // Bienvenida
            fwrite($socket, "EHLO localhost\r\n");
            fgets($socket, 512);
            
            // Leer líneas extras si hay
            stream_set_timeout($socket, 0, 100000); // 100ms
            while ($line = fgets($socket, 512)) {
                $info = stream_get_meta_data($socket);
                if ($info['timed_out']) break;
            }
            
            fwrite($socket, "MAIL FROM: <noreply@learnsclass.com>\r\n");
            fgets($socket, 512);
            
            fwrite($socket, "RCPT TO: <$to>\r\n");
            fgets($socket, 512);
            
            fwrite($socket, "DATA\r\n");
            fgets($socket, 512);
            
            $body = $headers . "\r\n" . $messageHtml . "\r\n.\r\n";
            fwrite($socket, $body);
            fgets($socket, 512);
            
            fwrite($socket, "QUIT\r\n");
            fclose($socket);
            return true;
        }
        
        // 2. Si no hay un SMTP local encendido (como en producción), usar mail() estándar de PHP
        return @mail($to, $subject, $messageHtml, $headers);
    }
    
    public static function sendRegistrationEmail($email, $username) {
        $subject = "Registro Exitoso - Pendiente de Aprobación";
        $message = "
        <html>
        <head>
            <title>Registro Exitoso - Learns class</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #0f172a; color: #f8fafc; padding: 2rem; margin: 0;'>
            <div style='max-width: 500px; margin: 2rem auto; background-color: #1e293b; padding: 2.5rem; border-radius: 16px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.3);'>
                <div style='text-align: center; margin-bottom: 1.5rem;'>
                    <h1 style='color: #ef4444; margin: 0; font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px;'>Learns class</h1>
                </div>
                <h2 style='color: #3b82f6; border-bottom: 1px solid #334155; padding-bottom: 0.5rem; margin-top: 0;'>¡Hola, {$username}!</h2>
                <p style='line-height: 1.6; color: #cbd5e1;'>Te has registrado exitosamente en la plataforma de cursos de tecnología y ciencia de datos **Learns class**.</p>
                <p style='line-height: 1.6; color: #cbd5e1;'>Tu cuenta se encuentra actualmente <strong style='color: #fbbf24;'>pendiente de aprobación</strong> por parte de un administrador para garantizar la integridad de nuestra comunidad de aprendizaje.</p>
                <p style='line-height: 1.6; color: #cbd5e1;'>Recibirás otra notificación por correo electrónico tan pronto como tu acceso sea verificado y activado.</p>
                
                <div style='margin-top: 2rem; padding: 1rem; background-color: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; border-radius: 4px;'>
                    <p style='margin: 0; font-size: 0.9rem; color: #93c5fd; font-weight: 600;'>¿Qué sigue?</p>
                    <p style='margin: 0.25rem 0 0 0; font-size: 0.85rem; color: #cbd5e1;'>Nuestros administradores revisan los nuevos registros de forma constante. Este proceso suele tomar menos de 24 horas.</p>
                </div>
                
                <p style='color: #94a3b8; font-size: 0.85rem; margin-top: 2.5rem; border-top: 1px solid #334155; padding-top: 1rem; line-height: 1.5;'>
                    Gracias por registrarte y por tu paciencia.<br>
                    <strong>El equipo de Learns class</strong>
                </p>
            </div>
        </body>
        </html>";
        
        return self::sendMail($email, $subject, $message);
    }

    public static function sendApprovalEmail($email, $username) {
        $subject = "Cuenta Aprobada - Plataforma de Cursos Learns class";
        $message = "
        <html>
        <head>
            <title>Cuenta Aprobada</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #0f172a; color: #f8fafc; padding: 2rem; margin: 0;'>
            <div style='max-width: 500px; margin: 2rem auto; background-color: #1e293b; padding: 2.5rem; border-radius: 16px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.3);'>
                <div style='text-align: center; margin-bottom: 1.5rem;'>
                    <h1 style='color: #ef4444; margin: 0; font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px;'>Learns class</h1>
                </div>
                <h2 style='color: #22c55e; border-bottom: 1px solid #334155; padding-bottom: 0.5rem; margin-top: 0;'>¡Bienvenido, {$username}!</h2>
                <p style='line-height: 1.6; color: #cbd5e1;'>¡Grandes noticias! Tu cuenta ha sido <strong style='color: #22c55e;'>aprobada</strong> exitosamente por el administrador.</p>
                <p style='line-height: 1.6; color: #cbd5e1;'>Ya tienes acceso completo e ilimitado para iniciar sesión, explorar nuestros laboratorios interactivos y certificar tus conocimientos en Inteligencia Artificial, Algoritmos Genéticos y Regresión Lineal.</p>
                
                <div style='text-align: center; margin: 2rem 0;'>
                    <a href='http://localhost:8000/index.php?action=login' style='display: inline-block; padding: 0.8rem 2rem; background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; text-decoration: none; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);'>Iniciar Sesión Ahora</a>
                </div>
                
                <p style='color: #94a3b8; font-size: 0.85rem; margin-top: 2.5rem; border-top: 1px solid #334155; padding-top: 1rem; line-height: 1.5;'>
                    Nos vemos en clase,<br>
                    <strong>El equipo de Learns class</strong>
                </p>
            </div>
        </body>
        </html>";
        
        return self::sendMail($email, $subject, $message);
    }
    
    public static function sendRejectionEmail($email, $username) {
        $subject = "Cuenta Rechazada - Plataforma de Cursos Learns class";
        $message = "
        <html>
        <head>
            <title>Cuenta Rechazada</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #0f172a; color: #f8fafc; padding: 2rem; margin: 0;'>
            <div style='max-width: 500px; margin: 2rem auto; background-color: #1e293b; padding: 2.5rem; border-radius: 16px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.3);'>
                <div style='text-align: center; margin-bottom: 1.5rem;'>
                    <h1 style='color: #ef4444; margin: 0; font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px;'>Learns class</h1>
                </div>
                <h2 style='color: #ef4444; border-bottom: 1px solid #334155; padding-bottom: 0.5rem; margin-top: 0;'>Hola, {$username}</h2>
                <p style='line-height: 1.6; color: #cbd5e1;'>Queremos informarte que tu solicitud de registro en la plataforma ha sido <strong style='color: #ef4444;'>rechazada</strong> por el administrador durante el proceso de verificación.</p>
                <p style='line-height: 1.6; color: #cbd5e1;'>Si consideras que ha habido un malentendido o error en tu solicitud, no dudes en ponerte en contacto directamente con soporte técnico escribiendo al administrador del sistema.</p>
                
                <p style='color: #94a3b8; font-size: 0.85rem; margin-top: 2.5rem; border-top: 1px solid #334155; padding-top: 1rem; line-height: 1.5;'>
                    Atentamente,<br>
                    <strong>Soporte de Learns class</strong>
                </p>
            </div>
        </body>
        </html>";
        
        return self::sendMail($email, $subject, $message);
    }

    public static function sendAdminNotificationEmail($adminEmail, $adminUsername, $newUserUsername, $newUserEmail) {
        $subject = "Nueva Solicitud de Registro Pendiente - Learns class";
        $message = "
        <html>
        <head>
            <title>Nueva Solicitud de Registro - Learns class</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #0f172a; color: #f8fafc; padding: 2rem; margin: 0;'>
            <div style='max-width: 500px; margin: 2rem auto; background-color: #1e293b; padding: 2.5rem; border-radius: 16px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.3);'>
                <div style='text-align: center; margin-bottom: 1.5rem;'>
                    <h1 style='color: #ef4444; margin: 0; font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px;'>Learns class</h1>
                </div>
                <h2 style='color: #fbbf24; border-bottom: 1px solid #334155; padding-bottom: 0.5rem; margin-top: 0;'>¡Hola, {$adminUsername}!</h2>
                <p style='line-height: 1.6; color: #cbd5e1;'>Se ha registrado un nuevo usuario en la plataforma que requiere tu aprobación manual:</p>
                
                <div style='margin: 1.5rem 0; padding: 1.2rem; background-color: rgba(251, 191, 36, 0.05); border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 8px;'>
                    <p style='margin: 0; color: #fef08a; font-weight: 600;'>Detalles del Nuevo Usuario:</p>
                    <p style='margin: 0.5rem 0 0 0; font-size: 0.9rem; color: #cbd5e1;'><strong>Usuario:</strong> {$newUserUsername}</p>
                    <p style='margin: 0.25rem 0 0 0; font-size: 0.9rem; color: #cbd5e1;'><strong>Correo Electrónico:</strong> {$newUserEmail}</p>
                </div>
                
                <p style='line-height: 1.6; color: #cbd5e1;'>Por favor, ingresa al panel de administración para revisar, aprobar o rechazar esta solicitud.</p>
                
                <div style='text-align: center; margin: 2rem 0;'>
                    <a href='http://localhost:8000/index.php?action=admin_dashboard' style='display: inline-block; padding: 0.8rem 2rem; background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #0f172a; text-decoration: none; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);'>Ir al Panel de Control</a>
                </div>
                
                <p style='color: #94a3b8; font-size: 0.85rem; margin-top: 2.5rem; border-top: 1px solid #334155; padding-top: 1rem; line-height: 1.5;'>
                    Sistema de Notificaciones Automáticas<br>
                    <strong>Learns class Admin</strong>
                </p>
            </div>
        </body>
        </html>";
        
        return self::sendMail($adminEmail, $subject, $message);
    }
}
