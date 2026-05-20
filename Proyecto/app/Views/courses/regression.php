<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regresión Lineal - Learns class</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-dark text-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom border-secondary px-4">
        <a href="index.php" class="navbar-brand fw-bold text-info">Learns class</a>
        <div class="ms-auto">
            <a href="index.php" class="btn btn-outline-light btn-sm">Salir del Curso</a>
        </div>
    </nav>
    
    <div class="container-fluid course-layout py-4">
        <aside class="course-sidebar p-3">
            <div class="mb-4">
                <div class="progress bg-dark border border-secondary" style="height: 10px;">
                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo ($module / 3) * 100; ?>%;"></div>
                </div>
                <p class="small text-secondary mt-2">Progreso: <?php echo round(($module / 3) * 100); ?>%</p>
            </div>

            <h3>Módulos del Curso</h3>
            <ul class="module-list">
                <li class="<?php echo $module === 1 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 1 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 1. La Recta de Regresión</li>
                <li class="<?php echo $module === 2 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 2 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 2. Predicción de Datos</li>
                <li class="<?php echo $module === 3 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 3 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 3. Laboratorio Práctico</li>
            </ul>

            <div class="time-meta">
                <i class="fa-solid fa-hourglass-half"></i>
                <span>Tiempo estimado: 2 horas</span>
            </div>
        </aside>

        <main class="course-content">
            <?php if ($module === 1): ?>
                <div class="course-header">
                    <span class="tag tag-blue">Módulo 1: Fundamentos Estadísticos</span>
                    <h2>La Recta Predictiva: ¿Qué es la Regresión?</h2>
                    <p>Aprende a encontrar la línea matemática que mejor describe el comportamiento de tus datos para prever el futuro.</p>
                </div>
                <div class="content-body">
                    <p>La <strong>Regresión Lineal Simple</strong> es la herramienta base del Machine Learning predictivo. Su objetivo es modelar la relación entre dos variables: una variable independiente (X, la causa) y una variable dependiente (Y, el efecto).</p>

                    <h4 class="mt-4 text-info">La Ecuación del Destino (Y = mX + b)</h4>
                    <p>Todo el modelo se resume en una línea recta. Imagina que quieres predecir el precio de un apartamento:</p>
                    <ul style="color: #cfcfcf; line-height: 1.8; margin-bottom: 2rem;">
                        <li><strong>Y (Predicción):</strong> El precio final que queremos calcular.</li>
                        <li><strong>X (Entrada):</strong> El dato que ya tenemos (ej. los metros cuadrados).</li>
                        <li><strong>m (Pendiente):</strong> El "peso" o importancia. ¿Cuánto aumenta el precio por cada metro extra?</li>
                        <li><strong>b (Sesgo/Base):</strong> El precio de partida. El valor de la propiedad incluso si tuviera 0 metros construidos (valor del suelo).</li>
                    </ul>

                    <div class="card p-3 mt-4" style="border-color: var(--primary-color);">
                        <h5 class="text-info"><i class="fas fa-chart-line"></i> Intuición Visual</h5>
                        <p class="small mb-0">Imagina un gráfico con muchos puntos dispersos. La regresión lineal es como tomar una cuerda y tratar de pasarla por medio de todos los puntos, de modo que la distancia total entre la cuerda y los puntos sea la mínima posible.</p>
                    </div>

                    <div style="margin-top: 3rem;">
                        <a href="index.php?action=course_regression&module=2" class="btn">Módulo 2: El Arte de Predecir <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 2): ?>
                <div class="course-header">
                    <span class="tag tag-blue">Módulo 2: Inferencia de Datos</span>
                    <h2>Predicción de Datos (Inferencia)</h2>
                    <p>Usa tu modelo matemático entrenado para predecir valores en el mundo real.</p>
                </div>
                <div class="content-body">
                    <p>Una vez que el algoritmo ha encontrado la línea de mejor ajuste, ha comprimido todo ese conocimiento histórico en la simple ecuación <strong>Y = mX + b</strong>. Ahora podemos introducirle valores nuevos (X) y nos devolverá predicciones automáticas.</p>

                    <h4 class="mt-4 text-info">Correlación no es Causalidad</h4>
                    <p>Es el error más común de los principiantes. Que dos cosas suban al mismo tiempo no significa que una cause la otra. Por ejemplo, la venta de helados y los incendios forestales suben juntos en verano, pero los helados no causan incendios (la causa común es el calor).</p>

                    <div class="demo-container mt-4 mb-4">
                        <h4 class="text-info mb-3">Calculadora de Predicciones en Tiempo Real</h4>
                        <p class="small text-secondary mb-3">Hemos entrenado un modelo para calcular el tiempo de viaje basado en la distancia. La IA determinó que la fórmula es <strong>2 minutos por km + 5 minutos de espera</strong>.</p>
                        
                        <form method="POST" action="index.php?action=course_regression&module=2" class="interactive-form">
                            <div class="input-row">
                                <div class="input-group">
                                    <label>Distancia a viajar (Km)</label>
                                    <input type="number" name="x_value" placeholder="Ej: 10" required>
                                </div>
                            </div>
                            <button type="submit" name="submit_regression" class="btn mt-3">Interpolar Predicción</button>
                        </form>
                        
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_regression'])) {
                            $x = (float)$_POST['x_value'];
                            $y = (2 * $x) + 5;
                            echo "<div class='neuron-result active mt-4'>
                                    <i class='fa-solid fa-bolt'></i>
                                    <span>Predicción: El viaje de $x km tardará <strong>$y minutos</strong>.</span>
                                  </div>";
                        }
                        ?>
                    </div>
                    <div style="margin-top: 2rem;">
                        <a href="index.php?action=course_regression&module=3" class="btn">Módulo 3: Proyecto de Negocios <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 3): ?>
                <div class="course-header">
                    <span class="tag tag-blue">Módulo 3: Proyecto Final</span>
                    <h2>Laboratorio: Predicción de Ventas</h2>
                    <p>Estima cuánto venderá una empresa basado en su inversión en publicidad.</p>
                </div>
                <div class="content-body">
                    <p>Como analista de datos, se te pide proyectar los ingresos del próximo trimestre. Tienes los datos históricos y sabes que por cada dólar invertido en Facebook Ads, las ventas suben $5, con una base fija de clientes de $1000.</p>

                    <div class="card p-4 mb-4" style="border-color: var(--text-muted);">
                        <h5 class="text-info">Modelo de Negocio Predictivo:</h5>
                        <code>Ventas ($) = (5 * Inversión) + 1000</code>
                    </div>

                    <?php
                    $sales = null;
                    if (isset($_POST['submit_sales'])) {
                        $invest = (float)$_POST['investment'];
                        $sales = (5 * $invest) + 1000;
                    }
                    ?>

                    <form method="POST" action="index.php?action=course_regression&module=3" class="interactive-form">
                        <div class="input-row">
                            <div class="input-group">
                                <label>Presupuesto de Inversión ($)</label>
                                <input type="number" name="investment" placeholder="Ej: 500" required>
                            </div>
                        </div>
                        <button type="submit" name="submit_sales" class="btn">Calcular Retorno (ROI)</button>
                    </form>

                    <?php if ($sales): ?>
                        <div class="neuron-result active">
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                            <span>Ingresos Estimados: <strong>$<?php echo number_format($sales, 2); ?></strong></span>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 3rem;">
                        <a href="index.php?action=complete_course&course=regression" class="btn btn-secondary">Finalizar y Certificar <i class="fa-solid fa-award"></i></a>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
