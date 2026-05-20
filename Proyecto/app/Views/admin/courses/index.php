<?php
if (!function_exists('traducirBadgeEstado')) {
    function traducirBadgeEstado($estado) {
        $clases = [
            'active' => 'badge-approved', // Verde
            'inactive' => 'badge-rejected', // Rojo
            'pending' => 'badge-pending'
        ];
        return $clases[$estado] ?? 'badge-pending';
    }
    
    function traducirEstado($estado) {
        $estados = [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
        ];
        return $estados[$estado] ?? ucfirst((string) $estado);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .badge { transition: all 0.3s ease; display: inline-block; padding: 4px 10px; border-radius: 12px; font-weight: 500; }
        .badge:hover { transform: translateY(-2px); filter: brightness(1.1); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .course-thumb { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1); }
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
        <!-- Dashboard Header -->
        <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h1>Gestión de Cursos</h1>
                <p>Administra el contenido educativo de la plataforma</p>
            </div>
            <a href="index.php?action=admin_courses_create" class="btn" style="background: var(--primary-color);"><i class="fa-solid fa-plus"></i> Nuevo Curso</a>
        </div>

        <!-- Sección de Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="fa-solid fa-book-open"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['total']; ?></span>
                    <span class="stat-label">Total Cursos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="fa-solid fa-check-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['active']; ?></span>
                    <span class="stat-label">Cursos Activos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-red"><i class="fa-solid fa-times-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['inactive']; ?></span>
                    <span class="stat-label">Cursos Inactivos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-yellow"><i class="fa-solid fa-user-graduate"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['total_enrollments']; ?></span>
                    <span class="stat-label">Inscripciones</span>
                </div>
            </div>
        </div>

        <!-- Tabla de Cursos -->
        <div class="table-header-row">
            <h2>Listado de Cursos</h2>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Portada</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Nivel</th>
                    <th>Módulos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($courses) > 0): ?>
                    <?php foreach($courses as $c): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($c['thumbnail']); ?>" class="course-thumb" alt="Thumbnail"></td>
                        <td><strong><?php echo htmlspecialchars($c['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($c['category']); ?></td>
                        <td><?php echo htmlspecialchars($c['level']); ?></td>
                        <td><?php echo htmlspecialchars($c['modules_count']); ?></td>
                        <td>
                            <span class="badge <?php echo traducirBadgeEstado($c['status']); ?>">
                                <?php echo htmlspecialchars(traducirEstado($c['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="index.php?action=admin_courses_edit&id=<?php echo $c['id']; ?>" class="btn-small" style="background-color: var(--primary-color);" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="#" onclick="confirmDelete(<?php echo $c['id']; ?>)" class="btn-small btn-reject" title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">No hay cursos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará el curso y todas sus inscripciones permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#1e293b',
                color: '#f8fafc'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php?action=admin_courses_delete&id=' + id;
                }
            })
        }
    </script>

    <?php require __DIR__ . '/../../partials/footer.php'; ?>
</body>
</html>
