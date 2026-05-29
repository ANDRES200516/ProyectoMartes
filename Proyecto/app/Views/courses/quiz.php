<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen: <?php echo htmlspecialchars($quiz['title']); ?></title>
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- LAYOUT CSS (Design System) -->
    <link rel="stylesheet" href="assets/layout.css">
    <style>
        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 2rem;
            font-family: 'Inter', sans-serif;
        }
        .quiz-container {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .quiz-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        .quiz-header h1 {
            color: var(--text-light);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        .quiz-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        .question-block {
            margin-bottom: 2rem;
            display: none; /* Hide all initially for step-by-step */
        }
        .question-block.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .question-text {
            font-size: 1.15rem;
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }
        .options-list {
            list-style: none;
            padding: 0;
        }
        .option-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 0.8rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }
        .option-item:hover {
            border-color: var(--primary);
            background: rgba(79, 70, 229, 0.1);
        }
        .option-item input[type="radio"] {
            margin-right: 1rem;
            transform: scale(1.2);
            cursor: pointer;
        }
        .option-item label {
            width: 100%;
            cursor: pointer;
        }
        .quiz-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
        .btn-nav {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-prev {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
        }
        .btn-prev:hover {
            background-color: rgba(255,255,255,0.05);
        }
        .btn-next, .btn-submit {
            background-color: var(--primary);
            color: white;
            border: none;
        }
        .btn-next:hover, .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: var(--border-color);
            border-radius: 4px;
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: var(--primary);
            width: 0%;
            transition: width 0.3s ease;
        }
        .progress-text {
            text-align: right;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

    <div class="quiz-container">
        <a href="index.php?action=course_details&course=<?php echo htmlspecialchars($module['course_id']); ?>" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">
            <i class="fa fa-arrow-left"></i> Volver al curso
        </a>

        <div class="quiz-header">
            <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
            <p><?php echo htmlspecialchars($quiz['description']); ?></p>
        </div>

        <div class="progress-text" id="progressText">Pregunta 1 de <?php echo count($questions); ?></div>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" id="progressBar"></div>
        </div>

        <form id="quizForm">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
            
            <?php foreach ($questions as $index => $q): ?>
                <div class="question-block <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                    <div class="question-text">
                        <?php echo ($index + 1) . '. ' . htmlspecialchars($q['question']); ?>
                    </div>
                    
                    <ul class="options-list">
                        <li class="option-item">
                            <input type="radio" id="q_<?php echo $q['id']; ?>_A" name="answers[<?php echo $q['id']; ?>]" value="A">
                            <label for="q_<?php echo $q['id']; ?>_A"><?php echo htmlspecialchars($q['option_a']); ?></label>
                        </li>
                        <li class="option-item">
                            <input type="radio" id="q_<?php echo $q['id']; ?>_B" name="answers[<?php echo $q['id']; ?>]" value="B">
                            <label for="q_<?php echo $q['id']; ?>_B"><?php echo htmlspecialchars($q['option_b']); ?></label>
                        </li>
                        <?php if(!empty($q['option_c'])): ?>
                        <li class="option-item">
                            <input type="radio" id="q_<?php echo $q['id']; ?>_C" name="answers[<?php echo $q['id']; ?>]" value="C">
                            <label for="q_<?php echo $q['id']; ?>_C"><?php echo htmlspecialchars($q['option_c']); ?></label>
                        </li>
                        <?php endif; ?>
                        <?php if(!empty($q['option_d'])): ?>
                        <li class="option-item">
                            <input type="radio" id="q_<?php echo $q['id']; ?>_D" name="answers[<?php echo $q['id']; ?>]" value="D">
                            <label for="q_<?php echo $q['id']; ?>_D"><?php echo htmlspecialchars($q['option_d']); ?></label>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endforeach; ?>

            <div class="quiz-footer">
                <button type="button" class="btn-nav btn-prev" id="btnPrev" style="display: none;"><i class="fa fa-chevron-left"></i> Anterior</button>
                <div style="flex-grow: 1;"></div>
                <button type="button" class="btn-nav btn-next" id="btnNext">Siguiente <i class="fa fa-chevron-right"></i></button>
                <button type="submit" class="btn-nav btn-submit" id="btnSubmit" style="display: none;"><i class="fa fa-paper-plane"></i> Enviar Examen</button>
            </div>
        </form>
    </div>

    <!-- SWEETALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const totalQuestions = <?php echo count($questions); ?>;
        let currentIndex = 0;
        
        const blocks = document.querySelectorAll('.question-block');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const form = document.getElementById('quizForm');
        const courseId = <?php echo json_encode($module['course_id']); ?>;

        function updateUI() {
            blocks.forEach((block, idx) => {
                block.classList.toggle('active', idx === currentIndex);
            });

            btnPrev.style.display = currentIndex > 0 ? 'block' : 'none';
            
            if (currentIndex === totalQuestions - 1) {
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'block';
            } else {
                btnNext.style.display = 'block';
                btnSubmit.style.display = 'none';
            }

            const percent = ((currentIndex + 1) / totalQuestions) * 100;
            progressBar.style.width = percent + '%';
            progressText.innerText = `Pregunta ${currentIndex + 1} de ${totalQuestions}`;
        }

        btnNext.addEventListener('click', () => {
            if (currentIndex < totalQuestions - 1) {
                currentIndex++;
                updateUI();
            }
        });

        btnPrev.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateUI();
            }
        });

        // Make entire row clickable for radio
        document.querySelectorAll('.option-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validate that all questions have answers
            const checkedInputs = form.querySelectorAll('input[type="radio"]:checked');
            if (checkedInputs.length < totalQuestions) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Faltan respuestas',
                    text: 'Por favor responde todas las preguntas antes de enviar.',
                    background: '#1e293b',
                    color: '#fff'
                });
                return;
            }
            
            btnSubmit.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Procesando...';
            btnSubmit.style.pointerEvents = 'none';

            try {
                const formData = new FormData(form);
                const response = await fetch('index.php?action=submit_quiz', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: result.passed ? 'success' : 'error',
                        title: result.passed ? '¡Aprobado!' : 'No Aprobado',
                        text: `Puntuación: ${result.score}% - ${result.message}`,
                        background: '#1e293b',
                        color: '#fff',
                        confirmButtonText: 'Volver al Curso',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = 'index.php?action=course_details&course=' + courseId;
                    });
                } else {
                    Swal.fire('Error', result.message, 'error');
                    btnSubmit.innerHTML = '<i class="fa fa-paper-plane"></i> Enviar Examen';
                    btnSubmit.style.pointerEvents = 'auto';
                }
            } catch (error) {
                Swal.fire('Error', 'Ocurrió un error de conexión', 'error');
                btnSubmit.innerHTML = '<i class="fa fa-paper-plane"></i> Enviar Examen';
                btnSubmit.style.pointerEvents = 'auto';
            }
        });

        // Initialize UI
        updateUI();
    </script>
</body>
</html>
