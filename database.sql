-- ====================================================================
-- SCRIPT DE BASE DE DATOS COMPATIBLE CON MYSQL (HOSTINGER)
-- Generado automáticamente para reemplazar la estructura de Supabase (PostgreSQL)
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar tablas existentes si es necesario para una reinstalación limpia
DROP TABLE IF EXISTS progreso_usuarios;
DROP TABLE IF EXISTS materias;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

-- ====================================================================
-- 1. TABLA DE USUARIOS (Perfiles)
-- ====================================================================
CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
rol VARCHAR(20) NOT NULL CHECK (rol IN ('alumno', 'profesor')),
nombre_completo VARCHAR(255) NOT NULL,
correo VARCHAR(255) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
avatar TEXT,
fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- 2. TABLA DE MATERIAS Y CONTENIDO EDUCATIVO
-- ====================================================================
CREATE TABLE materias (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(255) NOT NULL,
descripcion TEXT,
nivel VARCHAR(50),
fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- 3. TABLA DE PROGRESO DE ALUMNOS Y MÉTRICAS
-- ====================================================================
CREATE TABLE progreso_usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
usuario_id INT NOT NULL,
materia_id INT NOT NULL,
estado VARCHAR(50) DEFAULT 'en_progreso',
porcentaje INT DEFAULT 0,
ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;