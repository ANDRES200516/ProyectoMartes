<?php
if (!function_exists('traducirEstado')) {
    function traducirEstado($estado) {
        $estados = [
            'approved' => 'Aprobado',
            'rejected' => 'Rechazado',
            'suspended' => 'Suspendido',
            'pending' => 'Pendiente'
        ];
        return $estados[$estado] ?? ucfirst((string) $estado);
    }
}
if (!function_exists('traducirBadgeEstado')) {
    function traducirBadgeEstado($estado) {
        $clases = [
            'approved' => 'badge-approved',
            'rejected' => 'badge-rejected',
            'suspended' => 'badge-suspended',
            'pending' => 'badge-pending'
        ];
        return $clases[$estado] ?? 'badge-pending';
    }
}

// Fetch rejected users for the modal
$rejectedUsers = array_values(array_filter($users, function($u) {
    return $u['status'] === 'rejected';
}));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Plataforma de Cursos</title>
    <link rel="stylesheet" href="assets/style.css">
    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Modal Styles */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px);
            display: flex; justify-content: center; align-items: center;
            opacity: 0; pointer-events: none; transition: opacity 0.3s ease; z-index: 1000;
        }
        .modal-overlay.active { opacity: 1; pointer-events: auto; }
        .modal-content {
            background: var(--card-bg); border: 1px solid var(--border-color);
            border-radius: 12px; width: 90%; max-width: 600px; padding: 25px;
            transform: translateY(-20px); transition: transform 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .modal-overlay.active .modal-content { transform: translateY(0); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px; }
        .modal-header h2 { margin: 0; font-size: 1.4rem; color: #f8fafc; }
        .modal-close { background: none; border: none; color: var(--text-muted); font-size: 1.5rem; cursor: pointer; transition: color 0.2s; }
        .modal-close:hover { color: #f87171; }
        .rejected-list { max-height: 400px; overflow-y: auto; }
        .rejected-item {
            background: rgba(255,255,255,0.03); border-radius: 8px; padding: 15px; margin-bottom: 10px;
            display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(255,255,255,0.05);
        }
        .rejected-item-info h4 { margin: 0 0 5px 0; color: #e2e8f0; }
        .rejected-item-info p { margin: 0; font-size: 0.9rem; color: var(--text-muted); }
        .stat-card.clickable { cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card.clickable:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(248, 113, 113, 0.2); border-color: rgba(248, 113, 113, 0.4); }
        .badge { transition: all 0.3s ease; display: inline-block; padding: 4px 10px; border-radius: 12px; font-weight: 500; }
        .badge:hover { transform: translateY(-2px); filter: brightness(1.1); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .badge-suspended { background-color: rgba(249, 115, 22, 0.2); color: #fdba74; border: 1px solid rgba(249, 115, 22, 0.3); }
        .btn-small { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:6px; color:#fff; text-decoration:none; }
        .btn-suspend { background: #fb923c; border: none; }
        .btn-suspend:hover { filter: brightness(0.95); }
        
        /* Charts Grid Styling */
        .dashboard-charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .chart-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .chart-card h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #f1f5f9;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .chart-wrapper {
            position: relative;
            height: 280px;
            width: 100%;
        }
        /* Tooltip/Popover Styles */
        .stat-card {
            position: relative;
        }
        .stat-tooltip {
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%) translateY(100%);
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            min-width: 250px;
            max-width: 350px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            pointer-events: auto;
            margin-top: 8px;
        }
        .stat-card:hover .stat-tooltip {
            opacity: 1;
            visibility: visible;
        }
        .stat-tooltip-header {
            font-weight: 600;
            font-size: 0.9rem;
            color: #f1f5f9;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .stat-tooltip-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .stat-tooltip-item {
            padding: 6px 8px;
            background: rgba(255,255,255,0.02);
            border-radius: 4px;
            font-size: 0.85rem;
            color: #cbd5e1;
            border-left: 2px solid var(--primary-color);
            padding-left: 10px;
        }
        .stat-tooltip-item strong {
            color: #f1f5f9;
        }
        .stat-tooltip-empty {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            padding: 10px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=admin_dashboard" class="logo">Learn class</a>
        <div class="links">
            <a href="index.php?action=admin_dashboard" style="color: var(--primary-color);">Usuarios</a>
            <a href="index.php?action=admin_courses">Cursos</a>
            <span style="margin-left: 15px; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 15px;">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            
            <!-- Campana de Notificaciones -->
            <div class="notification-bell" id="bellBtn">
                <i class="fa-solid fa-bell"></i>
                <?php if ($pendingCount > 0): ?>
                    <span class="bell-badge"><?php echo $pendingCount; ?></span>
                <?php endif; ?>

                <!-- Menu Desplegable -->
                <div class="notification-dropdown" id="bellDropdown">
                    <div class="dropdown-header">Solicitudes Pendientes</div>
                    <div class="dropdown-body">
                        <?php if ($pendingCount > 0): ?>
                            <?php foreach($pendingUsers as $pu): ?>
                                <div class="dropdown-item">
                                    <div class="item-info">
                                        <strong><?php echo htmlspecialchars($pu['username']); ?></strong>
                                        <span><?php echo htmlspecialchars($pu['email']); ?></span>
                                    </div>
                                    <div class="item-actions">
                                        <a href="index.php?action=approve_user&id=<?php echo $pu['id']; ?>" class="approve-link" title="Aprobar"><i class="fa-solid fa-check"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="dropdown-empty">No hay solicitudes nuevas</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>
    
    <div class="container">
        
        <!-- Sección de Estadísticas Dinámicas -->
        <div class="stats-grid">
            <div class="stat-card" data-type="users" role="button" onclick="openCardModal('users')">
                <div class="stat-icon icon-blue"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['total']; ?></span>
                    <span class="stat-label">Total Usuarios</span>
                </div>
                <div class="stat-tooltip">
                    <div class="stat-tooltip-header">Todos los Usuarios (<?php echo count($users); ?>)</div>
                    <div class="stat-tooltip-list">
                        <?php if (count($users) > 0): ?>
                            <?php foreach(array_slice($users, 0, 10) as $u): ?>
                                <div class="stat-tooltip-item">
                                    <strong><?php echo htmlspecialchars($u['username']); ?></strong>
                                    <br><small><?php echo htmlspecialchars($u['email']); ?></small>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($users) > 10): ?>
                                <div class="stat-tooltip-item" style="text-align: center; border-left: none; padding: 8px; color: var(--text-muted);">
                                    ... y <?php echo count($users) - 10; ?> más
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="stat-tooltip-empty">Sin usuarios</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="stat-card" data-type="pending" role="button" onclick="openCardModal('pending')">
                <div class="stat-icon icon-yellow"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['pending']; ?></span>
                    <span class="stat-label">Pendientes</span>
                </div>
                <div class="stat-tooltip">
                    <div class="stat-tooltip-header">Solicitudes Pendientes (<?php echo count($pendingUsers); ?>)</div>
                    <div class="stat-tooltip-list">
                        <?php if (count($pendingUsers) > 0): ?>
                            <?php foreach(array_slice($pendingUsers, 0, 10) as $pu): ?>
                                <div class="stat-tooltip-item">
                                    <strong><?php echo htmlspecialchars($pu['username']); ?></strong>
                                    <br><small><?php echo htmlspecialchars($pu['email']); ?></small>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($pendingUsers) > 10): ?>
                                <div class="stat-tooltip-item" style="text-align: center; border-left: none; padding: 8px; color: var(--text-muted);">
                                    ... y <?php echo count($pendingUsers) - 10; ?> más
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="stat-tooltip-empty">Sin solicitudes pendientes</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="stat-card" data-type="approved" role="button" onclick="openCardModal('approved')">
                <div class="stat-icon icon-green"><i class="fa-solid fa-user-check"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['approved']; ?></span>
                    <span class="stat-label">Aprobados</span>
                </div>
                <div class="stat-tooltip">
                    <div class="stat-tooltip-header">Usuarios Aprobados (<?php echo $stats['approved']; ?>)</div>
                    <div class="stat-tooltip-list">
                        <?php 
                            $approvedUsers = array_filter($users, function($u) {
                                return $u['status'] === 'approved';
                            });
                        ?>
                        <?php if (count($approvedUsers) > 0): ?>
                            <?php foreach(array_slice($approvedUsers, 0, 10) as $au): ?>
                                <div class="stat-tooltip-item">
                                    <strong><?php echo htmlspecialchars($au['username']); ?></strong>
                                    <br><small><?php echo htmlspecialchars($au['email']); ?></small>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($approvedUsers) > 10): ?>
                                <div class="stat-tooltip-item" style="text-align: center; border-left: none; padding: 8px; color: var(--text-muted);">
                                    ... y <?php echo count($approvedUsers) - 10; ?> más
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="stat-tooltip-empty">Sin usuarios aprobados</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="stat-card clickable" id="openRejectedModal">
                <div class="stat-icon icon-red"><i class="fa-solid fa-user-xmark"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['rejected']; ?></span>
                    <span class="stat-label">Rechazados</span>
                </div>
                <div class="stat-tooltip">
                    <div class="stat-tooltip-header">Usuarios Rechazados (<?php echo count($rejectedUsers); ?>)</div>
                    <div class="stat-tooltip-list">
                        <?php if (count($rejectedUsers) > 0): ?>
                            <?php foreach(array_slice($rejectedUsers, 0, 10) as $ru): ?>
                                <div class="stat-tooltip-item">
                                    <strong><?php echo htmlspecialchars($ru['username']); ?></strong>
                                    <br><small><?php echo htmlspecialchars($ru['email']); ?></small>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($rejectedUsers) > 10): ?>
                                <div class="stat-tooltip-item" style="text-align: center; border-left: none; padding: 8px; color: var(--text-muted);">
                                    ... y <?php echo count($rejectedUsers) - 10; ?> más
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="stat-tooltip-empty">Sin usuarios rechazados</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Gráficos Estadísticos -->
        <div class="dashboard-charts-grid">
            <div class="chart-card">
                <h3><i class="fa-solid fa-chart-pie" style="color: var(--primary-color);"></i> Distribución de Usuarios</h3>
                <div class="chart-wrapper">
                    <canvas id="usersChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3><i class="fa-solid fa-chart-bar" style="color: #10b981;"></i> Matrículas por Curso</h3>
                <div class="chart-wrapper">
                    <canvas id="coursesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Modal Genérico para tarjetas -->
        <div class="modal-overlay" id="cardModal">
            <div class="modal-content" style="max-width:800px;">
                <div class="modal-header">
                    <h2 id="cardModalTitle">Detalle</h2>
                    <button class="modal-close" id="closeCardModal"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div id="cardModalBody">
                    <!-- Contenido dinámico: para 'enrollments' mostramos cursos con conteo -->
                    <div id="enrollmentsList" style="display:none;">
                        <h3 style="margin-top:0;">Cursos y Matrículas</h3>
                        <div style="display:flex;flex-direction:column;gap:10px;">
                        <?php foreach($enrollmentStats as $es): ?>
                            <a href="index.php?action=admin_course_students&id=<?php echo $es['id']; ?>" class="card" style="display:flex;justify-content:space-between;align-items:center;padding:12px;border-radius:8px;background:var(--card-bg);border:1px solid var(--border-color);text-decoration:none;color:inherit;">
                                <div><?php echo htmlspecialchars($es['title']); ?></div>
                                <div style="font-weight:700;color:var(--primary);"><?php echo $es['enroll_count']; ?></div>
                            </a>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Solicitudes Pendientes -->
        <?php if ($pendingCount > 0): ?>
        <div class="pending-section">
            <h2>🔔 Solicitudes Pendientes (<?php echo $pendingCount; ?>)</h2>
            <p>Los siguientes usuarios necesitan tu aprobación para acceder a la plataforma.</p>
            
            <div class="grid">
                <?php foreach($pendingUsers as $pu): ?>
                <div class="card card-pending">
                    <h3><?php echo htmlspecialchars($pu['username']); ?></h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($pu['email']); ?></p>
                    <p><strong>Fecha:</strong> <?php echo htmlspecialchars($pu['created_at']); ?></p>
                    <div class="action-buttons">
                        <a href="index.php?action=approve_user&id=<?php echo $pu['id']; ?>" class="btn btn-approve">✓ Aprobar</a>
                        <a href="index.php?action=reject_user&id=<?php echo $pu['id']; ?>" class="btn btn-reject">✗ Rechazar</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Tabla de Todos los Usuarios -->
        <div class="table-header-row">
            <h2>Todos los Usuarios</h2>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="userSearch" placeholder="Buscar por nombre o email...">
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['id']); ?></td>
                    <td><?php echo htmlspecialchars($u['username']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td>
                        <span class="badge <?php echo $u['role'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                            <?php echo htmlspecialchars($u['role']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?php echo traducirBadgeEstado($u['status']); ?>">
                            <?php echo htmlspecialchars(traducirEstado($u['status'])); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <?php if ($u['status'] === 'pending'): ?>
                                <a href="index.php?action=approve_user&id=<?php echo $u['id']; ?>" class="btn-small btn-approve" title="Aprobar"><i class="fa-solid fa-check"></i></a>
                                <a href="index.php?action=reject_user&id=<?php echo $u['id']; ?>" class="btn-small btn-reject" title="Rechazar"><i class="fa-solid fa-xmark"></i></a>
                            <?php elseif ($u['status'] === 'rejected'): ?>
                                <a href="index.php?action=approve_user&id=<?php echo $u['id']; ?>" class="btn-small btn-approve" title="Re-Aprobar"><i class="fa-solid fa-check"></i></a>
                            <?php endif; ?>
                            
                            <a href="index.php?action=edit_user&id=<?php echo $u['id']; ?>" class="btn-small" style="background-color: var(--primary-color);" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                            
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <?php if ($u['status'] !== 'suspended'): ?>
                                    <a href="index.php?action=suspend_user&id=<?php echo $u['id']; ?>" class="btn-small btn-suspend" title="Suspender" onclick="return confirm('¿Suspender a este usuario?')"><i class="fa-solid fa-user-slash"></i></a>
                                <?php else: ?>
                                    <a href="index.php?action=approve_user&id=<?php echo $u['id']; ?>" class="btn-small" title="Reactivar"><i class="fa-solid fa-user-check"></i></a>
                                <?php endif; ?>

                                <a href="index.php?action=delete_user&id=<?php echo $u['id']; ?>" class="btn-small btn-reject" title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Rechazados -->
    <div class="modal-overlay" id="rejectedModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Solicitudes Rechazadas (<?php echo $stats['rejected']; ?>)</h2>
                <button class="modal-close" id="closeRejectedModal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="rejected-list">
                <?php if (count($rejectedUsers) > 0): ?>
                    <?php foreach($rejectedUsers as $ru): ?>
                        <div class="rejected-item">
                            <div class="rejected-item-info">
                                <h4><?php echo htmlspecialchars($ru['username']); ?></h4>
                                <p><?php echo htmlspecialchars($ru['email']); ?></p>
                                <p style="font-size: 0.8rem; margin-top: 3px;"><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($ru['created_at']); ?></p>
                            </div>
                            <a href="index.php?action=approve_user&id=<?php echo $ru['id']; ?>" class="btn-small btn-approve" title="Aprobar"><i class="fa-solid fa-check"></i> Aprobar</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-muted); padding: 20px;">No hay solicitudes rechazadas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Toggle Dropdown de notificaciones
        document.getElementById('bellBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('bellDropdown').classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('bellDropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Búsqueda Dinámica en Tiempo Real
        document.getElementById('userSearch').addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('#userTableBody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });

        // Modal Logic
        const rejectedModal = document.getElementById('rejectedModal');
        const openRejectedModal = document.getElementById('openRejectedModal');
        const closeRejectedModal = document.getElementById('closeRejectedModal');

        if (openRejectedModal && rejectedModal) {
            openRejectedModal.addEventListener('click', () => {
                rejectedModal.classList.add('active');
            });
        }

        if (closeRejectedModal && rejectedModal) {
            closeRejectedModal.addEventListener('click', () => {
                rejectedModal.classList.remove('active');
            });
        }

        if (rejectedModal) {
            rejectedModal.addEventListener('click', (e) => {
                if (e.target === rejectedModal) {
                    rejectedModal.classList.remove('active');
                }
            });
        }

        // Tarjetas del dashboard: abrir modal genérico
        const cardModal = document.getElementById('cardModal');
        const closeCardModalBtn = document.getElementById('closeCardModal');
        function openCardModal(type){
            const titleMap = { users: 'Usuarios', pending: 'Solicitudes Pendientes', approved: 'Usuarios Aprobados', enrollments: 'Matrículas por Curso' };
            document.getElementById('cardModalTitle').innerText = titleMap[type] || 'Detalle';
            // Mostrar lista de matrículas si es necesario
            document.getElementById('enrollmentsList').style.display = (type === 'enrollments') ? 'block' : 'none';
            cardModal.classList.add('active');
        }
        closeCardModalBtn && closeCardModalBtn.addEventListener('click', ()=> cardModal.classList.remove('active'));
        cardModal && cardModal.addEventListener('click', (e)=>{ if(e.target === cardModal) cardModal.classList.remove('active'); });

        // --- CHART.JS CONFIGURATIONS ---
        
        // 1. Users Status Doughnut Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        const usersChart = new Chart(usersCtx, {
            type: 'doughnut',
            data: {
                labels: ['Aprobados', 'Pendientes', 'Rechazados', 'Suspendidos'],
                datasets: [{
                    data: [
                        <?php echo $stats['approved']; ?>,
                        <?php echo $stats['pending']; ?>,
                        <?php echo $stats['rejected']; ?>,
                        <?php echo $stats['suspended']; ?>
                    ],
                    backgroundColor: [
                        '#10b981', // green
                        '#f59e0b', // amber
                        '#ef4444', // red
                        '#64748b'  // slate
                    ],
                    borderWidth: 1,
                    borderColor: '#1e293b'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            font: { family: 'Inter', size: 12 }
                        }
                    }
                }
            }
        });

        // 2. Course Enrollments Bar Chart
        const coursesCtx = document.getElementById('coursesChart').getContext('2d');
        const courseLabels = <?php echo json_encode(array_column($enrollmentStats, 'title')); ?>;
        const courseData = <?php echo json_encode(array_column($enrollmentStats, 'enroll_count')); ?>;
        
        const coursesChart = new Chart(coursesCtx, {
            type: 'bar',
            data: {
                labels: courseLabels.map(label => label.length > 25 ? label.substring(0, 25) + '...' : label),
                datasets: [{
                    label: 'Estudiantes Matriculados',
                    data: courseData,
                    backgroundColor: 'rgba(56, 189, 248, 0.4)',
                    borderColor: '#38bdf8',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#94a3b8', stepSize: 1 },
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    x: {
                        ticks: { color: '#94a3b8', maxRotation: 45, minRotation: 45 },
                        grid: { display: false }
                    }
                }
            }
        });
    </script>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
