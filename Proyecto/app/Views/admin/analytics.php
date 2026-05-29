<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Premium - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav>
        <a href="index.php?action=admin_dashboard" class="logo">Learn class</a>
        <div class="links">
            <a href="index.php?action=admin_dashboard">Usuarios</a>
            <a href="index.php?action=admin_courses">Cursos</a>
            <a href="index.php?action=admin_analytics" style="color:var(--primary-color);">Analytics</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <h2 style="margin-bottom: 1.5rem;"><i class="fa-solid fa-chart-pie" style="color:var(--primary-color)"></i> Panel de Analytics Premium</h2>

        <!-- Stats Top -->
        <div class="stat-card" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="text-align:center;">
                <div style="font-size:2rem; font-weight:800; color:var(--primary-color)"><?php echo $stats['users']['total']; ?></div>
                <div class="text-muted">Total Usuarios</div>
                <div style="font-size:0.8rem; color:#10b981;">+<?php echo $stats['users']['new_this_week']; ?> esta semana</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:2rem; font-weight:800; color:#38bdf8;"><?php echo $stats['courses']['active']; ?></div>
                <div class="text-muted">Cursos Activos</div>
                <div style="font-size:0.8rem;">de <?php echo $stats['courses']['total']; ?> totales</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:2rem; font-weight:800; color:#a855f7;"><?php echo $stats['enrollments']['total']; ?></div>
                <div class="text-muted">Inscripciones Totales</div>
                <div style="font-size:0.8rem; color:#10b981;">+<?php echo $stats['enrollments']['last_30d']; ?> últimos 30 días</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:2rem; font-weight:800; color:#fbbf24;"><?php echo $stats['total_xp']; ?></div>
                <div class="text-muted">XP Otorgados Globales</div>
                <div style="font-size:0.8rem; color:#f59e0b;"><i class="fa-solid fa-star"></i> Engagement total</div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            
            <!-- Grafico Inscripciones -->
            <div class="card">
                <h3><i class="fa-solid fa-chart-line"></i> Inscripciones (Últimos 30 días)</h3>
                <canvas id="enrollmentsChart" height="120"></canvas>
            </div>

            <!-- Top Cursos -->
            <div class="card">
                <h3><i class="fa-solid fa-fire"></i> Cursos Populares</h3>
                <ul style="list-style:none; padding:0; margin-top:1rem; display:flex; flex-direction:column; gap:0.8rem;">
                    <?php foreach ($stats['top_courses'] as $tc): ?>
                        <li style="display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.03); padding:10px; border-radius:8px;">
                            <span style="font-weight:500; font-size:0.95rem;"><?php echo htmlspecialchars($tc['title']); ?></span>
                            <span class="badge" style="background:var(--primary-color);"><?php echo $tc['count']; ?> alumnos</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Leaderboard Top 5 -->
            <div class="card">
                <h3><i class="fa-solid fa-trophy"></i> Top 5 Usuarios Global (XP)</h3>
                <table style="margin-top:1rem;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Puntos XP</th>
                            <th>Racha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leaderboard as $index => $u): ?>
                            <tr>
                                <td><strong style="color:var(--primary-color);">#<?php echo $index + 1; ?></strong></td>
                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                <td><?php echo number_format($u['total_points']); ?> XP</td>
                                <td><i class="fa-solid fa-fire" style="color:#f97316;"></i> <?php echo $u['streak']; ?> días</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Grafico Niveles -->
            <div class="card">
                <h3><i class="fa-solid fa-chart-pie"></i> Distribución por Nivel</h3>
                <canvas id="levelsChart" height="180"></canvas>
            </div>
            
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <script>
        // Data for enrollments
        const chartData = <?php echo json_encode($stats['enrollments_chart']); ?>;
        const labels = chartData.map(item => item.day);
        const dataCounts = chartData.map(item => item.count);

        new Chart(document.getElementById('enrollmentsChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Inscripciones Nuevas',
                    data: dataCounts,
                    borderColor: '#7c4dff',
                    backgroundColor: 'rgba(124, 77, 255, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { 
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
                    x: { grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });

        // Data for Levels
        const levelsData = <?php echo json_encode($stats['levels_dist']); ?>;
        const levelLabels = levelsData.map(item => item.level);
        const levelCounts = levelsData.map(item => item.count);

        new Chart(document.getElementById('levelsChart'), {
            type: 'doughnut',
            data: {
                labels: levelLabels,
                datasets: [{
                    data: levelCounts,
                    backgroundColor: ['#10b981', '#38bdf8', '#fbbf24', '#f43f5e', '#a855f7'],
                    borderWidth: 0
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#e2e8f0' } }
                }
            }
        });
    </script>
</body>
</html>
