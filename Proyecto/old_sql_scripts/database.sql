CREATE DATABASE IF NOT EXISTS courses_db;
USE courses_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) DEFAULT '',
    phone VARCHAR(20) DEFAULT '',
    photo VARCHAR(255) DEFAULT '',
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar un administrador por defecto (password: admin123)
INSERT INTO users (username, email, password, role, status) VALUES 
('admin', 'admin@admin.com', '$2y$10$z/dNqJXgpEtbizfiNHr30uYgBmlV8PC4fdIt65Qy0Rir61lOH2bcy', 'admin', 'approved')
ON DUPLICATE KEY UPDATE password = VALUES(password), role = VALUES(role), status = VALUES(status);