<?php
/**
 * Script para instalar el curso de Regresión Lineal
 * Ejecutar desde línea de comandos: php install_regression_course.php
 */

require_once __DIR__ . '/config/Database.php';

try {
    $db = new Config\Database();
    $conn = $db->getConnection();
    
    if (!$conn) {
        throw new Exception("No se pudo conectar a la base de datos");
    }
    
    echo "✓ Conectado a la base de datos courses_db\n\n";
    
    // Leer el archivo SQL
    $sql_file = __DIR__ . '/add_regression_course.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("Archivo SQL no encontrado: $sql_file");
    }
    
    $sql = file_get_contents($sql_file);
    
    // Dividir por punto y coma y ejecutar cada statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $executed = 0;
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $conn->exec($statement);
            $executed++;
        } catch (Exception $e) {
            echo "⚠ Error en sentencia: " . substr($statement, 0, 50) . "...\n";
            echo "  Detalles: " . $e->getMessage() . "\n";
        }
    }
    
    echo "✓ $executed sentencias SQL ejecutadas correctamente\n";
    
    // Verificar que el curso fue insertado
    $check = $conn->query("SELECT COUNT(*) as total FROM courses WHERE slug = 'regresion-lineal'")->fetch(PDO::FETCH_ASSOC);
    
    if ($check['total'] > 0) {
        echo "✓ Curso de Regresión Lineal insertado correctamente\n";
        
        // Obtener información del curso
        $course = $conn->query("SELECT id, title, level, duration_hours FROM courses WHERE slug = 'regresion-lineal'")->fetch(PDO::FETCH_ASSOC);
        echo "\nDetalles del curso:\n";
        echo "  ID: " . $course['id'] . "\n";
        echo "  Título: " . $course['title'] . "\n";
        echo "  Nivel: " . $course['level'] . "\n";
        echo "  Duración: " . $course['duration_hours'] . " horas\n";
        
        // Contar módulos
        $modules = $conn->query("SELECT COUNT(*) as total FROM modules WHERE course_id = " . $course['id'])->fetch(PDO::FETCH_ASSOC);
        echo "  Módulos: " . $modules['total'] . "\n";
        
        // Contar lecciones
        $lessons = $conn->query("SELECT COUNT(*) as total FROM lessons WHERE module_id IN (SELECT id FROM modules WHERE course_id = " . $course['id'] . ")")->fetch(PDO::FETCH_ASSOC);
        echo "  Lecciones: " . $lessons['total'] . "\n";
        
        echo "\n✅ ¡Instalación completada exitosamente!\n";
    } else {
        echo "❌ Error: El curso no fue insertado\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
