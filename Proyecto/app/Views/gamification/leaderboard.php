<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .rank-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .rank-card:hover {
            transform: translateX(5px);
            background: rgba(255,255,255,0.05);
            border-color: var(--primary-color);
        }
        .rank-card.top-1 { background: linear-gradient(90deg, rgba(251, 191, 36, 0.1), transparent); border-color: #fbbf24; }
        .rank-card.top-2 { background: linear-gradient(90deg, rgba(148, 163, 184, 0.1), transparent); border-color: #94a3b8; }
        .rank-card.top-3 { background: linear-gradient(90deg, rgba(180, 83, 9, 0.1), transparent); border-color: #b45309; }
        
        .rank-number {
            font-size: 2rem;
            font-weight: 900;
            width: 60px;
            text-align: center;
            color: var(--text-muted);
        }
        .top-1 .rank-number { color: #fbbf24; }
        .top-2 .rank-number { color: #94a3b8; }
        .top-3 .rank-number { color: #b45309; }

        .rank-user { flex: 1; padding-left: 15px; }
        .rank-user h4 { margin: 0; font-size: 1.2rem; }
        .rank-user span { color: var(--text-muted); font-size: 0.9rem; }

        .rank-stats { display: flex; gap: 2rem; align-items: center; }
        .stat-item { text-align: center; }
        .stat-val { font-size: 1.2rem; font-weight: 800; color: var(--primary-color); }
        .stat-lbl { font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=dashboard" class="logo">Learns class</a>
        <div class="links">
            <a href="index.php?action=dashboard">Cursos</a>
            <a href="index.php?action=leaderboard" style="color:var(--primary-color);">Leaderboard</a>
            <a href="index.php?action=badges">Mis Insignias</a>
            <a href="index.php?action=profile">Mi Perfil</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
            <div>
                <h2 style="margin:0;"><i class="fa-solid fa-trophy" style="color:#fbbf24;"></i> Clasificación Global</h2>
                <p class="text-muted" style="margin-top:0.5rem;">Compite con otros estudiantes ganando XP y manteniendo tus rachas.</p>
            </div>
            <div class="stat-card" style="display:flex; gap: 2rem; background:linear-gradient(135deg, var(--primary-color), var(--accent-color)); border:none; color:white;">
                <div style="text-align:center;">
                    <div style="font-size:1.5rem; font-weight:800;">#<?php echo $myRank; ?></div>
                    <div style="font-size:0.85rem; opacity:0.9;">Tu Posición</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.5rem; font-weight:800;"><?php echo number_format($myPoints); ?></div>
                    <div style="font-size:0.85rem; opacity:0.9;">Tus XP Totales</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.5rem; font-weight:800;"><i class="fa-solid fa-fire" style="color:#ffcc00;"></i> <?php echo $myStreak['current_streak'] ?? 0; ?></div>
                    <div style="font-size:0.85rem; opacity:0.9;">Tu Racha</div>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 30px;">
            <?php if (empty($leaderboard)): ?>
                <div style="text-align:center; padding: 2rem; color:var(--text-muted);">
                    Aún no hay usuarios en el leaderboard. ¡Sé el primero en ganar puntos!
                </div>
            <?php else: ?>
                <?php foreach ($leaderboard as $index => $u): 
                    $rank = $index + 1;
                    $class = '';
                    if ($rank === 1) $class = 'top-1';
                    elseif ($rank === 2) $class = 'top-2';
                    elseif ($rank === 3) $class = 'top-3';
                ?>
                    <div class="rank-card <?php echo $class; ?>">
                        <div class="rank-number">#<?php echo $rank; ?></div>
                        
                        <div style="width: 50px; height: 50px; border-radius: 50%; background:rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; overflow:hidden;">
                            <?php if(!empty($u['photo'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($u['photo']); ?>" style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <i class="fa-solid fa-user" style="color:var(--text-muted);"></i>
                            <?php endif; ?>
                        </div>

                        <div class="rank-user">
                            <h4><?php echo htmlspecialchars($u['username']); ?></h4>
                            <span><?php echo htmlspecialchars($u['full_name'] ?: 'Estudiante'); ?></span>
                        </div>

                        <div class="rank-stats">
                            <div class="stat-item">
                                <div class="stat-val" style="color:#f97316;"><i class="fa-solid fa-fire"></i> <?php echo $u['streak'] ?? 0; ?></div>
                                <div class="stat-lbl">Racha</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-val"><?php echo number_format($u['total_points']); ?> XP</div>
                                <div class="stat-lbl">Puntos</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
