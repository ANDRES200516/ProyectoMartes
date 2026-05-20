<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes Matriculados - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .students-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }
        .student-photo-cell {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .progress-bar-container {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border-radius: 6px;
            overflow: hidden;
            height: 8px;
            border: 1px solid rgba(255,255,255,0.02);
            margin-top: 5px;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), #10b981);
            border-radius: 6px;
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
        <div class="students-header">
            <div>
                <span style="font-size: 0.85rem; text-transform: uppercase; color: var(--primary-color); font-weight: bold; letter-spacing: 1px;">Reporte de Matrículas</span>
                <h2 style="margin: 5px 0 0 0;"><?php echo htmlspecialchars($course['title']); ?></h2>
            </div>
            <a href="index.php?action=admin_courses" class="btn btn-reject btn-sm" style="text-decoration:none; padding:6px 10px; font-size:0.95rem;"><i class="fa-solid fa-arrow-left"></i> Volver</a>
        </div>

        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin:0; color: #e2e8f0;"><i class="fa-solid fa-users" style="color: var(--primary-color); margin-right: 8px;"></i> Estudiantes Inscritos (<?php echo count($students); ?>)</h3>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="studentSearch" placeholder="Buscar por nombre o email...">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Email</th>
                    <th>Progreso</th>
                    <th>Estado de Cursada</th>
                    <th>Fecha de Inscripción</th>
                    <th>Fecha de Finalización</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <?php $studentPhoto = !empty($student['photo']) ? 'uploads/' . htmlspecialchars($student['photo']) : 'assets/images/default-avatar.png'; ?>
                        <img src="<?php echo $studentPhoto; ?>" alt="Avatar" class="student-photo-cell" onerror="this.src='assets/images/default-avatar.png'">
                                <strong><?php echo htmlspecialchars($student['full_name'] ?? 'Usuario'); ?></strong>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td>
                            <div style="display:flex; flex-direction:column; width: 150px;">
                                <span style="font-size: 0.85rem; font-weight: bold; color: #f8fafc;"><?php echo number_format($student['progress_percentage'], 1); ?>%</span>
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" style="width: <?php echo $student['progress_percentage']; ?>%;"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?php echo $student['status'] === 'completed' ? 'badge-approved' : 'badge-pending'; ?>">
                                <?php echo $student['status'] === 'completed' ? 'Completado' : 'Cursando'; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($student['enrolled_at']); ?></td>
                        <td>
                            <?php echo $student['completed_at'] ? htmlspecialchars($student['completed_at']) : '<span style="opacity: 0.5;">-</span>'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            <i class="fa-solid fa-circle-info" style="font-size: 2rem; margin-bottom: 10px; color: var(--primary-color);"></i><br>
                            Ningún estudiante se ha inscrito en este curso todavía.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Búsqueda en tiempo real
        document.getElementById('studentSearch').addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('#studentTableBody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
