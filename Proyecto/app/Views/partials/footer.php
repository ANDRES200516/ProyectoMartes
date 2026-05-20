<style>
/* Estilos integrados para asegurar compatibilidad en todas las vistas */
.premium-footer {
    margin-top: 50px;
    padding: 40px 10%;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: #94a3b8;
    font-family: 'Inter', sans-serif;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.footer-brand h3 {
    color: #f8fafc;
    font-size: 1.5rem;
    margin-bottom: 15px;
    font-weight: 700;
}

.footer-brand p {
    font-size: 0.95rem;
    line-height: 1.6;
}

.footer-links h4 {
    color: #e2e8f0;
    font-size: 1.1rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.footer-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #38bdf8;
}

.footer-contact i {
    margin-right: 10px;
    color: #38bdf8;
}

.footer-bottom {
    grid-column: 1 / -1;
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    font-size: 0.85rem;
    margin-top: 20px;
}
</style>

<footer class="premium-footer">
    <div class="footer-brand">
        <h3>Learns class</h3>
        <p>La plataforma de aprendizaje diseñada para ingenieros que no se conforman con lo básico. Algoritmos, lógica y futuro en un solo lugar.</p>
    </div>
    
    <div class="footer-links">
        <h4>Navegación</h4>
        <ul>
            <li><a href="index.php?action=dashboard">Mis Cursos</a></li>
            <li><a href="index.php?action=profile">Mi Perfil</a></li>
        </ul>
    </div>
    
    <div class="footer-links footer-contact">
        <h4>Soporte</h4>
        <ul>
            <li><i class="fa-solid fa-envelope"></i> soporte@learnclass.local</li>
            <li><i class="fa-solid fa-location-dot"></i> Centro de Innovación Tecnológica</li>
        </ul>
    </div>
    
    <div class="footer-bottom">
        &copy; <?php echo date('Y'); ?> Learns class. Reservados todos los derechos para mentes brillantes. <br>
        <span style="opacity: 0.7;">Desarrollado por Sebastian Hernandez</span>
    </div>
</footer>

<?php require __DIR__ . '/alerts.php'; ?>
