<?php
$pdo = new PDO('mysql:host=localhost;dbname=courses_db', 'root', '123456789');
$pdo->exec("ALTER TABLE enrollments ADD COLUMN motivation TEXT NULL AFTER course_id");
echo "Columna motivation añadida a enrollments.\n";
?>
