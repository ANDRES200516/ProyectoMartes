<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Detalles del Curso</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 30px;
            margin-top: 30px;
        }
        @media (max-width: 900px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
            .sidebar-card {
                position: static !important;
            }
        }
        
        /* Hero Banner */
        .course-hero {
            background: linear-gradient(rgba(15, 23, 42, 0.8), #0f172a), 
                        url('<?php echo htmlspecialchars(\App\Models\Course::resolveBanner($course)); ?>') no-repeat center center;
            background-size: cover;
            border-bottom: 1px solid var(--border-color);
            padding: 50px 0;
            color: #f8fafc;
        }
        .course-hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .course-hero-content h1 {
            font-size: 2.2rem;
            margin: 15px 0;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Sidebar summary card */
        .sidebar-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4);
            position: sticky;
            top: 20px;
        }
        .sidebar-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 20px;
        }
        .sidebar-card-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            color: #e2e8f0;
        }
        .sidebar-card-info-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding-bottom: 8px;
        }
        .sidebar-card-info-item span:first-child {
            color: var(--text-muted);
        }

        /* Content panels */
        .details-panel {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .details-panel h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #f1f5f9;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding-bottom: 10px;
        }
        .checkmark-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 12px;
        }
        .checkmark-list li {
            position: relative;
            padding-left: 25px;
            color: #cbd5e1;
            line-height: 1.4;
        }
        .checkmark-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 0;
            color: #10b981;
            font-weight: bold;
        }

        /* Accordion curriculum */
        .curriculum-module {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .curriculum-module-header {
            background: rgba(255,255,255,0.02);
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            user-select: none;
        }
        .curriculum-module-header h4 {
            margin: 0;
            color: #e2e8f0;
            font-size: 1rem;
        }
        .curriculum-module-body {
            background: rgba(15,23,42,0.3);
            border-top: 1px solid var(--border-color);
            display: none;
            padding: 10px 20px;
        }
        .curriculum-lesson-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            font-size: 0.9rem;
        }
        .curriculum-lesson-row:last-child {
            border-bottom: none;
        }
        .curriculum-lesson-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #cbd5e1;
        }
        
        /* Review styling */
        .reviews-summary {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            padding: 15px;
            background: rgba(255,255,255,0.02);
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.03);
        }
        .reviews-rating-number {
            font-size: 3rem;
            font-weight: 800;
            color: #f8fafc;
        }
        .review-card {
            background: rgba(15,23,42,0.2);
            border: 1px solid rgba(255,255,255,0.03);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
        }
        .review-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .review-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .review-user-photo {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=dashboard" class="logo">Learns class</a>
        <div class="links">
            <a href="index.php?action=dashboard">Mis Cursos</a>
            <a href="index.php?action=profile">Mi Perfil</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>

    <!-- Hero del Curso -->
    <div class="course-hero">
        <div class="course-hero-content">
            <div style="display:flex; gap:10px;">
                <span class="tag tag-blue" style="font-size:0.8rem;"><?php echo htmlspecialchars($course['level']); ?></span>
                <span class="tag" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); color:#e2e8f0; font-size:0.8rem;"><?php echo htmlspecialchars($course['category'] ?? 'General'); ?></span>
            </div>
            <h1><?php echo htmlspecialchars($course['title']); ?></h1>
            <p style="font-size: 1.15rem; color: #cbd5e1; max-width: 800px; margin-bottom: 20px; line-height:1.5;"><?php echo htmlspecialchars($course['short_description']); ?></p>
            
            <div style="display:flex; align-items:center; gap:8px; color: #fbbf24; font-size:0.95rem;">
                <i class="fa-solid fa-star"></i>
                <span style="font-weight:bold; color:#f8fafc;"><?php echo number_format($course['rating_avg'], 1); ?></span>
                <span style="color:var(--text-muted);">(<?php echo $course['rating_count']; ?> valoraciones)</span>
                <span style="color:rgba(255,255,255,0.2); margin:0 5px;">|</span>
                <span style="color:#cbd5e1;"><i class="fa-solid fa-users"></i> <?php echo $course['students_count']; ?> estudiantes</span>
            </div>
        </div>
    </div>

    <div class="container" style="max-width: 1200px;">
        <div class="details-grid">
            
            <!-- CONTENIDO PRINCIPAL (Izquierda) -->
            <div>
                <!-- Descripción del Curso -->
                <div class="details-panel">
                    <h3>Acerca de este curso</h3>
                    <div style="color: #cbd5e1; line-height: 1.6; font-size: 0.95rem;">
                        <?php echo nl2br((string)$course['description']); ?>
                    </div>
                </div>

                <!-- Requisitos y Objetivos -->
                <?php if (!empty($course['objectives']) || !empty($course['requirements'])): ?>
                    <div class="details-panel" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                        <?php if (!empty($course['objectives'])): ?>
                            <div>
                                <h3 style="border-bottom:none; margin-bottom:10px;">Lo que aprenderás</h3>
                                <ul class="checkmark-list">
                                    <?php 
                                    $objectives = array_filter(explode('|', $course['objectives']));
                                    foreach ($objectives as $obj): 
                                    ?>
                                        <li><?php echo htmlspecialchars(trim($obj)); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($course['requirements'])): ?>
                            <div style="margin-top:15px; border-top:1px solid rgba(255,255,255,0.05); padding-top:15px;">
                                <h3 style="border-bottom:none; margin-bottom:10px;">Requisitos previos</h3>
                                <ul style="padding-left:20px; color:#cbd5e1; line-height:1.5;">
                                    <?php 
                                    $requirements = array_filter(explode('|', $course['requirements']));
                                    foreach ($requirements as $req): 
                                    ?>
                                        <li style="margin-bottom:8px;"><?php echo htmlspecialchars(trim($req)); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Temario de Clases -->
                <div class="details-panel">
                    <h3>Contenido del curso</h3>
                    <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-size:0.85rem; color:var(--text-muted);">
                        <span><?php echo count($modules); ?> módulos • <?php echo $course['total_lessons']; ?> lecciones</span>
                        <a href="javascript:void(0)" onclick="toggleAllModules()" style="color:var(--primary-color); text-decoration:none;">Expandir todos</a>
                    </div>

                    <?php foreach ($modules as $module): ?>
                        <div class="curriculum-module">
                            <div class="curriculum-module-header" onclick="toggleModule(this)">
                                <h4>Módulo <?php echo $module['sort_order']; ?>: <?php echo htmlspecialchars($module['title']); ?></h4>
                                <span style="font-size:0.8rem; color:var(--text-muted);"><i class="fa-solid fa-chevron-down"></i></span>
                            </div>
                            <div class="curriculum-module-body">
                                <?php 
                                $lessons = $lessonsByModule[$module['id']] ?? [];
                                if (count($lessons) > 0):
                                    foreach ($lessons as $lesson):
                                ?>
                                    <div class="curriculum-lesson-row">
                                        <div class="curriculum-lesson-info">
                                            <?php if ($lesson['video_type'] === 'youtube'): ?>
                                                <i class="fa-solid fa-circle-play" style="color: #f43f5e;"></i>
                                            <?php elseif ($lesson['pdf_url']): ?>
                                                <i class="fa-solid fa-file-pdf" style="color: #38bdf8;"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-file-lines" style="color: #10b981;"></i>
                                            <?php endif; ?>
                                            
                                            <span><?php echo htmlspecialchars($lesson['title']); ?></span>
                                            
                                            <?php if ($lesson['is_free']): ?>
                                                <span class="badge badge-approved" style="font-size: 0.65rem; padding: 1px 5px; margin-left: 5px;">Vista Previa Libre</span>
                                            <?php endif; ?>
                                        </div>
                                        <div style="display:flex; align-items:center; gap:15px;">
                                            <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="fa-solid fa-clock"></i> <?php echo $lesson['duration_minutes']; ?> min</span>
                                            
                                            <?php if ($isEnrolled): ?>
                                                <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>&lesson=<?php echo $lesson['id']; ?>" style="color: var(--primary-color); text-decoration:none;" title="Ver clase"><i class="fa-solid fa-arrow-right-to-bracket"></i></a>
                                            <?php else: ?>
                                                <?php if ($lesson['is_free']): ?>
                                                    <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>&lesson=<?php echo $lesson['id']; ?>" class="btn-small" style="background:#10b981; padding: 4px 8px; font-size: 0.75rem; text-decoration:none;"><i class="fa-solid fa-eye"></i> Previsualizar</a>
                                                <?php else: ?>
                                                    <i class="fa-solid fa-lock" style="color:var(--text-muted); opacity:0.6;" title="Inscríbete para desbloquear"></i>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php 
                                    endforeach;
                                else:
                                ?>
                                    <p style="color:var(--text-muted); font-size:0.85rem; text-align:center; padding:10px 0; margin:0;">No hay lecciones cargadas en este módulo.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Videos del Curso -->
                <div class="details-panel">
                    <h3>Videos en español</h3>
                    <div style="display:grid; gap:16px;">
                        <?php foreach ($courseVideos as $video): ?>
                            <div style="background: rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); border-radius:12px; padding:16px;">
                                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                                    <div>
                                        <strong style="color:#f8fafc; font-size:1rem;"><?php echo htmlspecialchars($video['title']); ?></strong>
                                        <p style="margin:6px 0 0 0; color:#cbd5e1; font-size:0.92rem; line-height:1.5;"><?php echo htmlspecialchars($video['description']); ?></p>
                                    </div>
                                    <a href="<?php echo htmlspecialchars($video['url']); ?>" target="_blank" rel="noreferrer" class="btn" style="padding:8px 14px; white-space:nowrap;">Ver video</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Quizzes por Módulo -->
                <div class="details-panel">
                    <h3>Quizzes por módulo</h3>
                    <?php foreach ($modules as $module): ?>
                        <?php $extra = $moduleExtras[$module['id']] ?? null; ?>
                        <?php if (!$extra) continue; ?>
                        <div style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:12px; padding:18px; margin-bottom:16px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                                <div>
                                    <h4 style="margin:0; color:#f8fafc;">Quiz: Módulo <?php echo $module['sort_order']; ?> - <?php echo htmlspecialchars($module['title']); ?></h4>
                                    <p style="margin:8px 0 0 0; color:#cbd5e1; font-size:0.92rem;">Tema: <?php echo htmlspecialchars($extra['topic']); ?></p>
                                </div>
                                <?php if ($extra['has_lab']): ?>
                                    <span class="badge badge-approved" style="font-size:0.75rem; padding: 4px 10px;">Laboratorio práctico final</span>
                                <?php endif; ?>
                            </div>
                            <ol style="margin:16px 0 0 0; padding-left:20px; color:#cbd5e1; line-height:1.7;">
                                <?php foreach ($extra['quiz_questions'] as $question): ?>
                                    <li style="margin-bottom:12px;"><strong><?php echo htmlspecialchars($question); ?></strong></li>
                                <?php endforeach; ?>
                            </ol>
                            <?php if ($extra['has_lab']): ?>
                                <div style="margin-top:12px; padding:16px; background: rgba(59,130,246,0.08); border-radius:10px; border:1px solid rgba(59,130,246,0.15); color:#e2e8f0;">
                                    <strong>Laboratorio práctico:</strong>
                                    <p style="margin:8px 0 0 0;"><?php echo htmlspecialchars($extra['lab_description']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Sección de Reseñas / Valoraciones -->
                <div class="details-panel">
                    <h3>Opiniones de los estudiantes</h3>
                    <div class="reviews-summary">
                        <div class="reviews-rating-number"><?php echo number_format($course['rating_avg'], 1); ?></div>
                        <div>
                            <div style="color: #fbbf24; font-size: 1.1rem; display:flex; gap:3px;">
                                <?php 
                                $avgStars = round($course['rating_avg']);
                                for($i=1; $i<=5; $i++) {
                                    echo $i <= $avgStars ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                }
                                ?>
                            </div>
                            <span style="font-size:0.85rem; color:var(--text-muted); margin-top:5px; display:block;">Promedio basado en <?php echo $course['rating_count']; ?> valoraciones</span>
                        </div>
                    </div>

                    <!-- Dejar reseña si está inscrito y no la ha dejado -->
                    <?php if ($isEnrolled && !$hasReviewed): ?>
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                            <h4 style="margin-top:0; color:#f8fafc; font-size:0.95rem; margin-bottom: 15px;">Escribe una reseña de este curso</h4>
                            <form action="index.php?action=save_review" method="POST">
                                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                
                                <div style="margin-bottom:12px;">
                                    <label style="display:block; font-size:0.85rem; color:#cbd5e1; margin-bottom:6px;">Calificación (Estrellas)</label>
                                    <select name="rating" required style="background:#0f172a; color:#f8fafc; border:1px solid var(--border-color); padding:8px; border-radius:6px; outline:none; cursor:pointer;">
                                        <option value="5">⭐⭐⭐⭐⭐ (Excelente)</option>
                                        <option value="4">⭐⭐⭐⭐ (Muy Bueno)</option>
                                        <option value="3">⭐⭐⭐ (Bueno)</option>
                                        <option value="2">⭐⭐ (Regular)</option>
                                        <option value="1">⭐ (Malo)</option>
                                    </select>
                                </div>

                                <div style="margin-bottom:15px;">
                                    <label style="display:block; font-size:0.85rem; color:#cbd5e1; margin-bottom:6px;">Comentario</label>
                                    <textarea name="comment" rows="3" required placeholder="Comparte tu experiencia de aprendizaje en el curso..." style="width:100%; background:#0f172a; color:#f8fafc; border:1px solid var(--border-color); padding:10px; border-radius:6px; outline:none; resize:vertical; box-sizing:border-box;"></textarea>
                                </div>

                                <button type="submit" class="btn" style="padding: 8px 16px; font-size:0.85rem;"><i class="fa-solid fa-paper-plane"></i> Enviar Reseña</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 15px;">
                        <?php if (count($reviews) > 0): ?>
                            <?php foreach ($reviews as $rev): ?>
                                <div class="review-card">
                                    <div class="review-card-header">
                                        <div class="review-user-info">
                                            <img src="<?php echo htmlspecialchars($rev['user_photo'] ?? 'assets/images/default-avatar.png'); ?>" alt="Avatar" class="review-user-photo" onerror="this.src='assets/images/default-avatar.png'">
                                            <div>
                                                <strong style="font-size:0.85rem; color:#f1f5f9;"><?php echo htmlspecialchars($rev['user_name']); ?></strong>
                                                <span style="font-size: 0.75rem; color: var(--text-muted); display:block;"><?php echo htmlspecialchars($rev['created_at']); ?></span>
                                            </div>
                                        </div>
                                        <div style="color: #fbbf24; font-size:0.8rem;">
                                            <?php 
                                            for($i=1; $i<=5; $i++) {
                                                echo $i <= $rev['rating'] ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <p style="margin:0; font-size:0.9rem; color:#cbd5e1; line-height:1.5;"><?php echo htmlspecialchars($rev['comment']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color:var(--text-muted); font-size:0.9rem; text-align:center; padding:15px 0;">Este curso todavía no tiene valoraciones. ¡Sé el primero en calificarlo!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- TARJETA LATERAL (Derecha) -->
            <div>
                <div class="sidebar-card">
                    <img src="<?php echo htmlspecialchars(\App\Models\Course::resolveThumbnail($course)); ?>" alt="Portada" onerror="this.src='assets/images/courses/default-thumb.svg'">
                    
                    <div class="sidebar-card-info">
                        <div class="sidebar-card-info-item">
                            <span>Nivel</span>
                            <span style="font-weight:bold;"><?php echo htmlspecialchars($course['level']); ?></span>
                        </div>
                        <div class="sidebar-card-info-item">
                            <span>Duración</span>
                            <span style="font-weight:bold;"><?php echo htmlspecialchars($course['duration_hours']); ?> horas</span>
                        </div>
                        <div class="sidebar-card-info-item">
                            <span>Lecciones</span>
                            <span style="font-weight:bold;"><?php echo $course['total_lessons']; ?> clases</span>
                        </div>
                        <div class="sidebar-card-info-item">
                            <span>Módulos</span>
                            <span style="font-weight:bold;"><?php echo count($modules); ?></span>
                        </div>
                        <div class="sidebar-card-info-item">
                            <span>Certificación</span>
                            <span style="font-weight:bold; color: #fbbf24;"><i class="fa-solid fa-award"></i> Incluida</span>
                        </div>
                    </div>

                    <?php if ($isEnrolled): ?>
                        <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>" class="btn" style="width: 100%; text-align: center; display: block; box-sizing: border-box;"><i class="fa-solid fa-graduation-cap"></i> Continuar Cursada</a>
                    <?php else: ?>
                        <a href="index.php?action=enroll_form&course=<?php echo $course['slug']; ?>" class="btn" style="width: 100%; text-align: center; display: block; box-sizing: border-box;"><i class="fa-solid fa-circle-play"></i> Inscribirse Ahora</a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Controladores del acordeón del temario
        function toggleModule(header) {
            const body = header.nextElementSibling;
            const icon = header.querySelector('span i');
            
            if (body.style.display === 'block') {
                body.style.display = 'none';
                icon.className = 'fa-solid fa-chevron-down';
            } else {
                body.style.display = 'block';
                icon.className = 'fa-solid fa-chevron-up';
            }
        }

        function toggleAllModules() {
            const bodies = document.querySelectorAll('.curriculum-module-body');
            const link = document.querySelector('[onclick="toggleAllModules()"]');
            const allOpen = Array.from(bodies).every(b => b.style.display === 'block');

            bodies.forEach(body => {
                const header = body.previousElementSibling;
                const icon = header.querySelector('span i');
                if (allOpen) {
                    body.style.display = 'none';
                    icon.className = 'fa-solid fa-chevron-down';
                } else {
                    body.style.display = 'block';
                    icon.className = 'fa-solid fa-chevron-up';
                }
            });

            link.innerText = allOpen ? 'Expandir todos' : 'Contraer todos';
        }
    </script>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
