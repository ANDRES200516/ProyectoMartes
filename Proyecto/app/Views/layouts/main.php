<?php
// Default variables
$pageTitle = $pageTitle ?? 'Learns class';
$activeMenu = $activeMenu ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Learns class</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    
    <!-- Base Styles -->
    <link rel="stylesheet" href="assets/style.css">
    <!-- Premium Layout Styles -->
    <link rel="stylesheet" href="assets/layout.css">
    
    <!-- Extra Head Content -->
    <?= $extraHead ?? '' ?>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="app-sidebar">
            <div class="sidebar-header">
                <a href="index.php?action=<?= $_SESSION['role'] === 'admin' ? 'admin_dashboard' : 'dashboard' ?>" class="sidebar-logo">Learns class</a>
            </div>
            <nav class="sidebar-nav">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?action=admin_dashboard" class="sidebar-nav-item <?= $activeMenu === 'users' ? 'active' : '' ?>">
                        <i class="fa-solid fa-users"></i> Usuarios
                    </a>
                    <a href="index.php?action=admin_courses" class="sidebar-nav-item <?= $activeMenu === 'courses' ? 'active' : '' ?>">
                        <i class="fa-solid fa-book-open"></i> Gestión de Cursos
                    </a>
                <?php else: ?>
                    <a href="index.php?action=dashboard" class="sidebar-nav-item <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">
                        <i class="fa-solid fa-house"></i> Mi Panel
                    </a>
                    <a href="index.php?action=profile" class="sidebar-nav-item <?= $activeMenu === 'profile' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user"></i> Mi Perfil
                    </a>
                <?php endif; ?>
                <!-- Más opciones genéricas aquí (ej. Ajustes, Notificaciones) -->
                <a href="index.php?action=logout" class="sidebar-nav-item" style="margin-top: auto; color: var(--danger-color, #f43f5e);">
                    <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <!-- Topbar -->
            <header class="app-topbar">
                <div class="topbar-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Buscar cursos, usuarios...">
                </div>
                
                <div class="topbar-actions">
                    <!-- Notifications -->
                    <div class="notification-bell" id="topbarBell">
                        <i class="fa-solid fa-bell"></i>
                        <span class="bell-badge" style="display:none;" id="topbarBellBadge">0</span>
                    </div>

                    <!-- User Avatar -->
                    <div class="user-avatar-wrapper">
                        <?php 
                            $initials = isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 2)) : 'U';
                            $role = isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : 'User';
                        ?>
                        <div class="user-avatar"><?= $initials ?></div>
                        <div class="user-info">
                            <span class="user-name"><?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?></span>
                            <span class="user-role"><?= $role ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="app-content">
                <?= $content ?>
            </div>
        </main>
    </div>

    <!-- Layout Scripts -->
    <script>
        // Funcionalidad base para el sidebar o notificaciones
    </script>

    <!-- Extra Scripts -->
    <?= $extraScripts ?? '' ?>
    <?php require_once __DIR__ . '/../partials/alerts.php'; ?>
</body>
</html>
