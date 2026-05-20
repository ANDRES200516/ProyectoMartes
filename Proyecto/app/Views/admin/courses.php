<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .courses-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
            flex-wrap: wrap;
        }
        .filter-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            background: rgba(255,255,255,0.02);
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }
        .filter-form select, .filter-form input {
            background: #0f172a;
            color: #e2e8f0;
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: 6px;
            outline: none;
        }
        .filter-form select:focus, .filter-form input:focus {
            border-color: var(--primary-color);
        }
        .course-img-cell {
            width: 80px;
            height: 48px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            display: block;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table thead th { text-align: left; padding: 12px 10px; font-size: 0.9rem; color: var(--muted); }
        table tbody td { padding: 12px 10px; vertical-align: middle; border-top: 1px solid rgba(255,255,255,0.03); }
        table tbody tr:hover { background: rgba(56,189,248,0.02); }
        .btn-create {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        }
        .actions-cell {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .btn-action {
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-action.btn-edit { background-color: #3b82f6; }
        .btn-action.btn-edit:hover { background-color: #2563eb; }
        .btn-action.btn-duplicate { background-color: #8b5cf6; }
        .btn-action.btn-duplicate:hover { background-color: #7c3aed; }
        .btn-action.btn-content { background-color: #10b981; }
        .btn-action.btn-content:hover { background-color: #059669; }
        .btn-action.btn-delete { background-color: #ef4444; }
        .btn-action.btn-delete:hover { background-color: #dc2626; }
        .badge-level {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(59, 130, 246, 0.22);
            color: #ffffff;
            border: 1px solid rgba(59, 130, 246, 0.35);
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=admin_dashboard" class="logo">Learn class</a>
        <div class="links">
            <a href="index.php?action=admin_dashboard">Usuarios</a>
            <a href="index.php?action=admin_courses" style="color: var(--primary-color);">Cursos</a>
            <span style="margin-left: 15px; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 15px;">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <!-- Tarjetas de Estadísticas de Cursos -->
        <div class="stats-grid" style="margin-bottom: 30px;">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['total']; ?></span>
                    <span class="stat-label">Cursos Creados</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['active']; ?></span>
                    <span class="stat-label">Activos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-yellow"><i class="fa-solid fa-pen-ruler"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['draft']; ?></span>
                    <span class="stat-label">Borradores</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-red"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['total_enrollments']; ?></span>
                    <span class="stat-label">Matrículas Totales</span>
                </div>
            </div>
        </div>

        <div class="courses-actions">
            <h2>Gestión de Cursos</h2>
            <a href="index.php?action=admin_courses_create" class="btn-create">
                <i class="fa-solid fa-plus"></i> Crear Nuevo Curso
            </a>
        </div>

        <!-- Filtros de búsqueda -->
        <div style="margin-bottom: 20px;">
            <form action="index.php" method="GET" class="filter-form">
                <input type="hidden" name="action" value="admin_courses">
                
                <i class="fa-solid fa-filter" style="color: var(--primary-color);"></i>
                
                <input type="text" name="search" placeholder="Buscar curso o etiqueta..." value="<?php echo htmlspecialchars($filters['search']); ?>">
                
                <select name="level">
                    <option value="">Todos los niveles</option>
                    <option value="Básico" <?php echo $filters['level'] === 'Básico' ? 'selected' : ''; ?>>Básico</option>
                    <option value="Intermedio" <?php echo $filters['level'] === 'Intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                    <option value="Avanzado" <?php echo $filters['level'] === 'Avanzado' ? 'selected' : ''; ?>>Avanzado</option>
                </select>

                <select name="status">
                    <option value="">Todos los estados</option>
                    <option value="active" <?php echo $filters['status'] === 'active' ? 'selected' : ''; ?>>Activo</option>
                    <option value="draft" <?php echo $filters['status'] === 'draft' ? 'selected' : ''; ?>>Borrador</option>
                    <option value="inactive" <?php echo $filters['status'] === 'inactive' ? 'selected' : ''; ?>>Inactivo</option>
                </select>

                <select name="sort">
                    <option value="">Ordenar por fecha</option>
                    <option value="title" <?php echo $filters['sort'] === 'title' ? 'selected' : ''; ?>>Título (A-Z)</option>
                    <option value="students" <?php echo $filters['sort'] === 'students' ? 'selected' : ''; ?>>Más populares</option>
                    <option value="rating" <?php echo $filters['sort'] === 'rating' ? 'selected' : ''; ?>>Mejor calificados</option>
                </select>

                <button type="submit" class="btn" style="padding: 8px 15px;">Filtrar</button>
                <a href="index.php?action=admin_courses" class="btn btn-reject btn-sm" style="text-decoration: none; padding:6px 10px; font-size:0.9rem;">Limpiar</a>
            </form>
        </div>

        <!-- Tabla de Cursos -->
        <table>
            <thead>
                <tr>
                    <th>Miniatura</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Nivel</th>
                    <th>Módulos</th>
                    <th>Lecciones</th>
                    <th>Estudiantes</th>
                    <th>Rating</th>
                    <th>Estado</th>
                    <th style="width: 180px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($courses) > 0): ?>
                    <?php foreach($courses as $c): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars(\App\Models\Course::resolveThumbnail($c)); ?>" alt="Thumb" class="course-img-cell" onerror="this.src='assets/images/courses/default-thumb.svg'"></td>
                        <td>
                            <strong><?php echo htmlspecialchars($c['title']); ?></strong>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 3px;">
                                <i class="fa-solid fa-clock"></i> <?php echo $c['duration_hours']; ?>h
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($c['category'] ?? 'General'); ?></td>
                        <td>
                            <span class="badge-level" title="Nivel de curso">
                                <?php echo htmlspecialchars($c['level'] ?: 'Básico'); ?>
                            </span>
                        </td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $c['modules_count']; ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo $c['total_lessons']; ?></td>
                        <td style="text-align: center; font-weight: bold;">
                            <a href="index.php?action=admin_course_students&id=<?php echo $c['id']; ?>" class="course-students-card" style="display:inline-block;padding:8px 10px;border-radius:8px;background:var(--card-bg);border:1px solid var(--border-color);text-decoration:none;color:inherit;">
                                <div style="font-weight:700;"><?php echo $c['students_count']; ?></div>
                                <div style="font-size:0.8rem;color:var(--muted);">Estudiantes</div>
                            </a>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 4px; color: #fbbf24;">
                                <i class="fa-solid fa-star"></i>
                                <span style="font-weight: bold; color: #e2e8f0;"><?php echo number_format($c['rating_avg'], 1); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge <?php 
                                echo $c['status'] === 'active' ? 'badge-approved' : ($c['status'] === 'draft' ? 'badge-pending' : 'badge-suspended'); 
                            ?>">
                                <?php 
                                echo $c['status'] === 'active' ? 'Activo' : ($c['status'] === 'draft' ? 'Borrador' : 'Inactivo'); 
                                ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="index.php?action=admin_courses_edit&id=<?php echo $c['id']; ?>" class="btn-action btn-edit" title="Editar Metadatos"><i class="fa-solid fa-pen"></i></a>
                                <a href="index.php?action=admin_course_content&id=<?php echo $c['id']; ?>" class="btn-action btn-content" title="Gestionar Contenido (Módulos/Lecciones)"><i class="fa-solid fa-book-open"></i></a>
                                <!-- students button removed: count card links to students -->
                                <a href="index.php?action=admin_courses_duplicate&id=<?php echo $c['id']; ?>" class="btn-action btn-duplicate" title="Clonar Curso" onclick="return confirm('¿Clonar este curso y todo su contenido?')"><i class="fa-solid fa-copy"></i></a>
                                <a href="index.php?action=admin_courses_delete&id=<?php echo $c['id']; ?>" class="btn-action btn-delete" title="Eliminar Curso" onclick="return confirm('¿Estás seguro de eliminar permanentemente este curso con todos sus módulos y lecciones?')"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            <i class="fa-solid fa-face-frown" style="font-size: 2rem; margin-bottom: 10px;"></i><br>
                            No se encontraron cursos creados.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
