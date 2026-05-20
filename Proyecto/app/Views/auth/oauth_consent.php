<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorizar Acceso - Learns class</title>
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
        body{
            height:100vh; display:flex; justify-content:center; align-items:center; overflow:hidden;
            background: linear-gradient(-45deg,#020024,#090979,#000428,#1f1c2c);
            background-size:400% 400%; animation:fondo 15s ease infinite; position:relative;
        }
        @keyframes fondo{ 0%{ background-position:0% 50%; } 50%{ background-position:100% 50%; } 100%{ background-position:0% 50%; } }
        
        .circulo{ position:absolute; border-radius:50%; background:rgba(255,255,255,0.05); animation:flotar linear infinite; bottom:-150px; }
        .circulo:nth-child(1){ width:80px; height:80px; left:10%; animation-duration:18s; }
        .circulo:nth-child(2){ width:120px; height:120px; left:25%; animation-duration:25s; }
        .circulo:nth-child(3){ width:60px; height:60px; left:50%; animation-duration:15s; }
        .circulo:nth-child(4){ width:150px; height:150px; left:70%; animation-duration:30s; }
        .circulo:nth-child(5){ width:90px; height:90px; left:85%; animation-duration:22s; }
        @keyframes flotar{ 0%{ transform:translateY(0) rotate(0deg); opacity:0; } 10%{ opacity:1; } 100%{ transform:translateY(-120vh) rotate(360deg); opacity:0; } }

        .consent-container{
            width:450px; padding:45px; border-radius:25px; background:rgba(255,255,255,0.06);
            backdrop-filter:blur(15px); border:1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.37); animation:aparecer 1s ease; z-index:10; text-align:center; color:white;
        }
        @keyframes aparecer{ from{ opacity:0; transform:translateY(-40px); } to{ opacity:1; transform:translateY(0); } }

        .provider-icon {
            font-size: 50px; margin-bottom: 20px;
            color: #fff;
            width: 80px; height: 80px; line-height: 80px;
            background: rgba(255,255,255,0.1); border-radius: 50%;
            display: inline-block;
        }

        .consent-container h2 { font-size: 24px; margin-bottom: 15px; }
        .consent-container p { font-size: 14px; color: #cfcfcf; margin-bottom: 30px; line-height: 1.6; }

        .btn-group { display: flex; gap: 15px; }
        .btn {
            flex: 1; padding: 14px; border: none; border-radius: 14px; font-size: 15px; font-weight: 600; cursor: pointer; transition: 0.3s;
        }
        .btn-allow {
            background: linear-gradient(90deg,#4e8cff,#8f5cff); color: white; text-decoration: none; display: inline-block;
        }
        .btn-allow:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(124,77,255,0.4); }
        .btn-cancel {
            background: rgba(255,255,255,0.1); color: white; text-decoration: none; display: inline-block;
        }
        .btn-cancel:hover { background: rgba(255,255,255,0.2); }
    </style>
</head>
<body>
    <div class="circulo"></div><div class="circulo"></div><div class="circulo"></div><div class="circulo"></div><div class="circulo"></div>

    <div class="consent-container">
        <?php 
            $providerIcons = [
                'google' => 'fab fa-google',
                'facebook' => 'fab fa-facebook-f',
                'github' => 'fab fa-github'
            ];
            $icon = $providerIcons[$provider] ?? 'fas fa-link';
            $providerName = ucfirst(htmlspecialchars($provider));
        ?>
        <i class="<?php echo $icon; ?> provider-icon"></i>
        <h2>Autorización Requerida</h2>
        <p><b>Learns class</b> solicita acceso a tu cuenta de <b><?php echo $providerName; ?></b> para obtener tu nombre y correo electrónico público. ¿Deseas continuar?</p>
        
        <div class="btn-group">
            <a href="index.php?action=login" class="btn btn-cancel">Cancelar</a>
            <a href="index.php?action=oauth_callback&provider=<?php echo urlencode($provider); ?>" class="btn btn-allow">Permitir Acceso</a>
        </div>
    </div>
</body>
</html>
