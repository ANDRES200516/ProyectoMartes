<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Plataforma de Cursos</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav>
        <a href="index.php?action=dashboard" class="logo">Learns class</a>
        <div class="links">
            <a href="index.php?action=dashboard">Cursos</a>
            <a href="index.php?action=profile">Mi Perfil</a>
            <a href="index.php?action=logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.2rem;">
            <h2>Mi Perfil</h2>
            <div class="stat-card" style="display:flex; gap:1rem; align-items:center;">
                <div style="text-align:center; min-width:140px;">
                    <div style="font-size:1.2rem; font-weight:800"><?php echo count($enrollments); ?></div>
                    <div class="text-muted">Cursos Inscritos</div>
                </div>
                <div style="text-align:center; min-width:140px;">
                    <?php
                        $avg = 0; if (count($enrollments) > 0) { foreach ($enrollments as $e) $avg += floatval($e['progress_percentage']); $avg = round($avg / count($enrollments)); }
                    ?>
                    <div style="font-size:1.2rem; font-weight:800"><?php echo $avg; ?>%</div>
                    <div class="text-muted">Progreso promedio</div>
                </div>
                <div style="text-align:center; min-width:140px;">
                    <div style="font-size:1.2rem; font-weight:800"><?php echo count($certificates); ?></div>
                    <div class="text-muted">Certificados</div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if ($success === 'data_updated'): ?>
            <div class="alert alert-success">Datos actualizados correctamente.</div>
        <?php elseif ($success === 'photo_updated'): ?>
            <div class="alert alert-success">Foto de perfil actualizada correctamente.</div>
        <?php elseif ($success === 'password_updated'): ?>
            <div class="alert alert-success">Contraseña actualizada correctamente.</div>
        <?php elseif ($success === 'current_incorrect'): ?>
            <div class="alert alert-danger">La contraseña actual no coincide.</div>
        <?php elseif ($success === 'password_mismatch'): ?>
            <div class="alert alert-danger">La nueva contraseña y la confirmación no coinciden.</div>
        <?php elseif ($success === 'password_short'): ?>
            <div class="alert alert-danger">La contraseña debe tener al menos 8 caracteres.</div>
        <?php elseif ($success === 'invalid_email'): ?>
            <div class="alert alert-danger">El correo electrónico no es válido.</div>
        <?php endif; ?>

        <div style="display:grid; grid-template-columns: 320px 1fr; gap:2rem; align-items:start;">
            <!-- SIDEBAR PERFIL -->
            <aside>
                <div class="card" style="text-align:center;">
                    <div style="display:flex; flex-direction:column; align-items:center; gap:1rem;">
                        <div style="width:140px; height:140px; border-radius:50%; overflow:hidden; background:rgba(255,255,255,0.03); display:flex; align-items:center; justify-content:center;">
                            <?php if (!empty($user->photo)): ?>
                                <img src="uploads/<?php echo htmlspecialchars($user->photo); ?>" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
                            <?php else: ?>
                                <div style="font-size:3rem; font-weight:800; color:var(--text-muted);"><?php echo strtoupper(substr($user->username,0,1)); ?></div>
                            <?php endif; ?>
                        </div>
                        <h3 style="margin-bottom:0.2rem"><?php echo htmlspecialchars($user->full_name ?: $user->username); ?></h3>
                        <div class="text-muted"><?php echo htmlspecialchars($user->email); ?></div>
                        <div style="margin-top:0.6rem; display:flex; gap:0.5rem;">
                            <label for="photo" class="btn" style="padding:0.6rem 1rem; font-size:0.9rem;">Cambiar Foto</label>
                        </div>
                        <form action="index.php?action=update_photo" method="POST" enctype="multipart/form-data" id="avatarForm">
                            <?php echo \App\Helpers\Security::csrfField(); ?>
                            <input type="file" id="photo" name="photo" accept="image/*" style="display:none;">
                        </form>
                    </div>
                </div>

                <div class="card" style="margin-top:1rem;">
                    <h4 style="margin-bottom:0.8rem">Actividad reciente</h4>
                    <?php if (empty($notifications)): ?>
                        <div class="text-muted">No hay actividad reciente.</div>
                    <?php else: ?>
                        <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.6rem;">
                            <?php foreach ($notifications as $n): ?>
                                <li style="font-size:0.95rem;">
                                    <strong><?php echo htmlspecialchars($n['type']); ?>:</strong> <?php echo htmlspecialchars($n['message']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </aside>

            <!-- CONTENIDO PRINCIPAL -->
            <main>
                <div class="card">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <h3>Resumen</h3>
                        <div class="text-muted">Modo oscuro activo • Glassmorphism</div>
                    </div>
                    <p class="text-muted" style="margin-top:0.6rem">Gestiona tu información, seguridad y revisa tus cursos y certificados.</p>
                </div>

                <div style="margin-top:1rem; display:flex; gap:1rem;">
                    <button class="btn" id="tabOverview">Cursos</button>
                    <button class="btn" id="tabCertificates" style="background:transparent; border:1px solid var(--border-color); color:var(--text-muted);">Certificados</button>
                    <button class="btn" id="tabSecurity" style="background:transparent; border:1px solid var(--border-color); color:var(--text-muted);">Seguridad</button>
                </div>

                <div id="panelCourses" style="margin-top:1rem;">
                    <div class="grid">
                        <?php if (empty($enrollments)): ?>
                            <div class="card">No estás inscrito en ningún curso.</div>
                        <?php else: ?>
                            <?php foreach ($enrollments as $e): ?>
                                <div class="card" style="display:flex; gap:1rem; align-items:center;">
                                    <div style="width:120px; height:80px; overflow:hidden; border-radius:8px;">
                                        <?php if (!empty($e['course_thumbnail'])): ?>
                                            <img src="uploads/courses/<?php echo htmlspecialchars($e['course_thumbnail']); ?>" style="width:100%; height:100%; object-fit:cover;">
                                        <?php else: ?>
                                            <div style="background:linear-gradient(135deg,var(--primary-color),var(--accent-color)); width:100%; height:100%;"></div>
                                        <?php endif; ?>
                                    </div>
                                    <div style="flex:1">
                                        <h4 style="margin:0 0 0.3rem 0"><?php echo htmlspecialchars($e['course_title']); ?></h4>
                                        <div style="height:10px; background:rgba(255,255,255,0.05); border-radius:8px; overflow:hidden; margin-top:0.6rem;">
                                            <div style="width:<?php echo intval($e['progress_percentage']); ?>%; height:100%; background:linear-gradient(90deg,var(--primary-color),var(--accent-color));"></div>
                                        </div>
                                        <div style="display:flex; justify-content:space-between; margin-top:0.6rem; align-items:center;">
                                            <div class="text-muted"><?php echo intval($e['progress_percentage']); ?>% • <?php echo intval($e['total_lessons']); ?> lecciones</div>
                                            <div>
                                                <a class="btn" href="index.php?action=learn&course=<?php echo $e['course_id']; ?>">Continuar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="panelCertificates" style="margin-top:1rem; display:none;">
                    <div class="card">
                        <h3>Mis Certificados</h3>
                        <?php if (empty($certificates)): ?>
                            <div class="text-muted">Aún no tienes certificados completados.</div>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>Emitido</th>
                                        <th>Código</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($certificates as $c): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($c['course_title']); ?></td>
                                            <td><?php echo htmlspecialchars($c['created_at'] ?? $c['issued_at'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($c['code']); ?></td>
                                            <td><a class="btn" href="index.php?action=certificate&code=<?php echo urlencode($c['code']); ?>" target="_blank">Ver / Descargar</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="panelSecurity" style="margin-top:1rem; display:none;">
                    <div class="card">
                        <h3>Cambiar contraseña</h3>
                        <form action="index.php?action=update_password" method="POST" style="margin-top:0.8rem; max-width:480px;">
                            <?php echo \App\Helpers\Security::csrfField(); ?>
                            <div class="form-group">
                                <label for="current_password">Contraseña actual</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Nueva contraseña</label>
                                <input type="password" id="new_password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirmar contraseña</label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button class="btn" type="submit">Actualizar contraseña</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>

    <script>
        // Tabs
        const tabOverview = document.getElementById('tabOverview');
        const tabCertificates = document.getElementById('tabCertificates');
        const tabSecurity = document.getElementById('tabSecurity');
        const panelCourses = document.getElementById('panelCourses');
        const panelCertificates = document.getElementById('panelCertificates');
        const panelSecurity = document.getElementById('panelSecurity');

        tabOverview.onclick = () => { panelCourses.style.display='block'; panelCertificates.style.display='none'; panelSecurity.style.display='none'; };
        tabCertificates.onclick = () => { panelCourses.style.display='none'; panelCertificates.style.display='block'; panelSecurity.style.display='none'; };
        tabSecurity.onclick = () => { panelCourses.style.display='none'; panelCertificates.style.display='none'; panelSecurity.style.display='block'; };

        // Avatar upload trigger
        const avatarInput = document.getElementById('photo');
        document.querySelector('label[for="photo"]').addEventListener('click', ()=> avatarInput.click());
        avatarInput.addEventListener('change', ()=> document.getElementById('avatarForm').submit());
    </script>
</body>
</html>
