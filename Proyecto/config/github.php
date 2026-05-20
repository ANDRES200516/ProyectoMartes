<?php
// config/github.php

// ATENCIÓN: DEBES LLENAR ESTOS DATOS CON TUS CREDENCIALES DE GITHUB
// 1. Ve a https://github.com/settings/developers
// 2. Haz clic en "OAuth Apps" y luego en "New OAuth App"
// 3. Application name: Learns class (o el que quieras)
// 4. Homepage URL: http://localhost:8000
// 5. Authorization callback URL: http://localhost:8000/index.php?action=github_callback
// 6. Haz clic en "Register application".
// 7. Copia el "Client ID" y genera un "Client Secret" haciendo clic en "Generate a new client secret".

define('GITHUB_CLIENT_ID', 'Ov23li9jyELtg1AwILa8');
define('GITHUB_CLIENT_SECRET', '6bf73930aab043f3c8a35754355db674273a7177ññ');

// Autodetectar el protocolo, host y puerto actual para evitar el error de redirección
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';

define('GITHUB_REDIRECT_URI', $protocol . '://' . $host . $script . '?action=github_callback');
?>
