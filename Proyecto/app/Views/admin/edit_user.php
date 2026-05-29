<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Learn class</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav>
        <a href="index.php?action=admin_dashboard" class="logo">Learn class</a>
        <div class="links">
            <a href="index.php?action=admin_dashboard">Volver al Panel</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>
    
    <div class="container" style="max-width: 600px;">
        <div class="auth-card" style="max-width: 100%; animation: fadeIn 0.3s ease-out;">
            <h2><i class="fa-solid fa-user-pen"></i> Editar Usuario</h2>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">Editando a: <strong><?php echo htmlspecialchars($user->username); ?></strong></p>
            
            <form action="index.php?action=update_user" method="POST">
                <?php echo \App\Helpers\Security::csrfField(); ?>
                <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                
                <div class="form-group">
                    <label>Nombre Completo</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user->full_name); ?>">
                </div>

                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user->phone); ?>">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Rol de Usuario</label>
                        <select name="role" class="btn-secondary" style="width: 100%; padding: 0.75rem; background: var(--bg-color); color: white; border: 1px solid var(--border-color); border-radius: 6px;">
                            <option value="user" <?php echo $user->role === 'user' ? 'selected' : ''; ?>>Usuario</option>
                            <option value="admin" <?php echo $user->role === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Estado de Cuenta</label>
                        <select name="status" class="btn-secondary" style="width: 100%; padding: 0.75rem; background: var(--bg-color); color: white; border: 1px solid var(--border-color); border-radius: 6px;">
                            <option value="pending" <?php echo $user->status === 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="approved" <?php echo $user->status === 'approved' ? 'selected' : ''; ?>>Aprobado</option>
                            <option value="rejected" <?php echo $user->status === 'rejected' ? 'selected' : ''; ?>>Rechazado</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn">Actualizar Usuario</button>
                    <a href="index.php?action=admin_dashboard" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
