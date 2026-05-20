<?php
$pdo = new PDO('mysql:host=localhost;dbname=courses_db', 'root', '123456789');
$pdo->exec("ALTER TABLE enrollments 
    ADD COLUMN knowledge_level VARCHAR(50) NULL AFTER motivation,
    ADD COLUMN weekly_hours VARCHAR(20) NULL AFTER knowledge_level,
    ADD COLUMN main_goal VARCHAR(100) NULL AFTER weekly_hours");
echo "Campos adicionales (knowledge_level, weekly_hours, main_goal) añadidos a enrollments.\n";
?>
