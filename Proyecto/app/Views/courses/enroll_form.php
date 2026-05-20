<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Inscripción - <?php echo htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .survey-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .survey-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }
        .survey-header h2 {
            margin: 0 0 10px 0;
            color: #f8fafc;
            font-size: 1.6rem;
            font-weight: 700;
        }
        .survey-header p {
            color: var(--text-muted);
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            color: #e2e8f0;
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            background: #0f172a;
            color: #f8fafc;
            border: 1px solid var(--border-color);
            padding: 12px;
            border-radius: 8px;
            outline: none;
            box-sizing: border-box;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            border-color: var(--primary-color);
        }
        .form-group textarea {
            resize: vertical;
        }
        .form-footer {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 35px;
            border-top: 1px solid var(--border-color);
            padding-top: 25px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=dashboard" class="logo">Learns class</a>
        <div class="links">
            <a href="index.php?action=dashboard">Mis Cursos</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="survey-container">
            <div class="survey-header">
                <h2>Encuesta de Inscripción</h2>
                <p>Estás a punto de inscribirte en:<br><strong style="color: var(--primary-color);"><?php echo htmlspecialchars($course['title']); ?></strong></p>
                <p style="font-size: 0.85rem; margin-top: 10px; opacity: 0.8;"><i class="fa-solid fa-circle-info" style="color: var(--primary-color);"></i> Queremos conocer tus metas para guiar tu aprendizaje científico.</p>
            </div>

            <form action="index.php?action=enroll" method="POST">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">

                <div class="form-group">
                    <label for="motivation"><i class="fa-solid fa-fire" style="color: #f43f5e; margin-right: 6px;"></i> ¿Por qué te interesa este curso?</label>
                    <textarea id="motivation" name="motivation" rows="3" required placeholder="Ej. Deseo aprender lógica computacional y algoritmos aplicados para aplicarlo en mi trabajo de desarrollo de software..."></textarea>
                </div>

                <div class="form-group">
                    <label for="knowledge_level"><i class="fa-solid fa-gauge-high" style="color: #38bdf8; margin-right: 6px;"></i> ¿Cuál es tu nivel de conocimiento actual del tema?</label>
                    <select id="knowledge_level" name="knowledge_level" required>
                        <option value="Principiante">Principiante (Desde cero)</option>
                        <option value="Intermedio">Intermedio (Tengo bases previas)</option>
                        <option value="Avanzado">Avanzado (Quiero perfeccionarme)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="weekly_hours"><i class="fa-solid fa-clock" style="color: #fbbf24; margin-right: 6px;"></i> ¿Cuántas horas semanales vas a dedicarle?</label>
                    <input type="number" id="weekly_hours" name="weekly_hours" required min="1" max="168" value="5" placeholder="Ej. 6">
                </div>

                <div class="form-group">
                    <label for="main_goal"><i class="fa-solid fa-bullseye" style="color: #10b981; margin-right: 6px;"></i> ¿Cuál es tu meta principal al finalizar?</label>
                    <select id="main_goal" name="main_goal" required>
                        <option value="Mejorar habilidades profesionales">Mejorar habilidades profesionales (Crecimiento laboral)</option>
                        <option value="Crear un proyecto propio">Crear un proyecto propio (Emprendimiento / Startup)</option>
                        <option value="Conseguir un nuevo empleo">Conseguir un nuevo empleo (Cambio de carrera)</option>
                        <option value="Obtener la certificación científica">Obtener la certificación (Académico / Portafolio)</option>
                        <option value="Otro">Otro propósito</option>
                    </select>
                </div>

                <div class="form-footer">
                    <a href="index.php?action=course_details&course=<?php echo $course['slug']; ?>" class="btn btn-secondary" style="text-decoration:none;">Cancelar</a>
                    <button type="submit" class="btn"><i class="fa-solid fa-check-circle"></i> Inscribirse y Comenzar</button>
                </div>
            </form>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
