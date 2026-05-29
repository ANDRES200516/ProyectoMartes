<?php
ob_start();
?>
<style>
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
        margin-top: -30px; /* To overlap hero a bit if we want, or keep margin */
    }
    @media (max-width: 900px) {
        .details-grid { grid-template-columns: 1fr; }
        .sidebar-card { position: static !important; margin-top: 20px; }
    }
    
    /* Hero Banner */
    .course-hero {
        background: linear-gradient(rgba(15, 23, 42, 0.85), #0f172a), 
                    url('<?php echo htmlspecialchars(\App\Models\Course::resolveBanner($course)); ?>') no-repeat center center;
        background-size: cover;
        border-bottom: 1px solid var(--border-color);
        padding: 60px 0;
        color: #f8fafc;
        margin: -2rem -2rem 2rem -2rem; /* Extend to edge of app-content */
    }
    .course-hero-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 40px;
    }
    .course-hero-content h1 {
        font-size: 2.5rem;
        margin: 15px 0;
        font-weight: 800;
        line-height: 1.2;
    }

    /* Sidebar summary card */
    .sidebar-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5);
        position: sticky;
        top: 90px; /* account for topbar */
    }
    .sidebar-card img {
        width: 100%;
        height: 200px;
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
    .sidebar-card-info-item span:first-child { color: var(--text-muted); }

    /* Content panels */
    .details-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
    }
    .details-panel h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #f1f5f9;
        font-size: 1.3rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding-bottom: 10px;
    }
    .checkmark-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 15px;
    }
    .checkmark-list li {
        position: relative;
        padding-left: 30px;
        color: #cbd5e1;
        line-height: 1.5;
    }
    .checkmark-list li::before {
        content: '\f00c'; /* fontawesome check */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 2px;
        color: #10b981;
    }

    /* Accordion curriculum */
    .curriculum-module {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
    }
    .curriculum-module-header {
        background: rgba(255,255,255,0.03);
        padding: 16px 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        transition: var(--transition);
    }
    .curriculum-module-header:hover {
        background: rgba(59, 130, 246, 0.1);
    }
    .curriculum-module-header h4 {
        margin: 0;
        color: var(--text-main);
        font-size: 1.05rem;
    }
    .curriculum-module-body {
        background: rgba(15,23,42,0.4);
        border-top: 1px solid var(--border-color);
        display: none;
        padding: 10px 20px;
    }
    .curriculum-lesson-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,0.03);
        font-size: 0.95rem;
        transition: var(--transition);
    }
    .curriculum-lesson-row:hover {
        padding-left: 5px;
        padding-right: 5px;
        background: rgba(255,255,255,0.02);
    }
    .curriculum-lesson-row:last-child { border-bottom: none; }
    .curriculum-lesson-info {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #cbd5e1;
    }
    
    /* Review styling */
    .reviews-summary {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        padding: 20px;
        background: rgba(59, 130, 246, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    .reviews-rating-number {
        font-size: 3.5rem;
        font-weight: 800;
        color: #f8fafc;
        line-height: 1;
    }
    .review-card {
        background: rgba(15,23,42,0.4);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
    }
    .review-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .review-user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .review-user-photo {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary);
    }
    
    .tag {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .tag-blue { background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.4); }
</style>
<?php
$extraHead = ob_get_clean();

ob_start();
?>
<!-- Hero del Curso -->
<div class="course-hero">
    <div class="course-hero-content">
        <div style="display:flex; gap:10px;">
            <span class="tag tag-blue" style="font-size:0.8rem; text-transform: uppercase;"><?php echo htmlspecialchars($course['level']); ?></span>
            <span class="tag" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); color:#e2e8f0; font-size:0.8rem; text-transform: uppercase;"><?php echo htmlspecialchars($course['category'] ?? 'General'); ?></span>
        </div>
        <h1><?php echo htmlspecialchars($course['title']); ?></h1>
        <p style="font-size: 1.15rem; color: #cbd5e1; max-width: 800px; margin-bottom: 25px; line-height:1.6;"><?php echo htmlspecialchars($course['short_description']); ?></p>
        
        <div style="display:flex; align-items:center; gap:8px; color: #fbbf24; font-size:1rem;">
            <i class="fa-solid fa-star"></i>
            <span style="font-weight:bold; color:#f8fafc; font-size: 1.1rem;"><?php echo number_format($course['rating_avg'], 1); ?></span>
            <span style="color:var(--text-muted); margin-left: 5px;">(<?php echo $course['rating_count']; ?> valoraciones)</span>
            <span style="color:rgba(255,255,255,0.2); margin:0 10px;">|</span>
            <span style="color:#e2e8f0;"><i class="fa-solid fa-users" style="color: var(--primary);"></i> <?php echo $course['students_count']; ?> estudiantes matriculados</span>
        </div>
    </div>
