<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Configuración global para SweetAlert2 en Dark Mode
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#1e293b',
        color: '#f8fafc',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    <?php if (isset($_SESSION['swal_success'])): ?>
        Toast.fire({
            icon: 'success',
            title: '<?php echo htmlspecialchars($_SESSION['swal_success']); ?>'
        });
        <?php unset($_SESSION['swal_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['swal_error'])): ?>
        Swal.fire({
            icon: 'error',
            title: '¡Oops!',
            text: '<?php echo htmlspecialchars($_SESSION['swal_error']); ?>',
            background: '#1e293b',
            color: '#f8fafc',
            confirmButtonColor: '#38bdf8'
        });
        <?php unset($_SESSION['swal_error']); ?>
    <?php endif; ?>

    // Sobrescribir window.alert nativo
    window.alert = function(message) {
        Swal.fire({
            title: 'Información',
            text: message,
            icon: 'info',
            confirmButtonColor: '#38bdf8',
            background: '#1e293b',
            color: '#f8fafc'
        });
    };

    // Interceptor global de clics para atrapar onclick="return confirm(...)"
    document.addEventListener('click', function(e) {
        let target = e.target.closest('a, button, input[type="submit"]');
        if (!target) return;

        let onclickAttr = target.getAttribute('onclick');
        if (onclickAttr && (onclickAttr.includes('confirm(') || onclickAttr.includes('confirmUserAction('))) {
            e.preventDefault();
            e.stopImmediatePropagation();

            // Extraer el texto de confirmación
            let match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
            let message = match ? match[1] : '¿Estás seguro de realizar esta acción?';

            Swal.fire({
                title: '¿Confirmar acción?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // Verde
                cancelButtonColor: '#ef4444', // Rojo
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                background: '#1e293b',
                color: '#f8fafc'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Remover temporalmente para no volver a interceptar y ejecutar
                    target.removeAttribute('onclick');
                    if (target.tagName === 'A' && target.href) {
                        window.location.href = target.href;
                    } else if (target.form) {
                        target.form.submit();
                    } else {
                        target.click();
                    }
                }
            });
        }
    }, true); // Usar fase de captura
</script>
