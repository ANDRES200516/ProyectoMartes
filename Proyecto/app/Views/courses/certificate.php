<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado Learns class - <?php echo htmlspecialchars($certificate['code']); ?></title>
    <!-- Outfit Font for premium branding -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #090d16;
            font-family: 'Outfit', sans-serif;
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Certificate Container */
        .certificate-wrapper {
            width: 842px; /* Landscape A4 ratio aspect */
            height: 595px;
            padding: 30px;
            box-sizing: border-box;
            background: linear-gradient(135deg, #0f172a 0%, #020617 100%);
            border: 8px double #fbbf24; /* Gold double border */
            border-radius: 8px;
            position: relative;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            overflow: hidden;
        }

        /* Ambient glowing circles behind */
        .certificate-wrapper::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            background: radial-gradient(circle, rgba(251, 191, 36, 0.04) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Gold corner decorations */
        .corner-decoration {
            position: absolute;
            width: 40px;
            height: 40px;
            border-color: #fbbf24;
            border-style: solid;
            pointer-events: none;
        }
        .top-left { top: 15px; left: 15px; border-width: 2px 0 0 2px; }
        .top-right { top: 15px; right: 15px; border-width: 2px 2px 0 0; }
        .bottom-left { bottom: 15px; left: 15px; border-width: 0 0 2px 2px; }
        .bottom-right { bottom: 15px; right: 15px; border-width: 0 2px 2px 0; }

        /* Typography & layout */
        .logo-diploma {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fbbf24;
            letter-spacing: 2px;
            margin-top: 15px;
        }
        
        .diploma-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-style: italic;
            font-weight: 600;
            color: #f8fafc;
            margin: 15px 0 0 0;
            letter-spacing: 1px;
        }

        .certify-text {
            font-size: 0.95rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 15px 0 5px 0;
        }

        .student-name {
            font-size: 2.2rem;
            font-weight: 800;
            color: #fbbf24;
            margin: 10px 0;
            border-bottom: 2px dashed rgba(251, 191, 36, 0.3);
            padding-bottom: 5px;
            min-width: 400px;
            display: inline-block;
        }

        .course-text {
            font-size: 1.05rem;
            color: #cbd5e1;
            line-height: 1.5;
            max-width: 600px;
            margin: 10px 0;
        }

        .course-title {
            color: #f8fafc;
            font-weight: 600;
            font-size: 1.25rem;
            display: block;
            margin-top: 5px;
        }

        .signatures-row {
            display: flex;
            justify-content: space-between;
            width: 80%;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .signature-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 180px;
        }
        .signature-line {
            width: 100%;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 8px;
        }
        .signature-title {
            font-size: 0.75rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cert-footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: #64748b;
            padding: 0 10px;
            box-sizing: border-box;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 10px;
        }

        /* Buttons block */
        .actions-bar {
            margin-top: 25px;
            display: flex;
            gap: 15px;
            z-index: 10;
        }
        
        .btn-print {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            color: #0f172a;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(251, 191, 36, 0.4);
        }
        
        .btn-back {
            background: rgba(255,255,255,0.05);
            color: #cbd5e1;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.1);
            color: #f8fafc;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #fff;
                color: #000;
            }
            .actions-bar {
                display: none !important;
            }
            .certificate-wrapper {
                box-shadow: none !important;
                border-color: #000 !important;
                background: #fff !important;
                color: #000 !important;
                position: absolute;
                top: 0;
                left: 0;
                margin: 0;
                width: 100%;
                height: 100%;
                page-break-after: avoid;
            }
            .diploma-title, .course-title, .student-name {
                color: #000 !important;
            }
            .student-name {
                border-bottom-color: #000 !important;
            }
            .certify-text, .course-text, .signature-title, .cert-footer {
                color: #333 !important;
            }
            .corner-decoration {
                border-color: #000 !important;
            }
            .logo-diploma {
                color: #000 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Contenedor del Certificado -->
    <div class="certificate-wrapper" id="certificate">
        <!-- Esquinas doradas -->
        <div class="corner-decoration top-left"></div>
        <div class="corner-decoration top-right"></div>
        <div class="corner-decoration bottom-left"></div>
        <div class="corner-decoration bottom-right"></div>

        <!-- Marca de agua / Logo -->
        <div class="logo-diploma">
            <i class="fa-solid fa-graduation-cap"></i> LEARNS CLASS
        </div>

        <div class="diploma-title">Certificado de Aceptación y Logro</div>
        
        <div class="certify-text">Se otorga con distinción a</div>
        
        <div class="student-name"><?php echo htmlspecialchars($certificate['student_name']); ?></div>
        
        <div class="course-text">
            Por haber completado con éxito y aprobado todos los requisitos prácticos y teóricos del curso de nivel <strong style="color: #fbbf24;"><?php echo htmlspecialchars($certificate['course_level']); ?></strong>:
            <strong class="course-title"><?php echo htmlspecialchars($certificate['course_title']); ?></strong>
            con una duración total de <strong><?php echo htmlspecialchars($certificate['duration_hours']); ?> horas</strong> lectivas registradas.
        </div>

        <!-- Firmas -->
        <div class="signatures-row">
            <div class="signature-col">
                <div style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: #fbbf24; font-style: italic; margin-bottom: 5px;">Sebastian Hernandez</div>
                <div class="signature-line"></div>
                <span class="signature-title">Director de la Plataforma</span>
            </div>
            <div class="signature-col">
                <div style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: #fbbf24; font-style: italic; margin-bottom: 5px;">Learns System</div>
                <div class="signature-line"></div>
                <span class="signature-title">Verificación Digital</span>
            </div>
        </div>

        <!-- ID de Validación y Fecha -->
        <div class="cert-footer">
            <span>Fecha de Emisión: <?php echo date('d-m-Y', strtotime($certificate['issued_at'])); ?></span>
            <span>Código de Validación: <?php echo htmlspecialchars($certificate['code']); ?></span>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="actions-bar">
        <a href="index.php?action=dashboard" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Volver a Mis Cursos</a>
        <button onclick="window.print()" class="btn-print"><i class="fa-solid fa-file-pdf"></i> Guardar como PDF / Imprimir</button>
    </div>

</body>
</html>
