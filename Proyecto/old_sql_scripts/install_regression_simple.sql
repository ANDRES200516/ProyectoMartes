-- ============================================================
-- CURSO: REGRESIÓN LINEAL - INSTALACIÓN COMPLETA
-- ============================================================

-- 1. INSERTAR CURSO PRINCIPAL
INSERT INTO courses (slug, title, short_description, description, level, duration_hours, thumbnail, banner, requirements, objectives, category, tags, status, total_lessons) 
VALUES ('regresion-lineal', 'Regresión Lineal: Fundamentos a Proyectos Reales', 'Domina la regresión lineal desde matemática básica hasta modelos predictivos avanzados.', 'La regresión lineal es el algoritmo más fundamental del Machine Learning. Aprenderás cómo construir modelos que predicen valores continuos. Cubriremos desde mínimos cuadrados, interpretación de coeficientes, validación, hasta regularización con Ridge y Lasso. Implementarás 3 proyectos reales.', 'Intermedio', 32, 'assets/images/courses/regression-thumb.jpg', 'assets/images/courses/regression-banner.jpg', 'Python básico.|Álgebra lineal elemental.|Estadística descriptiva.', 'Comprender teoría de regresión.|Implementar desde cero en NumPy.|Usar scikit-learn.|Validar modelos correctamente.|Aplicar regularización.|Detectar multicolinealidad.|Construir 3 proyectos profesionales.', 'Data Science', 'Regresión Lineal, Machine Learning, Predicción, Python, Estadística', 'active', 27);

-- 2. INSERTAR MÓDULOS
INSERT INTO modules (course_id, title, description, sort_order) VALUES
(9, 'Fundamentos Matemáticos de la Regresión', 'Conceptos de álgebra lineal, geometría y cálculo aplicados a regresión', 1),
(9, 'Regresión Lineal Simple: Teoría y Práctica', 'De una variable independiente a predicciones: la base de todo', 2),
(9, 'Regresión Múltiple: Varios Predictores', 'Extensión a múltiples variables e interpretación de coeficientes', 3),
(9, 'Validación y Evaluación de Modelos', 'Métricas, train-test split, cross-validation y diagnósticos', 4),
(9, 'Regularización: Ridge, Lasso y Elastic Net', 'Técnicas para prevenir overfitting y mejorar generalización', 5),
(9, 'Supuestos del Modelo y Diagnósticos', 'Verificar normalidad, homocedasticidad, multicolinealidad', 6),
(9, 'Regresión Polinomial y No Lineal', 'Extender regresión lineal a relaciones curvadas', 7),
(9, 'Series Temporales: Predicción de Tendencias', 'Aplicar regresión a datos temporales', 8),
(9, 'Proyecto Final: Sistema Predictivo Integral', 'Construir una aplicación completa de predicción', 9);

-- 3. INSERTAR LECCIONES MÓDULO 1: Fundamentos Matemáticos
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(10, 'Álgebra Lineal: Vectores y Matrices', '<h2>Conceptos Esenciales de Álgebra Lineal</h2><p>La regresión lineal es fundamentalmente un problema de álgebra lineal. Necesitamos entender vectores, matrices, productos punto y espacios lineales.</p><h3>Conceptos Clave</h3><ul><li><strong>Vector:</strong> Arreglo ordenado de números.</li><li><strong>Matriz:</strong> Arreglo bidimensional de números.</li><li><strong>Producto Punto:</strong> Combina dos vectores en un escalar.</li><li><strong>Norma:</strong> La longitud de un vector.</li><li><strong>Producto Matricial:</strong> La operación fundamental en ML.</li></ul>', 'https://www.youtube.com/embed/fNk_zzaMoSs', 'youtube', 28, 1, 1),
(10, 'Cálculo: Derivadas y Gradientes', '<h2>El Gradiente: Dirección de Máximo Cambio</h2><p>Para encontrar el mínimo de una función necesitamos derivadas. El gradiente es el vector de derivadas parciales.</p><h3>Conceptos Clave</h3><ul><li><strong>Derivada:</strong> Tasa de cambio instantánea.</li><li><strong>Gradiente:</strong> Vector que apunta hacia mayor aumento.</li><li><strong>Descenso de Gradiente:</strong> Algoritmo para minimizar funciones.</li></ul>', '', 'none', 32, 2, 0),
(10, 'Probabilidad y Estadística Bayesiana', '<h2>Interpretación Probabilística de Regresión</h2><p>La regresión lineal es un modelo probabilístico donde los datos siguen una distribución normal alrededor de la línea de regresión.</p>', '', 'none', 25, 3, 0);

