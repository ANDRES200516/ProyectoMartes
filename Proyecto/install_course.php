#!/usr/bin/env php
<?php
/**
 * Script para instalar el Curso de Regresión Lineal
 * 
 * Uso desde terminal:
 * php install_course.php
 * 
 * Requisitos:
 * - PHP CLI
 * - MySQL/MariaDB instalado y corriendo
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║     🎓 INSTALADOR DE CURSO: REGRESIÓN LINEAL             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Cargar configuración de BD
require_once __DIR__ . '/config/Database.php';

try {
    echo "📦 Conectando a la base de datos...\n";
    $db = new Config\Database();
    $conn = $db->getConnection();
    
    if (!$conn) {
        throw new Exception("No se pudo conectar a la base de datos");
    }
    
    echo "✅ Conexión exitosa a courses_db\n\n";
    
    // Leer el archivo SQL
    $sql_file = __DIR__ . '/add_regression_course.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("Archivo no encontrado: $sql_file");
    }
    
    echo "📄 Leyendo archivo SQL...\n";
    $content = file_get_contents($sql_file);
    
    // Dividir por punto y coma
    $statements = array_filter(
        array_map('trim', explode(';', $content)),
        function($s) { return !empty($s) && strpos($s, '--') !== 0; }
    );
    
    echo "   Total de sentencias: " . count($statements) . "\n\n";
    
    // Ejecutar
    $executed = 0;
    $errors = [];
    
    foreach ($statements as $idx => $stmt) {
        try {
            $conn->exec($stmt);
            $executed++;
            
            // Mostrar progreso
            if ($idx % 5 == 0) {
                echo ".";
                flush();
            }
        } catch (Exception $e) {
            $errors[] = [
                'stmt' => substr($stmt, 0, 80),
                'error' => $e->getMessage()
            ];
        }
    }
    
    echo "\n\n";
    echo "✅ Ejecutadas " . $executed . " sentencias SQL\n";
    
    if (!empty($errors)) {
        echo "⚠️  Errores detectados: " . count($errors) . "\n";
        foreach ($errors as $err) {
            echo "   - " . $err['stmt'] . "...\n";
            echo "     → " . $err['error'] . "\n";
        }
    }
    
    // Verificar instalación
    echo "\n📊 Verificando instalación...\n";
    
    $course = $conn->query(
        "SELECT id, title, level, duration_hours FROM courses WHERE slug = 'regresion-lineal' LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);
    
    if (!$course) {
        throw new Exception("El curso no fue insertado correctamente");
    }
    
    echo "✅ Curso instalado:\n";
    echo "   ID: " . $course['id'] . "\n";
    echo "   Título: " . $course['title'] . "\n";
    echo "   Nivel: " . $course['level'] . "\n";
    echo "   Duración: " . $course['duration_hours'] . " horas\n\n";
    
    $modules = $conn->query(
        "SELECT COUNT(*) as total FROM modules WHERE course_id = " . $course['id']
    )->fetch(PDO::FETCH_ASSOC);
    echo "✅ Módulos: " . $modules['total'] . "\n";
    
    $lessons = $conn->query(
        "SELECT COUNT(*) as total FROM lessons WHERE module_id IN (
            SELECT id FROM modules WHERE course_id = " . $course['id'] . "
        )"
    )->fetch(PDO::FETCH_ASSOC);
    echo "✅ Lecciones: " . $lessons['total'] . "\n\n";
    
    echo "╔════════════════════════════════════════════════════════════╗\n";
    echo "║  ✨ ¡INSTALACIÓN COMPLETADA EXITOSAMENTE!                ║\n";
    echo "╚════════════════════════════════════════════════════════════╝\n\n";
    
    echo "🌐 Acceder al curso:\n";
    echo "   Admin: http://localhost:8000/index.php?action=admin_courses\n";
    echo "   Catálogo: http://localhost:8000/index.php?action=courses\n\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n\n";
    exit(1);
}

exit(0);
?>
