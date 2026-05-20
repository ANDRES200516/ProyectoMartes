    <style>
        :root {
            --primary: #4e54c8;
            --secondary: #8f94fb;
            --accent: #00d2ff;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text: #ffffff;
            --text-muted: #b0b0b0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background: #020024;
            color: var(--text);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* BACKGROUND GRADIENT ANIMATION */
        .bg-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, #1f1c2c 0%, #020024 100%);
            z-index: -2;
        }

        .bg-blob {
            position: fixed;
            width: 500px;
            height: 500px;
            background: var(--primary);
            filter: blur(150px);
            border-radius: 50%;
            opacity: 0.2;
            z-index: -1;
            animation: blobMove 20s infinite alternate;
        }

        @keyframes blobMove {
            0% { transform: translate(-10%, -10%); }
            100% { transform: translate(60%, 40%); }
        }

        /* NAVBAR */
        nav {
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: 0.4s;
        }

        nav.scrolled {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(15px);
            padding: 1rem 5%;
            border-bottom: 1px solid var(--glass-border);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(to right, var(--accent), #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-links a {
            color: var(--text);
            text-decoration: none;
            margin-left: 2rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: 0.3s;
            opacity: 0.8;
        }

        .nav-links a:hover {
            color: var(--accent);
            opacity: 1;
        }

        /* HERO SECTION */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 10%;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to bottom, #fff 40%, #888);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
            max-width: 700px;
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
        }

        /* BUTTONS */
        .btn-primary {
            background: #fff;
            color: #000;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-block;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(255,255,255,0.3);
        }

        .btn-outline {
            border: 1px solid var(--glass-border);
            color: #fff;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            margin-left: 1rem;
            transition: 0.3s;
            backdrop-filter: blur(5px);
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: #fff;
        }

        /* REVEAL ON SCROLL */
        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: 1s cubic-bezier(0.17, 0.67, 0.83, 0.67);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* COMPARISON */
        .comparison {
            padding: 150px 10%;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .card {
            background: #111 !important; /* Negro sólido para máximo contraste */
            padding: 3rem 2rem;
            border-radius: 20px;
            border: 2px solid #444 !important; /* Borde visible */
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .card h3 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #ffffff !important;
            font-weight: 800;
        }

        .feature-list li {
            margin-bottom: 1.2rem;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #ffffff !important;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .feature-list i {
            color: var(--accent) !important;
            min-width: 25px;
        }

        .feature-list li:hover {
            color: #fff;
            transform: translateX(10px);
        }

        .feature-list i {
            font-size: 1.4rem;
            color: var(--accent);
        }

        /* PARTICLES */
        #canvas-particles {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
        }

        footer {
            padding: 80px 10%;
            text-align: center;
            background: rgba(0,0,0,0.4);
            border-top: 1px solid var(--glass-border);
        }

        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
            .hero h1 { font-size: 3rem; }
        }
    </style>
