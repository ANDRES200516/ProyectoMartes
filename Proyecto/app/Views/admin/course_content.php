<?php
// Extracción segura de datos para JavaScript
$courseJson = json_encode($course);
$modulesJson = json_encode($modules);
$lessonsJson = json_encode($lessonsByModule);

// Prepare head
ob_start();
?>
<style>
    /* Builder Layout */
    .builder-container {
        display: grid;
        grid-template-columns: 300px 1fr 350px;
        gap: 20px;
        height: calc(100vh - var(--topbar-height) - 4rem);
        overflow: hidden;
    }

    /* Columnas */
    .builder-col {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .builder-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border-color);
        background: rgba(255,255,255,0.02);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .builder-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: var(--text-main);
    }

    .builder-body {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
    }

    /* Lista Izquierda (Draggable) */
    .module-item {
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .module-header {
        padding: 12px 15px;
        background: rgba(255,255,255,0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: grab;
        border-bottom: 1px solid var(--border-color);
    }
    
    .module-header h4 {
        margin: 0;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-main);
    }

    .lessons-list {
        padding: 10px;
        min-height: 50px;
    }

    .lesson-item {
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .lesson-item:hover, .lesson-item.active {
        border-color: var(--primary);
        background: rgba(59, 130, 246, 0.1);
    }

    .lesson-info {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }

    /* Centro: Editor */
    .editor-form label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .editor-form input, .editor-form textarea, .editor-form select {
        width: 100%;
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-family: inherit;
        transition: var(--transition);
    }

    .editor-form input:focus, .editor-form textarea:focus, .editor-form select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    /* Derecha: Preview */
    .preview-container {
        border: 2px dashed var(--border-color);
        border-radius: 12px;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        text-align: center;
        padding: 20px;
        background: var(--bg-dark);
    }

    .preview-content {
        width: 100%;
        height: 100%;
        display: none;
        flex-direction: column;
    }

    .preview-video {
        width: 100%;
        aspect-ratio: 16/9;
        background: #000;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    /* Ocultar elementos */
    .hidden { display: none !important; }
    
    /* Drag & Drop Visuals */
    .sortable-ghost { opacity: 0.4; }
    .drag-handle { color: var(--text-muted); cursor: grab; padding-right: 5px; }

</style>
<!-- SortableJS para Drag & Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<?php
$extraHead = ob_get_clean();

// Main Content
ob_start();
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <a href="index.php?action=admin_courses" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;"><i class="fa-solid fa-arrow-left"></i> Volver a Cursos</a>
        <h2 style="margin: 5px 0 0 0; color: var(--text-main);">Builder: <?php echo htmlspecialchars($course['title']); ?></h2>
    </div>
    <button class="btn" onclick="openModuleForm()"><i class="fa-solid fa-plus"></i> Nuevo Módulo</button>
</div>

<div class="builder-container">
    <!-- Columna Izquierda: Temario -->
    <div class="builder-col">
        <div class="builder-header">
            <h3>Temario</h3>
            <span class="badge badge-user" id="moduleCount"><?php echo count($modules); ?> Módulos</span>
        </div>
        <div class="builder-body" id="modulesList">
            <?php if (count($modules) > 0): ?>
                <?php foreach ($modules as $module): ?>
                    <div class="module-item" data-id="<?php echo $module['id']; ?>">
                        <div class="module-header">
                            <h4><i class="fa-solid fa-grip-vertical drag-handle"></i> M.<?php echo $module['sort_order']; ?> <?php echo htmlspecialchars($module['title']); ?></h4>
                            <div>
                                <button class="btn-small" style="background:transparent; color: var(--primary);" onclick='editModule(<?php echo json_encode($module); ?>)'><i class="fa-solid fa-pen"></i></button>
                                <button class="btn-small" style="background:transparent; color: var(--primary);" onclick='openLessonForm(<?php echo $module['id']; ?>)'><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="lessons-list" data-module="<?php echo $module['id']; ?>">
                            <?php 
                            $m_lessons = $lessonsByModule[$module['id']] ?? [];
                            foreach ($m_lessons as $lesson): 
                            ?>
                                <div class="lesson-item" data-id="<?php echo $lesson['id']; ?>" onclick='editLesson(<?php echo json_encode($lesson); ?>, <?php echo $module['id']; ?>)'>
                                    <div class="lesson-info">
                                        <i class="fa-solid fa-grip-vertical drag-handle"></i>
                                        <?php if ($lesson['video_type'] === 'youtube'): ?>
                                            <i class="fa-brands fa-youtube" style="color: #f43f5e;"></i>
                                        <?php elseif ($lesson['pdf_url']): ?>
                                            <i class="fa-solid fa-file-pdf" style="color: #38bdf8;"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-file-lines" style="color: #10b981;"></i>
                                        <?php endif; ?>
                                        <span><?php echo htmlspecialchars($lesson['title']); ?></span>
                                    </div>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);"><?php echo $lesson['duration_minutes']; ?>m</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; color: var(--text-muted); padding: 20px;">
                    <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <p>No hay módulos. Crea el primero.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Columna Central: Editor -->
    <div class="builder-col">
        <div class="builder-header">
            <h3 id="editorTitle">Editor</h3>
        </div>
        <div class="builder-body">
            
            <!-- Estado Inicial -->
            <div id="editorEmpty" class="preview-container" style="border: none;">
                <i class="fa-solid fa-pen-nib" style="font-size: 3rem; margin-bottom: 15px; color: rgba(255,255,255,0.1);"></i>
                <p>Selecciona una lección para editarla o crea contenido nuevo.</p>
            </div>

            <!-- Formulario de Módulo -->
            <form id="formModule" class="editor-form hidden" action="index.php?action=admin_save_module" method="POST">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <input type="hidden" name="module_id" id="mod_id" value="">
                
                <label>Título del Módulo</label>
                <input type="text" name="title" id="mod_title" required>
                
                <label>Descripción</label>
                <textarea name="description" id="mod_desc" rows="4"></textarea>
                
                <label>Orden</label>
                <input type="number" name="sort_order" id="mod_sort" value="1" required>

                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="btn" style="flex:1;">Guardar Módulo</button>
                    <a id="btnDeleteMod" href="#" class="btn btn-reject" style="display:none;" onclick="return confirm('¿Eliminar módulo?')"><i class="fa-solid fa-trash"></i></a>
                </div>
            </form>

            <!-- Formulario de Lección -->
            <form id="formLesson" class="editor-form hidden" action="index.php?action=admin_save_lesson" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <input type="hidden" name="module_id" id="les_mod_id" value="">
                <input type="hidden" name="lesson_id" id="les_id" value="">
                <input type="hidden" name="existing_pdf" id="les_exist_pdf" value="">

                <label>Título de la Lección</label>
                <input type="text" name="title" id="les_title" required onkeyup="updatePreview()">

                <label>Tipo de Recurso</label>
                <select name="video_type" id="les_type" required onchange="toggleResourceFields(); updatePreview();">
                    <option value="none">Texto / Material de Lectura</option>
                    <option value="youtube">Video de YouTube</option>
                    <option value="pdf">Documento PDF</option>
                </select>

                <div id="fieldVideo" class="hidden">
                    <label>URL de YouTube</label>
                    <input type="url" name="video_url" id="les_video" placeholder="https://youtube.com/watch?v=..." onchange="updatePreview()">
                </div>

                <div id="fieldPdf" class="hidden">
                    <label>Archivo PDF</label>
                    <input type="file" name="pdf_file" id="les_pdf" accept="application/pdf">
                    <p id="les_pdf_info" style="font-size: 0.8rem; color: var(--primary); margin-top: -10px; margin-bottom: 15px;"></p>
                </div>

                <label>Contenido Escrito (HTML)</label>
                <textarea name="content" id="les_content" rows="8" onkeyup="updatePreview()"></textarea>

                <div style="display: flex; gap: 15px;">
                    <div style="flex:1;">
                        <label>Duración (min)</label>
                        <input type="number" name="duration_minutes" id="les_duration" value="10">
                    </div>
                    <div style="flex:1;">
                        <label>Orden</label>
                        <input type="number" name="sort_order" id="les_sort" value="1">
                    </div>
                </div>

                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="is_free" id="les_free" value="1" style="width:auto; margin:0;">
                    Lección Gratuita (Preview)
                </label>

                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn" style="flex:1;">Guardar Lección</button>
                    <a id="btnDeleteLes" href="#" class="btn btn-reject" style="display:none;" onclick="return confirm('¿Eliminar lección?')"><i class="fa-solid fa-trash"></i></a>
                </div>
            </form>

        </div>
    </div>

    <!-- Columna Derecha: Preview -->
    <div class="builder-col">
        <div class="builder-header">
            <h3>Vista Previa</h3>
            <span class="badge badge-approved" style="background: transparent; border: 1px solid var(--border-color);">En vivo</span>
        </div>
        <div class="builder-body" style="padding: 0; background: var(--bg-dark);">
            <div id="previewEmpty" class="preview-container" style="border:none;">
                <i class="fa-solid fa-eye" style="font-size: 3rem; margin-bottom: 15px; color: rgba(255,255,255,0.1);"></i>
                <p>El contenido se previsualizará aquí.</p>
            </div>

            <div id="previewContent" class="preview-content">
                <div id="prevVideo" class="preview-video hidden"></div>
                <div id="prevPdf" class="hidden" style="padding: 20px; text-align: center; border-bottom: 1px solid var(--border-color); background: rgba(56, 189, 248, 0.1);">
                    <i class="fa-solid fa-file-pdf" style="font-size: 2rem; color: #38bdf8; margin-bottom: 10px;"></i>
                    <p style="margin:0; font-size: 0.9rem; color: var(--text-main);">Visor de PDF Integrado</p>
                </div>
                <div style="padding: 20px;">
                    <h2 id="prevTitle" style="margin-top:0; font-size: 1.3rem; color: var(--text-main);"></h2>
                    <div id="prevText" style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Scripts
ob_start();
?>
<script>
    const courseId = <?php echo $course['id']; ?>;
    
    // UI Elements
    const editorEmpty = document.getElementById('editorEmpty');
    const formModule = document.getElementById('formModule');
    const formLesson = document.getElementById('formLesson');
    const editorTitle = document.getElementById('editorTitle');
    
    // Drag and Drop (SortableJS)
    document.addEventListener('DOMContentLoaded', function() {
        // Sort modules
        const modulesList = document.getElementById('modulesList');
        if(modulesList) {
            new Sortable(modulesList, {
                animation: 150,
                handle: '.module-header',
                ghostClass: 'sortable-ghost',
                onEnd: function (evt) {
                    // Aquí en Fase 2 (AJAX) se enviará el nuevo orden al servidor
                    console.log('Módulos reordenados', evt.oldIndex, '->', evt.newIndex);
                }
            });
        }

        // Sort lessons inside modules
        document.querySelectorAll('.lessons-list').forEach(list => {
            new Sortable(list, {
                group: 'shared', // Permite mover lecciones entre módulos
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function (evt) {
                    console.log('Lección movida', evt.item);
                    // Actualizar el module_id si cambió de lista
                    const newModuleId = evt.to.getAttribute('data-module');
                    // En Fase 2 (AJAX) esto guarda el cambio
                }
            });
        });
    });

    function hideAllForms() {
        editorEmpty.classList.add('hidden');
        formModule.classList.add('hidden');
        formLesson.classList.add('hidden');
        document.querySelectorAll('.lesson-item').forEach(el => el.classList.remove('active'));
    }

    // Module Logic
    function openModuleForm() {
        hideAllForms();
        formModule.classList.remove('hidden');
        editorTitle.innerText = "Nuevo Módulo";
        document.getElementById('mod_id').value = '';
        formModule.reset();
        document.getElementById('mod_sort').value = document.querySelectorAll('.module-item').length + 1;
        document.getElementById('btnDeleteMod').style.display = 'none';
        
        showEmptyPreview();
    }

    function editModule(module) {
        hideAllForms();
        formModule.classList.remove('hidden');
        editorTitle.innerText = "Editar Módulo";
        
        document.getElementById('mod_id').value = module.id;
        document.getElementById('mod_title').value = module.title;
        document.getElementById('mod_desc').value = module.description || '';
        document.getElementById('mod_sort').value = module.sort_order;
        
        const delBtn = document.getElementById('btnDeleteMod');
        delBtn.style.display = 'block';
        delBtn.href = `index.php?action=admin_delete_module&id=${module.id}&course_id=${courseId}`;
        
        showEmptyPreview();
    }

    // Lesson Logic
    function openLessonForm(moduleId) {
        hideAllForms();
        formLesson.classList.remove('hidden');
        editorTitle.innerText = "Nueva Lección";
        formLesson.reset();
        
        document.getElementById('les_mod_id').value = moduleId;
        document.getElementById('les_id').value = '';
        document.getElementById('les_exist_pdf').value = '';
        document.getElementById('les_pdf_info').innerText = '';
        
        // Auto sort
        const lessonsInMod = document.querySelector(`.lessons-list[data-module="${moduleId}"]`);
        const count = lessonsInMod ? lessonsInMod.children.length : 0;
        document.getElementById('les_sort').value = count + 1;
        
        document.getElementById('btnDeleteLes').style.display = 'none';
        
        toggleResourceFields();
        updatePreview();
    }

    function editLesson(lesson, moduleId) {
        hideAllForms();
        
        // Highlight in list
        const listItem = document.querySelector(`.lesson-item[data-id="${lesson.id}"]`);
        if(listItem) listItem.classList.add('active');

        formLesson.classList.remove('hidden');
        editorTitle.innerText = "Editar Lección";
        
        document.getElementById('les_mod_id').value = moduleId;
        document.getElementById('les_id').value = lesson.id;
        document.getElementById('les_exist_pdf').value = lesson.pdf_url || '';
        
        document.getElementById('les_title').value = lesson.title;
        
        let type = 'none';
        if(lesson.video_type && lesson.video_type !== 'none') type = lesson.video_type;
        else if(lesson.pdf_url) type = 'pdf';
        
        document.getElementById('les_type').value = type;
        document.getElementById('les_video').value = lesson.video_url || '';
        
        const pdfInfo = document.getElementById('les_pdf_info');
        if(lesson.pdf_url) {
            pdfInfo.innerText = "Archivo actual: " + lesson.pdf_url.split('/').pop();
        } else {
            pdfInfo.innerText = "";
        }
        
        document.getElementById('les_content').value = lesson.content || '';
        document.getElementById('les_duration').value = lesson.duration_minutes;
        document.getElementById('les_sort').value = lesson.sort_order;
        document.getElementById('les_free').checked = (parseInt(lesson.is_free) === 1);
        
        const delBtn = document.getElementById('btnDeleteLes');
        delBtn.style.display = 'block';
        delBtn.href = `index.php?action=admin_delete_lesson&id=${lesson.id}&course_id=${courseId}`;

        toggleResourceFields();
        updatePreview();
    }

    function toggleResourceFields() {
        const type = document.getElementById('les_type').value;
        document.getElementById('fieldVideo').classList.add('hidden');
        document.getElementById('fieldPdf').classList.add('hidden');
        
        if (type === 'youtube') document.getElementById('fieldVideo').classList.remove('hidden');
        if (type === 'pdf') document.getElementById('fieldPdf').classList.remove('hidden');
    }

    // Live Preview
    function showEmptyPreview() {
        document.getElementById('previewEmpty').style.display = 'flex';
        document.getElementById('previewContent').style.display = 'none';
    }

    function updatePreview() {
        document.getElementById('previewEmpty').style.display = 'none';
        const contentBox = document.getElementById('previewContent');
        contentBox.style.display = 'flex';

        const title = document.getElementById('les_title').value || 'Título de la Lección';
        const type = document.getElementById('les_type').value;
        const videoUrl = document.getElementById('les_video').value;
        const content = document.getElementById('les_content').value;

        document.getElementById('prevTitle').innerText = title;
        document.getElementById('prevText').innerHTML = content.replace(/\n/g, '<br>') || '<em>Contenido vacío...</em>';

        const pVideo = document.getElementById('prevVideo');
        const pPdf = document.getElementById('prevPdf');
        
        pVideo.classList.add('hidden');
        pPdf.classList.add('hidden');

        if(type === 'youtube' && videoUrl) {
            pVideo.classList.remove('hidden');
            // Extract YT ID for preview
            let videoId = '';
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = videoUrl.match(regExp);
            if (match && match[2].length === 11) {
                videoId = match[2];
                pVideo.innerHTML = `<iframe width="100%" height="100%" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
            } else {
                pVideo.innerHTML = `<div style="display:flex; height:100%; align-items:center; justify-content:center;">Invalid YouTube URL</div>`;
            }
        } else if (type === 'pdf') {
            pPdf.classList.remove('hidden');
        }
    }
</script>
<?php
$extraScripts = ob_get_clean();

$pageTitle = 'Constructor: ' . $course['title'];
$activeMenu = 'courses';

require __DIR__ . '/../layouts/main.php';
