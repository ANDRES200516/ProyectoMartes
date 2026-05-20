<?php
// config/google.php

// ATENCIÓN: DEBES LLENAR ESTOS DATOS CON TUS CREDENCIALES DE GOOGLE CLOUD CONSOLE
// 1. Ve a https://console.cloud.google.com/
// 2. Crea un proyecto y ve a "APIs & Services" > "Credentials"
// 3. Crea una credencial "OAuth client ID" (Tipo: Web application)
// 4. En "Authorized redirect URIs" añade: http://localhost:8000/index.php?action=google_callback

define('GOOGLE_CLIENT_ID', 'TU_CLIENT_ID_AQUI.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'TU_CLIENT_SECRET_AQUI');

// Si tu puerto o dominio es distinto, asegúrate de actualizar esta URL y también en Google Cloud Console.
define('GOOGLE_REDIRECT_URI', 'http://localhost:8000/index.php?action=google_callback');
?>
