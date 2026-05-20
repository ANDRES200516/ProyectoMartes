<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
            max-width: 800px;
            margin: 30px auto;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            display: block;
            color: #e2e8f0;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            background: #0f172a;
            color: #e2e8f0;
            border: 1px solid var(--border-color);
            padding: 10px 12px;
            border-radius: 6px;
            outline: none;
            box-sizing: border-box;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            border-color: var(--primary-color);
        }
        .form-group textarea {
            resize: vertical;
        }
        .form-header {
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .form-footer {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }
        .help-text {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 5px;
        }
        .preview-img {
            max-width: 150px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            margin-top: 10px;
            display: block;
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
        <div class="form-container">
            <div class="form-header">
                <h2 style="margin:0;"><i class="fa-solid fa-pen-to-square" style="color: var(--primary-color); margin-right: 8px;"></i> Editar Curso: <?php echo htmlspecialchars($course['title']); ?></h2>
                <a href="index.php?action=admin_courses" class="btn btn-reject" style="text-decoration:none;"><i class="fa-solid fa-arrow-left"></i> Volver</a>
            </div>

            <form action="index.php?action=admin_courses_edit&id=<?php echo $course['id']; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    
                    <div class="form-group full-width">
                        <label for="title">Título del Curso</label>
                        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($course['title']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="category">Categoría</label>
                        <input type="text" id="category" name="category" required value="<?php echo htmlspecialchars($course['category'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="level">Nivel de Dificultad</label>
                        <select id="level" name="level" required>
                            <option value="Básico" <?php echo $course['level'] === 'Básico' ? 'selected' : ''; ?>>Básico</option>
                            <option value="Intermedio" <?php echo $course['level'] === 'Intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                            <option value="Avanzado" <?php echo $course['level'] === 'Avanzado' ? 'selected' : ''; ?>>Avanzado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="duration_hours">Duración Estimada (Horas)</label>
                        <input type="number" step="0.1" id="duration_hours" name="duration_hours" required value="<?php echo htmlspecialchars($course['duration_hours']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="status">Estado del Curso</label>
                        <select id="status" name="status" required>
                            <option value="draft" <?php echo $course['status'] === 'draft' ? 'selected' : ''; ?>>Borrador (Oculto)</option>
                            <option value="active" <?php echo $course['status'] === 'active' ? 'selected' : ''; ?>>Activo (Visible)</option>
                            <option value="inactive" <?php echo $course['status'] === 'inactive' ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="short_description">Descripción Corta</label>
                        <textarea id="short_description" name="short_description" rows="2" required><?php echo htmlspecialchars($course['short_description']); ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Descripción Completa (Soporta HTML)</label>
                        <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($course['description']); ?></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="requirements">Requisitos del Curso</label>
                        <textarea id="requirements" name="requirements" rows="2"><?php echo htmlspecialchars($course['requirements'] ?? ''); ?></textarea>
                        <div class="help-text">Separa cada requisito con una barra vertical (|) para mostrarlos en forma de lista.</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="objectives">Objetivos de Aprendizaje</label>
                        <textarea id="objectives" name="objectives" rows="3"><?php echo htmlspecialchars($course['objectives'] ?? ''); ?></textarea>
                        <div class="help-text">Separa cada objetivo con una barra vertical (|) para mostrarlos en forma de lista.</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="tags">Etiquetas / Tags</label>
                        <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($course['tags'] ?? ''); ?>">
                        <div class="help-text">Separa las etiquetas con comas.</div>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Miniatura del Curso (Imagen)</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <?php if ($course['thumbnail']): ?>
                            <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" alt="Current Thumb" class="preview-img">
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="banner">Banner del Curso (Encabezado)</label>
                        <input type="file" id="banner" name="banner" accept="image/*">
                        <?php if ($course['banner']): ?>
                            <img src="<?php echo htmlspecialchars($course['banner']); ?>" alt="Current Banner" class="preview-img">
                        <?php endif; ?>
                    </div>

                </div>

                <div class="form-footer">
                    <a href="index.php?action=admin_courses" class="btn btn-reject" style="text-decoration:none;">Cancelar</a>
                    <button type="submit" class="btn"><i class="fa-solid fa-save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