</div>

<div style="max-width: 1200px; margin: 0 auto;">
    <div class="details-grid">
        
        <!-- CONTENIDO PRINCIPAL (Izquierda) -->
        <div>
            <!-- Descripción del Curso -->
            <div class="details-panel">
                <h3>Acerca de este curso</h3>
                <div style="color: #cbd5e1; line-height: 1.7; font-size: 1rem;">
                    <?php echo nl2br((string)$course['description']); ?>
                </div>
            </div>

            <!-- Requisitos y Objetivos -->
            <?php if (!empty($course['objectives']) || !empty($course['requirements'])): ?>
                <div class="details-panel" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                    <?php if (!empty($course['objectives'])): ?>
                        <div>
                            <h3 style="border-bottom:none; margin-bottom:15px;">Lo que aprenderás</h3>
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
                        <div style="margin-top:20px; border-top:1px solid rgba(255,255,255,0.05); padding-top:20px;">
                            <h3 style="border-bottom:none; margin-bottom:15px;">Requisitos previos</h3>
                            <ul style="padding-left:20px; color:#cbd5e1; line-height:1.6; font-size: 0.95rem;">
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
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; font-size:0.9rem; color:var(--text-muted);">
                    <span><strong style="color:var(--text-main);"><?php echo count($modules); ?></strong> módulos • <strong style="color:var(--text-main);"><?php echo $course['total_lessons']; ?></strong> lecciones</span>
                    <a href="javascript:void(0)" onclick="toggleAllModules()" style="color:var(--primary); text-decoration:none; font-weight:600;">Expandir todos</a>
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
                                            <i class="fa-solid fa-circle-play" style="color: #f43f5e; width: 20px; text-align:center;"></i>
                                        <?php elseif ($lesson['pdf_url']): ?>
                                            <i class="fa-solid fa-file-pdf" style="color: #38bdf8; width: 20px; text-align:center;"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-file-lines" style="color: #10b981; width: 20px; text-align:center;"></i>
                                        <?php endif; ?>
                                        
                                        <span><?php echo htmlspecialchars($lesson['title']); ?></span>
                                        
                                        <?php if ($lesson['is_free']): ?>
                                            <span class="badge badge-approved" style="font-size: 0.65rem; padding: 2px 6px; margin-left: 8px;">Preview</span>
                                        <?php endif; ?>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:15px;">
                                        <span style="font-size: 0.85rem; color: var(--text-muted);"><i class="fa-solid fa-clock"></i> <?php echo $lesson['duration_minutes']; ?> min</span>
                                        
                                        <?php if ($isEnrolled): ?>
                                            <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>&lesson=<?php echo $lesson['id']; ?>" class="btn-small" style="background:var(--primary); padding: 4px 10px; font-size: 0.8rem; text-decoration:none;"><i class="fa-solid fa-play"></i></a>
                                        <?php else: ?>
                                            <?php if ($lesson['is_free']): ?>
                                                <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>&lesson=<?php echo $lesson['id']; ?>" class="btn-small" style="background:#10b981; padding: 4px 10px; font-size: 0.8rem; text-decoration:none;"><i class="fa-solid fa-eye"></i> Previsualizar</a>
                                            <?php else: ?>
                                                <i class="fa-solid fa-lock" style="color:var(--text-muted); opacity:0.5;" title="Inscríbete para desbloquear"></i>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            else:
                            ?>
                                <p style="color:var(--text-muted); font-size:0.9rem; text-align:center; padding:15px 0; margin:0;">Módulo en construcción.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Sección de Reseñas / Valoraciones -->
            <div class="details-panel">
                <h3>Opiniones de los estudiantes</h3>
                <div class="reviews-summary">
                    <div class="reviews-rating-number"><?php echo number_format($course['rating_avg'], 1); ?></div>
                    <div>
                        <div style="color: #fbbf24; font-size: 1.3rem; display:flex; gap:4px;">
                            <?php 
                            $avgStars = round($course['rating_avg']);
                            for($i=1; $i<=5; $i++) {
                                echo $i <= $avgStars ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span style="font-size:0.9rem; color:var(--text-muted); margin-top:8px; display:block;">Promedio basado en <?php echo $course['rating_count']; ?> valoraciones globales</span>
                    </div>
                </div>

                <!-- Dejar reseña -->
                <?php if ($isEnrolled && !$hasReviewed): ?>
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 12px; margin-bottom: 30px;">
                        <h4 style="margin-top:0; color:var(--text-main); font-size:1.1rem; margin-bottom: 15px;">¿Qué te pareció el curso?</h4>
                        <form action="index.php?action=save_review" method="POST">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            
                            <div style="margin-bottom:15px;">
                                <label style="display:block; font-size:0.9rem; color:var(--text-muted); margin-bottom:8px;">Calificación</label>
                                <select name="rating" required style="width:100%; background:var(--bg-dark); color:var(--text-main); border:1px solid var(--border-color); padding:12px; border-radius:8px; outline:none; cursor:pointer;">
                                    <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                                    <option value="4">⭐⭐⭐⭐ Muy Bueno</option>
                                    <option value="3">⭐⭐⭐ Bueno</option>
                                    <option value="2">⭐⭐ Regular</option>
                                    <option value="1">⭐ Malo</option>
                                </select>
                            </div>

                            <div style="margin-bottom:20px;">
                                <label style="display:block; font-size:0.9rem; color:var(--text-muted); margin-bottom:8px;">Comentario</label>
                                <textarea name="comment" rows="4" required placeholder="Comparte tu experiencia..." style="width:100%; background:var(--bg-dark); color:var(--text-main); border:1px solid var(--border-color); padding:15px; border-radius:8px; outline:none; resize:vertical;"></textarea>
                            </div>

                            <button type="submit" class="btn"><i class="fa-solid fa-paper-plane"></i> Enviar Reseña</button>
                        </form>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 20px;">
                    <?php if (count($reviews) > 0): ?>
                        <?php foreach ($reviews as $rev): ?>
                            <div class="review-card">
                                <div class="review-card-header">
                                    <div class="review-user-info">
                                        <img src="<?php echo htmlspecialchars($rev['user_photo'] ?? 'assets/images/default-avatar.png'); ?>" alt="Avatar" class="review-user-photo" onerror="this.src='assets/images/default-avatar.png'">
                                        <div>
                                            <strong style="font-size:0.95rem; color:#f1f5f9;"><?php echo htmlspecialchars($rev['user_name']); ?></strong>
                                            <span style="font-size: 0.8rem; color: var(--text-muted); display:block; margin-top:2px;"><?php echo htmlspecialchars($rev['created_at']); ?></span>
                                        </div>
                                    </div>
                                    <div style="color: #fbbf24; font-size:0.85rem;">
                                        <?php 
                                        for($i=1; $i<=5; $i++) {
                                            echo $i <= $rev['rating'] ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p style="margin:0; font-size:0.95rem; color:#cbd5e1; line-height:1.6;"><?php echo htmlspecialchars($rev['comment']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="text-align: center; padding: 30px; color: var(--text-muted);">
                            <i class="fa-solid fa-comment-dots" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.5;"></i>
                            <p style="margin:0;">Sin reseñas. ¡Sé el primero en calificarlo!</p>
                        </div>
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
                        <span style="font-weight:600; color:var(--text-main);"><?php echo htmlspecialchars($course['level']); ?></span>
                    </div>
                    <div class="sidebar-card-info-item">
                        <span>Duración total</span>
                        <span style="font-weight:600; color:var(--text-main);"><?php echo htmlspecialchars($course['duration_hours']); ?> horas</span>
                    </div>
                    <div class="sidebar-card-info-item">
                        <span>Lecciones</span>
                        <span style="font-weight:600; color:var(--text-main);"><?php echo $course['total_lessons']; ?> clases</span>
                    </div>
                    <div class="sidebar-card-info-item">
                        <span>Certificación</span>
                        <span style="font-weight:600; color: #fbbf24;"><i class="fa-solid fa-award"></i> Incluida</span>
                    </div>
                </div>

                <?php if ($isEnrolled): ?>
                    <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>" class="btn" style="width: 100%; text-align: center; display: block; box-sizing: border-box; padding: 15px;"><i class="fa-solid fa-graduation-cap"></i> Ir al Curso</a>
                <?php else: ?>
                    <a href="index.php?action=enroll_form&course=<?php echo $course['slug']; ?>" class="btn" style="width: 100%; text-align: center; display: block; box-sizing: border-box; padding: 15px;"><i class="fa-solid fa-bolt"></i> Inscribirse Ahora</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();

ob_start();
?>
<script>
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

        link.innerText = allOpen ? 'Contraer todos' : 'Expandir todos';
    }
</script>
<?php
$extraScripts = ob_get_clean();

$pageTitle = $course['title'];
$activeMenu = 'courses';

require __DIR__ . '/../layouts/main.php';
