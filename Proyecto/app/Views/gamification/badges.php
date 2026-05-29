<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Insignias - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .badge-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 2rem;
        }
        .badge-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .badge-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 25px rgba(124, 77, 255, 0.2);
        }
        .badge-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 15px auto;
            background: rgba(255,255,255,0.05);
            box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
            border: 2px solid rgba(255,255,255,0.1);
        }
        .badge-card.earned .badge-icon {
            box-shadow: 0 0 30px var(--badge-color, var(--primary-color));
            border-color: var(--badge-color, var(--primary-color));
            color: white;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
        }
        .badge-card.locked {
            opacity: 0.6;
            filter: grayscale(100%);
        }
        .badge-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #f8fafc;
        }
        .badge-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.4;
        }
        .badge-date {
            font-size: 0.75rem;
            color: var(--primary-color);
            margin-top: 15px;
            font-weight: 600;
        }
        .locked-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            color: rgba(255,255,255,0.3);
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php?action=dashboard" class="logo">Learns class</a>
        <div class="links">
            <a href="index.php?action=dashboard">Cursos</a>
            <a href="index.php?action=leaderboard">Leaderboard</a>
            <a href="index.php?action=badges" style="color:var(--primary-color);">Mis Insignias</a>
            <a href="index.php?action=profile">Mi Perfil</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 10px;"><i class="fa-solid fa-medal" style="color:#38bdf8;"></i> Sala de Trofeos</h2>
            <p class="text-muted" style="max-width:600px; margin:0 auto;">Colecciona insignias completando cursos, manteniendo tus rachas de estudio y dominando los exámenes con puntuación perfecta.</p>
        </div>

        <div class="badge-grid">
            <?php foreach ($allBadges as $badge): ?>
                <?php 
                    $isEarned = (int)$badge['earned'] === 1;
                    $class = $isEarned ? 'earned' : 'locked';
                    $color = $badge['color'] ?: 'var(--primary-color)';
                ?>
                <div class="badge-card <?php echo $class; ?>" style="--badge-color: <?php echo $color; ?>">
                    <?php if(!$isEarned): ?>
                        <i class="fa-solid fa-lock locked-overlay"></i>
                    <?php endif; ?>
                    
                    <div class="badge-icon" style="<?php echo $isEarned ? 'background-color: '.$color : ''; ?>">
                        <i class="fa-solid <?php echo htmlspecialchars($badge['icon']); ?>"></i>
                    </div>
                    
                    <div class="badge-title"><?php echo htmlspecialchars($badge['name']); ?></div>
                    <div class="badge-desc"><?php echo htmlspecialchars($badge['description']); ?></div>
                    
                    <?php if($isEarned && !empty($badge['awarded_at'])): ?>
                        <div class="badge-date">Desbloqueado: <?php echo date('d M Y', strtotime($badge['awarded_at'])); ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
