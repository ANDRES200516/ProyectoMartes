<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algoritmos Genéticos - Learns class</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-dark text-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom border-secondary px-4">
        <a href="index.php" class="navbar-brand fw-bold text-danger">Learns class</a>
        <div class="ms-auto">
            <a href="index.php" class="btn btn-outline-light btn-sm">Salir del Curso</a>
        </div>
    </nav>
    
    <div class="container-fluid course-layout py-4">
        <aside class="course-sidebar p-3">
            <div class="mb-4">
                <div class="progress bg-dark border border-secondary" style="height: 10px;">
                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo ($module / 4) * 100; ?>%;"></div>
                </div>
                <p class="small text-secondary mt-2">Progreso: <?php echo round(($module / 4) * 100); ?>%</p>
            </div>

            <h3>Módulos del Curso</h3>
            <ul class="module-list">
                <li class="<?php echo $module === 1 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 1 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 1. Selección Natural</li>
                <li class="<?php echo $module === 2 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 2 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 2. Cruce y Mutación</li>
                <li class="<?php echo $module === 3 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 3 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 3. Función de Aptitud</li>
                <li class="<?php echo $module === 4 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 4 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 4. Desafío Práctico</li>
            </ul>

            <div class="time-meta">
                <i class="fa-solid fa-hourglass-half"></i>
                <span>Tiempo estimado: 3.5 horas</span>
            </div>
        </aside>

        <main class="course-content">
            <?php if ($module === 1): ?>
                <div class="course-header">
                    <span class="tag tag-red">Módulo 1: La Inteligencia Evolutiva</span>
                    <h2>Conceptos de Selección Natural Computacional</h2>
                    <p>Entiende cómo la teoría de la evolución de Darwin es utilizada para crear software capaz de resolver problemas imposibles para la matemática tradicional.</p>
                </div>
                <div class="content-body">
                    <p>Los <strong>Algoritmos Genéticos (AG)</strong> pertenecen a la familia de los algoritmos evolutivos. Se basan en una premisa fascinante: si la naturaleza pudo diseñar sistemas complejos como el ojo humano mediante la evolución, podemos usar ese mismo proceso para diseñar software óptimo.</p>
                    
                    <h4 class="mt-4 text-primary">La Población Inicial</h4>
                    <p>En lugar de intentar resolver un problema con una sola fórmula, creamos una <strong>"Población"</strong> de cientos de soluciones aleatorias. Imagina que quieres diseñar la antena más potente para un satélite; el algoritmo genera 500 diseños de antenas con formas locas y absurdas al azar.</p>

                    <h4 class="mt-4 text-primary">La Supervivencia del Más Apto (Selection)</h4>
                    <p>Al igual que en la sabana africana, solo los fuertes sobreviven. Sometemos a nuestra población de soluciones a un entorno de pruebas. Las antenas que captan mejor la señal son "seleccionadas" para pasar sus genes (su diseño) a la siguiente generación. Las que no funcionan, son eliminadas permanentemente.</p>

                    <div class="card p-3 mt-4" style="border-color: var(--danger-color);">
                        <h5 class="text-danger"><i class="fas fa-biohazard"></i> Analogía del Mundo Real</h5>
                        <p class="small mb-0">Amazon utiliza algoritmos genéticos para organizar sus almacenes. Millones de combinaciones de rutas se "pelean" entre sí; solo la ruta que ahorra más combustible sobrevive para ser usada por los camiones.</p>
                    </div>旋
                    
                    <div style="margin-top: 3rem;">
                        <a href="index.php?action=course_genetic&module=2" class="btn">Módulo 2: Reproducción Digital <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 2): ?>
                <div class="course-header">
                    <span class="tag tag-red">Módulo 2: Operadores Genéticos</span>
                    <h2>Cruce y Mutación: La Mezcla de la Perfección</h2>
                    <p>Los operadores biológicos transformados en funciones matemáticas para garantizar la diversidad algorítmica.</p>
                </div>
                <div class="content-body">
                    <p>Una vez que hemos seleccionado a los "padres" más aptos, el algoritmo debe generar una nueva descendencia. Aquí es donde la magia de la herencia genética entra en juego mediante dos procesos fundamentales:</p>

                    <h4 class="mt-4 text-primary">Crossover (Cruce de ADN)</h4>
                    <p>Tomamos dos soluciones exitosas y combinamos su código. Si el Padre A es bueno en una parte del problema y el Padre B en otra, su "Hijo" heredará ambas virtudes. Matemáticamente, intercambiamos bits de información entre las cadenas de datos.</p>

                    <h4 class="mt-4 text-primary">Mutación (El Motor de la Innovación)</h4>
                    <p>Si solo cruzáramos soluciones parecidas, la evolución se detendría. La <strong>Mutación</strong> introduce cambios aleatorios e inesperados (como cambiar un 1 por un 0 al azar). Esto permite que el algoritmo "explore" nuevas posibilidades que no estaban presentes en los padres, evitando que el sistema se estanque en una solución mediocre.</p>

                    <div class="demo-container mt-4 mb-4">
                        <h4 class="text-primary mb-3">Laboratorio de Mutación Cromosómica</h4>
                        <p class="small text-secondary mb-3">Haz clic para simular una mutación en una cadena de ADN digital. Cada letra representa una instrucción de diseño para una IA evolutiva.</p>
                        <form method="POST" action="index.php?action=course_genetic&module=2" class="interactive-form">
                            <button type="submit" name="submit_genetic" class="btn">Simular Rayo Cósmico (Mutar)</button>
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_genetic'])) {
                            $genes = ['A', 'C', 'T', 'G']; $organism = '';
                            for($i=0; $i<16; $i++){ $organism .= $genes[array_rand($genes)]; }
                            echo "<div class='neuron-result active mt-4' style='font-family: monospace; letter-spacing: 5px; font-size: 1.2rem; color: #ff4d4d;'>" . substr($organism, 0, 4) . "-" . substr($organism, 4, 4) . "-" . substr($organism, 8, 4) . "-" . substr($organism, 12, 4) . "</div>";
                        }
                        ?>
                    </div>
                    <div style="margin-top: 2rem;">
                        <a href="index.php?action=course_genetic&module=3" class="btn">Módulo 3: El Juez de la Evolución <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 3): ?>
                <div class="course-header">
                    <span class="tag tag-red">Módulo 3: El Criterio de Éxito</span>
                    <h2>Función de Aptitud (Fitness Function)</h2>
                    <p>El núcleo de todo algoritmo genético: el juez implacable que decide quién vive y quién muere.</p>
                </div>
                <div class="content-body">
                    <p>En la naturaleza, el "Fitness" es la capacidad de sobrevivir y tener hijos. En la informática, la <strong>Función de Aptitud</strong> es una ecuación que mide qué tan cerca está una solución de ser perfecta.</p>

                    <h4 class="mt-4 text-primary">Diseñando al Juez Perfecto</h4>
                    <p>Si estamos resolviendo el "Problema del Viajante" (encontrar la ruta más corta entre 50 ciudades), la función de fitness será la suma de los kilómetros de la ruta. Entre menor sea la distancia, mayor será el "Fitness" de esa solución.</p>
                    
                    <div class="card p-4 mt-4" style="border-color: #ffc107;">
                        <h5 class="text-warning"><i class="fas fa-exclamation-triangle"></i> El Peligro del Mal Diseño</h5>
                        <p class="mb-0">Si diseñas mal tu función de fitness, el algoritmo te dará lo que pediste, no lo que necesitas. Un algoritmo genético diseñado para "limpiar una habitación rápido" podría decidir que la mejor forma es tirar todo por la ventana porque es lo que minimiza el tiempo.</p>
                    </div>旋

                    <div style="margin-top: 3rem;">
                        <a href="index.php?action=course_genetic&module=4" class="btn">Desafío Final: Optimización Real <i class="fa-solid fa-trophy"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 4): ?>
                <div class="course-header">
                    <span class="tag tag-red">Módulo 4: Proyecto de Ingeniería</span>
                    <h2>Optimización: El Problema de la Mochila</h2>
                    <p>Aplica tus conocimientos para resolver un clásico de la computación: ¿Cómo llevar el máximo valor en espacio limitado?</p>
                </div>
                <div class="content-body">
                    <p>Imagina que eres un explorador. Tienes una mochila que soporta 10kg y tienes frente a ti oro, comida, agua y herramientas. Cada uno tiene un peso y un valor. Un algoritmo genético puede encontrar la combinación perfecta en milisegundos.</p>

                    <?php
                    $fitness = 0;
                    if (isset($_POST['submit_knapsack'])) {
                        $mutation = (float)$_POST['mutation_rate'];
                        // Simulación simple: mayor mutación balanceada da mejor fitness
                        $fitness = 100 - abs(0.1 - $mutation) * 500;
                        if ($fitness < 0) $fitness = 10;
                    }
                    ?>

                    <form method="POST" action="index.php?action=course_genetic&module=4" class="interactive-form">
                        <div class="input-row">
                            <div class="input-group">
                                <label>Tasa de Exploración (Mutación 0.01 - 0.5)</label>
                                <input type="number" name="mutation_rate" step="0.01" value="0.05" required>
                            </div>
                        </div>
                        <button type="submit" name="submit_knapsack" class="btn">Lanzar Generaciones Evolutivas</button>
                    </form>

                    <?php if ($fitness > 0): ?>
                        <div class="neuron-result active">
                            <i class="fa-solid fa-dna"></i>
                            <span>Nivel de Optimización: <strong><?php echo round($fitness, 2); ?>%</strong>. ¡La población ha convergido!</span>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 3rem;">
                        <a href="index.php?action=complete_course&course=genetic" class="btn btn-secondary">Finalizar y Certificar <i class="fa-solid fa-graduation-cap"></i></a>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
