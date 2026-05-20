<?php
$pdo = new PDO('mysql:host=localhost;dbname=courses_db', 'root', '123456789');
$pdo->exec("ALTER TABLE enrollments ADD COLUMN status ENUM('active', 'completed') DEFAULT 'active' AFTER main_goal");
echo "Columna status añadida a enrollments.\n";
?>