</head>
<body>

    <div class="bg-gradient"></div>
    <div class="bg-blob"></div>
    <canvas id="canvas-particles"></canvas>

    <nav id="navbar">
        <a href="#" class="logo">Learns class.</a>
        <div class="nav-links">
            <a href="#ventajas">Beneficios</a>
            <a href="index.php?action=login">Log In</a>
            <a href="index.php?action=register" class="btn-primary" style="padding: 0.6rem 1.8rem; font-size: 0.85rem;">Empezar</a>
        </div>
    </nav>

    <section class="hero">
        <h1 class="reveal">Domina la IA. <br> <span style="color: var(--accent);">Sin Límites.</span></h1>
        <p class="reveal">La plataforma de aprendizaje diseñada para ingenieros que no se conforman con lo básico. Algoritmos, lógica y futuro en un solo lugar.</p>
        <div class="cta-buttons reveal">
            <a href="index.php?action=register" class="btn-primary">Crear Cuenta Gratis</a>
            <a href="#ventajas" class="btn-outline">Saber Más</a>
        </div>
    </section>

    <section class="comparison" id="ventajas">
        <div class="grid">
            <div class="card advantages">
                <h3>Por qué Learns class</h3>
                <ul class="feature-list">
                    <li><i class="fas fa-bolt"></i> <span><strong>Procesamiento Real:</strong> Simuladores de lógica neuronal interactivos.</span></li>
                    <li><i class="fas fa-fingerprint"></i> <span><strong>Rutas Únicas:</strong> El algoritmo adapta el contenido a tu progreso.</span></li>
                    <li><i class="fas fa-globe"></i> <span><strong>Global:</strong> Certificaciones válidas en cualquier lugar del mundo.</span></li>
                </ul>
            </div>
            <div class="card disadvantages">
                <h3>El Desafío</h3>
                <ul class="feature-list">
                    <li><i class="fas fa-brain"></i> <span><strong>Esfuerzo Mental:</strong> No vendemos atajos, vendemos conocimiento profundo.</span></li>
                    <li><i class="fas fa-clock"></i> <span><strong>Tiempo:</strong> Requiere una inversión real de tu disciplina diaria.</span></li>
                    <li><i class="fas fa-code"></i> <span><strong>Código:</strong> Estarás frente a frente con la lógica pura de la IA.</span></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- NUEVA SECCIÓN: CURSOS DETALLADOS -->
    <section class="courses-preview" style="padding: 100px 10%; text-align: center;">
        <h2 class="reveal" style="font-size: 3rem; margin-bottom: 3rem;">Explora el Conocimiento</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <div class="card reveal">
                <i class="fas fa-brain" style="font-size: 3rem; color: var(--accent); margin-bottom: 1.5rem;"></i>
                <h4>Inteligencia Artificial</h4>
                <p style="color: var(--text-muted); margin-top: 1rem;">Desde redes neuronales hasta el procesamiento del lenguaje natural. Entiende cómo piensan las máquinas y cómo construir el futuro.</p>
            </div>
            <div class="card reveal">
                <i class="fas fa-dna" style="font-size: 3rem; color: #ff4e50; margin-bottom: 1.5rem;"></i>
                <h4>Algoritmos Genéticos</h4>
                <p style="color: var(--text-muted); margin-top: 1rem;">La evolución aplicada al código. Aprende a resolver problemas complejos imitando la selección natural y la genética.</p>
            </div>
            <div class="card reveal">
                <i class="fas fa-chart-line" style="font-size: 3rem; color: #00ff88; margin-bottom: 1.5rem;"></i>
                <h4>Regresión Lineal</h4>
                <p style="color: var(--text-muted); margin-top: 1rem;">La base de la predicción de datos. Domina los fundamentos matemáticos que permiten anticipar tendencias y comportamientos.</p>
            </div>
        </div>
    </section>

    <!-- NUEVA SECCIÓN: METODOLOGÍA -->
    <section class="methodology" style="padding: 100px 10%; background: rgba(255,255,255,0.02);">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h2 class="reveal" style="font-size: 2.5rem; margin-bottom: 2rem;">Nuestra Metodología</h2>
            <p class="reveal" style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.8; margin-bottom: 2rem;">
                En <strong>Learns class</strong>, no creemos en el aprendizaje pasivo. Nuestra plataforma utiliza un sistema de <strong>Aprendizaje Activo Basado en Retos</strong>. Cada módulo está diseñado para que implementes lo aprendido de inmediato en nuestro entorno virtual.
            </p>
            <div class="reveal" style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap;">
                <div style="text-align: center;">
                    <span style="font-size: 2.5rem; font-weight: 800; color: #fff;">100%</span>
                    <p style="font-size: 0.9rem; color: var(--accent);">Práctico</p>
                </div>
                <div style="text-align: center;">
                    <span style="font-size: 2.5rem; font-weight: 800; color: #fff;">24/7</span>
                    <p style="font-size: 0.9rem; color: var(--accent);">Acceso</p>
                </div>
                <div style="text-align: center;">
                    <span style="font-size: 2.5rem; font-weight: 800; color: #fff;">TOP</span>
                    <p style="font-size: 0.9rem; color: var(--accent);">Certificación</p>
                </div>
            </div>
        </div>
    </section>

    <!-- NUEVA SECCIÓN: PROPÓSITO Y CONTEXTO -->
    <section class="purpose" style="padding: 100px 10%; background: linear-gradient(to bottom, rgba(0,0,0,0.5), transparent);">
        <div class="grid" style="align-items: center;">
            <div class="reveal">
                <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">¿Para qué sirve Learns class?</h2>
                <p style="color: var(--text-muted); line-height: 1.8; font-size: 1.1rem; margin-bottom: 1.5rem;">
                    Nacimos con un propósito claro: <strong>cerrar la brecha entre la teoría académica y la implementación real</strong>. En el mundo actual, saber que existe la Inteligencia Artificial no es suficiente; necesitas saber cómo programarla, optimizarla y desplegarla.
                </p>
                <p style="color: var(--text-muted); line-height: 1.8; font-size: 1.1rem;">
                    <strong>Learns class</strong> es tu laboratorio de experimentación. Aquí no solo lees diapositivas; interactúas con algoritmos que evolucionan, redes que aprenden y modelos matemáticos que predicen el futuro. Es la herramienta definitiva para ingenieros, desarrolladores y mentes curiosas que buscan liderar la revolución tecnológica.
                </p>
            </div>
            <div class="reveal" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="card" style="padding: 1.5rem; text-align: center;">
                    <i class="fas fa-microchip" style="font-size: 2rem; color: var(--accent);"></i>
                    <h5 style="margin-top: 1rem;">Optimización</h5>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Aplica algoritmos genéticos para resolver problemas de logística y diseño.</p>
                </div>
                <div class="card" style="padding: 1.5rem; text-align: center;">
                    <i class="fas fa-project-diagram" style="font-size: 2rem; color: var(--accent);"></i>
                    <h5 style="margin-top: 1rem;">Predicción</h5>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Usa la regresión para anticipar tendencias de mercado y fallos de sistemas.</p>
                </div>
                <div class="card" style="padding: 1.5rem; text-align: center;">
                    <i class="fas fa-robot" style="font-size: 2rem; color: var(--accent);"></i>
                    <h5 style="margin-top: 1rem;">Automatización</h5>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Crea agentes inteligentes que tomen decisiones basadas en datos reales.</p>
                </div>
                <div class="card" style="padding: 1.5rem; text-align: center;">
                    <i class="fas fa-graduation-cap" style="font-size: 2rem; color: var(--accent);"></i>
                    <h5 style="margin-top: 1rem;">Carrera</h5>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Eleva tu perfil profesional con habilidades de alta demanda técnica.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- NUEVA SECCIÓN: MISIÓN Y VISIÓN -->
    <section class="mission-vision" style="padding: 100px 10%;">
        <div class="grid">
            <div class="reveal" style="background: var(--glass); padding: 4rem; border-radius: 30px; border: 1px solid var(--glass-border); text-align: left;">
                <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; color: #fff;">Nuestra Misión</h2>
                <p style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.8;">
                    Democratizar el acceso al conocimiento técnico avanzado, transformando la curiosidad en capacidad de ejecución, y preparando a la próxima generación de ingenieros para los retos del mañana a través de una metodología práctica y disruptiva.
                </p>
            </div>
            <div class="reveal" style="background: var(--glass); padding: 4rem; border-radius: 30px; border: 1px solid var(--glass-border); text-align: left;">
                <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem; color: var(--accent);">Nuestra Visión</h2>
                <p style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.8;">
                    Ser la plataforma líder en formación tecnológica avanzada en habla hispana, reconocida globalmente por formar expertos capaces de liderar la innovación en Inteligencia Artificial y computación evolutiva, impulsando el progreso de la sociedad digital.
                </p>
            </div>
        </div>
    </section>

    <footer>
        <p style="font-weight: 700; color: #fff; margin-bottom: 1rem;">Learns class LMS</p>
        <p>&copy; 2026 Reservados todos los derechos para mentes brillantes.</p>
    </footer>

    <script>
        // REVEAL ON SCROLL
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }
        window.addEventListener("scroll", reveal);
        reveal(); // Run on load

        // NAVBAR SCROLL EFFECT
        window.addEventListener("scroll", function() {
            var nav = document.getElementById("navbar");
            if (window.scrollY > 50) {
                nav.classList.add("scrolled");
            } else {
                nav.classList.remove("scrolled");
            }
        });

        // MOUSE EFFECT ON CARDS
        document.querySelectorAll('.card').forEach(card => {
            card.onmousemove = e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--x', `${x}px`);
                card.style.setProperty('--y', `${y}px`);
            }
        });

        // CANVAS PARTICLES
        const canvas = document.getElementById('canvas-particles');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.onresize = resize;
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2;
                this.speedX = Math.random() * 0.5 - 0.25;
                this.speedY = Math.random() * 0.5 - 0.25;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;
            }
            draw() {
                ctx.fillStyle = 'rgba(255,255,255,0.2)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        for (let i = 0; i < 100; i++) particles.push(new Particle());

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.update();
                p.draw();
            });
            requestAnimationFrame(animate);
        }
        animate();
    </script>
</body>
</html>