-- 4. INSERTAR LECCIONES MÓDULO 2: Regresión Lineal Simple
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(11, 'Mínimos Cuadrados: La Línea que Mejor se Ajusta', '<h2>Derivando la Solución de Mínimos Cuadrados</h2><p>La regresión lineal busca encontrar la línea que minimiza la suma de errores cuadrados.</p><pre><code>β₁ = Cov(X,Y)/Var(X)\nβ₀ = ȳ - β₁x̄</code></pre>', 'https://www.youtube.com/embed/P8hT5nDaiMQ', 'youtube', 35, 1, 1),
(11, 'Implementación desde Cero en NumPy', '<h2>Tu Primer Modelo: Regresión Lineal Manual</h2><p>Implementaremos regresión lineal sin scikit-learn para comprender cada paso.</p><pre><code>import numpy as np\n\nclass LinearRegression:\n    def fit(self, X, y):\n        X = np.column_stack([np.ones(len(X)), X])\n        self.coef_ = np.linalg.lstsq(X, y, rcond=None)[0]\n    \n    def predict(self, X):\n        X = np.column_stack([np.ones(len(X)), X])\n        return X @ self.coef_</code></pre>', '', 'none', 40, 2, 0),
(11, 'Uso de Scikit-Learn: API Estándar de ML', '<h2>Regresión Lineal con Scikit-Learn</h2><p>En práctica usamos librerías optimizadas.</p><pre><code>from sklearn.linear_model import LinearRegression\n\nmodel = LinearRegression()\nmodel.fit(X_train, y_train)\ny_pred = model.predict(X_test)\nprint(f"R²: {model.score(X_test, y_test)}")</code></pre>', '', 'none', 30, 3, 0);

-- 5. INSERTAR LECCIONES MÓDULO 3: Regresión Múltiple
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(12, 'Extensión a Múltiples Variables', '<h2>De Uno a Muchos Predictores</h2><p>y = β₀ + β₁x₁ + β₂x₂ + ... + βₚxₚ + ε</p><p>En forma matricial: y = Xβ + ε</p>', '', 'none', 33, 1, 0),
(12, 'Interpretación de Coeficientes', '<h2>¿Qué Significan los Coeficientes?</h2><p>Cada coeficiente β_i representa el cambio esperado en y cuando x_i aumenta en 1 unidad, manteniendo otras variables constantes.</p><h3>Ejemplo</h3><p>Precio = 50000 + 200*Area + 5000*Habitaciones</p>', 'https://www.youtube.com/embed/nk2CQITm_eo', 'youtube', 28, 2, 0),
(12, 'Proyecto 1: Predicción de Precios de Casas', '<h2>Dataset: Housing Prices</h2><p>Construiremos un modelo que predice precios de casas basado en características como área, ubicación, antigüedad.</p><ol><li>Cargar dataset</li><li>Explorar datos</li><li>Dividir train/test</li><li>Entrenar modelo</li><li>Evaluar con R² y RMSE</li></ol>', '', 'none', 45, 3, 0);

-- 6. INSERTAR LECCIONES MÓDULO 4: Validación y Evaluación
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(13, 'Métricas de Evaluación para Regresión', '<h2>¿Cómo Saber si el Modelo es Bueno?</h2><ul><li>MAE: Promedio del error absoluto</li><li>MSE: Promedio del error al cuadrado</li><li>RMSE: Raíz del MSE</li><li>R²: Proporción de varianza explicada (0-1)</li></ul>', '', 'none', 32, 1, 0),
(13, 'Train-Test Split y Validación Cruzada', '<h2>Evitar Engañarse a Uno Mismo</h2><p>Dividir datos: 70-80% entrenamiento, 20-30% prueba.</p><pre><code>from sklearn.model_selection import cross_val_score\nscores = cross_val_score(model, X, y, cv=5)\nprint(f"R² promedio: {scores.mean():.3f}")</code></pre>', 'https://www.youtube.com/embed/fSytzGwwBVw', 'youtube', 30, 2, 0),
(13, 'Diagnósticos de Residuos', '<h2>¿Está el Modelo Cumpliendo sus Supuestos?</h2><p>Los residuos deben ser aleatorios y normalmente distribuidos.</p><p>Gráficos clave: Q-Q Plot, Residuos vs Valores Ajustados</p>', '', 'none', 28, 3, 0);

