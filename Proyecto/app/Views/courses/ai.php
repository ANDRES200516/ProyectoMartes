<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curso IA - Learns class</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-dark text-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom border-secondary px-4">
        <a href="index.php" class="navbar-brand fw-bold text-primary">Learns class</a>
        <div class="ms-auto">
            <a href="index.php" class="btn btn-outline-light btn-sm">Salir del Curso</a>
        </div>
    </nav>
    
    <div class="container-fluid course-layout py-4">
        <aside class="course-sidebar p-3">
            <div class="mb-4">
                <div class="progress bg-dark border border-secondary" style="height: 10px;">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo ($module / 5) * 100; ?>%;"></div>
                </div>
                <p class="small text-secondary mt-2">Progreso: <?php echo ($module / 5) * 100; ?>%</p>
            </div>
            
            <h3>Módulos del Curso</h3>
            <ul class="module-list">
                <li class="<?php echo $module === 1 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 1 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 1. Introducción</li>
                <li class="<?php echo $module === 2 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 2 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 2. El Perceptrón</li>
                <li class="<?php echo $module === 3 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 3 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 3. Redes Neuronales</li>
                <li class="<?php echo $module === 4 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 4 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 4. Entrenamiento</li>
                <li class="<?php echo $module === 5 ? 'active' : ''; ?>"><i class="fa-solid <?php echo $module > 5 ? 'fa-circle-check' : 'fa-circle-play'; ?>"></i> 5. Laboratorio Práctico</li>
            </ul>

            <div class="time-meta">
                <i class="fa-solid fa-hourglass-half"></i>
                <span>Tiempo estimado: 2.5 horas</span>
            </div>
        </aside>

        <main class="course-content">
            <?php if ($module === 1): ?>
                <!-- ... existing module 1 ... -->
                <div class="course-header">
                    <span class="tag tag-purple">Módulo 1</span>
                    <h2>¿Qué es la Inteligencia Artificial?</h2>
                    <p>Fundamentos, historia y el impacto del aprendizaje automático en el mundo moderno.</p>
                </div>
                <div class="content-body">
                    <p>La <strong>Inteligencia Artificial (IA)</strong> no es un concepto nuevo, pero su evolución en la última década ha superado décadas de teoría. Se define como la capacidad de una máquina para imitar funciones cognitivas humanas como el aprendizaje y la resolución de problemas.</p>
                    
                    <h4 class="mt-4 text-primary">Contexto Histórico y Evolución</h4>
                    <p>Todo comenzó con Alan Turing y su pregunta: <i>"¿Pueden pensar las máquinas?"</i>. Durante años, la IA se basó en sistemas de reglas (Si A entonces B). Sin embargo, el verdadero cambio llegó con el <strong>Aprendizaje Automático</strong>, donde ya no programamos reglas, sino que permitimos que la máquina las descubra a partir de millones de datos.</p>

                    <div class="card p-4 mt-4">
                        <h5 class="text-accent" style="color: var(--accent);"><i class="fas fa-lightbulb"></i> ¿Por qué ahora?</h5>
                        <p class="small mb-0">La explosión de la IA actual se debe a tres pilares: el aumento masivo de datos (Big Data), el poder de procesamiento de las GPUs modernas y algoritmos de optimización más eficientes.</p>
                    </div>

                    <h4 class="mt-4 text-primary">IA Débil vs IA Fuerte</h4>
                    <p>Es vital entender que estamos en la era de la <strong>IA Débil (Narrow AI)</strong>. Son sistemas maestros en una sola tarea. Tu recomendación de Spotify, el reconocimiento facial de tu teléfono y los diagnósticos médicos asistidos por IA son ejemplos de esto. La <strong>IA Fuerte</strong>, una conciencia sintética capaz de razonar en cualquier campo, sigue siendo el "Santo Grial" de la ciencia ficción.</p>
                    
                    <div style="margin-top: 3rem;">
                        <a href="index.php?action=course_ai&module=2" class="btn">Ir al Módulo 2: El Perceptrón <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 2): ?>
                <div class="course-header">
                    <span class="tag tag-purple">Módulo 2: La Neurona Artificial</span>
                    <h2>El Perceptrón: El Ladrillo del Futuro</h2>
                    <p>Comprende la unidad fundamental que permite a las máquinas tomar decisiones binarias basadas en estímulos matemáticos.</p>
                </div>
                <div class="content-body">
                    <p>Inspirado en las neuronas biológicas, el <strong>Perceptrón</strong> es un algoritmo que recibe entradas, las procesa mediante una suma ponderada y genera una salida. Es el ancestro directo de todas las redes neuronales modernas.</p>
                    
                    <h4 class="mt-4 text-primary">Arquitectura de un Perceptrón</h4>
                    <ul class="mb-4" style="line-height: 2;">
                        <li><strong>Entradas (Inputs):</strong> Datos brutos que recibe la neurona (ej: píxeles, valores numéricos).</li>
                        <li><strong>Pesos (Weights):</strong> Representan la importancia de cada entrada. Un peso alto significa que esa entrada influye mucho en la decisión final.</li>
                        <li><strong>Suma Ponderada:</strong> El cálculo de Σ (Entrada * Peso).</li>
                        <li><strong>Función de Activación:</strong> Decide si el resultado es suficiente para que la neurona "dispare" una señal.</li>
                    </ul>

                    <div class="demo-container mt-4 mb-4">
                        <h4 class="text-primary mb-3">Laboratorio Interactivo: Lógica de Decisión</h4>
                        <p class="small text-secondary mb-3">Configura las entradas para ver si la neurona artificial decide "Activar". Esto simula cómo un sistema decide si un correo es Spam o no basándose en palabras clave.</p>
                        <?php
                        $output = null;
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ai'])) {
                            $in1 = isset($_POST['input1']) ? (int)$_POST['input1'] : 0;
                            $in2 = isset($_POST['input2']) ? (int)$_POST['input2'] : 0;
                            $sum = ($in1 * 0.7) + ($in2 * 0.8); // Pesos simulados
                            $output = $sum >= 1.0 ? 1 : 0;
                        }
                        ?>
                        <form method="POST" action="index.php?action=course_ai&module=2" class="interactive-form">
                            <div class="input-row">
                                <div class="input-group">
                                    <label>Presencia de Enlace (Input A)</label>
                                    <select name="input1"><option value="0">0 (No)</option><option value="1">1 (Sí)</option></select>
                                </div>
                                <div class="input-group">
                                    <label>Palabras Urgentes (Input B)</label>
                                    <select name="input2"><option value="0">0 (No)</option><option value="1">1 (Sí)</option></select>
                                </div>
                            </div>
                            <button type="submit" name="submit_ai" class="btn mt-3">Procesar Neurona</button>
                        </form>
                        <?php if ($output !== null): ?>
                            <div class="neuron-result <?php echo $output ? 'active' : ''; ?> mt-4">
                                <i class="fa-solid <?php echo $output ? 'fa-bolt' : 'fa-moon'; ?>"></i>
                                <span>Estado Final: <?php echo $output ? 'Activada (Es SPAM)' : 'Inactiva (No es Spam)'; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top: 2rem;">
                        <a href="index.php?action=course_ai&module=3" class="btn">Avanzar a Redes Neuronales <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 3): ?>
                <div class="course-header">
                    <span class="tag tag-purple">Módulo 3: Arquitecturas Profundas</span>
                    <h2>Estructura de una Red Neuronal (Multi-Layer)</h2>
                    <p>De una neurona a una red: Cómo el apilamiento de capas genera el aprendizaje profundo o "Deep Learning".</p>
                </div>
                <div class="content-body">
                    <p>Un perceptrón solo puede aprender líneas rectas. Para aprender la complejidad de la voz humana o la visión, necesitamos conectar cientos de ellos en capas. Aquí es donde entramos en el terreno del <strong>Deep Learning</strong>.</p>
                    
                    <h4 class="mt-4 text-primary">La Jerarquía del Aprendizaje</h4>
                    <p>Imagina que la red está viendo la foto de un rostro. Cada capa tiene un propósito:</p>
                    <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 2rem 0;">
                        <div class="card p-3">
                            <strong style="color: var(--accent);">Capas Iniciales:</strong>
                            <p class="small text-white">Detectan rasgos simples como líneas verticales, horizontales y bordes de luz.</p>
                        </div>
                        <div class="card p-3">
                            <strong style="color: var(--accent);">Capas Intermedias:</strong>
                            <p class="small text-white">Combinan líneas para formar patrones: ojos, narices, bocas.</p>
                        </div>
                        <div class="card p-3">
                            <strong style="color: var(--accent);">Capas Finales:</strong>
                            <p class="small text-white">Reconocen estructuras completas (Rostros, Objetos, Animales).</p>
                        </div>
                        <div class="card p-3">
                            <strong style="color: var(--accent);">Capa de Salida:</strong>
                            <p class="small text-white">Clasifica el objeto con una probabilidad estadística final.</p>
                        </div>
                    </div>

                    <div style="margin-top: 2rem;">
                        <a href="index.php?action=course_ai&module=4" class="btn">Ver: Cómo aprenden (Entrenamiento) <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 4): ?>
                <div class="course-header">
                    <span class="tag tag-purple">Módulo 4: El Ciclo de Aprendizaje</span>
                    <h2>Entrenamiento, Error y Corrección</h2>
                    <p>Descubre el algoritmo de Retropropagación (Backpropagation), el cerebro detrás del aprendizaje.</p>
                </div>
                <div class="content-body">
                    <p>¿Cómo sabe una red que se ha equivocado? Mediante la comparación matemática entre su predicción y la realidad. Este proceso se repite millones de veces en lo que llamamos <strong>Épocas de Entrenamiento</strong>.</p>

                    <h4 class="mt-4 text-primary">La Función de Pérdida (Loss Function)</h4>
                    <p>Es una fórmula matemática que cuantifica el error. Si la IA dice que una foto es un perro pero es un gato, la función de pérdida genera un valor alto. El objetivo del entrenamiento es "bajar" por una montaña de errores hasta llegar al punto más bajo (mínimo global) mediante el <strong>Descenso del Gradiente</strong>.</p>

                    <div class="card p-4 border-info">
                        <h5 class="text-info"><i class="fas fa-undo"></i> Backpropagation: El gran secreto</h5>
                        <p>Una vez que sabemos el error, el sistema retrocede por todas las capas de la red ajustando cada peso. Es como si el sistema dijera: "Tú, neurona 5, fuiste 20% responsable del error, baja un poco tu sensibilidad".</p>
                    </div>

                    <div style="margin-top: 3rem;">
                        <a href="index.php?action=course_ai&module=5" class="btn">Ir al Laboratorio de Ingeniería Final <i class="fa-solid fa-flask"></i></a>
                    </div>
                </div>

            <?php elseif ($module === 5): ?>
                <div class="course-header">
                    <span class="tag tag-purple">Módulo 5: Proyecto Final</span>
                    <h2>Laboratorio Práctico: Clasificador Predictivo</h2>
                    <p>Conviértete en un Ingeniero de Machine Learning. Ajusta los hiperparámetros de un modelo para enseñarle a clasificar objetos mediante pesos ponderados.</p>
                </div>
                <div class="content-body">
                    <div class="card" style="background: rgba(0,0,0,0.2); margin-bottom: 2rem;">
                        <h4>Instrucciones:</h4>
                        <p>1. Define los pesos de la neurona (Sensibilidad al peso y a la textura).<br>
                           2. Si la suma ponderada supera el umbral (10), la neurona dirá que es una <strong>Manzana</strong>.</p>
                    </div>

                    <?php
                    $result = null;
                    if (isset($_POST['submit_lab'])) {
                        $pWeight = (float)$_POST['p_weight'];
                        $pTexture = (float)$_POST['p_texture'];
                        $inputW = 150; // Gramos
                        $inputT = 8;   // Rugosidad
                        $sum = ($inputW * $pWeight) + ($inputT * $pTexture);
                        $result = $sum >= 10 ? 'Manzana' : 'Naranja';
                    }
                    ?>

                    <form method="POST" action="index.php?action=course_ai&module=5" class="interactive-form">
                        <div class="input-row">
                            <div class="input-group">
                                <label>Sensibilidad al Peso (0.01 - 0.1)</label>
                                <input type="number" name="p_weight" step="0.01" value="0.05" required>
                            </div>
                            <div class="input-group">
                                <label>Sensibilidad a Textura (0.1 - 1)</label>
                                <input type="number" name="p_texture" step="0.1" value="0.5" required>
                            </div>
                        </div>
                        <button type="submit" name="submit_lab" class="btn">Entrenar y Clasificar</button>
                    </form>

                    <?php if ($result): ?>
                        <div class="neuron-result active">
                            <i class="fa-solid fa-brain"></i>
                            <span>La IA ha clasificado el objeto como: <strong><?php echo $result; ?></strong></span>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 3rem;">
                        <a href="index.php?action=complete_course&course=ai" class="btn btn-secondary">Finalizar y Certificar <i class="fa-solid fa-certificate"></i></a>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
