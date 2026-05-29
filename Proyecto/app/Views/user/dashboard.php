<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel de Aprendizaje - Learns class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Hero Welcome Banner */
        .premium-hero {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.95) 100%), 
                        url('assets/images/hero-bg.jpg') no-repeat center center;
            background-size: cover;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 35px;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .premium-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(56,189,248,0.08) 0%, transparent 60%);
            pointer-events: none;
        }
        .hero-welcome {
            z-index: 2;
        }
        .hero-welcome h2 {
            font-size: 2rem;
            color: #f8fafc;
            margin: 0 0 10px 0;
            font-weight: 700;
        }
        .hero-welcome p {
            color: #94a3b8;
            margin: 0;
            font-size: 1.1rem;
            max-width: 500px;
            line-height: 1.6;
        }
        .hero-stats {
            display: flex;
            gap: 20px;
            z-index: 2;
        }
        .hero-stat-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 15px 25px;
            text-align: center;
            min-width: 100px;
            backdrop-filter: blur(8px);
        }
        .hero-stat-card .num {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-color);
            display: block;
            line-height: 1.1;
        }
        .hero-stat-card .label {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Section titles styling */
        .section-header {
            margin: 30px 0 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-header h3 {
            font-size: 1.4rem;
            color: #f1f5f9;
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Progress Card updates */
        .my-course-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }
        .my-course-card:hover {
            transform: translateY(-4px);
            border-color: rgba(56, 189, 248, 0.3);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .my-course-thumb {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-bottom: 1px solid var(--border-color);
        }
        .my-course-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .my-course-body h4 {
            margin: 0 0 10px 0;
            font-size: 1.15rem;
            color: #f8fafc;
            font-weight: 600;
        }
        .my-course-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 15px;
        }
        
        .progress-container {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
            margin-bottom: 6px;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), #10b981);
            border-radius: 10px;
        }

        /* Search input aesthetics */
        .filter-panel {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Certificate Badge */
        .btn-certificate {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            color: #0f172a !important;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-certificate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=dashboard" class="logo">Learns class</a>
        <div class="links">
            <span style="font-weight: 500;"><i class="fa-regular fa-user" style="margin-right: 5px; color: var(--primary-color);"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            
            <!-- Campana de Notificaciones -->
            <div class="notification-bell" id="bellBtn">
                <i class="fa-solid fa-bell"></i>
                <?php if (count($notifications) > 0): ?>
                    <span class="bell-badge"><?php echo count($notifications); ?></span>
                <?php endif; ?>

                <!-- Menu Desplegable -->
                <div class="notification-dropdown" id="bellDropdown" style="right: 0;">
                    <div class="dropdown-header">
                        Notificaciones
                        <?php if (count($notifications) > 0): ?>
                            <button onclick="markAllAsRead(event)" class="mark-all-read-btn">Marcar como leídas</button>
                        <?php endif; ?>
                    </div>
                    <div class="dropdown-body" id="notificationsContainer">
                        <?php if (count($notifications) > 0): ?>
                            <?php foreach($notifications as $n): ?>
                                <a href="<?php echo $n['link'] ? htmlspecialchars($n['link']) : '#'; ?>" class="dropdown-item notif-<?php echo $n['type']; ?>">
                                    <div class="item-info">
                                        <strong><?php echo $n['type'] === 'success' ? '🏆 Logro obtenido' : '🔔 Aviso del sistema'; ?></strong>
                                        <span><?php echo htmlspecialchars($n['message']); ?></span>
                                        <small style="opacity:0.6; font-size:0.7rem; margin-top:2px; display:block;"><?php echo htmlspecialchars($n['created_at']); ?></small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="dropdown-empty">No tienes notificaciones pendientes</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <a href="index.php?action=profile">Mi Perfil</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>
    
    <div class="container">
        
        <?php 
            $enrolledCount = count($userEnrollments);
            $completedCount = count(array_filter($userEnrollments, function($e) {
                return $e['status'] === 'completed';
            }));
        ?>

        <!-- Hero Premium -->
        <div class="premium-hero">
            <div class="hero-welcome">
                <h2>¡Hola de nuevo, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
                <p>Continúa expandiendo tu mente científica hoy. Cada lección completada te acerca un paso más al dominio de tus habilidades.</p>
            </div>
            <div class="hero-stats">
                <div class="hero-stat-card">
                    <span class="num" style="color: #fbbf24;"><?php echo number_format($userXp); ?></span>
                    <span class="label">XP Totales</span>
                </div>
                <div class="hero-stat-card">
                    <span class="num" style="color: #f97316;"><i class="fa-solid fa-fire" style="font-size:1.5rem;"></i> <?php echo $userStreak['current_streak'] ?? 0; ?></span>
                    <span class="label">Días de Racha</span>
                </div>
                <div class="hero-stat-card">
                    <span class="num" style="color: #38bdf8;">#<?php echo $userRank; ?></span>
                    <span class="label">Ranking</span>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: MIS CURSOS EN CURSADA -->
        <?php if ($enrolledCount > 0): ?>
            <div class="section-header">
                <h3><i class="fa-solid fa-graduation-cap" style="color: var(--primary-color);"></i> Mis Cursos en Cursada</h3>
            </div>
            <div class="grid" style="margin-bottom: 40px; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                <?php foreach($userEnrollments as $e): ?>
                    <div class="my-course-card">
                        <img src="<?php echo htmlspecialchars(\App\Models\Course::resolveThumbnail(['thumbnail' => $e['course_thumbnail'], 'slug' => $e['course_slug']])); ?>" alt="Portada" class="my-course-thumb" onerror="this.src='assets/images/courses/default-thumb.svg'">
                        <div class="my-course-body">
                            <span class="tag tag-blue" style="width:fit-content; margin-bottom: 10px; font-size:0.75rem;"><?php echo htmlspecialchars($e['course_level']); ?></span>
                            <h4><?php echo htmlspecialchars($e['course_title']); ?></h4>
                            
                            <div style="flex-grow:1;"></div>
                            
                            <!-- Progreso -->
                            <div class="progress-container">
                                <div class="progress-bar-fill" style="width: <?php echo $e['progress_percentage']; ?>%;"></div>
                            </div>
                            <div class="my-course-meta">
                                <span><?php echo $e['status'] === 'completed' ? 'Completado' : 'Progreso'; ?></span>
                                <span style="font-weight: bold; color: #f8fafc;"><?php echo number_format($e['progress_percentage'], 0); ?>%</span>
                            </div>

                            <div style="display: flex; gap: 10px; margin-top: 10px;">
                                <?php if ($e['status'] === 'completed'): ?>
                                    <a href="index.php?action=learn&course=<?php echo $e['course_slug']; ?>" class="btn btn-secondary" style="flex-grow: 1;"><i class="fa-solid fa-rotate-left"></i> Repasar</a>
                                    <a href="index.php?action=certificate&code=<?php 
                                        // Retrieve certificate code if completed
                                        $database = new \Config\Database();
                                        $conn = $database->getConnection();
                                        $stmtCert = $conn->prepare("SELECT code FROM certificates WHERE user_id = :uid AND course_id = :cid LIMIT 1");
                                        $stmtCert->execute(['uid' => $_SESSION['user_id'], 'cid' => $e['course_id']]);
                                        $certRow = $stmtCert->fetch(\PDO::FETCH_ASSOC);
                                        echo $certRow ? $certRow['code'] : '';
                                    ?>" class="btn btn-certificate" target="_blank"><i class="fa-solid fa-award"></i> Certificado</a>
                                <?php else: ?>
                                    <a href="index.php?action=learn&course=<?php echo $e['course_slug']; ?>" class="btn" style="flex-grow: 1; text-align: center;">Continuar Lección <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- SECCIÓN: CATÁLOGO DE CURSOS -->
        <div class="section-header">
            <h3><i class="fa-solid fa-compass" style="color: #10b981;"></i> Catálogo de Cursos</h3>
        </div>

        <div class="filter-panel">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" placeholder="Buscar por título, categoría o tecnología..." onkeyup="filterCourses()">
            </div>
            <div>
                <select id="levelFilter" onchange="filterCourses()" style="padding: 10px; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #f1f5f9; border-radius: 8px; outline: none; cursor:pointer;">
                    <option value="">Todos los niveles</option>
                    <option value="Básico">Básico</option>
                    <option value="Intermedio">Intermedio</option>
                    <option value="Avanzado">Avanzado</option>
                </select>
            </div>
        </div>

        <div class="grid" id="coursesGrid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
            <?php 
            // Filter out already enrolled courses for the main catalog list
            $catalogCourses = array_filter($courses, function($c) use ($userEnrollments) {
                return !isset($userEnrollments[$c['id']]);
            });

            if (empty($catalogCourses)): 
            ?>
                <div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px;">
                    <i class="fa-solid fa-book-open fa-3x" style="margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>Has completado o te has inscrito en todos nuestros cursos disponibles. ¡Buen trabajo!</p>
                </div>
            <?php else: ?>
                <?php foreach($catalogCourses as $c): ?>
                    <div class="my-course-card catalog-card" data-title="<?php echo strtolower(htmlspecialchars($c['title'] . ' ' . ($c['category'] ?? ''))); ?>" data-level="<?php echo htmlspecialchars($c['level']); ?>">
                        <img src="<?php echo htmlspecialchars(\App\Models\Course::resolveThumbnail($c)); ?>" alt="Portada" class="my-course-thumb" onerror="this.src='assets/images/courses/default-thumb.svg'">
                        <div class="my-course-body">
                            <div style="display:flex; justify-content:space-between; margin-bottom: 10px; align-items: center;">
                                <span class="tag tag-blue" style="font-size:0.75rem;"><?php echo htmlspecialchars($c['level']); ?></span>
                                <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="fa-solid fa-clock"></i> <?php echo htmlspecialchars($c['duration_hours']); ?>h</span>
                            </div>
                            <h4 style="font-size:1.1rem; min-height: 48px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"><?php echo htmlspecialchars($c['title']); ?></h4>
                            <p style="font-size:0.85rem; color: var(--text-muted); min-height: 54px; line-height: 1.5; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;"><?php echo htmlspecialchars($c['short_description']); ?></p>
                            
                            <div style="flex-grow:1; margin-bottom: 15px;">
                                <div style="display:flex; align-items:center; gap:5px; color:#fbbf24; font-size:0.85rem;">
                                    <i class="fa-solid fa-star"></i>
                                    <span style="font-weight:bold; color:#e2e8f0;"><?php echo number_format($c['rating_avg'], 1); ?></span>
                                    <span style="color:var(--text-muted); font-size:0.8rem;">(<?php echo $c['rating_count']; ?> valoraciones)</span>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; border-top:1px solid rgba(255,255,255,0.05); padding-top: 15px;">
                                <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="fa-solid fa-layer-group"></i> <?php echo $c['modules_count']; ?> módulos</span>
                                <a href="index.php?action=course_details&course=<?php echo $c['slug']; ?>" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.85rem;">Ver Curso</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <script>
        // Toggle Dropdown de Notificaciones
        document.getElementById('bellBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('bellDropdown').classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('bellDropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // AJAX: Marcar notificaciones como leídas
        function markAllAsRead(e) {
            e.preventDefault();
            e.stopPropagation();

            fetch('index.php?action=mark_all_notifications_read')
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        const badge = document.querySelector('.bell-badge');
                        if (badge) badge.remove();
                        
                        document.getElementById('notificationsContainer').innerHTML = 
                            '<div class="dropdown-empty">No tienes notificaciones pendientes</div>';
                        
                        const markBtn = document.querySelector('.mark-all-read-btn');
                        if (markBtn) markBtn.remove();

                        Toast.fire({
                            icon: 'success',
                            title: 'Notificaciones leídas'
                        });
                    }
                });
        }

        // Búsqueda Dinámica
        function filterCourses() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const level = document.getElementById('levelFilter').value;
            const cards = document.querySelectorAll('.catalog-card');

            cards.forEach(card => {
                const title = card.getAttribute('data-title');
                const cardLevel = card.getAttribute('data-level');
                
                const matchesSearch = title.includes(search);
                const matchesLevel = level === '' || cardLevel === level;

                if (matchesSearch && matchesLevel) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
