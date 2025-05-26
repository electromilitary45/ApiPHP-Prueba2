-- MYSQL DATABASE SCRIPT
CREATE DATABASE IF NOT EXISTS bdPrueba;

CREATE TABLE IF NOT EXISTS avii.users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    profesion VARCHAR(100),
    telefono VARCHAR(20),
    edad INT,
    peso DECIMAL(5,2),
    estatura DECIMAL(4,2)
);

CREATE TABLE IF NOT EXISTS avii.categorias_recetas (
    categoriaId INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS avii.recetas (
    recetaId INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    ingredientes TEXT NOT NULL,
    instrucciones TEXT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    categoriaId INT,
    fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    diaRecomendado VARCHAR(20),
    calorias INT,
    FOREIGN KEY (categoriaId) REFERENCES avii.categorias_recetas(categoriaId)
);