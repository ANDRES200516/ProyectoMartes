<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Learns class</title>

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
        .circulo:nth-child(3){ width:60px; height:60px; left:50%; animation-duration:15s; }
        .circulo:nth-child(4){ width:150px; height:150px; left:70%; animation-duration:30s; }
        .circulo:nth-child(5){ width:90px; height:90px; left:85%; animation-duration:22s; }

        @keyframes flotar{
            0%{ transform:translateY(0) rotate(0deg); opacity:0; }
            10%{ opacity:1; }
            100%{ transform:translateY(-120vh) rotate(360deg); opacity:0; }
        }

        /* LOGIN */
        .login-container{
            width:400px;
            padding:45px;
            border-radius:25px;
            background:rgba(255,255,255,0.06);
            backdrop-filter:blur(15px);
            border:1px solid rgba(255,255,255,0.1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.37);
            animation:aparecer 1s ease;
            z-index:10;
        }

        @keyframes aparecer{
            from{ opacity:0; transform:translateY(-40px); }
            to{ opacity:1; transform:translateY(0); }
        }

        .login-container h1{
            text-align:center;
            color:white;
            margin-bottom:35px;
            font-size:42px;
        }

        .error-message {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ff8a96;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .input-box{
            position:relative;
            margin-bottom:25px;
        }

        .input-box input{
            width:100%;
            padding:16px 50px;
            border:none;
            outline:none;
            border-radius:14px;
            background:rgba(255,255,255,0.07);
            color:white;
            font-size:16px;
            transition:0.3s;
            border:2px solid transparent;
        }

        .input-box input::placeholder{ color:#cfcfcf; }

        .input-box input:focus{
            border-color:#7c4dff;
            box-shadow: 0 0 15px rgba(124,77,255,0.6);
            transform:scale(1.02);
        }

        .input-box i{
            position:absolute;
            top:18px;
            color:#bdbdbd;
            font-size:18px;
        }

        .input-box .fa-user, .input-box .fa-lock{ left:18px; }
        .toggle-password{ right:18px; cursor:pointer; }

        .remember{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:25px;
            color:white;
            font-size:14px;
        }

        .remember a{ color:#9c7dff; text-decoration:none; }
        .remember a:hover{ text-decoration:underline; }

        .btn-login{
            width:100%;
            padding:16px;
            border:none;
            border-radius:14px;
            background:linear-gradient(90deg,#4e8cff,#8f5cff);
            color:white;
            font-size:17px;
            font-weight:600;
            cursor:pointer;
            transition:0.3s;
        }

        .btn-login:hover{
            transform:translateY(-4px);
            box-shadow: 0 10px 20px rgba(124,77,255,0.5);
        }

        .register{
            text-align:center;
            margin-top:25px;
            color:white;
        }

        .register a{
            color:#9c7dff;
            text-decoration:none;
            font-weight:600;
        }

        .register a:hover{ text-decoration:underline; }

        .social-login{ margin-top:30px; }
        .social-login p{ color:white; text-align:center; margin-bottom:20px; }

        .social-icons{
            display:flex;
            justify-content:center;
            gap:20px;
        }

        .social-icons a{
            width:50px;
            height:50px;
            border-radius:50%;
            display:flex;
            justify-content:center;
            align-items:center;
            background:rgba(255,255,255,0.08);
            color:white;
            font-size:20px;
            transition:0.3s;
        }

        .social-icons a:hover{
            transform:translateY(-5px) scale(1.1);
            background:linear-gradient(90deg,#4e8cff,#8f5cff);
        }

        @media(max-width:500px){
            .login-container{ width:90%; padding:35px 25px; }
            .login-container h1{ font-size:32px; }
        }
    </style>
</head>
<body>

    <!-- PARTICULAS -->
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>

    <!-- LOGIN -->
    <div class="login-container">
        <a href="index.php" class="back-link" style="display: inline-block; color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.9rem; margin-bottom: 1.5rem; transition: 0.3s;">
            <i class="fa fa-arrow-left"></i> Volver al Inicio
        </a>
        <h1>Inicia Sesión</h1>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'pending'): ?>
            <div class="success-message" style="background: rgba(40, 167, 69, 0.2); border: 1px solid rgba(40, 167, 69, 0.5); color: #8aff96; padding: 10px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-size: 14px;">
                Registro exitoso. Tu cuenta ha sido vinculada pero está pendiente de aprobación por el administrador.
            </div>
        <?php endif; ?>

        <form action="index.php?action=login" method="POST">
            <div class="input-box">
                <i class="fa fa-user"></i>
                <input type="text" name="username" placeholder="Usuario" required autocomplete="username">
            </div>

            <div class="input-box">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Contraseña" required autocomplete="current-password">
                <i class="fa fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <div class="remember">
                <label><input type="checkbox"> Recordarme</label>
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>

            <button class="btn-login" type="submit">
                Ingresar
            </button>
        </form>

        <div class="social-login">
            <p>O inicia sesión con</p>
            <div class="social-icons">
                <a href="index.php?action=social_login&provider=google" title="Ingresar con Google"><i class="fab fa-google"></i></a>
                <a href="index.php?action=github_login" title="Ingresar con GitHub Real"><i class="fab fa-github"></i></a>
            </div>
        </div>

        <div class="register">
            ¿No tienes cuenta?
            <a href="index.php?action=register">Registrarse</a>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            if(password.type === 'password'){
                password.type = 'text';
                togglePassword.classList.remove('fa-eye');
                togglePassword.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                togglePassword.classList.remove('fa-eye-slash');
                togglePassword.classList.add('fa-eye');
            }
        });

        // EFECTO BOTON LOGIN
        const form = document.querySelector('form');
        const btn = document.querySelector('.btn-login');

        form.addEventListener('submit', (e) => {
            // Permitimos el envío real, pero cambiamos visualmente el botón
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Ingresando...';
            btn.style.pointerEvents = 'none';
        });
    </script>
    <?php require __DIR__ . '/../partials/alerts.php'; ?>
</body>
</html>
