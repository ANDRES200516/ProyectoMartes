<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estructura de Curso - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .content-builder-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }
        .module-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }
        .module-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }
        .module-card-header {
            background: rgba(255,255,255,0.02);
            padding: 15px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.25s ease, visibility 0.25s ease;
            z-index: 999;
        }
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .modal-content {
            width: min(95%, 520px);
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.35);
            transform: translateY(-18px);
            transition: transform 0.25s ease;
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }
        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .modal-body label {
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        .modal-body input,
        .modal-body textarea {
            width: 100%;
            background: rgba(15, 23, 42, 0.7);
            color: var(--text-color);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 12px 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .modal-body input:focus,
        .modal-body textarea:focus,
        .modal-body select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 6px rgba(59, 130, 246, 0.12);
        }
        .modal-header {
            padding: 22px 24px 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 1.35rem;
            color: var(--text-color);
        }
        .form-footer {
            padding: 20px 24px 24px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(15, 23, 42, 0.8);
        }
        .modal-close {
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
        }
        .modal-close:hover {
            color: var(--text-color);
        }
        .module-title-section h3 {
            margin: 0;
            color: #f8fafc;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .module-title-section p {
            margin: 5px 0 0 0;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        .module-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .lessons-list {
            padding: 15px 25px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .lesson-row {
            background: rgba(15,23,42,0.4);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 8px;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
        }
        .lesson-row:hover {
            border-color: rgba(56, 189, 248, 0.3);
            background: rgba(15,23,42,0.6);
        }
        .lesson-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #e2e8f0;
        }
        .lesson-info i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        .lesson-info .lesson-type-video { color: #f43f5e; }
        .lesson-info .lesson-type-pdf { color: #38bdf8; }
        .lesson-info .lesson-type-text { color: #10b981; }
        .lesson-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        .modal-body select, .modal-body input, .modal-body textarea {
            width: 100%;
            background: #0f172a;
            color: #e2e8f0;
            border: 1px solid var(--border-color);
            padding: 10px;
            border-radius: 6px;
            outline: none;
            box-sizing: border-box;
            margin-bottom: 15px;
        }
        .modal-body select:focus, .modal-body input:focus, .modal-body textarea:focus {
            border-color: var(--primary-color);
        }
        .modal-body label {
            display: block;
            margin-bottom: 6px;
            color: #e2e8f0;
            font-weight: 500;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .checkbox-container input {
            width: auto;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=admin_dashboard" class="logo">Learn class</a>
        <div class="links">
            <a href="index.php?action=admin_dashboard">Usuarios</a>
            <a href="index.php?action=admin_courses" style="color: var(--primary-color);">Cursos</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="content-builder-header">
            <div>
                <span style="font-size: 0.85rem; text-transform: uppercase; color: var(--primary-color); font-weight: bold; letter-spacing: 1px;">Estructura del Curso</span>
                <h2 style="margin: 5px 0 0 0;"><?php echo htmlspecialchars($course['title']); ?></h2>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="index.php?action=admin_courses" class="btn btn-reject btn-sm" style="text-decoration:none; padding:6px 10px; font-size:0.95rem;"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                <button class="btn" onclick="openAddModuleModal()"><i class="fa-solid fa-plus"></i> Agregar Módulo</button>
            </div>
        </div>

        <!-- Módulos y Lecciones -->
        <div class="module-list">
            <?php if (count($modules) > 0): ?>
                <?php foreach ($modules as $module): ?>
                    <div class="module-card">
                        <div class="module-card-header">
                            <div class="module-title-section">
                                <h3>
                                    <span class="badge" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">Módulo <?php echo $module['sort_order']; ?></span>
                                    <?php echo htmlspecialchars($module['title']); ?>
                                </h3>
                                <?php if (!empty($module['description'])): ?>
                                    <p><?php echo htmlspecialchars($module['description']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="module-actions">
                                <button class="btn btn-approve" style="padding: 6px 12px;" onclick="openAddLessonModal(<?php echo $module['id']; ?>)">
                                    <i class="fa-solid fa-circle-plus"></i> + Lección
                                </button>
                                <button class="btn-small" style="background: #3b82f6;" onclick='openEditModuleModal(<?php echo json_encode($module); ?>)' title="Editar Módulo">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <a href="index.php?action=admin_delete_module&id=<?php echo $module['id']; ?>&course_id=<?php echo $course['id']; ?>" class="btn-small btn-reject" title="Eliminar Módulo" onclick="return confirm('¿Estás seguro de eliminar este módulo con todas sus lecciones?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>

                        <div class="lessons-list">
                            <?php 
                            $lessons = $lessonsByModule[$module['id']] ?? []; 
                            if (count($lessons) > 0): 
                            ?>
                                <?php foreach ($lessons as $lesson): ?>
                                    <div class="lesson-row">
                                        <div class="lesson-info">
                                            <span style="font-weight: bold; font-family: monospace; opacity: 0.6;"><?php echo $lesson['sort_order']; ?>.</span>
                                            <?php if ($lesson['video_type'] === 'youtube'): ?>
                                                <i class="fa-solid fa-circle-play lesson-type-video" title="Video de YouTube"></i>
                                            <?php elseif ($lesson['pdf_url']): ?>
                                                <i class="fa-solid fa-file-pdf lesson-type-pdf" title="Documento PDF"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-file-lines lesson-type-text" title="Texto de Lectura"></i>
                                            <?php endif; ?>
                                            <span><?php echo htmlspecialchars($lesson['title']); ?></span>
                                            
                                            <?php if ($lesson['is_free']): ?>
                                                <span class="badge badge-approved" style="font-size: 0.7rem; padding: 2px 6px;">Gratis (Vista Previa)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="lesson-meta">
                                            <span><i class="fa-solid fa-clock"></i> <?php echo $lesson['duration_minutes']; ?> min</span>
                                            
                                            <div style="display:flex; gap:5px;">
                                                <button class="btn-small" style="background: #3b82f6;" onclick='openEditLessonModal(<?php echo json_encode($lesson); ?>, <?php echo $module['id']; ?>)' title="Editar Lección">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <a href="index.php?action=admin_delete_lesson&id=<?php echo $lesson['id']; ?>&course_id=<?php echo $course['id']; ?>" class="btn-small btn-reject" title="Eliminar Lección" onclick="return confirm('¿Eliminar esta lección?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; padding: 10px 0; margin: 0;">
                                    Este módulo no tiene lecciones creadas todavía.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card" style="text-align: center; padding: 40px; border-style: dashed; border-color: rgba(255,255,255,0.15);">
                    <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 15px;"></i>
                    <h3 style="margin:0 0 10px 0; color: #f8fafc;">Estructura Vacía</h3>
                    <p style="margin:0 0 20px 0; color: var(--text-muted); font-size: 0.95rem;">Comienza agregando un módulo para organizar el contenido de tu curso.</p>
                    <button class="btn" onclick="openAddModuleModal()"><i class="fa-solid fa-plus"></i> Crear Primer Módulo</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MODAL DE MÓDULO (Agregar/Editar) -->
    <div class="modal-overlay" id="moduleModal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2 id="moduleModalTitle">Agregar Módulo</h2>
                <button class="modal-close" onclick="closeModuleModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="index.php?action=admin_save_module" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="module_id" id="modal_module_id" value="">

                    <label for="module_title">Título del Módulo</label>
                    <input type="text" name="title" id="module_title" required placeholder="Ej. Introducción y Conceptos Básicos">

                    <label for="module_description">Descripción Breve</label>
                    <textarea name="description" id="module_description" rows="4" placeholder="Explica resumidamente qué se tratará en este módulo..."></textarea>

                    <label for="module_sort">Orden de Ordenamiento</label>
                    <input type="number" name="sort_order" id="module_sort" value="1" required min="1">
                </div>
                <div class="form-footer">
                    <button type="button" class="btn btn-reject" onclick="closeModuleModal()">Cancelar</button>
                    <button type="submit" class="btn">Guardar Módulo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE LECCIÓN (Agregar/Editar) -->
    <div class="modal-overlay" id="lessonModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2 id="lessonModalTitle">Agregar Lección</h2>
                <button class="modal-close" onclick="closeLessonModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="index.php?action=admin_save_lesson" method="POST" enctype="multipart/form-data">
                <div class="modal-body" style="max-height: 480px; overflow-y: auto;">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="hidden" name="module_id" id="modal_lesson_module_id" value="">
                    <input type="hidden" name="lesson_id" id="modal_lesson_id" value="">
                    <input type="hidden" name="existing_pdf" id="modal_existing_pdf" value="">

                    <label for="lesson_title">Título de la Lección</label>
                    <input type="text" name="title" id="lesson_title" required placeholder="Ej. 1.1 ¿Qué es una Neurona Artificial?">

                    <label for="lesson_type">Tipo de Recurso</label>
                    <select name="video_type" id="lesson_type" required onchange="toggleLessonTypeFields()">
                        <option value="none">Texto / Material de Lectura</option>
                        <option value="youtube">Video de YouTube</option>
                        <option value="pdf">Documento PDF</option>
                    </select>

                    <!-- Campo URL Video -->
                    <div id="field_video_url" style="display: none;">
                        <label for="lesson_video">URL del Video de YouTube</label>
                        <input type="url" name="video_url" id="lesson_video" placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <!-- Campo PDF Upload -->
                    <div id="field_pdf_file" style="display: none;">
                        <label for="lesson_pdf">Archivo PDF del Material</label>
                        <input type="file" name="pdf_file" id="lesson_pdf" accept="application/pdf">
                        <span id="existing_pdf_info" style="font-size: 0.85rem; color: #38bdf8; display: block; margin-top: -10px; margin-bottom: 10px;"></span>
                    </div>

                    <!-- Campo Contenido Texto -->
                    <label for="lesson_content">Contenido Escrito (Soporta HTML/Instrucciones)</label>
                    <textarea name="content" id="lesson_content" rows="6" placeholder="Escribe el artículo de la lección, guías o código para que practique el estudiante..."></textarea>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label for="lesson_duration">Duración Estimada (Minutos)</label>
                            <input type="number" name="duration_minutes" id="lesson_duration" value="10" required min="1">
                        </div>
                        <div>
                            <label for="lesson_sort">Orden</label>
                            <input type="number" name="sort_order" id="lesson_sort" value="1" required min="1">
                        </div>
                    </div>

                    <div class="checkbox-container">
                        <input type="checkbox" name="is_free" id="lesson_is_free" value="1">
                        <label for="lesson_is_free" style="display: inline; cursor: pointer; color: #f8fafc;">
                            Permitir previsualización gratuita (Sin necesidad de inscribirse)
                        </label>
                    </div>
                </div>
                <div class="form-footer" style="margin-top: 15px; padding-top: 15px;">
                    <button type="button" class="btn btn-reject" onclick="closeLessonModal()">Cancelar</button>
                    <button type="submit" class="btn">Guardar Lección</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modales interactivos de Módulos
        const moduleModal = document.getElementById('moduleModal');
        
        function openAddModuleModal() {
            document.getElementById('moduleModalTitle').innerText = 'Agregar Módulo';
            document.getElementById('modal_module_id').value = '';
            document.getElementById('module_title').value = '';
            document.getElementById('module_description').value = '';
            document.getElementById('module_sort').value = document.querySelectorAll('.module-card').length + 1;
            moduleModal.classList.add('active');
        }

        function openEditModuleModal(moduleData) {
            document.getElementById('moduleModalTitle').innerText = 'Editar Módulo';
            document.getElementById('modal_module_id').value = moduleData.id;
            document.getElementById('module_title').value = moduleData.title;
            document.getElementById('module_description').value = moduleData.description;
            document.getElementById('module_sort').value = moduleData.sort_order;
            moduleModal.classList.add('active');
        }

        function closeModuleModal() {
            moduleModal.classList.remove('active');
        }

        moduleModal.addEventListener('click', function(event) {
            if (event.target === moduleModal) {
                closeModuleModal();
            }
        });

        // Modales interactivos de Lecciones
        const lessonModal = document.getElementById('lessonModal');

        function openAddLessonModal(moduleId) {
            document.getElementById('lessonModalTitle').innerText = 'Agregar Lección';
            document.getElementById('modal_lesson_module_id').value = moduleId;
            document.getElementById('modal_lesson_id').value = '';
            document.getElementById('modal_existing_pdf').value = '';
            document.getElementById('existing_pdf_info').innerText = '';
            
            document.getElementById('lesson_title').value = '';
            document.getElementById('lesson_type').value = 'none';
            document.getElementById('lesson_video').value = '';
            document.getElementById('lesson_pdf').value = '';
            document.getElementById('lesson_content').value = '';
            document.getElementById('lesson_duration').value = '15';
            
            // Auto order number
            const list = document.querySelector(`.module-card:has(button[onclick*="openAddLessonModal(${moduleId})"]) .lessons-list`);
            const count = list ? list.querySelectorAll('.lesson-row').length : 0;
            document.getElementById('lesson_sort').value = count + 1;

            document.getElementById('lesson_is_free').checked = false;

            toggleLessonTypeFields();
            lessonModal.classList.add('active');
        }

        function openEditLessonModal(lessonData, moduleId) {
            document.getElementById('lessonModalTitle').innerText = 'Editar Lección';
            document.getElementById('modal_lesson_module_id').value = moduleId;
            document.getElementById('modal_lesson_id').value = lessonData.id;
            document.getElementById('modal_existing_pdf').value = lessonData.pdf_url || '';
            
            document.getElementById('lesson_title').value = lessonData.title;
            
            let type = 'none';
            if (lessonData.video_type && lessonData.video_type !== 'none') {
                type = lessonData.video_type;
            } else if (lessonData.pdf_url) {
                type = 'pdf';
            }
            document.getElementById('lesson_type').value = type;

            document.getElementById('lesson_video').value = lessonData.video_url || '';
            document.getElementById('lesson_pdf').value = '';
            
            if (lessonData.pdf_url) {
                const name = lessonData.pdf_url.split('/').pop();
                document.getElementById('existing_pdf_info').innerText = 'Archivo actual: ' + name;
            } else {
                document.getElementById('existing_pdf_info').innerText = '';
            }

            document.getElementById('lesson_content').value = lessonData.content || '';
            document.getElementById('lesson_duration').value = lessonData.duration_minutes;
            document.getElementById('lesson_sort').value = lessonData.sort_order;
            document.getElementById('lesson_is_free').checked = parseInt(lessonData.is_free) === 1;

            toggleLessonTypeFields();
            lessonModal.classList.add('active');
        }

        function closeLessonModal() {
            lessonModal.classList.remove('active');
        }

        function toggleLessonTypeFields() {
            const type = document.getElementById('lesson_type').value;
            const videoField = document.getElementById('field_video_url');
            const pdfField = document.getElementById('field_pdf_file');

            videoField.style.display = 'none';
            pdfField.style.display = 'none';

            if (type === 'youtube') {
                videoField.style.display = 'block';
            } else if (type === 'pdf') {
                pdfField.style.display = 'block';
            }
        }
    </script>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
