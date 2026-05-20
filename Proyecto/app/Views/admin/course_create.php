<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Curso - Learn class</title>
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
                <h2 style="margin:0;"><i class="fa-solid fa-plus-circle" style="color: var(--primary-color); margin-right: 8px;"></i> Crear Nuevo Curso</h2>
                <a href="index.php?action=admin_courses" class="btn btn-reject" style="text-decoration:none;"><i class="fa-solid fa-arrow-left"></i> Volver</a>
            </div>

            <form action="index.php?action=admin_courses_create" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    
                    <div class="form-group full-width">
                        <label for="title">Título del Curso</label>
                        <input type="text" id="title" name="title" required placeholder="Ej. Inteligencia Artificial: De Cero a Experto">
                    </div>

                    <div class="form-group">
                        <label for="category">Categoría</label>
                        <input type="text" id="category" name="category" required placeholder="Ej. Inteligencia Artificial, Programación, Ciencia de Datos">
                    </div>

                    <div class="form-group">
                        <label for="level">Nivel de Dificultad</label>
                        <select id="level" name="level" required>
                            <option value="Básico">Básico</option>
                            <option value="Intermedio">Intermedio</option>
                            <option value="Avanzado">Avanzado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="duration_hours">Duración Estimada (Horas)</label>
                        <input type="number" step="0.1" id="duration_hours" name="duration_hours" required placeholder="Ej. 40.5">
                    </div>

                    <div class="form-group">
                        <label for="status">Estado del Curso</label>
                        <select id="status" name="status" required>
                            <option value="draft" selected>Borrador (Oculto)</option>
                            <option value="active">Activo (Visible)</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="short_description">Descripción Corta</label>
                        <textarea id="short_description" name="short_description" rows="2" required placeholder="Un resumen atractivo de una línea para las tarjetas del catálogo..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Descripción Completa (Soporta HTML)</label>
                        <textarea id="description" name="description" rows="5" required placeholder="Explica detalladamente de qué trata el curso, la metodología y qué aprenderá el estudiante..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="requirements">Requisitos del Curso</label>
                        <textarea id="requirements" name="requirements" rows="2" placeholder="Ej. Conocimientos básicos de matemáticas.|Python básico (opcional)."></textarea>
                        <div class="help-text">Separa cada requisito con una barra vertical (|) para mostrarlos en forma de lista.</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="objectives">Objetivos de Aprendizaje</label>
                        <textarea id="objectives" name="objectives" rows="3" placeholder="Ej. Crear redes neuronales desde cero.|Entrenar algoritmos de clasificación."></textarea>
                        <div class="help-text">Separa cada objetivo con una barra vertical (|) para mostrarlos en forma de lista.</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="tags">Etiquetas / Tags</label>
                        <input type="text" id="tags" name="tags" placeholder="Ej. IA, Python, Machine Learning, Redes Neuronales">
                        <div class="help-text">Separa las etiquetas con comas.</div>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Miniatura del Curso (Imagen)</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                        <div class="help-text">Resolución sugerida: 800x450px (Relación de aspecto 16:9).</div>
                    </div>

                    <div class="form-group">
                        <label for="banner">Banner del Curso (Encabezado)</label>
                        <input type="file" id="banner" name="banner" accept="image/*">
                        <div class="help-text">Resolución sugerida: 1920x450px (Para portada premium).</div>
                    </div>

                </div>

                <div class="form-footer">
                    <a href="index.php?action=admin_courses" class="btn btn-reject" style="text-decoration:none;">Cancelar</a>
                    <button type="submit" class="btn"><i class="fa-solid fa-save"></i> Guardar Curso</button>
                </div>
            </form>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
