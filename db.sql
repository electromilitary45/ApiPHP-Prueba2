CREATE DATABASE IF NOT EXISTS bdPrueba;

CREATE TABLE IF NOT EXISTS bdPrueba.users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    profesion VARCHAR(100),
    telefono VARCHAR(20),
    edad INT,
    peso DECIMAL(5,2),
    estatura DECIMAL(4,2)
);