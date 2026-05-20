-- ============================================================
-- ADD LINEAR REGRESSION COURSE
-- Este script agrega el curso de Regresión Lineal a la BD
-- Se inserta como ID 9 (después del curso JavaScript)
-- ============================================================

-- CURSO: Regresión Lineal y Análisis Predictivo
INSERT INTO `courses` (`slug`,`title`,`short_description`,`description`,`level`,`duration_hours`,`thumbnail`,`banner`,`requirements`,`objectives`,`category`,`tags`,`status`,`total_lessons`) VALUES
('regresion-lineal','Regresión Lineal: Fundamentos a Proyectos Reales','Domina la regresión lineal desde matemática básica hasta modelos predictivos avanzados.','La regresión lineal es el algoritmo más fundamental del Machine Learning y la estadística. En este curso aprenderás cómo construir modelos que predicen valores continuos a partir de datos históricos. Cubriremos desde la matemática detrás de mínimos cuadrados, interpretación de coeficientes, validación del modelo, hasta regularización con Ridge y Lasso. Implementarás proyectos reales: predicción de precios, análisis de tendencias y modelado de relaciones entre variables. Perfecto para data scientists, analistas y desarrolladores que quieren entender el corazón del Machine Learning.','Intermedio',32,'assets/images/courses/regression-thumb.jpg','assets/images/courses/regression-banner.jpg','Python básico.|Álgebra lineal elemental.|Estadística descriptiva (media, varianza, correlación).','Comprender la teoría matemática de regresión lineal.|Implementar regresión lineal desde cero sin librerías.|Usar scikit-learn para construir modelos predictivos.|Validar y evaluar la calidad de modelos con métricas adecuadas.|Aplicar regularización Ridge, Lasso y Elastic Net.|Detectar y tratar multicolinealidad y outliers.|Construir 3 proyectos reales para tu portafolio.','Data Science','Regresión Lineal, Machine Learning, Predicción, Python, Estadística','active',27);

-- ============================================================
-- MODULOS PARA REGRESIÓN LINEAL (9 módulos)
-- ============================================================
INSERT INTO `modules` (`course_id`,`title`,`description`,`sort_order`) VALUES
(9,'Fundamentos Matemáticos de la Regresión','Conceptos de álgebra lineal, geometría y cálculo aplicados a regresión',1),
(9,'Regresión Lineal Simple: Teoría y Práctica','De una variable independiente a predicciones: la base de todo','',2),
(9,'Regresión Múltiple: Varios Predictores','Extensión a múltiples variables y interpretación de coeficientes',3),
(9,'Validación y Evaluación de Modelos','Métricas, train-test split, cross-validation y diagnósticos',4),
(9,'Regularización: Ridge, Lasso y Elastic Net','Técnicas para prevenir overfitting y mejorar generalización',5),
(9,'Supuestos del Modelo y Diagnósticos','Verificar normalidad, homocedasticidad, multicolinealidad',6),
(9,'Regresión Polinomial y No Lineal','Extender regresión lineal a relaciones curvadas',7),
(9,'Series Temporales: Predicción de Tendencias','Aplicar regresión a datos temporales',8),
(9,'Proyecto Final: Sistema Predictivo Integral','Construir una aplicación completa de predicción',9);

-- ============================================================
-- LECCIONES POR MODULO (3 lecciones por módulo = 27 lecciones)
-- ============================================================

