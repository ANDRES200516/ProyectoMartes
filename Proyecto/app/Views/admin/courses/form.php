<?php
// Determinar si es crear o editar
$isEdit = isset($course);
$actionUrl = $isEdit ? 'index.php?action=admin_courses_update' : 'index.php?action=admin_courses_store';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Editar Curso' : 'Nuevo Curso'; ?> - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
        .image-preview {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            border: 2px dashed rgba(255,255,255,0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-position: center;
            margin-top: 10px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=admin_dashboard" class="logo">Learn class</a>
        <div class="links">
            <a href="index.php?action=admin_courses"><i class="fa-solid fa-arrow-left"></i> Volver a Cursos</a>
        </div>
    </nav>
    
    <div class="container" style="max-width: 800px;">
        <div class="auth-card" style="max-width: 100%; animation: fadeIn 0.3s ease-out;">
            <h2><i class="fa-solid <?php echo $isEdit ? 'fa-pen-to-square' : 'fa-plus-circle'; ?>"></i> <?php echo $isEdit ? 'Editar Curso' : 'Crear Nuevo Curso'; ?></h2>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">Completa los detalles del curso educativo.</p>
            
            <form action="<?php echo $actionUrl; ?>" method="POST" enctype="multipart/form-data" id="courseForm">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                <?php endif; ?>
                
                <div class="form-grid">
                    <!-- Columna Izquierda -->
                    <div>
                        <div class="form-group">
                            <label>Título del Curso <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="title" value="<?php echo $isEdit ? htmlspecialchars($course['title']) : ''; ?>" required placeholder="Ej: Introducción a React">
                        </div>

                        <div class="form-group">
                            <label>Categoría</label>
                            <input type="text" name="category" value="<?php echo $isEdit ? htmlspecialchars($course['category']) : 'Tecnología'; ?>" placeholder="Ej: Programación">
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nivel</label>
                                <select name="level" class="btn-secondary" style="width: 100%; padding: 0.75rem; background: var(--bg-color); color: white; border: 1px solid var(--border-color); border-radius: 6px;">
                                    <option value="Básico" <?php echo ($isEdit && $course['level'] === 'Básico') ? 'selected' : ''; ?>>Básico</option>
                                    <option value="Intermedio" <?php echo ($isEdit && $course['level'] === 'Intermedio') ? 'selected' : ''; ?>>Intermedio</option>
                                    <option value="Avanzado" <?php echo ($isEdit && $course['level'] === 'Avanzado') ? 'selected' : ''; ?>>Avanzado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Duración (Horas)</label>
                                <input type="number" step="0.5" name="duration_hours" value="<?php echo $isEdit ? htmlspecialchars($course['duration_hours']) : '0'; ?>" min="0">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select name="status" class="btn-secondary" style="width: 100%; padding: 0.75rem; background: var(--bg-color); color: white; border: 1px solid var(--border-color); border-radius: 6px;">
                                <option value="active" <?php echo ($isEdit && $course['status'] === 'active') ? 'selected' : ''; ?>>Activo (Visible)</option>
                                <option value="inactive" <?php echo ($isEdit && $course['status'] === 'inactive') ? 'selected' : ''; ?>>Inactivo (Oculto)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div>
                        <div class="form-group">
                            <label>Descripción <span style="color: #ef4444;">*</span></label>
                            <textarea name="description" rows="5" required placeholder="Describe lo que los estudiantes aprenderán..." style="width: 100%; padding: 0.75rem; background: var(--bg-color); color: white; border: 1px solid var(--border-color); border-radius: 6px; resize: vertical;"><?php echo $isEdit ? htmlspecialchars($course['description']) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Imagen del Curso (Opcional)</label>
                            <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" class="btn-secondary" style="width: 100%; padding: 0.5rem; background: var(--bg-color); border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer;">
                            
                            <div class="image-preview" id="imagePreview" <?php if($isEdit && !empty($course['thumbnail'])): ?> style="background-image: url('<?php echo htmlspecialchars($course['thumbnail']); ?>'); border: none;" <?php endif; ?>>
                                <?php if(!$isEdit || empty($course['thumbnail'])): ?>
                                    <span>Vista previa de imagen</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 20px; display: flex; justify-content: flex-end; gap: 15px;">
                    <a href="index.php?action=admin_courses" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn" style="background-color: var(--primary-color);">
                        <i class="fa-solid fa-save"></i> <?php echo $isEdit ? 'Guardar Cambios' : 'Crear Curso'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview de imagen
        const thumbnailInput = document.getElementById('thumbnailInput');
        const imagePreview = document.getElementById('imagePreview');

        thumbnailInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    imagePreview.style.backgroundImage = `url('${this.result}')`;
                    imagePreview.style.border = 'none';
                    imagePreview.innerHTML = '';
                });
                reader.readAsDataURL(file);
            }
        });

        // Confirmación antes de enviar el formulario para creación
        <?php if (!$isEdit): ?>
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Crear curso?',
                text: "El curso estará disponible inmediatamente en el catálogo.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#38bdf8',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Sí, crear',
                cancelButtonText: 'Cancelar',
                background: '#1e293b',
                color: '#f8fafc'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
        <?php endif; ?>
    </script>

    <?php require __DIR__ . '/../../partials/footer.php'; ?>
</body>
</html>
