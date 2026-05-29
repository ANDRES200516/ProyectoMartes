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

// Extra Head (Styles & CDN)
ob_start();
?>
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
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: 16px; width: 90%; max-width: 600px; padding: 25px;
        transform: translateY(-20px); transition: transform 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .modal-overlay.active .modal-content { transform: translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px; }
    .modal-header h2 { margin: 0; font-size: 1.4rem; color: var(--text-main); }
    .modal-close { background: none; border: none; color: var(--text-muted); font-size: 1.5rem; cursor: pointer; transition: color 0.2s; }
    .modal-close:hover { color: #f43f5e; }
    .rejected-list { max-height: 400px; overflow-y: auto; }
    .rejected-item {
        background: rgba(255,255,255,0.03); border-radius: 8px; padding: 15px; margin-bottom: 10px;
        display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(255,255,255,0.05);
    }
    .rejected-item-info h4 { margin: 0 0 5px 0; color: var(--text-main); }
    .rejected-item-info p { margin: 0; font-size: 0.9rem; color: var(--text-muted); }
    
    .stat-card.clickable { cursor: pointer; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-weight: 500; font-size: 0.8rem; }
    .badge-approved { background-color: rgba(16, 185, 129, 0.2); color: #10b981; }
    .badge-rejected { background-color: rgba(244, 63, 94, 0.2); color: #f43f5e; }
    .badge-pending { background-color: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .badge-suspended { background-color: rgba(100, 116, 139, 0.2); color: #94a3b8; }
    .badge-admin { background-color: rgba(139, 92, 246, 0.2); color: #8b5cf6; }
    .badge-user { background-color: rgba(59, 130, 246, 0.2); color: #3b82f6; }

    .btn-small { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:8px; color:#fff; text-decoration:none; transition: var(--transition); }
    .btn-small:hover { transform: scale(1.05); }
    .btn-approve { background: #10b981; }
    .btn-reject { background: #f43f5e; }
    .btn-suspend { background: #64748b; }
    
    .dashboard-charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }
    .chart-wrapper {
        position: relative;
        height: 280px;
        width: 100%;
    }
    
    /* Stats grid is somewhat defined in layout.css, but we refine it here for dashboard */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        position: relative;
    }
    
    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .icon-blue { background: rgba(59, 130, 246, 0.15); color: var(--primary); }
    .icon-yellow { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .icon-green { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    .icon-red { background: rgba(244, 63, 94, 0.15); color: #f43f5e; }
    
    .stat-info { display: flex; flex-direction: column; }
    .stat-value { font-size: 1.8rem; font-weight: 700; color: var(--text-main); line-height: 1.2; }
    .stat-label { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Tooltip */
    .stat-tooltip {
        position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%) translateY(100%);
        background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px;
        padding: 12px; min-width: 250px; max-height: 300px; overflow-y: auto; z-index: 99;
        opacity: 0; visibility: hidden; transition: opacity 0.2s ease, visibility 0.2s ease;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5); pointer-events: auto; margin-top: 8px;
    }
    .stat-card:hover .stat-tooltip { opacity: 1; visibility: visible; }
    .stat-tooltip-header { font-weight: 600; font-size: 0.9rem; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .stat-tooltip-item { padding: 6px 8px; background: rgba(255,255,255,0.02); border-radius: 6px; font-size: 0.85rem; color: var(--text-muted); border-left: 2px solid var(--primary); padding-left: 10px; margin-bottom: 6px; }
    .stat-tooltip-item strong { color: var(--text-main); }
    
    /* Tables */
    table { width: 100%; border-collapse: separate; border-spacing: 0 0.5rem; margin-top: 1rem; }
    th { padding: 1rem; text-align: left; color: var(--text-muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
    td { padding: 1rem; background: var(--bg-card); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
    td:first-child { border-left: 1px solid var(--border-color); border-radius: 12px 0 0 12px; }
    td:last-child { border-right: 1px solid var(--border-color); border-radius: 0 12px 12px 0; }
    tr:hover td { background: rgba(255,255,255,0.02); }
    
    .table-header-row { display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; margin-bottom: 1rem; }
    .table-header-row h2 { font-size: 1.5rem; margin: 0; }
    .search-box { position: relative; width: 300px; }
    .search-box i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
    .search-box input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; color: var(--text-main); }
    .search-box input:focus { border-color: var(--primary); outline: none; }
</style>
<?php
$extraHead = ob_get_clean();

// Content
ob_start();
?>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="premium-card stat-card" data-type="users" role="button" onclick="openCardModal('users')">
        <div class="stat-icon icon-blue"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <span class="stat-value"><?php echo $stats['total']; ?></span>
            <span class="stat-label">Total Usuarios</span>
        </div>
        <div class="stat-tooltip">
            <div class="stat-tooltip-header">Últimos Usuarios</div>
            <div class="stat-tooltip-list">
                <?php foreach(array_slice($users, 0, 5) as $u): ?>
                    <div class="stat-tooltip-item">
                        <strong><?php echo htmlspecialchars($u['username']); ?></strong><br><small><?php echo htmlspecialchars($u['email']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="premium-card stat-card" data-type="pending" role="button" onclick="openCardModal('pending')">
        <div class="stat-icon icon-yellow"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <span class="stat-value"><?php echo $stats['pending']; ?></span>
            <span class="stat-label">Pendientes</span>
        </div>
    </div>
    
    <div class="premium-card stat-card" data-type="approved" role="button" onclick="openCardModal('approved')">
        <div class="stat-icon icon-green"><i class="fa-solid fa-user-check"></i></div>
        <div class="stat-info">
            <span class="stat-value"><?php echo $stats['approved']; ?></span>
            <span class="stat-label">Aprobados</span>
        </div>
    </div>
    
    <div class="premium-card stat-card clickable" id="openRejectedModal">
        <div class="stat-icon icon-red"><i class="fa-solid fa-user-xmark"></i></div>
        <div class="stat-info">
            <span class="stat-value"><?php echo $stats['rejected']; ?></span>
            <span class="stat-label">Rechazados</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="dashboard-charts-grid">
    <div class="premium-card">
        <h3 style="margin-top:0; margin-bottom: 20px; font-size: 1.1rem;"><i class="fa-solid fa-chart-pie" style="color: var(--primary);"></i> Distribución de Usuarios</h3>
        <div class="chart-wrapper">
            <canvas id="usersChart"></canvas>
        </div>
    </div>
    <div class="premium-card">
        <h3 style="margin-top:0; margin-bottom: 20px; font-size: 1.1rem;"><i class="fa-solid fa-chart-bar" style="color: #10b981;"></i> Matrículas por Curso</h3>
        <div class="chart-wrapper">
            <canvas id="coursesChart"></canvas>
        </div>
    </div>
</div>

<!-- Table Users -->
<div class="table-header-row">
    <h2>Listado de Usuarios</h2>
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="userSearch" placeholder="Buscar usuario...">
    </div>
</div>

<div style="overflow-x: auto;">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <?php foreach($users as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['id']); ?></td>
                <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><span class="badge <?php echo $u['role'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>"><?php echo htmlspecialchars($u['role']); ?></span></td>
                <td><span class="badge <?php echo traducirBadgeEstado($u['status']); ?>"><?php echo htmlspecialchars(traducirEstado($u['status'])); ?></span></td>
                <td style="color: var(--text-muted); font-size: 0.9rem;"><?php echo htmlspecialchars(date('d/m/Y', strtotime($u['created_at']))); ?></td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <?php if ($u['status'] === 'pending'): ?>
                            <a href="index.php?action=approve_user&id=<?php echo $u['id']; ?>" class="btn-small btn-approve" title="Aprobar"><i class="fa-solid fa-check"></i></a>
                            <a href="index.php?action=reject_user&id=<?php echo $u['id']; ?>" class="btn-small btn-reject" title="Rechazar"><i class="fa-solid fa-xmark"></i></a>
                        <?php elseif ($u['status'] === 'rejected'): ?>
                            <a href="index.php?action=approve_user&id=<?php echo $u['id']; ?>" class="btn-small btn-approve" title="Re-Aprobar"><i class="fa-solid fa-check"></i></a>
                        <?php endif; ?>
                        
                        <a href="index.php?action=edit_user&id=<?php echo $u['id']; ?>" class="btn-small" style="background-color: var(--primary);" title="Editar"><i class="fa-solid fa-pen"></i></a>
                        
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <?php if ($u['status'] !== 'suspended'): ?>
                                <a href="index.php?action=suspend_user&id=<?php echo $u['id']; ?>" class="btn-small btn-suspend" title="Suspender" onclick="return confirm('¿Suspender?')"><i class="fa-solid fa-ban"></i></a>
                            <?php else: ?>
                                <a href="index.php?action=approve_user&id=<?php echo $u['id']; ?>" class="btn-small" title="Reactivar"><i class="fa-solid fa-user-check"></i></a>
                            <?php endif; ?>
                            <a href="index.php?action=delete_user&id=<?php echo $u['id']; ?>" class="btn-small btn-reject" title="Eliminar" onclick="return confirm('¿Eliminar permanentemente?')"><i class="fa-solid fa-trash"></i></a>
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
            <h2>Rechazados (<?php echo $stats['rejected']; ?>)</h2>
            <button class="modal-close" id="closeRejectedModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="rejected-list">
            <?php if (count($rejectedUsers) > 0): ?>
                <?php foreach($rejectedUsers as $ru): ?>
                    <div class="rejected-item">
                        <div class="rejected-item-info">
                            <h4><?php echo htmlspecialchars($ru['username']); ?></h4>
                            <p><?php echo htmlspecialchars($ru['email']); ?></p>
                        </div>
                        <a href="index.php?action=approve_user&id=<?php echo $ru['id']; ?>" class="btn-small btn-approve" title="Aprobar"><i class="fa-solid fa-check"></i></a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-muted); padding: 20px;">No hay solicitudes rechazadas.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Genérico -->
<div class="modal-overlay" id="cardModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="cardModalTitle">Detalle</h2>
            <button class="modal-close" id="closeCardModal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="cardModalBody">
            <div id="enrollmentsList" style="display:none;">
                <h3 style="margin-top:0;">Cursos y Matrículas</h3>
                <div style="display:flex;flex-direction:column;gap:10px;">
                <?php foreach($enrollmentStats as $es): ?>
                    <a href="index.php?action=admin_course_students&id=<?php echo $es['id']; ?>" class="premium-card" style="display:flex;justify-content:space-between;align-items:center;padding:12px;text-decoration:none;color:inherit;">
                        <div><?php echo htmlspecialchars($es['title']); ?></div>
                        <div style="font-weight:700;color:var(--primary);"><?php echo $es['enroll_count']; ?></div>
                    </a>
                <?php endforeach; ?>
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
    // Search
    const searchInput = document.getElementById('userSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('#userTableBody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // Modals
    const rejectedModal = document.getElementById('rejectedModal');
    const openRejectedModal = document.getElementById('openRejectedModal');
    const closeRejectedModal = document.getElementById('closeRejectedModal');

    if (openRejectedModal && rejectedModal) {
        openRejectedModal.addEventListener('click', () => rejectedModal.classList.add('active'));
    }
    if (closeRejectedModal && rejectedModal) {
        closeRejectedModal.addEventListener('click', () => rejectedModal.classList.remove('active'));
    }

    const cardModal = document.getElementById('cardModal');
    const closeCardModalBtn = document.getElementById('closeCardModal');
    
    function openCardModal(type){
        const titleMap = { users: 'Usuarios', pending: 'Solicitudes Pendientes', approved: 'Usuarios Aprobados', enrollments: 'Matrículas por Curso' };
        document.getElementById('cardModalTitle').innerText = titleMap[type] || 'Detalle';
        document.getElementById('enrollmentsList').style.display = (type === 'enrollments') ? 'block' : 'none';
        cardModal.classList.add('active');
    }
    if (closeCardModalBtn) closeCardModalBtn.addEventListener('click', ()=> cardModal.classList.remove('active'));
    
    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('active');
        });
    });

    // Charts
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
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
                backgroundColor: ['#10b981', '#f59e0b', '#f43f5e', '#64748b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#94a3b8', font: { family: 'Inter', size: 12 } } }
            },
            cutout: '70%'
        }
    });

    const coursesCtx = document.getElementById('coursesChart').getContext('2d');
    const courseLabels = <?php echo json_encode(array_column($enrollmentStats, 'title')); ?>;
    const courseData = <?php echo json_encode(array_column($enrollmentStats, 'enroll_count')); ?>;
    
    new Chart(coursesCtx, {
        type: 'bar',
        data: {
            labels: courseLabels.map(label => label.length > 25 ? label.substring(0, 25) + '...' : label),
            datasets: [{
                label: 'Matriculados',
                data: courseData,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { color: '#94a3b8', stepSize: 1 }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                x: { ticks: { color: '#94a3b8' }, grid: { display: false } }
            }
        }
    });
</script>
<?php
$extraScripts = ob_get_clean();

$pageTitle = 'Panel de Administración';
$activeMenu = 'users';

require __DIR__ . '/../layouts/main.php';
