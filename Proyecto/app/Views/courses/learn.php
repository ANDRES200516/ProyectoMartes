<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula - <?php echo htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{
            --bg:#060712;
            --card-bg: rgba(255,255,255,0.03);
            --glass: rgba(255,255,255,0.04);
            --text:#e6eef8;
            --muted:#94a3b8;
            --primary:#38bdf8;
            --accent:#10b981;
            --border-color: rgba(255,255,255,0.06);
        }
        html,body{height:100%;margin:0;background:linear-gradient(180deg,#04060a 0%, #071029 100%);color:var(--text);font-family:Inter,Segoe UI,Arial,sans-serif}
        a{color:inherit}
        nav{height:64px;display:flex;align-items:center;justify-content:space-between;padding:0 18px;border-bottom:1px solid var(--border-color);backdrop-filter: blur(6px);background:linear-gradient(180deg, rgba(255,255,255,0.02), transparent)}
        nav .brand{display:flex;align-items:center;gap:12px;font-weight:700}
        nav .brand .logo{font-size:1.05rem;color:var(--text)}
        .classroom-layout{display:grid;grid-template-columns:360px 1fr;height:calc(100vh - 64px);gap:20px;padding:20px;box-sizing:border-box}
        @media (max-width:1000px){.classroom-layout{grid-template-columns:1fr;height:auto;padding:12px;}}

        /* SIDEBAR */
        .classroom-sidebar{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:14px;padding:14px;border:1px solid var(--border-color);height:100%;overflow:auto;position:sticky;top:20px}
        .sidebar-top{display:flex;flex-direction:column;gap:10px;padding:8px}
        .sidebar-top h3{margin:0;font-size:1rem}
        .progress-area{display:flex;flex-direction:column;gap:8px}
        .progress-bar{height:10px;background:rgba(255,255,255,0.03);border-radius:999px;overflow:hidden}
        .progress-fill{height:100%;background:linear-gradient(90deg,var(--primary),var(--accent));width:<?php echo max(0, min(100, $enrollment['progress_percentage'] ?? 0)); ?>%;transition:width .4s}
        .sidebar-modules{margin-top:8px}
        .module{margin-bottom:10px}
        .module-header{display:flex;justify-content:space-between;align-items:center;padding:10px;border-radius:8px;cursor:pointer}
        .module-header h4{margin:0;font-size:0.9rem;color:var(--text)}
        .lessons{margin-top:6px;padding-left:6px}
        .lesson-item{display:flex;align-items:center;gap:10px;padding:8px;border-radius:8px;color:var(--muted);cursor:pointer;margin-bottom:6px;border-left:3px solid transparent}
        .lesson-item:hover{background:linear-gradient(90deg, rgba(255,255,255,0.01), transparent);color:var(--text)}
        .lesson-item.active{background:linear-gradient(90deg, rgba(56,189,248,0.06), transparent);color:var(--text);border-left-color:var(--primary)}
        .lesson-meta{font-size:0.78rem;color:var(--muted);margin-left:auto}

        /* MAIN */
        .classroom-main{height:100%;overflow:auto}
        .player-card{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:14px;padding:12px;border:1px solid var(--border-color);box-shadow:0 8px 24px rgba(2,6,23,0.6)}
        .video-wrapper{position:relative;padding-bottom:56.25%;height:0;border-radius:10px;overflow:hidden;background:#000}
        .video-wrapper iframe, .video-wrapper video{position:absolute;top:0;left:0;width:100%;height:100%;}
        .lesson-info{display:flex;align-items:center;gap:14px;margin-top:14px}
        .lesson-title{font-size:1.25rem;font-weight:700}
        .lesson-desc{color:var(--muted);font-size:0.95rem}
        .actions{display:flex;gap:10px;margin-top:12px}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:10px;background:linear-gradient(90deg,#0b1220, #0d1b2a);border:1px solid var(--border-color);color:var(--text);text-decoration:none}
        .btn-primary{background:linear-gradient(90deg,var(--primary),#2563eb);border:none}
        .btn-success{background:linear-gradient(90deg,var(--accent),#059669);border:none}
        .badge-completed{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:999px;background:linear-gradient(90deg, rgba(16,185,129,0.16), rgba(16,185,129,0.06));color:var(--accent);font-weight:600;animation:pop .6s ease}
        @keyframes pop{0%{transform:scale(.9);opacity:0}100%{transform:scale(1);opacity:1}}

        /* Resources & comments */
        .resources{margin-top:18px;display:flex;flex-direction:column;gap:8px}
        .resource-item{display:flex;justify-content:space-between;align-items:center;padding:10px;border-radius:10px;background:var(--glass);border:1px solid var(--border-color)}
        .comments{margin-top:18px}
        .comment-box{display:flex;gap:8px}
        .comment-box textarea{flex:1;padding:10px;border-radius:8px;background:#071025;border:1px solid var(--border-color);color:var(--text)}

        /* Mobile: hamburger and overlay sidebar */
        .mobile-hamburger{display:none}
        @media (max-width:1000px){
            .classroom-sidebar{position:fixed;left:-100%;top:64px;height:calc(100% - 64px);width:320px;transition:left .35s;z-index:40}
            .classroom-sidebar.open{left:6px}
            .mobile-hamburger{display:flex;align-items:center;gap:10px}
        }

        /* small tweaks */
        .footer-actions{display:flex;gap:10px;align-items:center}
    </style>
</head>
<body>
    <nav>
        <div class="brand">
            <div class="logo"><a href="index.php?action=dashboard">Learns</a></div>
            <div style="font-size:0.9rem;color:var(--muted)">/ <?php echo htmlspecialchars($course['title']); ?></div>
        </div>
        <div style="display:flex;align-items:center;gap:12px">
            <div class="mobile-hamburger">
                <button id="openSidebar" class="btn" aria-label="Abrir temario"><i class="fa-solid fa-bars"></i></button>
            </div>
            <a href="index.php?action=course_details&course=<?php echo $course['slug']; ?>" class="btn"><i class="fa-solid fa-circle-info"></i> Detalles</a>
            <a href="index.php?action=dashboard" class="btn"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        </div>
    </nav>

    <div class="classroom-layout">

        <aside class="classroom-sidebar" id="sidebar">
            <div class="sidebar-top">
                <h3>Temario</h3>
                <?php if (!$isFreePreview): ?>
                    <div class="progress-area">
                        <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:var(--muted)"><span>Mi avance</span><strong id="progressText"><?php echo number_format($enrollment['progress_percentage'],0); ?>%</strong></div>
                        <div class="progress-bar"><div class="progress-fill" id="progressBarFill"></div></div>
                    </div>
                <?php else: ?>
                    <div class="tag" style="color:var(--primary);font-size:0.85rem">Vista previa</div>
                <?php endif; ?>
            </div>

            <div class="sidebar-modules">
                <?php foreach ($modules as $module): ?>
                    <div class="module">
                        <div class="module-header" onclick="toggleModule(this)">
                            <h4>Módulo <?php echo $module['sort_order']; ?> — <?php echo htmlspecialchars($module['title']); ?></h4>
                            <div style="font-size:0.85rem;color:var(--muted)"><i class="fa-solid fa-chevron-up"></i></div>
                        </div>
                        <div class="lessons">
                            <?php $lessons = $lessonsByModule[$module['id']] ?? []; foreach($lessons as $lesson): $isLActive = ($currentLesson && $currentLesson['id'] === $lesson['id']); $isLCompleted = !$isFreePreview && $lessonModel->isCompleted($_SESSION['user_id'], $lesson['id']); ?>
                                <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>&lesson=<?php echo $lesson['id']; ?>" class="lesson-item <?php echo $isLActive ? 'active' : ''; ?>">
                                    <?php if ($lesson['video_type'] === 'youtube'): ?><i class="fa-solid fa-play-circle" style="color:var(--primary)"></i>
                                    <?php elseif ($lesson['pdf_url']): ?><i class="fa-solid fa-file-pdf" style="color:#ef4444"></i>
                                    <?php else: ?><i class="fa-solid fa-file-lines" style="color:var(--muted)"></i><?php endif; ?>
                                    <div><?php echo htmlspecialchars($lesson['title']); ?><div style="font-size:0.78rem;color:var(--muted)">Clase • <?php echo $lesson['duration_minutes']; ?> min</div></div>
                                    <div class="lesson-meta">
                                        <?php if ($isLCompleted): ?><i class="fa-solid fa-circle-check" style="color:var(--accent)"></i><?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>

        <main class="classroom-main">
            <div class="player-card">
                <?php if ($isFreePreview): ?>
                    <div style="padding:8px;background:linear-gradient(90deg,#b45309,#d97706);border-radius:8px;color:#071029;font-weight:600;margin-bottom:12px;">Vista Previa</div>
                <?php endif; ?>

                <?php if ($currentLesson): ?>
                    <div class="video-wrapper" id="mediaPlayer">
                        <?php if ($currentLesson['video_type'] === 'youtube' && !empty($currentLesson['video_url'])): ?>
                            <iframe src="<?php echo htmlspecialchars($currentLesson['video_url']); ?>?rel=0&showinfo=0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                        <?php elseif ($currentLesson['video_type'] === 'local' && !empty($currentLesson['video_path'])): ?>
                            <video controls <?php echo (!empty($currentLesson['autoplay']) ? 'autoplay' : ''); ?> style="width:100%;height:100%;">
                                <source src="<?php echo htmlspecialchars($currentLesson['video_path']); ?>" type="video/mp4">
                                Tu navegador no soporta este formato de video.
                            </video>
                        <?php elseif ($currentLesson['pdf_url']): ?>
                            <iframe src="<?php echo htmlspecialchars($currentLesson['pdf_url']); ?>" style="width:100%;height:100%;border:none"></iframe>
                        <?php else: ?>
                            <div style="padding:40px;text-align:center;color:var(--muted)">No hay contenido multimedia disponible para esta lección.</div>
                        <?php endif; ?>
                    </div>

                    <div class="lesson-info">
                        <div>
                            <div class="lesson-title"><?php echo htmlspecialchars($currentLesson['title']); ?></div>
                            <div class="lesson-desc"><?php echo htmlspecialchars($currentLesson['subtitle'] ?? ''); ?></div>
                        </div>
                        <div style="margin-left:auto;text-align:right">
                            <div style="color:var(--muted);font-size:0.85rem">Duración: <?php echo $currentLesson['duration_minutes']; ?> min</div>
                            <div style="margin-top:6px">
                                <?php if (!$isFreePreview): ?>
                                    <?php if (($enrollment['progress_percentage'] ?? 0) >= 100): ?>
                                        <span class="badge-completed"><i class="fa-solid fa-circle-check"></i> Curso Finalizado</span>
                                    <?php else: ?>
                                        <button id="markCompleteBtn" class="btn" onclick="toggleLessonCompleteBtn(<?php echo $currentLesson['id']; ?>)"><i class="fa-regular fa-circle"></i><span id="markCompleteBtnText">Marcar como Completada</span></button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($currentLesson['content'])): ?>
                        <div style="margin-top:16px;background:var(--card-bg);padding:16px;border-radius:10px;border:1px solid var(--border-color);color:var(--muted)"><?php echo $currentLesson['content']; ?></div>
                    <?php endif; ?>

                    <div class="resources">
                        <?php if (!empty($currentLesson['resources'])): foreach($currentLesson['resources'] as $res): ?>
                            <div class="resource-item"><div><i class="fa-solid fa-download"></i> <?php echo htmlspecialchars($res['title']); ?></div><a href="<?php echo htmlspecialchars($res['url']); ?>" class="btn">Descargar</a></div>
                        <?php endforeach; else: ?>
                            <div class="resource-item">No hay recursos adjuntos para esta lección.</div>
                        <?php endif; ?>
                    </div>

                    <div class="comments">
                        <h4 style="margin:12px 0 8px 0">Comentarios</h4>
                        <div class="comment-box">
                            <textarea id="commentText" rows="2" placeholder="Escribe un comentario público..."></textarea>
                            <button class="btn btn-primary" onclick="postComment(<?php echo $currentLesson['id']; ?>)">Publicar</button>
                        </div>
                        <div id="commentsList" style="margin-top:12px;color:var(--muted)">
                            <!-- comentarios cargados vía backend -->
                        </div>
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:18px">
                        <div>
                            <?php if ($prevLesson): ?>
                                <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>&lesson=<?php echo $prevLesson['id']; ?>" class="btn"><i class="fa-solid fa-arrow-left"></i> Clase Anterior</a>
                            <?php endif; ?>
                        </div>
                        <div class="footer-actions">
                            <?php if ($nextLesson): ?>
                                <a href="index.php?action=learn&course=<?php echo $course['slug']; ?>&lesson=<?php echo $nextLesson['id']; ?>" class="btn btn-primary">Clase Siguiente <i class="fa-solid fa-arrow-right"></i></a>
                            <?php else: ?>
                                <?php if (!$isFreePreview): ?>
                                    <?php if (($enrollment['progress_percentage'] ?? 0) >= 100): ?>
                                        <a href="index.php?action=view_certificate&course=<?php echo $course['id']; ?>" class="btn btn-success"><i class="fa-solid fa-certificate"></i> Descargar Certificado</a>
                                    <?php else: ?>
                                        <a href="index.php?action=dashboard" class="btn">Ir a Mis Cursos</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="index.php?action=enroll_form&course=<?php echo $course['slug']; ?>" class="btn btn-primary">Inscribirse</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php else: ?>
                    <div style="padding:40px;text-align:center;color:var(--muted)"><i class="fa-solid fa-circle-info fa-2x" style="color:var(--primary)"></i><h3>No hay lecciones</h3><p>El instructor no ha agregado contenidos todavía.</p></div>
                <?php endif; ?>
            </div>
        </main>

    </div>

    <script>
        // Sidebar module toggle
        function toggleModule(el){
            const icon = el.querySelector('i, svg, .fa-chevron-up, .fa-chevron-down');
            const next = el.nextElementSibling || el.parentElement.querySelector('.lessons');
            if(next.style.display === 'none' || next.style.display === ''){ next.style.display = 'block'; if(icon) el.querySelector('i, .fa-chevron-up').className = 'fa-solid fa-chevron-up'; }
            else { next.style.display = 'none'; if(icon) el.querySelector('i, .fa-chevron-up').className = 'fa-solid fa-chevron-down'; }
        }

        // Mobile sidebar open/close
        document.getElementById('openSidebar')?.addEventListener('click', ()=>{document.getElementById('sidebar').classList.add('open')});
        document.addEventListener('click', (e)=>{ if(window.innerWidth<=1000 && !document.getElementById('sidebar').contains(e.target) && !e.target.closest('#openSidebar')){ document.getElementById('sidebar').classList.remove('open')} });

        // Mark lesson complete (AJAX)
        function toggleLessonCompleteBtn(lessonId){
            const btn = document.getElementById('markCompleteBtn');
            const isCompleted = btn && btn.classList.contains('completed');
            const next = !isCompleted;
            submitProgressChange(lessonId, next);
        }
        function submitProgressChange(lessonId, completed){
            const fd = new FormData(); fd.append('lesson_id', lessonId); fd.append('completed', completed?1:0);
            fetch('index.php?action=mark_lesson_progress',{method:'POST',body:fd}).then(r=>r.json()).then(data=>{ if(data.success){ document.getElementById('progressText') && (document.getElementById('progressText').innerText = Math.round(data.progress_percentage)+'%'); document.getElementById('progressBarFill') && (document.getElementById('progressBarFill').style.width = data.progress_percentage+'%'); const btn = document.getElementById('markCompleteBtn'); if(btn){ if(completed){btn.classList.add('completed'); btn.querySelector('i').className='fa-solid fa-circle-check'; document.getElementById('markCompleteBtnText')&&(document.getElementById('markCompleteBtnText').innerText='Clase Completada')} else {btn.classList.remove('completed'); btn.querySelector('i').className='fa-regular fa-circle'; document.getElementById('markCompleteBtnText')&&(document.getElementById('markCompleteBtnText').innerText='Marcar como Completada')} } if(data.status === 'completed' && completed && Math.round(data.progress_percentage) >= 100){
                        if(window.Swal){ Swal.fire({title:'🎉 ¡Felicitaciones!',html:'Has completado el curso. <strong>Descarga tu certificado</strong>.',icon:'success',confirmButtonText:'Ver Certificado',background:'#0b1220',color:'#e6eef8'}).then(()=>{ window.location.href='index.php?action=view_certificate&course=<?php echo $course['id']; ?>' }) }
                    } } });
        }

        // Post comment (simple)
        function postComment(lessonId){ const text = document.getElementById('commentText').value; if(!text.trim()) return; const fd=new FormData(); fd.append('lesson_id', lessonId); fd.append('text', text); fetch('index.php?action=post_comment',{method:'POST',body:fd}).then(r=>r.json()).then(data=>{ if(data.success){ const list=document.getElementById('commentsList'); const div=document.createElement('div'); div.style.padding='10px'; div.style.borderBottom='1px solid var(--border-color)'; div.innerHTML=`<strong>${data.user_name}</strong><div style="color:var(--muted)">${text}</div>`; list.prepend(div); document.getElementById('commentText').value=''; } }); }
    </script>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