-- 7. INSERTAR LECCIONES MÓDULO 5: Regularización
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(14, 'El Problema del Overfitting', '<h2>¿Cuándo el Modelo Memoriza?</h2><p>Con muchas variables el modelo puede memorizar ruido. Síntomas: alto R² en train, bajo en test.</p>', '', 'none', 25, 1, 0),
(14, 'Ridge Regression (L2 Regularization)', '<h2>Penalizar Coeficientes Grandes</h2><p>Ridge añade un término de penalización:</p><p>RSS + λ Σ(β_i²)</p><pre><code>from sklearn.linear_model import Ridge\nmodel = Ridge(alpha=1.0)</code></pre>', 'https://www.youtube.com/embed/1dKRdX9bfIo', 'youtube', 30, 2, 0),
(14, 'Lasso y Elastic Net: Selección de Variables', '<h2>L1 y Combinaciones</h2><p>Lasso puede hacer coeficientes exactamente cero (selección automática).</p><pre><code>from sklearn.linear_model import Lasso, ElasticNet\nlasso = Lasso(alpha=0.1)</code></pre>', '', 'none', 32, 3, 0);

-- 8. INSERTAR LECCIONES MÓDULO 6: Supuestos y Diagnósticos
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(15, 'Supuestos de la Regresión Lineal', '<h2>Las Premisas Básicas</h2><ol><li>Linealidad: relación es lineal</li><li>Independencia: observaciones independientes</li><li>Normalidad: residuos normales</li><li>Homocedasticidad: varianza constante</li><li>No multicolinealidad: variables no correlacionadas</li></ol>', '', 'none', 28, 1, 0),
(15, 'Multicolinealidad y Correlación', '<h2>Cuando X variables se Correlacionan</h2><p>VIF mide multicolinealidad:</p><p>VIF < 5: Sin multicolinealidad</p><p>VIF 5-10: Moderada</p><p>VIF > 10: Problema serio</p>', 'https://www.youtube.com/embed/Esm2zvsQlh0', 'youtube', 25, 2, 0),
(15, 'Detección y Tratamiento de Outliers', '<h2>¿Qué Hacer con Valores Extremos?</h2><ul><li>Investigar: ¿Es un error?</li><li>Eliminar si es error claro</li><li>Usar regresión robusta</li><li>Transformar variables</li></ul>', '', 'none', 30, 3, 0);

-- 9. INSERTAR LECCIONES MÓDULO 7: Regresión Polinomial
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(16, 'Extensiones No Lineales', '<h2>Cuando la Recta No es Suficiente</h2><p>Regresión polinomial añade términos potencia:</p><p>y = β₀ + β₁x + β₂x² + β₃x³</p>', '', 'none', 28, 1, 0),
(16, 'Feature Engineering para Regresión', '<h2>Creando Nuevas Características</h2><pre><code>from sklearn.preprocessing import PolynomialFeatures\npoly = PolynomialFeatures(degree=2)\nX_poly = poly.fit_transform(X)</code></pre>', 'https://www.youtube.com/embed/neXJc-_f5U4', 'youtube', 32, 2, 0),
(16, 'Proyecto 2: Predicción de Demanda', '<h2>Dataset: Predicción de Demanda de Energía</h2><p>Predice demanda basada en temperatura, hora del día, etc.</p>', '', 'none', 40, 3, 0);

-- 10. INSERTAR LECCIONES MÓDULO 8: Series Temporales
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(17, 'Características de Datos Temporales', '<h2>Cuando el Tiempo Importa</h2><p>Los datos de series temporales tienen autocorrelación: observaciones cercanas están relacionadas.</p>', '', 'none', 30, 1, 0),
(17, 'Tendencias y Estacionalidad', '<h2>Descomposición de Series Temporales</h2><ul><li>Tendencia: movimiento de largo plazo</li><li>Estacionalidad: patrones repetitivos</li><li>Residual: ruido aleatorio</li></ul>', 'https://www.youtube.com/embed/e8Yw4alG16Q', 'youtube', 28, 2, 0),
(17, 'Proyecto 3: Predicción de Ventas Mensuales', '<h2>Dataset: Ventas Históricas</h2><p>Predice ventas futuras usando variables temporales: mes, estación, tendencia.</p>', '', 'none', 45, 3, 0);

-- 11. INSERTAR LECCIONES MÓDULO 9: Proyecto Final
INSERT INTO lessons (module_id, title, content, video_url, video_type, duration_minutes, sort_order, is_free) VALUES
(18, 'Integración de Conceptos', '<h2>Construyendo un Sistema Predictivo Profesional</h2><p>Integraremos todo lo aprendido en un pipeline completo.</p>', '', 'none', 50, 1, 0),
(18, 'Despliegue en Producción', '<h2>De Jupyter a Producción</h2><p>Guardar modelo entrenado y crear API simple con Flask.</p>', 'https://www.youtube.com/embed/BS8uS-4NcUY', 'youtube', 40, 2, 0),
(18, 'Portfolio: Presentando tu Trabajo', '<h2>Documentación y Presentación Profesional</h2><p>Documentar, visualizar y presentar tu proyecto de forma impactante.</p>', '', 'none', 35, 3, 0);
