<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Registro - Learns class</title>
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

        .login-container{
            width:420px; padding:45px; border-radius:25px; background:rgba(255,255,255,0.06);
            backdrop-filter:blur(15px); border:1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.37); animation:aparecer 1s ease; z-index:10;
        }
        @keyframes aparecer{ from{ opacity:0; transform:translateY(-40px); } to{ opacity:1; transform:translateY(0); } }

        .login-container h1{ text-align:center; color:white; margin-bottom:15px; font-size:32px; }
        .login-container p.subtitle { text-align:center; color:#cfcfcf; margin-bottom:30px; font-size:14px; }

        .error-message {
            background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.5); color: #ff8a96;
            padding: 10px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-size: 14px;
        }

        .input-box{ position:relative; margin-bottom:25px; }
        .input-box input{
            width:100%; padding:16px 50px; border:none; outline:none; border-radius:14px;
            background:rgba(255,255,255,0.07); color:white; font-size:16px; transition:0.3s; border:2px solid transparent;
        }
        .input-box input[readonly] { background:rgba(255,255,255,0.03); color:#aaa; cursor:not-allowed; }
        .input-box input::placeholder{ color:#cfcfcf; }
        .input-box input:not([readonly]):focus{ border-color:#7c4dff; box-shadow: 0 0 15px rgba(124,77,255,0.6); transform:scale(1.02); }
        .input-box i{ position:absolute; top:18px; color:#bdbdbd; font-size:18px; left:18px; }

        .btn-login{
            width:100%; padding:16px; border:none; border-radius:14px;
            background:linear-gradient(90deg,#4e8cff,#8f5cff); color:white; font-size:17px; font-weight:600; cursor:pointer; transition:0.3s;
        }
        .btn-login:hover{ transform:translateY(-4px); box-shadow: 0 10px 20px rgba(124,77,255,0.5); }
    </style>
</head>
<body>
    <div class="circulo"></div><div class="circulo"></div><div class="circulo"></div><div class="circulo"></div><div class="circulo"></div>

    <div class="login-container">
        <h1>Casi Listo</h1>
        <p class="subtitle">Solo falta crear tu usuario local para terminar tu vinculación con <b><?php echo ucfirst(htmlspecialchars($_SESSION['oauth_provider'] ?? 'Social')); ?></b>.</p>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=complete_social_profile" method="POST">
            
            <div class="input-box">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['oauth_email'] ?? ''); ?>" required>
            </div>

            <div class="input-box">
                <i class="fa fa-user"></i>
                <input type="text" name="username" placeholder="Elige un Nombre de Usuario" required autocomplete="username">
            </div>

            <div class="input-box">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Establece una Contraseña" required>
            </div>

            <button class="btn-login" type="submit">
                Finalizar Registro
            </button>
        </form>
    </div>
    <?php require __DIR__ . '/../partials/alerts.php'; ?>
</body>
</html>
