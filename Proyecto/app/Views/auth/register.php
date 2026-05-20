<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Learns class</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            overflow:hidden;
            background: linear-gradient(-45deg,#020024,#090979,#000428,#1f1c2c);
            background-size:400% 400%;
            animation:fondo 15s ease infinite;
            position:relative;
        }

        @keyframes fondo{
            0%{ background-position:0% 50%; }
            50%{ background-position:100% 50%; }
            100%{ background-position:0% 50%; }
        }

        /* PARTICULAS */
        .circulo{
            position:absolute;
            border-radius:50%;
            background:rgba(255,255,255,0.05);
            animation:flotar linear infinite;
            bottom:-150px;
        }
        .circulo:nth-child(1){ width:80px; height:80px; left:10%; animation-duration:18s; }
        .circulo:nth-child(2){ width:120px; height:120px; left:25%; animation-duration:25s; }
        .circulo:nth-child(3){ width:50px; height:50px; left:45%; animation-duration:15s; }
        .circulo:nth-child(4){ width:90px; height:90px; left:70%; animation-duration:20s; }
        .circulo:nth-child(5){ width:60px; height:60px; left:85%; animation-duration:12s; }

        @keyframes flotar{
            from{ transform:translateY(0) rotate(0deg); opacity:1; }
            to{ transform:translateY(-120vh) rotate(360deg); opacity:0; }
        }

        .container{
            position:relative;
            width:400px;
            background:rgba(255,255,255,0.05);
            padding:40px;
            border-radius:24px;
            backdrop-filter:blur(15px);
            border:1px solid rgba(255,255,255,0.1);
            box-shadow:0 25px 45px rgba(0,0,0,0.2);
            z-index:10;
        }

        .container h2{
            color:white;
            text-align:center;
            font-size:2rem;
            margin-bottom:30px;
            font-weight:700;
            letter-spacing:1px;
        }

        .input-box{
            position:relative;
            margin-bottom:25px;
        }

        .input-box i{
            position:absolute;
            left:15px;
            top:50%;
            transform:translateY(-50%);
            color:rgba(255,255,255,0.5);
            font-size:18px;
        }

        .input-box input{
            width:100%;
            padding:14px 15px 14px 45px;
            background:rgba(255,255,255,0.05);
            border:1px solid rgba(255,255,255,0.1);
            border-radius:12px;
            color:white;
            outline:none;
            font-size:15px;
            transition:0.3s;
        }

        .input-box input:focus{
            border-color: #4e8cff;
            background:rgba(255,255,255,0.1);
        }

        .btn-register{
            width:100%;
            padding:15px;
            border:none;
            border-radius:12px;
            background:linear-gradient(90deg,#4e8cff,#8f5cff);
            color:white;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:0.3s;
            margin-bottom:20px;
        }

        .btn-register:hover{
            transform:translateY(-3px);
            box-shadow: 0 10px 20px rgba(78,140,255,0.3);
        }

        .login-link{
            text-align:center;
            color:rgba(255,255,255,0.7);
            font-size:14px;
        }

        .login-link a{
            color:#4e8cff;
            text-decoration:none;
            font-weight:600;
        }

        .login-link a:hover{
            text-decoration:underline;
        }

        .alert {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        .alert-danger { background: rgba(255, 78, 80, 0.2); color: #ff4e50; border: 1px solid rgba(255, 78, 80, 0.3); }
        .alert-success { background: rgba(0, 255, 136, 0.2); color: #00ff88; border: 1px solid rgba(0, 255, 136, 0.3); }

        /* BOTONES SOCIALES */
        .social-login{ margin-top:25px; }
        .social-login p{ color:white; text-align:center; margin-bottom:15px; font-size:14px; }

        .social-icons{
            display:flex;
            justify-content:center;
            gap:20px;
        }

        .social-icons a{
            width:45px;
            height:45px;
            border-radius:50%;
            display:flex;
            justify-content:center;
            align-items:center;
            background:rgba(255,255,255,0.08);
            color:white;
            font-size:18px;
            transition:0.3s;
            text-decoration:none;
        }

        .social-icons a:hover{
            transform:translateY(-5px) scale(1.1);
            background:linear-gradient(90deg,#4e8cff,#8f5cff);
        }
    </style>
</head>
<body>

    <!-- FONDO CON PARTICULAS -->
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>

    <div class="container">
        <a href="index.php" class="back-link" style="display: inline-block; color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.9rem; margin-bottom: 1rem; transition: 0.3s;">
            <i class="fa fa-arrow-left"></i> Volver al Inicio
        </a>
        <h2>Únete ahora</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="index.php?action=register" method="POST">
            <div class="input-box">
                <i class="fa fa-user"></i>
                <input type="text" name="username" placeholder="Nombre de usuario" required autocomplete="username">
            </div>

            <div class="input-box">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" placeholder="Correo electrónico" required autocomplete="email">
            </div>

            <div class="input-box">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Contraseña" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn-register">Crear cuenta</button>

            <div class="login-link">
                ¿Ya tienes cuenta? <a href="index.php?action=login">Inicia sesión</a>
            </div>
        </form>

        <div class="social-login">
            <p>O regístrate con</p>
            <div class="social-icons">
                <a href="index.php?action=social_login&provider=google" title="Registrarse con Google"><i class="fab fa-google"></i></a>
                <a href="index.php?action=github_login" title="Registrarse con GitHub"><i class="fab fa-github"></i></a>
            </div>
        </div>
        </div>
    </div>
    <?php require __DIR__ . '/../partials/alerts.php'; ?>
</body>
</html>