-- MODULO 36: Fundamentos Matemáticos (module_id=36)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(36,'Álgebra Lineal: Vectores y Matrices','<h2>Regresión lineal (introducción visual)</h2>
<p>La <strong>regresión lineal</strong> es un modelo supervisado que aprende a predecir una <strong>variable respuesta</strong> (la <em>dependiente</em>, y) como una función de una o más <strong>variables independientes</strong> (las <em>características</em>, x<sub>i</sub>).</p>

<h3>¿Por qué sigue siendo importante?</h3>
<p>A pesar de la popularidad de modelos complejos, se usa ampliamente porque es <strong>eficaz</strong>, <strong>fácil de interpretar</strong> y <strong>fácil de ampliar</strong>. Además, muchas ideas se reciclan en otros algoritmos.</p>

<h3>Modelo matemático</h3>
<p>En general:</p>
<pre><code>y = β₀ + β₁x₁ + β₂x₂ + ... + βₚxₚ + ε</code></pre>
<ul>
  <li><strong>y</strong>: lo que queremos predecir.</li>
  <li><strong>x<sub>i</sub></strong>: variables independientes (características).</li>
  <li><strong>β<sub>i</sub></strong>: coeficientes (pesos) que el modelo aprende.</li>
  <li><strong>ε</strong>: error irreducible (lo que no explica el modelo).</li>
</ul>

<h3>Una vez entrenado…</h3>
<p>Estimamos los coeficientes (β̂<sub>i</sub>) y la predicción es simplemente conectar nuevas entradas x<sub>i</sub> en la ecuación.</p>

<p><em>Ejemplo conceptual:</em> predecir el precio de una casa usando número de habitaciones (y: precio; x: habitaciones), o predecir peso usando altura y edad.</p>','https://www.youtube.com/embed/fNk_zzaMoSs','youtube',28,1,1),
(36,'Cálculo: Derivadas y Gradientes','<h2>Cómo funciona, brevemente (ajustar una recta)</h2>
<p>La regresión lineal intenta encontrar una <strong>línea</strong> (o <strong>superficie</strong> en casos multivariados) que mejor se ajuste a los datos. Para cuantificar qué tan “malo” es un modelo, medimos el <strong>error</strong> entre lo observado y lo predicho, usando <strong>residuos</strong>.</p>

<h3>Ejemplo visual (precio vs tamaño)</h3>
<p>Supón que quieres predecir el precio de una vivienda ($) usando el tamaño en pies cuadrados. Primero, podrías usar un modelo muy simple que predice siempre el promedio (por ejemplo, ~290,000), pero es claramente insuficiente.</p>
<p>Luego, buscamos la <strong>línea de mejor ajuste</strong> minimizando el error entre la línea y los puntos. Aunque siempre habrá error irreducible, el patrón general queda capturado.</p>

<h3>Predicción con el modelo</h3>
<p>Cuando ya entrenamos el modelo (encontrando los coeficientes), predecir es tan simple como reemplazar el valor de la característica <code>x</code> en la ecuación.</p>','', 'none',32,2,0),
(36,'Probabilidad y Estadística Bayesiana','<h2>Interpretación Probabilística de Regresión</h2><p>La regresión lineal puede verse como un modelo probabilístico donde asumimos que los datos siguen una distribución normal alrededor de la línea de regresión.</p><p>P(y|X) = Normal(μ=Xβ, σ²)</p><p>Esto nos permite calcular intervalos de confianza y utilizar Bayes para interpretación.</p>','','none',25,3,0);

-- MODULO 37: Regresión Lineal Simple (module_id=37)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(37,'Mínimos Cuadrados: La Línea que Mejor se Ajusta','<h2>Derivando la Solución de Mínimos Cuadrados</h2><p>La regresión lineal busca encontrar la línea que minimiza la suma de errores cuadrados:</p><pre><code>min Σ(y_i - ŷ_i)² = min Σ(y_i - (β₀ + β₁x_i))²</code></pre><p>Usando cálculo, podemos derivar cerrada para β₀ y β₁:</p><pre><code>β₁ = Σ((x_i - x̄)(y_i - ȳ)) / Σ((x_i - x̄)²) = Cov(X,Y)/Var(X)\nβ₀ = ȳ - β₁x̄</code></pre>','https://www.youtube.com/embed/P8hT5nDaiMQ','youtube',35,1,1),
(37,'Implementación desde Cero en NumPy','<h2>Tu Primer Modelo: Regresión Lineal Manual</h2><p>Implementaremos regresión lineal sin usar scikit-learn para comprender cada paso:</p><pre><code>import numpy as np\n\nclass LinearRegression:\n    def fit(self, X, y):\n        # Agregar columna de unos para el intercepto\n        X = np.column_stack([np.ones(len(X)), X])\n        # Fórmula cerrada: β = (Xᵀ X)⁻¹ Xᵀ y\n        self.coef_ = np.linalg.lstsq(X, y, rcond=None)[0]\n    \n    def predict(self, X):\n        X = np.column_stack([np.ones(len(X)), X])\n        return X @ self.coef_</code></pre>','','none',40,2,0),
(37,'Uso de Scikit-Learn: API Estándar de ML','<h2>Regresión Lineal con Scikit-Learn</h2><p>En la práctica usamos librerías optimizadas. Scikit-learn proporciona una API consistente:</p><pre><code>from sklearn.linear_model import LinearRegression\nfrom sklearn.model_selection import train_test_split\n\n# Dividir datos\nX_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2)\n\n# Crear y entrenar modelo\nmodel = LinearRegression()\nmodel.fit(X_train, y_train)\n\n# Predicciones\ny_pred = model.predict(X_test)\n\n# Acceder a parámetros\nprint(f"Intercepto: {model.intercept_}\")\nprint(f"Coeficientes: {model.coef_}\")</code></pre>','','none',30,3,0);

-- MODULO 38: Regresión Múltiple (module_id=38)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(38,'Extensión a Múltiples Variables','<h2>De Uno a Muchos Predictores</h2><p>La regresión múltiple extiende la idea de usar una sola variable independiente a usar varias:</p><pre><code>y = β₀ + β₁x₁ + β₂x₂ + ... + βₚxₚ + ε</code></pre><p>En forma matricial: <strong>y = Xβ + ε</strong></p><p>La solución sigue siendo: <strong>β = (XᵀX)⁻¹Xᵀy</strong></p><p>Ahora podemos modelar relaciones más complejas usando múltiples características.</p>','','none',33,1,0),
(38,'Interpretación de Coeficientes','<h2>¿Qué Significan los Coeficientes?</h2><p>Cada coeficiente β_i representa el cambio esperado en y cuando x_i aumenta en 1 unidad, manteniendo todas las otras variables constantes (ceteris paribus).</p><h3>Ejemplo</h3><p>En un modelo de predicción de precio de casa:</p><pre><code>Precio = 50000 + 200*Area + 5000*Habitaciones - 100*Edad</code></pre><p>Por cada m² adicional, el precio sube $200. Por cada habitación, sube $5000. Por cada año de antigüedad, baja $100.</p><h3>Estandarización</h3><p>Para comparar importancia relativa, es mejor estandarizar variables (z-score) antes de ajustar el modelo.</p>','https://www.youtube.com/embed/nk2CQITm_eo','youtube',28,2,0),
(38,'Proyecto 1: Predicción de Precios de Casas','<h2>Dataset: Housing Prices</h2><p>Construiremos un modelo que predice precios de casas basado en características como área, ubicación, antigüedad, etc.</p><p>Pasos:</p><ol><li>Cargar el dataset Boston Housing o similar.</li><li>Explorar datos: correlaciones, distribuciones.</li><li>Dividir en train/test.</li><li>Entrenar regresión múltiple.</li><li>Evaluar con R² y RMSE.</li></ol>','','none',45,3,0);

-- MODULO 39: Validación y Evaluación (module_id=39)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(39,'Métricas de Evaluación para Regresión','<h2>¿Cómo Saber si el Modelo es Bueno?</h2><p>No existe una única métrica perfecta. Debemos entender cada una:</p><ul><li><strong>MAE (Error Absoluto Medio):</strong> Promedio del error absoluto. Interpretable en unidades originales.</li><li><strong>MSE (Error Cuadrático Medio):</strong> Promedio del error al cuadrado. Penaliza más errores grandes.</li><li><strong>RMSE (Raíz del Error Cuadrático Medio):</strong> Raíz de MSE. En unidades originales.</li><li><strong>R² (Coeficiente de Determinación):</strong> Proporción de varianza explicada (0-1). 1 es perfecto.</li><li><strong>R² Ajustado:</strong> R² penalizado por número de variables (evita sobre-ajuste).</li></ul>','','none',32,1,0),
(39,'Train-Test Split y Validación Cruzada','<h2>Evitar Engañarse a Uno Mismo</h2><p>Un error común es evaluar en el mismo conjunto de datos con el que entrenamos. El modelo memoriza.</p><p><strong>Solución:</strong> Dividir datos en entrenamiento (70-80%) y prueba (20-30%).</p><p><strong>Mejor aún:</strong> Usar k-fold cross-validation para estimación más robusta del rendimiento.</p><pre><code>from sklearn.model_selection import cross_val_score\nscores = cross_val_score(model, X, y, cv=5, scoring=\'r2\')\nprint(f"R² promedio: {scores.mean():.3f} (+/- {scores.std():.3f})\")</code></pre>','https://www.youtube.com/embed/fSytzGwwBVw','youtube',30,2,0),
(39,'Diagnósticos de Residuos','<h2>¿Está el Modelo Cumpliendo sus Supuestos?</h2><p>Los residuos (errores) deben ser aleatorios y normalmente distribuidos. Gráficos clave:</p><ul><li><strong>Q-Q Plot:</strong> Residuos vs distribución normal.</li><li><strong>Residuos vs Valores Ajustados:</strong> Debe ser patrón aleatorio (no cónico).</li><li><strong>Scale-Location:</strong> Homocedasticidad.</li></ul>','','none',28,3,0);

-- MODULO 40: Regularización (module_id=40)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(40,'El Problema del Overfitting','<h2>¿Cuándo el Modelo Memoriza?</h2><p>Con muchas variables relativas a datos, el modelo puede memorizar ruido en lugar de patrones reales. El overfitting generalmente tiene alto R² en entrenamiento pero bajo en prueba.</p><p><strong>Síntomas:</strong> Diferencia grande entre R² train vs test, coeficientes muy grandes.</p>','','none',25,1,0),
(40,'Ridge Regression (L2 Regularization)','<h2>Penalizar Coeficientes Grandes</h2><p>Ridge añade un término de penalización a la suma de cuadrados de los coeficientes:</p><pre><code>RSS + λ Σ(β_i²)</code></pre><p>Parámetro λ (alpha en sklearn) controla la fuerza de regularización:</p><pre><code>from sklearn.linear_model import Ridge\nmodel = Ridge(alpha=1.0)  # Aumentar alpha aumenta penalización\nmodel.fit(X_train, y_train)</code></pre>','https://www.youtube.com/embed/1dKRdX9bfIo','youtube',30,2,0),
(40,'Lasso y Elastic Net: Selección de Variables','<h2>L1 y Combinaciones de Regularización</h2><p><strong>Lasso (L1):</strong> Penaliza el valor absoluto de coeficientes. Puede hacer algunos coeficientes exactamente cero (selección automática de variables).</p><p><strong>Elastic Net:</strong> Combina Ridge y Lasso.</p><pre><code>from sklearn.linear_model import Lasso, ElasticNet\nlasso = Lasso(alpha=0.1)\nelastic = ElasticNet(alpha=0.1, l1_ratio=0.5)</code></pre>','','none',32,3,0);

-- MODULO 41: Supuestos y Diagnósticos (module_id=41)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(41,'Supuestos de la Regresión Lineal','<h2>Las Premisas Básicas</h2><p>La regresión lineal asume:</p><ol><li><strong>Linealidad:</strong> La relación entre X e y es lineal.</li><li><strong>Independencia:</strong> Las observaciones son independientes.</li><li><strong>Normalidad:</strong> Los residuos siguen distribución normal.</li><li><strong>Homocedasticidad:</strong> Varianza constante de residuos.</li><li><strong>No multicolinealidad:</strong> Variables independientes no están correlacionadas.</li></ol><p>Si se violan estos supuestos, nuestro modelo puede ser incorrecto o ineficiente.</p>','','none',28,1,0),
(41,'Multicolinealidad y Correlación','<h2>Cuando X\'s se Correlacionan entre Sí</h2><p>Si dos variables independientes están muy correlacionadas, es difícil separar su efecto individual. El VIF (Variance Inflation Factor) mide esto:</p><pre><code>VIF < 5: Sin multicolinealidad\nVIF 5-10: Moderada\nVIF > 10: Problema serio</code></pre>','https://www.youtube.com/embed/Esm2zvsQlh0','youtube',25,2,0),
(41,'Detección y Tratamiento de Outliers','<h2>¿Qué Hacer con Valores Extremos?</h2><p>Outliers pueden distorsionar el modelo. Opciones:</p><ul><li>Investigar: ¿Es un error? ¿Es genuino?</li><li>Eliminar si es claramente un error.</li><li>Usar regresión robusta (Huber, RANSACRegressor).</li><li>Transformar variables (log, raíz cuadrada).</li></ul>','','none',30,3,0);

-- MODULO 42: Regresión Polinomial (module_id=42)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(42,'Extensiones No Lineales','<h2>Cuando la Recta No es Suficiente</h2><p>A veces la relación es curva, no lineal. La regresión polinomial añade términos potencia:</p><pre><code>y = β₀ + β₁x + β₂x² + β₃x³ + ...</code></pre><p>Esto sigue siendo "lineal" en los parámetros (es regresión lineal con features transformadas).</p>','','none',28,1,0),
(42,'Feature Engineering para Regresión','<h2>Creando Nuevas Características</h2><p>Podemos crear características polinomiales automáticamente:</p><pre><code>from sklearn.preprocessing import PolynomialFeatures\npoly = PolynomialFeatures(degree=2)\nX_poly = poly.fit_transform(X)\nmodel.fit(X_poly, y)</code></pre><p>¡Pero cuidado con el overfitting! Validar siempre con cross-validation.</p>','https://www.youtube.com/embed/neXJc-_f5U4','youtube',32,2,0),
(42,'Proyecto 2: Predicción de Demanda','<h2>Dataset: Predicción de Demanda de Energía</h2><p>Usaremos regresión polinomial para predecir demanda de energía basado en temperatura, hora del día, etc.</p>','','none',40,3,0);

-- MODULO 43: Series Temporales (module_id=43)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(43,'Características de Datos Temporales','<h2>Cuando el Tiempo Importa</h2><p>Los datos de series temporales tienen autocorrelación: observaciones cercanas en tiempo están relacionadas. Esto viola la independencia que asume regresión lineal.</p><p>Pero podemos usar características derivadas del tiempo.</p>','','none',30,1,0),
(43,'Tendencias y Estacionalidad','<h2>Descomposición de Series Temporales</h2><p>Una serie temporal puede descomponerse en:</p><ul><li><strong>Tendencia:</strong> Movimiento de largo plazo.</li><li><strong>Estacionalidad:</strong> Patrones repetitivos (diario, semanal, anual).</li><li><strong>Residual:</strong> Ruido aleatorio.</li></ul>','https://www.youtube.com/embed/e8Yw4alG16Q','youtube',28,2,0),
(43,'Proyecto 3: Predicción de Ventas Mensuales','<h2>Dataset: Ventas Históricas</h2><p>Predecir ventas futuras usando regresión con variables temporales: mes, estación, tendencia.</p>','','none',45,3,0);

-- MODULO 44: Proyecto Final (module_id=44)
INSERT INTO `lessons` (`module_id`,`title`,`content`,`video_url`,`video_type`,`duration_minutes`,`sort_order`,`is_free`) VALUES
(44,'Integración de Conceptos','<h2>Construyendo un Sistema Predictivo Profesional</h2><p>Integraremos todo lo aprendido en un pipeline completo:</p><ol><li>Carga y exploración de datos.</li><li>Limpieza y ingeniería de características.</li><li>Entrenamiento con validación cruzada.</li><li>Selección de modelo (Ridge vs Lasso vs ElasticNet).</li><li>Evaluación final en test set.</li></ol>','','none',50,1,0),
(44,'Despliegue en Producción','<h2>De Jupyter a Producción</h2><p>Guardar el modelo entrenado y crear una API simple con Flask para servir predicciones.</p>','https://www.youtube.com/embed/BS8uS-4NcUY','youtube',40,2,0),
(44,'Portfolio: Presentando tu Trabajo','<h2>Documentación y Presentación Profesional</h2><p>Cómo documentar, visualizar resultados y presentar tu proyecto de forma impactante para impresionar a empleadores y clientes.</p>','','none',35,3,0);

-- ============================================================
-- ACTUALIZAR CONTEO DE LECCIONES PARA EL NUEVO CURSO
-- ============================================================
UPDATE courses SET total_lessons = 27 WHERE id = 9;
